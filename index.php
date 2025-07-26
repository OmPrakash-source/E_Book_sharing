<?php
session_start();
include 'db.php';

$result = $conn->query("SELECT files.id, files.title, files.filename, files.downloads, files.likes, users.name 
                        FROM files 
                        JOIN users ON files.user_id = users.id 
                        ORDER BY files.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>E-Book Sharing Platform</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h2>Welcome to E-Book Sharing Platform</h2>

    <?php if (isset($_SESSION['user'])): ?>
        <p>Hello, <?= $_SESSION['user']['name'] ?> 
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                | <a href="admin.php">Admin Panel</a>
            <?php endif; ?>
            | <a href="upload.php">Upload</a> 
            | <a href="logout.php">Logout</a>
        </p>
    <?php else: ?>
        <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
    <?php endif; ?>

    <h3>Available E-Books</h3>
    <table border="1" cellpadding="8">
        <tr>
            <th>Title</th>
            <th>Uploaded By</th>
            <th>Downloads</th>
            <th>Likes</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['downloads'] ?></td>
            <td><?= $row['likes'] ?></td>
            <td>
                <a href="download.php?id=<?= $row['id'] ?>">Download</a> |
                <a href="like.php?id=<?= $row['id'] ?>">Like</a>
                <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                    | <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this file?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
