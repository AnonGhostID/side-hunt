#!/bin/bash

# Laravel Server Manager Script
# Port: 8899

# Colors for better readability
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Server configuration
HOST="127.0.0.1"
PORT="8899"
LOG_FILE="storage/logs/laravel-server.log"

# Function to display menu
show_menu() {
    clear
    echo -e "${BLUE}=====================================${NC}"
    echo -e "${BLUE}    Laravel Server Manager (${PORT})${NC}"
    echo -e "${BLUE}=====================================${NC}"
    echo
    echo "1) Start Server"
    echo "2) Check Server Status"
    echo "3) Stop Server"
    echo "4) Restart Server"
    echo "5) View Server Logs"
    echo "6) Test Server Connection"
    echo "7) View Live Logs (follow mode)"
    echo "8) Exit"
    echo
    echo -n "Enter your choice [1-8]: "
}

# Function to start server
start_server() {
    echo -e "${YELLOW}Starting Laravel server on port ${PORT}...${NC}"
    
    # Check if already running
    if pgrep -f "php artisan serve.*${PORT}" > /dev/null; then
        echo -e "${RED}Server is already running on port ${PORT}!${NC}"
    else
        nohup php artisan serve --host=${HOST} --port=${PORT} > ${LOG_FILE} 2>&1 &
        sleep 2
        
        # Verify if started successfully
        if pgrep -f "php artisan serve.*${PORT}" > /dev/null; then
            echo -e "${GREEN}Server started successfully!${NC}"
        else
            echo -e "${RED}Failed to start server. Check the logs for details.${NC}"
        fi
    fi
}

# Function to check server status
check_status() {
    echo -e "${YELLOW}Checking server status...${NC}"
    echo
    
    if pgrep -f "php artisan serve.*${PORT}" > /dev/null; then
        echo -e "${GREEN}Server is RUNNING${NC}"
        echo
        echo "Process details:"
        ps aux | grep "php artisan serve" | grep -v grep
    else
        echo -e "${RED}Server is NOT RUNNING${NC}"
    fi
}

# Function to stop server
stop_server() {
    echo -e "${YELLOW}Stopping Laravel server...${NC}"
    
    if pgrep -f "php artisan serve.*${PORT}" > /dev/null; then
        pkill -f "php artisan serve.*${PORT}"
        sleep 2
        
        # Verify if stopped successfully
        if ! pgrep -f "php artisan serve.*${PORT}" > /dev/null; then
            echo -e "${GREEN}Server stopped successfully!${NC}"
        else
            echo -e "${RED}Failed to stop server. Try manual kill.${NC}"
        fi
    else
        echo -e "${YELLOW}Server is not running.${NC}"
    fi
}

# Function to restart server
restart_server() {
    echo -e "${YELLOW}Restarting Laravel server...${NC}"
    
    # Stop server
    if pgrep -f "php artisan serve.*${PORT}" > /dev/null; then
        pkill -f "php artisan serve.*${PORT}"
        echo "Stopping server..."
        sleep 2
    fi
    
    # Start server
    echo "Starting server..."
    nohup php artisan serve --host=${HOST} --port=${PORT} > ${LOG_FILE} 2>&1 &
    sleep 2
    
    # Verify if started successfully
    if pgrep -f "php artisan serve.*${PORT}" > /dev/null; then
        echo -e "${GREEN}Server restarted successfully!${NC}"
    else
        echo -e "${RED}Failed to restart server. Check the logs for details.${NC}"
    fi
}

# Function to view logs
view_logs() {
    echo -e "${YELLOW}Displaying last 20 lines of server logs:${NC}"
    echo -e "${BLUE}========================================${NC}"
    
    if [ -f "${LOG_FILE}" ]; then
        tail -20 ${LOG_FILE}
    else
        echo -e "${RED}Log file not found at: ${LOG_FILE}${NC}"
    fi
}

# Function to view live logs
view_live_logs() {
    echo -e "${YELLOW}Displaying live server logs:${NC}"
    echo -e "${GREEN}Options:${NC}"
    echo -e "  1) Press Ctrl+C to stop following (safe method)"
    echo -e "  2) Press Shift+F to resume following"
    echo -e "  3) Press 'q' to exit"
    echo -e "${BLUE}=================================================${NC}"
    
    if [ -f "${LOG_FILE}" ]; then
        # Method 1: Using less (recommended)
        less +F ${LOG_FILE}
        
        # Method 2: Using tail in subshell (alternative)
        # (tail -f ${LOG_FILE} 2>&1) || true
        
        echo -e "${GREEN}Exited live logs view.${NC}"
    else
        echo -e "${RED}Log file not found at: ${LOG_FILE}${NC}"
    fi
}

# Function to test connection
test_connection() {
    echo -e "${YELLOW}Testing server connection...${NC}"
    echo
    
    # Check if server is running first
    if ! pgrep -f "php artisan serve.*${PORT}" > /dev/null; then
        echo -e "${RED}Server is not running!${NC}"
        return
    fi
    
    # Test connection
    response=$(curl -s -o /dev/null -w "%{http_code}" -I http://${HOST}:${PORT} 2>/dev/null)
    
    if [ $? -eq 0 ]; then
        if [ "$response" = "200" ]; then
            echo -e "${GREEN}Connection successful! HTTP Status: ${response}${NC}"
        else
            echo -e "${YELLOW}Connection established but received HTTP Status: ${response}${NC}"
        fi
        
        echo
        echo "Full response headers:"
        curl -I http://${HOST}:${PORT}
    else
        echo -e "${RED}Connection failed! Server might not be responding.${NC}"
    fi
}

# Function to pause and wait for user input
pause() {
    echo
    echo -n "Press Enter to continue..."
    read
}

# Main loop
while true; do
    show_menu
    read choice
    
    case $choice in
        1)
            start_server
            pause
            ;;
        2)
            check_status
            pause
            ;;
        3)
            stop_server
            pause
            ;;
        4)
            restart_server
            pause
            ;;
        5)
            view_logs
            pause
            ;;
        6)
            test_connection
            pause
            ;;
        7)
            view_live_logs
            ;;
        8)
            echo -e "${GREEN}Exiting Laravel Server Manager. Goodbye!${NC}"
            exit 0
            ;;
        *)
            echo -e "${RED}Invalid option. Please select 1-8.${NC}"
            pause
            ;;
    esac
done