<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <h3><?= strtolower(get_setting('site_name', 'peweka')) ?></h3>
                <p>Culture & The Future. Brand clothing lokal yang menggabungkan budaya dengan gaya modern streetwear.
                    Kualitas premium, desain unik.</p>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?= base_url() ?>">Home</a></li>
                    <li><a href="<?= base_url() ?>#products">Produk</a></li>
                    <li><a href="<?= base_url() ?>#about">Tentang Kami</a></li>
                </ul>
            </div>
            <div class="footer-social">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="<?= get_setting('instagram_url', 'https://instagram.com/peweka.cloth') ?>" target="_blank"
                        class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-link" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy;
                <?= date('Y') ?> <?= get_setting('site_name', 'Peweka') ?>. Culture & The Future. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>

</html>