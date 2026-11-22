const role = localStorage.getItem('role');

async function muatDataMahasiswa() {
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

        const data = hasil.data;
        document.getElementById('total').textContent = data.length;

        if (data.length === 0) {
            status.textContent = 'Belum ada data';
            return;
        }

        status.textContent = '';
        tbody.innerHTML = data.map((m, i) => `
            <tr>
                <td>${i + 1}</td>
                <td>${m.foto
                    ? `<img src="${BASE_URL}/uploads/${escapeHTML(m.foto)}" alt="Foto ${escapeHTML(m.nama)}" width="48" height="48" style="object-fit:cover">`
                    : '-'}</td>
                <td>${escapeHTML(m.nim)}</td>
                <td>${escapeHTML(m.nama)}</td>
                <td>${escapeHTML(m.prodi)}</td>
                <td class="hanya-admin">
                    <a href="form_edit.html?id=${encodeURIComponent(m.id)}">Ubah</a>
                    <button data-aksi="hapus" data-id="${escapeHTML(m.id)}">Hapus</button>
                </td>
            </tr>`).join('');
    } catch (error) {
        status.textContent = 'Gagal memuat data: ' + error.message;
    }
}

async function hapusData(id) {
    if (!confirm('Hapus data ini?')) {
        return;
    }

    // diteruskan Router ke MahasiswaController::destroy($id)
    const hasil = await panggilApi(`/mahasiswa?id=${encodeURIComponent(id)}`, { method: 'DELETE' });

    if (hasil) {
        tampilNotif(hasil.message, 'sukses');
        muatDataMahasiswa();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (!localStorage.getItem('token')) {
        window.location.href = 'login.html';
        return;
    }

    document.getElementById('info-user').textContent = `Login sebagai: ${role}`;

    // Acara 25 tugas 2: sembunyikan tombol pengelolaan untuk role user
    if (role !== 'admin') {
        document.body.classList.add('role-user');
    }

    document.getElementById('btn-logout').addEventListener('click', () => {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        window.location.href = 'login.html';
    });

    document.querySelector('#tabel-mahasiswa tbody').addEventListener('click', (e) => {
        const tombol = e.target.closest('button');
        if (tombol && tombol.dataset.aksi === 'hapus') {
            hapusData(tombol.dataset.id);
        }
    });

    muatDataMahasiswa();
});