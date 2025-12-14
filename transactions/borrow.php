<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: ../index.php");
require_once "../config/db.php";

$books = $pdo->query("SELECT * FROM books WHERE available>0")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id'];
    $borrow_date = date('Y-m-d');

    if ($book_id) {
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, book_id, borrow_date) VALUES (:user_id,:book_id,:borrow_date)");
            $stmt->execute([':user_id'=>$user_id, ':book_id'=>$book_id, ':borrow_date'=>$borrow_date]);

            $stmt2 = $pdo->prepare("UPDATE books SET available=available-1 WHERE book_id=:book_id");
            $stmt2->execute([':book_id'=>$book_id]);

            $pdo->commit();
            $success = "Book borrowed successfully!";
        } catch(PDOException $e) {
            $pdo->rollBack();
            $error = "Error: ".$e->getMessage();
        }
    } else $error = "Select a book!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav>
    <a href="list.php">Transactions</a> | <a href="../books/list.php">Books</a> | <a href="../dashboard.php">Dashboard</a>
</nav>
<div class="container">
    <h2>Borrow Book</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="post">
        <select name="book_id" required>
            <option value="">Select Book</option>
            <?php foreach($books as $b): ?>
            <option value="<?php echo $b['book_id']; ?>"><?php echo $b['title']." (".$b['available']." available)"; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Borrow</button>
    </form>
</div>
</body>
</html>