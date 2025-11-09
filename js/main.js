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

    async function loadServices() {
        const container = document.getElementById('services-list-container');
        const serviceError = document.getElementById('service-error');
        if (!container) return;
    
        container.innerHTML = '<p>Cargando servicios...</p>';
        serviceError.style.display = 'none';
    
        try {
            const response = await fetch('api/get_services.php');
            if (!response.ok) throw new Error('Network response was not ok');
            const services = await response.json();
    
            container.innerHTML = ''; // Clear loading message
            services.forEach(service => {
                if (service.status !== 'activo') return;
    
                const serviceItem = document.createElement('div');
                serviceItem.className = 'service-item';
    
                // Format price for Colombian locale
                const formattedPrice = new Intl.NumberFormat('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                }).format(service.price);
    
                serviceItem.innerHTML = `
                    <div class="service-item-select">
                        <input type="checkbox" id="service-${service.id}" name="services[]" value="${service.id}">
                        <label for="service-${service.id}">${service.name}</label>
                    </div>
                    <div class="service-details">
                        <span class="duration"><i data-lucide="clock"></i> ${service.duration} min</span>
                        <span class="price"><i data-lucide="tag"></i> ${formattedPrice}</span>
                    </div>
                `;
                container.appendChild(serviceItem);
            });
    
            // Add event listener to hide error message when a service is selected
            container.addEventListener('change', () => {
                const anyChecked = container.querySelector('input[name="services[]"]:checked');
                if (anyChecked) {
                    serviceError.style.display = 'none';
                }
            });
    
        } catch (error) {
            container.innerHTML = '<p>Error al cargar los servicios.</p>';
            console.error('Error fetching services:', error);
        }
    }

    function openBookingModal(date, time) {
        const modal = document.getElementById('booking-modal');
        if (modal) {
            // Format date for display
            const dateObj = new Date(date + 'T00:00:00'); // Use T00:00:00 to avoid timezone issues
            const formattedDate = new Intl.DateTimeFormat('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            }).format(dateObj);
    
            // Format time for display
            let formattedTime = '';
            let twentyFourHourTime = time;
            try {
                // Check if time is in AM/PM format and convert it
                if (time.includes('AM') || time.includes('PM')) {
                    twentyFourHourTime = convert_to_24h(time);
                }

                const dateTimeString = `${date}T${twentyFourHourTime}`;
                const timeObj = new Date(dateTimeString);
                if (isNaN(timeObj)) {
                    throw new Error('Invalid time value');
                }
                
                // We format the original time for display, not the 24h one
                formattedTime = new Intl.DateTimeFormat('es-ES', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                }).format(timeObj);

            } catch (e) {
                console.error(`Could not parse time: ${time}`, e);
                formattedTime = 'Hora inválida'; // Fallback display
            }
    
            document.getElementById('modal-date-display').innerHTML = `<i data-lucide="calendar"></i> ${formattedDate}`;
            document.getElementById('modal-time-display').innerHTML = `<i data-lucide="clock"></i> ${formattedTime}`;
            document.getElementById('modal-datetime-input').value = `${date} ${time}`;
            
            modal.style.display = 'block';
            
            loadServices();
            lucide.createIcons(); // Refresh icons if they are added dynamically
        } else {
            console.error("Booking modal not found on this page.");
        }
    }
    
    function convert_to_24h(time_str) {
        const [time, modifier] = time_str.split(' ');
        let [hours, minutes] = time.split(':');
    
        if (hours === '12') {
            hours = '00';
        }
    
        if (modifier === 'PM') {
            hours = parseInt(hours, 10) + 12;
        }
    
        return `${hours}:${minutes}`;
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

        const bookingForm = document.getElementById('booking-form');
        bookingForm.addEventListener('submit', function(event) {
            const selectedServices = bookingForm.querySelectorAll('input[name="services[]"]:checked');
            const serviceError = document.getElementById('service-error');
            
            if (selectedServices.length === 0) {
                event.preventDefault(); // Stop form submission
                serviceError.style.display = 'block'; // Show error message
            } else {
                serviceError.style.display = 'none'; // Hide error message
            }
        });
    }
});
