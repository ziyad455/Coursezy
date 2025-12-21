<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseContentController extends Controller
{
    /**
     * Get course overview with sections (metadata only)
     */
    public function getCourseOverview($courseId)
    {
        $course = Course::with(['coach:id,name,email', 'category:id,name'])
            ->findOrFail($courseId);

        // Check if user is enrolled or if it's a preview request
        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $courseId)
                ->exists();
        }

        // Get sections with lesson count and total duration
        $sections = $course->publishedSections()
            ->select('id', 'title', 'description', 'order')
            ->withCount('lessons')
            ->with(['lessons' => function ($query) use ($isEnrolled) {
                $baseQuery = $query->select('id', 'section_id', 'title', 'duration', 'type', 'order', 'is_preview')
                    ->where('is_published', true)
                    ->orderBy('order');
                
                // Only show preview lessons to non-enrolled users
                if (!$isEnrolled) {
                    $baseQuery->where('is_preview', true);
                }
                
                return $baseQuery;
            }])
            ->get();

        // Calculate course statistics
        $totalLessons = $sections->sum('lessons_count');
        $totalDuration = $sections->flatMap->lessons->sum('duration');

        return response()->json([
            'success' => true,
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'price' => $course->price,
                'thumbnail' => $course->thumbnail,
                'coach' => $course->coach,
                'category' => $course->category,
                'total_lessons' => $totalLessons,
                'total_duration' => $totalDuration,
                'formatted_duration' => $this->formatDuration($totalDuration),
                'is_enrolled' => $isEnrolled
            ],
            'sections' => $sections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'title' => $section->title,
                    'description' => $section->description,
                    'lessons_count' => $section->lessons_count,
                    'lessons' => $section->lessons->map(function ($lesson) {
                        return [
                            'id' => $lesson->id,
                            'title' => $lesson->title,
                            'type' => $lesson->type,
                            'duration' => $lesson->duration,
                            'formatted_duration' => $lesson->formatted_duration,
                            'is_preview' => $lesson->is_preview,
                            'order' => $lesson->order
                        ];
                    })
                ];
            })
        ]);
    }

    /**
     * Get specific section with lessons (metadata only)
     */
    public function getSection($courseId, $sectionId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check enrollment
        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $courseId)
                ->exists();
        }

        $section = $course->publishedSections()
            ->with(['lessons' => function ($query) use ($isEnrolled) {
                $baseQuery = $query->where('is_published', true)
                    ->orderBy('order');
                
                if (!$isEnrolled) {
                    $baseQuery->where('is_preview', true);
                }
                
                return $baseQuery;
            }])
            ->findOrFail($sectionId);

        return response()->json([
            'success' => true,
            'section' => $section,
            'is_enrolled' => $isEnrolled
        ]);
    }

    /**
     * Get lesson content (video URL fetched on-demand)
     */
    public function getLesson($courseId, $sectionId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $section = $course->publishedSections()->findOrFail($sectionId);
        $lesson = $section->lessons()
            ->where('is_published', true)
            ->findOrFail($lessonId);

        // Check if user has access to this lesson
        $hasAccess = false;
        $user = Auth::user();
        
        if ($lesson->is_preview) {
            // Preview lessons are accessible to everyone
            $hasAccess = true;
        } elseif ($user) {
            // Check if user is enrolled
            $hasAccess = Enrollment::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->exists();
        }

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'You need to be enrolled in this course to access this lesson'
            ], 403);
        }

        // Get next and previous lessons
        $nextLesson = $section->lessons()
            ->where('is_published', true)
            ->where('order', '>', $lesson->order)
            ->orderBy('order')
            ->first(['id', 'title']);

        $previousLesson = $section->lessons()
            ->where('is_published', true)
            ->where('order', '<', $lesson->order)
            ->orderByDesc('order')
            ->first(['id', 'title']);

        // If no next lesson in current section, check next section
        if (!$nextLesson) {
            $nextSection = $course->publishedSections()
                ->where('order', '>', $section->order)
                ->orderBy('order')
                ->first();
            
            if ($nextSection) {
                $nextLesson = $nextSection->lessons()
                    ->where('is_published', true)
                    ->orderBy('order')
                    ->first(['id', 'title', 'section_id']);
            }
        }

        // Prepare lesson data with content URL
        $lessonData = [
            'id' => $lesson->id,
            'title' => $lesson->title,
            'description' => $lesson->description,
            'type' => $lesson->type,
            'duration' => $lesson->duration,
            'formatted_duration' => $lesson->formatted_duration,
            'is_preview' => $lesson->is_preview,
            'next_lesson' => $nextLesson,
            'previous_lesson' => $previousLesson
        ];

        // Include content based on type
        switch ($lesson->type) {
            case 'video':
                $lessonData['video_url'] = $lesson->video_url;
                break;
            case 'text':
                $lessonData['content'] = $lesson->content;
                break;
            case 'pdf':
                $lessonData['file_url'] = $lesson->file_url;
                break;
        }

        return response()->json([
            'success' => true,
            'lesson' => $lessonData,
            'section' => [
                'id' => $section->id,
                'title' => $section->title
            ],
            'course' => [
                'id' => $course->id,
                'title' => $course->title
            ]
        ]);
    }

    /**
     * Get all preview lessons for a course
     */
    public function getPreviewLessons($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        $previewLessons = Lesson::whereHas('section', function ($query) use ($courseId) {
                $query->where('course_id', $courseId)
                    ->where('is_published', true);
            })
            ->where('is_preview', true)
            ->where('is_published', true)
            ->with('section:id,title')
            ->select('id', 'section_id', 'title', 'type', 'duration')
            ->get();

        return response()->json([
            'success' => true,
            'course' => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description
            ],
            'preview_lessons' => $previewLessons
        ]);
    }

    /**
     * Format duration from seconds to human readable format
     */
    private function formatDuration($seconds)
    {
        if (!$seconds) {
            return null;
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0) {
            return sprintf('%d hours %d minutes', $hours, $minutes);
        }

        return sprintf('%d minutes', $minutes);
    }
}
