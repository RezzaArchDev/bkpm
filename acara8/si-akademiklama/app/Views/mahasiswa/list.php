<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
</head>
<body style="font-family:'Segoe UI',Arial,sans-serif; padding:20px;">
    <h3>Daftar Mahasiswa</h3>

    <a href="<?= $base ?>/mahasiswa/create" style="display:inline-block; margin-bottom:12px; padding:8px 14px; background:#1a1a2e; color:#fff; text-decoration:none; border-radius:6px;">+ Tambah Mahasiswa</a>

    <!-- ===== TUGAS MANDIRI (ACARA 8): form pencarian ===== -->
    <form method="GET" action="<?= $base ?>/mahasiswa" style="margin-bottom:16px;">
        <input type="text" name="q" placeholder="Cari nama atau NIM..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" style="padding:8px; width:250px;">
        <button type="submit" style="padding:8px 14px;">Cari</button>
        <?php if (!empty($_GET['q'])): ?>
            <a href="<?= $base ?>/mahasiswa" style="margin-left:8px;">Reset</a>
        <?php endif; ?>
    </form>
    <!-- ===== AKHIR TUGAS MANDIRI (ACARA 8) ===== -->

    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%;">
        <thead style="background:#1a1a2e; color:#fff;">
            <tr>
                <th>NIM</th><th>Nama</th><th>Email</th><th>Prodi</th><th>Angkatan</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarMahasiswa)): ?>
                <tr><td colspan="6" style="text-align:center;">Tidak ada data</td></tr>
            <?php endif; ?>
            <?php foreach ($daftarMahasiswa as $mhs): ?>
                <tr>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama']) ?></td>
                    <td><?= htmlspecialchars($mhs['email']) ?></td>
                    <td><?= htmlspecialchars($mhs['prodi_nama']) ?></td>
                    <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
                    <td>
                        <a href="<?= $base ?>/mahasiswa/<?= $mhs['id'] ?>/edit">Edit</a> |
                        <!-- ===== STUDI KASUS (ACARA 8) Langkah 6: konfirmasi JS sebelum hapus ===== -->
                        <form method="POST" action="<?= $base ?>/mahasiswa/<?= $mhs['id'] ?>/delete"
                                style="display:inline;"
                                onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?');">
                            <button type="submit" style="background:none; border:none; color:red; cursor:pointer; padding:0;">Hapus</button>
                        </form>
                        <!-- ===== AKHIR STUDI KASUS (ACARA 8) ===== -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
