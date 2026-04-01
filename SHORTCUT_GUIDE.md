# How to Create Desktop Shortcut for One-Click Launch

## Quick Start Options

### Option 1: Run START.bat Directly ⭐ (RECOMMENDED)
1. Navigate to: `C:\laragon\www\peminjaman-alat\`
2. Double-click: **START.bat**
3. Both servers will launch automatically!

---

### Option 2: Create Desktop Shortcut (Windows)

#### Method A: Manual Shortcut Creation

1. **Right-click on Desktop** → Select "New" → "Shortcut"
2. **Paste this path:**
   ```
   C:\laragon\www\peminjaman-alat\START.bat
   ```
3. **Click Next** → Enter name: `Equipment Rental System`
4. **Click Finish**

Now you have a desktop icon - just double-click it to start everything!

#### Method B: Automatic Shortcut Creator

Run this PowerShell command (as Administrator):

```powershell
$DesktopPath = [Environment]::GetFolderPath("Desktop")
$ProjectPath = "C:\laragon\www\peminjaman-alat\START.bat"
$ShortcutPath = "$DesktopPath\Equipment Rental System.lnk"

$Shell = New-Object -ComObject WScript.Shell
$Shortcut = $Shell.CreateShortcut($ShortcutPath)
$Shortcut.TargetPath = $ProjectPath
$Shortcut.WorkingDirectory = "C:\laragon\www\peminjaman-alat\"
$Shortcut.IconLocation = "C:\Windows\System32\cmd.exe,0"
$Shortcut.Save()

Write-Host "✓ Desktop shortcut created successfully!"
Write-Host "  Location: $ShortcutPath"
```

---

## What Each Launcher Does

### START.bat (Batch File)
- ✅ Simple, no installation needed
- ✅ Checks all dependencies
- ✅ Installs npm modules if needed (first run)
- ✅ Starts Laravel backend
- ✅ Starts Vue.js frontend
- ✅ Automatically opens browser
- ⚠️ Shows console window

**How to use:**
```batch
cd C:\laragon\www\peminjaman-alat
START.bat
```

### launcher.vbs (VBScript)
- ✅ Launches START.bat
- ✅ Can be made into shortcut
- ✅ Professional looking
- ⚠️ Requires VBScript support (usually enabled)

**How to use:**
```batch
windows key → Right-click on Desktop → New → Shortcut
Target: C:\laragon\www\peminjaman-alat\launcher.vbs
```

### launcher.py (Python)
- ✅ More customizable
- ✅ Better error handling
- ⚠️ Requires Python installed
- ⚠️ Slower startup

---

## After Launching

Once you run any launcher:

1. **Two new command windows will open:**
   - One for Laravel Backend (Port 8000)
   - One for Vue.js Frontend (Port 5173)

2. **Main launcher window closes or stays open**

3. **Browser automatically opens** to http://localhost:5173

4. **Access your app:**
   - Frontend: http://localhost:5173
   - Backend: http://localhost:8000
   - API: http://localhost:8000/api/health

---

## Stopping the Services

To stop all services:

1. Close the backend terminal window
2. Close the frontend terminal window
3. Or press **Ctrl+C** in each window

---

## Available Files

| File | Type | How to Use |
|------|------|-----------|
| **START.bat** | Batch | Double-click to start |
| **launcher.vbs** | VBScript | Double-click to start |
| **launcher.py** | Python | `python launcher.py` |
| **quickstart.bat** | Batch | Same as START.bat |
| **run-dev.bat** | Batch | Alternative launcher |

---

## Desktop Shortcut Guide (Detailed)

### Create Shortcut in 30 Seconds

1. Open File Explorer
2. Navigate to: `C:\laragon\www\peminjaman-alat`
3. Right-click `START.bat`
4. Select: **Send to** → **Desktop (create shortcut)**

Done! You now have a desktop icon. Double-click anytime to start development!

### Custom Icon for Shortcut (Optional)

1. Right-click the shortcut on Desktop
2. Select **Properties**
3. Click **Change Icon**
4. Browse and select any `.ico` file
5. Or use: `C:\Windows\System32\shell32.dll` (has many icons)

---

## Troubleshooting

### "Command not recognized" Error
```
Solution: Ensure Node.js path is correct:
C:\laragon\bin\nodejs\node-v22
```

### Port Already in Use
```
Solution: Laragon might still be running another service
Kill process: netstat -ano | findstr :5173
taskkill /PID [PID] /F
```

### npm not found
```
Solution: Close and reopen command prompt
Or run: $env:PATH = "C:\laragon\bin\nodejs\node-v22;$env:PATH"
```

---

## One-Click Solution Recommendations

**Best Option:** Create desktop shortcut of `START.bat`
- No installation needed
- Single click to start everything
- Shows status/errors clearly
- Easy to understand

**Professional Option:** Use PowerShell script to create shortcut
- Repeatable
- Can be distributed to team
- Automated setup

---

## Tips

- 💡 Rename shortcut to something friendly like:
  - "🚀 Start Development"
  - "Development Server"
  - "Run App"

- 💡 Pin the launcher window to taskbar for quick access

- 💡 Create multiple shortcuts if you work on different projects

- 💡 Never close this launcher until you're done developing

---

**Ready? Just double-click START.bat and start coding!** 🚀
