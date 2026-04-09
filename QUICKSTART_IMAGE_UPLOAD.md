# 📸 Quick Start Guide - Image Upload Feature

## What You Can Do Now

Your equipment management system now has **mandatory image upload** for all new equipment! ✅

## Step-by-Step Guide

### For Admin Users Adding Equipment

```
📍 STEP 1: Open Admin Dashboard
   Location: http://localhost:5173/admin
   Login with: admin@trustequip.id / 12345678

📍 STEP 2: Go to Equipment Section
   Navigation: Dashboard → ITEMS tab

📍 STEP 3: Click "Add Equipment"
   Button: "➕ Tambah Alat" (green button, top right)

📍 STEP 4: Fill Equipment Details
   Required Fields (*):
   - Nama Alat: Name of the equipment
   - Kategori: Choose category from dropdown
   - Kondisi: Select condition (Baik/Sedang/Rusak)
   - Total Stok: How many items you have
   - Denda/Hari: Daily fine amount (in Rupiah)
   
   Optional:
   - Deskripsi: Description of the equipment

📍 STEP 5: Upload Photo
   - Click "📁 Pilih Foto..." button
   - Select from your computer:
     ✅ Accepted: JPG, PNG, WebP
     ❌ Not Accepted: PDF, DOC, GIF
   - File Size: Max 5MB
   - Preview shows immediately after selection

📍 STEP 6: Save Equipment
   - Review the preview image
   - If wrong image, click "✕ Hapus" to remove
   - Click "Simpan" button to save
   - ✅ Equipment created with photo!
   - Image stored at: /storage/equipment/filename.jpg
```

### For Editing Existing Equipment

```
📍 Edit and Keep Current Image
   1. Click edit icon on equipment row
   2. Update any fields you want to change
   3. Photo stays the same
   4. Click "Simpan" to save

📍 Edit and Replace Image
   1. Click edit icon on equipment row
   2. Current image shows in preview
   3. Click "✕ Hapus" to remove old image
   4. Click "📁 Pilih Foto..." for new image
   5. Select and preview new image
   6. Click "Simpan" to save
   ✅ Old image deleted, new image stored
```

## File Upload Rules

```
✅ ALLOWED FORMATS       ❌ NOT ALLOWED
- JPG/JPEG             - PDF
- PNG                  - Word (.doc, .docx)
- WebP                 - Excel (.xls, .xlsx)
                       - GIF
                       - BMP
                       - TIFF

✅ FILE SIZE: UP TO 5MB (5120 KB)
   Examples:
   - 2MB photo ✅ OK
   - 5MB photo ✅ OK
   - 10MB photo ❌ TOO LARGE
   - Small 200KB phone photo ✅ OK
```

## Error Messages & Solutions

### ❌ "Format foto hanya JPG, PNG, atau WebP"
- **Problem**: You selected a file type not allowed
- **Solution**: Convert image to JPG, PNG, or WebP format

### ❌ "Ukuran foto tidak boleh lebih dari 5MB"
- **Problem**: The image file is too large
- **Solution**: Use image compression tool or select lower resolution photo

### ❌ "Gambar alat wajib diunggah!"
- **Problem**: You forgot to select a photo for new equipment
- **Solution**: Click "📁 Pilih Foto..." and select an image

### ❌ No error but photo won't upload
- **Problem**: Browser cache or temporary network issue
- **Solution**: Clear browser cache and try again

## Image Preview & Organization

### Before Upload (Preview)
```
┌──────────────────────────────┐
│     Equipment Photo Form      │
├──────────────────────────────┤
│  ┌───────────────────────┐   │
│  │   📷                  │   │
│  │  No photo selected    │   │
│  └───────────────────────┘   │
│    [📁 Pilih Foto...]        │
│  Format: JPG, PNG, WebP       │
│  Max: 5MB                     │
└──────────────────────────────┘
```

### After Selection (Preview Shows)
```
┌──────────────────────────────┐
│     Equipment Photo Form      │
├──────────────────────────────┤
│  ┌───────────────────────┐   │
│  │  [Image Preview]    ✕ │   │
│  │  ┌─────────────────┐  │   │
│  │  │  Laptop Photo   │  │   │
│  │  │  (200x200px)    │  │   │
│  │  └─────────────────┘  │   │
│  └───────────────────────┘   │
│    [📁 Pilih Foto...]        │
│  ✕ Hapus                     │
└──────────────────────────────┘
```

After clicking "Simpan":
- Image stored in: `/storage/equipment/equipment_{id}_{timestamp}.jpg`
- Automatically accessible at: `http://localhost:8000/storage/equipment/{filename}`
- Displayed on homepage equipment carousel
- Displayed on equipment details pages

## Real World Example

```
📌 Example: Adding Projector Equipment

Name: Proyektor EPSON EB-2250U
Category: Elektronik
Condition: Baik
Stock: 5 units
Daily Fine: 150000
Description: Professional projector 5000 lumens
Photo: [Select projector_photo.jpg - 2.5MB]

After saving:
✅ Equipment created
✅ Photo stored as: equipment_12_20260410_153045.jpg
✅ Accessible at: /storage/equipment/equipment_12_20260410_153045.jpg
✅ Displays on homepage carousel
✅ Shows on equipment details page
```

## Troubleshooting Checklist

- [ ] Using correct image format? (JPG, PNG, WebP)
- [ ] Image file smaller than 5MB? (Check file properties)
- [ ] Filling all required fields with asterisks? (*)
- [ ] Clicked "Pilih Foto" and selected file? (Check preview shows)
- [ ] Clicking "Simpan" button? (Not any other button)
- [ ] Equipment appears in list after saving? (Refresh page)
- [ ] Image visible in carousel or details? (Check /storage/equipment/ folder)

## Frequently Asked Questions

**Q: Can I use PNG format?**
A: Yes! PNG is fully supported (along with JPG and WebP).

**Q: What if I select wrong image by mistake?**
A: Click the "✕ Hapus" button next to the preview to remove it.

**Q: Can I edit the image later?**
A: Yes! Edit the equipment and upload a new image. Old image is automatically deleted.

**Q: Where are uploaded images stored?**
A: In `/storage/app/public/equipment/` folder. Access via `/storage/equipment/{filename}`

**Q: Is image upload mandatory?**
A: Only for NEW equipment. You can edit existing equipment without changing the image.

**Q: What's the maximum file size?**
A: Maximum 5MB per image.

**Q: Can customers see the images?**
A: Yes! Images display on the equipment carousel (homepage) and equipment details pages.

---

**Status**: ✅ **READY TO USE**

You can start adding equipment with photos immediately!

