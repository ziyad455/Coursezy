# AI API Troubleshooting Guide

## Current Issue: API Quota Exceeded

### Problem
The `aiapi.py` service is experiencing a **429 Rate Limit Error** due to exceeding the free tier quota for Google's Gemini API.

**Error Details:**
- **Error Code**: 429 (Too Many Requests)
- **Quota Limit**: 15 requests per minute (free tier)
- **Model**: gemini-1.5-flash
- **Current API Key**: AIzaSyDbuREoaw4m1UQYwJvM0LTi6RV4KLTYv28

### Status
✅ **API Server**: Running on port 5500
✅ **API Key**: Valid and authenticated
✅ **Model**: Using correct model (gemini-1.5-flash)
❌ **Quota**: Exceeded free tier limit

## Solutions

### Option 1: Wait for Quota Reset (Immediate Fix)
The free tier quota resets every minute. Simply wait 1-2 minutes between requests.

**Implementation in AiApi.py:**
```python
# Already implemented in lines 76-97
@retry_with_exponential_backoff(max_retries=3, base_delay=1)
```

### Option 2: Get a New API Key (Quick Fix)
1. Go to [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Create a new API key
3. Update the `.env` file:
   ```
   api=YOUR_NEW_API_KEY_HERE
   ```
4. Restart the API server

### Option 3: Upgrade to Paid Plan (Best for Production)
1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Enable billing for your project
3. Upgrade from free tier to pay-as-you-go
4. Benefits:
   - 360 requests per minute (vs 15)
   - 1 million tokens per minute (vs 32,000)
   - Better reliability

### Option 4: Implement Request Caching
Add caching to reduce API calls:
```python
from functools import lru_cache

@lru_cache(maxsize=100)
def cached_generate_content(prompt_hash):
    # Your generation logic here
```

## How to Test if API is Working

### 1. Check Server Status
```bash
ps aux | grep AiApi
# Should show the python process running
```

### 2. Test API Endpoints
```bash
# Test chat endpoint
curl -X POST http://127.0.0.1:5500/chat \
  -H "Content-Type: application/json" \
  -d '{"message": "Hello", "user_id": "1"}'

# Test generate-sections endpoint
curl -X POST http://127.0.0.1:5500/generate-sections \
  -H "Content-Type: application/json" \
  -d '{"description": "Learn Python basics"}'
```

### 3. Run Diagnostic Test
```bash
cd /home/ziyad-tber/My_Lravel_Apps/coursezy/app/Http/python
./venv/bin/python test_google_api.py
```

## Starting/Stopping the API Server

### Start the Server
```bash
cd /home/ziyad-tber/My_Lravel_Apps/coursezy/app/Http/python
nohup ./venv/bin/python AiApi.py > aiapi.log 2>&1 &
```

### Stop the Server
```bash
# Find the process
ps aux | grep AiApi

# Kill the process
kill <PID>
```

### View Logs
```bash
tail -f /home/ziyad-tber/My_Lravel_Apps/coursezy/app/Http/python/aiapi.log
```

## API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/chat` | POST | Chat with AI assistant |
| `/generate-sections` | POST | Generate course sections |
| `/create_vector` | POST | Create vector embeddings (requires Pinecone) |
| `/search_similar` | GET | Search similar courses (requires Pinecone) |

## Environment Variables (.env)

```bash
# Google Generative AI API Key
api=YOUR_GOOGLE_API_KEY

# Pinecone API Key (optional, for vector search)
PINECONE_API_KEY=YOUR_PINECONE_KEY

# Flask Configuration
FLASK_ENV=development
FLASK_DEBUG=True
```

## Common Issues and Fixes

### Issue 1: "API Key Invalid"
**Fix**: Get a new API key from Google AI Studio

### Issue 2: "Model not found"
**Fix**: Use one of these available models:
- `gemini-1.5-flash` (recommended)
- `gemini-1.5-pro`
- `gemini-2.0-flash`

### Issue 3: "Quota exceeded"
**Fix**: Wait 1 minute or upgrade to paid plan

### Issue 4: "Connection refused"
**Fix**: Make sure the API server is running on port 5500

## Monitoring

Check API status in Laravel:
```php
// In your Laravel controller
$response = Http::post('http://127.0.0.1:5500/chat', [
    'message' => 'Test',
    'user_id' => 1
]);

if ($response->failed()) {
    Log::error('AI API Error: ' . $response->body());
}
```

## Next Steps

1. **For Development**: Use the free tier with rate limiting
2. **For Production**: 
   - Upgrade to paid Google Cloud account
   - Implement proper error handling
   - Add request caching
   - Set up monitoring and alerts
   - Consider load balancing for high traffic

## Support

- [Google AI Documentation](https://ai.google.dev)
- [Gemini API Rate Limits](https://ai.google.dev/gemini-api/docs/rate-limits)
- [Pinecone Documentation](https://docs.pinecone.io)