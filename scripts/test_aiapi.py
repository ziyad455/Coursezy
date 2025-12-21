#!/usr/bin/env python3
"""
Test script for AiApi.py to verify model loading and API functionality
"""
import requests
import json
import sys

BASE_URL = "http://localhost:5500"

def test_server_running():
    """Test if the Flask server is running"""
    print("🔍 Testing if server is running...")
    try:
        response = requests.get(f"{BASE_URL}/", timeout=2)
        print("✓ Server is running!")
        return True
    except requests.exceptions.ConnectionError:
        print("✗ Server is not running. Start it with: python app/Http/python/AiApi.py")
        return False
    except Exception as e:
        print(f"✗ Error: {e}")
        return False

def test_chat_endpoint():
    """Test the /chat endpoint"""
    print("\n🔍 Testing /chat endpoint...")
    
    payload = {
        "message": "What is Coursezy?",
        "user_id": 1
    }
    
    try:
        response = requests.post(
            f"{BASE_URL}/chat",
            json=payload,
            headers={"Content-Type": "application/json"},
            timeout=30
        )
        
        if response.status_code == 200:
            data = response.json()
            print("✓ Chat endpoint working!")
            print(f"Response: {data.get('reply', 'No reply')[:200]}...")
            return True
        else:
            print(f"✗ Chat endpoint failed with status {response.status_code}")
            print(f"Error: {response.text}")
            return False
            
    except Exception as e:
        print(f"✗ Error testing chat: {e}")
        return False

def test_generate_sections():
    """Test the /generate-sections endpoint"""
    print("\n🔍 Testing /generate-sections endpoint...")
    
    payload = {
        "description": "Learn Python programming from basics to advanced concepts including data structures and algorithms"
    }
    
    try:
        response = requests.post(
            f"{BASE_URL}/generate-sections",
            json=payload,
            headers={"Content-Type": "application/json"},
            timeout=30
        )
        
        if response.status_code == 200:
            data = response.json()
            print("✓ Generate sections endpoint working!")
            print(f"Generated {len(data.get('sections', []))} sections:")
            for i, section in enumerate(data.get('sections', []), 1):
                print(f"  {i}. {section.get('title')} - {section.get('duration')}")
            return True
        else:
            print(f"✗ Generate sections failed with status {response.status_code}")
            print(f"Error: {response.text}")
            return False
            
    except Exception as e:
        print(f"✗ Error testing generate-sections: {e}")
        return False

def test_search_similar():
    """Test the /search_similar endpoint"""
    print("\n🔍 Testing /search_similar endpoint...")
    
    try:
        response = requests.get(
            f"{BASE_URL}/search_similar",
            params={"description": "Python programming course"},
            timeout=30
        )
        
        if response.status_code == 200:
            data = response.json()
            print("✓ Search similar endpoint working!")
            print(f"Found {len(data.get('similar_descriptions_ids', []))} similar courses")
            return True
        elif response.status_code == 503:
            print("⚠ Pinecone not configured (this is optional)")
            return True
        else:
            print(f"✗ Search similar failed with status {response.status_code}")
            print(f"Error: {response.text}")
            return False
            
    except Exception as e:
        print(f"✗ Error testing search_similar: {e}")
        return False

def main():
    print("=" * 60)
    print("AiApi.py Test Suite")
    print("=" * 60)
    
    # Test if server is running
    if not test_server_running():
        print("\n❌ Server is not running. Please start it first:")
        print("   python app/Http/python/AiApi.py")
        sys.exit(1)
    
    # Run all tests
    results = []
    results.append(("Chat Endpoint", test_chat_endpoint()))
    results.append(("Generate Sections", test_generate_sections()))
    results.append(("Search Similar", test_search_similar()))
    
    # Summary
    print("\n" + "=" * 60)
    print("Test Summary")
    print("=" * 60)
    
    passed = sum(1 for _, result in results if result)
    total = len(results)
    
    for name, result in results:
        status = "✓ PASS" if result else "✗ FAIL"
        print(f"{status} - {name}")
    
    print(f"\nTotal: {passed}/{total} tests passed")
    
    if passed == total:
        print("\n🎉 All tests passed! Your AI API is working correctly.")
        sys.exit(0)
    else:
        print("\n⚠ Some tests failed. Check the errors above.")
        sys.exit(1)

if __name__ == "__main__":
    main()
