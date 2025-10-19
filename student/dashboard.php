<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireStudentLogin();

$conn = getDBConnection();
$student_id = $_SESSION['student_id'];
$class_id = $_SESSION['student_class_id'];

$stmt = $conn->prepare("SELECT t.*, s.name as status_name, c.name as class_name 
                        FROM tests t 
                        JOIN status s ON t.status_id = s.id 
                        JOIN classes c ON t.class_id = c.id
                        WHERE t.class_id = ? 
                        ORDER BY t.date DESC");
$stmt->execute([$class_id]);
$available_tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM student_test_results WHERE student_id = ? AND completed = 1");
$stmt->execute([$student_id]);
$completed_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Student</h1>
            <div>
                <span style="color: white; margin-right: 20px;">Hi, <?php echo $_SESSION['student_name']; ?></span>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3><?php echo count($available_tests); ?></h3>
                <p>Quiz Tersedia</p>
            </div>
            <div class="dashboard-card">
                <h3><?php echo $completed_count; ?></h3>
                <p>Quiz Selesai</p>
            </div>
        </div>
        
        <div class="card">
            <h3>Quiz Tersedia</h3>
            <?php if (count($available_tests) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Quiz</th>
                            <th>Mata Pelajaran</th>
                            <th>Tanggal</th>
                            <th>Jumlah Soal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($available_tests as $test): ?>
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM student_test_results WHERE student_id = ? AND test_id = ?");
                            $stmt->execute([$student_id, $test['id']]);
                            $my_result = $stmt->fetch(PDO::FETCH_ASSOC);
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($test['name']); ?></td>
                                <td><?php echo htmlspecialchars($test['subject']); ?></td>
                                <td><?php echo formatDate($test['date']); ?></td>
                                <td><?php echo $test['total_questions']; ?></td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower($test['status_name']); ?>">
                                        <?php echo $test['status_name']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($my_result && $my_result['completed']): ?>
                                        <a href="my-results.php?test_id=<?php echo $test['id']; ?>" class="btn btn-small">Lihat Hasil</a>
                                    <?php else: ?>
                                        <a href="take-quiz.php?test_id=<?php echo $test['id']; ?>" class="btn btn-small btn-success">Mulai Quiz</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada quiz tersedia untuk kelas Anda.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
