<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the chat interface
     * 
     * Restriction:
     * - Users should NOT be able to search or view all users.
     * - Only show users the current user has already had a conversation with.
     */
    public function index($userId = null)
    {
        $currentUserId = Auth::id();
        
        // Get ONLY users that have exchanged messages with current user
        // This ensures users can't browse all users in the system
        $conversationUserIds = Message::where('from_user_id', $currentUserId)
            ->pluck('to_user_id')
            ->merge(
                Message::where('to_user_id', $currentUserId)
                    ->pluck('from_user_id')
            )
            ->unique()
            ->toArray();
        
        // Only get users who have had conversations with the current user
        // Do NOT expose all users in the system
        $conversationUsers = User::whereIn('id', $conversationUserIds)
            ->orderBy('name')
            ->get();
        
        // Prepare conversation data
        $conversations = [];
        foreach ($conversationUsers as $user) {
            $lastMessage = Message::getLastMessage($currentUserId, $user->id);
            $unreadCount = Message::getUnreadCount($user->id, $currentUserId);
            
            $conversations[] = [
                'user' => $user,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
                'has_messages' => true
            ];
        }
        
        // Sort conversations by last message time
        usort($conversations, function($a, $b) {
            if (!$a['last_message'] && !$b['last_message']) return 0;
            if (!$a['last_message']) return 1;
            if (!$b['last_message']) return -1;
            return $b['last_message']->created_at->timestamp - $a['last_message']->created_at->timestamp;
        });
        
        $selectedUser = null;
        $messages = [];

        if ($userId) {
            // Verify the user can access this conversation
            if (!$this->canAccessConversation($userId)) {
                abort(403, 'You do not have permission to access this conversation.');
            }
            
            $selectedUser = User::findOrFail($userId);
            $messages = Message::where(function($query) use ($userId) {
                $query->where('from_user_id', Auth::id())
                      ->where('to_user_id', $userId);
            })->orWhere(function($query) use ($userId) {
                $query->where('from_user_id', $userId)
                      ->where('to_user_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->with(['fromUser', 'toUser'])
            ->get();

            // Mark messages as read
            Message::where('from_user_id', $userId)
                   ->where('to_user_id', Auth::id())
                   ->where('is_read', false)
                   ->update(['is_read' => true]);
        }

        // Use enhanced view if it exists, otherwise fallback
        $viewName = view()->exists('messages.index_enhanced') ? 'messages.index_enhanced' : 'messages.index';
        
        // Pass only conversation users, not all users
        return view($viewName, compact('conversations', 'conversationUsers', 'selectedUser', 'messages'));
    }

    /**
     * Send a new message
     * 
     * Restriction:
     * - A user can only start a conversation if:
     *   1. The recipient has already sent them a message, OR
     *   2. They are a coach of a course the recipient (student) is enrolled in.
     */
    public function send(Request $request)
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $toUserId = $request->to_user_id;
        $fromUserId = Auth::id();
        
        // Check if user can send message to this recipient
        if (!$this->canSendMessageTo($toUserId)) {
            return response()->json([
                'success' => false,
                'error' => 'You do not have permission to send messages to this user.'
            ], 403);
        }

        $message = Message::create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'message' => $request->message,
            'is_read' => false
        ]);

        $message->load('fromUser', 'toUser');

        // Broadcast the message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Delete a message sent by the authenticated user
     */
    public function deleteMessage($messageId)
    {
        $message = Message::findOrFail($messageId);
        if ($message->from_user_id !== Auth::id()) {
            return response()->json(['success' => false, 'error' => 'You can only delete your own messages.'], 403);
        }

        // Keep a copy for broadcasting target ids
        $messageCopy = clone $message;
        $message->delete();

        broadcast(new MessageDeleted($messageCopy))->toOthers();

        return response()->json(['success' => true]);
    }

    /**
     * Get messages between two users
     */
    public function getMessages($userId)
    {
        // Verify the user can access this conversation
        if (!$this->canAccessConversation($userId)) {
            return response()->json([
                'error' => 'You do not have permission to access this conversation.'
            ], 403);
        }
        
        $messages = Message::where(function($query) use ($userId) {
            $query->where('from_user_id', Auth::id())
                  ->where('to_user_id', $userId);
        })->orWhere(function($query) use ($userId) {
            $query->where('from_user_id', $userId)
                  ->where('to_user_id', Auth::id());
        })
        ->orderBy('created_at', 'asc')
        ->with(['fromUser', 'toUser'])
        ->get();

        // Mark messages as read
        Message::where('from_user_id', $userId)
               ->where('to_user_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * Get unread message count
     */
    public function getUnreadCount()
    {
        $count = Message::where('to_user_id', Auth::id())
                       ->where('is_read', false)
                       ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark all messages from a specific user to the current user as read
     *
     * @param int $userId The other participant's user id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead($userId)
    {
        if (!$this->canAccessConversation($userId)) {
            return response()->json([
                'success' => false,
                'error' => 'You do not have permission to modify this conversation.'
            ], 403);
        }

        Message::where('from_user_id', $userId)
               ->where('to_user_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Get conversations list with last message preview
     * Only returns users the current user has already had conversations with
     */
    public function getConversations()
    {
        $userId = Auth::id();
        
        // Get ONLY users that have exchanged messages with the current user
        // This ensures we don't expose all users in the system
        $conversationUsers = User::whereIn('id', function($query) use ($userId) {
            $query->select('from_user_id')
                  ->from('messages')
                  ->where('to_user_id', $userId)
                  ->union(
                      Message::select('to_user_id')
                             ->where('from_user_id', $userId)
                  );
        })->get();
        
        $conversations = [];
        
        foreach ($conversationUsers as $user) {
            $lastMessage = Message::getLastMessage($userId, $user->id);
            $unreadCount = Message::getUnreadCount($user->id, $userId);
            
            $conversations[] = [
                'user' => $user,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
                'is_online' => false // You can implement online status tracking later
            ];
        }
        
        // Sort by last message time
        usort($conversations, function($a, $b) {
            if (!$a['last_message'] && !$b['last_message']) return 0;
            if (!$a['last_message']) return 1;
            if (!$b['last_message']) return -1;
            return $b['last_message']->created_at->timestamp - $a['last_message']->created_at->timestamp;
        });
        
        return response()->json($conversations);
    }

    /**
     * Check if the current user can send a message to another user
     * 
     * @param int $toUserId
     * @return bool
     */
    private function canSendMessageTo($toUserId)
    {
        $currentUserId = Auth::id();
        
        // Check if there's already an existing conversation
        // (either direction - they messaged us or we messaged them)
        $existingConversation = Message::where(function($query) use ($currentUserId, $toUserId) {
            $query->where('from_user_id', $currentUserId)
                  ->where('to_user_id', $toUserId);
        })->orWhere(function($query) use ($currentUserId, $toUserId) {
            $query->where('from_user_id', $toUserId)
                  ->where('to_user_id', $currentUserId);
        })->exists();
        
        if ($existingConversation) {
            return true;
        }
        
        // Check if the current user is a coach of a course the recipient is enrolled in
        $isCoachOfStudentCourse = DB::table('courses')
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->where('courses.coach_id', $currentUserId)
            ->where('enrollments.student_id', $toUserId)
            ->exists();
        
        return $isCoachOfStudentCourse;
    }

    /**
     * Check if the current user can access a conversation with another user
     * 
     * @param int $userId
     * @return bool
     */
    private function canAccessConversation($userId)
    {
        $currentUserId = Auth::id();
        
        // Check if there's an existing conversation between the users
        return Message::where(function($query) use ($currentUserId, $userId) {
            $query->where('from_user_id', $currentUserId)
                  ->where('to_user_id', $userId);
        })->orWhere(function($query) use ($currentUserId, $userId) {
            $query->where('from_user_id', $userId)
                  ->where('to_user_id', $currentUserId);
        })->exists();
    }

    /**
     * Search for users to start a new conversation with
     * Only returns students enrolled in courses taught by the current coach
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchUsersForNewConversation(Request $request)
    {
        $currentUserId = Auth::id();
        $searchTerm = $request->get('search', '');
        
        // Only allow coaches to search for their students
        $students = User::select('users.*')
            ->join('enrollments', 'users.id', '=', 'enrollments.student_id')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('courses.coach_id', $currentUserId)
            ->where('users.id', '!=', $currentUserId);
        
        if ($searchTerm) {
            $students->where(function($query) use ($searchTerm) {
                $query->where('users.name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('users.email', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Exclude users who already have conversations
        $existingConversationUserIds = Message::where('from_user_id', $currentUserId)
            ->pluck('to_user_id')
            ->merge(
                Message::where('to_user_id', $currentUserId)
                    ->pluck('from_user_id')
            )
            ->unique()
            ->toArray();
        
        $students->whereNotIn('users.id', $existingConversationUserIds);
        
        $results = $students->distinct()
                           ->limit(10)
                           ->get();
        
        return response()->json($results);
    }
}
