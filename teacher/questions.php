<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireTeacherLogin();

$conn = getDBConnection();

$stmt = $conn->query("SELECT q.*, COUNT(qtm.test_id) as used_count 
                      FROM questions q 
                      LEFT JOIN question_test_mapping qtm ON q.id = qtm.question_id 
                      LEFT JOIN tests t ON qtm.test_id = t.id AND t.teacher_id = " . $_SESSION['teacher_id'] . "
                      WHERE t.teacher_id = " . $_SESSION['teacher_id'] . " OR t.teacher_id IS NULL
                      GROUP BY q.id
                      ORDER BY q.id DESC");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Soal - Quizller</title>
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
        
        <div class="card">
            <h3>Bank Soal Saya</h3>
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
                <p>Belum ada soal di bank soal Anda. Buat quiz baru dan tambahkan soal untuk menyimpannya di bank soal.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
