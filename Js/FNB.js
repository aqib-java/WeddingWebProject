    // =========================================================
// FUNGSI UTAMA: MENGUBAH COURSE MENU F&B
// =========================================================
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.fnbp-tab-btn');
    const coursePanels = document.querySelectorAll('.fnbp-course-panel');

    // Pastikan tombol dan panelnya benar-benar ada di halaman
    if (tabButtons.length > 0 && coursePanels.length > 0) {
        tabButtons.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah loncatan halaman default

                // 1. Ambil nilai kategori dari atribut data-course (misal: 'mains', 'desserts')
                const targetCourse = this.getAttribute('data-course');
                const targetPanel = document.getElementById('course-' + targetCourse);

                if (!targetPanel) return;

                // 2. Hapus class 'active' dari semua tombol, lalu pasang ke tombol yang diklik
                tabButtons.forEach(function(b) {
                    b.classList.remove('active');
                });
                this.classList.add('active');

                // 3. Sembunyikan semua panel menu, lalu tampilkan panel tujuan
                coursePanels.forEach(function(panel) {
                    panel.classList.remove('active');
                });
                targetPanel.classList.add('active');
            });
        });
    }
});