// Main JavaScript file untuk aplikasi Peminjaman Alat

console.log('Aplikasi Peminjaman Alat telah dimuat');

// Fungsi untuk mengambil data alat
async function fetchData(endpoint) {
    try {
        const response = await fetch(endpoint);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

// Fungsi untuk menampilkan daftar alat
function displayAlat(alat) {
    console.log('Menampilkan data alat:', alat);
    // TODO: Implementasi logika menampilkan data alat ke halaman
}

// Event listener untuk DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM sepenuhnya dimuat');
    // TODO: Tambahkan event listeners yang diperlukan
});
