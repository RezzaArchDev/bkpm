<h5 class="mb-3">Daftar Mahasiswa</h5>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Program Studi</th>
            <th>Angkatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($daftarMahasiswa as $i => $mhs): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($mhs['nim']) ?></td>
                <td><?= htmlspecialchars($mhs['nama']) ?></td>
                <td><?= htmlspecialchars($mhs['email']) ?></td>
                <td><?= htmlspecialchars($mhs['prodi']) ?></td>
                <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
                <?php $warna = ['aktif' => 'success', 'cuti' => 'warning', 'lulus' => 'primary'][$mhs['status']] ?? 'secondary'; ?>
                <td><span class="badge bg-<?= $warna ?>"><?= htmlspecialchars($mhs['status']) ?></span></td>
                <td><a href="/bkpm/acara7/si-akademik/public/mahasiswa/<?= $mhs['id'] ?>" class="btn btn-sm btn-info">Detail</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>