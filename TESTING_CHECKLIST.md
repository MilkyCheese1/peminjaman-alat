# 📱 Responsive Design Testing Checklist

## Cara Testing di VS Code / Browser

### 1. **Chrome DevTools (Recommended)**
- Buka aplikasi di browser (http://localhost:8000 atau sesuai port Anda)
- Tekan `F12` atau `Ctrl+Shift+I` (Windows) / `Cmd+Option+I` (Mac)
- Klik icon device (top-left) atau tekan `Ctrl+Shift+M` untuk toggle device mode
- Pilih device preset atau set custom width

### 2. **Breakpoints yang Ditest**
```
MOBILE (< 576px)
└─ iPhone SE (375px), iPhone 12 (390px), Galaxy S21 (360px)

TABLET (576px - 768px)
└─ iPad Mini (768px), Samsung Tab S6 (728px)

DESKTOP (> 768px)
└─ 1024px, 1280px, 1440px+
```

---

## ✅ TESTING CHECKLIST

### **MOBILE LAYOUT (≤ 576px)**

#### 📍 Navbar/Header
- [ ] Navbar tetap di atas saat scroll (fixed position)
- [ ] Navbar smooth slide down saat scroll ke atas
- [ ] Navbar smooth slide up saat scroll ke bawah
- [ ] Logo visible dan proporsional
- [ ] "PELANGGAN / PEMINJAM" label terlihat
- [ ] Tabs (Beranda, Jelajahi Alat, etc) bisa discroll horizontal
- [ ] No horizontal overflow pada navbar
- [ ] Navbar height ~140px saat expanded

#### 🗂️ Browse Section ("Jelajahi Alat")
- [ ] Heading "Jelajahi Alat" ukuran proporsional (~1.1rem)
- [ ] Gap 10px antara navbar dan heading ("Jelajahi Alat")
- [ ] Grid 3 kolom dengan gap 12px
- [ ] Card tidak overflow (sesuai width)
- [ ] Card image height 100px
- [ ] Card-meta (harga + stok) terlihat jelas
- [ ] Card title tidak overflow ke 2-3 baris

#### 🔘 Buttons in Card
- [ ] Button "Pinjam" FULL WIDTH
- [ ] Button "Detail" FULL WIDTH (di bawah "Pinjam")
- [ ] Buttons stacked vertical (tidak horizontal)
- [ ] Buttons padding 8px 10px
- [ ] Buttons tidak keluar dari card
- [ ] Buttons di dalam card-content dengan margin-top
- [ ] Buttons clickable dan responsive

#### 🔍 Filter Section
- [ ] Search input full width
- [ ] Category dropdown full width
- [ ] Sort dropdown full width
- [ ] Filter gap 10px
- [ ] Stats row terlihat (Total, Tersedia)
- [ ] Stats font size 0.8rem

#### 📦 Product Cards Layout
- [ ] 3 columns grid
- [ ] Card width equal (responsive)
- [ ] No horizontal scrollbar
- [ ] Cards have proper spacing
- [ ] Cards responsive dan tidak overlap

#### 🎯 Overall Mobile
- [ ] No horizontal overflow
- [ ] Content tidak tertutup navbar
- [ ] Bottom padding ada untuk scroll
- [ ] All text readable
- [ ] No layout shift saat scroll

---

### **TABLET LAYOUT (576px - 768px)**

#### 📍 Navbar/Header
- [ ] Navbar behavior sama seperti mobile (scroll following)
- [ ] Navbar lebih compact atau expanded? (check consistency)
- [ ] Tabs scrollable jika tidak cukup space
- [ ] Header-actions (search, user menu) tersedia? Check desktop rules

#### 🗂️ Browse Section
- [ ] Grid masih 3 kolom atau berubah ke 4?
- [ ] Heading ukuran normal (tidak mini) atau tablet-specific?
- [ ] Content padding proporsional
- [ ] Cards readable dengan size tab

#### 🔘 Buttons
- [ ] Buttons full width atau berdampingan?
- [ ] Check if desktop button style applies here
- [ ] Buttons padding appropriate untuk tablet

#### 📱 General Tablet
- [ ] Layout consistent antara mobile dan desktop
- [ ] No weird layout shifts
- [ ] All content accessible
- [ ] Smooth transitions saat resize browser

---

### **DESKTOP LAYOUT (> 768px)**

#### 📍 Navbar/Header
- [ ] Header sticky di atas (position: sticky)
- [ ] Logo section visible dengan full size
- [ ] Header-actions visible (search, notifications, user menu)
- [ ] Tabs horizontal di bawah
- [ ] NO scroll-following behavior (tetap sticky)
- [ ] Header tidak bergerak saat scroll
- [ ] Navbar height normal/besar

#### 🗂️ Browse Section
- [ ] Grid 4 kolom (auto-fill minmax 280px)
- [ ] Card image height 200px
- [ ] Card content padding 15px
- [ ] Card-meta ditampilkan full
- [ ] Category badge visible
- [ ] Details box visible (denda, stok, kondisi)
- [ ] Card description visible (text clamp 2 lines)

#### 🔘 Buttons
- [ ] Buttons horizontal (berdampingan)
- [ ] Button "Pinjam" dan "Detail" side-by-side
- [ ] Button gap 8px antara keduanya
- [ ] Buttons padding 10px 12px (normal)
- [ ] Buttons tidak full width

#### 🗂️ Filters
- [ ] Filter grid (auto-fit minmax 200px)
- [ ] Semua filter visible dalam 1 baris atau wrap proporsional
- [ ] Search input accessible
- [ ] All dropdowns functional

#### 🎯 Modal/Detail View
- [ ] Modal max-width 500px
- [ ] Modal image height 200px
- [ ] Modal info layout grid 2 column
- [ ] Modal buttons visible dan clickable
- [ ] Modal backdrop working

#### 📦 General Desktop
- [ ] Layout stable, tidak jitter
- [ ] All features functional
- [ ] Buttons hover effects working
- [ ] Modal scrollable jika content banyak

---

## 🧪 HOW TO TEST EACH VIEW

### **Via Chrome DevTools:**
```
1. Open app in browser
2. Press F12 (DevTools)
3. Click device icon (Ctrl+Shift+M)
4. Test each breakpoint:
   - Responsive (drag to see breakpoints)
   - iPhone SE (375px)
   - iPad (768px)
   - Desktop (1024px, 1280px)
5. Test scroll behavior (mouse wheel / drag)
6. Test interactions (click buttons, etc)
7. Check console for errors (Ctrl+Shift+J)
```

### **Via Firefox DevTools:**
```
1. Open app in Firefox
2. Press F12
3. Click device toolbar icon (Ctrl+Shift+M)
4. Select device or drag
5. Test normally
```

---

## 🐛 THINGS TO CHECK

- [ ] **Navbar scroll behavior** - smooth atau jitter?
- [ ] **No overflow** - ada horizontal scrollbar?
- [ ] **Text readable** - font size OK?
- [ ] **Button clickable** - semua bisa diklik?
- [ ] **Modal functional** - bisa open/close?
- [ ] **Cards aligned** - grid proporsional?
- [ ] **No layout shift** - ada element yang jump?
- [ ] **Responsive images** - gambar loaded?
- [ ] **Filter working** - search, category, sort fungsi?
- [ ] **Performance** - smooth scroll atau lag?

---

## 📝 NOTES

### Media Queries Reference:
```css
/* Mobile */
@media (max-width: 576px) { }

/* Tablet */
@media (max-width: 768px) { }

/* Desktop */
@media (min-width: 577px) { }
```

### Current Implementation:
- ✅ Mobile (≤ 576px): Scroll-following navbar, 3-col grid, vertical buttons
- ✅ Tablet (576-768px): Desktop styles (needs review)
- ✅ Desktop (> 768px): Sticky navbar, 4-col grid, horizontal buttons

---

## 🚀 NEXT STEPS

1. Test di semua breakpoint menggunakan DevTools
2. Buat list issue jika ada
3. Report issue dengan:
   - Device/breakpoint (375px, 768px, 1024px)
   - What's wrong?
   - Current behavior vs expected behavior
   - Screenshot jika perlu
