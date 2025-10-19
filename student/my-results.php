<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireStudentLogin();

$test_id = isset($_GET['test_id']) ? (int)$_GET['test_id'] : 0;
$student_id = $_SESSION['student_id'];

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT * FROM tests WHERE id = ?");
$stmt->execute([$test_id]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    header("Location: dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM student_test_results WHERE student_id = ? AND test_id = ?");
$stmt->execute([$student_id, $test_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result || !$result['completed']) {
    header("Location: dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT sa.*, q.title, q.optionA, q.optionB, q.optionC, q.optionD, q.correctAns, q.score 
                        FROM student_answers sa 
                        JOIN questions q ON sa.question_id = q.id 
                        WHERE sa.student_id = ? AND sa.test_id = ?
                        ORDER BY sa.question_id");
$stmt->execute([$student_id, $test_id]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT SUM(score) as total FROM questions q 
                        JOIN question_test_mapping qtm ON q.id = qtm.question_id 
                        WHERE qtm.test_id = ?");
$stmt->execute([$test_id]);
$max_score = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$correct_count = 0;
$wrong_count = 0;
foreach ($answers as $answer) {
    if ($answer['is_correct']) {
        $correct_count++;
    } else {
        $wrong_count++;
    }
}

$percentage = $max_score > 0 ? round(($result['score'] / $max_score) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Quiz - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Student</h1>
            <div>
                <a href="dashboard.php" style="margin-right: 10px;">Dashboard</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <div class="card">
            <h3>Hasil Quiz: <?php echo htmlspecialchars($test['name']); ?></h3>
            <div class="dashboard-grid" style="margin-top: 20px;">
                <div class="dashboard-card">
                    <h3><?php echo $result['score']; ?></h3>
                    <p>Total Skor</p>
                </div>
                <div class="dashboard-card">
                    <h3><?php echo $max_score; ?></h3>
                    <p>Skor Maksimal</p>
                </div>
                <div class="dashboard-card">
                    <h3><?php echo $percentage; ?>%</h3>
                    <p>Persentase</p>
                </div>
                <div class="dashboard-card">
                    <h3 style="color: <?php echo $percentage >= 75 ? '#28a745' : ($percentage >= 60 ? '#ffc107' : '#dc3545'); ?>">
                        <?php 
                        if ($percentage >= 75) echo 'LULUS';
                        else if ($percentage >= 60) echo 'CUKUP';
                        else echo 'KURANG';
                        ?>
                    </h3>
                    <p>Status</p>
                </div>
            </div>
        </div>
        
        <div class="stats-container">
            <h3>Statistik Jawaban</h3>
            <div style="max-width: 400px; margin: 0 auto;">
                <canvas id="answerChart"></canvas>
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
                        <p><strong>Jawaban Anda: </strong><?php echo strtoupper($answer['answer'] ?? 'Tidak dijawab'); ?></p>
                        <p><strong>Jawaban Benar: </strong><?php echo strtoupper($answer['correctAns']); ?></p>
                        <p><strong>Status: </strong>
                            <?php if ($answer['is_correct']): ?>
                                <span style="color: #28a745; font-weight: bold;">✓ BENAR (+<?php echo $answer['score']; ?> poin)</span>
                            <?php else: ?>
                                <span style="color: #dc3545; font-weight: bold;">✗ SALAH</span>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        const ctx = document.getElementById('answerChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Benar', 'Salah'],
                datasets: [{
                    data: [<?php echo $correct_count; ?>, <?php echo $wrong_count; ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
