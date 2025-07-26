<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo "<h2>Access Denied. Admins only.</h2>";
    exit;
}

$result = $conn->query("SELECT files.id, files.title, files.filename, files.downloads, files.likes, users.name 
                        FROM files 
                        JOIN users ON files.user_id = users.id 
                        ORDER BY files.id DESC");
?>

<!DOCTYPE html>
<html>
<head><title>Admin Dashboard</title></head>
<link rel = "stylesheet" href = "admin.css">
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?= $_SESSION['user']['name'] ?> | <a href="logout.php">Logout</a></p>
    <a href="index.php">← Back to Home</a>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Uploader</th>
            <th>Downloads</th>
            <th>Likes</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['downloads'] ?></td>
            <td><?= $row['likes'] ?></td>
            <td>
                <a href="download.php?id=<?= $row['id'] ?>">Download</a>---------------------
                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this file?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
