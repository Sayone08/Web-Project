<?php
// Start session to track user login state
session_start();

// Direct database connection with provided credentials
$host = 'localhost'; // Hostname
$username = 'root';  // Database username
$password = '';      // Database password (empty for default root user)
$dbname = 'atsmsdb'; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Query to check if user exists in the userlogins table
    $sql = "SELECT * FROM userlogins WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $input_username, $input_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Valid login
        $_SESSION['user'] = $input_username;
        header("Location: search.php");
        exit();
    } else {
        // Invalid login
        $error_message = "Invalid username or password.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <title>User Login</title>
    <link href="/css/style.css" rel="stylesheet" type="text/css" />


    <style>
        /* Basic styling for the login form container */
        .login-form {
            width: 100%;
            max-width: 400px;
            /* Limiting max width for better layout */
            margin: 50px auto;
            /* Center the form on the page */
            padding: 30px;
            background: #fff;
            /* White background for the form */
            border-radius: 8px;
            /* Rounded corners */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            /* Subtle shadow for depth */
            text-align: center;
            /* Center the content */
        }

        /* Styling for the form heading */
        .login-form h2 {
            font-size: 24px;
            color: #333;
            /* Dark color for the title */
            margin-bottom: 20px;
            /* Space below the heading */
        }

        /* Styling for the error message */
        .login-form p {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Styling for the input fields */
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            /* Make inputs take full width */
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            /* Light border */
            border-radius: 4px;
            /* Rounded borders */
            font-size: 16px;
            color: #333;
            /* Text color */
        }

        /* Focus effect for inputs */
        .login-form input[type="text"]:focus,
        .login-form input[type="password"]:focus {
            border-color: #4CAF50;
            /* Green border when focused */
            outline: none;
            /* Remove default outline */
        }

        /* Styling for the login button */
        .login-form button {
            width: 100%;
            /* Full width for the button */
            padding: 14px;
            background-color: #4CAF50;
            /* Green background */
            color: #fff;
            /* White text */
            font-size: 18px;
            border: none;
            /* No border */
            border-radius: 4px;
            /* Rounded corners */
            cursor: pointer;
            /* Pointer cursor on hover */
            transition: background-color 0.3s ease;
            /* Smooth transition for hover effect */
        }

        /* Hover effect for the login button */
        .login-form button:hover {
            background-color: #45a049;
            /* Darker green when hovering */
        }

        /* Optional: Styling for form links (like Forgotten Password) */
        .login-form a {
            color: #3498db;
            /* Blue color for links */
            text-decoration: none;
            /* Remove underline */
            font-size: 14px;
        }

        .login-form a:hover {
            text-decoration: underline;
            /* Underline on hover */
        }
    </style>
</head>

<body>
    <div class="header" id="top">
        <div class="wrap">
            <!---start-logo---->
            <div class="logo">
                <h3 style="color: yellow;">Auto/Taxi Stand Management System</h3>
            </div>
            <!---End-logo---->
            <!---start-top-nav---->
            <div class="top-nav">
                <!-- <ul>
                    <li class="active"><a href="index.php">Home</a></li>
                    <li><a href="search.php" target="_blank">Check Stand Receipt</a></li>
                    <li><a href="admin/index.php">Admin</a></li>
                    <div class="clear"> </div>
                </ul> -->
                <ul>
                    <li class="active"><a href="index.php">Home</a></li>
                    <li><a href="admin/index.php">Admin</a></li>
                    <li><a href="user-login.php">USER</a></li> <!-- Added USER option -->
                    <div class="clear"> </div>
                </ul>
            </div>
            <!-- <div class="clear"> </div>
            -End-top-nav-- -->
        </div>
    </div>
    <div class="login-form">
        <h2>User Login</h2>
        <?php if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        } ?>
        <form method="POST" action="user-login.php">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>