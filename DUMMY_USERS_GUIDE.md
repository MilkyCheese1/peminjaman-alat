# 🎓 TrustEquip - Dummy Users & Testing Guide

## 📋 Quick Reference - Demo Credentials

### 👨‍💼 OWNER (Pemilik Alat/Sistem)
| Nama | Email | Password | Telepon |
|------|-------|----------|---------|
| Budi Santoso | owner@trustequip.id | owner123456 | 081234567890 |
| Siti Nurhaliza | siti.owner@trustequip.id | siti123456 | 082345678901 |

**Akses:** Dapat mengelola alat pribadi, melihat peminjaman, dan statistik kepemilikan.

---

### 👨‍💻 ADMIN (Administrator Sistem)
| Nama | Email | Password | Telepon |
|------|-------|----------|---------|
| Ahmad Khoirulloh | admin@trustequip.id | admin123456 | 083456789012 |
| Rina Wijaya | rina.admin@trustequip.id | rina123456 | 084567890123 |

**Akses:** Dapat mengelola pengguna, sistem, laporan, dan pengaturan keseluruhan aplikasi.

---

### 👨‍🔧 STAFF (Pekerja/Karyawan)
| Nama | Email | Password | Department |
|------|-------|----------|------------|
| Hendra Wijaksono | staff@trustequip.id | staff123456 | Maintenance |
| Diana Kusuma | diana.staff@trustequip.id | diana123456 | Support |
| Rizki Pratama | rizki.staff@trustequip.id | rizki123456 | Logistics |

**Akses:** Dapat mengelola peminjaman, memproses pesanan, melihat laporan, dan memberikan dukungan.

---

### 👨‍🎓 CUSTOMER (Pengguna/Peminjam)
| Nama | Email | Password | Sekolah |
|------|-------|----------|---------|
| Ahmad Rizki | ahmad@school.id | customer123456 | SMA Negeri 1 |
| Rena Putri | rena@school.id | customer123456 | SMA Negeri 2 |
| Budi Santoso | budi@school.id | customer123456 | SMA Negeri 1 |
| Sinta Dewi | sinta@school.id | customer123456 | SMA Negeri 3 |
| Yusuf Rahman | yusuf@school.id | customer123456 | SMA Negeri 2 |

**Akses:** Dapat melihat katalog alat, melakukan peminjaman, dan melihat riwayat peminjaman pribadi.

---

## 🔐 Panduan Login

### Metode 1: Menggunakan Form Login
1. Buka halaman Login (`http://localhost:5173/login`)
2. Masukkan email dari tabel di atas
3. Masukkan password sesuai dengan role
4. Klik tombol "Masuk"
5. Anda akan diarahkan ke dashboard

### Metode 2: Quick Login dari Demo Page
1. Buka halaman Demo Users (`http://localhost:5173/demo-users`)
2. Cari akun yang ingin ditest
3. Klik tombol "🚀 Quick Login" untuk langsung login
4. Atau klik "📋 Salin Credentials" untuk menyalin email & password

### Metode 3: Quick Copy & Paste
1. Klik tombol "📋 Salin Credentials" di halaman `/demo-users`
2. Paste credentials ke form login
3. Tekan Enter untuk login

---

## 📊 Role & Permissions

### Owner (Pemilik)
- ✅ View all borrowings
- ✅ Manage own items
- ✅ View personal statistics
- ✅ View borrower ratings
- ❌ Manage system users
- ❌ Access admin panel

### Admin (Administrator)
- ✅ Manage all users
- ✅ Manage system settings
- ✅ View all reports
- ✅ System monitoring
- ✅ Suspend/block users
- ✅ Access admin dashboard

### Staff (Pekerja)
- ✅ Manage borrowing process
- ✅ Process orders
- ✅ View assigned reports
- ✅ Support customers
- ✅ Update item status
- ❌ Manage users
- ❌ System settings

### Customer (Peminjam)
- ✅ View available items
- ✅ Borrow items
- ✅ View personal borrowings
- ✅ Extend borrowing
- ✅ View borrowing history
- ❌ Manage items
- ❌ Access admin features

---

## 🧪 Testing Scenarios

### Scenario 1: Complete Borrowing Workflow
```
1. Login as CUSTOMER (ahmad@school.id / customer123456)
2. Navigate to Beranda to see available items
3. View Peminjaman history
4. Check profile in Profil tab
5. Logout
```

### Scenario 2: Admin Management
```
1. Login as ADMIN (admin@trustequip.id / admin123456)
2. Access admin dashboard
3. View user statistics
4. Check system health
5. Access system settings
```

### Scenario 3: Owner Operations
```
1. Login as OWNER (owner@trustequip.id / owner123456)
2. Check owned items
3. View borrowing statistics
4. Monitor item conditions
5. Check borrower ratings
```

### Scenario 4: Staff Support
```
1. Login as STAFF (staff@trustequip.id / staff123456)
2. View pending orders
3. Process borrowing requests
4. Update item status
5. Provide customer support
```

---

## 📁 File Structure

```
resources/js/
├── data/
│   └── dummyUsers.js          # Dummy users data & validation
├── pages/
│   ├── DemoUsers.vue          # Demo users reference page
│   ├── Login.vue              # Updated with dummy user validation
│   ├── DashboardEnhanced.vue  # Enhanced dashboard with role support
│   ├── LandingPage.vue        # Landing page
│   └── Register.vue           # Register page
└── router.js                  # Routes including /demo-users
```

---

## 🔄 Data Persistence

### Current Implementation (Demo)
- User data stored in **localStorage** with key: `user`
- Data includes: id, fullname, email, role, phone, school, avatar, status, joinDate
- Session persists until browser close or manual logout

### Stored JSON Example
```json
{
  "id": "customer-1",
  "fullname": "Ahmad Rizki",
  "email": "ahmad@school.id",
  "role": "customer",
  "phone": "088901234567",
  "school": "SMA Negeri 1",
  "avatar": "👨‍🎓",
  "status": "active",
  "joinDate": "01 Februari 2026"
}
```

---

## 🎯 Next Steps

### To Transition to Real Database:
1. Replace `dummyUsers.js` with actual API calls
2. Update `validateUser()` to make POST request to backend
3. Connect to Laravel backend authentication
4. Implement JWT tokens or sessions
5. Update localStorage to use secure session storage

### To Add More Demo Users:
1. Edit `resources/js/data/dummyUsers.js`
2. Add new user object to `dummyUsers` array
3. No need to rebuild - Vite hot reload will update
4. Refresh browser to see new users in `/demo-users` page

---

## 💡 Tips & Tricks

### Auto-Login URL (Development)
```
http://localhost:5173/login?email=ahmad@school.id
```
*(Currently not implemented, but can be added to auto-fill email field)*

### Test Different Roles
1. Logout from current account
2. Switch to different demo user
3. Compare dashboard differences per role
4. Test role-specific features

### Notification Testing
Open `/demo-users` page and check the notification system on the dashboard

---

## 🐛 Troubleshooting

### Issue: Login says "Email tidak ditemukan"
- **Solution:** Make sure you're using the exact email from the table above
- Check spelling carefully (lowercase, @domain.id)

### Issue: "Password salah" message
- **Solution:** Verify password matches the role's password
- Note: Customer accounts use `customer123456`
- Other roles have different passwords

### Issue: Dashboard not loading after login
- **Solution:** Check if role-based layout is loaded
- Verify localStorage has user data
- Check browser console for errors

### Issue: Demo page not showing all users
- **Solution:** Refresh the page (Ctrl+F5)
- Clear browser cache
- Check if dummyUsers.js has all entries

---

## 📞 Support

For issues or questions about dummy users:
1. Check this guide first
2. Review the DemoUsers.vue page (`/demo-users`)
3. Check browser DevTools Console for errors
4. Review dummyUsers.js for user data structure

---

## ✅ Checklist for Testing

- [ ] Owner can login and access dashboard
- [ ] Admin can access system management
- [ ] Staff can process borrowings
- [ ] Customer can browse and borrow items
- [ ] Role-specific data displays correctly
- [ ] Logout clears localStorage
- [ ] Quick login works
- [ ] Credentials copy functionality works
- [ ] Navigation between pages works
- [ ] Error messages display correctly

---

Generated: April 1, 2026
Version: 1.0
