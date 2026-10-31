// Ganti alamat ini sesuai nama folder proyekmu
const BASE_URL = 'http://localhost/bkpm/acara25/acara25_api_admin/public';

// Mengamankan teks dari database sebelum dimasukkan ke HTML (mencegah XSS)
function esc(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

async function muatDataMahasiswa() {
    const status = document.getElementById('status');
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    // Tugas 1: indikator sebelum data berhasil dimuat
    status.textContent = 'Memuat data...';
    tbody.innerHTML = '';

    try {
        // await new Promise((r) => setTimeout(r, 2000)); // UJI: aktifkan sementara untuk screenshot "Memuat data..."

        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        if (!hasil.success) {
            throw new Error(hasil.message);
        }

        const data = hasil.data;
        // data.length = 0; // UJI: aktifkan sementara untuk screenshot kondisi data kosong

        // Tugas 2: penanganan data kosong
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
        status.textContent = 'Gagal memuat data: ' + error.message;
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);