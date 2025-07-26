<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "Access Denied.";
    exit;
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$file_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT filename FROM files WHERE id = ?");
$stmt->bind_param("i", $file_id);
$stmt->execute();
$stmt->bind_result($filepath);

if ($stmt->fetch()) {
    $stmt->close();
    if (file_exists($filepath)) {
        unlink($filepath);
    }
    $del = $conn->prepare("DELETE FROM files WHERE id = ?");
    $del->bind_param("i", $file_id);
    $del->execute();
    header("Location: admin.php");
    exit;
} else {
    echo "File not found.";
}
?>
