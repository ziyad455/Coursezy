import os
import json
import time
import random
from datetime import datetime
from functools import wraps
from flask import Flask, request, jsonify
from flask_cors import CORS
from dotenv import load_dotenv
import google.generativeai as genai
from pinecone import Pinecone

load_dotenv()

app = Flask(__name__)
CORS(app)

API_KEY = os.getenv("api")
genai.configure(api_key=API_KEY)


PLATFORM_NAME = "Coursezy"
PLATFORM_FOCUS = "course creation, student management, teaching strategies, and online education"

# Path to store conversations
CONVERSATIONS_FILE = "conversations.json"

def load_conversations():
    """Load conversations from JSON file"""
    if os.path.exists(CONVERSATIONS_FILE):
        try:
            with open(CONVERSATIONS_FILE, 'r', encoding='utf-8') as f:
                return json.load(f)
        except (json.JSONDecodeError, FileNotFoundError):
            return {}
    return {}

def save_conversations(conversations):
    """S"""
    try:
        with open(CONVERSATIONS_FILE, 'w', encoding='utf-8') as f:
            json.dump(conversations, f, indent=2, ensure_ascii=False)
    except Exception as e:
        print(f"Error saving conversations: {e}")

def add_message_to_conversation(user_id, message, sender, ai_reply=None):
    """Add a message to user's conversation history"""
    conversations = load_conversations()
    
    user_id_str = str(user_id)
    if user_id_str not in conversations:
        conversations[user_id_str] = {
            "user_id": user_id,
            "created_at": datetime.now().isoformat(),
            "messages": []
        }
    
    # Add user message
    conversations[user_id_str]["messages"].append({
        "sender": sender,
        "message": message,
        "timestamp": datetime.now().isoformat()
    })
    
    # Add AI reply if provided
    if ai_reply:
        conversations[user_id_str]["messages"].append({
            "sender": "ai",
            "message": ai_reply,
            "timestamp": datetime.now().isoformat()
        })
    
    conversations[user_id_str]["updated_at"] = datetime.now().isoformat()
    save_conversations(conversations)

def retry_with_exponential_backoff(max_retries=3, base_delay=1):
    """Decorator to retry functions with exponential backoff for handling API rate limits"""
    def decorator(func):
        @wraps(func)
        def wrapper(*args, **kwargs):
            for attempt in range(max_retries):
                try:
                    return func(*args, **kwargs)
                except Exception as e:
                    error_str = str(e).lower()
                    # Check for rate limit or quota exceeded errors
                    if '429' in error_str or 'quota' in error_str or 'rate limit' in error_str:
                        if attempt < max_retries - 1:  # Don't sleep on the last attempt
                            delay = base_delay * (2 ** attempt) + random.uniform(0, 1)
                            print(f"Rate limit exceeded. Retrying in {delay:.2f} seconds... (attempt {attempt + 1}/{max_retries})")
                            time.sleep(delay)
                            continue
                    # Re-raise the exception if it's not a rate limit error or max retries reached
                    raise e
            return None
        return wrapper
    return decorator

def create_embedding_with_retry(content, max_retries=3):
    """Create embedding with retry logic specifically for quota exceeded errors"""
    for attempt in range(max_retries):
        try:
            result = genai.embed_content(
                model="models/embedding-001",
                content=content
            )
            return result['embedding']
        except Exception as e:
            error_str = str(e).lower()
            if ('429' in error_str or 'quota' in error_str or 'rate limit' in error_str) and attempt < max_retries - 1:
                # Exponential backoff with jitter
                delay = (2 ** attempt) + random.uniform(0.5, 2.0)
                print(f"API quota exceeded. Waiting {delay:.2f} seconds before retry {attempt + 2}/{max_retries}...")
                time.sleep(delay)
                continue
            else:
                raise e
    raise Exception("Max retries exceeded for embedding creation")

def get_system_prompt():
    """Generate a comprehensive system prompt that provides the AI with complete context about Coursezy
    and empowers it to be an expert assistant for the platform."""

    return f"""
🎓 COURSEZY AI ASSISTANT - OFFICIAL SYSTEM IDENTITY & OPERATIONAL GUIDELINES 🎓
================================================================================

🤖 WHO YOU ARE:
----------------
You are the OFFICIAL AI Assistant for COURSEZY, the premier online education platform. You were specifically created by ZIYAD TBER, a talented Software Engineer specializing in Web Applications & AI, to serve as the intelligent, helpful, and knowledgeable guide for all Coursezy users.

Your name is "Coursezy AI" and you are an integral part of the Coursezy ecosystem. You exist SOLELY to help users navigate, utilize, and succeed on the Coursezy platform. You have been programmed with deep knowledge of every feature, function, and capability of Coursezy.

⚠️ CRITICAL RESTRICTION - YOU MUST FOLLOW THIS RULE ABSOLUTELY:
----------------------------------------------------------------
YOU CAN ONLY DISCUSS TOPICS RELATED TO:
1. The Coursezy platform and its features
2. Online education and e-learning
3. Course creation and management
4. Teaching strategies for online courses
5. Student engagement in digital learning
6. Using Coursezy's tools and features
7. Educational content development
8. Online coaching and mentoring
9. Learning management systems
10. Educational technology relevant to Coursezy

IF SOMEONE ASKS ABOUT ANYTHING ELSE (weather, general knowledge, programming unrelated to Coursezy, politics, entertainment, etc.), YOU MUST RESPOND:
"I'm specifically designed to help with Coursezy and online education matters. Please ask me about courses, teaching, or how football to use our platform! 🎓"

🏛️ THE COURSEZY PLATFORM - COMPLETE OVERVIEW:
----------------------------------------------
Coursezy is a state-of-the-art online learning management system built with:
- Laravel PHP Framework (latest version)
- MySQL Database for robust data management
- Tailwind CSS for beautiful, responsive design
- Alpine.js for dynamic interactions
- Pusher for real-time messaging
- AI-powered features for enhanced learning
- Google OAuth integration for easy sign-up
- Advanced search with AI similarity matching

👥 USER ROLES & CAPABILITIES:
------------------------------

### COACHES/INSTRUCTORS CAN:
- Create unlimited courses with rich multimedia content
- Upload videos, PDFs, images, and interactive materials
- Set course prices and manage revenue
- Track detailed analytics (enrollments, completion rates, earnings)
- Communicate with students via integrated messaging
- Build comprehensive instructor profiles
- Add skills and certifications
- Receive and respond to student reviews
- Access dedicated coach dashboard
- Generate AI-powered course sections
- Monitor student progress in real-time
- Create course categories and tags
- Schedule course availability
- Offer discounts and promotions

### STUDENTS CAN:
- Browse courses by category, price, rating
- Enroll in multiple courses
- Track learning progress
- Rate and review courses
- Message instructors directly
- Build learning profiles
- Save favorite courses
- Access course materials 24/7
- Receive completion certificates
- Search courses with AI-powered recommendations
- Switch between light/dark themes
- Manage payment methods
- View learning history

📚 PLATFORM FEATURES IN DETAIL:
--------------------------------

**Authentication System:**
- Email/password registration
- Google OAuth integration
- Secure password reset
- Remember me functionality
- Email verification
- Role-based access control

**Course Management:**
- Rich text course descriptions
- Multiple pricing tiers
- Course categories (Technology, Business, Arts, etc.)
- AI-generated course sections
- Progress tracking
- Course prerequisites
- Difficulty levels
- Duration estimates
- Student limits

**Messaging System:**
- Real-time chat between students and coaches
- Message notifications
- Read receipts
- File sharing in chat
- Message history
- Block/report functionality

**Payment Processing:**
- Secure payment integration
- Multiple payment methods
- Transaction history
- Refund management
- Revenue analytics for coaches

**Profile Management:**
- Custom profile photos
- Skill badges
- Bio sections
- Social media links
- Verification badges
- Achievement displays

**Search & Discovery:**
- AI-powered course recommendations
- Advanced filtering options
- Keyword search
- Category browsing
- Trending courses
- Similar course suggestions

**Rating & Review System:**
- 5-star rating system
- Detailed written reviews
- Review moderation
- Response to reviews
- Rating analytics

🎯 YOUR COMMUNICATION STYLE:
-----------------------------
1. **Be Concise**: Keep responses under 3-4 sentences unless explaining complex features
2. **Use Simple Language**: Avoid technical jargon unless necessary
3. **Be Friendly**: Use emojis appropriately (📚 for courses, 👨‍🏫 for coaches, 🎓 for students)
4. **Be Action-Oriented**: Always provide clear next steps
5. **Be Encouraging**: Motivate users to explore and succeed
6. **Be Professional**: Maintain a helpful, educational tone

📋 RESPONSE TEMPLATES FOR COMMON SCENARIOS:
-------------------------------------------

**When asked about course creation:**
"Creating a course on Coursezy is simple! 📚 Go to your coach dashboard, click 'Create Course', fill in the details, and our AI will even help generate course sections. Need specific help with any step?"

**When asked about enrollment:**
"To enroll in a course: Find the course you want, click 'Enroll Now', complete payment, and you'll have instant access! 🎓 Your courses appear in 'My Courses' section."

**When asked about messaging:**
"Our real-time messaging lets you chat directly with coaches/students! 💬 Just click the message icon on their profile or in your inbox. Messages are instant and secure."

**When asked about non-Coursezy topics:**
"I'm specifically designed to help with Coursezy and online education matters. Please ask me about courses, teaching, or how to use our platform! 🎓"

🚀 ADVANCED KNOWLEDGE BASE:
---------------------------

**Technical Architecture:**
- Built on Laravel 12.x framework
- Uses MySQL for data persistence
- Redis for caching and sessions
- Pusher for WebSocket connections
- AI integration via Google Gemini API
- Pinecone vector database for course similarity
- Responsive design for all devices

**Business Model:**
- Coaches set their own prices
- Platform takes a small commission
- Students pay per course (no subscriptions)
- Coaches can offer bulk discounts
- Affiliate program available

**Best Practices You Should Promote:**
- Coaches should upload intro videos
- Courses should have clear learning objectives
- Regular student engagement improves completion
- High-quality thumbnails increase enrollment
- Responding to reviews builds trust
- Using AI-generated sections saves time

⚡ QUICK FACTS TO REMEMBER:
---------------------------
- Platform founded by: Ziyad Tber
- Primary purpose: Democratize online education
- Unique feature: AI-powered course creation
- Main differentiator: Real-time messaging
- Target audience: Professionals sharing expertise
- Supported languages: Currently English (more coming)
- Mobile support: Fully responsive design
- Average course completion rate: Track in analytics

❌ TOPICS YOU MUST NEVER DISCUSS:
----------------------------------
- Politics or political opinions
- Religious topics beyond educational courses
- Personal medical advice
- Legal advice
- Investment or financial advice (except course pricing)
- Competitor platforms in detail
- Technical hacking or exploits
- Personal information about users
- Anything unrelated to education or Coursezy

✅ YOUR MISSION STATEMENT:
--------------------------
"I exist to make online education accessible, enjoyable, and profitable for everyone on Coursezy. Every interaction should help users create better courses, learn more effectively, or utilize our platform's features to their fullest potential. I am here to ensure every coach succeeds and every student achieves their learning goals on Coursezy."

🎓 REMEMBER: You are COURSEZY AI - the friendly, knowledgeable, and helpful assistant that makes online education simple and successful for everyone! Always be positive, helpful, and focused exclusively on Coursezy and educational topics.

Now, let's help someone succeed on Coursezy today! 💪✨
"""


@app.route('/chat', methods=['POST'])
def chat():
    user_message = request.json.get("message")
    user_id = request.json.get("user_id")  # Get user ID from request
    
    if not user_message:
        return jsonify({"error": "No message provided"}), 400
    
    if not user_id:
        return jsonify({"error": "No user ID provided"}), 400

    try:
        # Create the system prompt focused on Coursezy coaching
        system_prompt = get_system_prompt()
        
        # Load conversation history for context
        conversations = load_conversations()
        user_id_str = str(user_id)
        conversation_history = ""
        
        if user_id_str in conversations:
            recent_messages = conversations[user_id_str]["messages"][-10:]  # Last 10 messages for context
            for msg in recent_messages:
                role = "Coach" if msg["sender"] == "user" else "AI Assistant"
                conversation_history += f"{role}: {msg['message']}\n"
        
        # Combine system prompt with conversation history and user message
        full_prompt = f"{system_prompt}\n\nConversation History:\n{conversation_history}\nCoach Question: {user_message}\n\nAI Assistant:"
        
        model = genai.GenerativeModel('gemini-1.5-flash')
        response = model.generate_content(full_prompt)
        
        ai_reply = response.text
        
        # Save the conversation
        add_message_to_conversation(user_id, user_message, "user", ai_reply)
        
        return jsonify({"reply": ai_reply})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/generate-sections', methods=['POST'])
def generate_sections():
    course_description = request.json.get("description")
    
    if not course_description:
        return jsonify({"error": "No course description provided"}), 400

    try:
        prompt = f"""
        You are an expert course designer for the Coursezy platform. 
        Given this course description: "{course_description}"
        
        Create exactly 4 logical sections for this course, formatted as follows:
        
        Section Title|Brief Description|Estimated Duration
        
        Example:
        HTML Fundamentals|Learn the building blocks of web development|45 min
        
        Rules:
        1. Create exactly 4 sections
        2. Each section should be on a separate line
        3. Use the exact format: Title|Description|Duration
        4. Keep descriptions concise (under 15 words)
        5. Duration should be in format like '45 min' or '2h 30m'
        6. Sections should progress from basic to advanced
        
        Now generate the 4 sections for this course:
        """
        
        model = genai.GenerativeModel('gemini-1.5-flash')
        response = model.generate_content(prompt)
        
        # Split the response into sections
        sections = [line.strip() for line in response.text.split('\n') if line.strip()]
        
        # Parse each section into components
        parsed_sections = []
        for section in sections[:4]:  # Take only first 4 in case model returns extra
            if '|' in section:
                title, desc, duration = section.split('|', 2)
                parsed_sections.append({
                    "title": title.strip(),
                    "description": desc.strip(),
                    "duration": duration.strip()
                })
        
        return jsonify({
            "sections": parsed_sections,
            "original_response": response.text  # For debugging
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500
    
PINECONE_API_KEY = os.getenv("PINECONE_API_KEY")
INDEX_NAME = "cours-index"

# Initialize Pinecone only if API key is provided
if PINECONE_API_KEY and PINECONE_API_KEY != "YOUR_PINECONE_API_KEY_HERE":
    try:
        pc = Pinecone(api_key=PINECONE_API_KEY)
        index = pc.Index(INDEX_NAME)
        print(f"Connected to existing index: {INDEX_NAME}")
    except Exception as e:
        print(f"Warning: Could not connect to Pinecone: {e}")
        index = None
else:
    print("Warning: Pinecone API key not configured")
    pc = None
    index = None

# API 1: إنشاء vector وحفظه
@app.route('/create_vector', methods=['POST'])
def create_vector():
    if not index:
        return jsonify({"error": "Pinecone is not configured. Please add PINECONE_API_KEY to .env file"}), 503
    
    data = request.json
    product_id = data.get('id')
    description = data.get('description')

    if not product_id or not description:
        return jsonify({"error": "id and description are required"}), 400

    try:
        # تنظيف النص قبل الإرسال
        clean_description = description.strip()
        if len(clean_description) < 10:
            return jsonify({"error": "Description too short"}), 400

        # توليد embedding من Google AI with retry mechanism
        try:
            embedding = create_embedding_with_retry(clean_description, max_retries=3)
        except Exception as embedding_error:
            # If embedding creation fails due to quota, return specific error
            error_msg = str(embedding_error)
            if '429' in error_msg.lower() or 'quota' in error_msg.lower():
                return jsonify({
                    "error": "429 You exceeded your current quota, please check your plan and billing details. For more information on this error, head to ▶"
                }), 429
            else:
                return jsonify({"error": f"Embedding creation failed: {error_msg}"}), 500
        
        # تحقق من البعد
        print(f"Embedding dimension: {len(embedding)}")

        # حفظ المتجه في Pinecone
        index.upsert(vectors=[(str(product_id), embedding)])

        return jsonify({"message": "Vector created and saved", "id": product_id})
    
    except Exception as e:
        error_msg = str(e)
        print(f"Error in create_vector: {error_msg}")
        # Return specific status codes for different error types
        if '429' in error_msg.lower() or 'quota' in error_msg.lower():
            return jsonify({"error": f"Vector creation failed: {error_msg}"}), 429
        else:
            return jsonify({"error": f"Vector creation failed: {error_msg}"}), 500

# API 2: البحث عن أوصاف مشابهة
@app.route('/search_similar', methods=['GET'])
def search_similar():
    if not index:
        return jsonify({"error": "Pinecone is not configured. Please add PINECONE_API_KEY to .env file"}), 503
    
    query = request.args.get('description')
    if not query:
        return jsonify({"error": "description query parameter is required"}), 400

    try:
        # توليد embedding للاستعلام with retry mechanism
        try:
            query_embedding = create_embedding_with_retry(query, max_retries=3)
        except Exception as embedding_error:
            error_msg = str(embedding_error)
            if '429' in error_msg.lower() or 'quota' in error_msg.lower():
                return jsonify({"error": "API quota exceeded for search. Please try again later."}), 429
            else:
                return jsonify({"error": f"Search embedding creation failed: {error_msg}"}), 500

        # البحث في Pinecone عن أقرب المتجهات مع threshold
        results = index.query(
            vector=query_embedding, 
            top_k=5, 
            include_metadata=True,
            include_values=False
        )

        # فلترة النتائج بناءً على similarity score
        similar_ids = []
        for match in results.matches:
            if match.score > 0.7:  # threshold للتشابه
                similar_ids.append(match.id)

        print(f"Query: {query}")
        print(f"Found {len(similar_ids)} similar courses with score > 0.7")
        for match in results.matches:
            print(f"ID: {match.id}, Score: {match.score}")

        return jsonify({"similar_descriptions_ids": similar_ids})
    
    except Exception as e:
        print(f"Error in search_similar: {str(e)}")
        return jsonify({"error": str(e)}), 500


if __name__ == '__main__':
    app.run(debug=True, port=5500)
