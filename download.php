<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$file_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT filename FROM files WHERE id = ?");
$stmt->bind_param("i", $file_id);
$stmt->execute();
$stmt->bind_result($filepath);
$stmt->fetch();
$stmt->close();

if (file_exists($filepath)) {
    $conn->query("UPDATE files SET downloads = downloads + 1 WHERE id = $file_id");

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
    header('Content-Length: ' . filesize($filepath));
    readfile($filepath);
    exit;
} else {
    echo "File not found.";
}
?>
