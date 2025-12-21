
import sys
import os

# Add the directory to sys.path
sys.path.append('/home/ziyad-tber/My_Lravel_Apps/coursezy/app/Http/python')

try:
    from AiApi import get_platform_routes
    
    test_queries = [
        "update my course",
        "change my course",
        "where can I see my money",
        "create a new lesson",
        "how to make a sandwich" # Irrelevant
    ]
    
    print("Testing Semantic Navigation...\n")
    
    for query in test_queries:
        print(f"Query: '{query}'")
        try:
            result = get_platform_routes.invoke(query)
            print(f"Result:\n{result}\n")
        except Exception as e:
            print(f"Error: {e}\n")
            import traceback
            traceback.print_exc()
            
except ImportError as e:
    print(f"Import Error: {e}")
    print("Make sure you are running this from the correct environment.")
