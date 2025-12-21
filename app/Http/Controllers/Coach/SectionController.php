<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $courseId)
    {
        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $sections = $course->sections()
            ->with('lessons')
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'sections' => $sections
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = DB::transaction(function () use ($request, $course) {
            $order = Section::getNextOrder($course->id);

            return Section::create([
                'course_id' => $course->id,
                'title' => $request->title,
                'description' => $request->description,
                'order' => $request->order ?? $order,
                'is_published' => $request->is_published ?? true
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Section created successfully',
            'section' => $section
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($courseId, $sectionId)
    {
        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()
            ->with('lessons')
            ->findOrFail($sectionId);

        return response()->json([
            'success' => true,
            'section' => $section
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $courseId, $sectionId)
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'sometimes|integer|min:0',
            'is_published' => 'sometimes|boolean'
        ]);

        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()->findOrFail($sectionId);

        $section->update($request->only([
            'title', 'description', 'order', 'is_published'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Section updated successfully',
            'section' => $section
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($courseId, $sectionId)
    {
        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        $section = $course->sections()->findOrFail($sectionId);

        // Delete section and all its lessons (cascade delete)
        $section->delete();

        // Reorder remaining sections
        $course->sections()
            ->where('order', '>', $section->order)
            ->decrement('order');

        return response()->json([
            'success' => true,
            'message' => 'Section deleted successfully'
        ]);
    }

    /**
     * Update sections order
     */
    public function updateOrder(Request $request, $courseId)
    {
        $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:sections,id',
            'sections.*.order' => 'required|integer|min:0'
        ]);

        $course = Course::where('coach_id', Auth::id())
            ->findOrFail($courseId);

        DB::transaction(function () use ($request, $course) {
            foreach ($request->sections as $sectionData) {
                $course->sections()
                    ->where('id', $sectionData['id'])
                    ->update(['order' => $sectionData['order']]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Section order updated successfully'
        ]);
    }
}
