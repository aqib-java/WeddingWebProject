// =========================================================
// 10. CONTACT PAGE: SLOT JAM MURNI DARI DATABASE MYSQL
// =========================================================
const cpDateInput = document.getElementById('meetingDate');
const cpTimeGrid = document.getElementById('timeSlotGrid');
const cpSelectedTime = document.getElementById('selectedTime');
const cpHelperText = document.getElementById('slotHelperText');
const cpDateLabel = document.getElementById('selectedDateLabel');

if (cpDateInput && cpTimeGrid && cpSelectedTime) {
    const opHours = [9, 10, 11, 12, 13, 14, 15, 16, 17];
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

            const isBufferBeforeMeeting = bookedHours.includes(hr + 1);
            const isBufferAfterMeeting = bookedHours.includes(hr - 1);

            const isBuffer = isBufferBeforeMeeting || isBufferAfterMeeting;

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
                        cpHelperText.innerHTML = `Jadwal dipilih: <strong>${label} WIB</strong> (Pukul ${nextHr} otomatis dikunci sebagai waktu jeda 1 jam).`;
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
}