// Dummy users dengan berbagai role
export const dummyUsers = [
  // OWNERS
  {
    id: 'owner-1',
    fullname: 'Budi Santoso',
    email: 'owner@trustequip.id',
    password: 'owner123456',
    role: 'owner',
    phone: '081234567890',
    school: 'SMA Negeri 1',
    address: 'Jl. Pendidikan No. 1',
    avatar: '👨‍💼',
    status: 'active',
    joinDate: '01 Januari 2026',
    totalBorrowings: 145,
    activeBorrowings: 12,
    rating: 4.9
  },
  {
    id: 'owner-2',
    fullname: 'Siti Nurhaliza',
    email: 'siti.owner@trustequip.id',
    password: 'siti123456',
    role: 'owner',
    phone: '082345678901',
    school: 'SMA Negeri 2',
    address: 'Jl. Pendidikan No. 2',
    avatar: '👩‍💼',
    status: 'active',
    joinDate: '05 Januari 2026',
    totalBorrowings: 98,
    activeBorrowings: 8,
    rating: 4.8
  },

  // ADMINS
  {
    id: 'admin-1',
    fullname: 'Ahmad Khoirulloh',
    email: 'admin@trustequip.id',
    password: 'admin123456',
    role: 'admin',
    phone: '083456789012',
    school: 'Kantor Pusat',
    address: 'Jl. Admin No. 1',
    avatar: '👨‍💻',
    status: 'active',
    joinDate: '10 Januari 2026',
    totalUsers: 1523,
    activeTransactions: 456,
    systemHealth: 99.8
  },
  {
    id: 'admin-2',
    fullname: 'Rina Wijaya',
    email: 'rina.admin@trustequip.id',
    password: 'rina123456',
    role: 'admin',
    phone: '084567890123',
    school: 'Kantor Pusat',
    address: 'Jl. Admin No. 2',
    avatar: '👩‍💻',
    status: 'active',
    joinDate: '12 Januari 2026',
    totalUsers: 1523,
    activeTransactions: 456,
    systemHealth: 99.8
  },

  // STAFFS
  {
    id: 'staff-1',
    fullname: 'Hendra Wijaksono',
    email: 'staff@trustequip.id',
    password: 'staff123456',
    role: 'staff',
    phone: '085678901234',
    school: 'SMA Negeri 1',
    address: 'Jl. Kerja No. 1',
    avatar: '👨‍🔧',
    status: 'active',
    joinDate: '15 Januari 2026',
    department: 'Maintenance',
    ordersHandled: 234,
    rating: 4.7
  },
  {
    id: 'staff-2',
    fullname: 'Diana Kusuma',
    email: 'diana.staff@trustequip.id',
    password: 'diana123456',
    role: 'staff',
    phone: '086789012345',
    school: 'SMA Negeri 2',
    address: 'Jl. Kerja No. 2',
    avatar: '👩‍🔧',
    status: 'active',
    joinDate: '18 Januari 2026',
    department: 'Support',
    ordersHandled: 198,
    rating: 4.6
  },
  {
    id: 'staff-3',
    fullname: 'Rizki Pratama',
    email: 'rizki.staff@trustequip.id',
    password: 'rizki123456',
    role: 'staff',
    phone: '087890123456',
    school: 'SMA Negeri 3',
    address: 'Jl. Kerja No. 3',
    avatar: '👨‍🔧',
    status: 'active',
    joinDate: '20 Januari 2026',
    department: 'Logistics',
    ordersHandled: 267,
    rating: 4.8
  },

  // CUSTOMERS
  {
    id: 'customer-1',
    fullname: 'Ahmad Rizki',
    email: 'ahmad@school.id',
    password: 'customer123456',
    role: 'customer',
    phone: '088901234567',
    school: 'SMA Negeri 1',
    address: 'Jl. Pelajar No. 1',
    avatar: '👨‍🎓',
    status: 'active',
    joinDate: '01 Februari 2026',
    totalBorrowings: 24,
    activeBorrowings: 3,
    rating: 4.8
  },
  {
    id: 'customer-2',
    fullname: 'Rena Putri',
    email: 'rena@school.id',
    password: 'customer123456',
    role: 'customer',
    phone: '089012345678',
    school: 'SMA Negeri 2',
    address: 'Jl. Pelajar No. 2',
    avatar: '👩‍🎓',
    status: 'active',
    joinDate: '05 Februari 2026',
    totalBorrowings: 18,
    activeBorrowings: 2,
    rating: 4.6
  },
  {
    id: 'customer-3',
    fullname: 'Budi Santoso',
    email: 'budi@school.id',
    password: 'customer123456',
    role: 'customer',
    phone: '081012345679',
    school: 'SMA Negeri 1',
    address: 'Jl. Pelajar No. 3',
    avatar: '👨‍🎓',
    status: 'active',
    joinDate: '08 Februari 2026',
    totalBorrowings: 31,
    activeBorrowings: 5,
    rating: 4.9
  },
  {
    id: 'customer-4',
    fullname: 'Sinta Dewi',
    email: 'sinta@school.id',
    password: 'customer123456',
    role: 'customer',
    phone: '082123456780',
    school: 'SMA Negeri 3',
    address: 'Jl. Pelajar No. 4',
    avatar: '👩‍🎓',
    status: 'active',
    joinDate: '12 Februari 2026',
    totalBorrowings: 15,
    activeBorrowings: 1,
    rating: 4.5
  },
  {
    id: 'customer-5',
    fullname: 'Yusuf Rahman',
    email: 'yusuf@school.id',
    password: 'customer123456',
    role: 'customer',
    phone: '083234567891',
    school: 'SMA Negeri 2',
    address: 'Jl. Pelajar No. 5',
    avatar: '👨‍🎓',
    status: 'active',
    joinDate: '15 Februari 2026',
    totalBorrowings: 22,
    activeBorrowings: 4,
    rating: 4.7
  }
]

// Fungsi untuk mencari user berdasarkan email dan password
export const validateUser = (email, password) => {
  const user = dummyUsers.find(u => u.email === email)
  
  if (!user) {
    return { success: false, message: 'Email tidak ditemukan' }
  }
  
  if (user.password !== password) {
    return { success: false, message: 'Password salah' }
  }
  
  return { success: true, user }
}

// Fungsi untuk mendapatkan user berdasarkan email
export const getUserByEmail = (email) => {
  return dummyUsers.find(u => u.email === email)
}

// Fungsi untuk mendapatkan semua user berdasarkan role
export const getUsersByRole = (role) => {
  return dummyUsers.filter(u => u.role === role)
}

// Role descriptions
export const roleDescriptions = {
  owner: {
    label: 'Pemilik',
    description: 'Pemilik alat atau sistem',
    icon: '👨‍💼',
    color: '#FF6B6B',
    permissions: ['view_all', 'manage_borrowings', 'manage_items', 'view_reports']
  },
  admin: {
    label: 'Administrator',
    description: 'Pengelola sistem keseluruhan',
    icon: '👨‍💻',
    color: '#4ECDC4',
    permissions: ['manage_users', 'manage_system', 'view_all_reports', 'system_settings']
  },
  staff: {
    label: 'Staff',
    description: 'Pekerja atau karyawan',
    icon: '👨‍🔧',
    color: '#45B7D1',
    permissions: ['manage_borrowings', 'process_orders', 'view_reports']
  },
  customer: {
    label: 'Pelanggan',
    description: 'Pengguna / Peminjam',
    icon: '👨‍🎓',
    color: '#96CEB4',
    permissions: ['view_items', 'borrow_items', 'view_own_borrowings']
  }
}
