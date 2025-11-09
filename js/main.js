document.addEventListener('DOMContentLoaded', function() {
    // New elements for the redesigned "Agendar Cita" page
    const daysContainer = document.getElementById('days-container');
    const dateRangeDisplay = document.getElementById('date-range-display');
    const prevWeekBtn = document.getElementById('prev-week');
    const nextWeekBtn = document.getElementById('next-week');
    const timeSlotsEl = document.getElementById('time-slots');

    // Only run this script if the necessary elements are on the page
    if (!daysContainer) {
        return;
    }

    let currentDate = new Date();

    const formatDate = (date) => {
        const options = { day: 'numeric', month: 'short' };
        return new Intl.DateTimeFormat('es-ES', options).format(date);
    }
    
    const toISODateString = (date) => {
        return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    }

    function renderWeekSelector() {
        // Clear previous content
        daysContainer.innerHTML = '';
        timeSlotsEl.innerHTML = '<p class="no-slots-selected">Selecciona una fecha para ver las horas disponibles.</p>';

        // Calculate week range
        const weekStart = new Date(currentDate);
        // weekStart.setDate(currentDate.getDate() - currentDate.getDay()); // Start week on Sunday
        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekStart.getDate() + 6); // Display 7 days

        // Update the date range display header
        dateRangeDisplay.textContent = `${formatDate(weekStart)} - ${formatDate(weekEnd)} ${weekStart.getFullYear()}`;

        // Generate day cards for the next 7 days
        for (let i = 0; i < 7; i++) {
            const dayDate = new Date(weekStart);
            dayDate.setDate(weekStart.getDate() + i);

            const dayCard = document.createElement('div');
            dayCard.className = 'day-card';
            dayCard.dataset.date = toISODateString(dayDate);

            const dayName = document.createElement('span');
            dayName.className = 'day-name';
            dayName.textContent = new Intl.DateTimeFormat('es-ES', { weekday: 'short' }).format(dayDate);
            
            const dayNumber = document.createElement('span');
            dayNumber.className = 'day-number';
            dayNumber.textContent = dayDate.getDate();

            dayCard.appendChild(dayName);
            dayCard.appendChild(dayNumber);

            // Highlight today
            const today = new Date();
            if (dayDate.toDateString() === today.toDateString()) {
                dayCard.classList.add('today'); // You can style this class if you want
            }

            dayCard.addEventListener('click', function() {
                // Remove selected state from others
                document.querySelectorAll('.day-card.selected').forEach(card => card.classList.remove('selected'));
                // Add selected state to clicked card
                this.classList.add('selected');
                // Fetch time slots for the selected date
                fetchTimeSlots(this.dataset.date);
            });

            daysContainer.appendChild(dayCard);
        }
    }

    async function fetchTimeSlots(date) {
        timeSlotsEl.innerHTML = '<p>Cargando horas...</p>';
        try {
            // NOTE: This assumes an API endpoint exists at this path.
            // This might need adjustment based on the actual project structure.
            const response = await fetch(`api/get_slots.php?date=${date}`);
            if (!response.ok) {
                 throw new Error(`HTTP error! status: ${response.status}`);
            }
            const slots = await response.json();

            timeSlotsEl.innerHTML = '';
            if (slots.length > 0) {
                slots.forEach(slot => {
                    const slotEl = document.createElement('div');
                    slotEl.classList.add('time-slot-agendar');
                    if (!slot.available) {
                        slotEl.classList.add('reserved');
                        slotEl.textContent = `${slot.time}`;
                    } else {
                        slotEl.textContent = slot.time;
                        // Attach click event to open booking modal
                        slotEl.addEventListener('click', () => openBookingModal(date, slot.time));
                    }
                    timeSlotsEl.appendChild(slotEl);
                });
            } else {
                timeSlotsEl.innerHTML = '<p>No hay horas disponibles para este día.</p>';
            }
        } catch (error) {
            console.error("Error fetching time slots:", error);
            timeSlotsEl.innerHTML = '<p>Error al cargar las horas. Es posible que el servicio no esté disponible.</p>';
        }
    }

    function openBookingModal(date, time) {
        const modal = document.getElementById('booking-modal');
        if (modal) {
            document.getElementById('modal-date').textContent = date;
            document.getElementById('modal-time').textContent = time;
            document.getElementById('modal-datetime-input').value = `${date} ${time}`;
            modal.style.display = 'block';
            // loadServices(); // Assuming this function exists to populate service options in the modal
        } else {
            console.error("Booking modal not found on this page.");
        }
    }
    
    // Event Listeners for week navigation
    prevWeekBtn.addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() - 7);
        renderWeekSelector();
    });

    nextWeekBtn.addEventListener('click', () => {
        currentDate.setDate(currentDate.getDate() + 7);
        renderWeekSelector();
    });

    // Initial render
    renderWeekSelector();
});

// Logic for other pages can go here, or be in separate files.
// For example, the modal closing logic:
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('booking-modal');
    if (modal) {
        const closeBtn = modal.querySelector('.close-button');
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    }
});
