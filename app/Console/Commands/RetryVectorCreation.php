<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Jobs\CreateCourseVector;
use Illuminate\Support\Facades\Http;

class RetryVectorCreation extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'vectors:retry 
                            {--course-id= : Specific course ID to retry}
                            {--all : Retry all courses without vectors}
                            {--check : Check vector status for courses}';

    /**
     * The console command description.
     */
    protected $description = 'Retry vector creation for courses that failed or check vector status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('check')) {
            return $this->checkVectorStatus();
        }

        if ($this->option('all')) {
            return $this->retryAllCourses();
        }

        if ($courseId = $this->option('course-id')) {
            return $this->retrySingleCourse($courseId);
        }

        $this->error('Please specify --all, --course-id=X, or --check option');
        return Command::FAILURE;
    }

    private function checkVectorStatus()
    {
        $this->info('Checking vector status for recent courses...');

        $courses = Course::select('id', 'title', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        if ($courses->isEmpty()) {
            $this->info('No courses found.');
            return Command::SUCCESS;
        }

        $this->table(
            ['Course ID', 'Title', 'Created At', 'Vector Status'],
            $courses->map(function ($course) {
                $vectorExists = $this->checkVectorExists($course->id);
                return [
                    $course->id,
                    substr($course->title, 0, 50) . (strlen($course->title) > 50 ? '...' : ''),
                    $course->created_at->format('Y-m-d H:i:s'),
                    $vectorExists ? '✅ Exists' : '❌ Missing'
                ];
            })->toArray()
        );

        return Command::SUCCESS;
    }

    private function retryAllCourses()
    {
        $this->info('Finding courses without vectors...');

        $courses = Course::select('id', 'title', 'description', 'created_at')
            ->whereNotNull('description')
            ->get();

        if ($courses->isEmpty()) {
            $this->info('No courses found.');
            return Command::SUCCESS;
        }

        $coursesWithoutVectors = $courses->filter(function ($course) {
            return !$this->checkVectorExists($course->id);
        });

        if ($coursesWithoutVectors->isEmpty()) {
            $this->info('All courses already have vectors.');
            return Command::SUCCESS;
        }

        $this->info("Found {$coursesWithoutVectors->count()} courses without vectors.");

        if (!$this->confirm('Do you want to retry vector creation for these courses?')) {
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($coursesWithoutVectors->count());
        $bar->start();

        foreach ($coursesWithoutVectors as $course) {
            CreateCourseVector::dispatch($course)
                ->delay(now()->addSeconds(rand(5, 30))); // Random delay to avoid rate limits
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Dispatched {$coursesWithoutVectors->count()} vector creation jobs.");

        return Command::SUCCESS;
    }

    private function retrySingleCourse($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            $this->error("Course with ID {$courseId} not found.");
            return Command::FAILURE;
        }

        if (!$course->description) {
            $this->error("Course {$courseId} has no description to create vector from.");
            return Command::FAILURE;
        }

        $this->info("Retrying vector creation for course: {$course->title}");

        CreateCourseVector::dispatch($course)->delay(now()->addSeconds(5));

        $this->info('Vector creation job dispatched.');
        return Command::SUCCESS;
    }

    private function checkVectorExists($courseId): bool
    {
        try {
            $response = Http::timeout(10)->get('http://127.0.0.1:5500/search_similar', [
                'description' => "test query for course {$courseId}"
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['similar_descriptions_ids']) && 
                       in_array((string)$courseId, $data['similar_descriptions_ids']);
            }

            return false;
        } catch (\Exception $e) {
            $this->warn("Could not check vector for course {$courseId}: " . $e->getMessage());
            return false;
        }
    }
}