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

    // Update user details using a prepared statement
    $updateUserDetailsQuery = $conn->prepare("UPDATE user_info SET age = ?, dob = ?, contact = ? WHERE username = ?");
    $updateUserDetailsQuery->bind_param("isss", $newAge, $newDOB, $newContact, $username);

    if ($updateUserDetailsQuery->execute()) {
        $message = "User details updated successfully!";
    } else {
        $message = "Failed to update user details.";
    }

    $updateUserDetailsQuery->close();
    $conn->close();

    // Send the message in the response
    echo json_encode(["message" => $message]);
}
?>
