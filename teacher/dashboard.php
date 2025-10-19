<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireTeacherLogin();

$conn = getDBConnection();
$teacher_id = $_SESSION['teacher_id'];

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM tests WHERE teacher_id = ?");
$stmt->execute([$teacher_id]);
$my_tests_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $conn->prepare("SELECT t.*, c.name as class_name, s.name as status_name 
                        FROM tests t 
                        JOIN classes c ON t.class_id = c.id 
                        JOIN status s ON t.status_id = s.id 
                        WHERE t.teacher_id = ? 
                        ORDER BY t.date DESC LIMIT 10");
$stmt->execute([$teacher_id]);
$recent_tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Teacher</h1>
            <div>
                <span style="color: white; margin-right: 20px;">Hi, <?php echo $_SESSION['teacher_name']; ?></span>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3><?php echo $my_tests_count; ?></h3>
                <p>Quiz Saya</p>
            </div>
        </div>
        
        <div class="card">
            <h3>Menu Teacher</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="create-test.php" class="btn btn-success">Buat Quiz Baru</a>
                <a href="my-tests.php" class="btn">Quiz Saya</a>
                <a href="questions.php" class="btn btn-secondary">Bank Soal</a>
            </div>
        </div>
        
        <div class="card">
            <h3>Quiz Terbaru Saya</h3>
            <?php if (count($recent_tests) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Quiz</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_tests as $test): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($test['name']); ?></td>
                                <td><?php echo htmlspecialchars($test['class_name']); ?></td>
                                <td><?php echo formatDate($test['date']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower($test['status_name']); ?>">
                                        <?php echo $test['status_name']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="test-results.php?id=<?php echo $test['id']; ?>" class="btn btn-small">Lihat Hasil</a>
                                    <a href="edit-test.php?id=<?php echo $test['id']; ?>" class="btn btn-small btn-success">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada quiz. <a href="create-test.php" class="link-text">Buat quiz baru</a></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
