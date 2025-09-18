#!/usr/bin/env python3
"""
Test script to diagnose Google Generative AI API issues
"""
import os
import sys
from dotenv import load_dotenv
import google.generativeai as genai

# Load environment variables
load_dotenv()

def test_api_key():
    """Test if the Google API key is valid and working"""
    
    # Get API key
    api_key = os.getenv("api")
    
    if not api_key:
        print("❌ ERROR: API key not found in .env file")
        print("Make sure you have 'api=YOUR_API_KEY' in your .env file")
        return False
    
    print(f"✓ API Key found: {api_key[:10]}...")
    
    try:
        # Configure the API
        genai.configure(api_key=api_key)
        print("✓ API configured successfully")
        
        # Try to create a simple model instance
        model = genai.GenerativeModel('gemini-pro')
        print("✓ Model instance created successfully")
        
        # Try to generate a simple response
        response = model.generate_content("Say hello in one word")
        print(f"✓ API Response successful: {response.text}")
        
        return True
        
    except Exception as e:
        print(f"❌ ERROR: {str(e)}")
        
        if "API_KEY_INVALID" in str(e):
            print("\nThe API key appears to be invalid. Please:")
            print("1. Check if the key is correct")
            print("2. Verify the key is enabled in Google Cloud Console")
            print("3. Make sure the Generative Language API is enabled for your project")
            
        elif "quota" in str(e).lower() or "429" in str(e):
            print("\nAPI quota exceeded. Please:")
            print("1. Wait a few minutes and try again")
            print("2. Check your quota limits in Google Cloud Console")
            print("3. Consider upgrading your API plan if needed")
            
        return False

def test_pinecone():
    """Test if Pinecone API is configured"""
    pinecone_key = os.getenv("PINECONE_API_KEY")
    
    if not pinecone_key:
        print("\n⚠️  WARNING: Pinecone API key not found")
        return False
    
    print(f"\n✓ Pinecone API Key found: {pinecone_key[:10]}...")
    return True

if __name__ == "__main__":
    print("=" * 60)
    print("Google Generative AI API Diagnostic Test")
    print("=" * 60)
    
    # Test Google API
    google_ok = test_api_key()
    
    # Test Pinecone
    pinecone_ok = test_pinecone()
    
    print("\n" + "=" * 60)
    if google_ok:
        print("✅ Google API is working correctly!")
    else:
        print("❌ Google API has issues - please fix them before running the server")
        print("\nTo get a new API key:")
        print("1. Go to https://makersuite.google.com/app/apikey")
        print("2. Create a new API key")
        print("3. Update the 'api' value in your .env file")
    
    if not pinecone_ok:
        print("\n⚠️  Pinecone is not configured (optional for vector search)")
    
    print("=" * 60)
    
    sys.exit(0 if google_ok else 1)