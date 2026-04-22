export function getTodayLocalISODate() {
  const now = new Date()
  const local = new Date(now.getTime() - now.getTimezoneOffset() * 60 * 1000)
  return local.toISOString().slice(0, 10)
}

export const staffReportReferenceDate = getTodayLocalISODate()

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
