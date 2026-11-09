// =========================================================
// 10. CONTACT PAGE: SLOT JAM MURNI DARI DATABASE MYSQL
// =========================================================
const cpDateInput = document.getElementById('meetingDate');
const cpTimeGrid = document.getElementById('timeSlotGrid');
const cpSelectedTime = document.getElementById('selectedTime');
const cpHelperText = document.getElementById('slotHelperText');
const cpDateLabel = document.getElementById('selectedDateLabel');

if (cpDateInput && cpTimeGrid && cpSelectedTime) {
    // Sesi 1 jam + jeda 1 jam => start tiap 2 jam
    const opHours = [9, 11, 13, 15, 17];
    const dbSlots = window.DB_BOOKED_SLOTS || {};

    function formatIndoDate(dStr) {
        const p = (dStr || '').split('-');
        return p.length === 3 ? `(${p[2]}/${p[1]}/${p[0]})` : '';
    }

    function renderContactSlots() {
        const chosenDate = cpDateInput.value;
        if (cpDateLabel) cpDateLabel.textContent = formatIndoDate(chosenDate);
        cpTimeGrid.innerHTML = '';
        cpSelectedTime.value = '';

        const bookedTimes = dbSlots[chosenDate] || [];
        const bookedHours = bookedTimes.map(function (t) {
            return parseInt(t.split(':')[0], 10);
        });

        opHours.forEach(function (hr) {
            const label = (hr < 10 ? '0' + hr : hr) + ':00';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'cp-time-slot';

            const isBooked = bookedHours.includes(hr);
            const isBuffer = !isBooked && bookedHours.some(function (b) {
                return Math.abs(b - hr) <= 1;
            });

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
                btn.innerHTML = `<span class="slot-time">${label}</span><span class="slot-badge">Tersedia</span>`;

                btn.addEventListener('click', function () {
                    cpTimeGrid.querySelectorAll('.cp-time-slot').forEach(function (b) {
                        b.classList.remove('is-selected');
                    });
                    this.classList.add('is-selected');
                    cpSelectedTime.value = label;
                    const nextHr = ((hr + 1) < 10 ? '0' + (hr + 1) : (hr + 1)) + ':00';
                    if (cpHelperText) {
                        cpHelperText.innerHTML = `Jadwal dipilih: <strong>${label} WIB</strong> (sesi 1 jam, jeda 1 jam hingga ${nextHr}).`;
                    }
                });
            }

            cpTimeGrid.appendChild(btn);
        });
    }

    cpDateInput.addEventListener('change', function () {
        if (cpHelperText) {
            cpHelperText.textContent = 'Silakan klik salah satu jam yang tersedia di atas.';
        }
        renderContactSlots();
    });

    renderContactSlots();

    // Cegah double-submit + feedback visual
    const cpForm = document.getElementById('appointmentForm');
    if (cpForm) {
        cpForm.addEventListener('submit', function (e) {
            if (!cpSelectedTime.value) {
                e.preventDefault();
                if (cpHelperText) {
                    cpHelperText.innerHTML = '<span style="color:#C84B31;">Silakan pilih salah satu jam terlebih dahulu.</span>';
                }
                cpTimeGrid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            const submitBtn = cpForm.querySelector('.cp-submit-btn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';
            }
        });
    }
}
