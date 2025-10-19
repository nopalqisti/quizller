<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireTeacherLogin();

$test_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT t.*, c.name as class_name, s.name as status_name 
                        FROM tests t 
                        JOIN classes c ON t.class_id = c.id 
                        JOIN status s ON t.status_id = s.id 
                        WHERE t.id = ? AND t.teacher_id = ?");
$stmt->execute([$test_id, $_SESSION['teacher_id']]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    header("Location: my-tests.php");
    exit();
}

$stmt = $conn->prepare("SELECT str.*, s.student_id, s.name as student_name 
                        FROM student_test_results str 
                        JOIN students s ON str.student_id = s.id 
                        WHERE str.test_id = ? AND str.completed = 1
                        ORDER BY str.score DESC");
$stmt->execute([$test_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_score = 0;
$total_students = count($results);
foreach ($results as $result) {
    $total_score += $result['score'];
}
$average_score = $total_students > 0 ? round($total_score / $total_students, 2) : 0;

$stmt = $conn->prepare("SELECT SUM(score) as total FROM questions q 
                        JOIN question_test_mapping qtm ON q.id = qtm.question_id 
                        WHERE qtm.test_id = ?");
$stmt->execute([$test_id]);
$max_score = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
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
            <h1>Quizller - Teacher</h1>
            <div>
                <a href="my-tests.php" style="margin-right: 10px;">Kembali</a>
                <a href="dashboard.php" style="margin-right: 10px;">Dashboard</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <div class="card">
            <h3>Detail Quiz: <?php echo htmlspecialchars($test['name']); ?></h3>
            <p><strong>Kelas:</strong> <?php echo htmlspecialchars($test['class_name']); ?></p>
            <p><strong>Tanggal:</strong> <?php echo formatDate($test['date']); ?></p>
            <p><strong>Jumlah Soal:</strong> <?php echo $test['total_questions']; ?></p>
            <p><strong>Skor Maksimal:</strong> <?php echo $max_score; ?></p>
        </div>
        
        <div class="stats-container">
            <h3>Statistik Hasil</h3>
            <div class="dashboard-grid" style="margin-bottom: 30px;">
                <div class="dashboard-card">
                    <h3><?php echo $total_students; ?></h3>
                    <p>Student Mengerjakan</p>
                </div>
                <div class="dashboard-card">
                    <h3><?php echo $average_score; ?></h3>
                    <p>Rata-rata Nilai</p>
                </div>
                <div class="dashboard-card">
                    <h3><?php echo $max_score; ?></h3>
                    <p>Nilai Maksimal</p>
                </div>
                <div class="dashboard-card">
                    <h3><?php echo $total_students > 0 ? round(($average_score / $max_score) * 100, 2) : 0; ?>%</h3>
                    <p>Persentase Rata-rata</p>
                </div>
            </div>
            
            <?php if (count($results) > 0): ?>
                <canvas id="scoreChart" style="max-height: 400px;"></canvas>
            <?php endif; ?>
        </div>
        
        <div class="card">
            <h3>Hasil Student</h3>
            <?php if (count($results) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Student</th>
                            <th>Nama Student</th>
                            <th>Skor</th>
                            <th>Persentase</th>
                            <th>Selesai Pada</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $result): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($result['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($result['student_name']); ?></td>
                                <td><?php echo $result['score']; ?> / <?php echo $max_score; ?></td>
                                <td><?php echo round(($result['score'] / $max_score) * 100, 2); ?>%</td>
                                <td><?php echo date('d M Y H:i', strtotime($result['completed_at'])); ?></td>
                                <td>
                                    <a href="student-answers.php?test_id=<?php echo $test_id; ?>&student_id=<?php echo $result['student_id']; ?>" class="btn btn-small">Lihat Jawaban</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada student yang mengerjakan quiz ini.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (count($results) > 0): ?>
    <script>
        const ctx = document.getElementById('scoreChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo '"' . implode('", "', array_map(function($r) { return $r['student_name']; }, $results)) . '"'; ?>],
                datasets: [{
                    label: 'Skor',
                    data: [<?php echo implode(', ', array_map(function($r) { return $r['score']; }, $results)); ?>],
                    backgroundColor: 'rgba(102, 126, 234, 0.6)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: <?php echo $max_score; ?>
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
