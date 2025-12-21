<?php

namespace App\Events;

use App\Models\Activity;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewActivity implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $activity;
    public $coachId;

    /**
     * Create a new event instance.
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity->load(['user', 'course', 'student']);
        $this->coachId = $activity->coach_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('coach.' . $this->coachId),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->activity->id,
            'type' => $this->activity->type,
            'description' => $this->activity->description,
            'icon' => $this->activity->icon,
            'color' => $this->activity->color,
            'time_ago' => $this->activity->time_ago,
            'amount' => $this->activity->amount,
            'rating' => $this->activity->rating,
            'user_name' => $this->activity->user ? $this->activity->user->name : null,
            'student_name' => $this->activity->student ? $this->activity->student->name : null,
            'course_title' => $this->activity->course ? $this->activity->course->title : null,
            'created_at' => $this->activity->created_at->toISOString(),
        ];
    }

    /**
     * The name of the queue on which to place the broadcasting job.
     */
    public function broadcastQueue(): string
    {
        return 'broadcasts';
    }
}
