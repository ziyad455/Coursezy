#!/bin/bash

echo "🤖 Installing AI Dependencies for Coursezy"
echo "=========================================="
echo ""

# Check if virtual environment exists
if [ ! -d "venv" ]; then
    echo "❌ Virtual environment not found!"
    echo "Creating virtual environment..."
    python3 -m venv venv
fi

# Activate virtual environment
echo "📦 Activating virtual environment..."
source venv/bin/activate

# Upgrade pip
echo "⬆️  Upgrading pip..."
pip install --upgrade pip

# Install required packages
echo "📥 Installing transformers..."
pip install transformers

echo "📥 Installing PyTorch..."
pip install torch

echo "📥 Installing sentence-transformers..."
pip install sentence-transformers

echo "📥 Installing other dependencies..."
pip install flask flask-cors python-dotenv pinecone-client

echo ""
echo "✅ All dependencies installed successfully!"
echo ""
echo "🚀 To start the AI API, run:"
echo "   source venv/bin/activate"
echo "   python app/Http/python/AiApi.py"
echo ""
echo "📝 Note: First run will download the model (~1.5GB)"
echo "   This only happens once!"
