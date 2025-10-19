<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireTeacherLogin();

$test_id = isset($_GET['test_id']) ? (int)$_GET['test_id'] : 0;

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ? AND teacher_id = ?");
$stmt->execute([$test_id, $_SESSION['teacher_id']]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    header("Location: dashboard.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_question'])) {
        $title = sanitize($_POST['title']);
        $optionA = sanitize($_POST['optionA']);
        $optionB = sanitize($_POST['optionB']);
        $optionC = sanitize($_POST['optionC']);
        $optionD = sanitize($_POST['optionD']);
        $correctAns = sanitize($_POST['correctAns']);
        $score = (int)$_POST['score'];
        
        $stmt = $conn->prepare("INSERT INTO questions (title, optionA, optionB, optionC, optionD, correctAns, score) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $optionA, $optionB, $optionC, $optionD, $correctAns, $score])) {
            $question_id = $conn->lastInsertId();
            
            $stmt = $conn->prepare("INSERT INTO question_test_mapping (question_id, test_id) VALUES (?, ?)");
            $stmt->execute([$question_id, $test_id]);
            
            $stmt = $conn->prepare("UPDATE tests SET total_questions = total_questions + 1 WHERE id = ?");
            $stmt->execute([$test_id]);
            
            $success = "Soal berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan soal!";
        }
    }
}

$stmt = $conn->prepare("SELECT q.* FROM questions q 
                        JOIN question_test_mapping qtm ON q.id = qtm.question_id 
                        WHERE qtm.test_id = ?");
$stmt->execute([$test_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Soal - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Teacher</h1>
            <div>
                <a href="my-tests.php" style="margin-right: 10px;">Kembali</a>
                <a href="dashboard.php" style="margin-right: 10px;">Dashboard</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h3>Quiz: <?php echo htmlspecialchars($test['name']); ?></h3>
            <p>Total Soal: <?php echo count($questions); ?></p>
        </div>
        
        <div class="card">
            <h3>Tambah Soal Baru</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Pertanyaan</label>
                    <textarea name="title" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Pilihan A</label>
                    <input type="text" name="optionA" required>
                </div>
                
                <div class="form-group">
                    <label>Pilihan B</label>
                    <input type="text" name="optionB" required>
                </div>
                
                <div class="form-group">
                    <label>Pilihan C</label>
                    <input type="text" name="optionC" required>
                </div>
                
                <div class="form-group">
                    <label>Pilihan D</label>
                    <input type="text" name="optionD" required>
                </div>
                
                <div class="form-group">
                    <label>Jawaban Benar</label>
                    <select name="correctAns" required>
                        <option value="">Pilih Jawaban</option>
                        <option value="a">A</option>
                        <option value="b">B</option>
                        <option value="c">C</option>
                        <option value="d">D</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Skor</label>
                    <input type="number" name="score" value="10" required>
                </div>
                
                <button type="submit" name="add_question" class="btn btn-success">Tambah Soal</button>
            </form>
        </div>
        
        <div class="card">
            <h3>Daftar Soal (<?php echo count($questions); ?>)</h3>
            <?php if (count($questions) > 0): ?>
                <?php $no = 1; foreach ($questions as $q): ?>
                    <div style="background: #f8f9fa; padding: 15px; margin-bottom: 15px; border-radius: 8px;">
                        <h4>Soal #<?php echo $no++; ?> (<?php echo $q['score']; ?> poin)</h4>
                        <p><strong><?php echo htmlspecialchars($q['title']); ?></strong></p>
                        <p>A. <?php echo htmlspecialchars($q['optionA']); ?></p>
                        <p>B. <?php echo htmlspecialchars($q['optionB']); ?></p>
                        <p>C. <?php echo htmlspecialchars($q['optionC']); ?></p>
                        <p>D. <?php echo htmlspecialchars($q['optionD']); ?></p>
                        <p><strong>Jawaban: </strong><?php echo strtoupper($q['correctAns']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada soal.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
