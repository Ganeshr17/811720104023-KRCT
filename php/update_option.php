<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (!isset($_SESSION["username"])) {
        http_response_code(401); // Unauthorized
        echo json_encode(["message" => "You are not logged in."]);
        exit();
    }

    $username = $_SESSION["username"];
    $newAge = $_POST["new_age"];
    $newDOB = $_POST["new_dob"];
    $newContact = $_POST["new_contact"];

    // Perform validation and update in the database (MySQL, MongoDB, or Redis)
    // For simplicity, let's assume you have a MySQL database named "users" with a table named "user_info"
    $conn = new mysqli("localhost", "root", "", "users");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update user options using a prepared statement
    $updateQuery = $conn->prepare("UPDATE user_info SET age = ?, dob = ?, contact = ? WHERE username = ?");
    $updateQuery->bind_param("iss", $newAge, $newDOB, $newContact, $username);

    if ($updateQuery->execute()) {
        $message = "User options updated successfully!";
    } else {
        $message = "Failed to update user options.";
    }

    $updateQuery->close();
    $conn->close();

    // Send the message in the response
    echo json_encode(["message" => $message]);
}
?>
