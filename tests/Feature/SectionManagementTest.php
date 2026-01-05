<?php

namespace Tests\Feature;

use App\Jobs\DeleteCloudinaryVideos;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SectionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $coach;
    protected User $otherCoach;
    protected Course $course;
    protected Section $section;
    protected Lesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coach = User::factory()->create(['role' => 'coach']);
        $this->otherCoach = User::factory()->create(['role' => 'coach']);

        $this->course = Course::factory()->create([
            'coach_id' => $this->coach->id,
        ]);

        $this->section = Section::create([
            'course_id' => $this->course->id,
            'title' => 'Test Section',
            'order' => 1,
            'is_published' => true,
        ]);

        $this->lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Test Video',
            'type' => 'video',
            'video_url' => 'https://cloudinary.com/test-video.mp4',
            'order' => 1,
            'is_published' => true,
            'metadata' => [
                'original_name' => 'test-video.mp4',
                'cloudinary_public_id' => 'coursezy/courses/1/test-video',
                'cloudinary_resource_type' => 'video',
            ],
        ]);
    }

    public function test_coach_can_view_manage_sections_page(): void
    {
        $response = $this->actingAs($this->coach)
            ->get(route('coach.courses.manage-sections', $this->course->id));

        $response->assertOk();
        $response->assertViewIs('coach.Courses.manage_sections');
        $response->assertViewHas('course');
    }

    public function test_other_coach_cannot_view_manage_sections_page(): void
    {
        $response = $this->actingAs($this->otherCoach)
            ->get(route('coach.courses.manage-sections', $this->course->id));

        $response->assertForbidden();
    }

    public function test_coach_can_add_video_to_section(): void
    {
        $response = $this->actingAs($this->coach)
            ->postJson(route('coach.sections.videos.store', [
                'course' => $this->course->id,
                'section' => $this->section->id,
            ]), [
                'title' => 'New Video',
                'video_url' => 'https://cloudinary.com/new-video.mp4',
                'public_id' => 'coursezy/courses/1/new-video',
                'original_name' => 'new-video.mp4',
                'size' => 1024,
                'mime_type' => 'video/mp4',
            ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('lessons', [
            'section_id' => $this->section->id,
            'title' => 'New Video',
        ]);
    }

    public function test_coach_can_delete_video_with_cloudinary_cleanup(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->coach)
            ->deleteJson(route('coach.sections.videos.destroy', [
                'course' => $this->course->id,
                'section' => $this->section->id,
                'lesson' => $this->lesson->id,
            ]));

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('lessons', ['id' => $this->lesson->id]);

        Queue::assertPushed(DeleteCloudinaryVideos::class, function ($job) {
            return in_array('coursezy/courses/1/test-video', $job->publicIds ?? []);
        });
    }

    public function test_coach_can_delete_section_with_all_videos(): void
    {
        Queue::fake();

        // Add another lesson to the section
        $lesson2 = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Second Video',
            'type' => 'video',
            'video_url' => 'https://cloudinary.com/second-video.mp4',
            'order' => 2,
            'is_published' => true,
            'metadata' => [
                'cloudinary_public_id' => 'coursezy/courses/1/second-video',
            ],
        ]);

        $response = $this->actingAs($this->coach)
            ->deleteJson(route('coach.sections.destroy', [
                'course' => $this->course->id,
                'section' => $this->section->id,
            ]));

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('sections', ['id' => $this->section->id]);
        $this->assertDatabaseMissing('lessons', ['id' => $this->lesson->id]);
        $this->assertDatabaseMissing('lessons', ['id' => $lesson2->id]);

        Queue::assertPushed(DeleteCloudinaryVideos::class);
    }

    public function test_other_coach_cannot_delete_section(): void
    {
        $response = $this->actingAs($this->otherCoach)
            ->deleteJson(route('coach.sections.destroy', [
                'course' => $this->course->id,
                'section' => $this->section->id,
            ]));

        $response->assertForbidden();
        $this->assertDatabaseHas('sections', ['id' => $this->section->id]);
    }

    public function test_validation_for_adding_video(): void
    {
        $response = $this->actingAs($this->coach)
            ->postJson(route('coach.sections.videos.store', [
                'course' => $this->course->id,
                'section' => $this->section->id,
            ]), [
                // Missing required fields
                'title' => '',
            ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['title', 'video_url', 'public_id']);
    }
}
