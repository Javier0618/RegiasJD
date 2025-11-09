<?php require_once 'includes/header.php'; ?>

<div class="page-container-agendar">
    <div class="agendar-hero">
        <div class="hero-content">
            <h1>Agenda tu Cita</h1>
            <p>Elige el día y la hora que mejor te convenga.</p>
        </div>
    </div>

    <div class="scheduler-content">
        <div class="date-navigation">
            <button class="nav-arrow" id="prev-week">&lt;</button>
            <span class="date-range" id="date-range-display">11 nov - 13 nov 2025</span>
            <button class="nav-arrow" id="next-week">&gt;</button>
        </div>

        <div class="day-selector-card">
            <h3 class="card-title"><i class="far fa-calendar-alt"></i> Elige un Día</h3>
            <div class="days-wrapper" id="days-container">
                <!-- Day cards will be dynamically inserted here by JavaScript -->
            </div>
        </div>

        <div class="time-selector-card">
            <h3 class="card-title"><i class="far fa-clock"></i> Elige una Hora</h3>
            <div class="time-slots-wrapper-agendar" id="time-slots">
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
