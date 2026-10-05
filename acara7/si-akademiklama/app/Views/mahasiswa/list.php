<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa (Database)</title>
</head>
<body style="font-family:'Segoe UI',Arial,sans-serif; padding:20px;">
    <h3>Daftar Mahasiswa (dari Database)</h3>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%;">
        <thead style="background:#1a1a2e; color:#fff;">
            <tr>
                <th>ID</th><th>NIM</th><th>Nama</th><th>Email</th><th>Prodi ID</th>
                <th>Angkatan</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftarMahasiswa as $mhs): ?>
                <tr>
                    <td><?= htmlspecialchars($mhs['id']) ?></td>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama']) ?></td>
                    <td><?= htmlspecialchars($mhs['email']) ?></td>
                    <td><?= htmlspecialchars($mhs['prodi_id']) ?></td>
                    <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
                    <td><?= htmlspecialchars($mhs['status'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>