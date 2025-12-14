<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
    <a href="dashboard.php">Home</a>
    <a href="books/list.php">Books</a>
    <a href="transactions/list.php">Transactions</a>
    <a href="logout.php">Logout</a>
</nav>
<div class="container">
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <p>Use the menu above to manage books and transactions.</p>
</div>
</body>
</html>