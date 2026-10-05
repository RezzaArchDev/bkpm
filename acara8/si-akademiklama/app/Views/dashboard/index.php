<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background:#f4f6f9; margin:0; padding:40px 20px; }
        .box { max-width:700px; margin:0 auto; background:#fff; padding:30px 40px; border-radius:10px;
                box-shadow:0 2px 10px rgba(0,0,0,0.08); }
        h2 { color:#1a1a2e; border-bottom:3px solid #1a1a2e; padding-bottom:8px; }
        a.btn { display:inline-block; margin-top:16px; margin-right:8px; padding:8px 16px;
                background:#1a1a2e; color:#fff; border-radius:6px; text-decoration:none; font-weight:600; }
        a.btn.logout { background:#c0392b; }
        .alert-success { margin-bottom:16px; padding:10px; background:#eafaf1; color:#1e8449;
                    border:1px solid #a9dfbf; border-radius:6px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Dashboard</h2>
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div class="alert-success"><?= htmlspecialchars($_SESSION['flash_message']) ?></div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>
        <p>Halo, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></strong>. Halaman ini hanya
        bisa diakses jika sudah login (dilindungi AuthMiddleware).</p>

        <a class="btn" href="<?= $base ?>/mahasiswa">Mahasiswa</a>
        <a class="btn" href="<?= $base ?>/data-mahasiswa">Data Mahasiswa (Acara 4)</a>
        <a class="btn logout" href="<?= $base ?>/logout">Logout</a>
    </div>
</body>
</html>