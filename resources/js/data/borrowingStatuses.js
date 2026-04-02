// Status workflow untuk sistem verifikasi peminjaman
export const BORROWING_STATUSES = {
  APPLIED: 'applied',
  APPROVED: 'approved',
  READY_FOR_PICKUP: 'ready_for_pickup',
  PICKED_UP: 'picked_up',
  RETURNED: 'returned',
  REJECTED: 'rejected',
  CANCELLED: 'cancelled',
  OVERDUE: 'overdue'
}

export const STATUS_INFO = {
  applied: {
    label: 'Menunggu Persetujuan',
    icon: '⏳',
    color: '#FFC107',
    description: 'Aplikasi peminjaman telah dikirim, menunggu persetujuan staff',
    nextAction: 'Tunggu persetujuan dari staff'
  },
  approved: {
    label: 'Disetujui',
    icon: '✅',
    color: '#28A745',
    description: 'Aplikasi disetujui, staff sedang menyiapkan alat',
    nextAction: 'Staff menyiapkan alat untuk pickup'
  },
  ready_for_pickup: {
    label: 'Siap Diambil',
    icon: '📦',
    color: '#17A2B8',
    description: 'Alat sudah siap, ambil dengan pickup code',
    nextAction: 'Ambil alat dengan pickup code'
  },
  picked_up: {
    label: 'Sedang Dipinjam',
    icon: '🚚',
    color: '#007BFF',
    description: 'Alat telah diambil dan sedang digunakan',
    nextAction: 'Kembalikan alat sesuai tanggal yang ditentukan'
  },
  returned: {
    label: 'Sudah Dikembalikan',
    icon: '✔️',
    color: '#20C997',
    description: 'Peminjaman selesai',
    nextAction: null
  },
  rejected: {
    label: 'Ditolak',
    icon: '❌',
    color: '#DC3545',
    description: 'Aplikasi peminjaman ditolak',
    nextAction: null
  },
  cancelled: {
    label: 'Dibatalkan',
    icon: '🚫',
    color: '#6C757D',
    description: 'Peminjaman dibatalkan',
    nextAction: 'Buat aplikasi baru jika diperlukan'
  },
  overdue: {
    label: 'Terlambat',
    icon: '🚨',
    color: '#E83E8C',
    description: 'Alat belum dikembalikan melewati batas waktu yang ditentukan',
    nextAction: 'Kembalikan alat segera untuk menghindari denda'
  }
}

// Generate pickup code (6-digit PIN format: XXX-XXX)
export const generatePickupCode = () => {
  const code = Math.random().toString().slice(2, 8)
  return `${code.slice(0, 3)}-${code.slice(3, 6)}`
}

// Check if pickup code is valid format
export const isValidPickupCode = (code) => {
  return /^\d{3}-\d{3}$/.test(code.toString().trim())
}

// Calculate fine for late return
export const calculateFine = (plannedReturnDate, actualReturnDate, dailyRate = 50000, maxDays = 30) => {
  if (new Date(actualReturnDate) <= new Date(plannedReturnDate)) {
    return {
      lateDays: 0,
      fine: 0,
      isOnTime: true
    }
  }

  const msPerDay = 24 * 60 * 60 * 1000
  const lateDays = Math.ceil((new Date(actualReturnDate) - new Date(plannedReturnDate)) / msPerDay)
  const cappedDays = Math.min(lateDays, maxDays)
  const fine = cappedDays * dailyRate

  return {
    lateDays,
    cappedDays,
    fine,
    isOnTime: false,
    exceeded: lateDays > maxDays
  }
}

// Format date to Indonesian format
export const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Format datetime to Indonesian format
export const formatDateTime = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Get duration in days
export const getDurationDays = (startDate, endDate) => {
  const msPerDay = 24 * 60 * 60 * 1000
  return Math.ceil((new Date(endDate) - new Date(startDate)) / msPerDay)
}

// Check if date is overdue
export const isOverdue = (plannedReturnDate) => {
  return new Date() > new Date(plannedReturnDate)
}
