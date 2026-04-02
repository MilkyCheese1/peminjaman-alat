// Role-based permissions and authorization system
// Sesuai dengan tabel otoritas: Admin, Staff (Petugas), Customer (Peminjam)

export const rolePermissions = {
  owner: {
    label: 'Pemilik',
    description: 'Pemilik alat atau sistem',
    icon: '👨‍💼',
    color: '#FF6B6B',
    permissions: {
      // Login & Logout
      login: true,
      logout: true,
      
      // User Management
      crud_user: true,
      view_all_users: true,
      manage_user_roles: true,
      
      // Equipment Management
      crud_equipment: true,
      view_all_equipment: true,
      crud_category: true,
      manage_stock: true,
      
      // Borrowing Management
      crud_borrowing: true,
      approve_borrowing: true,
      reject_borrowing: true,
      request_borrowing: true,
      
      // Return Management
      crud_return: true,
      verify_return: true,
      return_equipment: true,
      
      // Reporting & Logging
      view_reports: true,
      print_reports: true,
      view_activity_log: true,
      
      // Profile & Settings
      view_profile: true,
      edit_profile: true,
      view_borrowing_history: true,
      extend_borrowing: true
    },
    dashboardTabs: ['overview', 'my-items', 'borrowings', 'reports', 'profile', 'help']
  },

  admin: {
    label: 'Administrator',
    description: 'Pengelola sistem keseluruhan',
    icon: '👨‍💻',
    color: '#4ECDC4',
    permissions: {
      // Login & Logout
      login: true,
      logout: true,
      
      // User Management
      crud_user: true,
      view_all_users: true,
      manage_user_roles: true,
      
      // Equipment Management
      crud_equipment: true,
      view_all_equipment: true,
      crud_category: true,
      manage_stock: true,
      
      // Borrowing Management
      crud_borrowing: true,
      approve_borrowing: true,
      reject_borrowing: true,
      request_borrowing: false,
      
      // Return Management
      crud_return: true,
      verify_return: true,
      return_equipment: false,
      
      // Reporting & Logging
      view_reports: true,
      print_reports: true,
      view_activity_log: true,
      
      // Profile & Settings
      view_profile: true,
      edit_profile: true,
      view_borrowing_history: true,
      extend_borrowing: false
    },
    dashboardTabs: ['overview', 'users', 'borrowings', 'items', 'returns', 'reports', 'activity-logs', 'settings', 'help']
  },

  staff: {
    label: 'Staff / Petugas',
    description: 'Pekerja atau petugas peminjaman',
    icon: '👨‍🔧',
    color: '#45B7D1',
    permissions: {
      // Login & Logout
      login: true,
      logout: true,
      
      // User Management
      crud_user: false,
      view_all_users: false,
      manage_user_roles: false,
      
      // Equipment Management
      crud_equipment: false,
      view_all_equipment: true,
      crud_category: false,
      manage_stock: false,
      
      // Borrowing Management
      crud_borrowing: false,
      approve_borrowing: true,  // ✓ Menyetujui Peminjaman
      reject_borrowing: true,   // ✓ Menyetujui Peminjaman
      request_borrowing: false,
      
      // Return Management
      crud_return: false,
      verify_return: true,      // ✓ Memeriksa Pengembalian
      return_equipment: false,
      
      // Reporting & Logging
      view_reports: true,       // ✓ Mencetak Laporan
      print_reports: true,      // ✓ Mencetak Laporan
      view_activity_log: false,
      
      // Profile & Settings
      view_profile: true,
      edit_profile: true,
      view_borrowing_history: true,
      extend_borrowing: false
    },
    dashboardTabs: ['overview', 'approvals', 'verifications', 'reports', 'profile', 'help']
  },

  customer: {
    label: 'Pelanggan / Peminjam',
    description: 'Pengguna / Peminjam alat',
    icon: '👨‍🎓',
    color: '#96CEB4',
    permissions: {
      // Login & Logout
      login: true,
      logout: true,
      
      // User Management
      crud_user: false,
      view_all_users: false,
      manage_user_roles: false,
      
      // Equipment Management
      crud_equipment: false,
      view_all_equipment: true,    // ✓ Melihat daftar alat
      crud_category: false,
      manage_stock: false,
      
      // Borrowing Management
      crud_borrowing: false,
      approve_borrowing: false,
      reject_borrowing: false,
      request_borrowing: true,      // ✓ Mengajukan peminjaman
      
      // Return Management
      crud_return: false,
      verify_return: false,
      return_equipment: true,       // ✓ Mengembalikan alat
      
      // Reporting & Logging
      view_reports: false,
      print_reports: false,
      view_activity_log: false,
      
      // Profile & Settings
      view_profile: true,
      edit_profile: true,
      view_borrowing_history: true,
      extend_borrowing: true
    },
    dashboardTabs: ['overview', 'explore', 'my-borrowings', 'recommendations', 'profile', 'help']
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
