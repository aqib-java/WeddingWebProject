// =========================================================
    // 11. ADMIN DASHBOARD: TAB, EDIT INDEPENDEN, DELETE RESERVASI & AKUN
    // =========================================================
    const adminTabBtns = document.querySelectorAll('.admin-tab-btn');
    const adminTabPanels = document.querySelectorAll('.admin-tab-panel');

    // A. Pergantian Tab Admin (Reservasi vs Akun Klien)
    if (adminTabBtns.length > 0) {
        adminTabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-admin-tab');
                const targetPanel = document.getElementById(targetId);
                if (!targetPanel) return;

                adminTabBtns.forEach(function(b) { b.classList.remove('active'); });
                this.classList.add('active');

                adminTabPanels.forEach(function(p) { p.classList.remove('active'); });
                targetPanel.classList.add('active');
            });
        });
    }

    // B. Modal Edit Tanggal & Jam Independen
    const editModal = document.getElementById('editMeetingModal');
    const editBtns = document.querySelectorAll('.edit-meeting-btn');
    const editIdInput = document.getElementById('editMeetingId');
    const editDateInput = document.getElementById('editMeetingDate');
    const editTimeInput = document.getElementById('editMeetingTime');
    const editNotesInput = document.getElementById('editMeetingNotes');
    const modalClientName = document.getElementById('modalClientName');
    const adminTimeGrid = document.getElementById('adminTimeGrid');
    const adminSlotHelper = document.getElementById('adminSlotHelper');

    function renderAdminTimeSlots(selectedDate, currentMeetingId, desiredTime) {
        if (!adminTimeGrid) return;
        adminTimeGrid.innerHTML = '';
        const hours = [9, 10, 11, 12, 13, 14, 15, 16, 17];
        const map = window.ADMIN_BOOKED_MAP || {};
        const bookingsOnDate = map[selectedDate] || [];

        // Kecualikan reservasi yang sedang diedit agar tidak dianggap bentrok dengan dirinya sendiri
        const otherBookedHours = bookingsOnDate
            .filter(function(item) { return item.meeting_id !== parseInt(currentMeetingId, 10); })
            .map(function(item) { return item.hour; });

        let desiredStillAvailable = false;

        hours.forEach(function(hr) {
            const label = (hr < 10 ? '0' + hr : hr) + ':00';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cp-time-slot';

            const isBooked = otherBookedHours.includes(hr);
            const isBuffer = otherBookedHours.includes(hr - 1);

            if (isBooked) {
                btn.classList.add('is-booked');
                btn.disabled = true;
                btn.innerHTML = `<span class="slot-time">${label}</span><span class="slot-badge">Terisi</span>`;
            } else if (isBuffer) {
                btn.classList.add('is-buffer');
                btn.disabled = true;
                btn.innerHTML = `<span class="slot-time">${label}</span><span class="slot-badge">Jeda 1 Jam</span>`;
            } else {
                btn.classList.add('is-available');
                // Pertahankan jam pilihan sebelumnya secara otomatis jika tersedia di tanggal baru
                if (label === desiredTime) {
                    btn.classList.add('is-selected');
                    editTimeInput.value = label;
                    desiredStillAvailable = true;
                }
                btn.innerHTML = `<span class="slot-time">${label}</span><span class="slot-badge">Tersedia</span>`;

                btn.addEventListener('click', function() {
                    adminTimeGrid.querySelectorAll('.cp-time-slot').forEach(function(b) {
                        b.classList.remove('is-selected');
                    });
                    this.classList.add('is-selected');
                    editTimeInput.value = label;
                    if (adminSlotHelper) {
                        adminSlotHelper.innerHTML = `Jam aktif: <strong>${label} WIB</strong>.`;
                        adminSlotHelper.style.color = '#111111';
                    }
                });
            }

            adminTimeGrid.appendChild(btn);
        });

        // Beri info apakah jam lama tetap dipertahankan atau perlu dipilih ulang karena bentrok
        if (desiredTime && desiredStillAvailable) {
            if (adminSlotHelper) {
                adminSlotHelper.innerHTML = `Jam tetap di <strong>${desiredTime} WIB</strong> (Anda bisa langsung simpan atau klik jam lain).`;
                adminSlotHelper.style.color = '#111111';
            }
        } else if (desiredTime && !desiredStillAvailable) {
            editTimeInput.value = '';
            if (adminSlotHelper) {
                adminSlotHelper.innerHTML = `Pukul ${desiredTime} sudah terisi/jeda pada tanggal ini. Silakan pilih jam baru.`;
                adminSlotHelper.style.color = '#C84B31';
            }
        }
    }

    if (editBtns.length > 0 && editModal) {
        editBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const date = this.getAttribute('data-date');
                const time = this.getAttribute('data-time');
                const notes = this.getAttribute('data-notes');

                editIdInput.value = id;
                editDateInput.value = date;
                editTimeInput.value = time;
                editNotesInput.value = notes;
                if (modalClientName) modalClientName.textContent = 'Edit Jadwal: ' + name;

                renderAdminTimeSlots(date, id, time);
                editModal.classList.add('active');
            });
        });

        // Saat Admin mengubah tanggal, jam yang sudah dipilih TIDAK di-reset selama masih tersedia
        editDateInput.addEventListener('change', function() {
            const currentSelectedTime = editTimeInput.value;
            renderAdminTimeSlots(this.value, editIdInput.value, currentSelectedTime);
        });

        ['closeEditModal', 'cancelEditBtn'].forEach(function(btnId) {
            const closeEl = document.getElementById(btnId);
            if (closeEl) {
                closeEl.addEventListener('click', function() {
                    editModal.classList.remove('active');
                });
            }
        });
    }

    // C. Modal Konfirmasi Hapus Reservasi Meeting
    const deleteMeetingModal = document.getElementById('deleteMeetingModal');
    const deleteMeetingBtns = document.querySelectorAll('.delete-meeting-btn');
    const deleteMeetingIdInput = document.getElementById('deleteMeetingIdInput');
    const deleteMeetingText = document.getElementById('deleteMeetingText');

    if (deleteMeetingBtns.length > 0 && deleteMeetingModal) {
        deleteMeetingBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const mid = this.getAttribute('data-id');
                const mname = this.getAttribute('data-name');
                const mdate = this.getAttribute('data-date');
                const mtime = this.getAttribute('data-time');

                deleteMeetingIdInput.value = mid;
                if (deleteMeetingText) {
                    deleteMeetingText.innerHTML = `Apakah Anda yakin ingin menghapus reservasi <strong>#${mid} (${mname})</strong> pada tanggal <strong>${mdate} pukul ${mtime} WIB</strong> secara permanen?`;
                }
                deleteMeetingModal.classList.add('active');
            });
        });

        ['closeDeleteMeetingModal', 'cancelDeleteMeetingBtn'].forEach(function(btnId) {
            const closeEl = document.getElementById(btnId);
            if (closeEl) {
                closeEl.addEventListener('click', function() {
                    deleteMeetingModal.classList.remove('active');
                });
            }
        });
    }

    // D. Modal Konfirmasi Hard Delete Akun Klien
    const deleteModal = document.getElementById('deleteClientModal');
    const deleteBtns = document.querySelectorAll('.delete-client-btn');
    const deleteIdInput = document.getElementById('deleteClientIdInput');
    const deleteClientText = document.getElementById('deleteClientText');

    if (deleteBtns.length > 0 && deleteModal) {
        deleteBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const uid = this.getAttribute('data-userid');
                const fname = this.getAttribute('data-fullname');
                const email = this.getAttribute('data-email');

                deleteIdInput.value = uid;
                if (deleteClientText) {
                    deleteClientText.innerHTML = `Apakah Anda yakin ingin menghapus akun klien <strong>${fname}</strong> (${email}) secara permanen dari tabel <code>users</code>?`;
                }
                deleteModal.classList.add('active');
            });
        });

        ['closeDeleteModal', 'cancelDeleteBtn'].forEach(function(btnId) {
            const closeEl = document.getElementById(btnId);
            if (closeEl) {
                closeEl.addEventListener('click', function() {
                    deleteModal.classList.remove('active');
                });
            }
        });
    }