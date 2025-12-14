<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: ../index.php");
require_once "../config/db.php";

$id = $_GET['id'] ?? null;
if(!$id) header("Location: list.php");

$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id=:id");
$stmt->execute([':id'=>$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $available = $_POST['available'];

    if ($title && $author && is_numeric($available)) {
        $stmt = $pdo->prepare("UPDATE books SET title=:title, author=:author, category=:category, available=:available WHERE book_id=:id");
        $stmt->execute([
            ':title'=>$title, ':author'=>$author, ':category'=>$category, ':available'=>$available, ':id'=>$id
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
    <title>Edit Book</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
    <a href="list.php">Back to List</a> | <a href="../dashboard.php">Dashboard</a>
</nav>
<div class="container">
    <h2>Edit Book</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="title" value="<?php echo $book['title']; ?>" required>
        <input type="text" name="author" value="<?php echo $book['author']; ?>" required>
        <input type="text" name="category" value="<?php echo $book['category']; ?>">
        <input type="number" name="available" value="<?php echo $book['available']; ?>" required>
        <button type="submit">Update Book</button>
    </form>
</div>
</body>
</html>