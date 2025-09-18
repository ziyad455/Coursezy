# MessageController Restrictions Implementation

## Overview
This document describes the implemented access restrictions for the messaging system in the Coursezy application.

## Implemented Restrictions

### 1. **User Visibility Restrictions**
- Users can ONLY see other users they have already had conversations with
- The system does NOT expose a list of all users
- Removed the ability to browse or search all users in the system

### 2. **Conversation Starting Restrictions**
A user can only start a new conversation if ONE of the following conditions is met:
- **Condition 1**: The recipient has already sent them a message (existing conversation)
- **Condition 2**: The sender is a coach of a course that the recipient (student) is enrolled in

### 3. **Conversation Access Restrictions**
- Users can only access conversations they are participants in
- Attempting to access other users' conversations results in a 403 Forbidden error

## Key Changes Made

### MessageController Methods

#### 1. `index($userId = null)`
- Modified to only show users with existing conversations
- Removed exposure of all system users
- Added access verification for selected conversations

#### 2. `send(Request $request)`
- Added `canSendMessageTo()` check before allowing message sending
- Returns 403 error if user doesn't have permission

#### 3. `getMessages($userId)`
- Added `canAccessConversation()` check before returning messages
- Returns 403 error if user doesn't have access

#### 4. `getConversations()`
- Only returns users with existing conversations
- No exposure of all system users

### New Helper Methods

#### 1. `canSendMessageTo($toUserId)`
Checks if current user can send a message to another user:
- Returns `true` if existing conversation exists
- Returns `true` if sender is coach of recipient's course
- Returns `false` otherwise

#### 2. `canAccessConversation($userId)`
Checks if current user can access conversation with another user:
- Returns `true` only if messages exist between the users
- Returns `false` otherwise

#### 3. `searchUsersForNewConversation(Request $request)`
New endpoint for coaches to find their students:
- Only returns students enrolled in the coach's courses
- Excludes users with existing conversations
- Limited to 10 results
- Supports search by name or email

## Routes Added

```php
Route::get('/messages/search-users', [MessageController::class, 'searchUsersForNewConversation'])
    ->name('messages.search-users');
```

## Security Benefits

1. **Privacy Protection**: Users cannot discover or browse other users in the system
2. **Spam Prevention**: Users cannot randomly message others without a valid relationship
3. **Coach-Student Boundaries**: Coaches can only initiate conversations with their actual students
4. **Data Protection**: Users cannot access conversations they're not part of

## Testing

A comprehensive test suite has been created in `tests/Feature/MessageControllerTest.php` that verifies:
- Users can only see their own conversations
- Users cannot access other people's conversations
- Message sending restrictions are properly enforced
- Coaches can search for and message their students
- Students can reply once a conversation is initiated

## Frontend Considerations

When implementing the frontend, ensure:

1. **Message List**: Only display users from existing conversations
2. **New Conversation Button**: 
   - For coaches: Show option to search their students
   - For students: Hide or disable (they can only reply)
3. **Search Functionality**: 
   - Remove global user search
   - For coaches: Implement student search using `/messages/search-users` endpoint
4. **Error Handling**: Display appropriate messages when users try to access restricted conversations

## Database Relationships

The implementation relies on these key relationships:
- `User` ← has many → `Course` (as coach)
- `User` ← has many → `Enrollment` (as student)
- `Course` ← has many → `Enrollment`
- `Message` ← belongs to → `User` (from_user_id and to_user_id)

## Usage Examples

### Coach Starting Conversation with Student
```php
// Coach can message their student
POST /messages/send
{
    "to_user_id": 123,  // Student enrolled in coach's course
    "message": "Hello, how can I help with the course?"
}
// Response: 200 OK
```

### Unauthorized Message Attempt
```php
// Random user trying to message another user
POST /messages/send
{
    "to_user_id": 456,  // User with no relationship
    "message": "Hi there!"
}
// Response: 403 Forbidden
{
    "success": false,
    "error": "You do not have permission to send messages to this user."
}
```

### Coach Searching for Students
```php
GET /messages/search-users?search=John
// Returns only students named John enrolled in the coach's courses
```

## Maintenance Notes

- The restriction logic is centralized in helper methods for easy updates
- All database queries are optimized to avoid N+1 problems
- The implementation follows Laravel best practices
- Tests should be run after any modifications to ensure restrictions remain intact