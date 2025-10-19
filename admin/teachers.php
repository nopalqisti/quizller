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
        $email = sanitize($_POST['email']);
        $password = sanitize($_POST['password']);
        
        if (empty($name) || empty($email) || empty($password)) {
            $error = "Semua field harus diisi!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM teachers WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Email sudah terdaftar!";
            } else {
                $hashed_password = hashPassword($password);
                $stmt = $conn->prepare("INSERT INTO teachers (name, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashed_password])) {
                    $success = "Teacher berhasil ditambahkan!";
                } else {
                    $error = "Gagal menambahkan teacher!";
                }
            }
        }
    } elseif (isset($_POST['delete'])) {
        $id = (int)$_POST['id'];
        if ($id <= 0) {
            $error = "ID tidak valid!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM teachers WHERE id = ?");
            $stmt->execute([$id]);
            if (!$stmt->fetch()) {
                $error = "Teacher tidak ditemukan!";
            } else {
                $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
                if ($stmt->execute([$id])) {
                    $success = "Teacher berhasil dihapus!";
                } else {
                    $error = "Gagal menghapus teacher!";
                }
            }
        }
    }
}

$stmt = $conn->query("SELECT * FROM teachers ORDER BY name");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Teacher - Quizller</title>
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
            <h3>Tambah Teacher Baru</h3>
            <form method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 10px;">
                    <input type="text" name="name" placeholder="Nama" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="add" class="btn btn-success">Tambah</button>
                </div>
            </form>
        </div>
        
        <div class="card">
            <h3>Daftar Teacher</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?php echo $teacher['id']; ?></td>
                            <td><?php echo htmlspecialchars($teacher['name']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus teacher ini?');">
                                    <input type="hidden" name="id" value="<?php echo $teacher['id']; ?>">
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
