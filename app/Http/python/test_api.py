#!/usr/bin/env python3
"""
Test script for Flask API endpoints
"""
import requests
import json
import time

# Base URL for the Flask API
BASE_URL = "http://127.0.0.1:5500"

def test_chat_endpoint():
    """Test the /chat endpoint"""
    print("\n" + "="*50)
    print("Testing /chat endpoint...")
    print("="*50)
    
    url = f"{BASE_URL}/chat"
    payload = {
        "message": "Hello, can you help me create a Python course?",
        "user_id": 1
    }
    
    try:
        response = requests.post(url, json=payload)
        print(f"Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"Success! Response:")
            print(f"Reply: {data.get('reply', 'No reply found')[:200]}...")
        else:
            print(f"Error: {response.text}")
    except Exception as e:
        print(f"Failed to connect: {e}")

def test_generate_sections_endpoint():
    """Test the /generate-sections endpoint"""
    print("\n" + "="*50)
    print("Testing /generate-sections endpoint...")
    print("="*50)
    
    url = f"{BASE_URL}/generate-sections"
    payload = {
        "description": "A comprehensive Python programming course covering basics to advanced topics including web development with Flask"
    }
    
    try:
        response = requests.post(url, json=payload)
        print(f"Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"Success! Generated sections:")
            for section in data.get('sections', []):
                print(f"  - {section.get('title')}: {section.get('description')} ({section.get('duration')})")
        else:
            print(f"Error: {response.text}")
    except Exception as e:
        print(f"Failed to connect: {e}")

def test_search_similar_endpoint():
    """Test the /search_similar endpoint"""
    print("\n" + "="*50)
    print("Testing /search_similar endpoint...")
    print("="*50)
    
    url = f"{BASE_URL}/search_similar"
    params = {
        "description": "Learn Python programming"
    }
    
    try:
        response = requests.get(url, params=params)
        print(f"Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            similar_ids = data.get('similar_descriptions_ids', [])
            print(f"Success! Found {len(similar_ids)} similar courses")
            if similar_ids:
                print(f"Similar course IDs: {similar_ids}")
        else:
            print(f"Error: {response.text}")
    except Exception as e:
        print(f"Failed to connect: {e}")

def test_create_vector_endpoint():
    """Test the /create_vector endpoint"""
    print("\n" + "="*50)
    print("Testing /create_vector endpoint...")
    print("="*50)
    
    url = f"{BASE_URL}/create_vector"
    payload = {
        "id": "test_" + str(int(time.time())),
        "description": "This is a test course about learning advanced Python programming with machine learning"
    }
    
    try:
        response = requests.post(url, json=payload)
        print(f"Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"Success! {data.get('message', 'Vector created')}")
            print(f"ID: {data.get('id')}")
        else:
            print(f"Error: {response.text}")
    except Exception as e:
        print(f"Failed to connect: {e}")

def main():
    print("\n" + "="*50)
    print("Flask API Test Suite")
    print("="*50)
    print(f"Testing API at: {BASE_URL}")
    
    # Check if API is running
    try:
        response = requests.get(BASE_URL)
        print("✓ API is reachable")
    except:
        print("✗ API is not running. Please start the Flask app first.")
        print("Run: cd app/Http/python && source venv/bin/activate && python AiApi.py")
        return
    
    # Test each endpoint
    test_chat_endpoint()
    test_generate_sections_endpoint()
    test_create_vector_endpoint()
    test_search_similar_endpoint()
    
    print("\n" + "="*50)
    print("Test suite completed!")
    print("="*50)

if __name__ == "__main__":
    main()