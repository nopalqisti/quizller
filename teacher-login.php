<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE email = ?");
    $stmt->execute([$email]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($teacher && verifyPassword($password, $teacher['password'])) {
        $_SESSION['teacher_id'] = $teacher['id'];
        $_SESSION['teacher_name'] = $teacher['name'];
        $_SESSION['teacher_email'] = $teacher['email'];
        header("Location: teacher/dashboard.php");
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login - Quizller</title>
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
                <h2>Login Teacher</h2>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Login</button>
                </form>
                
                <p class="text-center mt-3">
                    Belum punya akun? <a href="teacher-register.php" class="link-text">Daftar di sini</a>
                </p>
                
                <p class="text-center mt-3">
                    <small>Contoh: budi@teacher.com / teacher123</small>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
