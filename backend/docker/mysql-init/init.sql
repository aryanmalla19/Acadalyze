CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE schools (
    school_id INT AUTO_INCREMENT PRIMARY KEY,
    school_name VARCHAR(100) NOT NULL,
    school_email VARCHAR(100) NOT NULL UNIQUE,
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
    email VARCHAR(100) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address VARCHAR(255),
    phone_number VARCHAR(15) NOT NULL,
    parent_phone_number VARCHAR(15),
    date_of_birth DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE SET NULL,
    FOREIGN KEY (school_id) REFERENCES schools(school_id) ON DELETE SET NULL
);

CREATE TABLE classes (
    class_id INT AUTO_INCREMENT PRIMARY KEY,
    class_teacher_id INT NOT NULL,
    school_id INT NOT NULL,
    class_name VARCHAR(20) NOT NULL,
    FOREIGN KEY (class_teacher_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (school_id) REFERENCES schools(school_id) ON DELETE RESTRICT
);

CREATE TABLE subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    teacher_id INT NOT NULL,
    subject_name VARCHAR(50) NOT NULL,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE exams (
    exam_id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL,
    exam_name VARCHAR(50) NOT NULL,
    exam_date DATE NOT NULL,
    FOREIGN KEY (school_id) REFERENCES schools(school_id) ON DELETE CASCADE
);

CREATE TABLE subjects_exams (
    subjects_exams_id INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT NOT NULL,
    exam_id INT NOT NULL,
    subject_exam_time TIMESTAMP,
    pass_marks INT NOT NULL,
    full_marks INT NOT NULL,
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id) ON DELETE CASCADE,
    FOREIGN KEY (exam_id) REFERENCES exams(exam_id) ON DELETE CASCADE
);

CREATE TABLE marks (
    marks_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subjects_exams_id INT NOT NULL,
    marks_obtained INT NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (subjects_exams_id) REFERENCES subjects_exams(subjects_exams_id) ON DELETE CASCADE
);

CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('Present', 'Absent', 'Late') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE
);

CREATE TABLE student_classes (
    student_class_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    enrollment_date DATE NOT NULL,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(class_id) ON DELETE CASCADE,
    UNIQUE (student_id, class_id)
);


-- Insert Sample Data
INSERT INTO roles (role_name) VALUES
('Student'),
('Teacher'),
('Parents'),
('Admin');

INSERT INTO schools (school_name, school_email, established_date, telephone_number, address) VALUES
('Greenwood High School', 'contact@greenwoodhigh.edu', '1995-09-01', '5551234567', '123 Maple St, Springfield'),
('Riverdale Academy', 'info@riverdaleacademy.edu', '2000-08-15', '5552345678', '456 Oak Ave, Riverside'),
('Sunshine Elementary', 'admin@sunshineelem.edu', '1985-07-20', '5553456789', NULL);