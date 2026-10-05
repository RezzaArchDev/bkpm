<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><title>Tambah Mahasiswa</title></head>
<body style="font-family:'Segoe UI',Arial,sans-serif; padding:20px; max-width:500px;">
    <h3>Tambah Mahasiswa</h3>
    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="<?= $base ?>/mahasiswa">
        <label>NIM</label><br>
        <input type="text" name="nim" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

        <label>Nama</label><br>
        <input type="text" name="nama" required style="width:100%; padding:8px; margin-bottom:10px;"><br>

        <label>Email</label><br>
        <input type="email" name="email" style="width:100%; padding:8px; margin-bottom:10px;"><br>

        <label>Program Studi</label><br>
        <select name="prodi_id" required style="width:100%; padding:8px; margin-bottom:10px;">
            <option value="">-- Pilih Prodi --</option>
            <?php foreach ($daftarProdi as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Angkatan</label><br>
        <input type="number" name="angkatan" value="<?= date('Y') ?>" style="width:100%; padding:8px; margin-bottom:14px;"><br>

        <button type="submit" style="padding:10px 20px; background:#1a1a2e; color:#fff; border:none; border-radius:6px;">Simpan</button>
        <a href="<?= $base ?>/mahasiswa" style="margin-left:10px;">Batal</a>
    </form>
</body>
</html>
