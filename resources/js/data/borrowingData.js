import { BORROWING_STATUSES, generatePickupCode } from './borrowingStatuses.js'

// Sample equipment list - Peralatan Kantor Lengkap
export const equipmentList = [
  // === KATEGORI 1: ELEKTRONIK & KOMPUTER ===
  {
    id: 'eq-1',
    name: 'Laptop Dell XPS 15',
    category: 'Elektronik & Komputer',
    stock: 5,
    condition: 'Sangat Baik',
    description: 'Laptop untuk presentasi, editing, dan pekerjaan kantor',
    image: '💻'
  },
  {
    id: 'eq-2',
    name: 'MacBook Pro 13"',
    category: 'Elektronik & Komputer',
    stock: 3,
    condition: 'Sangat Baik',
    description: 'Laptop premium untuk desain dan editing',
    image: '🖥️'
  },
  {
    id: 'eq-3',
    name: 'Desktop PC Workstation',
    category: 'Elektronik & Komputer',
    stock: 4,
    condition: 'Baik',
    description: 'Komputer desktop untuk pekerjaan berat',
    image: '🖱️'
  },
  {
    id: 'eq-4',
    name: 'Tablet iPad Pro 12.9"',
    category: 'Elektronik & Komputer',
    stock: 6,
    condition: 'Sangat Baik',
    description: 'Tablet untuk presentasi dan sketsa',
    image: '📱'
  },

  // === KATEGORI 2: PROYEKSI & DISPLAY ===
  {
    id: 'eq-5',
    name: 'Proyektor Epson EB-2250U',
    category: 'Proyeksi & Display',
    stock: 4,
    condition: 'Sangat Baik',
    description: 'Proyektor 4K untuk presentasi kelas dunia',
    image: '🎬'
  },
  {
    id: 'eq-6',
    name: 'Proyektor Mini Portabel',
    category: 'Proyeksi & Display',
    stock: 5,
    condition: 'Baik',
    description: 'Proyektor portable untuk meeting kecil',
    image: '🔦'
  },
  {
    id: 'eq-7',
    name: 'Monitor LED 27" 4K',
    category: 'Proyeksi & Display',
    stock: 3,
    condition: 'Sangat Baik',
    description: 'Monitor ultra HD untuk desain grafis',
    image: '🖼️'
  },
  {
    id: 'eq-8',
    name: 'Interactive Touchscreen 65"',
    category: 'Proyeksi & Display',
    stock: 2,
    condition: 'Sangat Baik',
    description: 'Layar sentuh interaktif untuk kolaborasi',
    image: '📊'
  },

  // === KATEGORI 3: AUDIO & RECORDING ===
  {
    id: 'eq-9',
    name: 'Microphone Condenser Rode',
    category: 'Audio & Recording',
    stock: 4,
    condition: 'Sangat Baik',
    description: 'Mic profesional untuk recording podcast',
    image: '🎙️'
  },
  {
    id: 'eq-10',
    name: 'Speaker Bluetooth JBL Flip 6',
    category: 'Audio & Recording',
    stock: 8,
    condition: 'Baik',
    description: 'Speaker portabel untuk presentasi',
    image: '🔊'
  },
  {
    id: 'eq-11',
    name: 'Audio Interface Focusrite 2i2',
    category: 'Audio & Recording',
    stock: 3,
    condition: 'Sangat Baik',
    description: 'Interface audio untuk recording studio',
    image: '🎵'
  },
  {
    id: 'eq-12',
    name: 'Headphones Sony WH-1000XM5',
    category: 'Audio & Recording',
    stock: 6,
    condition: 'Sangat Baik',
    description: 'Headphone premium dengan noise cancellation',
    image: '🎧'
  },

  // === KATEGORI 4: FOTOGRAFI & VIDEO ===
  {
    id: 'eq-13',
    name: 'Kamera DSLR Canon 5D Mark IV',
    category: 'Fotografi & Video',
    stock: 3,
    condition: 'Sangat Baik',
    description: 'Kamera full-frame untuk fotografi profesional',
    image: '📷'
  },
  {
    id: 'eq-14',
    name: 'Kamera Mirrorless Sony A7IV',
    category: 'Fotografi & Video',
    stock: 2,
    condition: 'Sangat Baik',
    description: 'Kamera mirrorless untuk video 4K',
    image: '📹'
  },
  {
    id: 'eq-15',
    name: 'Lensa Telephoto 70-200mm',
    category: 'Fotografi & Video',
    stock: 4,
    condition: 'Baik',
    description: 'Lensa zoom profesional',
    image: '🔭'
  },
  {
    id: 'eq-16',
    name: 'Gimbal Video DJI Ronin 4D',
    category: 'Fotografi & Video',
    stock: 2,
    condition: 'Sangat Baik',
    description: 'Stabilizer 3-axis untuk video sinematik',
    image: '🎥'
  },

  // === KATEGORI 5: PERALATAN PRESENTASI ===
  {
    id: 'eq-17',
    name: 'Pointer Laser Wireless',
    category: 'Peralatan Presentasi',
    stock: 10,
    condition: 'Baik',
    description: 'Pointer laser dengan remote presentasi',
    image: '🔴'
  },
  {
    id: 'eq-18',
    name: 'Clicker Presentasi Logitech R700',
    category: 'Peralatan Presentasi',
    stock: 8,
    condition: 'Sangat Baik',
    description: 'Remote control presentasi profesional',
    image: '📍'
  },
  {
    id: 'eq-19',
    name: 'Tripod Kamera Profesional',
    category: 'Peralatan Presentasi',
    stock: 5,
    condition: 'Baik',
    description: 'Stand tripod untuk kamera/proyektor',
    image: '🎯'
  },
  {
    id: 'eq-20',
    name: 'Whiteboard & Flip Chart',
    category: 'Peralatan Presentasi',
    stock: 4,
    condition: 'Baik',
    description: 'Papan tulis dan flip chart interaktif',
    image: '📌'
  },

  // === KATEGORI 6: PERALATAN KANTOR LAINNYA ===
  {
    id: 'eq-21',
    name: 'Printer Laser Berwarna Canon',
    category: 'Peralatan Kantor Lainnya',
    stock: 2,
    condition: 'Baik',
    description: 'Printer multifungsi untuk dokumen berkualitas',
    image: '🖨️'
  },
  {
    id: 'eq-22',
    name: 'Scanner Dokumen Fujitsu',
    category: 'Peralatan Kantor Lainnya',
    stock: 3,
    condition: 'Sangat Baik',
    description: 'Scanner high-speed untuk digitalisasi dokumen',
    image: '📠'
  },
  {
    id: 'eq-23',
    name: 'Lampu LED Studio 3-Warna',
    category: 'Peralatan Kantor Lainnya',
    stock: 6,
    condition: 'Baik',
    description: 'Lampu LED RGB untuk fotografi dan video',
    image: '💡'
  },
  {
    id: 'eq-24',
    name: 'Webcam 4K Logitech BRIO',
    category: 'Peralatan Kantor Lainnya',
    stock: 5,
    condition: 'Sangat Baik',
    description: 'Webcam ultra HD untuk video conference',
    image: '📸'
  }
]

// Dummy borrowing records (untuk demo)
export const borrowingRecords = [
  {
    id: 'BOR-001',
    customerId: 'customer-1',
    customerName: 'Ahmad Rizki',
    customerEmail: 'ahmad@school.id',
    equipmentId: 'eq-1',
    equipmentName: 'Laptop Dell XPS 15',
    equipmentQuantity: 1,
    
    // Timeline
    borrowDate: new Date(2026, 3, 1, 8, 0, 0),
    plannedReturnDate: new Date(2026, 3, 8, 23, 59, 59),
    actualReturnDate: null,
    
    // Status & Approval
    status: BORROWING_STATUSES.PICKED_UP,
    approvedByStaff: 'staff-1',
    approvedByStaffName: 'Hendra Wijaksono',
    approvedAt: new Date(2026, 3, 1, 8, 30, 0),
    
    // Pickup code & verification
    pickupCode: '123-456',
    pickupCodeGeneratedAt: new Date(2026, 3, 1, 8, 30, 0),
    pickupVerifiedAt: new Date(2026, 3, 1, 10, 15, 0),
    pickupPhotoUrl: null,
    
    // Return verification
    returnVerifiedByCustomer: false,
    returnVerifiedByStaff: false,
    returnPhotoBefore: null,
    returnPhotoAfter: null,
    damageNotes: '',
    
    // Reason & notes
    borrowReason: 'Untuk riset semester',
    approvalNotes: 'Approved',
    rejectionReason: null,
    
    // Fine
    isOverdue: false,
    lateDays: 0,
    fineAmount: 0,
    finePaid: false
  }
]

// Create new borrowing request
export const createBorrowingRequest = (customer, equipment, quantity, returnDate, reason = '') => {
  const id = `BOR-${Date.now()}`
  const borrowDate = new Date()
  
  return {
    id,
    customerId: customer.id,
    customerName: customer.fullname,
    customerEmail: customer.email,
    equipmentId: equipment.id,
    equipmentName: equipment.name,
    equipmentQuantity: quantity,
    
    borrowDate,
    plannedReturnDate: new Date(returnDate),
    actualReturnDate: null,
    
    status: BORROWING_STATUSES.APPLIED,
    approvedByStaff: null,
    approvedByStaffName: null,
    approvedAt: null,
    
    pickupCode: null,
    pickupCodeGeneratedAt: null,
    pickupVerifiedAt: null,
    pickupPhotoUrl: null,
    
    returnVerifiedByCustomer: false,
    returnVerifiedByStaff: false,
    returnPhotoBefore: null,
    returnPhotoAfter: null,
    damageNotes: '',
    
    borrowReason: reason,
    approvalNotes: '',
    rejectionReason: null,
    
    isOverdue: false,
    lateDays: 0,
    fineAmount: 0,
    finePaid: false
  }
}

// Approve borrowing request
export const approveBorrowingRequest = (borrowing, staff, notes = '') => {
  return {
    ...borrowing,
    status: BORROWING_STATUSES.APPROVED,
    approvedByStaff: staff.id,
    approvedByStaffName: staff.fullname,
    approvedAt: new Date(),
    approvalNotes: notes
  }
}

// Reject borrowing request
export const rejectBorrowingRequest = (borrowing, reason = '') => {
  return {
    ...borrowing,
    status: BORROWING_STATUSES.REJECTED,
    rejectionReason: reason
  }
}

// Generate pickup code for borrowing
export const generatePickupCodeForBorrowing = (borrowing) => {
  return {
    ...borrowing,
    status: BORROWING_STATUSES.READY_FOR_PICKUP,
    pickupCode: generatePickupCode(),
    pickupCodeGeneratedAt: new Date()
  }
}

// Verify pickup
export const verifyPickup = (borrowing, pickupCode, staffId, photoUrl = null) => {
  if (borrowing.pickupCode !== pickupCode.toString().trim()) {
    return {
      success: false,
      message: 'Kode pickup tidak sesuai'
    }
  }

  return {
    success: true,
    borrowing: {
      ...borrowing,
      status: BORROWING_STATUSES.PICKED_UP,
      pickupVerifiedAt: new Date(),
      pickupPhotoUrl: photoUrl
    }
  }
}

// Verify return (customer part)
export const verifyReturnCustomer = (borrowing, photoUrl = null) => {
  return {
    ...borrowing,
    returnVerifiedByCustomer: true,
    returnPhotoBefore: photoUrl
  }
}

// Verify return (staff part)
export const verifyReturnStaff = (borrowing, photoUrl = null, damageNotes = '') => {
  const actualReturnDate = new Date()
  const planned = new Date(borrowing.plannedReturnDate)
  const msPerDay = 24 * 60 * 60 * 1000
  const lateDays = Math.max(0, Math.ceil((actualReturnDate - planned) / msPerDay))
  const fineAmount = lateDays * 50000

  return {
    ...borrowing,
    status: BORROWING_STATUSES.RETURNED,
    returnVerifiedByStaff: true,
    returnPhotoAfter: photoUrl,
    damageNotes: damageNotes,
    actualReturnDate,
    lateDays,
    fineAmount,
    isOverdue: lateDays > 0
  }
}

// Get customer's borrowing history
export const getCustomerBorrowings = (customerId, records = borrowingRecords) => {
  return records.filter(b => b.customerId === customerId)
}

// Get pending approvals for staff
export const getPendingApprovals = (records = borrowingRecords) => {
  return records.filter(b => b.status === BORROWING_STATUSES.APPLIED)
}

// Get ready for pickup
export const getReadyForPickup = (records = borrowingRecords) => {
  return records.filter(b => b.status === BORROWING_STATUSES.READY_FOR_PICKUP)
}

// Get pending returns
export const getPendingReturns = (records = borrowingRecords) => {
  return records.filter(b => b.status === BORROWING_STATUSES.PICKED_UP)
}

// Get overdue borrowings
export const getOverdueBorrowings = (records = borrowingRecords) => {
  return records.filter(b => {
    const now = new Date()
    const plannedReturn = new Date(b.plannedReturnDate)
    return b.status === BORROWING_STATUSES.PICKED_UP && now > plannedReturn
  })
}

// Get completed borrowings
export const getCompletedBorrowings = (records = borrowingRecords) => {
  return records.filter(b => b.status === BORROWING_STATUSES.RETURNED)
}

// Get equipment by id
export const getEquipmentById = (equipmentId) => {
  return equipmentList.find(eq => eq.id === equipmentId)
}

// Check equipment availability
export const isEquipmentAvailable = (equipmentId, quantity = 1) => {
  const equipment = getEquipmentById(equipmentId)
  return equipment && equipment.stock >= quantity
}

// Update equipment stock after approval
export const decreaseEquipmentStock = (equipmentId, quantity = 1) => {
  const equipment = getEquipmentById(equipmentId)
  if (equipment) {
    equipment.stock = Math.max(0, equipment.stock - quantity)
  }
}

// Return equipment stock after cancellation or rejection
export const increaseEquipmentStock = (equipmentId, quantity = 1) => {
  const equipment = getEquipmentById(equipmentId)
  if (equipment) {
    equipment.stock += quantity
  }
}
