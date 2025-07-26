<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $file = $_FILES['file'];
    $user_id = $_SESSION['user']['id'];

    $folder = __DIR__ . "/uploads/";
    if (!file_exists($folder)) mkdir($folder);

    $filename = time() . "_" . basename($file['name']);
    $targetPath = $folder . $filename;
    $relativePath = "uploads/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $stmt = $conn->prepare("INSERT INTO files (user_id, title, filename) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $title, $relativePath);
        if ($stmt->execute()) {
            $msg = "File uploaded successfully!";
        } else {
            $error = "Database error.";
        }
    } else {
        $error = "File upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Upload E-Book</title></head>
<link rel = "stylesheet" href = "upload.css">
<body>
    <h2>Upload PDF</h2>
    <?php if (isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required><br><br>

    <label>File (PDF):</label>
    <input type="file" name="file" accept="application/pdf" required><br><br>

    <button type="submit">Upload</button>
    <div class = "ancore">
        <a href="index.php">&larr; Back</a>
    </div>
</form>
</body>
</html>
