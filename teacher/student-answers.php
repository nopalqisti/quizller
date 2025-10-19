<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireTeacherLogin();

$test_id = isset($_GET['test_id']) ? (int)$_GET['test_id'] : 0;
$student_id_param = isset($_GET['student_id']) ? $_GET['student_id'] : '';

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ? AND teacher_id = ?");
$stmt->execute([$test_id, $_SESSION['teacher_id']]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    header("Location: my-tests.php");
    exit();
}

$stmt = $conn->prepare("SELECT id FROM students WHERE student_id = ?");
$stmt->execute([$student_id_param]);
$student_row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student_row) {
    header("Location: test-results.php?id=" . $test_id);
    exit();
}

$student_internal_id = $student_row['id'];

$stmt = $conn->prepare("SELECT s.*, c.name as class_name FROM students s 
                        LEFT JOIN classes c ON s.class_id = c.id 
                        WHERE s.id = ?");
$stmt->execute([$student_internal_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT sa.*, q.title, q.optionA, q.optionB, q.optionC, q.optionD, q.correctAns, q.score 
                        FROM student_answers sa 
                        JOIN questions q ON sa.question_id = q.id 
                        WHERE sa.student_id = ? AND sa.test_id = ?
                        ORDER BY sa.question_id");
$stmt->execute([$student_internal_id, $test_id]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT * FROM student_test_results WHERE student_id = ? AND test_id = ?");
$stmt->execute([$student_internal_id, $test_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$correct_count = 0;
$wrong_count = 0;
foreach ($answers as $answer) {
    if ($answer['is_correct']) {
        $correct_count++;
    } else {
        $wrong_count++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Jawaban - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Teacher</h1>
            <div>
                <a href="test-results.php?id=<?php echo $test_id; ?>" style="margin-right: 10px;">Kembali</a>
                <a href="dashboard.php" style="margin-right: 10px;">Dashboard</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <div class="card">
            <h3>Informasi Student</h3>
            <table style="width: 100%; margin-bottom: 20px;">
                <tr>
                    <td><strong>ID Student:</strong></td>
                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                </tr>
                <tr>
                    <td><strong>Nama:</strong></td>
                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Kelas:</strong></td>
                    <td><?php echo htmlspecialchars($student['class_name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Quiz:</strong></td>
                    <td><?php echo htmlspecialchars($test['name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Total Skor:</strong></td>
                    <td><?php echo $result['score']; ?></td>
                </tr>
            </table>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3 style="color: #28a745;"><?php echo $correct_count; ?></h3>
                    <p>Jawaban Benar</p>
                </div>
                <div class="dashboard-card">
                    <h3 style="color: #dc3545;"><?php echo $wrong_count; ?></h3>
                    <p>Jawaban Salah</p>
                </div>
                <div class="dashboard-card">
                    <h3 style="color: #667eea;"><?php echo count($answers); ?></h3>
                    <p>Total Soal</p>
                </div>
                <div class="dashboard-card">
                    <h3 style="color: #667eea;"><?php echo count($answers) > 0 ? round(($correct_count / count($answers)) * 100, 2) : 0; ?>%</h3>
                    <p>Akurasi</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h3>Detail Jawaban</h3>
            <?php if (count($answers) > 0): ?>
                <?php $no = 1; foreach ($answers as $answer): ?>
                    <div class="answer-review <?php echo $answer['is_correct'] ? 'correct' : 'wrong'; ?>">
                        <h4>Soal #<?php echo $no++; ?> (<?php echo $answer['score']; ?> poin)</h4>
                        <p><strong><?php echo htmlspecialchars($answer['title']); ?></strong></p>
                        <p>A. <?php echo htmlspecialchars($answer['optionA']); ?></p>
                        <p>B. <?php echo htmlspecialchars($answer['optionB']); ?></p>
                        <p>C. <?php echo htmlspecialchars($answer['optionC']); ?></p>
                        <p>D. <?php echo htmlspecialchars($answer['optionD']); ?></p>
                        <p><strong>Jawaban Student: </strong><?php echo strtoupper($answer['answer'] ?? 'Tidak dijawab'); ?></p>
                        <p><strong>Jawaban Benar: </strong><?php echo strtoupper($answer['correctAns']); ?></p>
                        <p><strong>Status: </strong>
                            <?php if ($answer['is_correct']): ?>
                                <span style="color: #28a745; font-weight: bold;">✓ BENAR</span>
                            <?php else: ?>
                                <span style="color: #dc3545; font-weight: bold;">✗ SALAH</span>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada jawaban.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
