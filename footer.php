    </main> <!-- End main-content -->

    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a href="index.php" class="logo footer-logo">F&F<span>.</span></a>
                <p class="footer-desc">Curating luxury and elegance. Discover our exclusive collection of premium clothing and signature fragrances.</p>
                <div class="social-icons">
                    <a href="https://instagram.com/arin_ze67" target="_blank" data-tooltip="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/2348074001039" target="_blank" data-tooltip="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="mailto:arinzeudeoba@gmail.com" data-tooltip="Email"><i class="far fa-envelope"></i></a>
                </div>
            </div>
            
            <div class="footer-links">
                <h3>Shop</h3>
                <ul>
                    <li><a href="index.php#men">Men's Collection</a></li>
                    <li><a href="index.php#women">Women's Collection</a></li>
                    <li><a href="index.php#fragrance">Fragrances</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h3>Contact</h3>
                <ul class="contact-list">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>SABURIA NO11, DEI-DEI</span>
                    </li>
                    <li>
                        <i class="fab fa-whatsapp"></i>
                        <a href="https://wa.me/2348074001039" target="_blank">+2348074001039</a>
                    </li>
                    <li>
                        <i class="far fa-envelope"></i>
                        <a href="mailto:arinzeudeoba@gmail.com">arinzeudeoba@gmail.com</a>
                    </li>
                </ul>
            </div>

            <div class="footer-newsletter">
                <h3>Join Our List</h3>
                <p>Subscribe for exclusive offers and updates.</p>
                <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('Subscribed successfully!', 'success'); this.reset();">
                    <input type="email" placeholder="Your email address" required>
                    <button type="submit" aria-label="Subscribe"><i class="fas fa-arrow-right"></i></button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?= date('Y') ?> F&F Fashion and Fragrance. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="back-to-top" aria-label="Back to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script>
        const IS_LOGGED_IN = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>
