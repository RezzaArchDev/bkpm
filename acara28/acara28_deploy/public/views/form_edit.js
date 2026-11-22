const id = new URLSearchParams(window.location.search).get('id');

async function muatDataUntukEdit(id) {
    try {
        const res = await fetch(`${BASE_URL}/mahasiswa?id=${encodeURIComponent(id)}`);
        const hasil = await res.json();

        if (!hasil.success) {
            tampilNotif(hasil.message, 'gagal');
            return;
        }

        document.getElementById('nim').value = hasil.data.nim;
        document.getElementById('nama').value = hasil.data.nama;
        document.getElementById('prodi').value = hasil.data.prodi;

        if (hasil.data.foto) {
            document.getElementById('foto-lama').innerHTML =
                `<img src="${BASE_URL}/uploads/${escapeHTML(hasil.data.foto)}" alt="Foto saat ini" width="96">`;
        }
    } catch (error) {
        tampilNotif('Tidak dapat terhubung ke server', 'gagal');
    }
}

async function simpanPerubahan(id) {
    const nim = document.getElementById('nim').value.trim();
    const nama = document.getElementById('nama').value.trim();
    const prodi = document.getElementById('prodi').value.trim();
    const foto = document.getElementById('foto').files[0];

    // Tugas 2: validasi sisi View sebelum data dikirim ke Controller
    const kosong = [];
    if (nim === '') kosong.push('NIM');
    if (nama === '') kosong.push('Nama');
    if (prodi === '') kosong.push('Program studi');
    if (kosong.length > 0) {
        tampilNotif(kosong.join(', ') + ' wajib diisi', 'gagal');
        return;
    }

    const formData = new FormData();
    formData.append('nim', nim);
    formData.append('nama', nama);
    formData.append('prodi', prodi);
    if (foto) {
        formData.append('foto', foto);
    }

    // PHP tidak mengisi $_POST/$_FILES untuk PUT multipart, jadi dikirim sebagai
    // POST + ?_method=PUT. Router meneruskannya ke MahasiswaController::update($id).
    const hasil = await panggilApi(
        `/mahasiswa?id=${encodeURIComponent(id)}&_method=PUT`,
        { method: 'POST', body: formData }
    );

    if (hasil) {
        tampilNotif(hasil.message, 'sukses');
        setTimeout(() => { window.location.href = 'dashboard.html'; }, 1200);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (!wajibAdmin()) {
        return;
    }
    if (!id) {
        tampilNotif('ID mahasiswa tidak ditemukan pada alamat halaman', 'gagal');
        return;
    }

    muatDataUntukEdit(id);

    document.getElementById('form-edit').addEventListener('submit', (e) => {
        e.preventDefault();
        simpanPerubahan(id);
    });
});