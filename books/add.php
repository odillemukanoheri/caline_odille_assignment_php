<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: ../index.php");
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $available = $_POST['available'];

    if ($title && $author && is_numeric($available)) {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, category, available) VALUES (:title,:author,:category,:available)");
        $stmt->execute([
            ':title'=>$title, ':author'=>$author, ':category'=>$category, ':available'=>$available
        ]);
        header("Location: list.php");
        exit;
    } else {
        $error = "All fields are required!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
    <a href="list.php">Back to List</a> | <a href="../dashboard.php">Dashboard</a>
</nav>
<div class="container">
    <h2>Add New Book</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="text" name="category" placeholder="Category">
        <input type="number" name="available" placeholder="Available Copies" value="1" required>
        <button type="submit">Add Book</button>
    </form>
</div>
</body>
</html>