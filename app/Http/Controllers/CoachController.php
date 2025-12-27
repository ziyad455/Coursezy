<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Course;
use App\Models\Activity;
use App\Models\Enrollment;
use App\Models\Rating;
use App\Models\Section;
use App\Models\Lesson;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class CoachController extends BaseController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user->role !== 'coach') {
                return redirect()->route('dashboard')->with('error', 'Access denied. Coach role required.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $coachId = $user->id;

        // Get all courses for this coach
        $courses = Course::where('coach_id', $coachId)->get();
        $courseIds = $courses->pluck('id');

        // Statistics
        $totalCourses = $courses->count();

        // Total students (enrollments)
        $totalStudents = Enrollment::whereIn('course_id', $courseIds)->count();

        // Total revenue (sum of all payments)
        $totalRevenue = Activity::where('coach_id', $coachId)
            ->where('type', Activity::TYPE_PAYMENT)
            ->sum('amount');

        // Average rating
        $avgRating = Rating::whereIn('course_id', $courseIds)->avg('rating') ?? 0;
        $avgRating = round($avgRating, 1);

        // Course clicks/views (last 30 days)
        $courseClicks = Activity::where('coach_id', $coachId)
            ->where('type', Activity::TYPE_COURSE_CLICK)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Recent activities (last 10)
        $recentActivities = Activity::where('coach_id', $coachId)
            ->with(['user', 'student', 'course'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Mark activities as read
        Activity::where('coach_id', $coachId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('coach.dashboard', compact(
            'user',
            'totalCourses',
            'totalStudents',
            'totalRevenue',
            'avgRating',
            'courseClicks',
            'recentActivities'
        ));
    }

    public function inbox()
    {
        $userId = Auth::id();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // get all unique user IDs who have chatted with me
        $chatUserIds = Message::where('sender_id', $userId)
            ->pluck('receiver_id')
            ->merge(
                Message::where('receiver_id', $userId)->pluck('sender_id')
            )
            ->unique();

        // fetch the users
        $users = User::whereIn('id', $chatUserIds)->get();

        return view('student.inbox', compact('users', 'user'));
    }



    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('coach.accont');
    }

    public function coursesIndex()
    {
        $courses = Course::where('coach_id', Auth::id())->latest()->paginate(9);
        return view('coach.Courses.index', compact('courses'));
    }

    public function coursesAdd()
    {
        return view('coach.Courses.add');
    }

    public function sections(Course $course)
    {
        $this->authorize('view', $course);
        return view('coach.Courses.sections_vedios', ['course' => $course]);
    }

    public function storeSections(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'sections' => 'required|array|min:1',
            'sections.*.title' => 'required|string|max:255',
            'sections.*.videos' => 'required|array|min:1',
            'sections.*.videos.*.title' => 'required|string|max:255',
            'sections.*.videos.*.video_url' => 'required|url',
            'sections.*.videos.*.public_id' => 'required|string',
            'sections.*.videos.*.original_name' => 'nullable|string',
            'sections.*.videos.*.size' => 'nullable|integer',
            'sections.*.videos.*.mime_type' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $course) {
            foreach ($validated['sections'] as $sectionInput) {
                $sectionTitle = trim($sectionInput['title']);
                $sectionOrder = Section::getNextOrder($course->id);

                $section = Section::create([
                    'course_id' => $course->id,
                    'title' => $sectionTitle,
                    'description' => null,
                    'order' => $sectionOrder,
                    'is_published' => true,
                ]);

                // Create a lesson for each video in the section
                foreach ($sectionInput['videos'] as $videoInput) {
                    Lesson::create([
                        'section_id' => $section->id,
                        'title' => trim($videoInput['title']),
                        'description' => null,
                        'type' => 'video',
                        'video_url' => $videoInput['video_url'],
                        'content' => null,
                        'file_url' => null,
                        'duration' => null,
                        'order' => Lesson::getNextOrder($section->id),
                        'is_preview' => false,
                        'is_published' => true,
                        'metadata' => [
                            'original_name' => $videoInput['original_name'] ?? 'Untitled Video',
                            'size' => $videoInput['size'] ?? 0,
                            'mime_type' => $videoInput['mime_type'] ?? 'video/unknown',
                            'cloudinary_public_id' => $videoInput['public_id'],
                            'cloudinary_resource_type' => 'video',
                        ],
                    ]);
                }
            }
        });

        return redirect()
            ->route('coach.courses.show', $course->id)
            ->with('success', 'Sections created and videos processed successfully.');
    }

    public function getCloudinarySignature(Request $request)
    {
        // Get config values
        $cloudinaryUrl = config('services.cloudinary.url') ?? env('CLOUDINARY_URL');
        $cloudName = config('services.cloudinary.cloud_name') ?? env('CLOUDINARY_CLOUD_NAME');
        $apiKey = config('services.cloudinary.api_key') ?? env('CLOUDINARY_API_KEY');
        $apiSecret = config('services.cloudinary.api_secret') ?? env('CLOUDINARY_API_SECRET');

        // Fallback: Parse from CLOUDINARY_URL if components are missing
        if ($cloudinaryUrl && (!$cloudName || !$apiKey || !$apiSecret)) {
            // Support formats: cloudinary://api_key:api_secret@cloud_name
            if (preg_match('/^cloudinary:\/\/([^:]+):([^@]+)@(.+)$/', $cloudinaryUrl, $matches)) {
                $apiKey = $apiKey ?: $matches[1];
                $apiSecret = $apiSecret ?: $matches[2];
                $cloudName = $cloudName ?: $matches[3];
            }
        }

        if (!$cloudName || !$apiKey || !$apiSecret) {
            Log::error('Missing Cloudinary configuration for signature generation.', [
                'has_url' => (bool) $cloudinaryUrl,
                'has_cloud' => (bool) $cloudName,
                'has_key' => (bool) $apiKey,
                'has_secret' => (bool) $apiSecret
            ]);
            return response()->json(['error' => 'Cloudinary is not fully configured.'], 500);
        }

        $timestamp = time();
        // Get all parameters except those that shouldn't be in the signature
        // Cloudinary excludes: file, cloud_name, resource_type, api_key
        $params = $request->except(['api_key', 'signature', 'file', 'resource_type', 'cloud_name', '_token']);
        $params['timestamp'] = $timestamp;

        // ksort is required by Cloudinary for signing
        ksort($params);

        $paramString = [];
        foreach ($params as $key => $value) {
            // Only sign non-empty parameters
            if ($value !== null && $value !== '') {
                $paramString[] = "$key=$value";
            }
        }

        $stringToSign = implode('&', $paramString) . $apiSecret;
        $signature = sha1($stringToSign);

        return response()->json([
            'signature' => $signature,
            'timestamp' => $timestamp,
            'api_key' => $apiKey,
            'cloud_name' => $cloudName,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0|max:9999.99',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        $course = Course::create([
            ...$validated,
            'coach_id' => Auth::id()
        ]);

        // Dispatch background job to create vector embedding (non-blocking)
        try {
            \App\Jobs\CreateCourseVector::dispatch($course)->delay(now()->addSeconds(5));
        } catch (\Exception $e) {
            // Log silently; do not interrupt flow
            Log::warning('Vector job dispatch failed for course ' . $course->id . ': ' . $e->getMessage());
        }

        // Redirect to sections creation step
        return redirect()->route('coach.courses.sections', $course->id)
            ->with('success', 'Course created! Now add sections.');
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);

        // Load course with sections and lessons for preview
        $course->load([
            'sections' => function ($query) {
                $query->orderBy('order')
                    ->with([
                        'lessons' => function ($q) {
                            $q->orderBy('order');
                        }
                    ]);
            }
        ]);

        return view('coach.Courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $this->authorize('view', $course);
        return view('coach.Courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0|max:9999.99',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:published,draft',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $validated['thumbnail'] = $thumbnailPath;
        }

        $course->update($validated);

        return redirect()->route('coach.courses.index')->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {


        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('coach.courses.index')->with('success', 'Course deleted successfully!');
    }

    public function modify(ProfileUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validated();

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $profilePicturePath = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['profile_photo'] = $profilePicturePath;
            unset($validated['profile_picture']); // Remove the form field name
        }

        if ($request->input('remove_picture') == '1') {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = null;
        }

        if ($request->has('about')) {
            $validated['about_you'] = $request->input('about');
            unset($validated['about']);
        }

        // Remove form-only fields that don't exist in database
        unset($validated['remove_picture']);

        if (isset($validated['email']) && $validated['email'] !== $user->email) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        // Check if request came from Chatify and redirect back if so
        if ($request->has('redirect_to_chatify') || str_contains($request->header('referer', ''), 'chatify')) {
            return redirect()->route('chatify')->with('status', 'profile-photo-updated');
        }

        return redirect()->route('coach.profile')->with('status', 'profile-updated');
    }

}







