document.addEventListener('DOMContentLoaded', function() {
    // Retrieve username from local storage
    const username = localStorage.getItem('username');

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
        url: 'get_user_details.php',
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
}); 
function toggleEditMode() {
    // Toggle between view mode and edit mode
    $('#viewMode').toggle();
    $('#editMode').toggle();
}

function saveChanges() {
    const newAge = document.getElementById('edit_age').value;
    const newDOB = document.getElementById('edit_dob').value;
    const newContact = document.getElementById('edit_contact').value;

    // Send an AJAX request to save changes
    $.ajax({
        type: 'POST',
        url: './save_changes.php',
        data: {
            new_age: newAge,
            new_dob: newDOB,
            new_contact: newContact
        },
        dataType: 'json',
        success: function(response) {
            // Display Bootstrap alert with the message
            $('#alert-container').html('<div class="alert alert-success" role="alert">' + response.message + '</div>');

            // Update user details in view mode
            document.getElementById('user-age').innerText = 'Age: ' + newAge;
            document.getElementById('user-dob').innerText = 'Date of Birth: ' + newDOB;
            document.getElementById('user-contact').innerText = 'Contact: ' + newContact;

            // Toggle back to view mode
            toggleEditMode();
        },
        error: function(error) {
            // Display Bootstrap alert with the error message
            $('#alert-container').html('<div class="alert alert-danger" role="alert">' + error.responseJSON.message + '</div>');
        }
    });
}
