import os
from flask import Flask, request, jsonify
from flask_cors import CORS
from dotenv import load_dotenv
import google.generativeai as genai
from pinecone import Pinecone

load_dotenv()

app = Flask(__name__)
CORS(app)

# إعدادات API keys
API_KEY = os.getenv("api")
genai.configure(api_key=API_KEY)

PINECONE_API_KEY = os.getenv("PINECONE_API_KEY")
pc = Pinecone(api_key=PINECONE_API_KEY)

INDEX_NAME = "cours-index"

# الاتصال بالفهرس الموجود
index = pc.Index(INDEX_NAME)
print(f"Connected to existing index: {INDEX_NAME}")

# API 1: إنشاء vector وحفظه
@app.route('/create_vector', methods=['POST'])
def create_vector():
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

        # توليد embedding من Google AI
        result = genai.embed_content(
            model="models/embedding-001",
            content=clean_description
        )
        embedding = result['embedding']
        
        # تحقق من البعد
        print(f"Embedding dimension: {len(embedding)}")

        # حفظ المتجه في Pinecone
        index.upsert(vectors=[(str(product_id), embedding)])

        return jsonify({"message": "Vector created and saved", "id": product_id})
    
    except Exception as e:
        error_msg = str(e)
        print(f"Error in create_vector: {error_msg}")
        return jsonify({"error": f"Vector creation failed: {error_msg}"}), 500

# API 2: البحث عن أوصاف مشابهة
@app.route('/search_similar', methods=['GET'])
def search_similar():
    query = request.args.get('description')
    if not query:
        return jsonify({"error": "description query parameter is required"}), 400

    try:
        # توليد embedding للاستعلام
        result = genai.embed_content(
            model="models/embedding-001",
            content=query
        )
        query_embedding = result['embedding']

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
    app.run(debug=True, port=5001)
