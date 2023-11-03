<?php
include('connect.php'); // Include the database connection code
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['original_url'])) {
    $original_url = $_POST['original_url'];
    
    // Check if the URL already exists in the database
    $check_sql = "SELECT id FROM url_mappings WHERE original_url = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $original_url);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // URL already exists, display an alert
        $_SESSION['duplicate_message'] = "This URL already exists in the database.";
    } else {
        // URL is not a duplicate, proceed with insertion
        $short_code = generateShortCode(); // Replace with your code

        // Insert the new short URL into the database
        $sql = "INSERT INTO url_mappings (short_code, original_url) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $short_code, $original_url);

        if ($stmt->execute()) {
            // Set a session variable to indicate success
            $_SESSION['success_message'] = "URL added successfully!";
        }
    }

    $conn->close();
}

function generateShortCode($length = 6) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $char_length = strlen($characters);
    $short_code = "";

    for ($i = 0; $i < $length; $i++) {
        $short_code .= $characters[rand(0, $char_length - 1)];
    }

    return $short_code;
}

header("Location: index.php");
exit();
?>
