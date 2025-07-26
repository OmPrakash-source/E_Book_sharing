<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // ✅ Fetch user by email
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed, $role);
        $stmt->fetch();

        // ✅ Verify password
        if (password_verify($pass, $hashed)) {

            // ✅ Auto-promote to admin if specific email
            if ($email === 'omjhade12@gmail.com' && $role !== 'admin') {
                $conn->query("UPDATE users SET role = 'admin' WHERE email = '$email'");
                $role = 'admin'; // update role for this session
            }

            // ✅ Save session
            $_SESSION['user'] = [
                'id' => $id,
                'name' => $name,
                'role' => $role
            ];

            // ✅ Redirect based on role
            header("Location: " . ($role === 'admin' ? 'admin.php' : 'index.php'));
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h2>User Login</h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>

    
</body>
</html>
