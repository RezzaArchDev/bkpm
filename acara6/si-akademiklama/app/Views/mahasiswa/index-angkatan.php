<h3>Daftar Mahasiswa (Tugas Mandiri - dengan Angkatan)</h3>

<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead style="background:#1a1a2e; color:#fff;">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Angkatan</th>
            <th>Label</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($daftarMahasiswa as $i => $mhs): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                <td><?= htmlspecialchars($mhs->getNama()) ?></td>
                <td><?= htmlspecialchars($mhs->getProdi()) ?></td>
                <td><?= htmlspecialchars($mhs->getAngkatan()) ?></td>
                <td><?= htmlspecialchars($mhs->getLabel()) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
