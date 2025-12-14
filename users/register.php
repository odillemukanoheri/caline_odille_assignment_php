<?php
require_once "../config/db.php";

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm"];

    // 🔹 Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // 🔹 Check if email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $errors[] = "Email already registered. Please login.";
        }
    }

    // 🔹 Insert user
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, role)
            VALUES (:username, :email, :password, 'user')
        ");

        $stmt->execute([
            ":username" => $username,
            ":email"    => $email,
            ":password" => $hashedPassword
        ]);

        $success = "Account created successfully! You can now login.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">

    <h1>Create Account</h1>

    <!-- 🔹 Guidance -->
    <p style="text-align:center; color:#555;">
        Please fill in the form below to create your account.
    </p>

    <!-- 🔹 Error messages -->
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red; text-align:center;'>$error</p>";
        }
    }
    ?>

    <!-- 🔹 Success message -->
    <?php
    if ($success) {
        echo "<p style='color:green; text-align:center;'>$success</p>";
    }
    ?>

    <!-- 🔹 Registration Form -->
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm" placeholder="Confirm password" required>
        <button type="submit">Create Account</button>
    </form>

    <!-- 🔹 Back to login -->
    <p style="text-align:center; margin-top:15px;">
        Already have an account?
        <a href="../index.php" style="color:#2980b9; font-weight:bold;">
            Login here
        </a>
    </p>

</div>

</body>
</html>
