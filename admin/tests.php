<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$conn = getDBConnection();

$stmt = $conn->query("SELECT t.*, te.name as teacher_name, c.name as class_name, s.name as status_name 
                      FROM tests t 
                      JOIN teachers te ON t.teacher_id = te.id 
                      JOIN classes c ON t.class_id = c.id 
                      JOIN status s ON t.status_id = s.id 
                      ORDER BY t.date DESC");
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Quiz - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Admin</h1>
            <div>
                <a href="dashboard.php" style="margin-right: 10px;">Dashboard</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <div class="card">
            <h3>Daftar Semua Quiz</h3>
            <?php if (count($tests) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Quiz</th>
                            <th>Teacher</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Jumlah Soal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tests as $test): ?>
                            <tr>
                                <td><?php echo $test['id']; ?></td>
                                <td><?php echo htmlspecialchars($test['name']); ?></td>
                                <td><?php echo htmlspecialchars($test['teacher_name']); ?></td>
                                <td><?php echo htmlspecialchars($test['subject']); ?></td>
                                <td><?php echo htmlspecialchars($test['class_name']); ?></td>
                                <td><?php echo formatDate($test['date']); ?></td>
                                <td><?php echo $test['total_questions']; ?></td>
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
