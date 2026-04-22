import { calculateBorrowingFine } from '../data/returnFine'
import { getTodayLocalISODate } from '../data/staffBorrowing'

function selectedPrice(item) {
  if (!item) {
    return 0
  }

  return Number(item.alatHargaAsli || item.alat_harga_asli || item.hargaAsli || 0)
}

export function resolveReturnStatusBadge(item) {
  const statusPengembalian = item?.statusPengembalian || 'Belum Dikembalikan'
  const condition = String(item?.kondisiPengembalian || '').trim()

  if (statusPengembalian === 'Dikembalikan') {
    if (condition === 'Rusak') {
      return {
        label: 'Dikembalikan Rusak',
        toneClass: 'bg-transparent text-rose-700 dark:text-rose-200',
        iconClass: 'h-2.5 w-2.5 border-l-[5px] border-r-[5px] border-b-[8px] border-l-transparent border-r-transparent border-b-rose-500 drop-shadow-[0_0_8px_rgba(244,63,94,0.55)]',
      }
    }

    if (condition === 'Hilang') {
      return {
        label: 'Hilang',
        toneClass: 'bg-transparent text-rose-700 dark:text-rose-200',
        iconClass: 'h-2.5 w-2.5 rounded-[2px] bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.55)]',
      }
    }

    return {
      label: 'Dikembalikan',
      toneClass: 'bg-transparent text-emerald-700 dark:text-emerald-200',
      iconClass: 'h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.65)]',
    }
  }

  const overduePreview = calculateBorrowingFine({
    price: selectedPrice(item),
    dueDate: item?.tanggalKembaliRencana,
    actualDate: item?.tanggalKembaliAktual || getTodayLocalISODate(),
    statusPengembalian,
    kondisiPengembalian: condition || 'Normal',
  })

  if (overduePreview.daysLate > 0) {
    return {
      label: 'Terlambat',
      toneClass: 'bg-transparent text-rose-700 dark:text-rose-200',
      iconClass: 'h-2.5 w-2.5 rotate-45 rounded-[1px] bg-rose-500 shadow-[0_0_12px_rgba(244,63,94,0.65)]',
    }
  }

  return {
    label: 'Belum Dikembalikan',
    toneClass: 'bg-transparent text-amber-700 dark:text-amber-200',
    iconClass: 'h-2.5 w-4 rounded-[999px] bg-amber-500 shadow-[0_0_12px_rgba(245,158,11,0.65)]',
  }
}
