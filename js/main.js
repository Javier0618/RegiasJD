document.addEventListener('DOMContentLoaded', function() {
    const calendarDates = document.querySelector('.calendar-dates');
    const monthYearEl = document.getElementById('month-year');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const timeSlotsEl = document.getElementById('time-slots');

    let currentDate = new Date();
    currentDate.setDate(1); // Start with the first day of the month

    function renderCalendar() {
        const month = currentDate.getMonth();
        const year = currentDate.getFullYear();
        monthYearEl.textContent = `${new Intl.DateTimeFormat('es-ES', { month: 'long' }).format(currentDate)} ${year}`;

        const firstDayOfMonth = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        calendarDates.innerHTML = '';

        // Add empty slots for days before the month starts
        for (let i = 0; i < firstDayOfMonth; i++) {
            calendarDates.innerHTML += `<div class="calendar-day empty"></div>`;
        }

        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayEl = document.createElement('div');
            dayEl.classList.add('calendar-day');
            dayEl.textContent = day;
            dayEl.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            // Highlight today's date
            const today = new Date();
            if (year === today.getFullYear() && month === today.getMonth() && day === today.getDate()) {
                dayEl.classList.add('today');
            }

            calendarDates.appendChild(dayEl);
        }

        // Add click event listener to each day
        document.querySelectorAll('.calendar-day:not(.empty)').forEach(day => {
            day.addEventListener('click', function() {
                document.querySelectorAll('.calendar-day.selected').forEach(d => d.classList.remove('selected'));
                this.classList.add('selected');
                fetchTimeSlots(this.dataset.date);
            });
        });
    }

    async function fetchTimeSlots(date) {
        timeSlotsEl.innerHTML = '<p>Cargando horas...</p>';
        try {
            const response = await fetch(`/api/get_slots.php?date=${date}`);
            const slots = await response.json();

            timeSlotsEl.innerHTML = '';
            if (slots.length > 0) {
                slots.forEach(slot => {
                    const slotEl = document.createElement('div');
                    slotEl.classList.add('time-slot');
                    if (!slot.available) {
                        slotEl.classList.add('reserved');
                        slotEl.textContent = `${slot.time} (Reservada)`;
                    } else {
                        slotEl.textContent = slot.time;
                        slotEl.addEventListener('click', () => openBookingModal(date, slot.time));
                    }
                    timeSlotsEl.appendChild(slotEl);
                });
            } else {
                timeSlotsEl.innerHTML = '<p>No hay horas disponibles para este día.</p>';
            }
        } catch (error) {
            timeSlotsEl.innerHTML = '<p>Error al cargar las horas. Inténtalo de nuevo.</p>';
        }
    }

    function openBookingModal(date, time) {
        const modal = document.getElementById('booking-modal');
        document.getElementById('modal-date').textContent = date;
        document.getElementById('modal-time').textContent = time;
        document.getElementById('modal-datetime-input').value = `${date} ${time}`;
        modal.style.display = 'block';
        loadServices();
    }

    // Additional functions for modal, form submission, etc. will go here.
    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
    }

    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
    }

    if (calendarDates) {
        renderCalendar();
    }
});
