<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        include_once('includes/dbconnection.php'); // Database connection file

        $user_type = $_POST['user_type'];
        $full_name = $_POST['full_name'];
        $username = $_POST['username'];
        $mobile_number = $_POST['mobile_number'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // SQL query for registering either admin or user
        if ($user_type == 'admin') {
            $sql = "INSERT INTO tbladmin (AdminName, UserName, MobileNumber, Email, Password) 
                    VALUES (?, ?, ?, ?, ?)";
        } else {
            $sql = "INSERT INTO tbluser (FullName, UserName, MobileNumber, Email, Password) 
                    VALUES (?, ?, ?, ?, ?)";
        }

        // Prepare and execute statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiss", $full_name, $username, $mobile_number, $email, $password);

        if ($stmt->execute()) {
            echo "Registration successful.";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST" action="">
        <label>User Type:</label>
        <select name="user_type">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select><br><br>

        <label>Full Name:</label>
        <input type="text" name="full_name" required><br><br>

        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Mobile Number:</label>
        <input type="text" name="mobile_number"><br><br>

        <label>Email:</label>
        <input type="email" name="email"><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
