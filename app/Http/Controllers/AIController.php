<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        // Use local Hugging Face API running on port 5500
        // Force 127.0.0.1 to avoid localhost IPv6 issues
        $this->apiUrl = env('AI_API_URL', 'http://127.0.0.1:5500');
    }

    public function chat(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'message' => 'required|string|max:1000',
                'user_id' => 'required'
            ]);

            $message = $request->input('message');
            // Keep user_id as strictly consistent value, ensure it's not null.
            // test_api.py sends integer 1. We should send what the frontend sends (integer).
            $userId = $request->input('user_id');

            // Log the incoming request
            Log::info('AI Chat Request', [
                'user_id' => $userId,
                'message' => $message
            ]);

            // Send request to local Hugging Face API
            // mirror test_api.py: requests.post(url, json=payload)
            // Http::asJson() ensures Content-Type: application/json
            $response = Http::asJson()->timeout(120)->post($this->apiUrl . '/chat', [
                'message' => $message,
                'user_id' => $userId
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Extract the reply from the response
                $reply = $data['reply'] ?? 'I apologize, but I couldn\'t generate a response.';

                // Log successful response
                Log::info('AI Chat Response Success', [
                    'user_id' => $userId,
                    'reply_length' => strlen($reply)
                ]);

                return response()->json([
                    'success' => true,
                    'response' => $reply,
                    'reply' => $reply // For compatibility with existing frontend
                ]);
            } else {
                // Log error response
                Log::error('Hugging Face API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'success' => false,
                    'response' => 'I\'m having trouble connecting to the AI service. Please try again later.',
                    'error' => 'API request failed'
                ], 500);
            }
        } catch (\Exception $e) {
            // Log exception
            Log::error('AI Chat Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'response' => 'An error occurred while processing your request. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function testConnection()
    {
        try {
            // Test the local Hugging Face API with a simple request
            $response = Http::timeout(10)->post($this->apiUrl . '/chat', [
                'message' => 'Say "Hello, Coursezy!" if you can hear me.',
                'user_id' => 0 // Test user ID
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'message' => 'Hugging Face API is connected and working!',
                    'api_response' => $data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to connect to Hugging Face API',
                    'error' => $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing connection. Make sure the Python API is running on port 5500.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
