-- Users Table: Stores both clients and the administrator
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(255) UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('client', 'admin') NOT NULL DEFAULT 'client',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services Table: Stores all salon services
CREATE TABLE `services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `duration` INT NOT NULL, -- Duration in minutes
  `price` DECIMAL(10, 2) NOT NULL,
  `image` VARCHAR(255),
  `category` VARCHAR(100),
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
);

-- Schedules Table: Stores the administrator's availability
CREATE TABLE `schedules` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `date` DATE NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `is_available` BOOLEAN NOT NULL DEFAULT 1 -- 1 for available, 0 for not
);

-- Appointments Table: Stores all scheduled appointments
CREATE TABLE `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `client_name` VARCHAR(255) NOT NULL,
  `client_phone` VARCHAR(20) NOT NULL,
  `appointment_time` DATETIME NOT NULL,
  `total_duration` INT NOT NULL, -- Total duration of all services
  `total_price` DECIMAL(10, 2) NOT NULL,
  `comments` TEXT,
  `status` ENUM('Pendiente', 'Confirmada', 'Cancelada', 'Completada') NOT NULL DEFAULT 'Pendiente',
  `cancellation_reason` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Appointment_Services Table: Links appointments to services (many-to-many)
CREATE TABLE `appointment_services` (
  `appointment_id` INT,
  `service_id` INT,
  PRIMARY KEY (`appointment_id`, `service_id`),
  FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE
);
