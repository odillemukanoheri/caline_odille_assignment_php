<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: ../index.php");
require_once "../config/db.php";

$stmt = $pdo->query("SELECT t.*, u.username, b.title FROM transactions t 
                     JOIN users u ON t.user_id=u.user_id
                     JOIN books b ON t.book_id=b.book_id
                     ORDER BY t.borrow_date DESC");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transactions</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
    <a href="../dashboard.php">Dashboard</a>
    <a href="borrow.php">Borrow Book</a>
    <a href="return.php">Return Book</a>
    <a href="../books/list.php">Books</a>
    <a href="../logout.php">Logout</a>
</nav>
<div class="container">
    <h2>Transactions</h2>
    <table>
        <tr>
            <th>ID</th><th>User</th><th>Book</th><th>Borrow Date</th><th>Return Date</th><th>Status</th>
        </tr>
        <?php foreach($transactions as $t): ?>
        <tr>
            <td><?php echo $t['transaction_id']; ?></td>
            <td><?php echo $t['username']; ?></td>
            <td><?php echo $t['title']; ?></td>
            <td><?php echo $t['borrow_date']; ?></td>
            <td><?php echo $t['return_date'] ?? '-'; ?></td>
            <td><?php echo $t['status']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>