// Alamat API di hosting (tanpa garis miring di akhir)
const BASE_URL = 'https://archdev.my.id';

// Mengamankan teks dari database sebelum dimasukkan ke HTML (mencegah XSS)
function escapeHTML(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

// Notifikasi visual untuk operasi berhasil maupun gagal
function tampilNotif(pesan, jenis = 'sukses') {
    let kotak = document.getElementById('notif');
    if (!kotak) {
        kotak = document.createElement('div');
        kotak.id = 'notif';
        document.body.prepend(kotak);
    }
    kotak.className = 'notif ' + jenis; // 'sukses' atau 'gagal'
    kotak.textContent = pesan;
    kotak.hidden = false;

    clearTimeout(tampilNotif.timer);
    tampilNotif.timer = setTimeout(() => { kotak.hidden = true; }, 4000);
}

function pesanError(hasil, status) {
    let teks = `[${status}] ${hasil.message}`;
    if (hasil.errors && !Array.isArray(hasil.errors)) {
        teks += ' - ' + Object.values(hasil.errors).join(', ');
    }
    return teks;
}

// ACARA 29 - Tugas mandiri 2: retry otomatis SATU kali.
async function fetchRetry(url, opsi = {}, boleh = null) {
    const method = (opsi.method || 'GET').toUpperCase();
    const bolehUlang = boleh !== null ? boleh : (method === 'GET' || method === 'HEAD');

    try {
        return await fetch(url, opsi);
    } catch (error) {
        if (!bolehUlang) {
            throw error;
        }
        await new Promise((r) => setTimeout(r, 800)); // jeda sebentar
        return await fetch(url, opsi);                // percobaan kedua (terakhir)
    }
}

// ACARA 29 - Langkah 3: pola try-catch-finally untuk tombol.
async function denganLoading(tombol, teksLoading, fungsi) {
    const teksAsli = tombol.textContent;
    tombol.disabled = true;
    tombol.textContent = teksLoading;
    try {
        await fungsi();
    } catch (error) {
        tampilNotif('Terjadi kesalahan: ' + error.message, 'gagal');
    } finally {
        tombol.disabled = false;
        tombol.textContent = teksAsli;
    }
}

// Pemanggil API ber-token. Mendukung JSON maupun FormData.
// Mengembalikan hasil JSON jika berhasil, atau null jika gagal (notifikasi sudah ditampilkan).
async function panggilApi(path, opsi = {}) {
    const headers = { 'Authorization': `Bearer ${localStorage.getItem('token')}` };

    // FormData: biarkan browser mengatur Content-Type (beserta boundary)
    if (!(opsi.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }

    try {
        const res = await fetchRetry(`${BASE_URL}${path}`, { ...opsi, headers });
        const hasil = await res.json();

        if (res.status === 401) {
            localStorage.removeItem('token');
            localStorage.removeItem('role');
            window.location.href = 'login.html';
            return null;
        }
        if (!res.ok) {
            tampilNotif(pesanError(hasil, res.status), 'gagal');
            return null;
        }
        return hasil;
    } catch (error) {
        tampilNotif('Tidak dapat terhubung ke server', 'gagal');
        return null;
    }
}

// Halaman form hanya untuk admin yang sudah login
function wajibAdmin() {
    if (!localStorage.getItem('token')) {
        window.location.href = 'login.html';
        return false;
    }
    if (localStorage.getItem('role') !== 'admin') {
        window.location.href = 'dashboard.html';
        return false;
    }
    return true;
}