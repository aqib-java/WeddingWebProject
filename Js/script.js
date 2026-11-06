document.addEventListener('DOMContentLoaded', function() {
    
    // 1. NAVBAR SCROLL EFFECT
    const header = document.getElementById('header');
    
    if(header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // 2. HAMBURGER MENU & SIDEBAR LOGIC
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const menuOverlay = document.getElementById('menuOverlay');
    const menuLinks = document.querySelectorAll('.sidebar-nav a');

    // CEK APAKAH ELEMENNYA ADA SEBELUM MENJALANKAN FUNGSI
    if(menuToggle && sidebarMenu && menuOverlay && closeMenuBtn) {
        
        // Fungsi buka
        menuToggle.addEventListener('click', function() {
            sidebarMenu.classList.add('active');
            menuOverlay.classList.add('active');
        });

        // Fungsi tutup via 'X'
        closeMenuBtn.addEventListener('click', function() {
            sidebarMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
        });

        // Fungsi tutup via background gelap
        menuOverlay.addEventListener('click', function() {
            sidebarMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
        });

        // Fungsi tutup via klik menu
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                sidebarMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
            });
        });
        
    } else {
        console.error("Salah satu elemen menu tidak ditemukan! Cek class dan ID di HTML.");
    }

    // 3. MOMENTS SLIDER LOGIC
    const track = document.getElementById('sliderTrack');
    const slides = Array.from(document.querySelectorAll('.slide'));
    const nextButton = document.querySelector('.next-btn');
    const prevButton = document.querySelector('.prev-btn');
    
    if (track && slides.length > 0) {
        let currentIndex = 0;

        function updateSliderPosition() {
            // Menggeser track ke kiri sebesar persentase (100% * index)
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        nextButton.addEventListener('click', function() {
            if (currentIndex < slides.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0; // Kembali ke awal jika sudah di akhir
            }
            updateSliderPosition();
        });

        prevButton.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = slides.length - 1; // Ke foto terakhir jika di foto pertama
            }
            updateSliderPosition();
        });
    }

    // 4. SCROLL REVEAL ANIMATION (Untuk Foto & Teks di Halaman Wedding Options)
    const revealElements = document.querySelectorAll('.reveal-photo, .reveal-item');

    if (revealElements.length > 0) {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Jika elemen masuk ke dalam layar (minimal 15% bagian terlihat)
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, {
            threshold: 0.15, // Memicu animasi saat 15% foto sudah masuk layar
            rootMargin: '0px 0px -50px 0px' // Memberi sedikit jeda agar animasi terlihat jelas oleh mata
        });

        revealElements.forEach(el => {
            revealObserver.observe(el);
        });
    }

    // 5. FILTER KATALOG KOLEKSI BUSANA (SuitesNDress.php)
    const filterButtons = document.querySelectorAll('.sd-filter-btn');
    const catalogCards = document.querySelectorAll('.sd-card');

    if (filterButtons.length > 0 && catalogCards.length > 0) {
        filterButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                // Hapus class active dari semua tombol, lalu aktifkan tombol yang diklik
                filterButtons.forEach(function(b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');

                const selectedCategory = this.getAttribute('data-filter');

                // Tampilkan atau sembunyikan kartu sesuai kategori
                catalogCards.forEach(function(card) {
                    const cardCategory = card.getAttribute('data-category');
                    if (selectedCategory === 'all' || selectedCategory === cardCategory) {
                        card.classList.remove('is-hidden');
                    } else {
                        card.classList.add('is-hidden');
                    }
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {

    // =========================================================
    // 1. NAVBAR SCROLL EFFECT
    // =========================================================
    const header = document.getElementById('header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // =========================================================
    // 2. HAMBURGER MENU & SIDEBAR LOGIC
    // =========================================================
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const menuOverlay = document.getElementById('menuOverlay');
    const menuLinks = document.querySelectorAll('.sidebar-nav a');

    if (menuToggle && sidebarMenu && menuOverlay && closeMenuBtn) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            sidebarMenu.classList.add('active');
            menuOverlay.classList.add('active');
        });

        closeMenuBtn.addEventListener('click', function() {
            sidebarMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
        });

        menuOverlay.addEventListener('click', function() {
            sidebarMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
        });

        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                sidebarMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
            });
        });
    }

    // =========================================================
    // 3. MOMENTS SLIDER LOGIC (About Page)
    // =========================================================
    const track = document.getElementById('sliderTrack');
    const slides = Array.from(document.querySelectorAll('.slide'));
    const nextButton = document.querySelector('.next-btn');
    const prevButton = document.querySelector('.prev-btn');

    if (track && slides.length > 0 && nextButton && prevButton) {
        let currentIndex = 0;

        function updateSliderPosition() {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
        }

        nextButton.addEventListener('click', function() {
            currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
            updateSliderPosition();
        });

        prevButton.addEventListener('click', function() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
            updateSliderPosition();
        });
    }

    // =========================================================
    // 4. SCROLL REVEAL ANIMATION (Otomatis untuk Semua Halaman)
    // =========================================================
    const revealElements = document.querySelectorAll('.reveal-photo, .reveal-item');

    if (revealElements.length > 0 && 'IntersectionObserver' in window) {
        revealElements.forEach(function(el) {
            el.classList.add('anim-hidden');
        });

        const revealObserver = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -30px 0px'
        });

        revealElements.forEach(function(el) {
            revealObserver.observe(el);
        });
    }

    // =========================================================
    // 5. SUITS & DRESS: SMOOTH STAGGERED FILTER + PARALLAX + MODAL
    // =========================================================
    const filterButtons = document.querySelectorAll('.sd-filter-btn');
    const catalogCards = document.querySelectorAll('.sd-card');

    // A. Filter dengan Animasi Transisi Halus & Berurutan (Staggered)
    if (filterButtons.length > 0 && catalogCards.length > 0) {
        filterButtons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterButtons.forEach(function(b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');

                const selectedCategory = this.getAttribute('data-filter');

                // Langkah 1: Fade-out semua kartu terlebih dahulu
                catalogCards.forEach(function(card) {
                    card.classList.add('filtering-out');
                });

                // Langkah 2: Setelah 300ms, atur kartu yang tampil & munculkan berurutan
                setTimeout(function() {
                    let visibleIndex = 0;
                    catalogCards.forEach(function(card) {
                        const cardCategory = card.getAttribute('data-category');
                        if (selectedCategory === 'all' || selectedCategory === cardCategory) {
                            card.classList.remove('is-hidden');
                            setTimeout(function() {
                                card.classList.remove('filtering-out');
                            }, visibleIndex * 90); // Muncul bergantian tiap 90ms
                            visibleIndex++;
                        } else {
                            card.classList.add('is-hidden');
                        }
                    });
                }, 300);
            });
        });

        // B. Efek Gerak Parallax Halus Saat Kursor Diarahkan ke Foto Busana
        catalogCards.forEach(function(card) {
            const img = card.querySelector('.sd-card-img');
            card.addEventListener('mousemove', function(e) {
                const rect = card.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width - 0.5) * 10;
                const y = ((e.clientY - rect.top) / rect.height - 0.5) * 10;
                if (img) {
                    img.style.transform = `scale(1.06) translate(${x}px, ${y}px)`;
                }
            });

            card.addEventListener('mouseleave', function() {
                if (img) {
                    img.style.transform = '';
                }
            });
        });

        // C. Buat & Jalankan Pop-Up Quick-View Modal Secara Dinamis
        const modalOverlay = document.createElement('div');
        modalOverlay.className = 'sd-modal-overlay';
        modalOverlay.innerHTML = `
            <div class="sd-modal-box">
                <button class="sd-modal-close" aria-label="Tutup">&times;</button>
                <div class="sd-modal-img-col">
                    <img src="" alt="Detail Busana" id="sdModalImg">
                </div>
                <div class="sd-modal-info-col">
                    <span class="sd-card-number" id="sdModalNum"></span>
                    <h2 class="sd-card-title" id="sdModalTitle"></h2>
                    <div class="sd-card-meta" id="sdModalMeta"></div>
                    <p class="sd-card-desc" id="sdModalDesc" style="margin-bottom: 35px;"></p>
                    <a href="Contact.php" class="btn-outline">Jadwalkan Fitting Koleksi Ini</a>
                </div>
            </div>
        `;
        document.body.appendChild(modalOverlay);

        const modalImg = document.getElementById('sdModalImg');
        const modalNum = document.getElementById('sdModalNum');
        const modalTitle = document.getElementById('sdModalTitle');
        const modalMeta = document.getElementById('sdModalMeta');
        const modalDesc = document.getElementById('sdModalDesc');
        const modalClose = modalOverlay.querySelector('.sd-modal-close');

        // Saat salah satu kartu gaun/jas diklik, buka modal dengan datanya
        catalogCards.forEach(function(card) {
            card.addEventListener('click', function() {
                const imgEl = card.querySelector('.sd-card-img');
                const numEl = card.querySelector('.sd-card-number');
                const titleEl = card.querySelector('.sd-card-title');
                const metaEl = card.querySelector('.sd-card-meta');
                const descEl = card.querySelector('.sd-card-desc');

                if (imgEl) modalImg.src = imgEl.src;
                if (numEl) modalNum.textContent = numEl.textContent;
                if (titleEl) modalTitle.textContent = titleEl.textContent;
                if (metaEl) modalMeta.innerHTML = metaEl.innerHTML;
                if (descEl) modalDesc.textContent = descEl.textContent;

                modalOverlay.classList.add('active');
            });
        });

        // Fungsi tutup modal
        modalClose.addEventListener('click', function() {
            modalOverlay.classList.remove('active');
        });

        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                modalOverlay.classList.remove('active');
            }
        });
    }

    // =========================================================
    // 8. SHOW / HIDE PASSWORD LOGIN & REGISTER
    // =========================================================
    const toggleLoginPass = document.getElementById('toggleLoginPass');
    const loginPasswordInput = document.getElementById('loginPassword');

    if (toggleLoginPass && loginPasswordInput) {
        toggleLoginPass.addEventListener('click', function() {
            const isPassword = loginPasswordInput.getAttribute('type') === 'password';
            loginPasswordInput.setAttribute('type', isPassword ? 'text' : 'password');
            this.textContent = isPassword ? 'Sembunyikan' : 'Tampilkan';
        });
    }

});

});