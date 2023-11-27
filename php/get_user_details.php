<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the username is provided in the POST data
    if (isset($_POST["username"])) {
        $username = $_POST["username"];

        // Perform retrieval from the database (MySQL, MongoDB, or Redis)
        // For simplicity, let's assume you have a MySQL database named "users" with a table named "user_info"
        $conn = new mysqli("localhost", "root", "", "users");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve user details using a prepared statement
        $getUserDetailsQuery = $conn->prepare("SELECT age, dob, contact FROM user_info WHERE username = ?");
        $getUserDetailsQuery->bind_param("s", $username);
        $getUserDetailsQuery->execute();
        $result = $getUserDetailsQuery->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userDetails = [
                "age" => $row["age"],
                "dob" => $row["dob"],
                "contact" => $row["contact"]
            ];
            echo json_encode($userDetails);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["message" => "User details not found."]);
        }

        $getUserDetailsQuery->close();
        $conn->close();
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Username not provided in the request."]);
    }
}
?>
