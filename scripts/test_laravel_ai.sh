#!/bin/bash

echo "=========================================="
echo "Testing Laravel AIController with Hugging Face"
echo "=========================================="
echo ""

# Get the Laravel app URL (default to localhost:8000)
LARAVEL_URL="${LARAVEL_URL:-http://localhost:8000}"

echo "🔍 Testing AI Chat endpoint..."
echo ""

# Test the chat endpoint
response=$(curl -s -X POST "${LARAVEL_URL}/api/ai/chat" \
  -H "Content-Type: application/json" \
  -d '{
    "message": "What is Coursezy?",
    "user_id": 1
  }')

echo "Response:"
echo "$response" | python3 -m json.tool 2>/dev/null || echo "$response"
echo ""

# Check if successful
if echo "$response" | grep -q '"success":true'; then
    echo "✅ AI Chat is working with Hugging Face!"
else
    echo "❌ AI Chat failed. Make sure:"
    echo "   1. Laravel app is running (php artisan serve)"
    echo "   2. Python AI API is running on port 5500"
    echo "   3. Routes are configured correctly"
fi

echo ""
echo "=========================================="
echo "Testing AI Connection Test endpoint..."
echo "=========================================="
echo ""

# Test the connection test endpoint
test_response=$(curl -s -X GET "${LARAVEL_URL}/api/ai/test")

echo "Response:"
echo "$test_response" | python3 -m json.tool 2>/dev/null || echo "$test_response"
echo ""

if echo "$test_response" | grep -q '"success":true'; then
    echo "✅ Connection test passed!"
else
    echo "❌ Connection test failed"
fi
