<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Services\FirebaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    protected $firebaseStorage;

    public function __construct(FirebaseStorageService $firebaseStorage)
    {
        $this->firebaseStorage = $firebaseStorage;
    }

    /**
     * Display a listing of the resource.
     */
    public function index($courseId, $sectionId)
    {
        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()->findOrFail($sectionId);

        $lessons = $section->lessons()
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'lessons' => $lessons
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $courseId, $sectionId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,quiz,pdf',
            'video' => 'required_if:type,video|file|mimes:mp4,avi,mov,wmv|max:2048000', // Max 2GB
            'content' => 'required_if:type,text|string',
            'file' => 'required_if:type,pdf|file|mimes:pdf|max:50000', // Max 50MB
            'is_preview' => 'boolean',
            'is_published' => 'boolean'
        ]);

        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()->findOrFail($sectionId);

        $lesson = DB::transaction(function () use ($request, $section) {
            $lessonData = [
                'section_id' => $section->id,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'order' => $request->order ?? Lesson::getNextOrder($section->id),
                'is_preview' => $request->is_preview ?? false,
                'is_published' => $request->is_published ?? true,
            ];

            // Handle video upload
            if ($request->type === 'video' && $request->hasFile('video')) {
                $uploadResult = $this->firebaseStorage->uploadVideo(
                    $request->file('video'),
                    'courses/' . $section->course_id . '/videos'
                );

                if (!$uploadResult['success']) {
                    throw new \Exception($uploadResult['message']);
                }

                $lessonData['video_url'] = $uploadResult['url'];
                $lessonData['duration'] = $uploadResult['duration'];
                $lessonData['metadata'] = [
                    'size' => $uploadResult['size'],
                    'mime_type' => $uploadResult['mime_type'],
                    'original_name' => $uploadResult['original_name'],
                    'storage_path' => $uploadResult['path']
                ];
            }

            // Handle text content
            if ($request->type === 'text') {
                $lessonData['content'] = $request->content;
            }

            // Handle PDF upload
            if ($request->type === 'pdf' && $request->hasFile('file')) {
                $uploadResult = $this->firebaseStorage->uploadVideo(
                    $request->file('file'),
                    'courses/' . $section->course_id . '/pdfs'
                );

                if (!$uploadResult['success']) {
                    throw new \Exception($uploadResult['message']);
                }

                $lessonData['file_url'] = $uploadResult['url'];
                $lessonData['metadata'] = [
                    'size' => $uploadResult['size'],
                    'mime_type' => $uploadResult['mime_type'],
                    'original_name' => $uploadResult['original_name'],
                    'storage_path' => $uploadResult['path']
                ];
            }

            return Lesson::create($lessonData);
        });

        return response()->json([
            'success' => true,
            'message' => 'Lesson created successfully',
            'lesson' => $lesson
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($courseId, $sectionId, $lessonId)
    {
        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()->findOrFail($sectionId);
        $lesson = $section->lessons()->findOrFail($lessonId);

        return response()->json([
            'success' => true,
            'lesson' => $lesson
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $courseId, $sectionId, $lessonId)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'sometimes|file|mimes:mp4,avi,mov,wmv|max:2048000',
            'content' => 'sometimes|string',
            'file' => 'sometimes|file|mimes:pdf|max:50000',
            'order' => 'sometimes|integer|min:0',
            'is_preview' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean'
        ]);

        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()->findOrFail($sectionId);
        $lesson = $section->lessons()->findOrFail($lessonId);

        $updateData = $request->only([
            'title', 'description', 'order', 'is_preview', 'is_published'
        ]);

        // Handle new video upload
        if ($request->hasFile('video')) {
            // Delete old video if exists
            if ($lesson->metadata && isset($lesson->metadata['storage_path'])) {
                $this->firebaseStorage->deleteFile($lesson->metadata['storage_path']);
            }

            $uploadResult = $this->firebaseStorage->uploadVideo(
                $request->file('video'),
                'courses/' . $courseId . '/videos'
            );

            if ($uploadResult['success']) {
                $updateData['video_url'] = $uploadResult['url'];
                $updateData['duration'] = $uploadResult['duration'];
                $metadata = $lesson->metadata ?? [];
                $metadata = array_merge($metadata, [
                    'size' => $uploadResult['size'],
                    'mime_type' => $uploadResult['mime_type'],
                    'original_name' => $uploadResult['original_name'],
                    'storage_path' => $uploadResult['path']
                ]);
                $updateData['metadata'] = $metadata;
            }
        }

        // Handle text content update
        if ($request->has('content')) {
            $updateData['content'] = $request->content;
        }

        $lesson->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Lesson updated successfully',
            'lesson' => $lesson
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseId, $sectionId, $lessonId)
    {
        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()->findOrFail($sectionId);
        $lesson = $section->lessons()->findOrFail($lessonId);

        // Delete video from Firebase if exists
        if ($lesson->metadata && isset($lesson->metadata['storage_path'])) {
            $this->firebaseStorage->deleteFile($lesson->metadata['storage_path']);
        }

        $lesson->delete();

        // Reorder remaining lessons
        $section->lessons()
            ->where('order', '>', $lesson->order)
            ->decrement('order');

        return response()->json([
            'success' => true,
            'message' => 'Lesson deleted successfully'
        ]);
    }

    /**
     * Update lessons order
     */
    public function updateOrder(Request $request, $courseId, $sectionId)
    {
        $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order' => 'required|integer|min:0'
        ]);

        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()->findOrFail($sectionId);

        DB::transaction(function () use ($request, $section) {
            foreach ($request->lessons as $lessonData) {
                $section->lessons()
                    ->where('id', $lessonData['id'])
                    ->update(['order' => $lessonData['order']]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Lesson order updated successfully'
        ]);
    }

    /**
     * Upload video and return URL without creating lesson
     */
    public function uploadVideo(Request $request, $courseId)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,avi,mov,wmv|max:2048000'
        ]);

        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $uploadResult = $this->firebaseStorage->uploadVideo(
            $request->file('video'),
            'courses/' . $courseId . '/videos'
        );

        if (!$uploadResult['success']) {
            return response()->json([
                'success' => false,
                'message' => $uploadResult['message']
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Video uploaded successfully',
            'data' => $uploadResult
        ]);
    }
}
