<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>User Profile</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/profile.js"></script>
</head>
<body>

    <div class="container">
        <div class="profile-container">
            <!-- Display a success or error message if provided in the URL -->
            <?php
            if (isset($_GET['message'])) {
                $message = urldecode($_GET['message']);
                echo '<div id="alert-container" class="mt-3"><div class="alert alert-success alert-dismissible fade show" role="alert">' . $message .
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button></div></div>';
            }
            ?>

            <div id="viewMode">
                <!-- Display User Details in View Mode -->
                <p id="username-display"></p>
                <p id="user-age"></p>
                <p id="user-dob"></p>
                <p id="user-contact"></p>
                <button onclick="toggleEditMode()">Edit</button>
            </div>

            <div id="editMode" style="display: none;">
                <!-- Editable Form in Edit Mode -->
                <form id="editProfileForm">
                    <div class="form-group">
                        <label for="edit_age">Age:</label>
                        <input type="text" class="form-control" id="edit_age" name="edit_age">
                    </div>
                    <div class="form-group">
                        <label for="edit_dob">Date of Birth:</label>
                        <input type="date" class="form-control" id="edit_dob" name="edit_dob">
                    </div>
                    <div class="form-group">
                        <label for="edit_contact">Contact:</label>
                        <input type="text" class="form-control" id="edit_contact" name="edit_contact">
                    </div>
                    <button type="button" onclick="saveChanges()">Save Changes</button>
                    <button type="button" onclick="toggleEditMode()">Cancel</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js (required for Bootstrap) -->
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Retrieve username from local storage
        const username = window.localStorage.getItem('username');

        if (username) {
            // Display the username on the profile page
            document.getElementById('username-display').innerText = 'Welcome, ' + username + '!';

        } else {
            // Handle the case where the username is not available
            console.log('Username not found in local storage');
        }

        // Retrieve user details using an AJAX request
        $.ajax({
            type: 'POST',
            url: './get_user_details.php',
            data: {
                username: username
            },
            dataType: 'json',
            success: function(response) {
                // Display user details in view mode

                document.getElementById('user-age').innerText = 'Age: ' + response.age;
                document.getElementById('user-dob').innerText = 'Date of Birth: ' + response.dob;
                document.getElementById('user-contact').innerText = 'Contact: ' + response.contact;

                // Set values in edit mode
                document.getElementById('edit_age').value = response.age;
                document.getElementById('edit_dob').value = response.dob;
                document.getElementById('edit_contact').value = response.contact;
            },
            error: function(error) {
                console.error('Error retrieving user details: ' + error.responseText);
            }
        });
    </script>
    <script src="../js/profile.js"></script>
</body>
</html>
