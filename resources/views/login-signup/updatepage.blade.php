<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .hidden {
            display: none;
        }

        .loading-image {
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<x-welcome-header />

<body>
    <div class="container mt-5">
        <h1 class="text-center">Update Profile</h1>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{route('account.update')}}" method="POST" class="mb-4">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ Auth::user()->name }}" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="{{Auth::user()->email}}"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>

        <h5 class="text-center">Request Change Password</h5>

        <div class="customder hidden">
            <center>
                <img class="loading-image" src="https://cdn.breathewellbeing.in/downloads/videos/images/ajaxloader.gif"
                    alt="loading..">
            </center>
        </div>


        <form id="sendOtpForm" action="{{ route('account.email-otp') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email1" name="email" placeholder="Enter login email" required class="form-control">
            <div class="invalid-feedback" id="emailError" style="display: none; color: red;"></div>
        </div>
        <button id="sendOtp" type="submit" class="btn btn-primary">Send OTP</button>
        <div class="customder hidden">Loading...</div>
    </form>

        <form id="verifyOtpForm" class="hidden" action="{{route('account.email-otp-verify')}}" method="POST">
            @csrf
            <div class="form-group">
                <label for="otp">Enter OTP</label>
                <input type="text" class="form-control" name="otp" id="otp"
                    placeholder="Enter the OTP sent to your email" value="{{old('otp')}}" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify OTP</button>
        </form>

        <form id="changepassword" class="hidden" action="{{ route('account.change-password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="inputPassword1" class="form-label">New Password</label>
                <input type="password" name="inputPassword1" placeholder="Enter the new password" class="form-control" id="inputPassword1" required>
                <div id="passwordError" class="invalid-feedback d-none"></div>
            </div>
            <div class="mb-3">
                <label for="inputPassword2" class="form-label">Confirm Password</label>
                <input type="password" name="inputPassword2" placeholder="Confirm Password" class="form-control" id="inputPassword2" required>
                <div id="confirmPasswordError" class="invalid-feedback d-none"></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <div class="customder hidden">Loading...</div>
        </form>
        <div id="successMessage" class="hidden"></div>


    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            function confirmReload() {
                return confirm("All submissions are complete. Do you want to reload the page?");
            }
            $('#sendOtpForm').on('submit', function (e) {
                e.preventDefault(); // Prevent form submission
                // Clear previous error messages
                $('#email1').removeClass('is-invalid');
                $('#emailError').text('').hide();

                const email = $('#email1').val().trim();
                
                 var sendotp = $('#sendOtp')
                 sendotp.prop('disabled', true);
                // Validate email format using HTML5 validation first
                if (!email) {
                    $('#email1').addClass('is-invalid');
                    $('#emailError').text('Email is required.').show();
                    return;
                }

                // Debugging: Log the email value
                console.log('Email entered:', email);

                // Use a simple regex to validate the email format
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                console.log('Email pattern:', emailPattern);
                if (!emailPattern.test(email)) {
                    $('#email1').addClass('is-invalid');
                    $('#emailError').text('Please enter a valid email address.').show();
                    return;
                }
                // Show loader
                $('.customder').removeClass('hidden');

                // Send AJAX request to send OTP
                console.log($(this).serialize());
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(), // Serialize form data
                    success: function (response) {
                        $('.customder').addClass('hidden'); // Hide loader
                        $('#sendOtpForm').addClass('hidden'); // Hide send OTP form
                        $('#verifyOtpForm').removeClass('hidden'); // Show verify OTP form
                        alert('OTP sent successfully!'); // Show success message
                    },
                    error: function (xhr) {
                        $('.customder').addClass('hidden'); // Hide loader
                        alert('Error: ' + xhr.responseJSON.message); // Show error message
                    }
                });
            });

            $('#verifyOtpForm').on('submit', function (e) {
                e.preventDefault(); // Prevent form submission
                $('.customder').removeClass('hidden');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(), // Serialize form data
                    success: function (response) {
                        $('.customder').addClass('hidden'); // Hide loader
                        $('#verifyOtpForm').addClass('hidden');
                        $('#changepassword').removeClass('hidden');
                        alert('otp varrify successfully Now change the password here '); // Show success message
                    },
                    error: function (xhr) {
                        $('.customder').addClass('hidden'); // Hide loader
                        alert('Error: ' + xhr.responseJSON.message); // Show error message
                    }
                });
            });
            $('#changepassword').on('submit', function (e) {
                e.preventDefault();
                const password = $('#inputPassword1').val();
                const confirmPassword = $('#inputPassword2').val();
                let valid = true;
                console.log({
                    inputPassword1: $('#inputPassword1').val(),
                    inputPassword2: $('#inputPassword2').val()
                });


                // Clear previous messages
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('').addClass('d-none');

                // Validate password length
                if (password.length < 8) {
                    $('#inputPassword1').addClass('is-invalid');
                    $('#passwordError').text('Password must be at least 8 characters long.').removeClass('d-none');
                    valid = false;
                }

                // Validate password match
                if (password !== confirmPassword) {
                    $('#inputPassword2').addClass('is-invalid');
                    $('#confirmPasswordError').text('Passwords do not match.').removeClass('d-none');
                    valid = false;
                }
                console.log($(this).serialize());

                // Send AJAX to change the password
                if (valid) {
                    $('.customder').removeClass('hidden'); // Show loader
                    $.ajax({
                        type: 'POST',
                        url: $(this).attr('action'),
                        data: $(this).serialize(), // Serialize form data
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token here
                        },
                        success: function (response) {
                            $('.customder').addClass('hidden'); // Hide loader
                            $('#successMessage').text(response.message).removeClass('hidden'); // Show success message
                            $('#changepassword').addClass('hidden'); // Optionally hide the form
                        },
                        error: function (xhr) {
                            $('.customder').addClass('hidden'); // Hide loader
                            if (xhr.status === 422) {
                                // Handle validation errors
                                const errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    $(`#${field}`).addClass('is-invalid');
                                    $(`#${field}Error`).text(errors[field][0]).removeClass('d-none');
                                }
                            } else {
                                const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'An unexpected error occurred.';
                                alert('Error: ' + errorMessage);
                            }
                        }
                    });
                }
            });

            // Clear errors on input
            $('#inputPassword1, #inputPassword2').on('input', function () {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('').addClass('d-none');
            });


        });
    </script>
</body>

</html>