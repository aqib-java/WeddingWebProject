document.addEventListener('DOMContentLoaded', function () {

    // =========================================================
    // 1. NAVBAR SCROLL EFFECT
    // =========================================================
    const header = document.getElementById('header');

    if (header) {
        const onScroll = function () {
            header.classList.toggle('scrolled', window.scrollY > 50);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // =========================================================
    // 2. HAMBURGER MENU & SIDEBAR
    // =========================================================
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const closeMenuBtn = document.getElementById('closeMenuBtn');
    const menuOverlay = document.getElementById('menuOverlay');
    const menuLinks = document.querySelectorAll('.sidebar-nav a');

    if (menuToggle && sidebarMenu && menuOverlay && closeMenuBtn) {
        const openMenu = function () {
            sidebarMenu.classList.add('active');
            menuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        };
        const closeMenu = function () {
            sidebarMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        };

        menuToggle.addEventListener('click', function (e) {
            e.preventDefault();
            openMenu();
        });
        closeMenuBtn.addEventListener('click', closeMenu);
        menuOverlay.addEventListener('click', closeMenu);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
        menuLinks.forEach(function (link) {
            link.addEventListener('click', closeMenu);
        });
    }

    // =========================================================
    // 3. MOMENTS SLIDER (About Page)
    // =========================================================
    const track = document.getElementById('sliderTrack');
    const slides = Array.from(document.querySelectorAll('.slide'));
    const nextButton = document.querySelector('.next-btn');
    const prevButton = document.querySelector('.prev-btn');

    if (track && slides.length > 0 && nextButton && prevButton) {
        let currentIndex = 0;

        const updateSliderPosition = function () {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
        };

        nextButton.addEventListener('click', function () {
            currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
            updateSliderPosition();
        });
        prevButton.addEventListener('click', function () {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
            updateSliderPosition();
        });
    }

    // =========================================================
    // 4. SCROLL REVEAL ANIMATION
    // =========================================================
    const revealElements = document.querySelectorAll('.reveal-photo, .reveal-item');

    if (revealElements.length > 0 && 'IntersectionObserver' in window) {
        revealElements.forEach(function (el) {
            el.classList.add('anim-hidden');
        });

        const revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -30px 0px'
        });

        revealElements.forEach(function (el) {
            revealObserver.observe(el);
        });
    }

    // =========================================================
    // 5. SUITS & DRESS: FILTER + PARALLAX + MODAL
    // =========================================================
    const filterButtons = document.querySelectorAll('.sd-filter-btn');
    const catalogCards = document.querySelectorAll('.sd-card');

    if (filterButtons.length > 0 && catalogCards.length > 0) {
        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterButtons.forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                const selectedCategory = this.getAttribute('data-filter');

                catalogCards.forEach(function (card) { card.classList.add('filtering-out'); });

                setTimeout(function () {
                    let visibleIndex = 0;
                    catalogCards.forEach(function (card) {
                        const cardCategory = card.getAttribute('data-category');
                        if (selectedCategory === 'all' || selectedCategory === cardCategory) {
                            card.classList.remove('is-hidden');
                            const delay = visibleIndex * 90;
                            setTimeout(function () {
                                card.classList.remove('filtering-out');
                            }, delay);
                            visibleIndex++;
                        } else {
                            card.classList.add('is-hidden');
                        }
                    });
                }, 250);
            });
        });

        catalogCards.forEach(function (card) {
            const img = card.querySelector('.sd-card-img');

            card.addEventListener('mousemove', function (e) {
                if (!img || window.matchMedia('(hover: none)').matches) return;
                const rect = card.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width - 0.5) * 10;
                const y = ((e.clientY - rect.top) / rect.height - 0.5) * 10;
                img.style.transform = `scale(1.06) translate(${x}px, ${y}px)`;
            });

            card.addEventListener('mouseleave', function () {
                if (img) img.style.transform = '';
            });
        });

        // Pop-up Quick-View Modal (dibuat sekali)
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

        const closeModal = function () {
            modalOverlay.classList.remove('active');
            document.body.style.overflow = '';
        };

        catalogCards.forEach(function (card) {
            card.addEventListener('click', function () {
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
                document.body.style.overflow = 'hidden';
            });
        });

        modalClose.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', function (e) {
            if (e.target === modalOverlay) closeModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
    }

    // =========================================================
    // 6. SHOW / HIDE PASSWORD (Login & Register)
    // =========================================================
    document.querySelectorAll('.auth-toggle-pass').forEach(function (toggleBtn) {
        const targetId = toggleBtn.id === 'toggleLoginPass' ? 'loginPassword' : 'regPassword';
        const input = document.getElementById(targetId);
        if (!input) return;

        toggleBtn.addEventListener('click', function () {
            const isPassword = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPassword ? 'text' : 'password');
            this.textContent = isPassword ? 'Sembunyikan' : 'Tampilkan';
        });
    });

    // =========================================================
    // 7. PASSWORD STRENGTH + MATCH HINT (Register)
    // =========================================================
    const regPassword = document.getElementById('regPassword');
    const regConfirm = document.getElementById('regConfirmPassword');
    const strengthFill = document.getElementById('strengthFill');
    const matchHint = document.getElementById('passMatchHint');

    if (regPassword && strengthFill) {
        regPassword.addEventListener('input', function () {
            const val = this.value;
            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const pct = Math.min(score * 20, 100);
            const colors = ['#C84B31', '#E76E3C', '#DFA6A9', '#8CA361', '#5B7A3A'];
            strengthFill.style.width = pct + '%';
            strengthFill.style.backgroundColor = val ? colors[Math.min(score, 4)] : 'transparent';
        });
    }

    if (regPassword && regConfirm && matchHint) {
        const checkMatch = function () {
            if (!regConfirm.value) {
                matchHint.textContent = '';
                return;
            }
            const match = regPassword.value === regConfirm.value;
            matchHint.textContent = match ? 'Kata sandi cocok' : 'Kata sandi belum cocok';
            matchHint.style.color = match ? '#5B7A3A' : '#C84B31';
        };
        regPassword.addEventListener('input', checkMatch);
        regConfirm.addEventListener('input', checkMatch);
    }

});
