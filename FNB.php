<?php include 'header.php'; ?>

    <!-- FOOD & BEVERAGE PAGE -->
    <main class="fnb-page">

        <!-- 1. INTRO & PHILOSOPHY SECTION (Off-White) -->
        <section class="fnbp-intro-section">
            <div class="fnbp-intro-header reveal-item">
                <span class="fnbp-subtitle">Makanan & Minuman</span>
                <h1 class="fnbp-main-title">
                    Organik, segar,<br>
                    dan menggugah selera.
                </h1>
                <p class="fnbp-main-desc">
                    Setiap hidangan di meja perjamuan Anda berawal dari tanah yang subur. Kami bekerja sama dengan petani lokal dan kebun organik sendiri untuk menghadirkan cita rasa musiman yang murni, segar, dan tak terlupakan.
                </p>
            </div>

            <!-- Split Layout: Gambar Kiri & Filosofi Kanan -->
            <div class="fnbp-philosophy-container">
                <div class="fnbp-phil-left">
                    <div class="fnbp-phil-img-box reveal-photo">
                        <img src="Images/FNB-1.jpg" alt="Panen bahan organik segar" class="fnbp-phil-img">
                    </div>
                </div>

                <div class="fnbp-phil-right reveal-item">
                    <div class="fnbp-phil-inner">
                        <span class="fnbp-number">FILOSOFI DAPUR KAMI</span>
                        <h2 class="fnbp-phil-title">Dari kebun langsung ke meja perjamuan.</h2>
                        <p class="fnbp-phil-text">
                            Tim kuliner kami merancang menu secara eksklusif berdasarkan hasil panen terbaik setiap musimnya. Tanpa bahan pengawet, setiap piring memadukan teknik memasak klasik dengan keaslian rasa alam.
                        </p>

                        <ul class="fnbp-specs">
                            <li>
                                <span>Sumber Bahan</span>
                                <span>100% Kebun & Petani Lokal</span>
                            </li>
                            <li>
                                <span>Kurasi Menu</span>
                                <span>Menyesuaikan Musim Panen</span>
                            </li>
                            <li>
                                <span>Preferensi Diet</span>
                                <span>Halal, Vegan & Gluten-Free</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. INTERACTIVE SEASONAL MENU SECTION (Dusty Pink) -->
        <section class="fnbp-menu-section">
            <div class="fnbp-menu-container">
                
                <div class="fnbp-menu-header reveal-item">
                    <span class="fnbp-subtitle">Kurasi Musiman</span>
                    <h2 class="fnbp-section-title">Simfoni Rasa di Setiap Sajian.</h2>
                    
                    <!-- Tombol Tab Menu -->
                    <div class="fnbp-tabs">
                        <button type="button" class="fnbp-tab-btn" data-course="starters">01 / Canapé & Pembuka</button>
                        <button type="button" class="fnbp-tab-btn" data-course="mains">02 / Hidangan Utama</button>
                        <button type="button" class="fnbp-tab-btn" data-course="desserts">03 / Pencuci Mulut</button>
                        <button type="button" class="fnbp-tab-btn" data-course="drinks">04 / Botanical Bar</button>
                    </div>
                </div>

                <!-- KONTEN TAB 1: STARTERS -->
                <div class="fnbp-course-panel active" id="course-starters">
                    <div class="fnbp-menu-grid">
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Tartlet Jamur Truffle Liar</h3>
                                <span class="fnbp-dish-tag">VEGETARIAN</span>
                            </div>
                            <p class="fnbp-dish-desc">Pastri renyah berisi ragout jamur hutan panggang, krim keju kambing lembut, dan minyak truffle putih.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Burrata & Tomat Pusaka</h3>
                                <span class="fnbp-dish-tag">ORGANIK KEBUN</span>
                            </div>
                            <p class="fnbp-dish-desc">Keju burrata segar disajikan bersama tomat pusaka warna-warni, daun basil manis, dan reduksi balsamic.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Ceviche Kakap Putih Sitrus</h3>
                                <span class="fnbp-dish-tag">SEGAR TANGKAPAN</span>
                            </div>
                            <p class="fnbp-dish-desc">Irisan tipis kakap putih yang dimarinasi perasan jeruk bali, delima merah, ketumbar mikro, dan cabai manis.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Bruschetta Buah Ara & Madu</h3>
                                <span class="fnbp-dish-tag">SIGNATURE</span>
                            </div>
                            <p class="fnbp-dish-desc">Roti sourdough panggang kayu bakar dengan olesan ricotta kocok, buah ara segar, kacang kenari, dan madu hutan.</p>
                        </div>
                    </div>
                </div>

                <!-- KONTEN TAB 2: MAINS -->
                <div class="fnbp-course-panel" id="course-mains">
                    <div class="fnbp-menu-grid">
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Tenderloin Sapi Panggang Herba</h3>
                                <span class="fnbp-dish-tag">PRIME CUT</span>
                            </div>
                            <p class="fnbp-dish-desc">Daging sapi tenderloin panggang mentega rosemary, disajikan dengan pure kentang lembut dan wortel mini glasir.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Salmon Kulit Renyah Saus Lemon</h3>
                                <span class="fnbp-dish-tag">OMEGA RICH</span>
                            </div>
                            <p class="fnbp-dish-desc">Fillet salmon panggang bertekstur renyah dengan saus mentega lemon capers, adas panggang, dan kacang polong manis.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Risotto Saffron & Asparagus</h3>
                                <span class="fnbp-dish-tag">VEGAN OPSI</span>
                            </div>
                            <p class="fnbp-dish-desc">Beras arborio Italia yang dimasak perlahan dengan kaldu sayuran kebun, putik saffron, dan pucuk asparagus hijau.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Bebek Panggang Saus Beri Hutan</h3>
                                <span class="fnbp-dish-tag">CHEF SPECIAL</span>
                            </div>
                            <p class="fnbp-dish-desc">Dada bebek panggang empuk dengan saus reduksi buah beri hitam, disajikan bersama umbi bit panggang dan daun thyme.</p>
                        </div>
                    </div>
                </div>

                <!-- KONTEN TAB 3: DESSERTS -->
                <div class="fnbp-course-panel" id="course-desserts">
                    <div class="fnbp-menu-grid">
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Pavlova Bunga Elder & Beri</h3>
                                <span class="fnbp-dish-tag">FAVORIT</span>
                            </div>
                            <p class="fnbp-dish-desc">Meringue renyah di luar dan lembut di dalam, dipadukan krim mascarpone bunga elder serta buah beri segar.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Tart Cokelat Hitam & Garam Laut</h3>
                                <span class="fnbp-dish-tag">ARTISANAL</span>
                            </div>
                            <p class="fnbp-dish-desc">Ganache cokelat hitam 70% dari biji kakao lokal dengan taburan garam laut Bali dan es krim vanila.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Panna Cotta Vanila & Persik</h3>
                                <span class="fnbp-dish-tag">GLUTEN-FREE</span>
                            </div>
                            <p class="fnbp-dish-desc">Puding krim lembut beraroma biji vanila murni dengan kompot buah persik hangat dan remah pistachio.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Sorbet Lemon Kebun & Kemangi</h3>
                                <span class="fnbp-dish-tag">SEGAR ALAMI</span>
                            </div>
                            <p class="fnbp-dish-desc">Pencuci mulut dingin yang menyegarkan dari perasan lemon kuning organik dan infusi daun kemangi segar.</p>
                        </div>
                    </div>
                </div>

                <!-- KONTEN TAB 4: DRINKS -->
                <div class="fnbp-course-panel" id="course-drinks">
                    <div class="fnbp-menu-grid">
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Rosemary & Grapefruit Spritz</h3>
                                <span class="fnbp-dish-tag">BOTANICAL</span>
                            </div>
                            <p class="fnbp-dish-desc">Perpaduan menyegarkan sari jeruk bali merah, sirup rosemary buatan sendiri, dan air soda kristal.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Spiced Pear & Ginger Elixir</h3>
                                <span class="fnbp-dish-tag">WARM / COLD</span>
                            </div>
                            <p class="fnbp-dish-desc">Sari buah pir matang yang direbus bersama kayu manis, bunga lawang, dan perasan jahe segar.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Rosewater & Lychee Fizz</h3>
                                <span class="fnbp-dish-tag">FLORAL SERIES</span>
                            </div>
                            <p class="fnbp-dish-desc">Minuman beraroma kelopak mawar organik, buah leci manis, perasan jeruk nipis, dan daun mint.</p>
                        </div>
                        <div class="fnbp-dish-item">
                            <div class="fnbp-dish-top">
                                <h3 class="fnbp-dish-name">Kurasi Teh Kebun & Kopi Arabika</h3>
                                <span class="fnbp-dish-tag">AFTER DINNER</span>
                            </div>
                            <p class="fnbp-dish-desc">Seduhan teh bunga chamomile, peppermint segar, atau kopi single-origin untuk menutup malam perayaan.</p>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- 3. DINING STYLES & PRIVATE TASTING SECTION (Hijau Sage) -->
        <section class="fnbp-service-section">
            <div class="fnbp-service-container">

                <div class="fnbp-service-header reveal-item">
                    <span class="fnbp-subtitle">Gaya Penyajian</span>
                    <h2 class="fnbp-section-title">Dirancang Mengikuti Suasana Pesta Anda.</h2>
                </div>

                <!-- 3 Kolom Gaya Penyajian -->
                <div class="fnbp-service-grid">
                    <div class="fnbp-service-card">
                        <div class="fnbp-service-img-box reveal-photo">
                            <img src="Images/Cocktail & Canape.jpg" alt="Cocktail & Canapé" class="fnbp-service-img">
                        </div>
                        <div class="fnbp-service-body reveal-item">
                            <span class="fnbp-card-num">01 / STANDING RECEPTION</span>
                            <h3 class="fnbp-card-title">Cocktail & Canapé</h3>
                            <p class="fnbp-card-desc">Sajian lepas tangan berukuran satu suapan yang diedarkan secara hangat saat tamu bercengkrama di bawah pepohonan.</p>
                        </div>
                    </div>

                    <div class="fnbp-service-card">
                        <div class="fnbp-service-img-box reveal-photo">
                            <img src="Images/Long-Table Banquet.jpg" alt="Long-Table Banquet" class="fnbp-service-img">
                        </div>
                        <div class="fnbp-service-body reveal-item">
                            <span class="fnbp-card-num">02 / SEATED DINNER</span>
                            <h3 class="fnbp-card-title">Long-Table Banquet</h3>
                            <p class="fnbp-card-desc">Jamuan makan malam meja panjang bergaya keluarga atau set menu berurutan di bawah kilau lampu gantung.</p>
                        </div>
                    </div>

                    <div class="fnbp-service-card">
                        <div class="fnbp-service-img-box reveal-photo">
                            <img src="Images/Full Botanical Bar.jpg" alt="Full Botanical Bar" class="fnbp-service-img">
                        </div>
                        <div class="fnbp-service-body reveal-item">
                            <span class="fnbp-card-num">03 / BESPOKE DRINKS</span>
                            <h3 class="fnbp-card-title">Full Botanical Bar</h3>
                            <p class="fnbp-card-desc">Bar terbuka dengan peracik minuman profesional yang menyajikan kreasi minuman khas bernama pasangan pengantin.</p>
                        </div>
                    </div>
                </div>

                <!-- Box CTA Sesi Cicip Menu -->
                <div class="fnbp-tasting-cta reveal-item">
                    <h3 class="fnbp-tasting-title">Ingin mencicipi kreasi menu kami secara langsung?</h3>
                    <p class="fnbp-tasting-desc">Jadwalkan sesi Private Food Tasting bersama Executive Chef kami untuk menyusun menu perjamuan impian Anda.</p>
                    <a href="Contact.php" class="btn-outline">Jadwalkan Food Tasting</a>
                </div>

            </div>
        </section>

    </main>

<?php include 'footer.php'; ?>

    <script src="Js/FNB.js"></script>
