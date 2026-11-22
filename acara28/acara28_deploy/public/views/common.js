// Ganti alamat ini sesuai nama folder proyekmu
const BASE_URL = 'https://archdev.my.id';

// Mengamankan teks dari database sebelum dimasukkan ke HTML (mencegah XSS)
function escapeHTML(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

// Tugas 1: notifikasi visual untuk operasi berhasil maupun gagal
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

// Pemanggil API ber-token. Mendukung JSON maupun FormData.
// Mengembalikan hasil JSON jika berhasil, atau null jika gagal (notifikasi sudah ditampilkan).
async function panggilApi(path, opsi = {}) {
    const headers = { 'Authorization': `Bearer ${localStorage.getItem('token')}` };

    // FormData: biarkan browser mengatur Content-Type (beserta boundary)
    if (!(opsi.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }

    try {
        const res = await fetch(`${BASE_URL}${path}`, { ...opsi, headers });
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