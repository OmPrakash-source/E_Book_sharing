<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit;
}

$file_id = intval($_GET['id']);
$user_id = $_SESSION['user']['id'];

$check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND file_id = ?");
$check->bind_param("ii", $user_id, $file_id);
$check->execute();
$check->store_result();

if ($check->num_rows == 0) {
    $conn->query("UPDATE files SET likes = likes + 1 WHERE id = $file_id");
    $insert = $conn->prepare("INSERT INTO likes (user_id, file_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $file_id);
    $insert->execute();
}

header("Location: index.php");
