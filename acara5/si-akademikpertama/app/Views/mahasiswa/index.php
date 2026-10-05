<h5 class="mb-3">Daftar Mahasiswa</h5>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Angkatan</th> <!-- ===== TUGAS MANDIRI ===== -->
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
                <td><?= htmlspecialchars($mhs->getAngkatan()) ?></td> <!-- ===== TUGAS MANDIRI ===== -->
                <td><?= htmlspecialchars($mhs->getLabel()) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>