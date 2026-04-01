#!/bin/bash

# Equipment Rental System - Start Development Environment (Linux/Mac)

echo "===================================="
echo "Equipment Rental System - DEV Setup"
echo "===================================="
echo ""

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "Installing npm dependencies..."
    npm install
    echo ""
fi

# Start Laravel backend
echo "Starting Laravel Backend (Port 8000)..."
php artisan serve --host=localhost --port=8000 &
LARAVEL_PID=$!

sleep 3

# Start Vue.js frontend
echo "Starting Vue.js Frontend (Port 5173)..."
npm run dev &
VITE_PID=$!

echo ""
echo "===================================="
echo "Development servers started"
echo "Frontend: http://localhost:5173"
echo "Backend:  http://localhost:8000"
echo "===================================="
echo ""

# Handle termination
trap "kill $LARAVEL_PID $VITE_PID 2>/dev/null; exit" SIGINT SIGTERM

# Wait for both processes
wait $LARAVEL_PID $VITE_PID
