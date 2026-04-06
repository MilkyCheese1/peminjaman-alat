<template>
  <div class="help-container">
    <div class="help-header">
      <h2>❓ Pusat Bantuan</h2>
      <p>Pelajari cara menggunakan sistem peminjaman alat</p>
    </div>

    <div class="faq-section">
      <h3>Pertanyaan yang Sering Diajukan</h3>
      
      <details class="faq-item" v-for="(faq, index) in faqs" :key="index">
        <summary>{{ faq.question }}</summary>
        <div class="faq-answer" v-html="faq.answer"></div>
      </details>
    </div>

    <div class="support-section">
      <h3>Hubungi Kami</h3>
      <div class="support-channels">
        <div class="channel">
          <div class="icon">📧</div>
          <h4>Email</h4>
          <p>support@trustequip.local</p>
        </div>
        <div class="channel">
          <div class="icon">📞</div>
          <h4>Telepon</h4>
          <p>(021) 1234-5678</p>
        </div>
        <div class="channel">
          <div class="icon">💬</div>
          <h4>Chat</h4>
          <p>Tersedia Senin-Jumat 09:00-17:00</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const faqs = ref([
  {
    question: 'Bagaimana cara meminjam alat?',
    answer: 'Untuk meminjam alat, silakan masuk ke dashboard, pilih tab <strong>Jelajahi Alat</strong>, pilih alat yang ingin dipinjam, lalu isi form dengan tanggal peminjaman dan perkiraan tanggal kembali. Peminjaman Anda akan ditinjau oleh staff sebelum disetujui.'
  },
  {
    question: 'Berapa lama saya bisa meminjam alat?',
    answer: 'Durasi peminjaman standar adalah <strong>3-7 hari</strong> tergantung jenis alat. Anda dapat memperpanjang peminjaman melalui dashboard jika alat tersebut masih tersedia dan tidak ada peminjam lain yang menunggu.'
  },
  {
    question: 'Berapa biaya denda jika saya terlambat?',
    answer: 'Biaya denda adalah <strong>Rp 50.000 per hari</strong> dengan maksimal denda 30 hari. Harap kembalikan alat tepat waktu untuk menghindari biaya tambahan.'
  },
  {
    question: 'Bagaimana jika alat rusak saat saya gunakan?',
    answer: 'Jika alat rusak, segera laporkan ke staff melalui dashboard atau hubungi kami. Anda mungkin akan dikenakan biaya perbaikan tergantung tingkat kerusakan.'
  },
  {
    question: 'Bagaimana proses pengambilan alat yang sudah disetujui?',
    answer: 'Setelah peminjaman disetujui, status akan berubah menjadi <strong>Siap Diambil</strong>. Anda akan menerima kode pickup yang harus ditunjukkan kepada staff saat pengambilan alat.'
  },
  {
    question: 'Bisakah saya membatalkan peminjaman?',
    answer: 'Peminjaman dapat dibatalkan selama status masih <strong>Menunggu Persetujuan</strong>. Setelah disetujui, pembatalan tidak diperbolehkan. Hubungi staff jika ada situasi darurat.'
  },
])
</script>

<style scoped>
.help-container {
  display: flex;
  flex-direction: column;
  gap: 30px;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.help-header {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  padding: 40px;
  border-radius: 12px;
  text-align: center;
}

.help-header h2 {
  margin: 0 0 10px 0;
  font-size: 2rem;
}

.help-header p {
  margin: 0;
  font-size: 1.1rem;
  opacity: 0.9;
}

.faq-section,
.support-section {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.faq-section h3,
.support-section h3 {
  margin-top: 0;
  color: #1a1a2e;
  font-size: 1.3rem;
}

.faq-item {
  margin: 15px 0;
  padding: 0;
}

.faq-item summary {
  padding: 15px;
  background: #f9f9f9;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  color: #1a1a2e;
  user-select: none;
  transition: all 0.3s ease;
  border-left: 4px solid #0b7285;
}

.faq-item summary:hover {
  background: #f0f0f0;
}

.faq-item[open] summary {
  background: #e7f5ff;
  color: #0b7285;
}

.faq-answer {
  padding: 15px;
  color: #666;
  line-height: 1.6;
  border-left: 4px solid #0b7285;
  background: #f9f9f9;
  margin-top: 5px;
}

.support-channels {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.channel {
  background: linear-gradient(135deg, #0b7285 0%, #0c6b7d 100%);
  color: white;
  padding: 25px;
  border-radius: 12px;
  text-align: center;
  transition: all 0.3s ease;
}

.channel:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(11, 114, 133, 0.3);
}

.channel .icon {
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.channel h4 {
  margin: 10px 0;
  font-size: 1.1rem;
}

.channel p {
  margin: 0;
  font-size: 0.95rem;
  opacity: 0.9;
}

@media (max-width: 768px) {
  .help-header {
    padding: 25px;
  }

  .help-header h2 {
    font-size: 1.5rem;
  }

  .faq-section,
  .support-section {
    padding: 20px;
  }

  .support-channels {
    grid-template-columns: 1fr;
  }
}
</style>
