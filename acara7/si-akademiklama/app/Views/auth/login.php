<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background:#f4f6f9; display:flex;
                align-items:center; justify-content:center; height:100vh; margin:0; }
        .box { background:#fff; padding:30px 40px; border-radius:10px; width:100%; max-width:360px;
                box-shadow:0 2px 10px rgba(0,0,0,0.08); }
        h2 { color:#1a1a2e; border-bottom:3px solid #1a1a2e; padding-bottom:8px; }
        label { display:block; margin-top:12px; font-weight:600; }
        input { width:100%; padding:8px; margin-top:4px; border:1px solid #ccc; border-radius:6px; }
        button { width:100%; margin-top:18px; padding:10px; background:#1a1a2e; color:#fff;
                        border:none; border-radius:6px; font-weight:600; cursor:pointer; }
        .alert-error { margin-top:14px; padding:10px; background:#fdecea; color:#c0392b;
                        border:1px solid #f5b7b1; border-radius:6px; }
        .alert-info { margin-top:14px; padding:10px; background:#eaf2f8; color:#1a1a2e;
                border:1px solid #aed6f1; border-radius:6px; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Login</h2>

        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div class="alert-info"><?= htmlspecialchars($_SESSION['flash_message']) ?></div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= $base ?>/login">
            <label>Username</label>
            <input type="text" name="username" required autofocus>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Masuk</button>
        </form>
        <p style="color:#888; font-size:13px; margin-top:10px;">Hint: admin / admin123</p>
    </div>
</body>
</html>