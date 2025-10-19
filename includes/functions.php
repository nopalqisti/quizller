<?php

function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function isTeacherLoggedIn() {
    return isset($_SESSION['teacher_id']);
}

function isStudentLoggedIn() {
    return isset($_SESSION['student_id']);
}

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

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function getStatusName($status_id, $conn) {
    $stmt = $conn->prepare("SELECT name FROM status WHERE id = ?");
    $stmt->execute([$status_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['name'] : 'Unknown';
}

function getClassName($class_id, $conn) {
    $stmt = $conn->prepare("SELECT name FROM classes WHERE id = ?");
    $stmt->execute([$class_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['name'] : 'Unknown';
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}
?>
