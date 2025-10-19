<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireTeacherLogin();

$conn = getDBConnection();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $subject = sanitize($_POST['subject']);
    $date = sanitize($_POST['date']);
    $class_id = $_POST['class_id'];
    $teacher_id = $_SESSION['teacher_id'];
    $status_id = 1;
    
    $stmt = $conn->prepare("INSERT INTO tests (teacher_id, name, date, status_id, subject, total_questions, class_id) VALUES (?, ?, ?, ?, ?, 0, ?)");
    if ($stmt->execute([$teacher_id, $name, $date, $status_id, $subject, $class_id])) {
        $test_id = $conn->lastInsertId();
        header("Location: add-questions.php?test_id=" . $test_id);
        exit();
    } else {
        $error = "Gagal membuat quiz!";
    }
}

$stmt = $conn->query("SELECT * FROM classes ORDER BY name");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Quiz - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Teacher</h1>
            <div>
                <a href="dashboard.php" style="margin-right: 10px;">Dashboard</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h3>Buat Quiz Baru</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Nama Quiz</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <input type="text" name="subject" required>
                </div>
                
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="date" required>
                </div>
                
                <div class="form-group">
                    <label>Kelas</label>
                    <select name="class_id" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-success">Buat Quiz & Tambah Soal</button>
            </form>
        </div>
    </div>
</body>
</html>
