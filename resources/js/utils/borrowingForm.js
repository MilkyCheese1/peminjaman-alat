export function createEmptyBorrowingForm(referenceDate) {
  return {
    namaPeminjam: '',
    divisi: '',
    namaAlat: '',
    kategori: '',
    tanggalPinjam: referenceDate,
    tanggalKembaliRencana: referenceDate,
    tanggalKembaliAktual: '',
    status: 'Pending',
    petugas: 'Staff Operasional',
    keperluan: '',
    biaya: 0,
    catatan: '',
    gambar: '',
  }
}

export function validateBorrowingForm(form) {
  const requiredFields = [
    ['namaPeminjam', 'Nama peminjam'],
    ['divisi', 'Divisi'],
    ['namaAlat', 'Nama alat'],
    ['kategori', 'Kategori'],
    ['tanggalPinjam', 'Tanggal pinjam'],
    ['tanggalKembaliRencana', 'Tanggal kembali rencana'],
    ['status', 'Status'],
    ['petugas', 'Petugas staff'],
    ['keperluan', 'Keperluan'],
  ]

  const missingField = requiredFields.find(([key]) => String(form?.[key] ?? '').trim() === '')

  if (!missingField) {
    return { valid: true, missingLabel: '' }
  }

  return { valid: false, missingLabel: missingField[1] }
}

export function buildBorrowingPayload(form) {
  return {
    namaPeminjam: String(form.namaPeminjam).trim(),
    divisi: String(form.divisi).trim(),
    namaAlat: String(form.namaAlat).trim(),
    kategori: String(form.kategori).trim(),
    tanggalPinjam: form.tanggalPinjam,
    tanggalKembaliRencana: form.tanggalKembaliRencana,
    tanggalKembaliAktual: form.tanggalKembaliAktual,
    status: form.status,
    petugas: String(form.petugas).trim(),
    keperluan: String(form.keperluan).trim(),
    biaya: Number(form.biaya || 0),
    catatan: String(form.catatan || '').trim(),
  }
}
