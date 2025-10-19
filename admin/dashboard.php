<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$conn = getDBConnection();

$stmt = $conn->query("SELECT COUNT(*) as count FROM teachers");
$teacher_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $conn->query("SELECT COUNT(*) as count FROM students");
$student_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $conn->query("SELECT COUNT(*) as count FROM tests");
$test_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $conn->query("SELECT COUNT(*) as count FROM classes");
$class_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt = $conn->query("SELECT t.*, te.name as teacher_name, c.name as class_name, s.name as status_name 
                      FROM tests t 
                      JOIN teachers te ON t.teacher_id = te.id 
                      JOIN classes c ON t.class_id = c.id 
                      JOIN status s ON t.status_id = s.id 
                      ORDER BY t.date DESC LIMIT 10");
$recent_tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Admin</h1>
            <div>
                <span style="color: white; margin-right: 20px;">Hi, <?php echo $_SESSION['admin_username']; ?></span>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3><?php echo $teacher_count; ?></h3>
                <p>Total Teacher</p>
            </div>
            <div class="dashboard-card">
                <h3><?php echo $student_count; ?></h3>
                <p>Total Student</p>
            </div>
            <div class="dashboard-card">
                <h3><?php echo $test_count; ?></h3>
                <p>Total Quiz</p>
            </div>
            <div class="dashboard-card">
                <h3><?php echo $class_count; ?></h3>
                <p>Total Kelas</p>
            </div>
        </div>
        
        <div class="card">
            <h3>Menu Admin</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="teachers.php" class="btn btn-success">Kelola Teacher</a>
                <a href="students.php" class="btn btn-secondary">Kelola Student</a>
                <a href="classes.php" class="btn">Kelola Kelas</a>
                <a href="tests.php" class="btn btn-danger">Kelola Quiz</a>
            </div>
        </div>
        
        <div class="card">
            <h3>Quiz Terbaru</h3>
            <?php if (count($recent_tests) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Quiz</th>
                            <th>Teacher</th>
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
                                <td><?php echo htmlspecialchars($test['teacher_name']); ?></td>
                                <td><?php echo htmlspecialchars($test['class_name']); ?></td>
                                <td><?php echo formatDate($test['date']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower($test['status_name']); ?>">
                                        <?php echo $test['status_name']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="test-results.php?id=<?php echo $test['id']; ?>" class="btn btn-small">Lihat Hasil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada quiz.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
