<!DOCTYPE html>
<html>

<head>
    <title>Đăng ký</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1 class="center">Đăng ký</h1>
    <div class="container">
        <form action="register_process.php" method="post">
            <label for="username">Username:</label>
            <input type="text" class="text-input" name="username" id="username" required><br>

            <label for="password">Password:</label>
            <input type="password" class="text-input" name="password" id="password" required><br>

            <input type="submit" value="Register">
        </form>
    </div>

</body>

</html>