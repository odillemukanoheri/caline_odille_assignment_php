<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<nav style="background:#333;padding:10px;">
    <a href="/library_project/dashboard.php" style="color:white;margin-right:15px;">Home</a>
    <a href="/library_project/books/list.php" style="color:white;margin-right:15px;">Books</a>
    <a href="/library_project/transactions/list.php" style="color:white;margin-right:15px;">Transactions</a>
    <a href="/library_project/logout.php" style="color:white;">Logout</a>
</nav>
