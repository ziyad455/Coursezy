<?php

namespace App\Helpers;

use App\Models\ChMessage;
use App\Models\ChFavorite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;
use Illuminate\Support\Str;

class ChatifyMessenger
{
    /**
     * Get pusher instance
     */
    protected function pusher()
    {
        return new Pusher(
            config('chatify.pusher.key'),
            config('chatify.pusher.secret'),
            config('chatify.pusher.app_id'),
            config('chatify.pusher.options')
        );
    }

    /**
     * Authenticate pusher channel
     */
    public function pusherAuth($requestUser, $authUser, $channelName, $socketId)
    {
        $pusher = $this->pusher();
        $auth = $pusher->socket_auth($channelName, $socketId);
        return json_decode($auth);
    }

    /**
     * Push message via pusher
     */
    public function push($channel, $event, $data)
    {
        $pusher = $this->pusher();
        return $pusher->trigger($channel, $event, $data);
    }

    /**
     * Get storage instance
     */
    public function storage()
    {
        return Storage::disk(config('chatify.storage_disk_name'));
    }

    /**
     * Get allowed images extensions
     */
    public function getAllowedImages()
    {
        return config('chatify.attachments.allowed_images');
    }

    /**
     * Get allowed files extensions
     */
    public function getAllowedFiles()
    {
        return config('chatify.attachments.allowed_files');
    }

    /**
     * Get max upload size
     */
    public function getMaxUploadSize()
    {
        return config('chatify.attachments.max_upload_size') * 1048576; // Convert MB to bytes
    }

    /**
     * Get user with avatar
     */
    public function getUserWithAvatar($user)
    {
        if ($user->avatar && $user->avatar != 'avatar.png') {
            $user->avatar = $this->storage()->url(config('chatify.user_avatar.folder') . '/' . $user->avatar);
        } else {
            $user->avatar = asset('storage/' . config('chatify.user_avatar.folder') . '/' . config('chatify.user_avatar.default'));
        }
        return $user;
    }

    /**
     * Get fallback color
     */
    public function getFallbackColor()
    {
        $colors = config('chatify.colors');
        return $colors[array_rand($colors)];
    }

    /**
     * Check if user is in favorite list
     */
    public function inFavorite($userId)
    {
        return ChFavorite::where([
            'user_id' => Auth::id(),
            'favorite_id' => $userId
        ])->exists();
    }

    /**
     * Create new message
     */
    public function newMessage($data)
    {
        $message = ChMessage::create([
            'from_id' => $data['from_id'],
            'to_id' => $data['to_id'],
            'body' => $data['body'],
            'attachment' => $data['attachment'],
            'seen' => 0
        ]);
        return $message;
    }

    /**
     * Parse message data
     */
    public function parseMessage($message)
    {
        $message->fromUser = User::find($message->from_id);
        $message->toUser = User::find($message->to_id);
        
        if ($message->attachment) {
            $attachment = json_decode($message->attachment);
            $message->attachment_url = $this->storage()->url(config('chatify.attachments.folder') . '/' . $attachment->new_name);
        }
        
        $message->time = $message->created_at->diffForHumans();
        $message->fullTime = $message->created_at->toDateTimeString();
        $message->viewType = ($message->from_id == Auth::id()) ? 'sender' : 'default';
        
        return $message;
    }

    /**
     * Generate message card HTML
     */
    public function messageCard($message, $renderDefaultCard = false)
    {
        if (!$message) return '';
        
        $viewType = ($renderDefaultCard) ? 'default' : $message->viewType;
        $isSender = $viewType == 'sender';
        
        $html = '<div class="message-card mc-' . $viewType . '" data-id="' . $message->id . '">';
        
        if (!$isSender) {
            $avatar = $this->getUserWithAvatar($message->fromUser)->avatar;
            $html .= '<div class="avatar av-m" style="background-image: url(\'' . $avatar . '\');"></div>';
        }
        
        $html .= '<div class="message-card-content">';
        $html .= '<div class="message">';
        
        if ($message->attachment) {
            $attachment = json_decode($message->attachment);
            $fileExt = pathinfo($attachment->old_name, PATHINFO_EXTENSION);
            
            if (in_array($fileExt, $this->getAllowedImages())) {
                $html .= '<div class="image-wrapper">';
                $html .= '<div class="image-file chat-image" style="background-image: url(\'' . $message->attachment_url . '\');">';
                $html .= '<div>' . $attachment->old_name . '</div>';
                $html .= '</div>';
                $html .= '</div>';
            } else {
                $html .= '<div class="file-wrapper">';
                $html .= '<div class="file-info">';
                $html .= '<span class="fas fa-file"></span> ' . $attachment->old_name;
                $html .= '</div>';
                $html .= '<a href="' . route(config('chatify.attachments.download_route_name'), $attachment->new_name) . '" class="file-download">';
                $html .= '<span class="fas fa-download"></span>';
                $html .= '</a>';
                $html .= '</div>';
            }
        }
        
        if ($message->body) {
            $html .= nl2br($message->body);
        }
        
        $html .= '<sub class="message-time">';
        $html .= '<span class="fas fa-' . ($message->seen ? 'check-double seen' : 'check') . '"></span>';
        $html .= ' <span class="message-time">' . $message->time . '</span>';
        $html .= '</sub>';
        $html .= '</div>';
        
        if ($isSender) {
            $html .= '<div class="message-actions">';
            $html .= '<button class="delete-btn" data-id="' . $message->id . '"><span class="fas fa-trash"></span></button>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Fetch messages query
     */
    public function fetchMessagesQuery($userId)
    {
        return ChMessage::where(function($query) use ($userId) {
            $query->where('from_id', Auth::id())
                  ->where('to_id', $userId);
        })->orWhere(function($query) use ($userId) {
            $query->where('from_id', $userId)
                  ->where('to_id', Auth::id());
        });
    }

    /**
     * Make messages as seen
     */
    public function makeSeen($userId)
    {
        return ChMessage::where('from_id', $userId)
            ->where('to_id', Auth::id())
            ->where('seen', 0)
            ->update(['seen' => 1]);
    }

    /**
     * Delete message
     */
    public function deleteMessage($messageId)
    {
        $message = ChMessage::find($messageId);
        
        if ($message && $message->from_id == Auth::id()) {
            if ($message->attachment) {
                $attachment = json_decode($message->attachment);
                $this->storage()->delete(config('chatify.attachments.folder') . '/' . $attachment->new_name);
            }
            $message->delete();
            return true;
        }
        
        return false;
    }

    /**
     * Get contacts
     */
    public function getContacts($request)
    {
        $users = User::where('id', '!=', Auth::id());
        
        if ($request->has('search')) {
            $users = $users->where('name', 'like', '%' . $request->search . '%');
        }
        
        return $users->paginate($request->per_page ?? 10);
    }

    /**
     * Update user settings
     */
    public function updateSettings($request)
    {
        $user = Auth::user();
        
        if ($request->has('messenger_color')) {
            $user->messenger_color = $request->messenger_color;
        }
        
        if ($request->has('dark_mode')) {
            $user->dark_mode = $request->dark_mode;
        }
        
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && $user->avatar != 'avatar.png') {
                $this->storage()->delete(config('chatify.user_avatar.folder') . '/' . $user->avatar);
            }
            
            // Upload new avatar
            $avatar = Str::uuid() . '.' . $request->file('avatar')->extension();
            $request->file('avatar')->storeAs(
                config('chatify.user_avatar.folder'),
                $avatar,
                config('chatify.storage_disk_name')
            );
            $user->avatar = $avatar;
        }
        
        $user->save();
        return $user;
    }
}
