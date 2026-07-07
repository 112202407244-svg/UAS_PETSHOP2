<?php require_once BASE_PATH . '/app/views/layouts/header.php'; ?>

<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; background:#f5f5f5;">
  <div style="background:#fff; padding:36px 32px; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,0.1); width:100%; max-width:400px;">

    <h2 style="text-align:center; margin-bottom:24px; color:#333;">Login Pet Shop</h2>

    <?php if (!empty($error)): ?>
      <div style="background:#ffe0e0; color:#c0392b; padding:10px 14px; border-radius:8px; margin-bottom:16px; font-size:14px;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>index.php?url=auth/login">
      <div style="margin-bottom:16px;">
        <label style="display:block; font-size:13px; color:#555; margin-bottom:6px;">Email</label>
        <input type="email" name="email" required
          style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; box-sizing:border-box;"
          placeholder="contoh@email.com">
      </div>
      <div style="margin-bottom:20px;">
        <label style="display:block; font-size:13px; color:#555; margin-bottom:6px;">Password</label>
        <input type="password" name="password" required
          style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; box-sizing:border-box;"
          placeholder="••••••••">
      </div>
      <button type="submit"
        style="width:100%; padding:12px; background:#e67e22; color:#fff; border:none; border-radius:8px; font-size:15px; font-weight:600; cursor:pointer;">
        Masuk
      </button>
    </form>

    <p style="text-align:center; margin-top:16px; font-size:13px; color:#777;">
      Belum punya akun?
      <a href="<?= BASE_URL ?>index.php?url=auth/register" style="color:#e67e22; text-decoration:none; font-weight:500;">Daftar di sini</a>
    </p>

  </div>
</div>

<?php require_once BASE_PATH . '/app/views/layouts/footer.php'; ?>
