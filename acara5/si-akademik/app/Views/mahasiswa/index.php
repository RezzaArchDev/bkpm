<h5 class="mb-3">Daftar Mahasiswa</h5>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Angkatan</th>
            <th>Label</th>
            <th>Aksi</th> <!-- ===== TUGAS MANDIRI ===== -->
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
                <!-- ===== TUGAS MANDIRI ===== -->
                <td><a href="/acara5/si-akademik/public/mahasiswa/<?= $i + 1 ?>" class="btn btn-sm btn-info">Detail</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>