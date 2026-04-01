# 🚀 ONE-CLICK LAUNCHER - QUICK START GUIDE

## The Easiest Way to Start Development

### ⭐ RECOMMENDED: One-Click Launcher

**File:** `START.bat` in your project folder  
**Location:** `C:\laragon\www\peminjaman-alat\START.bat`

---

## Option 1: Direct Launch (30 seconds)

### Step 1: Open File Explorer
- Press `Windows Key + E`

### Step 2: Navigate to Project
- Path: `C:\laragon\www\peminjaman-alat\`

### Step 3: Run START.bat
- Find and **double-click** `START.bat`

✅ **Done!** Both servers launch automatically

---

## Option 2: Desktop Shortcut (1 minute)

Create a convenient desktop icon that you can click anytime.

### Method A: Drag & Drop (Fastest)

1. Open File Explorer → Navigate to: `C:\laragon\www\peminjaman-alat\`
2. Find: `START.bat`
3. Hold `Shift` + `Ctrl` and drag it to Desktop
4. Confirm: "Create link"

✅ Now you have a desktop shortcut!

### Method B: Right-Click Menu

1. Open File Explorer → Navigate to: `C:\laragon\www\peminjaman-alat\`
2. Find: `START.bat`
3. Right-click → **Send to** → **Desktop (create shortcut)**

✅ Desktop shortcut created!

### Method C: PowerShell (For Technical Users)

Open PowerShell as Administrator and run:

```powershell
cd C:\laragon\www\peminjaman-alat
powershell -ExecutionPolicy Bypass -File .\create-desktop-shortcut.ps1
```

✅ Automatic shortcut creation!

---

## What Happens When You Click START.bat

```
┌─────────────────────────────────────────┐
│  START.bat Launches...                  │
├─────────────────────────────────────────┤
│                                         │
│  1. Checks PHP installation             │
│  2. Checks Node.js installation         │
│  3. Checks npm installation             │
│  4. Checks npm dependencies             │
│     (Installs if needed on first run)   │
│                                         │
│  5. Opens Terminal 1: Laravel Backend   │
│     ➜ Port 8000                         │
│     ➜ http://localhost:8000             │
│                                         │
│  6. Waits 3 seconds                     │
│                                         │
│  7. Opens Terminal 2: Vue.js Frontend   │
│     ➜ Port 5173                         │
│     ➜ http://localhost:5173             │
│                                         │
│  8. Automatically opens browser         │
│     ➜ Shows Vue.js App                  │
│                                         │
└─────────────────────────────────────────┘
```

---

## After Launch

### 📱 You'll See

1. **Launcher window** - Shows status messages
   - Can be minimized
   - Leave it open (services run in background)

2. **Backend terminal** - Black window
   - Laravel on Port 8000
   - Shows "Server started, local development server is running"
   - Keep it open!

3. **Frontend terminal** - Black window
   - Vue.js/Vite on Port 5173
   - Shows "Local: http://localhost:5173"
   - Keep it open!

4. **Browser window** - Opens automatically
   - Shows your Vue.js application
   - Port 5173

### 🏠 Access URLs

| What | URL | Opens In |
|------|-----|----------|
| **Your App** | http://localhost:5173 | Browser |
| **Backend API** | http://localhost:8000 | Browser |
| **API Test** | http://localhost:8000/api/health | Browser |

---

## ⏹️ Stopping Everything

### Method 1: Close Terminal Windows
1. Close the **Backend** terminal (black window)
2. Close the **Frontend** terminal (black window)

### Method 2: Press Ctrl+C
- In each terminal, press: `Ctrl + C`

### Method 3: Close Launcher
- Close the launcher window
- Or just minimize it (services keep running)

---

## 🛠️ Available Launcher Files

### START.bat ⭐ (Recommended)
- **Type:** Batch File (.bat)
- **Easy to use:** Yes
- **Double-click:** Just works
- **Setup needed:** None
- **Performance:** Fastest
- **Error messages:** Clear

### launcher.vbs
- **Type:** VBScript (.vbs)
- **Easy to use:** Yes
- **Double-click:** Just works
- **Setup needed:** None
- **Performance:** Good
- **Error messages:** Basic

### launcher.py
- **Type:** Python Script (.py)
- **Easy to use:** Yes (if Python installed)
- **Command:** `python launcher.py`
- **Setup needed:** Python
- **Performance:** Good
- **Error messages:** Detailed

### quickstart.bat
- **Type:** Batch File (.bat)
- **Similar to:** START.bat
- **Use:** Alternative launcher

---

## ✨ Customizing Your Shortcut

### Rename the Shortcut
- Right-click on desktop shortcut
- Select **Rename**
- Type something like:
  - `🚀 Development Server`
  - `Equipment Rental System`
  - `Start App`

### Change the Icon
- Right-click shortcut
- Select **Properties**
- Click **Change Icon**
- Choose any icon you like
- Click **Apply** and **OK**

### Pin to Taskbar
- Right-click shortcut
- Select **Pin to Taskbar**
- Now click from taskbar anytime!

---

## ❓ Troubleshooting

### Problem: "php not found"
```
Solution: Make sure PHP is in Windows PATH
Check: Run `php --version` in PowerShell
```

### Problem: "npm not found"
```
Solution: Make sure Node.js is in PATH
Check: Run `npm --version` in PowerShell
```

### Problem: Port 5173 already in use
```
Solution: Vite will auto-use 5174, 5175, etc.
Or: Close other Vite servers first
```

### Problem: Port 8000 already in use
```
Solution: Close other PHP artisan servers
Or: Change port in START.bat line 103
Change: --port=8000 to --port=8001
```

### Problem: Services don't start
```
Solution 1: Run START.bat as Administrator
Solution 2: Check console for error messages
Solution 3: Ensure node_modules exists
  Run: npm install (in project folder)
```

---

## 📊 File Locations

| What | Location |
|------|----------|
| Launcher | `C:\laragon\www\peminjaman-alat\START.bat` |
| Project | `C:\laragon\www\peminjaman-alat` |
| Frontend | `C:\laragon\www\peminjaman-alat\resources\js` |
| Backend | `C:\laragon\www\peminjaman-alat\routes\api.php` |
| Config | `C:\laragon\www\peminjaman-alat\package.json` |

---

## 🎯 Next Steps

1. ✅ Create desktop shortcut (if desired)
2. 🚀 Double-click to launch everything
3. 💻 Open http://localhost:5173 in browser
4. 📝 Start building your app!

---

## Quick Commands

### From PowerShell (if you prefer)

```powershell
# Navigate to project
cd C:\laragon\www\peminjaman-alat

# Run launcher
.\START.bat

# Or run components separately:
php artisan serve --host=localhost --port=8000
npm run dev
```

---

## 🎉 You're All Set!

**That's it!** You now have a one-click launcher for your entire development environment.

**No more running multiple commands or remembering port numbers—just double-click and code!**

---

## 📚 Additional Files

- `SETUP_GUIDE.md` - Detailed setup documentation
- `README_SETUP.md` - Quick reference
- `SHORTCUT_GUIDE.md` - More shortcut options
- `create-desktop-shortcut.ps1` - PowerShell helper

---

**Happy Development! 🚀**

*Equipment Rental System - Vue.js + Laravel*
