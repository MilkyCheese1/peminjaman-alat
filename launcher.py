#!/usr/bin/env python3
"""
Equipment Rental System Launcher
Starts both Laravel backend and Vue.js frontend with a single click
"""

import os
import sys
import subprocess
import time
import webbrowser
from pathlib import Path

# Get project directory
PROJECT_DIR = Path(__file__).parent.resolve()
os.chdir(PROJECT_DIR)

# Node.js path
NODEJS_PATH = r"C:\laragon\bin\nodejs\node-v22"
NODE_EXE = os.path.join(NODEJS_PATH, "node.exe")
NPM_EXE = os.path.join(NODEJS_PATH, "npm.cmd")

def setup_environment():
    """Set up environment variables"""
    os.environ['PATH'] = f"{NODEJS_PATH};{os.environ['PATH']}"
    print(f"✓ Node.js path: {NODEJS_PATH}")

def check_dependencies():
    """Check if required tools are available"""
    print("\n📋 Checking dependencies...")
    
    # Check PHP
    result = subprocess.run(['php', '--version'], capture_output=True, text=True)
    if result.returncode == 0:
        php_version = result.stdout.split('\n')[0]
        print(f"  ✓ {php_version}")
    else:
        print("  ✗ PHP not found!")
        return False
    
    # Check Node.js
    result = subprocess.run([NODE_EXE, '--version'], capture_output=True, text=True)
    if result.returncode == 0:
        node_version = result.stdout.strip()
        print(f"  ✓ Node.js {node_version}")
    else:
        print("  ✗ Node.js not found!")
        return False
    
    # Check npm
    result = subprocess.run([NPM_EXE, '--version'], capture_output=True, text=True)
    if result.returncode == 0:
        npm_version = result.stdout.strip()
        print(f"  ✓ npm {npm_version}")
    else:
        print("  ✗ npm not found!")
        return False
    
    # Check node_modules
    if os.path.exists('node_modules'):
        print("  ✓ npm dependencies installed")
    else:
        print("  ⚠ npm dependencies not found, installing...")
        result = subprocess.run([NPM_EXE, 'install'], capture_output=True, text=True)
        if result.returncode == 0:
            print("  ✓ npm dependencies installed")
        else:
            print("  ✗ Failed to install npm dependencies!")
            return False
    
    return True

def start_backend():
    """Start Laravel backend in a new window"""
    print("\n🚀 Starting Laravel Backend (Port 8000)...")
    try:
        # Start in new window
        subprocess.Popen(
            ['cmd', '/c', 'php artisan serve --host=localhost --port=8000'],
            cwd=str(PROJECT_DIR),
            creationflags=subprocess.CREATE_NEW_CONSOLE
        )
        print("  ✓ Backend started in new window")
        return True
    except Exception as e:
        print(f"  ✗ Failed to start backend: {e}")
        return False

def start_frontend():
    """Start Vue.js frontend in a new window"""
    print("🚀 Starting Vue.js Frontend (Port 5173)...")
    try:
        # Create batch file to run npm
        batch_content = f"""@echo off
set PATH={NODEJS_PATH};%PATH%
npm run dev
pause
"""
        batch_file = PROJECT_DIR / 'temp_run_frontend.bat'
        with open(batch_file, 'w') as f:
            f.write(batch_content)
        
        # Start in new window
        subprocess.Popen(
            ['cmd', '/c', f'"{batch_file}"'],
            cwd=str(PROJECT_DIR),
            creationflags=subprocess.CREATE_NEW_CONSOLE
        )
        print("  ✓ Frontend started in new window")
        return True
    except Exception as e:
        print(f"  ✗ Failed to start frontend: {e}")
        return False

def main():
    """Main launcher"""
    print("\n" + "="*50)
    print("  Equipment Rental System Launcher")
    print("  Vue.js + Laravel Development")
    print("="*50 + "\n")
    
    # Setup
    setup_environment()
    
    # Check dependencies
    if not check_dependencies():
        print("\n❌ Dependency check failed!")
        print("Please ensure PHP and Node.js are properly installed.")
        input("\nPress Enter to exit...")
        sys.exit(1)
    
    # Start services
    print("\n" + "-"*50)
    print("Starting services...")
    print("-"*50)
    
    backend_ok = start_backend()
    time.sleep(2)  # Give backend time to start
    frontend_ok = start_frontend()
    
    if not (backend_ok and frontend_ok):
        print("\n⚠️  Some services failed to start!")
        input("\nPress Enter to exit...")
        sys.exit(1)
    
    # Success message
    print("\n" + "="*50)
    print("  ✅ ALL SERVICES STARTED SUCCESSFULLY")
    print("="*50)
    print("\n📱 Access URLs:\n")
    print("   🌐 Frontend:  http://localhost:5173")
    print("   🔗 Backend:   http://localhost:8000")
    print("   📡 API:       http://localhost:8000/api/health\n")
    print("❗ DO NOT CLOSE THIS WINDOW")
    print("   Minimize it and use the backend/frontend windows instead\n")
    print("   To stop: Close the terminal windows or press Ctrl+C\n")
    print("="*50 + "\n")
    
    # Try to open browser
    time.sleep(3)
    try:
        webbrowser.open('http://localhost:5173')
        print("🌐 Opening browser... (if it doesn't open, visit http://localhost:5173 manually)\n")
    except:
        pass
    
    # Keep this window open
    input("Press Enter to exit (or close backend/frontend windows first)...")

if __name__ == '__main__':
    main()
