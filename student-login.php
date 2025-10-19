<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = sanitize($_POST['student_id']);
    $password = sanitize($_POST['password']);
    $hashed_password = hashPassword($password);
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ? AND password = ?");
    $stmt->execute([$student_id, $hashed_password]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['name'];
        $_SESSION['student_class_id'] = $student['class_id'];
        header("Location: student/dashboard.php");
        exit();
    } else {
        $error = "ID Student atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Quizller</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller</h1>
            <a href="index.php">Kembali</a>
        </div>
        
        <div class="login-container">
            <div class="login-box">
                <h2>Login Student</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>ID Student</label>
                        <input type="text" name="student_id" placeholder="S001" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-secondary">Login</button>
                </form>
                
                <p class="text-center mt-3">
                    <small>Gunakan ID: S001-S020<br>Password: student123</small>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
