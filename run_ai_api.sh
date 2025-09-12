#!/bin/bash

# Script to run the AI API server for Coursezy

echo "🚀 Starting Coursezy AI API Server..."
echo "=================================="

# Navigate to the Python directory
cd /home/ziyad-tber/My_Lravel_Apps/coursezy/app/Http/python

# Activate virtual environment and run the server
./venv/bin/python AiApi.py

echo "AI API Server stopped."
