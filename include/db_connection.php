<?php
require __DIR__ . '/config.php';

$conn = null;

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new mysqli_sql_exception("Connection failed: " . $conn->connect_error);
    }

    // Set charset to utf8mb4 if the connection is successful
    if (!$conn->set_charset('utf8mb4')) {
        throw new mysqli_sql_exception("Error loading character set utf8mb4: " . $conn->error);
    }
} catch (mysqli_sql_exception $e) {
    // Log the error message to a log file
    error_log("Database connection failed: " . $e->getMessage());

    // Display a user-friendly message
    echo "Database connection failed. Please try again later.";
    exit;
}

// You can now use $conn for your database operations

// Example of a prepared statement to avoid SQL injection
/*
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    // Process each row
}
$stmt->close();
*/

// Close the connection when done
// $conn->close();
