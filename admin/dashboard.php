<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$conn = getDBConnection();

// Fetch Statistics
$teacher_count = $conn->query("SELECT COUNT(*) FROM teachers")->fetchColumn();
$student_count = $conn->query("SELECT COUNT(*) FROM students")->fetchColumn();
$test_count = $conn->query("SELECT COUNT(*) FROM tests")->fetchColumn();
$class_count = $conn->query("SELECT COUNT(*) FROM classes")->fetchColumn();

// Fetch Recent Quizzes
$stmt = $conn->query("SELECT t.*, te.name as teacher_name, c.name as class_name, s.name as status_name 
                      FROM tests t 
                      JOIN teachers te ON t.teacher_id = te.id 
                      JOIN classes c ON t.class_id = c.id 
                      JOIN status s ON t.status_id = s.id 
                      ORDER BY t.date DESC LIMIT 5");
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
                <a href="../logout.php" class="btn btn-small btn-danger">Logout</a>
            </div>
        </div>
        
        <div class="dashboard-grid">
            
            <div class="dashboard-card">
                <div class="icon-box icon-box-green">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div class="dashboard-info">
                    <h3><?php echo $teacher_count; ?></h3>
                    <p>Total Teacher</p>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="icon-box icon-box-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div class="dashboard-info">
                    <h3><?php echo $student_count; ?></h3>
                    <p>Total Student</p>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="icon-box icon-box-yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.101.433-.101.66 0 .77.409 1.447 1.034 1.863a4.485 4.485 0 00-1.448 2.39c-.125.65-.406 1.23-.831 1.707-.584.655-1.413.987-2.287.987a2.25 2.25 0 01-2.25-2.25v-4.5c0-1.135.845-2.098 1.976-2.192.617-.049 1.251-.08 1.902-.08 1.132 0 2.176.455 2.922 1.188z" />
                    </svg>
                </div>
                <div class="dashboard-info">
                    <h3><?php echo $test_count; ?></h3>
                    <p>Total Quiz</p>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="icon-box icon-box-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                </div>
                <div class="dashboard-info">
                    <h3><?php echo $class_count; ?></h3>
                    <p>Total Kelas</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h3 style="margin-bottom: 20px;">Menu Admin</h3>
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