<?php
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
include('connect.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Shortened URL</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div id="header">
        <h1>URL Shortener</h1>
        <form id="logout" method="post">
            <input type="submit" name="logout" value="Logout">
        </form>
    </div>

    <main>
        <a href="index.php">Back to Home</a>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
            $id = $_GET['id'];

            $sql = "SELECT short_code, original_url FROM url_mappings WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($short_code, $original_url);

            if ($stmt->fetch()) {
                $conn->close();
                echo '<form action="update.php" method="post">';
                echo "<input type='hidden' name='id' value='$id'>";
                echo "<h2>Edit Short URL</h2>";
                echo "<input type='text' name='short_code' class='text-input' value='$short_code'>";
                echo "<h2>Edit Long URL</h2>";
                echo "<input type='text' name='original_url' class='text-input' value='$original_url'>";
                echo "<input type='submit' value='Save'>";
                echo '</form>';
            } else {
                echo "Shortened URL not found.";
            }
        } else {
            echo "Invalid request.";
        }
        ?>
    </main>
</body>

</html>