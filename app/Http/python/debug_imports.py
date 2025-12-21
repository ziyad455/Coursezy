
try:
    import langchain
    print("langchain file:", langchain.__file__)
    import langchain.agents
    print("langchain.agents file:", langchain.agents.__file__)
except ImportError as e:
    print("Error:", e)
