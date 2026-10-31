// Ganti alamat ini sesuai nama folder proyekmu
const BASE_URL = 'http://localhost/bkpm/acara25/acara25_api_admin/public';

const token = localStorage.getItem('token');
const role = localStorage.getItem('role');

let dataMahasiswa = [];
let idEdit = null;

function esc(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

function pesanError(hasil, status) {
    let teks = `[${status}] ${hasil.message}`;
    if (hasil.errors && !Array.isArray(hasil.errors)) {
        teks += '\n' + Object.values(hasil.errors).join('\n');
    }
    return teks;
}

// Pemanggil API ber-token untuk operasi tulis (POST, PUT, DELETE)
async function panggilApi(path, opsi = {}) {
    const res = await fetch(`${BASE_URL}${path}`, {
        ...opsi,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
    });
    const hasil = await res.json();

    if (res.status === 401) {
        alert(hasil.message + '. Silakan login kembali.');
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        window.location.href = 'login.html';
        return null;
    }
    if (!res.ok) {
        alert(pesanError(hasil, res.status)); // contoh: 403 dari Auth::requireAdmin()
        return null;
    }
    return hasil;
}

// Langkah 5: ringkasan data (jumlah total) memakai MahasiswaController::index()
async function muatData() {
    const status = document.getElementById('status');
    const tbody = document.querySelector('#tabel-mahasiswa tbody');

    status.textContent = 'Memuat data...';
    tbody.innerHTML = '';

    try {
        const res = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await res.json();

        if (!hasil.success) {
            throw new Error(hasil.message);
        }

        dataMahasiswa = hasil.data;
        document.getElementById('total').textContent = dataMahasiswa.length;

        if (dataMahasiswa.length === 0) {
            status.textContent = 'Belum ada data mahasiswa.';
            return;
        }

        status.textContent = '';
        tbody.innerHTML = dataMahasiswa.map((m, i) => `
            <tr>
                <td>${i + 1}</td>
                <td>${esc(m.nim)}</td>
                <td>${esc(m.nama)}</td>
                <td>${esc(m.prodi)}</td>
                <td class="hanya-admin">
                    <button data-aksi="ubah" data-id="${esc(m.id)}">Ubah</button>
                    <button data-aksi="hapus" data-id="${esc(m.id)}">Hapus</button>
                </td>
            </tr>`).join('');
    } catch (error) {
        status.textContent = 'Gagal memuat data: ' + error.message;
    }
}

function resetForm() {
    idEdit = null;
    document.getElementById('nim').value = '';
    document.getElementById('nama').value = '';
    document.getElementById('prodi').value = '';
    document.getElementById('judul-form').textContent = 'Tambah Mahasiswa';
    document.getElementById('btn-simpan').textContent = 'Tambah';
    document.getElementById('btn-batal').hidden = true;
}

function isiForm(m) {
    idEdit = m.id;
    document.getElementById('nim').value = m.nim;
    document.getElementById('nama').value = m.nama;
    document.getElementById('prodi').value = m.prodi;
    document.getElementById('judul-form').textContent = 'Ubah Mahasiswa';
    document.getElementById('btn-simpan').textContent = 'Simpan Perubahan';
    document.getElementById('btn-batal').hidden = false;
}

async function simpan() {
    const body = JSON.stringify({
        nim: document.getElementById('nim').value,
        nama: document.getElementById('nama').value,
        prodi: document.getElementById('prodi').value,
    });

    const sedangUbah = idEdit !== null;
    const hasil = await panggilApi(
        sedangUbah ? `/mahasiswa?id=${idEdit}` : '/mahasiswa',
        { method: sedangUbah ? 'PUT' : 'POST', body }
    );

    if (hasil) {
        alert(hasil.message);
        resetForm();
        muatData();
    }
}

async function hapus(id) {
    if (!confirm('Hapus data mahasiswa ini?')) {
        return;
    }
    const hasil = await panggilApi(`/mahasiswa?id=${id}`, { method: 'DELETE' });
    if (hasil) {
        alert(hasil.message);
        muatData();
    }
}

// Tugas 2: sembunyikan tombol Tambah/Ubah/Hapus untuk role user.
// Ini hanya pelengkap tampilan, proteksi sebenarnya tetap Auth::requireAdmin() di backend.
function terapkanRole() {
    if (role !== 'admin') {
        document.body.classList.add('role-user');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Langkah 4: lindungi halaman dashboard
    if (!token) {
        window.location.href = 'login.html';
        return;
    }

    document.getElementById('info-user').textContent = `Login sebagai: ${role}`;

    terapkanRole(); // TUGAS 2: beri komentar (//) pada baris ini saat menguji Langkah 6 poin 1

    document.getElementById('btn-simpan').addEventListener('click', simpan);
    document.getElementById('btn-batal').addEventListener('click', resetForm);

    // Tugas 1: tombol Logout
    document.getElementById('btn-logout').addEventListener('click', () => {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        window.location.href = 'login.html';
    });

    document.querySelector('#tabel-mahasiswa tbody').addEventListener('click', (e) => {
        const tombol = e.target.closest('button');
        if (!tombol) {
            return;
        }
        if (tombol.dataset.aksi === 'ubah') {
            const m = dataMahasiswa.find((x) => String(x.id) === tombol.dataset.id);
            if (m) {
                isiForm(m);
            }
        } else if (tombol.dataset.aksi === 'hapus') {
            hapus(tombol.dataset.id);
        }
    });

    muatData();
});