<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php"); // Redirect to login if not logged in
        exit();
    }

    include_once('includes/dbconnection.php'); // Database connection file

    $user_id = $_SESSION['user_id'];

    // Fetch user's receipts from the database
    $sql = "SELECT * FROM receipts WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Check Stand Receipt</title>
</head>
<body>
    <h2>Your Receipts</h2>
    <table>
        <tr>
            <th>Receipt ID</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['receipt_id']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
