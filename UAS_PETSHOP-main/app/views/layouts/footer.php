<?php
$appName = defined('APP_NAME') ? APP_NAME : 'Pet Shop';
?>
            </div>
        </main>
    </div>

    <footer class="site-footer">
        <div class="container footer-inner">
            <div>
                <strong><?= htmlspecialchars($appName) ?></strong>
                <p>Aplikasi pet shop berbasis PHP Native MVC dengan fitur keranjang dan checkout.</p>
            </div>
            <div class="footer-meta">
                &copy; <?= date('Y') ?> <?= htmlspecialchars($appName) ?>
            </div>
        </div>
    </footer>

    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>
