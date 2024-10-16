<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Password Validation</title>
    <style>
        body {
            background-color: #f8f9fa;
            /* Light background */
        }

        .card-container {
            max-width: 400px;
            /* Set maximum width of the card */
            margin: auto;
            /* Center the card horizontally */
            margin-top: 100px;
            /* Add some space from the top */
        }
    </style>
</head>

<body>
    <div class="card card-container shadow">
        <div class="card-body">
            <h5 class="card-title text-center">Reset Your Password</h5>
            <form action="{{route('account.change-password')}}" method="POST" id="passwordForm">
                @csrf
                <div class="mb-3">
                    <label for="inputPassword1" class="form-label">New Password</label>
                    <input type="password" name="inputPassword1" class="form-control" id="inputPassword1" required>
                    <div id="passwordError" class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="inputPassword2" class="form-label">Confirm Password</label>
                    <input type="password" name="inputPassword2" class="form-control" id="inputPassword2" required>
                    <div id="confirmPasswordError" class="invalid-feedback"></div>
                </div>
                <button type="submit" class="btn btn-primary ">Submit</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
         $(document).ready(function () {
            $('#passwordForm').on('submit', function (e) {
                // Prevent form submission
                e.preventDefault();

                const password = $('#inputPassword1').val();
                const confirmPassword = $('#inputPassword2').val();
                let valid = true;

                // Clear previous error states
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Check if password is less than 8 characters
                if (!password.length <= 8) {
                    $('#inputPassword1').addClass('is-invalid');
                    $('#passwordError').text('Password must be at least 8 characters long.');
                    valid = true;
                } else {
                    valid = false;
                }

                // Check if passwords match
                if (password !== confirmPassword) {
                    $('#inputPassword2').addClass('is-invalid');
                    $('#confirmPasswordError').text('Passwords do not match.');
                    valid = false;
                } else {
                    valid = true;
                }

                // If valid, submit the form
                // if (valid) {
                //     this.submit(); // Submit the form programmatically
                // }
            });
        });
    </script>
</body>

</html>