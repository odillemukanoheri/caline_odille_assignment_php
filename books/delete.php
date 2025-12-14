<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: ../index.php");
require_once "../config/db.php";

$id = $_GET['id'] ?? null;
if($id) {
    $stmt = $pdo->prepare("DELETE FROM books WHERE book_id=:id");
    $stmt->execute([':id'=>$id]);
}
header("Location: list.php");
exit;
?>