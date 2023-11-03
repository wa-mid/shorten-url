<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Other registration data...

    // Check if the user already exists in the database
    $conn = new mysqli("localhost", "root", "", "short_url");
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // User already exists, show an alert
        echo "<script>alert('User ".$username."đã tồn tại! Vui lòng đăng ký username khác'); window.location = 'register.php';</script>";
    } else {
        // User doesn't exist, proceed with registration
        $hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $insert_sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ss", $username, $hash);

        if ($insert_stmt->execute()) {
            // Registration successful, redirect to a success page or login page
            echo "<script>alert('Đăng ký thành công user: ".$username."'); window.location = 'login.php';</script>";
            // header("Location: login.php");
            // exit();
        } else {
            // Registration failed, handle accordingly
            echo "<script>alert('Registration failed. Please try again.');</script>";
        }
    }

    $conn->close();
}
?>
