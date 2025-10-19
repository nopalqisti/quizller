<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizller - Aplikasi Kuis Online</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller</h1>
        </div>
        
        <div class="login-container">
            <div class="card" style="max-width: 800px;">
                <h2 class="text-center" style="color: #667eea; margin-bottom: 40px;">Selamat Datang di Quizller</h2>
                <p class="text-center" style="margin-bottom: 40px; color: #666;">Silakan pilih role untuk login</p>
                
                <div class="role-selection">
                    <div class="role-card" onclick="window.location.href='admin-login.php'">
                        <h3>Admin</h3>
                        <p>Kelola seluruh sistem</p>
                        <a href="admin-login.php" class="btn btn-small">Login sebagai Admin</a>
                    </div>
                    
                    <div class="role-card" onclick="window.location.href='teacher-login.php'">
                        <h3>Teacher</h3>
                        <p>Buat dan kelola quiz</p>
                        <a href="teacher-login.php" class="btn btn-small btn-success">Login sebagai Teacher</a>
                    </div>
                    
                    <div class="role-card" onclick="window.location.href='student-login.php'">
                        <h3>Student</h3>
                        <p>Ikuti quiz dan lihat hasil</p>
                        <a href="student-login.php" class="btn btn-small btn-secondary">Login sebagai Student</a>
                    </div>
                </div>
                
                <p class="text-center mt-3">
                    <a href="teacher-register.php" class="link-text">Daftar sebagai Teacher</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
