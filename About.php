<?php include 'header.php'; ?>

<!-- ABOUT PAGE SECTION -->
    <section class="about-page-section">
        
        <!-- Header Section -->
        <div class="about-page-header">
            <span class="ap-subtitle">Tentang</span>
            <h3 class="ap-title">Kami adalah Ever After.</h3>
        </div>

        <!-- Content Split Layout -->
        <div class="ap-content-container">
            
            <!-- Kolom Kiri -->
            <div class="ap-left-column">
                
                <div class="ap-image-small-wrapper">
                    <img src="Images/pengantin.jpg" alt="Pasangan Pengantin" class="ap-image-small">
                </div>
                
                <div class="ap-text-block">
                    <h3 class="ap-subheading">Menyatu dengan alam</h3>
                    <p class="ap-paragraph">
                        Kami percaya bahwa keindahan sejati berasal dari kemurnian alam. Setiap sudut di Ever After dirancang untuk menonjolkan harmoni antara lanskap natural dan arsitektur elegan, menciptakan latar belakang yang sempurna untuk momen tak terlupakan Anda.
                    </p>
                </div>

                <div class="ap-text-block">
                    <h3 class="ap-subheading">Perjalanan kami</h3>
                    <p class="ap-paragraph">
                        Berawal dari kecintaan pada ruang terbuka hijau dan perayaan cinta, kami membangun tempat ini untuk menjadi saksi bisu kebahagiaan banyak pasangan. Dedikasi kami adalah memberikan pengalaman pernikahan yang hangat, intim, dan berkesan selamanya.
                    </p>
                </div>

            </div>

            <!-- Kolom Kanan -->
            <div class="ap-right-column">
                <!-- Gambar gaun besar -->
                <img src="Images/WeddingAmbience.jpg" alt="Gaun pengantin berlatar arsitektur klasik" class="ap-image-large">
            </div>

        </div>
    </section>

    <!-- MOMENTS SLIDER SECTION -->
    <section class="moments-section" id="moments">
        
        <!-- Header -->
        <div class="moments-header">
            <span class="mm-logo">Ever After</span>
            <span class="mm-subtitle">Momen di Ever After</span>
        </div>

        <!-- Slider Container -->
        <div class="slider-container">
            
            <!-- Tombol Kiri -->
            <button class="slider-btn prev-btn" aria-label="Gambar Sebelumnya">&#8592;</button>

            <!-- Wrapper Gambar (Yang Akan Digeser) -->
            <div class="slider-wrapper">
                <div class="slider-track" id="sliderTrack">
                    <!-- Slide 1 -->
                    <div class="slide">
                        <img src="Images/WeddingMoment1.jpg" alt="Momen Pernikahan 1">
                    </div>
                    <!-- Slide 2 -->
                    <div class="slide">
                        <img src="Images/WeddingMoment2.jpg" alt="Momen Pernikahan 2">
                    </div>
                    <!-- Slide 3 -->
                    <div class="slide">
                        <img src="Images/WeddingMoment3.jpg" alt="Momen Pernikahan 3">
                    </div>
                </div>
            </div>

            <!-- Tombol Kanan -->
            <button class="slider-btn next-btn" aria-label="Gambar Selanjutnya">&#8594;</button>
            
        </div>
    </section>

    <?php include 'footer.php'; ?>
