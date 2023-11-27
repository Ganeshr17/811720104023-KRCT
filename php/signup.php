<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $dob = $_POST["dob"];
    $age = $_POST["age"];
    $contact = $_POST["contact"];

    // Password encryption using password_hash
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Perform validation and insertion into the database (MySQL, MongoDB, or Redis)
    // For simplicity, let's assume you have a MySQL database named "users" with a table named "user_info"
    $conn = new mysqli("localhost", "root", "", "users");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the username already exists using a prepared statement
    $checkUserQuery = $conn->prepare("SELECT * FROM user_info WHERE username = ?");
    $checkUserQuery->bind_param("s", $username);
    $checkUserQuery->execute();
    $result = $checkUserQuery->get_result();

    if ($result->num_rows > 0) {
        $message = "Username already exists. Please choose a different username.";
        echo json_encode(["message" => $message]);
        $checkUserQuery->close();
        $conn->close();
        exit();
    }

    // Insert user data into the database using a prepared statement
    $insertQuery = $conn->prepare("INSERT INTO user_info (username, password, age, dob, contact) VALUES (?, ?, ?, ?, ?)");
    $insertQuery->bind_param("ssiss", $username, $hashedPassword, $age, $dob, $contact);

    if ($insertQuery->execute()) {
        $message = "Registration successful! You can now log in.";
    } else {
        $message = "Registration failed. Please try again.";
    }

    $insertQuery->close();
    $checkUserQuery->close();
    $conn->close();

    // Send the message in the response
    echo json_encode(["message" => $message]);
}
?>
