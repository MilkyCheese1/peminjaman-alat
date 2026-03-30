# Dark Mode Feature

## Overview
A comprehensive dark mode feature has been added to the application. Users can toggle between light and dark themes, with their preference automatically saved.

## Files Added

### CSS
- **public/css/dark-mode.css** - Main dark mode stylesheet with CSS custom properties (variables) for easy theme switching

### JavaScript
- **public/js/dark-mode.js** - Dark mode toggle functionality and localStorage persistence

## Features

### ✨ Functionality
- **Toggle Button**: A button (🌙/☀️ emoji) in the navbar header to switch themes
- **Persistent Storage**: User preference is saved in localStorage and restored on page refresh
- **System Preference Detection**: If no user preference is saved, the system checks for OS-level dark mode preference
- **Smooth Transitions**: All color changes animate smoothly (0.3 seconds)
- **Auto-Update on System Change**: If user hasn't set a preference, the app respects system theme changes

### 🎨 Theme Coverage
Dark mode styling covers:
- **Dashboards** (user, staff, owner, admin)
- **Authentication Pages** (login, register)
- **Landing Page**
- **All Components**:
  - Navbars and sidebars
  - Cards and stat boxes
  - Forms and inputs
  - Buttons (primary, secondary, success, danger, info)
  - Tables
  - Modals
  - Alerts and badges
  - Status indicators
  - Scrollbars (custom styling)

## How It Works

### CSS Variables
The theme system uses CSS custom properties that change in dark mode:

```css
:root {
    /* Light mode (default) */
    --bg-primary: #f5f7fa;
    --bg-secondary: #ffffff;
    --text-primary: #333;
    --accent-color: #667eea;
    /* ... more variables */
}

html.dark-mode {
    /* Dark mode overrides */
    --bg-primary: #0f1419;
    --bg-secondary: #1a1f29;
    --text-primary: #e0e0e0;
    --accent-color: #7b8fd9;
    /* ... more variables */
}
```

### JavaScript Implementation
The dark mode toggle is automatically initialized and creates a button in the navbar:

```javascript
// Toggle dark mode
window.darkMode.toggle();

// Or directly
window.darkMode.enable();  // Force dark mode
window.darkMode.disable(); // Force light mode

// Check current state
window.darkMode.isEnabled(); // Returns boolean
```

## Integration in Templates

All dashboard and auth templates now include:

```html
<!-- In <head> -->
<link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

<!-- Before closing </body> -->
<script src="{{ asset('js/dark-mode.js') }}"></script>
```

## Color Palette

### Light Mode
- Background: #f5f7fa
- Cards: #ffffff
- Text: #333
- Accent: #667eea

### Dark Mode
- Background: #0f1419
- Cards: #1a1f29
- Text: #e0e0e0
- Accent: #7b8fd9

## Browser Support
- All modern browsers supporting CSS custom properties (CSS Variables)
- localStorage API for preference persistence
- matchMedia API for system preference detection

## User Guide

### Switching Theme
1. Look for the 🌙 (moon) or ☀️ (sun) button in the top navbar
2. Click it to toggle between light and dark modes
3. Your preference is automatically saved

### System Preference
- If you haven't manually set a preference, the app will use your system theme
- On Windows 10+: Check Settings > Personalization > Colors
- On macOS: System Preferences > General > Appearance
- On Linux: Depends on your desktop environment

## Customization

To adjust colors, edit the CSS variables in:

**Light Mode**: `:root` selector in `public/css/dark-mode.css`
**Dark Mode**: `html.dark-mode` selector in `public/css/dark-mode.css`

Example:
```css
html.dark-mode {
    --bg-primary: #0f1419;  /* Change this value */
    --text-primary: #e0e0e0;
}
```

## Event Listeners

You can listen for dark mode changes in your JavaScript:

```javascript
// When dark mode is enabled
window.addEventListener('darkModeEnabled', function() {
    console.log('Dark mode enabled');
    // Update any third-party components if needed
});

// When dark mode is disabled
window.addEventListener('darkModeDisabled', function() {
    console.log('Dark mode disabled');
});
```

## Performance Notes
- CSS variables provide near-zero performance overhead
- Theme switching is immediate (no page reload needed)
- Transitions are GPU-accelerated
- localStorage operations are minimal (single value stored)

## Future Enhancements
- Custom theme colors (user-selected palette)
- Auto-switch based on time of day
- Per-page theme preferences
- Theme scheduling
- High contrast mode variant
