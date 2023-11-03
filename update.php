<?php
include('connect.php'); // Include the database connection code
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['short_code']) && isset($_POST['original_url'])) {
    $id = $_POST['id'];
    $short_code = $_POST['short_code'];
    $original_url = $_POST['original_url'];

    $sql = "UPDATE url_mappings SET short_code = ?, original_url = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $short_code, $original_url, $id);
    if ($stmt->execute()) {
        // Set a session variable to indicate the success of the edit
        $_SESSION['success_message'] = "URL edited successfully!";
    }

    $conn->close();

    header("Location: index.php"); // Redirect back to the index page
    exit();
}
