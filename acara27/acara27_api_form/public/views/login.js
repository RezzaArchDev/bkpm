// Ganti alamat ini sesuai nama folder proyekmu
const BASE_URL = 'http://localhost/bkpm/acara27/acara27_api_form/public';

document.getElementById('form-login').addEventListener('submit', async (e) => {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    try {
        const res = await fetch(`${BASE_URL}/login`, {
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
    }
});