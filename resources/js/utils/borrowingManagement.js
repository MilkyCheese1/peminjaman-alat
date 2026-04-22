export function formatCompactCurrency(value) {
  const amount = Number(value || 0)

  if (amount >= 1000000) {
    return `Rp ${(amount / 1000000).toFixed(1)} jt`
  }

  if (amount >= 1000) {
    return `Rp ${(amount / 1000).toFixed(0)} rb`
  }

  return `Rp ${amount}`
}

export function statusToneClass(status) {
  const map = {
    Pending: 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-200',
    Disetujui: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/15 dark:text-cyan-200',
    Dipinjam: 'bg-violet-100 text-violet-800 dark:bg-violet-500/15 dark:text-violet-200',
    Dikembalikan: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200',
    Selesai: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-200',
    Ditolak: 'bg-rose-100 text-rose-800 dark:bg-rose-500/15 dark:text-rose-200',
  }

  return map[status] ?? 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-100'
}

export function getBorrowingQuickActions(status) {
  const actionsByStatus = {
    Pending: [
      {
        key: 'approve',
        label: 'Approve',
        className: 'border-emerald-200 text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/20 dark:text-emerald-200 dark:hover:bg-emerald-500/10',
      },
      {
        key: 'reject',
        label: 'Tolak',
        className: 'border-rose-200 text-rose-700 hover:bg-rose-50 dark:border-rose-500/20 dark:text-rose-200 dark:hover:bg-rose-500/10',
      },
    ],
    Disetujui: [
      {
        key: 'handover',
        label: 'Serahkan Alat',
        className: 'border-cyan-200 text-cyan-700 hover:bg-cyan-50 dark:border-cyan-500/20 dark:text-cyan-200 dark:hover:bg-cyan-500/10',
      },
    ],
    Dipinjam: [
      {
        key: 'return',
        label: 'Konfirmasi Kembali',
        className: 'border-amber-200 text-amber-700 hover:bg-amber-50 dark:border-amber-500/20 dark:text-amber-200 dark:hover:bg-amber-500/10',
      },
    ],
    Dikembalikan: [
      {
        key: 'complete',
        label: 'Selesaikan',
        className: 'border-emerald-200 text-emerald-700 hover:bg-emerald-50 dark:border-emerald-500/20 dark:text-emerald-200 dark:hover:bg-emerald-500/10',
      },
    ],
  }

  return actionsByStatus[status] ?? []
}

export function buildBorrowingPayloadFromItem(item) {
  return {
    kode: item.kode,
    namaPeminjam: String(item.namaPeminjam).trim(),
    divisi: String(item.divisi).trim(),
    namaAlat: String(item.namaAlat).trim(),
    kategori: String(item.kategori).trim(),
    tanggalPinjam: item.tanggalPinjam,
    tanggalKembaliRencana: item.tanggalKembaliRencana,
    tanggalKembaliAktual: item.tanggalKembaliAktual || null,
    status: item.status,
    petugas: String(item.petugas).trim(),
    keperluan: String(item.keperluan).trim(),
    biaya: Number(item.biaya || 0),
    catatan: String(item.catatan || '').trim(),
    gambar: String(item.gambar || ''),
  }
}

export function resolveBorrowingActionState(action, referenceDate) {
  const actionMap = {
    approve: {
      status: 'Disetujui',
      catatan: 'Permintaan telah disetujui dan siap diserahkan.',
    },
    reject: {
      status: 'Ditolak',
      catatan: 'Permintaan ditolak oleh staff setelah proses verifikasi.',
    },
    handover: {
      status: 'Dipinjam',
      catatan: 'Alat sudah diserahkan ke peminjam.',
    },
    return: {
      status: 'Dikembalikan',
      tanggalKembaliAktual: referenceDate,
      catatan: 'Alat sudah kembali dan menunggu penutupan transaksi.',
    },
    complete: {
      status: 'Selesai',
      catatan: 'Transaksi selesai dan alat telah diperiksa staff.',
    },
  }

  return actionMap[action] ?? null
}
