<?php
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('includes/dbconnection.php'); // Database connection file

        $username = $_POST['username'];
        $password = $_POST['password'];
        $user_type = $_POST['user_type'];

        // Determine table based on user type (admin or user)
        if ($user_type == 'admin') {
            $sql = "SELECT * FROM tbladmin WHERE UserName = ?";
        } else {
            $sql = "SELECT * FROM tbluser WHERE UserName = ?";
        }

        // Prepare and execute SQL query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['Password'])) {
                // Login successful, set session variables
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['user_name'] = $user['UserName'];
                $_SESSION['user_type'] = $user_type;

                // Redirect to appropriate page
                if ($user_type == 'admin') {
                    header("Location: admin/dashboard.php"); // Admin dashboard
                } else {
                    header("Location: user/check_receipt.php"); // User's Check Stand Receipt page
                }
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found.";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="">
        <label>User Type:</label>
        <select name="user_type">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select><br><br>

        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
