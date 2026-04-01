// Role-based permissions and authorization system

export const rolePermissions = {
  owner: {
    label: 'Pemilik',
    description: 'Pemilik alat atau sistem',
    icon: '👨‍💼',
    color: '#FF6B6B',
    permissions: {
      viewAll: true,
      manageBorrowings: true,
      manageItems: true,
      viewReports: true,
      manageUsers: false,
      systemSettings: false,
      viewAdminPanel: false
    },
    dashboardTabs: ['overview', 'my-items', 'borrowings', 'reports', 'profile', 'help']
  },

  admin: {
    label: 'Administrator',
    description: 'Pengelola sistem keseluruhan',
    icon: '👨‍💻',
    color: '#4ECDC4',
    permissions: {
      viewAll: true,
      manageBorrowings: true,
      manageItems: true,
      viewReports: true,
      manageUsers: true,
      systemSettings: true,
      viewAdminPanel: true
    },
    dashboardTabs: ['overview', 'users', 'borrowings', 'items', 'reports', 'settings', 'logs', 'help']
  },

  staff: {
    label: 'Staff',
    description: 'Pekerja atau karyawan',
    icon: '👨‍🔧',
    color: '#45B7D1',
    permissions: {
      viewAll: true,
      manageBorrowings: true,
      manageItems: false,
      viewReports: true,
      manageUsers: false,
      systemSettings: false,
      viewAdminPanel: false
    },
    dashboardTabs: ['overview', 'borrowings', 'orders', 'reports', 'profile', 'help']
  },

  customer: {
    label: 'Pelanggan',
    description: 'Pengguna / Peminjam',
    icon: '👨‍🎓',
    color: '#96CEB4',
    permissions: {
      viewAll: false,
      manageBorrowings: false,
      manageItems: false,
      viewReports: false,
      manageUsers: false,
      systemSettings: false,
      viewAdminPanel: false
    },
    dashboardTabs: ['overview', 'browse', 'my-borrowings', 'profile', 'help']
  }
}

// Check if user has specific permission
export const hasPermission = (userRole, permission) => {
  if (!rolePermissions[userRole]) return false
  return rolePermissions[userRole].permissions[permission] === true
}

// Check if user can access specific feature
export const canAccess = (userRole, feature) => {
  if (!rolePermissions[userRole]) return false
  const permissions = rolePermissions[userRole].permissions
  
  const featureMap = {
    'view-all-data': permissions.viewAll,
    'manage-borrowings': permissions.manageBorrowings,
    'manage-items': permissions.manageItems,
    'view-reports': permissions.viewReports,
    'manage-users': permissions.manageUsers,
    'system-settings': permissions.systemSettings,
    'admin-panel': permissions.viewAdminPanel
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
