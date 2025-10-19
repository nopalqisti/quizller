<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

requireAdminLogin();

$conn = getDBConnection();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $name = sanitize($_POST['name']);
        
        if (empty($name)) {
            $error = "Nama kelas harus diisi!";
        } else {
            $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
            if ($stmt->execute([$name])) {
                $success = "Kelas berhasil ditambahkan!";
            } else {
                $error = "Gagal menambahkan kelas!";
            }
        }
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        if ($id <= 0) {
            $error = "ID tidak valid!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM classes WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                $error = "Kelas tidak ditemukan!";
            } else {
                $stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $success = "Kelas berhasil dihapus!";
                } else {
                    $error = "Gagal menghapus kelas!";
                }
            }
        }
    }
}

$stmt = $conn->query("SELECT c.*, COUNT(s.id) as student_count FROM classes c LEFT JOIN students s ON c.id = s.class_id GROUP BY c.id ORDER BY c.name");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas - Quizller</title>
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
            <h3>Tambah Kelas Baru</h3>
            <form method="POST">
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 10px;">
                    <input type="text" name="name" placeholder="Nama Kelas" required>
                    <button type="submit" name="add" class="btn btn-success">Tambah</button>
                </div>
            </form>
        </div>
        
        <div class="card">
            <h3>Daftar Kelas</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kelas</th>
                        <th>Jumlah Student</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?php echo $class['id']; ?></td>
                            <td><?php echo htmlspecialchars($class['name']); ?></td>
                            <td><?php echo $class['student_count']; ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                                    <input type="hidden" name="id" value="<?php echo $class['id']; ?>">
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
