// Sistem Otoritas dan Permission untuk Dashboard
// Berdasarkan role: admin, staff (petugas), customer (peminjam)

export const ROLES = {
  ADMIN: 'admin',
  STAFF: 'staff',
  CUSTOMER: 'customer',
  OWNER: 'owner'
}

export const PERMISSIONS = {
  // === LOGIN & GENERAL ===
  LOGIN: 'login',
  LOGOUT: 'logout',

  // === USER MANAGEMENT ===
  CRUD_USER: 'crud_user',
  VIEW_ALL_USERS: 'view_all_users',
  MANAGE_USER_ROLES: 'manage_user_roles',

  // === EQUIPMENT MANAGEMENT ===
  CRUD_EQUIPMENT: 'crud_equipment',
  VIEW_ALL_EQUIPMENT: 'view_all_equipment',
  CRUD_CATEGORY: 'crud_category',
  MANAGE_STOCK: 'manage_stock',

  // === BORROWING MANAGEMENT ===
  CRUD_BORROWING: 'crud_borrowing',
  APPROVE_BORROWING: 'approve_borrowing',
  REJECT_BORROWING: 'reject_borrowing',
  REQUEST_BORROWING: 'request_borrowing',

  // === RETURN MANAGEMENT ===
  CRUD_RETURN: 'crud_return',
  VERIFY_RETURN: 'verify_return',
  RETURN_EQUIPMENT: 'return_equipment',

  // === REPORTING & LOGGING ===
  VIEW_REPORTS: 'view_reports',
  PRINT_REPORTS: 'print_reports',
  VIEW_ACTIVITY_LOG: 'view_activity_log',

  // === PROFILE & SETTINGS ===
  VIEW_PROFILE: 'view_profile',
  EDIT_PROFILE: 'edit_profile',
  VIEW_BORROWING_HISTORY: 'view_borrowing_history',
  EXTEND_BORROWING: 'extend_borrowing'
}

// Role-based permission mapping
export const ROLE_PERMISSIONS = {
  [ROLES.ADMIN]: [
    PERMISSIONS.LOGIN,
    PERMISSIONS.LOGOUT,
    PERMISSIONS.CRUD_USER,
    PERMISSIONS.VIEW_ALL_USERS,
    PERMISSIONS.MANAGE_USER_ROLES,
    PERMISSIONS.CRUD_EQUIPMENT,
    PERMISSIONS.VIEW_ALL_EQUIPMENT,
    PERMISSIONS.CRUD_CATEGORY,
    PERMISSIONS.MANAGE_STOCK,
    PERMISSIONS.CRUD_BORROWING,
    PERMISSIONS.APPROVE_BORROWING,
    PERMISSIONS.REJECT_BORROWING,
    PERMISSIONS.CRUD_RETURN,
    PERMISSIONS.VERIFY_RETURN,
    PERMISSIONS.VIEW_REPORTS,
    PERMISSIONS.PRINT_REPORTS,
    PERMISSIONS.VIEW_ACTIVITY_LOG,
    PERMISSIONS.VIEW_PROFILE,
    PERMISSIONS.EDIT_PROFILE,
    PERMISSIONS.VIEW_BORROWING_HISTORY,
    PERMISSIONS.EXTEND_BORROWING
  ],

  [ROLES.STAFF]: [
    PERMISSIONS.LOGIN,
    PERMISSIONS.LOGOUT,
    PERMISSIONS.VIEW_ALL_EQUIPMENT,
    PERMISSIONS.APPROVE_BORROWING,
    PERMISSIONS.REJECT_BORROWING,
    PERMISSIONS.VERIFY_RETURN,
    PERMISSIONS.PRINT_REPORTS,
    PERMISSIONS.VIEW_PROFILE,
    PERMISSIONS.EDIT_PROFILE,
    PERMISSIONS.VIEW_BORROWING_HISTORY
  ],

  [ROLES.CUSTOMER]: [
    PERMISSIONS.LOGIN,
    PERMISSIONS.LOGOUT,
    PERMISSIONS.VIEW_ALL_EQUIPMENT,
    PERMISSIONS.REQUEST_BORROWING,
    PERMISSIONS.RETURN_EQUIPMENT,
    PERMISSIONS.VIEW_PROFILE,
    PERMISSIONS.EDIT_PROFILE,
    PERMISSIONS.VIEW_BORROWING_HISTORY,
    PERMISSIONS.EXTEND_BORROWING
  ],

  [ROLES.OWNER]: [
    PERMISSIONS.LOGIN,
    PERMISSIONS.LOGOUT,
    PERMISSIONS.CRUD_USER,
    PERMISSIONS.VIEW_ALL_USERS,
    PERMISSIONS.MANAGE_USER_ROLES,
    PERMISSIONS.CRUD_EQUIPMENT,
    PERMISSIONS.VIEW_ALL_EQUIPMENT,
    PERMISSIONS.CRUD_CATEGORY,
    PERMISSIONS.MANAGE_STOCK,
    PERMISSIONS.CRUD_BORROWING,
    PERMISSIONS.APPROVE_BORROWING,
    PERMISSIONS.REJECT_BORROWING,
    PERMISSIONS.CRUD_RETURN,
    PERMISSIONS.VERIFY_RETURN,
    PERMISSIONS.VIEW_REPORTS,
    PERMISSIONS.PRINT_REPORTS,
    PERMISSIONS.VIEW_ACTIVITY_LOG,
    PERMISSIONS.VIEW_PROFILE,
    PERMISSIONS.EDIT_PROFILE,
    PERMISSIONS.VIEW_BORROWING_HISTORY,
    PERMISSIONS.EXTEND_BORROWING
  ]
}

// Helper function to check if user has permission
export const hasPermission = (userRole, permission) => {
  const permissions = ROLE_PERMISSIONS[userRole] || []
  return permissions.includes(permission)
}

// Helper function to check multiple permissions (AND logic)
export const hasAllPermissions = (userRole, permissions) => {
  return permissions.every(permission => hasPermission(userRole, permission))
}

// Helper function to check multiple permissions (OR logic)
export const hasAnyPermission = (userRole, permissions) => {
  return permissions.some(permission => hasPermission(userRole, permission))
}

// Dashboard menu items berdasarkan role
export const DASHBOARD_MENU = {
  [ROLES.ADMIN]: [
    { id: 'dashboard', label: '📊 Dashboard', icon: '📊' },
    { id: 'users', label: '👥 Kelola User', icon: '👥' },
    { id: 'equipment', label: '📦 Kelola Alat', icon: '📦' },
    { id: 'categories', label: '🏷️ Kategori', icon: '🏷️' },
    { id: 'borrowings', label: '📋 Peminjaman', icon: '📋' },
    { id: 'returns', label: '↩️ Pengembalian', icon: '↩️' },
    { id: 'reports', label: '📈 Laporan', icon: '📈' },
    { id: 'activities', label: '📜 Aktivitas', icon: '📜' }
  ],

  [ROLES.STAFF]: [
    { id: 'dashboard', label: '📊 Dashboard', icon: '📊' },
    { id: 'approvals', label: '✅ Persetujuan Peminjaman', icon: '✅' },
    { id: 'verifications', label: '🔍 Verifikasi Pengembalian', icon: '🔍' },
    { id: 'reports', label: '📋 Cetak Laporan', icon: '📋' },
    { id: 'profile', label: '👤 Profil Saya', icon: '👤' }
  ],

  [ROLES.CUSTOMER]: [
    { id: 'explore', label: '🛍️ Jelajahi Alat', icon: '🛍️' },
    { id: 'my-borrowings', label: '📦 Peminjaman Saya', icon: '📦' },
    { id: 'recommendations', label: '⭐ Rekomendasi Untuk Anda', icon: '⭐' },
    { id: 'profile', label: '👤 Profil Saya', icon: '👤' }
  ]
}

export default {
  ROLES,
  PERMISSIONS,
  ROLE_PERMISSIONS,
  hasPermission,
  hasAllPermissions,
  hasAnyPermission,
  DASHBOARD_MENU
}
