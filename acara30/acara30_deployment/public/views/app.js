// Halaman publik (index.html). File ini berdiri sendiri, tidak memakai common.js.
const BASE_URL = 'https://archdev.my.id';

// Mengamankan teks dari database sebelum dimasukkan ke HTML (mencegah XSS)
function esc(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

// Retry otomatis satu kali bila terjadi kegagalan jaringan (request GET aman diulang)
async function fetchRetry(url, opsi = {}) {
    try {
        return await fetch(url, opsi);
    } catch (error) {
        await new Promise((r) => setTimeout(r, 800));
        return await fetch(url, opsi);
    }
}

async function muatDataMahasiswa() {
    const status = document.getElementById('status');
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    // Indikator loading sebelum data berhasil dimuat
    status.textContent = 'Memuat data...';
    tbody.innerHTML = '';

    try {
        const response = await fetchRetry(`${BASE_URL}/mahasiswa`);
        if (!response.ok) {
            throw new Error('Server merespons dengan status ' + response.status);
        }
        const hasil = await response.json();

        if (!hasil.success) {
            throw new Error(hasil.message);
        }

        const data = hasil.data;

        // Penanganan data kosong
        if (data.length === 0) {
            status.textContent = 'Belum ada data mahasiswa.';
            return;
        }

        status.textContent = '';
        tbody.innerHTML = data.map((m, i) => `
            <tr>
                <td>${i + 1}</td>
                <td>${esc(m.nim)}</td>
                <td>${esc(m.nama)}</td>
                <td>${esc(m.prodi)}</td>
            </tr>`).join('');
    } catch (error) {
        // Umpan balik error yang jelas, bukan gagal senyap
        status.textContent = 'Gagal memuat data: ' + error.message;
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);