<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$conn = getDBConnection();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $student_id = sanitize($_POST['student_id']);
        $name = sanitize($_POST['name']);
        $password = sanitize($_POST['password']);
        $class_id = (int)$_POST['class_id'];
        
        if (empty($student_id) || empty($name) || empty($password) || $class_id <= 0) {
            $error = "Semua field harus diisi dengan benar!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM students WHERE student_id = ?");
            $stmt->execute([$student_id]);
            if ($stmt->fetch()) {
                $error = "ID Student sudah terdaftar!";
            } else {
                $hashed_password = hashPassword($password);
                $stmt = $conn->prepare("INSERT INTO students (student_id, name, password, class_id) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$student_id, $name, $hashed_password, $class_id])) {
                    $success = "Student berhasil ditambahkan!";
                } else {
                    $error = "Gagal menambahkan student!";
                }
            }
        }
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        if ($id <= 0) {
            $error = "ID tidak valid!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM students WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                $error = "Student tidak ditemukan!";
            } else {
                $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $success = "Student berhasil dihapus!";
                } else {
                    $error = "Gagal menghapus student!";
                }
            }
        }
    }
}

$stmt = $conn->query("SELECT s.*, c.name as class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id ORDER BY s.student_id");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->query("SELECT * FROM classes ORDER BY name");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Student - Quizller</title>
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
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h3>Tambah Student Baru</h3>
            <form method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 10px;">
                    <input type="text" name="student_id" placeholder="ID Student (S001)" required>
                    <input type="text" name="name" placeholder="Nama" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="class_id" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="add" class="btn btn-success">Tambah</button>
                </div>
            </form>
        </div>
        
        <div class="card">
            <h3>Daftar Student</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Student</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['class_name'] ?? '-'); ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus student ini?');">
                                    <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-small btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
