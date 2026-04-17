// Mock Chat Service - Frontend only
export interface ChatMessage {
  id: string
  sender_id: number
  sender_name: string
  sender_type: 'customer' | 'staff' | 'admin'
  message: string
  is_read: boolean
  created_at: string
}

export interface ChatUser {
  id: number
  username: string
  nama_lengkap: string
  avatar?: string
  type: 'customer' | 'staff'
  last_message?: string
  last_message_time?: string
  unread_count: number
}

// Mock data storage
const mockChats: { [key: string]: ChatMessage[] } = {
  'customer_1': [
    {
      id: '1',
      sender_id: 1,
      sender_name: 'Budi Santoso',
      sender_type: 'customer',
      message: 'Halo, saya ingin bertanya tentang peminjaman alat',
      is_read: true,
      created_at: new Date(Date.now() - 3600000).toISOString()
    },
    {
      id: '2',
      sender_id: 999,
      sender_name: 'Admin Support',
      sender_type: 'admin',
      message: 'Halo Budi, ada yang bisa saya bantu?',
      is_read: true,
      created_at: new Date(Date.now() - 3300000).toISOString()
    },
    {
      id: '3',
      sender_id: 1,
      sender_name: 'Budi Santoso',
      sender_type: 'customer',
      message: 'Apakah alat excavator tersedia minggu depan?',
      is_read: true,
      created_at: new Date(Date.now() - 3000000).toISOString()
    },
    {
      id: '4',
      sender_id: 999,
      sender_name: 'Admin Support',
      sender_type: 'admin',
      message: 'Excavator available pada tanggal 20-27 April. Sudah ada booking?',
      is_read: true,
      created_at: new Date(Date.now() - 2700000).toISOString()
    }
  ],
  'customer_2': [
    {
      id: '5',
      sender_id: 2,
      sender_name: 'Siti Nurhaliza',
      sender_type: 'customer',
      message: 'Bagaimana cara perpanjangan peminjaman?',
      is_read: false,
      created_at: new Date(Date.now() - 600000).toISOString()
    },
    {
      id: '6',
      sender_id: 999,
      sender_name: 'Admin Support',
      sender_type: 'admin',
      message: 'Perpanjangan bisa dilakukan melalui aplikasi atau hubungi kami',
      is_read: false,
      created_at: new Date(Date.now() - 300000).toISOString()
    }
  ],
  'staff_1': [
    {
      id: '7',
      sender_id: 3,
      sender_name: 'Ahmad Wijaya',
      sender_type: 'staff',
      message: 'Butuh guidance untuk setup equipment baru',
      is_read: true,
      created_at: new Date(Date.now() - 7200000).toISOString()
    },
    {
      id: '8',
      sender_id: 999,
      sender_name: 'Admin Support',
      sender_type: 'admin',
      message: 'Baik, ada guide di dokumen setup-equipment.pdf',
      is_read: true,
      created_at: new Date(Date.now() - 6900000).toISOString()
    }
  ],
  'staff_2': [
    {
      id: '9',
      sender_id: 4,
      sender_name: 'Dewi Kusuma',
      sender_type: 'staff',
      message: 'Permission untuk mengupdate status equipment?',
      is_read: false,
      created_at: new Date(Date.now() - 1800000).toISOString()
    }
  ]
}

const mockCustomers: ChatUser[] = [
  {
    id: 1,
    username: 'budi_santoso',
    nama_lengkap: 'Budi Santoso',
    type: 'customer',
    unread_count: 0,
    last_message: 'Apakah alat excavator tersedia minggu depan?',
    last_message_time: new Date(Date.now() - 3000000).toISOString()
  },
  {
    id: 2,
    username: 'siti_nur',
    nama_lengkap: 'Siti Nurhaliza',
    type: 'customer',
    unread_count: 2,
    last_message: 'Bagaimana cara perpanjangan peminjaman?',
    last_message_time: new Date(Date.now() - 600000).toISOString()
  }
]

const mockStaff: ChatUser[] = [
  {
    id: 3,
    username: 'ahmad_wijaya',
    nama_lengkap: 'Ahmad Wijaya',
    type: 'staff',
    unread_count: 0,
    last_message: 'Butuh guidance untuk setup equipment baru',
    last_message_time: new Date(Date.now() - 7200000).toISOString()
  },
  {
    id: 4,
    username: 'dewi_kusuma',
    nama_lengkap: 'Dewi Kusuma',
    type: 'staff',
    unread_count: 1,
    last_message: 'Permission untuk mengupdate status equipment?',
    last_message_time: new Date(Date.now() - 1800000).toISOString()
  }
]

export class ChatService {
  // Get all customer chat users
  static getCustomers(): ChatUser[] {
    return [...mockCustomers].sort((a, b) => 
      new Date(b.last_message_time || 0).getTime() - new Date(a.last_message_time || 0).getTime()
    )
  }

  // Get all staff chat users
  static getStaff(): ChatUser[] {
    return [...mockStaff].sort((a, b) => 
      new Date(b.last_message_time || 0).getTime() - new Date(a.last_message_time || 0).getTime()
    )
  }

  // Get chat messages for specific user
  static getMessages(userId: number, type: 'customer' | 'staff'): ChatMessage[] {
    const key = `${type}_${userId}`
    return mockChats[key] || []
  }

  // Send message from user
  static sendMessage(
    senderId: number,
    senderName: string,
    senderType: 'customer' | 'staff' | 'admin',
    message: string,
    toUserId: number
  ): ChatMessage {
    const toType = senderType === 'admin' ? 'customer' : 'staff'
    const key = `${toType}_${toUserId}`
    
    if (!mockChats[key]) {
      mockChats[key] = []
    }

    const newMessage: ChatMessage = {
      id: Date.now().toString(),
      sender_id: senderId,
      sender_name: senderName,
      sender_type: senderType,
      message,
      is_read: false,
      created_at: new Date().toISOString()
    }

    mockChats[key].push(newMessage)
    
    // Update last message in user list
    if (senderType === 'admin') {
      const userList = toType === 'customer' ? mockCustomers : mockStaff
      const user = userList.find(u => u.id === toUserId)
      if (user) {
        user.last_message = message
        user.last_message_time = newMessage.created_at
      }
    }

    return newMessage
  }

  // Mark messages as read
  static markAsRead(userId: number, type: 'customer' | 'staff'): void {
    const key = `${type}_${userId}`
    if (mockChats[key]) {
      mockChats[key].forEach(msg => {
        if (msg.sender_type !== 'admin') {
          msg.is_read = true
        }
      })
    }

    // Update user unread count
    const userList = type === 'customer' ? mockCustomers : mockStaff
    const user = userList.find(u => u.id === userId)
    if (user) {
      user.unread_count = 0
    }
  }

  // Get total unread messages for admin
  static getUnreadCount(): number {
    let count = 0
    mockCustomers.forEach(user => {
      count += user.unread_count
    })
    mockStaff.forEach(user => {
      count += user.unread_count
    })
    return count
  }

  // Send message from customer/staff
  static sendUserMessage(
    senderId: number,
    senderName: string,
    senderType: 'customer' | 'staff',
    message: string
  ): ChatMessage {
    const key = `${senderType}_${senderId}`
    
    if (!mockChats[key]) {
      mockChats[key] = []
    }

    const newMessage: ChatMessage = {
      id: Date.now().toString(),
      sender_id: senderId,
      sender_name: senderName,
      sender_type: senderType,
      message,
      is_read: false,
      created_at: new Date().toISOString()
    }

    mockChats[key].push(newMessage)

    // Update unread count
    const userList = senderType === 'customer' ? mockCustomers : mockStaff
    const user = userList.find(u => u.id === senderId)
    if (user) {
      user.unread_count = (mockChats[key] || []).filter(m => !m.is_read && m.sender_type !== 'admin').length
    }

    return newMessage
  }

  // Get latest unread for specific user
  static hasUnreadMessages(userId: number, type: 'customer' | 'staff'): boolean {
    const userList = type === 'customer' ? mockCustomers : mockStaff
    const user = userList.find(u => u.id === userId)
    return user ? user.unread_count > 0 : false
  }
}
