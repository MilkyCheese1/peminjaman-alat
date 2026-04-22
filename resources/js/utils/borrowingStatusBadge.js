export function resolveBorrowingStatusBadge(status) {
  const normalized = String(status || '').trim()

  const map = {
    Pending: {
      label: 'Pending',
      toneClass: 'bg-transparent text-amber-700 dark:text-amber-200',
      iconClass: 'h-2.5 w-2.5 rotate-45 rounded-[1px] bg-amber-500 shadow-[0_0_12px_rgba(245,158,11,0.65)]',
    },
    Disetujui: {
      label: 'Disetujui',
      toneClass: 'bg-transparent text-cyan-700 dark:text-cyan-200',
      iconClass: 'h-2 w-4 rounded-[999px] bg-cyan-500 shadow-[0_0_12px_rgba(6,182,212,0.6)]',
    },
    Dipinjam: {
      label: 'Dipinjam',
      toneClass: 'bg-transparent text-violet-700 dark:text-violet-200',
      iconClass: 'h-3 w-3 rounded-full border border-violet-500 bg-transparent shadow-[0_0_0_2px_rgba(139,92,246,0.12),0_0_10px_rgba(139,92,246,0.55)]',
    },
    Dikembalikan: {
      label: 'Dikembalikan',
      toneClass: 'bg-transparent text-emerald-700 dark:text-emerald-200',
      iconClass: 'h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.65)]',
    },
    Selesai: {
      label: 'Selesai',
      toneClass: 'bg-transparent text-emerald-700 dark:text-emerald-200',
      iconClass: 'h-2.5 w-2.5 rounded-[1px] bg-emerald-500 shadow-[0_0_12px_rgba(16,185,129,0.65)]',
    },
    Ditolak: {
      label: 'Ditolak',
      toneClass: 'bg-transparent text-rose-700 dark:text-rose-200',
      iconClass: 'h-2.5 w-1.5 rounded-[1px] bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.55)]',
    },
  }

  return map[normalized] ?? {
    label: normalized || '-',
    toneClass: 'bg-transparent text-slate-600 dark:text-slate-300',
    iconClass: 'h-2.5 w-2.5 rounded-[1px] bg-slate-400 shadow-[0_0_10px_rgba(148,163,184,0.45)]',
  }
}
