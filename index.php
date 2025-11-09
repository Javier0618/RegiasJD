<?php require_once 'includes/header.php'; ?>

<div class="page-container">
    <div class="page-header">
        <h1>Agendar Cita</h1>
        <p>Selecciona una fecha y hora para tu cita.</p>
    </div>

    <div class="scheduler-container">
        <!-- Calendar Section -->
        <div class="calendar-wrapper">
            <h2>Elige un Día</h2>
            <div id="calendar" class="calendar">
                <!-- Calendar will be generated here by PHP/JS -->
                <div class="calendar-header">
                    <button id="prev-month">&lt;</button>
                    <h3 id="month-year">Noviembre 2025</h3>
                    <button id="next-month">&gt;</button>
                </div>
                <div class="calendar-days">
                    <span>Dom</span><span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span>
                </div>
                <div class="calendar-dates">
                    <!-- Dates will be populated here -->
                </div>
            </div>
        </div>

        <!-- Time Slots Section -->
        <div class="time-slots-wrapper">
            <h2>Horas Disponibles</h2>
            <div id="time-slots" class="time-slots">
                <p class="no-slots-selected">Selecciona una fecha para ver las horas disponibles.</p>
                <!-- Available time slots will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal (hidden by default) -->
<div id="booking-modal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h3>Confirmar Cita</h3>
        <p><strong>Fecha:</strong> <span id="modal-date"></span></p>
        <p><strong>Hora:</strong> <span id="modal-time"></span></p>
        <form id="booking-form">
            <input type="hidden" id="modal-datetime-input" name="datetime">
            <div class="form-group">
                <label for="client-name">Nombre Completo</label>
                <input type="text" id="client-name" name="name" required>
            </div>
            <div class="form-group">
                <label for="client-phone">Celular</label>
                <input type="tel" id="client-phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="services">Servicios</label>
                <select id="services" name="services[]" multiple required>
                    <!-- Services will be loaded from DB -->
                </select>
            </div>
            <div class="form-group">
                <label for="comments">Comentario (Opcional)</label>
                <textarea id="comments" name="comments" rows="3"></textarea>
            </div>
            <div class="booking-summary">
                <p><strong>Tiempo Total:</strong> <span id="total-duration">0</span> min</p>
                <p><strong>Precio Total:</strong> $<span id="total-price">0</span></p>
            </div>
            <button type="submit" class="btn-primary">Agendar Cita</button>
        </form>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>
