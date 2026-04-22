export function getLocalDateParts(value) {
  if (!value) {
    return null
  }

  const date = new Date(String(value).includes('T') ? value : `${value}T00:00:00`)
  if (Number.isNaN(date.getTime())) {
    return null
  }

  return date
}

export function calculateLateDays(dueDate, actualDate) {
  const due = getLocalDateParts(dueDate)
  const actual = getLocalDateParts(actualDate)

  if (!due || !actual) {
    return 0
  }

  const diff = Math.floor((actual.getTime() - due.getTime()) / (24 * 60 * 60 * 1000))
  return Math.max(0, diff)
}

export function calculateBorrowingFine({
  price = 0,
  dueDate = null,
  actualDate = null,
  statusPengembalian = 'Belum Dikembalikan',
  kondisiPengembalian = 'Normal',
} = {}) {
  const unitPrice = Math.max(0, Number(price || 0))
  const daysLate = calculateLateDays(dueDate, actualDate)
  const normalizedReturnStatus = String(statusPengembalian || '').trim()
  const normalizedCondition = String(kondisiPengembalian || '').trim()

  const dendaKerusakan = normalizedReturnStatus === 'Dikembalikan' && normalizedCondition === 'Rusak'
    ? unitPrice
    : 0
  const dendaKehilangan = normalizedReturnStatus === 'Dikembalikan' && normalizedCondition === 'Hilang'
    ? Math.round(unitPrice * 1.5)
    : 0

  let dendaKeterlambatan = 0
  if (daysLate > 0) {
    const baseDaily = Math.round(unitPrice * 0.5)
    const heavyDaily = Math.round(unitPrice)

    if (daysLate <= 2) {
      dendaKeterlambatan = baseDaily * daysLate
    } else {
      dendaKeterlambatan = (baseDaily * 2) + (heavyDaily * (daysLate - 2))
    }
  }

  return {
    daysLate,
    dendaKerusakan,
    dendaKehilangan,
    dendaKeterlambatan,
    total: dendaKerusakan + dendaKehilangan + dendaKeterlambatan,
  }
}
