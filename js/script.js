function registerUser() {
    event.preventDefault(); // Prevent default form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const age = document.getElementById('age').value;
    const dob = document.getElementById('dob').value;
    const contact = document.getElementById('contact').value;

    $.ajax({
        type: 'POST',
        url: 'php/signup.php',
        data: {
            username: username,
            password: password,
            confirm_password: confirmPassword,
            age: age,
            dob: dob,
            contact: contact
        },
        dataType: 'json', // Specify that the response is JSON
        success: function(response) {
            // Display Bootstrap alert with the message
            $('#alert-container').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span></button></div>');

            // Redirect to login page after 2 seconds if registration is successful
            if (response.message.includes("successful")) {
                setTimeout(function() {
                    window.location.href = 'login.html';
                }, 2000);
            }
        },
        error: function(error) {
            // Display Bootstrap alert with the error message
            $('#alert-container').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                error.responseJSON.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true">&times;</span></button></div>');
        }
    });
}

function loginUser() {
            event.preventDefault(); // Prevent default form submission
            console.log("hllo")
            const loginUsername = $('#login_username').val();
            const loginPassword = $('#login_password').val();

            $.ajax({
                type: 'POST',
                url: 'php/login.php',
                dataType: 'json',
                data: {
                    login_username: loginUsername,
                    login_password: loginPassword
                },
                success: function(response) {
                    if (response.success) {
                        // Display Bootstrap alert with the success message
                        $('#login-alert-container').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                            response.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button></div>');

                        // Store username in local storage
                        localStorage.setItem('username', loginUsername);

                        // Redirect to profile page after 2 seconds
                        setTimeout(function() {
                            window.location.href = 'profile.php?message=' + encodeURIComponent(response.message);
                        }, 2000);
                    } else {
                        // Display Bootstrap alert with the error message
                        $('#login-alert-container').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            response.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span></button></div>');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Display Bootstrap alert with the error message
                    let errorMessage = xhr.responseText ? xhr.responseText : 'An error occurred during login.';
                    $('#login-alert-container').html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        errorMessage + '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span></button></div>');
                }
            });
        }
