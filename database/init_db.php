<?php
// Initialize SQLite database
$db_path = __DIR__ . '/quizller.db';

// Delete existing database if exists
if (file_exists($db_path)) {
    unlink($db_path);
}

try {
    $conn = new PDO('sqlite:' . $db_path);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables
    $sql = "
    -- Table: admins
    CREATE TABLE admins (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    );
    
    -- Table: classes
    CREATE TABLE classes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL
    );
    
    -- Table: teachers
    CREATE TABLE teachers (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    );
    
    -- Table: students
    CREATE TABLE students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id VARCHAR(50) NOT NULL UNIQUE,
        name VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        class_id INTEGER,
        FOREIGN KEY (class_id) REFERENCES classes(id)
    );
    
    -- Table: status
    CREATE TABLE status (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL
    );
    
    -- Table: tests
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
    
    -- Table: questions
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
    
    -- Table: question_test_mapping
    CREATE TABLE question_test_mapping (
        question_id INTEGER NOT NULL,
        test_id INTEGER NOT NULL,
        PRIMARY KEY (question_id, test_id),
        FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
        FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE CASCADE
    );
    
    -- Table: student_answers
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
    
    -- Table: student_test_results
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
    
    // Insert default data
    // Admins (password: admin123)
    $conn->exec("INSERT INTO admins (username, password) VALUES 
        ('opal', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9'),
        ('ardi', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9'),
        ('fina', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9'),
        ('rifki', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9')");
    
    // Classes
    $conn->exec("INSERT INTO classes (name) VALUES 
        ('Kelas X IPA 1'),
        ('Kelas X IPA 2'),
        ('Kelas XI IPA 1'),
        ('Kelas XII IPA 1')");
    
    // Teachers (password: teacher123)
    $conn->exec("INSERT INTO teachers (name, email, password) VALUES 
        ('Budi Santoso', 'budi@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44'),
        ('Siti Nurhaliza', 'siti@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44'),
        ('Ahmad Hidayat', 'ahmad@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44'),
        ('Dewi Lestari', 'dewi@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44'),
        ('Eko Prasetyo', 'eko@teacher.com', 'c21b160bd3ed770f49e706f6b6596e08c00b67379ba0b5b8c84d2b8f3da89f44')");
    
    // Students (password: student123)
    $conn->exec("INSERT INTO students (student_id, name, password, class_id) VALUES 
        ('S001', 'Andi Wijaya', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
        ('S002', 'Rina Kusuma', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
        ('S003', 'Joko Susanto', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
        ('S004', 'Lina Marlina', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
        ('S005', 'Rudi Hartono', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 1),
        ('S006', 'Maya Sari', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
        ('S007', 'Agus Salim', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
        ('S008', 'Fitri Handayani', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
        ('S009', 'Doni Kurniawan', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
        ('S010', 'Nina Anggraini', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 2),
        ('S011', 'Hadi Purnomo', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
        ('S012', 'Sari Rahayu', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
        ('S013', 'Bambang Sutrisno', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
        ('S014', 'Tuti Wulandari', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
        ('S015', 'Yanto Saputra', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 3),
        ('S016', 'Indah Permatasari', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4),
        ('S017', 'Fajar Ramadhan', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4),
        ('S018', 'Lia Amelia', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4),
        ('S019', 'Wahyu Nugroho', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4),
        ('S020', 'Ratna Dewi', 'f5bb0c8de146c67b44babbf4e6584cc0dab1c3a3c0c55e0d6c98c8c1f00f1a77', 4)");
    
    // Status
    $conn->exec("INSERT INTO status (name) VALUES 
        ('PENDING'),
        ('RUNNING'),
        ('COMPLETED')");
    
    echo "Database initialized successfully!";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
