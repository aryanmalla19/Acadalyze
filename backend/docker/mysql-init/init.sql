-- Create Tables
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
);

CREATE TABLE schools (
    school_id INT AUTO_INCREMENT PRIMARY KEY,
    school_name VARCHAR(100) NOT NULL,
    school_email VARCHAR(100) NOT NULL,
    established_date DATE NOT NULL,
    telephone_number VARCHAR(15) NOT NULL,
    address VARCHAR(255)
);

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NULL,
    school_id INT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address VARCHAR(255),
    phone_number VARCHAR(15) NOT NULL,
    parent_phone_number VARCHAR(15),
    date_of_birth DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    FOREIGN KEY (school_id) REFERENCES schools(school_id)
);

-- Insert Sample Data
INSERT INTO roles (role_name) VALUES
('Student'),
('Teacher'),
('Admin');

INSERT INTO schools (school_name, school_email, established_date, telephone_number, address) VALUES
('Greenwood High School', 'contact@greenwoodhigh.edu', '1995-09-01', '5551234567', '123 Maple St, Springfield'),
('Riverdale Academy', 'info@riverdaleacademy.edu', '2000-08-15', '5552345678', '456 Oak Ave, Riverside'),
('Sunshine Elementary', 'admin@sunshineelem.edu', '1985-07-20', '5553456789', NULL);