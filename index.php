<?php
session_start();
require_once "config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Please fill in all fields!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">

    <h1>Welcome to the Library System</h1>

    <!-- 🔹 USER GUIDANCE MESSAGE -->
    <p style="text-align:center; color:#555; margin-bottom:20px;">
        If this is your <strong>first time</strong> using the system, please create an account.<br>
        If you already have an account, log in below.
    </p>

    <!-- 🔹 ERROR MESSAGE -->
    <?php
    if (isset($error)) {
        echo "<p style='color:red; text-align:center;'>$error</p>";
    }
    ?>

    <!-- 🔹 LOGIN FORM -->
    <form method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
    </form>

    <!-- 🔹 FIRST-TIME USER LINK -->
    <p style="text-align:center; margin-top:15px;">
        New user?
        <a href="users/register.php" style="color:#2980b9; font-weight:bold;">
            Create an account
        </a>
    </p>

</div>

</body>
</html>
