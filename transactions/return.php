<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

require_once "../config/db.php";

/* Get borrowed transactions for this user */
$stmt = $pdo->prepare("
    SELECT t.transaction_id, b.title 
    FROM transactions t
    JOIN books b ON t.book_id = b.book_id
    WHERE t.user_id = :uid AND t.status = 'borrowed'
");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Handle return */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = $_POST['transaction_id'] ?? null;

    if ($transaction_id) {
        try {
            $pdo->beginTransaction();

            // 1️⃣ Mark transaction as returned
            $stmt1 = $pdo->prepare("
                UPDATE transactions 
                SET status = 'returned', return_date = NOW() 
                WHERE transaction_id = :tid
            ");
            $stmt1->execute([':tid' => $transaction_id]);

            // 2️⃣ Increase book availability
            $stmt2 = $pdo->prepare("
                UPDATE books 
                SET available = available + 1 
                WHERE book_id = (
                    SELECT book_id FROM transactions WHERE transaction_id = :tid
                )
            ");
            $stmt2->execute([':tid' => $transaction_id]);

            $pdo->commit();
            $success = "Book returned successfully!";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Error returning book.";
        }
    } else {
        $error = "Please select a transaction.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include "../includes/nav.php"; ?>

<div class="container">
    <h2>Return Book</h2>

    <?php
    if (isset($error)) {
        echo "<p style='color:red; text-align:center;'>$error</p>";
    }
    if (isset($success)) {
        echo "<p style='color:green; text-align:center;'>$success</p>";
    }
    ?>

    <?php if (count($transactions) > 0): ?>
        <form method="post">
            <select name="transaction_id" required>
                <option value="">Select borrowed book</option>
                <?php foreach ($transactions as $t): ?>
                    <option value="<?= $t['transaction_id']; ?>">
                        <?= htmlspecialchars($t['title']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Return Book</button>
        </form>
    <?php else: ?>
        <p style="text-align:center;">You have no borrowed books.</p>
    <?php endif; ?>
</div>

</body>
</html>
