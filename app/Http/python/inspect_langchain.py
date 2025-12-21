
import pkgutil
import langchain.agents
import inspect

print("langchain.agents path:", langchain.agents.__path__)

print("\nSubmodules:")
for loader, module_name, is_pkg in pkgutil.walk_packages(langchain.agents.__path__):
    print(f" - {module_name}")

try:
    from langchain.agents import AgentExecutor
    print("\nSUCCESS: Imported AgentExecutor from langchain.agents")
except ImportError as e:
    print(f"\nFAILED to import AgentExecutor from langchain.agents: {e}")

try:
    import langchain.agents.agent
    print("\nSUCCESS: Imported langchain.agents.agent")
except ImportError as e:
    print(f"\nFAILED to import langchain.agents.agent: {e}")
