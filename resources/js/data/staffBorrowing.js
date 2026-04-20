export const staffBorrowingStorageKey = 'staff-management-peminjaman'
export const staffReportReferenceDate = '2026-04-20'

export const staffBorrowingSeed = []

export const staffBorrowingStatusOptions = [
  'Pending',
  'Disetujui',
  'Dipinjam',
  'Dikembalikan',
  'Selesai',
  'Ditolak',
]

export function cloneStaffBorrowing(value) {
  return JSON.parse(JSON.stringify(value))
}

export function getStaffBorrowings() {
  if (typeof window === 'undefined') {
    return []
  }

  const storedValue = window.localStorage.getItem(staffBorrowingStorageKey)

  if (storedValue) {
    try {
      const parsedValue = JSON.parse(storedValue)

      if (Array.isArray(parsedValue)) {
        return cloneStaffBorrowing(parsedValue)
      }
    } catch (error) {
    }

    window.localStorage.removeItem(staffBorrowingStorageKey)
  }

  const fallbackData = []
  saveStaffBorrowings(fallbackData)
  return fallbackData
}

export function saveStaffBorrowings(items) {
  if (typeof window === 'undefined') {
    return
  }

  window.localStorage.setItem(staffBorrowingStorageKey, JSON.stringify(items))
}

export function resetStaffBorrowings() {
  const initialData = []
  saveStaffBorrowings(initialData)
  return initialData
}

export function formatRupiah(value) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(Number(value || 0))
}

export function formatDateIndonesia(value) {
  if (!value) {
    return '-'
  }

  return new Intl.DateTimeFormat('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
  }).format(new Date(value))
}

export function createBorrowingId() {
  return `trx-${Date.now()}-${Math.random().toString(16).slice(2, 8)}`
}

export function createBorrowingCode(items, dateValue = staffReportReferenceDate) {
  const normalizedDate = String(dateValue).replaceAll('-', '')
  const sameDateItems = items.filter((item) => String(item.tanggalPinjam).replaceAll('-', '') === normalizedDate)
  const runningNumber = String(sameDateItems.length + 1).padStart(3, '0')

  return `PMJ-${normalizedDate}-${runningNumber}`
}

export function summarizeBorrowings(items) {
  const totalBiaya = items.reduce((total, item) => total + Number(item.biaya || 0), 0)
  const selesai = items.filter((item) => item.status === 'Selesai').length
  const aktif = items.filter((item) => ['Pending', 'Disetujui', 'Dipinjam', 'Dikembalikan'].includes(item.status)).length

  return {
    totalTransaksi: items.length,
    totalBiaya,
    selesai,
    aktif,
  }
}
