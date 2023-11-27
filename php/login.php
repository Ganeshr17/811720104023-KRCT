<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_username = $_POST["login_username"];
    $login_password = $_POST["login_password"];

    // Perform login authentication here (MySQL, MongoDB, or Redis)
    // For simplicity, let's assume you have a MySQL database named "users" with a table named "user_info"
    $conn = new mysqli("localhost", "root", "", "users");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM user_info WHERE username = ?");
    $stmt->bind_param("s", $login_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Use password_verify to check the hashed password
        if (password_verify($login_password, $hashed_password)) {
            // Close the database connection
            $stmt->close();
            $conn->close();

            // Set the username in local storage using JavaScript
            echo '<script>';
            echo 'window.localStorage.setItem("username", "' . $login_username . '");';
            echo 'window.location.href = "php/profile.php";'; // Redirect to profile.php
            echo '</script>';
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid username!";
    }

    $stmt->close();
    $conn->close();
}
?>

