# Coursezy Flask API Documentation

## Overview

Coursezy uses a **dual AI system architecture**:
1. **Laravel AIController** - Direct integration with Google Gemini API for chat functionality
2. **Python Flask API** - Advanced AI features including embeddings and course recommendations

## Architecture Analysis

### Current Setup

```
                    Frontend (AiPage.blade.php)
                            |
                            | POST /ai/chat
                            v
                    Laravel AIController
                            |
                            | Direct API call
                            v
                    Google Gemini API
                    
                    
         Laravel CoachController          Search Feature
                    |                           |
                    | POST :5500/create_vector  | GET :5500/search_similar
                    v                           v
            Flask API (port 5500) <------------
                    |
                    | Uses Google AI & Pinecone
                    v
            Vector Database (Pinecone)
```

### Flask API Endpoints (http://127.0.0.1:5500)

| Endpoint | Method | Purpose | Used By |
|----------|--------|---------|---------|
| `/chat` | POST | AI Chat Assistant | **NOT CURRENTLY USED** (Laravel handles chat) |
| `/generate-sections` | POST | Generate course sections | **NOT CURRENTLY USED** |
| `/create_vector` | POST | Create embeddings for courses | CoachController (when creating courses) |
| `/search_similar` | GET | Find similar courses | Search feature (routes/web.php) |

## Key Findings

### 1. Chat Functionality
- ❌ **Flask API `/chat` endpoint is NOT being used**
- ✅ Laravel's AIController handles all chat requests directly
- The frontend sends requests to `/ai/chat` which routes to Laravel, not Flask

### 2. Vector Embeddings
- ✅ Flask API creates embeddings when courses are created
- ⚠️ **Quota Issues**: The free tier API quota for embeddings is frequently exceeded
- Used for semantic search functionality

### 3. Model Issues Fixed
- **Problem**: Used deprecated `gemini-pro` model
- **Solution**: Updated to `gemini-1.5-flash` in both Flask and Laravel

## Running the Flask API

### Manual Start
```bash
cd /home/ziyad-tber/My_Lravel_Apps/coursezy/app/Http/python
source venv/bin/activate
python AiApi.py
```

### Using Management Script
```bash
# Start the API
./app/Http/python/manage_flask_api.sh start

# Stop the API
./app/Http/python/manage_flask_api.sh stop

# Restart the API
./app/Http/python/manage_flask_api.sh restart

# Check status
./app/Http/python/manage_flask_api.sh status

# View logs
./app/Http/python/manage_flask_api.sh logs

# Test endpoints
./app/Http/python/manage_flask_api.sh test
```

## Configuration Files

### Flask API Configuration
- **File**: `/app/Http/python/AiApi.py`
- **Port**: 5500
- **Dependencies**: Flask, Google Generative AI, Pinecone
- **Environment Variables**:
  - `api` - Google AI API key
  - `PINECONE_API_KEY` - Pinecone API key

### Laravel Configuration
- **Controller**: `/app/Http/Controllers/AIController.php`
- **Environment Variable**: `GEMINI_API_KEY`

## Testing the APIs

### Test Flask API
```bash
# Test chat endpoint
curl -X POST http://127.0.0.1:5500/chat \
  -H "Content-Type: application/json" \
  -d '{"message": "Hello", "user_id": 1}'

# Test generate sections
curl -X POST http://127.0.0.1:5500/generate-sections \
  -H "Content-Type: application/json" \
  -d '{"description": "Python programming course"}'

# Test search similar
curl -X GET "http://127.0.0.1:5500/search_similar?description=learn+python"
```

### Test Laravel AI Chat
```bash
# Test through Laravel (this is what the frontend uses)
curl -X POST http://localhost/ai/chat \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: [your-csrf-token]" \
  -d '{"message": "Hello", "user_id": 1}'
```

## Recommendations

### Option 1: Unify on Laravel (Recommended)
Since Laravel already handles chat successfully:
1. Move embedding generation to Laravel
2. Remove Flask API dependency
3. Simplify architecture and deployment

### Option 2: Unify on Flask
If you want to use Flask for all AI features:
1. Update frontend to call Flask API directly
2. Remove Laravel AIController
3. Configure CORS properly in Flask

### Option 3: Keep Hybrid (Current)
If maintaining both:
1. Document which service handles what
2. Consider using Flask for complex AI tasks only
3. Ensure both services start on boot

## Troubleshooting

### Flask API Won't Start
```bash
# Check if port is in use
lsof -i :5500

# Kill any existing process
pkill -f "python AiApi.py"

# Check logs
tail -f app/Http/python/flask_api.log
```

### API Quota Exceeded
- Switch to a paid Google AI plan
- Implement caching for embeddings
- Rate limit API calls

### Model Not Found Error
- Ensure using `gemini-1.5-flash` or `gemini-1.5-pro`
- Check API key validity
- Verify model availability in your region

## API Keys Status
- ✅ Google AI API configured (both Flask and Laravel)
- ✅ Pinecone API configured (Flask only)
- ⚠️ Quota limits on free tier frequently exceeded

## Next Steps
1. Decide on architecture (unified vs hybrid)
2. Implement proper service management (systemd/supervisor)
3. Add error handling and retry logic
4. Consider caching for embeddings
5. Monitor API usage and quotas