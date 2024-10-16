<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit List Item</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>
    <nav>
        <x-welcome-header/>
    </nav>
    <div class="container mt-3">

        <h1>Edit List Item</h1>

        <form action="{{ route('update.list', $id) }}" method="POST" enctype="multipart/form-data" id="editForm">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title"
                    value="{{ $data->title }}" required minlength="3" maxlength="100">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="invalid-feedback" id="title-error" style="display: none;">Title must contain only letters
                    and numbers.</div>
            </div>

            <div class="form-group mb-3">
                <label for="value" class="form-label">Value:</label>
                <input type="text" class="form-control" name="value" id="valueInput" value="{{ $data->value }}"
                    required>
                <div id="valueError" class="invalid-feedback" style="display: none;">
                    Please enter a valid number (e.g., 123 or 123.45).
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="i_con" class="form-label">Icon:</label>
                <div class="d-flex align-items-center">
                    @if($data->i_con)
                        <img src="{{ asset('storage/icons/' . $data->i_con) }}" alt="Current Icon"
                            class="img-thumbnail me-3" style="max-width: 100px; max-height: 100px; object-fit: contain;">
                    @endif
                    <input type="file" class="form-control" name="i_con" id="iconInput" accept="image/*"
                        style="flex-grow: 1;">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const valueInput = document.getElementById('valueInput');
        const valueError = document.getElementById('valueError');

        valueInput.addEventListener('input', function () {
            // Check if the value is a valid number (integer or decimal)
            if (!/^\d+(\.\d+)?$/.test(this.value)) {
                valueInput.classList.add('is-invalid');
                valueError.style.display = 'block'; // Show error message
            } else {
                valueInput.classList.remove('is-invalid');
                valueError.style.display = 'none'; // Hide error message
            }
        });

        document.getElementById('editForm').addEventListener('submit', function (event) {
            // Check validation one last time before submission
            if (!/^\d+(\.\d+)?$/.test(valueInput.value)) {
                valueInput.classList.add('is-invalid');
                valueError.style.display = 'block'; // Show error message
                event.preventDefault(); // Prevent form submission
            }
        });
        // for title

        const titleInput = document.getElementById('title');
const titleError = document.getElementById('title-error');

// Add an input event listener to the title input field
titleInput.addEventListener('input', function () {
    // Check if the value contains only alphanumeric characters and spaces
    if (!/^[a-zA-Z0-9\s]*$/.test(this.value)) {
        titleInput.classList.add('is-invalid'); // Add invalid class
        titleError.style.display = 'block'; // Show error message
    } else {
        titleInput.classList.remove('is-invalid'); // Remove invalid class
        titleError.style.display = 'none'; // Hide error message
    }
});
    </script>
</body>

</html>