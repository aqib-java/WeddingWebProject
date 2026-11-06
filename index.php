<?php include 'Header.php'; ?>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <!-- Background Image Image-nya bisa diletakkan di CSS atau inline style -->
        <div class="hero-content">
            <h3 class="hero-title">
                Pernikahan, Terinspirasi<br>by Nature
            </h3>
            <p class="hero-description">
                Lanskap unik yang menangkap keajaiban<br>cinta abadi. Untuk upacara tak terlupakan.
            </p>
        </div>
    </section>

    <!-- EVENTS SECTION -->
    <section class="events-section" id="events">
        <div class="events-container">
            
            <!-- Kolom Kiri -->
            <div class="events-left">
                <div class="events-image-small-wrapper">
                    <!-- Pastikan path gambarnya benar ke folder Images lu -->
                    <img src="Images/BungaKecil.jpg" alt="Dekorasi bunga minimalis" class="events-image-small">
                </div>
                <div class="events-text-content">
                    <span class="section-subtitle">Acara Kami</span>
                    <h2 class="section-title">
                        Dirancang oleh alam,<br>
                        dibuat khusus untuk Anda.
                    </h2>
                    <a href='Contact.php' class="btn-outline">Jadwalkan Pertemuan</a>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="events-right">
                <!-- Pastikan path gambarnya benar -->
                <img src="Images/MejaWedding.jpg" alt="Meja perjamuan makan malam" class="events-image-large">
            </div>

        </div>
    </section>

    <!-- WEDDING OPTIONS SECTION -->
    <section class="options-section" id="wedding-options">
        <div class="options-container">
            
            <!-- Header Options -->
            <div class="options-header">
                <span class="section-subtitle">Pilihan Pernikahan</span>
                <h3 class="options-logo-text">Ever After</h3>
                <h2 class="section-title">Semua diundang dengan hangat.</h2>
                <p class="section-paragraph">
                    Pilih suasana yang paling sesuai dengan impian Anda. Kami menyediakan berbagai pilihan area untuk merayakan kisah cinta Anda bersama keluarga dan sahabat terdekat.
                </p>
            </div>

            <!-- Grid 4 Kolom -->
            <div class="options-grid">
                
                <!-- Card 1 -->
                <div class="option-card">
                    <img src="Images/IntimateWedding.jpg" alt="Pernikahan Intim" class="option-image">
                    <h4 class="option-title">Pernikahan Intim</h4>
                    <span class="option-location">Lorong Tanaman Rambat</span>
                    <span class="option-capacity">Hingga 40 Tamu</span>
                    <p class="option-desc">Sempurna untuk perayaan kecil yang hangat dan sangat berkesan.</p>
                </div>

                <!-- Card 2 -->
                <div class="option-card">
                    <img src="Images/MarqueeWedding.jpg" alt="Pernikahan Tenda" class="option-image">
                    <h4 class="option-title">Pernikahan Tenda</h4>
                    <span class="option-location">Lapangan Terbuka</span>
                    <span class="option-capacity">Hingga 200 Tamu</span>
                    <p class="option-desc">Rayakan di bawah kanopi elegan dengan semilir angin alam.</p>
                </div>

                <!-- Card 3 -->
                <div class="option-card">
                    <img src="Images/LargeWedding.jpg" alt="Pernikahan Skala Besar" class="option-image">
                    <h4 class="option-title">Skala Besar</h4>
                    <span class="option-location">Seluruh Area Giardino</span>
                    <span class="option-capacity">Hingga 350 Tamu</span>
                    <p class="option-desc">Pesan seluruh area eksklusif kami untuk pesta termegah Anda.</p>
                </div>

                <!-- Card 4 -->
                <div class="option-card">
                    <img src="Images/HighEndWedding.jpg" alt="Pernikahan Klasik" class="option-image">
                    <h4 class="option-title">Pernikahan Klasik</h4>
                    <span class="option-location">Area Rumah Pohon</span>
                    <span class="option-capacity">Hingga 150 Tamu</span>
                    <p class="option-desc">Rasakan kehangatan dan nuansa magis dikelilingi rimbunnya alam.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- SUITS & DRESS SECTION -->
    <section class="suits-dress-section" id="suits-dress">
        <div class="suits-dress-container">
            
            <!-- Header Section -->
            <div class="sd-header">
                <span class="section-subtitle">Koleksi Busana</span>
                <h2 class="section-title">Keanggunan dalam Setiap Jahitan.</h2>
                <p class="section-paragraph">
                    Sempurnakan hari bahagia Anda dengan koleksi gaun dan setelan jas eksklusif dari desainer rekanan kami. Dirancang khusus untuk memancarkan pesona terbaik Anda.
                </p>
            </div>

            <!-- Content Area (Split Kiri-Kanan) -->
            <div class="sd-content">
                
                <!-- Kiri: DRESS -->
                <div class="sd-column">
                    <div class="sd-image-wrapper">
                        <!-- Siapkan gambar gaun dengan rasio portrait -->
                        <img src="Images/ElegantDress.jpg" alt="Gaun Pengantin Elegan" class="sd-image">
                    </div>
                    <div class="sd-text-block">
                        <h4 class="sd-title">The Dress</h4>
                        <p class="sd-desc">
                            Dari siluet A-line klasik hingga <em>ballgown</em> mewah bertahtakan renda. Temukan gaun impian yang menari bersama angin di hari istimewa Anda.
                        </p>
                        <a href='SuitesNDress.php' class="cta-link">Jelajahi Koleksi Gaun</a>
                    </div>
                </div>

                <!-- Kanan: SUITS -->
                <div class="sd-column">
                    <div class="sd-image-wrapper">
                        <!-- Siapkan gambar jas pria dengan rasio portrait -->
                        <img src="Images/WeddingSuits.jpg" alt="Setelan Jas Pengantin Pria" class="sd-image">
                    </div>
                    <div class="sd-text-block">
                        <h4 class="sd-title">The Suits</h4>
                        <p class="sd-desc">
                            Potongan tegas (<em>tailored fit</em>) dengan material premium. Tampil memukau, maskulin, dan tak lekang oleh waktu saat Anda menunggunya di altar.
                        </p>
                        <a href='SuitesNDress.php' class="cta-link">Lihat Pilihan Setelan</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOD & DRINKS SECTION -->
    <section class="fnb-section" id="food-drinks">
        <div class="fnb-container">
            
            <!-- Kolom Kiri: Gambar dengan Background Pink -->
            <div class="fnb-left">
                <!-- Pastikan menyiapkan gambar persegi (1:1) untuk ini -->
                <img src="Images/fnb.jpg" alt="Bahan makanan organik segar" class="fnb-image">
            </div>

            <!-- Kolom Kanan: Teks dengan Background Off-White -->
            <div class="fnb-right">
                <div class="fnb-text-content">
                    <span class="section-subtitle">Makanan & Minuman</span>
                    <h2 class="section-title">
                        Organik, segar,<br>
                        dan lezat.
                    </h2>
                    <p class="section-paragraph">
                        Manjakan lidah tamu Anda dengan kreasi kuliner istimewa kami. Mulai dari hidangan pembuka yang menggugah selera hingga hidangan utama yang dikurasi dari bahan-bahan organik terbaik di musimnya.
                    </p>
                    <a href='FNB.php' class="btn-outline">Jelajahi Menu</a>
                </div>
            </div>

        </div>
    </section>

    <!-- CONTACT & FOOTER SECTION -->
    <footer class="contact-footer-section" id="contact">
        <div class="cf-container">
            
            <!-- Area Kontak Utama -->
            <div class="cf-main">
                <!-- Ikon Daun (Opsional, pakai teks biasa atau biarkan logo Serif yang dominan) -->
                <h2 class="cf-logo-text">Ever Afrer</h2>
                
                <a href="tel:0214567890" class="cf-phone">021-456-7890</a>
                
                <address class="cf-address">
                    Jl. Kemang Raya No. 12<br>
                    Jakarta Selatan, 12730
                </address>
                
                <a href='Contact.php' class="btn-outline">Jadwalkan Pertemuan</a>
            </div>

            <!-- Area Footer Links -->
            <div class="cf-bottom">
                
                <!-- Baris Navigasi Atas -->
                <div class="cf-nav-row">
                    <ul class="cf-nav-links left">
                        <li><a href="#wedding-options">Pilihan Pernikahan</a></li>
                        <li><a href="#food-drinks">Makanan & Minuman</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#contact">Kontak</a></li>
                    </ul>
                    <ul class="cf-nav-links right">
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                <!-- Baris Copyright & Sosmed -->
                <div class="cf-copy-row">
                    <p class="cf-copyright">©2026 oleh Ever After. Semua Hak Dilindungi.</p>
                    <ul class="cf-social-links">
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Facebook</a></li>
                    </ul>
                </div>
                
            </div>

        </div>
    </footer>

    <!-- Script JS ditaruh paling bawah, SETELAH semua HTML selesai -->
    <script src="js/script.js"></script>

</body>
</html>