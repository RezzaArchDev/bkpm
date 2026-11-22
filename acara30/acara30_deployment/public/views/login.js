// Halaman login. File ini berdiri sendiri, tidak memakai common.js.
const BASE_URL = 'https://archdev.my.id';

// Retry otomatis satu kali bila terjadi kegagalan jaringan.
// Login tidak membuat data baru, jadi aman diulang.
async function fetchRetry(url, opsi = {}) {
    try {
        return await fetch(url, opsi);
    } catch (error) {
        await new Promise((r) => setTimeout(r, 800));
        return await fetch(url, opsi);
    }
}

document.getElementById('form-login').addEventListener('submit', async (e) => {
    e.preventDefault();

    const tombol = e.submitter || e.target.querySelector('button');
    const teksAsli = tombol.textContent;
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Tombol dinonaktifkan selama proses login (try-catch-finally)
    tombol.disabled = true;
    tombol.textContent = 'Memproses...';

    try {
        const res = await fetchRetry(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password }),
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            window.location.href = 'dashboard.html';
        } else {
            alert(hasil.message);
        }
    } catch (error) {
        alert('Tidak dapat terhubung ke server');
    } finally {
        tombol.disabled = false;
        tombol.textContent = teksAsli;
    }
});