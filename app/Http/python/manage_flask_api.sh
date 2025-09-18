#!/bin/bash
# Flask API Management Script for Coursezy

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
VENV_PATH="$SCRIPT_DIR/venv"
API_FILE="$SCRIPT_DIR/AiApi.py"
PID_FILE="$SCRIPT_DIR/flask_api.pid"
LOG_FILE="$SCRIPT_DIR/flask_api.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

function check_status() {
    if [ -f "$PID_FILE" ]; then
        PID=$(cat "$PID_FILE")
        if ps -p $PID > /dev/null 2>&1; then
            echo -e "${GREEN}✓ Flask API is running (PID: $PID)${NC}"
            echo "  URL: http://127.0.0.1:5500"
            return 0
        else
            echo -e "${YELLOW}⚠ PID file exists but process is not running${NC}"
            rm "$PID_FILE"
            return 1
        fi
    else
        echo -e "${RED}✗ Flask API is not running${NC}"
        return 1
    fi
}

function start_api() {
    if check_status > /dev/null 2>&1; then
        echo -e "${YELLOW}Flask API is already running${NC}"
        return 0
    fi
    
    echo -e "${GREEN}Starting Flask API...${NC}"
    
    # Activate virtual environment and start Flask
    cd "$SCRIPT_DIR"
    source "$VENV_PATH/bin/activate"
    nohup python "$API_FILE" > "$LOG_FILE" 2>&1 &
    echo $! > "$PID_FILE"
    
    sleep 2
    
    if check_status > /dev/null 2>&1; then
        echo -e "${GREEN}✓ Flask API started successfully${NC}"
        echo "  Check logs at: $LOG_FILE"
        return 0
    else
        echo -e "${RED}✗ Failed to start Flask API${NC}"
        echo "  Check logs at: $LOG_FILE"
        return 1
    fi
}

function stop_api() {
    if [ -f "$PID_FILE" ]; then
        PID=$(cat "$PID_FILE")
        if ps -p $PID > /dev/null 2>&1; then
            echo -e "${YELLOW}Stopping Flask API (PID: $PID)...${NC}"
            kill $PID
            rm "$PID_FILE"
            echo -e "${GREEN}✓ Flask API stopped${NC}"
        else
            echo -e "${YELLOW}Process not found, cleaning up PID file${NC}"
            rm "$PID_FILE"
        fi
    else
        echo -e "${YELLOW}Flask API is not running${NC}"
    fi
}

function restart_api() {
    echo -e "${YELLOW}Restarting Flask API...${NC}"
    stop_api
    sleep 1
    start_api
}

function show_logs() {
    if [ -f "$LOG_FILE" ]; then
        echo -e "${GREEN}Showing last 50 lines of log:${NC}"
        tail -n 50 "$LOG_FILE"
    else
        echo -e "${YELLOW}No log file found${NC}"
    fi
}

function test_endpoints() {
    echo -e "${GREEN}Testing Flask API endpoints...${NC}"
    
    # Check if API is running
    if ! check_status > /dev/null 2>&1; then
        echo -e "${RED}Flask API is not running. Start it first with: $0 start${NC}"
        return 1
    fi
    
    echo ""
    echo -e "${YELLOW}1. Testing /chat endpoint...${NC}"
    curl -s -X POST http://127.0.0.1:5500/chat \
        -H "Content-Type: application/json" \
        -d '{"message": "Hello", "user_id": 1}' | python3 -m json.tool | head -5
    
    echo ""
    echo -e "${YELLOW}2. Testing /generate-sections endpoint...${NC}"
    curl -s -X POST http://127.0.0.1:5500/generate-sections \
        -H "Content-Type: application/json" \
        -d '{"description": "Python basics"}' | python3 -m json.tool | head -10
    
    echo ""
    echo -e "${GREEN}✓ Tests completed${NC}"
}

function show_usage() {
    echo "Flask API Management Script for Coursezy"
    echo ""
    echo "Usage: $0 {start|stop|restart|status|logs|test}"
    echo ""
    echo "Commands:"
    echo "  start    - Start the Flask API server"
    echo "  stop     - Stop the Flask API server"
    echo "  restart  - Restart the Flask API server"
    echo "  status   - Check if the API is running"
    echo "  logs     - Show recent log entries"
    echo "  test     - Test API endpoints"
}

# Main script logic
case "$1" in
    start)
        start_api
        ;;
    stop)
        stop_api
        ;;
    restart)
        restart_api
        ;;
    status)
        check_status
        ;;
    logs)
        show_logs
        ;;
    test)
        test_endpoints
        ;;
    *)
        show_usage
        ;;
esac