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
$existing_result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing_result && $existing_result['completed']) {
    header("Location: my-results.php?test_id=" . $test_id);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answers = $_POST['answers'] ?? [];
    $total_score = 0;
    
    if (!$existing_result) {
        $stmt = $conn->prepare("INSERT INTO student_test_results (student_id, test_id, score, completed) VALUES (?, ?, 0, 0)");
        $stmt->execute([$student_id, $test_id]);
    }
    
    $stmt = $conn->prepare("DELETE FROM student_answers WHERE student_id = ? AND test_id = ?");
    $stmt->execute([$student_id, $test_id]);
    
    foreach ($answers as $question_id => $answer) {
        $stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$question_id]);
        $question = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $is_correct = (strtolower($answer) == strtolower($question['correctAns'])) ? 1 : 0;
        
        if ($is_correct) {
            $total_score += $question['score'];
        }
        
        $stmt = $conn->prepare("INSERT INTO student_answers (student_id, test_id, question_id, answer, is_correct) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $test_id, $question_id, $answer, $is_correct]);
    }
    
    $stmt = $conn->prepare("UPDATE student_test_results SET score = ?, completed = 1, completed_at = datetime('now') WHERE student_id = ? AND test_id = ?");
    $stmt->execute([$total_score, $student_id, $test_id]);
    
    header("Location: my-results.php?test_id=" . $test_id);
    exit();
}

$stmt = $conn->prepare("SELECT q.* FROM questions q 
                        JOIN question_test_mapping qtm ON q.id = qtm.question_id 
                        WHERE qtm.test_id = ?
                        ORDER BY q.id");
$stmt->execute([$test_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerjakan Quiz - Quizller</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="navbar">
            <h1>Quizller - Student</h1>
            <div>
                <a href="dashboard.php" style="margin-right: 10px;">Kembali</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        
        <div class="card">
            <h3><?php echo htmlspecialchars($test['name']); ?></h3>
            <p><strong>Mata Pelajaran:</strong> <?php echo htmlspecialchars($test['subject']); ?></p>
            <p><strong>Jumlah Soal:</strong> <?php echo count($questions); ?></p>
        </div>
        
        <form method="POST" onsubmit="return confirm('Yakin ingin submit jawaban? Pastikan semua soal sudah dijawab.');">
            <?php $no = 1; foreach ($questions as $question): ?>
                <div class="quiz-question">
                    <h4>Soal #<?php echo $no++; ?> (<?php echo $question['score']; ?> poin)</h4>
                    <p><?php echo htmlspecialchars($question['title']); ?></p>
                    
                    <label class="quiz-option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="a" required>
                        A. <?php echo htmlspecialchars($question['optionA']); ?>
                    </label>
                    
                    <label class="quiz-option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="b" required>
                        B. <?php echo htmlspecialchars($question['optionB']); ?>
                    </label>
                    
                    <label class="quiz-option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="c" required>
                        C. <?php echo htmlspecialchars($question['optionC']); ?>
                    </label>
                    
                    <label class="quiz-option">
                        <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="d" required>
                        D. <?php echo htmlspecialchars($question['optionD']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
            
            <div class="card">
                <button type="submit" class="btn btn-success">Submit Jawaban</button>
            </div>
        </form>
    </div>
</body>
</html>
