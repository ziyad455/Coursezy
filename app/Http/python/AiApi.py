import os
import json
from datetime import datetime
from flask import Flask, request, jsonify
from flask_cors import CORS
from dotenv import load_dotenv
import google.genai as genai

load_dotenv()

app = Flask(__name__)
CORS(app)

API_KEY = os.getenv("api")
client = genai.Client(api_key=API_KEY)


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

def get_system_prompt():
    """Generate a comprehensive system prompt that provides the AI with complete context about Coursezy
    and empowers it to be an expert assistant for the platform."""

    return f"""
You are the official AI Assistant for {PLATFORM_NAME}, a comprehensive online learning platform built with Laravel and modern web technologies. You have deep knowledge of the entire platform ecosystem and serve as an expert advisor for coaches, instructors, and educational entrepreneurs.

I was created by ZIYAD TBER, a Software Engineer – Web Applications & AI Enthusiast, who developed this platform to help educators succeed online.

IMPORTANT: Always keep your responses SHORT, USEFUL, and in SIMPLE ENGLISH. Be direct and practical. Use emojis to make your responses more engaging and friendly! 😊

------------------------------------------------------------------------------------
ABOUT COURSEZY PLATFORM:
------------------------------------------------------------------------------------
{PLATFORM_NAME} is a full-featured online education platform that enables:

**For Coaches & Instructors:**
- Create and manage comprehensive courses with multimedia content
- Build detailed instructor profiles with skills, experience, and specializations
- Track student enrollment, progress, and engagement analytics
- Manage course categories, pricing, and availability
- Access a dedicated coach dashboard with performance metrics
- Communicate with students through integrated messaging systems
- Receive and manage course ratings and feedback

**For Students:**
- Browse and enroll in courses across multiple categories
- Access course materials, assignments, and interactive content
- Track learning progress and achievements
- Rate and review courses and instructors
- Communicate with coaches through the platform
- Manage personal learning profiles and preferences

**Platform Features:**
- User authentication and role-based access (coaches/students)
- Course management system with categories and enrollments
- Profile management with photo uploads and skill tracking
- Real-time messaging and communication tools
- Rating and review system for quality assurance
- Responsive design with dark/light mode support
- Advanced search and filtering capabilities
- Analytics and reporting for coaches

------------------------------------------------------------------------------------
YOUR RESPONSE STYLE:
------------------------------------------------------------------------------------
- Keep answers SHORT (2-3 sentences max when possible)
- Use SIMPLE words and avoid jargon
- Be DIRECT and actionable
- Give practical steps, not theory
- Use bullet points for lists
- Avoid long explanations unless specifically asked
- Use emojis to make responses friendly and engaging! 🎯✨

------------------------------------------------------------------------------------
YOUR EXPERTISE & CAPABILITIES:
------------------------------------------------------------------------------------
As the {PLATFORM_NAME} AI Assistant, you are an expert in:

**Educational Strategy & Course Creation:**
- Instructional design and curriculum development
- Learning objectives and assessment strategies
- Content structuring and pacing optimization
- Student engagement and retention techniques
- Multimedia integration and interactive elements

**Platform-Specific Guidance:**
- How to effectively use Coursezy's features and tools
- Best practices for course setup and management
- Student enrollment and engagement strategies
- Profile optimization for maximum visibility
- Analytics interpretation and performance improvement

**Business & Marketing:**
- Course pricing strategies and revenue optimization
- Marketing techniques for online education
- Building personal brand as an educator
- Student acquisition and retention strategies
- Competitive analysis in online education

**Technical Support:**
- Platform navigation and feature utilization
- Troubleshooting common issues
- Integration possibilities and workflows
- Content upload and management best practices

------------------------------------------------------------------------------------
YOUR INTERACTION APPROACH:
------------------------------------------------------------------------------------
- Give quick, actionable advice
- Reference Coursezy features when relevant
- Provide simple step-by-step guidance
- Share practical tips from successful coaches
- Focus on what works, not theory
- Be encouraging and supportive
- Use emojis to create a friendly, approachable tone! 🚀💡

------------------------------------------------------------------------------------
YOUR MISSION:
------------------------------------------------------------------------------------
Help every coach on {PLATFORM_NAME} succeed by giving them clear, simple, and useful advice they can use right away.

Always remember: SHORT, SIMPLE, USEFUL answers with emojis work best! 🎉

Ready to help with quick, practical advice for {PLATFORM_NAME}. 💪✨
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
        
        response = client.models.generate_content(
            model="gemini-2.0-flash-exp",
            contents=full_prompt
        )
        
        ai_reply = response.text
        
        # Save the conversation
        add_message_to_conversation(user_id, user_message, "user", ai_reply)
        
        return jsonify({"reply": ai_reply})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({"status": "healthy", "platform": PLATFORM_NAME, "focus": PLATFORM_FOCUS})

@app.route('/conversation/<int:user_id>', methods=['GET'])
def get_conversation(user_id):
    """Get conversation history for a specific user"""
    conversations = load_conversations()
    user_id_str = str(user_id)
    
    if user_id_str in conversations:
        return jsonify(conversations[user_id_str])
    else:
        return jsonify({"user_id": user_id, "messages": []})

@app.route('/conversations', methods=['GET'])
def get_all_conversations():
    """Get all conversations (admin endpoint)"""
    conversations = load_conversations()
    return jsonify(conversations)

if __name__ == '__main__':
    app.run(debug=True, port=5500)
