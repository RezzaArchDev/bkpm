document.addEventListener('DOMContentLoaded', () => {
    if (!wajibAdmin()) {
        return;
    }

    document.getElementById('form-tambah').addEventListener('submit', (e) => {
        e.preventDefault();

        // Tombol nonaktif selama data dikirim, aktif lagi di blok finally
        denganLoading(e.submitter, 'Menyimpan...', async () => {
            const nim = document.getElementById('nim').value.trim();
            const nama = document.getElementById('nama').value.trim();
            const prodi = document.getElementById('prodi').value.trim();
            const foto = document.getElementById('foto').files[0];

            // Validasi sisi View sebelum data dikirim ke Controller
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

            const hasil = await panggilApi('/mahasiswa', { method: 'POST', body: formData });

            if (hasil) {
                tampilNotif(hasil.message, 'sukses');
                setTimeout(() => { window.location.href = 'dashboard.html'; }, 1200);
            }
        });
    });
});