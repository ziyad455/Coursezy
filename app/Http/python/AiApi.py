"""
Coursezy AI Agent - JSON Tool-Calling Version
Replaced ReAct agent with intent classification and direct tool calling.
No more parsing errors - LLM outputs natural responses only.
"""

import os
import json
import time
import re
from datetime import datetime
from typing import List, Dict, Any, Optional
from flask import Flask, request, jsonify
from flask_cors import CORS
from dotenv import load_dotenv

from langchain_huggingface import HuggingFaceEndpoint, ChatHuggingFace
from langchain_core.tools import tool
from langchain_core.messages import BaseMessage, HumanMessage, AIMessage, SystemMessage
from langchain_core.chat_history import BaseChatMessageHistory
from pinecone import Pinecone

from langchain_core.callbacks.manager import Callbacks
from langchain_core.caches import BaseCache


try:
    from typing import Union, Optional, List, Dict, Any
    _rebuild_namespace = {
        "Union": Union,
        "Optional": Optional,
        "List": List,
        "Dict": Dict,
        "Any": Any,
        "Callbacks": Callbacks,
        "BaseCache": BaseCache,
    }
    HuggingFaceEndpoint.model_rebuild(_types_namespace=_rebuild_namespace)
    ChatHuggingFace.model_rebuild(_types_namespace=_rebuild_namespace)
except Exception as e:
    print(f"Warning: Model rebuild failed: {e}")

# Load environment variables
load_dotenv()

# --- Configuration ---
app = Flask(__name__)
CORS(app)

HF_API_KEY = os.getenv("HF_API_KEY")
PINECONE_API_KEY = os.getenv("PINECONE_API_KEY")
CONVERSATIONS_FILE = "conversations.json"

# Disable LangSmith tracing if needed
os.environ["LANGCHAIN_TRACING_V2"] = "false"

if not HF_API_KEY:
    print("WARNING: HF_API_KEY not found in environment variables!")

# --- Persistence Layer (Memory) ---

def load_conversations() -> Dict:
    if os.path.exists(CONVERSATIONS_FILE):
        try:
            with open(CONVERSATIONS_FILE, 'r', encoding='utf-8') as f:
                return json.load(f)
        except (json.JSONDecodeError, FileNotFoundError):
            return {}
    return {}

def save_conversations(data: Dict):
    try:
        with open(CONVERSATIONS_FILE, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2, ensure_ascii=False)
    except Exception as e:
        print(f"Error saving conversations: {e}")

class JSONFileChatMessageHistory(BaseChatMessageHistory):
    """
    Chat message history that stores history in a local JSON file.
    Uses legacy format for compatibility: {'sender': 'user'|'ai', 'message': 'content'}
    """
    def __init__(self, session_id: str):
        self.session_id = session_id
        self.messages: List[BaseMessage] = []
        self._load()

    def _load(self):
        data = load_conversations()
        session_data = data.get(self.session_id, {})
        raw_msgs = session_data.get("messages", [])
        self.messages = []
        for msg in raw_msgs:
            # Handle both legacy and new formats
            role = msg.get("sender") or msg.get("type")
            content = msg.get("message") or msg.get("content")
            
            if role in ["user", "human"]:
                self.messages.append(HumanMessage(content=content))
            elif role in ["ai", "assistant"]:
                self.messages.append(AIMessage(content=content))

    def add_message(self, message: BaseMessage) -> None:
        self.messages.append(message)
        self._save()

    def _save(self):
        data = load_conversations()
        
        serialized_msgs = []
        for msg in self.messages:
            sender = "user" if isinstance(msg, HumanMessage) else "ai"
            serialized_msgs.append({
                "sender": sender,
                "message": msg.content,
                "timestamp": datetime.now().isoformat()
            })
            
        data[self.session_id] = {
            "updated_at": datetime.now().isoformat(),
            "messages": serialized_msgs
        }
        save_conversations(data)

    def clear(self) -> None:
        self.messages = []
        self._save()

def get_session_history(session_id: str) -> BaseChatMessageHistory:
    return JSONFileChatMessageHistory(session_id)

# --- Tools ---

@tool
def get_platform_routes(query: str = "") -> str:
    """
    Returns the most relevant platform routes based on semantic similarity to the user's query.
    """
    try:
        from sentence_transformers import util
        import torch

        model = get_embedding_model()
        route_embeddings = get_route_embeddings()
        
        # 1. Encode user query
        query_embedding = model.encode(query, convert_to_tensor=True)
        
        # 2. Compute cosine similarities
        cosine_scores = util.cos_sim(query_embedding, route_embeddings)[0]
        
        # 3. Rank routes
        # Map scores to route keys
        route_keys = list(_routes_data.keys())
        scored_routes = []
        for i, score in enumerate(cosine_scores):
            scored_routes.append({
                "key": route_keys[i],
                "score": score.item(),
                "data": _routes_data[route_keys[i]]
            })
            
        # Sort by score desc
        scored_routes.sort(key=lambda x: x["score"], reverse=True)
        
        # 4. Filter and Select
        # Thresholds
        SIMILARITY_THRESHOLD = 0.35  # Minimum score to be considered relevant
        
        matches = [r for r in scored_routes if r["score"] >= SIMILARITY_THRESHOLD]
        
        # If no matches found or query is very generic, return a helpful message
        if not matches:
             # Try a lower fallback for very loose matches? Or just suggest top 3
             # For now, if nothing matches well, we don't return anything to avoid noise,
             # UNLESS it's a "list all" type query which should be handled by 'courses_list' or handled by intent classification.
             # But if the user intent was "navigation", we probably want to show *something*.
             # Let's show top 3 if they are at least somewhat relevant (> 0.2), else generic.
             
             matches = [r for r in scored_routes if r["score"] >= 0.25]
             if not matches:
                 return "I'm not sure which page you're looking for. Could you describe it differently? (e.g., 'edit course', 'check payouts', 'go to dashboard')"
                 
        # Limit to top 3 to avoid clutter
        top_matches = matches[:3]

        # Build HTML output
        output = "<strong>Suggested Pages:</strong><br><br>"
        for match in top_matches:
            route = match["data"]
            # Optional: Show score for debugging? No, keep it clean for user.
            output += (
                f"• <a href='{route['url']}' "
                f"style='color:#5A67D8; font-weight:600; text-decoration:none;' "
                f"target='_blank'>{route['description']}</a><br>"
            )

        output += "<br><em>Click any link to navigate directly.</em>"
        return output

    except Exception as e:
        print(f"Navigation error: {e}")
        return f"Error loading navigation links: {e}"


# Global embedding model cache
_embedding_model = None

def get_embedding_model():
    global _embedding_model
    if _embedding_model is None:
        try:
            from sentence_transformers import SentenceTransformer
            print("Loading embedding model (all-mpnet-base-v2)...")
            _embedding_model = SentenceTransformer('all-mpnet-base-v2')
        except ImportError:
            raise ImportError("sentence-transformers not installed.")
    return _embedding_model

# Cache for route embeddings
_route_embeddings_cache = None
_routes_data = {
    "create_course": {"url": "/courses/add", "description": "Create and publish a new course, start a new class, add a curriculum"},
    "courses_list": {"url": "/courses/index", "description": "View and manage all your courses, see my classes, list of created courses"},
    "edit_course": {"url": "/courses/index", "description": "Edit details of a specific course, change course settings, update content, modify class"},
    "course_sections": {"url": "/courses/index", "description": "Manage course sections, organize chapters, structure the curriculum"},
    "add_lessons": {"url": "/courses/index", "description": "Add lessons to course sections, upload video, create content for students"},
    "inbox": {"url": "/inbox", "description": "View and send messages to students, chat, check inbox, communicate"},
    "profile": {"url": "/accont", "description": "View and update your account information, profile settings, user details, change password"},
    "dashboard": {"url": "/coach/dashboard", "description": "Overview of your courses and students, instructor dashboard, main hub, home"},
    "payouts": {"url": "/courses/index", "description": "Manage course earnings and withdrawals, check revenue, see money, payments"}
}

def get_route_embeddings():
    global _route_embeddings_cache
    if _route_embeddings_cache is None:
        model = get_embedding_model()
        descriptions = [val["description"] for val in _routes_data.values()]
        # Pre-compute embeddings for all route descriptions
        _route_embeddings_cache = model.encode(descriptions, convert_to_tensor=True)
    return _route_embeddings_cache


def create_local_embedding(content: str) -> List[float]:
    model = get_embedding_model()
    embedding = model.encode(content, convert_to_tensor=False)
    if hasattr(embedding, 'tolist'):
        embedding = embedding.tolist()
    return embedding

@tool
def search_similar_content(query: str) -> str:
    """
    Searches for similar courses or content using vector search.
    Use this when the user asks to find courses, recommends content, or looks for specific topics.
    """
    if not PINECONE_API_KEY:
        return "I'm sorry, but the search feature is currently unavailable. Please try again later."
    
    try:
        pc = Pinecone(api_key=PINECONE_API_KEY)
        index = pc.Index("cours-index")
        
        embedding = create_local_embedding(query)
        results = index.query(
            vector=embedding,
            top_k=3,
            include_metadata=True,
            include_values=False
        )
        
        matches = []
        for match in results.matches:
            if match.score > 0.65:
                meta = match.metadata or {}
                title = meta.get('title', 'Untitled Course')
                description = meta.get('description', 'No description available')
                matches.append(f"• <strong>{title}</strong> (relevance: {match.score:.0%})<br>  {description}")
        
        if not matches:
            return f"I couldn't find any courses matching '{query}'. Try using different keywords or browse our course catalog."
            
        return f"Here are courses related to '{query}':<br><br>" + "<br><br>".join(matches)
    except Exception as e:
        return f"I encountered an error while searching. Please try again later."

@tool
def generate_course_sections(description: str) -> str:
    """
    Generates a structured course outline with sections based on a description.
    Use this ONLY when the user explicitly asks to generate/create/outline sections for a course.
    """
    return f"""Based on your request for '{description}', here's a suggested course outline:<br><br>
    <strong>1. Introduction to the Topic</strong><br>
    &nbsp;&nbsp;&nbsp;&nbsp;<em>Basics and Overview</em> • Duration: 30 minutes<br><br>
    <strong>2. Core Concepts & Theory</strong><br>
    &nbsp;&nbsp;&nbsp;&nbsp;<em>Deep dive into main ideas</em> • Duration: 60 minutes<br><br>
    <strong>3. Practical Application</strong><br>
    &nbsp;&nbsp;&nbsp;&nbsp;<em>Hands-on exercises and examples</em> • Duration: 90 minutes<br><br>
    <strong>4. Advanced Topics & Project</strong><br>
    &nbsp;&nbsp;&nbsp;&nbsp;<em>Final project and advanced techniques</em> • Duration: 120 minutes
    """

# --- JSON Tool-Calling Agent (Replaces ReAct) ---

# Initialize LLM
hf_model = "meta-llama/Llama-3.2-3B-Instruct"

# Use HuggingFaceEndpoint directly
endpoint = HuggingFaceEndpoint(
    repo_id=hf_model,
    max_new_tokens=512,
    huggingfacehub_api_token=HF_API_KEY,
)

llm = ChatHuggingFace(llm=endpoint, temperature=0.1)

# Define tools registry
TOOLS_REGISTRY = {
    "get_platform_routes": get_platform_routes,
    "search_similar_content": search_similar_content,
    "generate_course_sections": generate_course_sections,
}

# Intent classification patterns
# Greeting patterns - ONLY match pure greetings (no follow-up content)
GREETING_PATTERNS = [
    r'^(hi|hello|hey|greetings|good\s*(morning|afternoon|evening)|salut|bonjour|hola)\s*[!?.]*$',
    r'^(how\s+are\s+you|what\'?s\s+up|howdy)\s*[!?.]*$',
    r'^(thanks|thank\s+you|thx)\s*[!?.]*$',
    r'^(bye|goodbye|see\s+you|later)\s*[!?.]*$',
]

NAVIGATION_PATTERNS = [
    r'\b(where|how)\b.*(create|add|make|new)\s*(course|lesson|section)',
    r'\b(navigate|go\s+to|find|show\s+me|take\s+me)\b.*(page|section|dashboard|inbox|profile|course)',
    r'\b(where|how)\s+(can\s+i|do\s+i|to)\b.*(create|edit|manage|view|add)',
    r'\bcreate\s+(a\s+)?(new\s+)?course\b',
    r'\b(dashboard|inbox|profile|settings|account)\b',
]

SEARCH_PATTERNS = [
    r'\b(find|search|look\s+for|recommend|suggest)\b.*(course|topic|content|tutorial)',
    r'\b(courses?\s+(about|on|for|related))\b',
    r'\bwhat\s+courses?\b',
]

COURSE_CREATION_PATTERNS = [
    r'\b(generate|create|outline|structure|plan)\b.*(sections?|outline|curriculum|syllabus)',
    r'\b(help\s+me\s+)?(outline|structure|plan)\s+(a\s+)?(course|curriculum)\b',
]


# Session state for follow-up handling
_session_last_intent = {}  # {session_id: {"intent": str, "timestamp": float}}
INTENT_TTL_SECONDS = 120  # Expire after 2 minutes
REUSABLE_INTENTS = {"search", "course_creation"}  # Only these can be reused for follow-ups
AFFIRMATIVE_PATTERNS = r'^(yes|yeah|yep|sure|ok|okay|that\s+one|show\s+me|more|please|go\s+ahead)\b'


def get_last_intent(session_id: str) -> Optional[str]:
    """Get the last intent for a session, with TTL check."""
    data = _session_last_intent.get(session_id)
    if not data:
        return None
    if time.time() - data["timestamp"] > INTENT_TTL_SECONDS:
        clear_last_intent(session_id)
        return None
    return data["intent"]


def set_last_intent(session_id: str, intent: str):
    """Store the last intent for a session."""
    _session_last_intent[session_id] = {
        "intent": intent,
        "timestamp": time.time()
    }


def clear_last_intent(session_id: str):
    """Clear the last intent for a session."""
    _session_last_intent.pop(session_id, None)


def classify_intent(message: str, session_id: str = None) -> str:
    """
    Classifies user intent to determine if tools are needed.
    Returns: 'greeting', 'navigation', 'search', 'course_creation', or 'conversation'
    
    Priority order (most specific first):
    1. Affirmative follow-up (reuse last intent if whitelisted)
    2. Course creation
    3. Search
    4. Navigation  
    5. Greeting (only pure greetings)
    6. Conversation (fallback)
    """
    message_lower = message.lower().strip()
    
    # Check for affirmative follow-up first (only for whitelisted intents)
    if session_id and len(message) < 25:
        if re.match(AFFIRMATIVE_PATTERNS, message_lower):
            last = get_last_intent(session_id)
            if last and last in REUSABLE_INTENTS:
                print(f"Reusing last intent '{last}' for affirmative follow-up")
                return last
    
    # Check course creation patterns (most specific)
    for pattern in COURSE_CREATION_PATTERNS:
        if re.search(pattern, message_lower, re.IGNORECASE):
            return "course_creation"
    
    # Check search patterns
    for pattern in SEARCH_PATTERNS:
        if re.search(pattern, message_lower, re.IGNORECASE):
            return "search"
    
    # Check navigation patterns
    for pattern in NAVIGATION_PATTERNS:
        if re.search(pattern, message_lower, re.IGNORECASE):
            return "navigation"
    
    # Check greeting patterns (only pure greetings, no follow-up content)
    for pattern in GREETING_PATTERNS:
        if re.search(pattern, message_lower, re.IGNORECASE):
            return "greeting"
    
    return "conversation"


def get_tool_for_intent(intent: str) -> Optional[str]:
    """Maps intent to appropriate tool name"""
    mapping = {
        "navigation": "get_platform_routes",
        "search": "search_similar_content",
        "course_creation": "generate_course_sections",
    }
    return mapping.get(intent)


def extract_search_query(message: str) -> str:
    """
    Extract clean search query from natural language.
    E.g., "find courses about React" -> "React"
    """
    patterns = [
        r'(?:find|search|look\s+for|recommend|suggest).*?(?:courses?|content|tutorials?).*?(?:about|on|for|related\s+to)\s+(.+)',
        r'(?:courses?|content|tutorials?)\s+(?:about|on|for)\s+(.+)',
        r'(?:find|search|recommend)\s+(.+?)\s+(?:courses?|tutorials?)',
    ]
    for pattern in patterns:
        match = re.search(pattern, message, re.IGNORECASE)
        if match:
            return match.group(1).strip().rstrip('?.!')
    # Fallback: remove common prefixes and return
    cleaned = re.sub(r'^.*?(?:find|search|recommend|courses?)\s*', '', message, flags=re.IGNORECASE)
    return cleaned.strip() if cleaned.strip() else message


def extract_course_topic(message: str) -> str:
    """
    Extract course topic for section generation.
    E.g., "generate sections for a React course" -> "React"
    """
    patterns = [
        r'(?:sections?|outline|curriculum|syllabus)\s+(?:for|about)\s+(?:a\s+)?(?:course\s+(?:on|about)\s+)?(.+?)(?:\s+course)?$',
        r'(?:course|sections?)\s+(?:about|on)\s+(.+)',
        r'(?:generate|create|outline|structure).*?(?:for|about)\s+(?:a\s+)?(.+?)(?:\s+course)?$',
        r'(?:help\s+me\s+)?(?:with\s+)?(?:a\s+)?(.+?)\s+(?:course|curriculum|outline)',
    ]
    for pattern in patterns:
        match = re.search(pattern, message, re.IGNORECASE)
        if match:
            topic = match.group(1).strip().rstrip('?.!')
            # Clean up common words
            topic = re.sub(r'^(a|an|the|my)\s+', '', topic, flags=re.IGNORECASE)
            return topic if topic else message
    # Fallback: return the message
    return message


# System prompt for the JSON tool-calling agent
SYSTEM_PROMPT = """You are Coursezy AI, a helpful assistant for the Coursezy education platform. You help users navigate the platform, create courses, and find content.

IMPORTANT RULES:
1. Be friendly and conversational for greetings and general questions
2. When you receive tool results, incorporate them naturally into your response
3. Keep responses concise and helpful
4. Never make up URLs - only use information from tool results

You are here to help users with:
- Navigating the Coursezy platform
- Creating and managing courses
- Finding courses and content
- General questions about the platform"""

# Navigation-specific prompt (LLM must NOT see or rewrite navigation links)
NAVIGATION_EXPLANATION_PROMPT = """You are explaining how to navigate the Coursezy platform.

CRITICAL RULES:
- Do NOT include any links or URLs in your response
- Do NOT mention specific URLs or href values
- Do NOT invent UI elements like "search bar", "tab", "header", "menu", "button", "sidebar", "navbar"
- Do NOT say "click on" anything - the links will be provided separately
- Keep your explanation to 1-2 sentences maximum
- Just briefly describe what the page/feature is for

Example good response: "This page allows you to create and publish new courses on Coursezy."
Example bad response: "Click on the 'Courses' tab in the header to access this page."

The actual navigation links will be provided separately below your response."""


def invoke_agent(user_message: str, session_id: str) -> str:
    """
    Main agent invocation function using JSON tool-calling.
    This replaces the entire AgentExecutor + ReAct architecture.
    """
    start_time = time.time()
    
    # Get chat history
    history = get_session_history(session_id)
    
    # Classify intent (now with session awareness for follow-ups)
    intent = classify_intent(user_message, session_id)
    print(f"Intent classified as: {intent}")
    
    # Clear last intent for non-reusable intents
    if intent in ["greeting", "conversation"]:
        clear_last_intent(session_id)
    
    # Determine if we need to use a tool
    tool_name = get_tool_for_intent(intent)
    tool_result = None
    
    if tool_name and tool_name in TOOLS_REGISTRY:
        print(f"Executing tool: {tool_name}")
        try:
            tool_func = TOOLS_REGISTRY[tool_name]
            
            # Extract clean argument based on intent
            if tool_name == "search_similar_content":
                tool_arg = extract_search_query(user_message)
                print(f"Extracted search query: '{tool_arg}'")
            elif tool_name == "generate_course_sections":
                tool_arg = extract_course_topic(user_message)
                print(f"Extracted course topic: '{tool_arg}'")
            else:
                tool_arg = user_message
            
            tool_result = tool_func.invoke(tool_arg)
            print(f"Tool execution successful")
            
            # Clear last intent after successful tool execution
            clear_last_intent(session_id)
        except Exception as e:
            print(f"Tool execution error: {e}")
            tool_result = None
    
    # Store current intent for potential follow-up (only if reusable)
    if intent in REUSABLE_INTENTS:
        set_last_intent(session_id, intent)
    
    # Build messages for LLM
    messages = [SystemMessage(content=SYSTEM_PROMPT)]
    
    # Add chat history (last 6 messages for context)
    for msg in history.messages[-6:]:
        messages.append(msg)
    
    # Add current user message
    messages.append(HumanMessage(content=user_message))
    
    # Handle tool results based on intent type
    # IMPORTANT: Navigation HTML must NEVER be shown to LLM (it will rewrite it)
    if intent == "navigation":
        # Add navigation-specific prompt instead of tool result
        messages.append(SystemMessage(content=NAVIGATION_EXPLANATION_PROMPT))
    elif tool_result:
        # For non-navigation tools, inject result for LLM to incorporate
        context_message = f"[Tool Result - incorporate this information naturally in your response]\n{tool_result}"
        messages.append(SystemMessage(content=context_message))
    
    # Invoke LLM
    try:
        response = llm.invoke(messages)
        output = response.content if hasattr(response, 'content') else str(response)
        
        # Clean up output
        output = output.strip()
        
        # Remove any JSON artifacts that might have leaked through
        if output.startswith('{') and '"tool"' in output:
            # LLM accidentally output JSON, extract any text after it
            try:
                json_end = output.rfind('}') + 1
                if json_end < len(output):
                    output = output[json_end:].strip()
                else:
                    # Pure JSON output, generate a natural response
                    output = "I'm here to help! What would you like to know about Coursezy?"
            except:
                pass
        
    except Exception as e:
        print(f"LLM invocation error: {e}")
        output = "I apologize, but I'm experiencing a technical issue. Please try again in a moment."
    
    # For navigation intent: append raw tool HTML AFTER LLM explanation
    # This ensures links are never rewritten by the LLM
    if intent == "navigation" and tool_result:
        output = output + "<br><br>" + tool_result
    
    # Save to history
    history.add_message(HumanMessage(content=user_message))
    history.add_message(AIMessage(content=output))
    
    elapsed = time.time() - start_time
    print(f"Agent completed in {elapsed:.2f}s")
    
    return output


# --- Flask Endpoints ---

@app.route('/chat', methods=['POST'])
def chat():
    if not HF_API_KEY:
        return jsonify({"error": "AI service is currently unavailable. Please try again later."}), 503
        
    data = request.json
    user_message = data.get("message")
    user_id = data.get("user_id")
    
    if not user_message or not user_id:
        return jsonify({"error": "Missing required information"}), 400
        
    print(f"Processing chat request - User: {user_id}, Message: {user_message[:100]}...")
        
    try:
        output = invoke_agent(user_message, str(user_id))
        print(f"Agent response successful - Output length: {len(output)}")
        return jsonify({"reply": output})
        
    except Exception as e:
        print(f"Agent error: {str(e)[:200]}...")
        import traceback
        traceback.print_exc()
        return jsonify({"reply": "I apologize, but I'm experiencing a technical issue. Please try again in a moment."})

@app.route('/generate-sections', methods=['POST'])
def generate_sections_endpoint():
    """
    Direct endpoint for generating sections, keeping compatibility.
    """
    description = request.json.get("description")
    if not description:
        return jsonify({"error": "Please provide a course description"}), 400
        
    try:
        # Calling the function logic directly
        sections_text = generate_course_sections.invoke(description)
        
        # Parse for legacy frontend compatibility if needed
        parsed_sections = []
        lines = sections_text.split('<br>')
        for line in lines:
            line = line.strip()
            if line and '<strong>' in line and '</strong>' in line:
                # Extract title, description, and duration from HTML
                title_start = line.find('<strong>') + 8
                title_end = line.find('</strong>')
                title = line[title_start:title_end]
                
                # Extract description and duration (simplified parsing)
                remaining = line[title_end + 9:]
                if '<em>' in remaining and '</em>' in remaining:
                    desc_start = remaining.find('<em>') + 4
                    desc_end = remaining.find('</em>')
                    description_text = remaining[desc_start:desc_end]
                    
                    duration_text = remaining[desc_end + 5:].strip()
                    if 'Duration:' in duration_text:
                        duration = duration_text.split('Duration:')[1].strip()
                    else:
                        duration = "Flexible"
                        
                    parsed_sections.append({
                        "title": title,
                        "description": description_text,
                        "duration": duration
                    })
        
        return jsonify({
            "sections": parsed_sections,
            "original_response": sections_text
        })
    except Exception as e:
        print(f"Error in generate_sections: {e}")
        return jsonify({"error": "Unable to generate course sections. Please try again."}), 500

# Pinecone Vector Endpoints remain unchanged

# Initialize Pinecone global index
pinecone_index = None

def get_pinecone_index():
    global pinecone_index
    if pinecone_index is None and PINECONE_API_KEY:
        try:
            pc = Pinecone(api_key=PINECONE_API_KEY)
            pinecone_index = pc.Index("cours-index")
        except Exception as e:
            print(f"Pinecone init failed: {e}")
    return pinecone_index

@app.route('/create_vector', methods=['POST'])
def create_vector_endpoint():
    idx = get_pinecone_index()
    if not idx:
        return jsonify({"error": "Search service is currently unavailable"}), 503
        
    data = request.json
    pid = data.get('id')
    desc = data.get('description')
    
    if not pid or not desc:
        return jsonify({"error": "Both id and description are required"}), 400
        
    try:
        embedding = create_local_embedding(desc)
        idx.upsert(vectors=[(str(pid), embedding)])
        return jsonify({"message": "Course successfully indexed for search", "id": pid})
    except Exception as e:
        return jsonify({"error": "Unable to index course. Please try again."}), 500

@app.route('/search_similar', methods=['GET'])
def search_similar_endpoint():
    idx = get_pinecone_index()
    if not idx:
        return jsonify({"error": "Search service is currently unavailable"}), 503
        
    query = request.args.get('description')
    if not query:
        return jsonify({"error": "Search query is required"}), 400
        
    try:
        embedding = create_local_embedding(query)
        results = idx.query(
            vector=embedding,
            top_k=5,
            include_metadata=False
        )
        ids = [m.id for m in results.matches if m.score > 0.7]
        return jsonify({"similar_descriptions_ids": ids})
    except Exception as e:
        return jsonify({"error": "Search failed. Please try again."}), 500

if __name__ == '__main__':
    print("Starting Coursezy AI Agent Server (JSON Tool-Calling Version)...")
    print("Agent configured with:")
    print(f"- Model: {hf_model}")
    print(f"- Tools: {len(TOOLS_REGISTRY)} available")
    print(f"- Temperature: 0.1")
    print(f"- Intent Classification: ENABLED")
    print(f"- JSON Tool-Calling: ENABLED")
    print(f"- ReAct: DISABLED (replaced)")
    app.run(debug=True, port=5500)