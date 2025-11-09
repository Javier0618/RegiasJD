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
    <div class="modal-content redesigned">
        <div class="modal-header">
            <div class="header-icon-title">
                <i data-lucide="sparkles"></i>
                <h2>Agendar Cita</h2>
            </div>
            <button class="close-button">&times;</button>
        </div>
        <div class="modal-subheader">
            <span id="modal-date-display"><i data-lucide="calendar"></i> martes, 11 de noviembre</span>
            <span id="modal-time-display"><i data-lucide="clock"></i> 08:00 AM</span>
        </div>
        <div class="modal-body">
            <form id="booking-form" class="styled-form">
                <input type="hidden" id="modal-datetime-input" name="datetime">
                <div class="form-row">
                    <div class="form-group">
                        <label for="client-name">Nombre Completo *</label>
                        <input type="text" id="client-name" name="name" placeholder="Tu nombre completo" required>
                    </div>
                    <div class="form-group">
                        <label for="client-phone">Número de Celular *</label>
                        <input type="tel" id="client-phone" name="phone" placeholder="Ej: 3001234567" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Servicios * <span class="label-subtitle">(Selecciona uno o más)</span></label>
                    <div class="service-list" id="services-list-container">
                        <!-- Services will be dynamically inserted here -->
                    </div>
                    <p class="error-message" id="service-error">Por favor selecciona al menos un servicio</p>
                </div>

                <div class="form-group">
                    <label for="comments">Notas Adicionales (Opcional)</label>
                    <textarea id="comments" name="comments" placeholder="Alguna preferencia o detalle adicional..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary btn-submit-booking">Confirmar Cita</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php require_once 'includes/footer.php'; ?>
