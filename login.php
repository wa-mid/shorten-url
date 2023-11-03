<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user information from the database
    $conn = new mysqli("localhost", "root", "", "short_url");
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Authentication successful
            session_start();
            $_SESSION['user_id'] = $id;
            header("Location: index.php");
            exit();
        } else {
            // Authentication failed
            $error_message = "Invalid username or password";
        }
    } else {
        // User not found
        $error_message = "Invalid username or password";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="center">Đăng nhập</h1>
    <div class="container">
        <?php
        if (isset($error_message)) {
            echo "<p>$error_message</p>";
        }
        ?>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" class="text-input" name="username" id="username" required><br>

            <label for="password">Password:</label>
            <input type="password" class="text-input" name="password" id="password" required><br>

            <input type="submit" value="Login">
        </form>
    </div>

</body>

</html>