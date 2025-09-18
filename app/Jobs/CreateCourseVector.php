<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Course;

class CreateCourseVector implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The course instance.
     */
    protected Course $course;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
        // Set delay between retries (exponential backoff)
        $this->backoff = [30, 60, 120, 300, 600]; // 30s, 1m, 2m, 5m, 10m
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::timeout(30)->post('http://127.0.0.1:5500/create_vector', [
                'id' => $this->course->id,
                'description' => $this->course->description
            ]);

            if ($response->successful()) {
                Log::info('Vector created successfully for course in background job', [
                    'course_id' => $this->course->id,
                    'attempt' => $this->attempts()
                ]);
            } else {
                $statusCode = $response->status();
                $responseBody = $response->body();
                
                Log::warning('Vector creation failed in background job', [
                    'course_id' => $this->course->id,
                    'status_code' => $statusCode,
                    'response_body' => $responseBody,
                    'attempt' => $this->attempts()
                ]);

                // If it's a rate limit error, retry with exponential backoff
                if ($statusCode === 429) {
                    $this->fail(new \Exception("Rate limit exceeded: {$responseBody}"));
                    return;
                }

                // For other errors, still retry
                throw new \Exception("HTTP {$statusCode}: {$responseBody}");
            }

        } catch (\Exception $e) {
            Log::error('Exception in CreateCourseVector job', [
                'course_id' => $this->course->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts()
            ]);

            // Check if this is the final attempt
            if ($this->attempts() >= $this->tries) {
                Log::error('CreateCourseVector job failed after all retries', [
                    'course_id' => $this->course->id,
                    'final_error' => $e->getMessage()
                ]);
                return;
            }

            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('CreateCourseVector job finally failed', [
            'course_id' => $this->course->id,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        return $this->backoff;
    }
}