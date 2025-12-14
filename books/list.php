<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
require_once "../config/db.php";

$stmt = $pdo->query("SELECT * FROM books");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Books List</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
    <a href="../dashboard.php">Home</a>
    <a href="add.php">Add Book</a>
    <a href="../transactions/list.php">Transactions</a>
    <a href="../logout.php">Logout</a>
</nav>
<div class="container">
    <h2>Books List</h2>
    <table>
        <tr>
            <th>ID</th><th>Title</th><th>Author</th><th>Category</th><th>Available</th><th>Actions</th>
        </tr>
        <?php foreach($books as $book): ?>
        <tr>
            <td><?php echo $book['book_id']; ?></td>
            <td><?php echo $book['title']; ?></td>
            <td><?php echo $book['author']; ?></td>
            <td><?php echo $book['category']; ?></td>
            <td><?php echo $book['available']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $book['book_id']; ?>">Edit</a> |
                <a href="delete.php?id=<?php echo $book['book_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>