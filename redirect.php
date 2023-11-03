<?php
include('connect.php'); // Include the database connection code

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['code'])) {
    $shortCode = $_GET['code'];

    // Retrieve the original URL and current click count
    $sql = "SELECT original_url, click_count FROM url_mappings WHERE short_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $shortCode);
    $stmt->execute();
    $stmt->bind_result($originalUrl, $clickCount);
    $stmt->store_result();

    if ($stmt->fetch()) {
        // Increment the click count
        $clickCount++;
        
        // Update the click count in the database
        $updateSql = "UPDATE url_mappings SET click_count = ? WHERE short_code = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("is", $clickCount, $shortCode);
        $updateStmt->execute();

        $conn->close();

        // Redirect to the original URL
        header("Location: " . $originalUrl);
        exit();
    } else {
        // Shortened URL not found
        header("Location: index.php");
        exit();
    }
}
?>
