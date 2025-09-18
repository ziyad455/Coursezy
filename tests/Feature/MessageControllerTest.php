<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Message;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that users can only see conversations they're part of
     */
    public function test_users_can_only_see_their_conversations()
    {
        // Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // Create messages between user1 and user2
        Message::create([
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'message' => 'Hello',
            'is_read' => false
        ]);

        // User3 has no conversations
        $this->actingAs($user3);
        $response = $this->get('/messages');
        
        $response->assertOk();
        $conversations = $response->viewData('conversations');
        $this->assertCount(0, $conversations);

        // User1 should see conversation with user2
        $this->actingAs($user1);
        $response = $this->get('/messages');
        
        $response->assertOk();
        $conversations = $response->viewData('conversations');
        $this->assertCount(1, $conversations);
        $this->assertEquals($user2->id, $conversations[0]['user']->id);
    }

    /**
     * Test that users cannot access conversations they're not part of
     */
    public function test_users_cannot_access_other_conversations()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // Create message between user1 and user2
        Message::create([
            'from_user_id' => $user1->id,
            'to_user_id' => $user2->id,
            'message' => 'Private message',
            'is_read' => false
        ]);

        // User3 tries to access conversation between user1 and user2
        $this->actingAs($user3);
        $response = $this->get('/messages/' . $user1->id);
        
        $response->assertForbidden();
    }

    /**
     * Test that users cannot send messages to users they haven't had conversations with
     * unless they are a coach of a course the recipient is enrolled in
     */
    public function test_message_sending_restrictions()
    {
        $coach = User::factory()->create(['role' => 'coach']);
        $student1 = User::factory()->create(['role' => 'student']);
        $student2 = User::factory()->create(['role' => 'student']);
        $randomUser = User::factory()->create(['role' => 'student']);

        // Create a course with coach
        $course = Course::factory()->create(['coach_id' => $coach->id]);

        // Enroll student1 in the course
        Enrollment::create([
            'student_id' => $student1->id,
            'course_id' => $course->id,
            'purchased_at' => now()
        ]);

        // Test 1: Random user cannot send message to student1
        $this->actingAs($randomUser);
        $response = $this->postJson('/messages/send', [
            'to_user_id' => $student1->id,
            'message' => 'Hello'
        ]);
        
        $response->assertForbidden();
        $response->assertJson([
            'success' => false,
            'error' => 'You do not have permission to send messages to this user.'
        ]);

        // Test 2: Coach can send message to their student
        $this->actingAs($coach);
        $response = $this->postJson('/messages/send', [
            'to_user_id' => $student1->id,
            'message' => 'Hello student'
        ]);
        
        $response->assertOk();
        $response->assertJson(['success' => true]);

        // Test 3: Coach cannot send message to student not in their course
        $response = $this->postJson('/messages/send', [
            'to_user_id' => $student2->id,
            'message' => 'Hello'
        ]);
        
        $response->assertForbidden();

        // Test 4: After receiving a message, student1 can reply to coach
        $this->actingAs($student1);
        $response = $this->postJson('/messages/send', [
            'to_user_id' => $coach->id,
            'message' => 'Hello coach'
        ]);
        
        $response->assertOk();
        $response->assertJson(['success' => true]);
    }

    /**
     * Test that coaches can search for their students to start conversations
     */
    public function test_coach_can_search_their_students()
    {
        $coach1 = User::factory()->create(['role' => 'coach', 'name' => 'Coach One']);
        $coach2 = User::factory()->create(['role' => 'coach', 'name' => 'Coach Two']);
        $student1 = User::factory()->create(['role' => 'student', 'name' => 'John Student']);
        $student2 = User::factory()->create(['role' => 'student', 'name' => 'Jane Student']);
        $student3 = User::factory()->create(['role' => 'student', 'name' => 'Bob Student']);

        // Create courses
        $course1 = Course::factory()->create(['coach_id' => $coach1->id]);
        $course2 = Course::factory()->create(['coach_id' => $coach2->id]);

        // Enroll students
        Enrollment::create(['student_id' => $student1->id, 'course_id' => $course1->id, 'purchased_at' => now()]);
        Enrollment::create(['student_id' => $student2->id, 'course_id' => $course1->id, 'purchased_at' => now()]);
        Enrollment::create(['student_id' => $student3->id, 'course_id' => $course2->id, 'purchased_at' => now()]);

        // Coach1 searches for students
        $this->actingAs($coach1);
        $response = $this->getJson('/messages/search-users?search=John');
        
        $response->assertOk();
        $results = $response->json();
        
        // Should only find John Student (student1) who is enrolled in coach1's course
        $this->assertCount(1, $results);
        $this->assertEquals($student1->id, $results[0]['id']);

        // Coach1 searches without filter - should get both their students
        $response = $this->getJson('/messages/search-users');
        $response->assertOk();
        $results = $response->json();
        
        // Should find student1 and student2, but not student3
        $this->assertCount(2, $results);
        $studentIds = collect($results)->pluck('id')->toArray();
        $this->assertContains($student1->id, $studentIds);
        $this->assertContains($student2->id, $studentIds);
        $this->assertNotContains($student3->id, $studentIds);
    }
}