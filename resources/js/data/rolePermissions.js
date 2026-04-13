// Role-based permissions and authorization system
// Sesuai dengan tabel otoritas: Admin, Staff (Petugas), Customer (Peminjam)

export const rolePermissions = {
  owner: {
    label: 'Pemilik',
    description: 'Pemilik - Pengamat saja (Read-Only)',
    icon: '👨‍💼',
    color: '#FF6B6B',
    permissions: {
      // Login & Logout
      login: true,
      logout: true,
      
      // User Management
      crud_user: false,           // ✗ Tidak bisa manage user
      view_all_users: true,       // ✓ Hanya view
      manage_user_roles: false,
      
      // Equipment Management
      crud_equipment: false,      // ✗ Tidak bisa edit equipment
      view_all_equipment: true,   // ✓ Hanya view
      crud_category: false,
      manage_stock: false,
      
      // Borrowing Management
      crud_borrowing: false,
      approve_borrowing: false,
      reject_borrowing: false,
      request_borrowing: false,   // ✗ Owner tidak pinjam
      
      // Return Management
      crud_return: false,
      verify_return: false,
      return_equipment: false,
      
      // Reporting & Logging
      view_reports: true,         // ✓ View analytics/reports
      print_reports: true,        // ✓ Download/print reports
      view_activity_log: true,    // ✓ View activity logs
      
      // Profile & Settings
      view_profile: true,
      edit_profile: true,
      view_borrowing_history: false,  // ✗ Owner tidak ada borrowing history
      extend_borrowing: false
    },
    dashboardTabs: ['overview', 'borrowings', 'users', 'items', 'reports', 'activity-logs', 'profile', 'help']
  },

  admin: {
    label: 'Administrator',
    description: 'Admin - Kelola data sistem',
    icon: '👨‍💻',
    color: '#4ECDC4',
    permissions: {
      // Login & Logout
      login: true,
      logout: true,
      
      // User Management
      crud_user: true,            // ✓ Full CRUD user
      view_all_users: true,       // ✓ View semua users
      manage_user_roles: true,    // ✓ Manage roles
      
      // Equipment Management
      crud_equipment: true,       // ✓ Full CRUD equipment
      view_all_equipment: true,   // ✓ View semua equipment
      crud_category: true,        // ✓ Full CRUD category
      manage_stock: true,         // ✓ Manage stock
      
      // Borrowing Management
      crud_borrowing: false,      // ✗ Admin tidak CRUD borrowing diri sendiri
      approve_borrowing: false,   // ✗ Staff yang approve
      reject_borrowing: false,
      request_borrowing: false,   // ✗ Admin tidak pinjam
      
      // Return Management
      crud_return: false,
      verify_return: false,       // ✗ Staff yang verify
      return_equipment: false,
      
      // Reporting & Logging
      view_reports: true,         // ✓ View reports
      print_reports: true,        // ✓ Print reports
      view_activity_log: true,    // ✓ View activity logs
      
      // Profile & Settings
      view_profile: true,
      edit_profile: true,
      view_borrowing_history: false,  // ✗ Admin tidak punya borrowing
      extend_borrowing: false
    },
    dashboardTabs: ['overview', 'users', 'items', 'returns', 'reports', 'activity-logs', 'settings', 'profile', 'help']
  },

  staff: {
    label: 'Staff / Petugas',
    description: 'Staff - Proses peminjaman & pengembalian',
    icon: '👨‍🔧',
    color: '#45B7D1',
    permissions: {
      // Login & Logout
      login: true,
      logout: true,
      
      // User Management
      crud_user: false,           // ✗ Tidak bisa manage user
      view_all_users: false,      // ✗ Tidak perlu view users
      manage_user_roles: false,
      
      // Equipment Management
      crud_equipment: false,      // ✗ Tidak bisa edit equipment
      view_all_equipment: true,   // ✓ View untuk melihat stok
      crud_category: false,
      manage_stock: false,
      
      // Borrowing Management
      crud_borrowing: false,      // ✗ Tidak CRUD
      approve_borrowing: true,    // ✓ Setujui peminjaman
      reject_borrowing: true,     // ✓ Tolak peminjaman
      request_borrowing: false,   // ✗ Staff tidak pinjam
      
      // Return Management
      crud_return: false,
      verify_return: true,        // ✓ Verifikasi pengembalian
      return_equipment: false,    // ✗ Staff verify, bukan user yang return
      
      // Reporting & Logging
      view_reports: true,         // ✓ View borrowing reports
      print_reports: true,        // ✓ Print laporan
      view_activity_log: false,
      
      // Profile & Settings
      view_profile: true,
      edit_profile: true,
      view_borrowing_history: true,   // ✓ Lihat history untuk context
      extend_borrowing: false
    },
    dashboardTabs: ['overview', 'approvals', 'verifications', 'borrowings', 'reports', 'profile', 'help']
  },

  customer: {
    label: 'Pelanggan / Peminjam',
    description: 'Customer - Pinjam dan kembalikan alat',
    icon: '👨‍🎓',
    color: '#96CEB4',
    permissions: {
      // Login & Logout
      login: true,
      logout: true,
      
      // User Management
      crud_user: false,           // ✗ Tidak bisa manage user
      view_all_users: false,      // ✗ Tidak perlu view users
      manage_user_roles: false,
      
      // Equipment Management
      crud_equipment: false,      // ✗ Tidak bisa edit
      view_all_equipment: true,   // ✓ Browse alat yang tersedia
      crud_category: false,
      manage_stock: false,
      
      // Borrowing Management
      crud_borrowing: false,      // ✗ Tidak CRUD borrowing (hanya request)
      approve_borrowing: false,   // ✗ Staff yang approve
      reject_borrowing: false,
      request_borrowing: true,    // ✓ Mengajukan peminjaman
      
      // Return Management
      crud_return: false,
      verify_return: false,       // ✗ Staff yang verify
      return_equipment: true,     // ✓ Mengembalikan alat
      
      // Reporting & Logging
      view_reports: false,        // ✗ Tidak perlu reports
      print_reports: false,
      view_activity_log: false,
      
      // Profile & Settings
      view_profile: true,         // ✓ Lihat profil sendiri
      edit_profile: true,         // ✓ Edit profil sendiri
      view_borrowing_history: true,   // ✓ Lihat riwayat peminjaman sendiri
      extend_borrowing: true      // ✓ Minta perpanjangan (future feature)
    },
    dashboardTabs: ['overview', 'explore', 'my-borrowings', 'profile', 'help']
  }
}

// Check if user has specific permission
export const hasPermission = (userRole, permission) => {
  if (!rolePermissions[userRole]) return false
  return rolePermissions[userRole].permissions[permission] === true
}

// Check multiple permissions (AND - semua harus true)
export const hasAllPermissions = (userRole, permissions) => {
  return permissions.every(permission => hasPermission(userRole, permission))
}

// Check multiple permissions (OR - minimal satu true)
export const hasAnyPermission = (userRole, permissions) => {
  return permissions.some(permission => hasPermission(userRole, permission))
}

// Check if user can access specific feature (backward compatible)
export const canAccess = (userRole, feature) => {
  if (!rolePermissions[userRole]) return false
  
  const featureMap = {
    'login': rolePermissions[userRole].permissions.login,
    'logout': rolePermissions[userRole].permissions.logout,
    'crud-user': rolePermissions[userRole].permissions.crud_user,
    'crud-equipment': rolePermissions[userRole].permissions.crud_equipment,
    'crud-category': rolePermissions[userRole].permissions.crud_category,
    'crud-borrowing': rolePermissions[userRole].permissions.crud_borrowing,
    'approve-borrowing': rolePermissions[userRole].permissions.approve_borrowing,
    'crud-return': rolePermissions[userRole].permissions.crud_return,
    'verify-return': rolePermissions[userRole].permissions.verify_return,
    'view-reports': rolePermissions[userRole].permissions.view_reports,
    'print-reports': rolePermissions[userRole].permissions.print_reports,
    'view-activity-log': rolePermissions[userRole].permissions.view_activity_log,
    'view-all-equipment': rolePermissions[userRole].permissions.view_all_equipment,
    'request-borrowing': rolePermissions[userRole].permissions.request_borrowing,
    'return-equipment': rolePermissions[userRole].permissions.return_equipment
  }
  
  return featureMap[feature] || false
}

// Get allowed tabs for role
export const getAllowedTabs = (userRole) => {
  return rolePermissions[userRole]?.dashboardTabs || []
}

// Get role color
export const getRoleColor = (userRole) => {
  return rolePermissions[userRole]?.color || '#666'
}

// Get role label
export const getRoleLabel = (userRole) => {
  return rolePermissions[userRole]?.label || 'User'
}

// Get role Icon
export const getRoleIcon = (userRole) => {
  return rolePermissions[userRole]?.icon || '👤'
}

// Get role permissions object
export const getRolePermissions = (userRole) => {
  return rolePermissions[userRole]?.permissions || {}
}

// Get all role permissions info
export const getAllRolePermissions = () => {
  return rolePermissions
}

// Check if role is admin or owner (has high privileges)
export const isAdminOrOwner = (userRole) => {
  return userRole === 'admin' || userRole === 'owner'
}

// Check if role is staff (medium privileges)
export const isStaff = (userRole) => {
  return userRole === 'staff'
}

// Check if role is customer (low privileges)
export const isCustomer = (userRole) => {
  return userRole === 'customer'
}
