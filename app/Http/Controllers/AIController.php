<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        // Updated to use gemini-1.5-flash which is currently available
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
    }

    public function chat(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'message' => 'required|string|max:1000',
                'user_id' => 'required|integer'
            ]);

            $message = $request->input('message');
            $userId = $request->input('user_id');

            // Log the incoming request
            Log::info('AI Chat Request', [
                'user_id' => $userId,
                'message' => $message
            ]);

            // Prepare the request to Gemini API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $this->buildPrompt($message)]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 1,
                    'topP' => 1,
                    'maxOutputTokens' => 2048,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract the text from the response
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'I apologize, but I couldn\'t generate a response.';
                
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
                Log::error('Gemini API Error', [
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

    private function buildPrompt($message)
    {
        // Build a context-aware prompt for Coursezy
        $systemContext = "You are an AI assistant for Coursezy, an online learning platform. ";
        $systemContext .= "You help users with course creation, student management, learning strategies, ";
        $systemContext .= "and general questions about online education. ";
        $systemContext .= "Be helpful, friendly, and professional. ";
        $systemContext .= "If asked about specific courses or features, provide relevant educational advice.\n\n";
        
        return $systemContext . "User question: " . $message;
    }

    public function testConnection()
    {
        try {
            // Simple test to check if API key is configured
            if (empty($this->apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'API key is not configured'
                ]);
            }

            // Test the API with a simple request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Say "Hello, Coursezy!" if you can hear me.']
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'maxOutputTokens' => 50,
                ]
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Gemini API is connected and working!',
                    'api_response' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to connect to Gemini API',
                    'error' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing connection',
                'error' => $e->getMessage()
            ]);
        }
    }
}
