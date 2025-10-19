<?php

$db_path = __DIR__ . '/quizller.db';

if (file_exists($db_path)) {
    unlink($db_path);
}

try {
    $conn = new PDO('sqlite:' . $db_path);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "
    CREATE TABLE admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    );
    
    CREATE TABLE classes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL
    );
    
    CREATE TABLE teachers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    );
    
    CREATE TABLE students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id VARCHAR(50) NOT NULL UNIQUE,
        name VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        class_id INTEGER,
        FOREIGN KEY (class_id) REFERENCES classes(id)
    );
    
    CREATE TABLE status (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL
    );
    
    CREATE TABLE tests (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        teacher_id INTEGER NOT NULL,
        name VARCHAR(255) NOT NULL,
        date DATE NOT NULL,
        status_id INTEGER NOT NULL,
        subject VARCHAR(255) NOT NULL,
        total_questions INTEGER NOT NULL DEFAULT 0,
        class_id INTEGER NOT NULL,
        FOREIGN KEY (teacher_id) REFERENCES teachers(id),
        FOREIGN KEY (status_id) REFERENCES status(id),
        FOREIGN KEY (class_id) REFERENCES classes(id)
    );
    
    CREATE TABLE questions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title VARCHAR(500) NOT NULL,
        optionA VARCHAR(255) NOT NULL,
        optionB VARCHAR(255) NOT NULL,
        optionC VARCHAR(255) NOT NULL,
        optionD VARCHAR(255) NOT NULL,
        correctAns VARCHAR(1) NOT NULL,
        score INTEGER NOT NULL
    );
    
    CREATE TABLE question_test_mapping (
        question_id INTEGER NOT NULL,
        test_id INTEGER NOT NULL,
        PRIMARY KEY (question_id, test_id),
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
        FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
    );
    
    CREATE TABLE student_answers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER NOT NULL,
        test_id INTEGER NOT NULL,
        question_id INTEGER NOT NULL,
        answer VARCHAR(1),
        is_correct INTEGER DEFAULT 0,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE,
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
    );
    
    CREATE TABLE student_test_results (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER NOT NULL,
        test_id INTEGER NOT NULL,
        score INTEGER NOT NULL DEFAULT 0,
        completed INTEGER NOT NULL DEFAULT 0,
        completed_at DATETIME,
        UNIQUE (student_id, test_id),
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
    );
    ";
    
    $conn->exec($sql);
    
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->exec("INSERT INTO admins (username, password) VALUES 
        ('opal', '$admin_password'),
        ('ardi', '$admin_password'),
        ('fina', '$admin_password'),
        ('rifki', '$admin_password')");
    
    $conn->exec("INSERT INTO classes (name) VALUES 
        ('Kelas X IPA 1'),
        ('Kelas X IPA 2'),
        ('Kelas XI IPA 1'),
        ('Kelas XII IPA 1')");
    
    $teacher_password = password_hash('teacher123', PASSWORD_DEFAULT);
    $conn->exec("INSERT INTO teachers (name, email, password) VALUES 
        ('Budi Santoso', 'budi@teacher.com', '$teacher_password'),
        ('Siti Nurhaliza', 'siti@teacher.com', '$teacher_password'),
        ('Ahmad Hidayat', 'ahmad@teacher.com', '$teacher_password'),
        ('Dewi Lestari', 'dewi@teacher.com', '$teacher_password'),
        ('Eko Prasetyo', 'eko@teacher.com', '$teacher_password')");
    
    $student_password = password_hash('student123', PASSWORD_DEFAULT);
    $conn->exec("INSERT INTO students (student_id, name, password, class_id) VALUES 
        ('S001', 'Andi Wijaya', '$student_password', 1),
        ('S002', 'Rina Kusuma', '$student_password', 1),
        ('S003', 'Joko Susanto', '$student_password', 1),
        ('S004', 'Lina Marlina', '$student_password', 1),
        ('S005', 'Rudi Hartono', '$student_password', 1),
        ('S006', 'Maya Sari', '$student_password', 2),
        ('S007', 'Agus Salim', '$student_password', 2),
        ('S008', 'Fitri Handayani', '$student_password', 2),
        ('S009', 'Doni Kurniawan', '$student_password', 2),
        ('S010', 'Nina Anggraini', '$student_password', 2),
        ('S011', 'Hadi Purnomo', '$student_password', 3),
        ('S012', 'Sari Rahayu', '$student_password', 3),
        ('S013', 'Bambang Sutrisno', '$student_password', 3),
        ('S014', 'Tuti Wulandari', '$student_password', 3),
        ('S015', 'Yanto Saputra', '$student_password', 3),
        ('S016', 'Indah Permatasari', '$student_password', 4),
        ('S017', 'Fajar Ramadhan', '$student_password', 4),
        ('S018', 'Lia Amelia', '$student_password', 4),
        ('S019', 'Wahyu Nugroho', '$student_password', 4),
        ('S020', 'Ratna Dewi', '$student_password', 4)");
    
    $conn->exec("INSERT INTO status (name) VALUES 
        ('PENDING'),
        ('RUNNING'),
        ('COMPLETED')");
    
    echo "Database initialized successfully with secure password hashing!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
