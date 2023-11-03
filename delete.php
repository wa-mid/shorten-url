<?php
include('connect.php'); // Include the database connection code
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Perform the deletion of the shortened URL based on the provided ID
    $sql = "DELETE FROM url_mappings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Set a session variable to indicate success
        $_SESSION['success_message'] = "URL deleted successfully!";
    }

    $conn->close();
}

// Redirect back to index.php
header("Location: index.php");
exit();
?>
