<?php
// Common functions

// Sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if user is logged in as admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Check if user is logged in as teacher
function isTeacherLoggedIn() {
    return isset($_SESSION['teacher_id']);
}

// Check if user is logged in as student
function isStudentLoggedIn() {
    return isset($_SESSION['student_id']);
}

// Redirect to login if not logged in
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: /admin-login.php");
        exit();
    }
}

function requireTeacherLogin() {
    if (!isTeacherLoggedIn()) {
        header("Location: /teacher-login.php");
        exit();
    }
}

function requireStudentLogin() {
    if (!isStudentLoggedIn()) {
        header("Location: /student-login.php");
        exit();
    }
}

// Hash password
function hashPassword($password) {
    return hash('sha256', $password);
}

// Get status name by ID
function getStatusName($status_id, $conn) {
    $sql = "SELECT name FROM status WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $status_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['name'] : 'Unknown';
}

// Get class name by ID
function getClassName($class_id, $conn) {
    $sql = "SELECT name FROM classes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['name'] : 'Unknown';
}

// Format date
function formatDate($date) {
    return date('d M Y', strtotime($date));
}
?>
