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
        <h1><a href="index.php">URL Shortener</a></h1>
        <button id="logoutButton">Logout</button>
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

<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', (event) => {
        // Check for a success message in the session
        let successMessage = "<?php echo isset($_SESSION['success_message']) ? $_SESSION['success_message'] : ''; ?>";
        if (successMessage) {
            // Display a pop-up success alert
            alert(successMessage);
            // Remove the session variable to prevent displaying the message again
            <?php unset($_SESSION['success_message']); ?>
        }
        let duplicate_message = "<?php echo isset($_SESSION['duplicate_message']) ? $_SESSION['duplicate_message'] : ''; ?>";
        if (duplicate_message) {
            alert(duplicate_message);
            <?php unset($_SESSION['duplicate_message']); ?>
        }
    });

    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this URL?")) {
            window.location.href = "delete.php?id=" + id;
        }
    }
    document.getElementById("logoutButton").addEventListener("click", function() {
        if (confirm("Are you sure you want to logout?")) {
            // Redirect to the logout action (logout.php)
            window.location.href = "logout.php";
        }
    });
</script>

</html>