import os
from flask import Flask, request, jsonify
from flask_cors import CORS
from dotenv import load_dotenv
import google.generativeai as genai


load_dotenv()

app = Flask(__name__)
CORS(app)


API_KEY = os.getenv("api")
genai.configure(api_key=API_KEY)


if __name__ == '__main__':
    app.run(debug=True, port=5001)
