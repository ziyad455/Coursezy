<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    // This will broadcast on the channel for the specific conversation
    public function broadcastOn()
    {
        return new Channel('chat.' . $this->message->receiver_id); // Broadcasting to the receiver's chat channel
    }

    public function broadcastAs()
    {
        return 'new-message';
    }
}
