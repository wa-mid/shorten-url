<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('connect.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title>URL Shortener</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div id="header">
        <h1><a href="index.php">URL Shortener</a></h1>
        <button id="logoutButton">Logout</button>
    </div>

    <main>
        <form action="shorten.php" method="post">
            <input type="text" name="original_url" class="url-input text-input" placeholder="Enter URL to shorten">
            <input type="submit" class="submit-button" value="Shorten">
        </form>

        <h2>Your Shortened URLs</h2>
        <table class="custom-table">
            <tr>
                <th>URL rút gọn</th>
                <th>URL gốc</th>
                <th>Ngày tạo</th>
                <th>Lượt truy cập</th>
                <th>Sửa</th>
                <th>Xóa</th>
            </tr>

            <?php
            $sql = "SELECT id, short_code, original_url, created_at, click_count FROM url_mappings";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><a href='{$row['short_code']}' target='_blank'>{$row['short_code']}</a></td>";
                    echo "<td><a href='{$row['original_url']}' target='_blank'>{$row['original_url']}</a></td>";
                    echo "<td>{$row['created_at']}</td>";
                    echo "<td>{$row['click_count']}</td>";
                    echo "<td><a href='edit.php?id={$row['id']}'><i class='fa-solid fa-pen-to-square'></i></a></td>";
                    echo "<td><a href='#' onclick='confirmDelete({$row['id']});'><i class='fa-solid fa-trash'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No shortened URLs found.</td></tr>";
            }

            $conn->close();
            ?>
        </table>

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