<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 11 Multi Auth</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsleyjs/2.9.2/parsley.min.js"></script>

</head>

<body>
    <div class="mb-5 mt-1">
        <nav>
            <x-welcome-header />
        </nav>
    </div>

    <div class="container p-1">

        <div class="">


            @if ($errors->any())
                <div class="alert alert-danger" id="errorShow">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card-body">
                @if (Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
                        {{ Session::get('success') }}
                    </div>
                @endif

                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorMessage">
                        {{ Session::get('error') }}
                        <a href="{{ session('error_file') }}" class="btn btn-warning ml-2">Download Error Rows</a>
                    </div>
                @endif

                   <div class=" rounded ">
                    <h1 class="text-center"><em>My List</em></h1>
                   </div>
                <div class="row m-auto">
                    <div class="col-md-2 my-4">
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addItemModal ">
                            Add Item
                        </button>
                    </div>
                    <div class="col-md-3 my-4">
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                            Bulk Upload
                        </button>
                    </div>
                    <!-- Modal for Bulk Upload -->
                    <div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload CSV File</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('import-data.csv') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="csvFile">Upload CSV File:</label>
                                            <input type="file" id="csvFile" name="csvFile" class="form-control"
                                                accept=".csv" required>
                                        </div>
                                        <div class="my-2">
                                            <button type="submit" class="btn btn-primary mt-3">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <!-- Modal for Adding New Item -->

                <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addItemModalLabel">Add New  
                                    Item</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form   id="addItemForm" action="{{ route('store.listdata') }}" method="POST"
                                    enctype="multipart/form-data" data-parsley-validate>
                                    @csrf

                                    <div class="mb-3">
                                        <label for="i_con" class="form-label">Icon (Image)</label>
                                        <input type="file" class="form-control @error('i_con') is-invalid @enderror"
                                            name="i_con" accept="image/*" required
                                            data-parsley-required-message="Please select an image.">
                                        @error('i_con')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="title" class="form-label">Title:</label>
                                        <input type="text" id="title"
                                            class="form-control @error('title') is-invalid @enderror" name="title"
                                            value="{{old('title')}}" required minlength="3" maxlength="100">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="title-error" style="display: none;">Title must
                                            contain only letters
                                            and numbers.</div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="value" class="form-label">Value:</label>
                                        <input type="text" class="form-control" name="value" id="valueInput"
                                            value="{{old('value')}}" required>
                                        <div id="valueError" class="invalid-feedback" style="display: none;">
                                            Please enter a valid number (e.g., 123 or 123.45).
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary" id="addItemButton">Add Item</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



                <table id="listDataTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Sno</th>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var table = $('#listDataTable').DataTable({
                processing: true,
                serverSide: true,

                // deferLoading: 0,
                ajax: '{{ route('account.dashboard') }}',



                columns: [
                    { data: 'id', name: 'ID', searchable: false, orderable: false, },
                    { data: 'icon', name: 'icon', searchable: false, orderable: false, },
                    { data: 'title', name: 'Title' },
                    { data: 'value', name: 'Value', searchable: false, orderable: false, },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        render: function (data, type, row) {
                            var buttonClass = data === 1 ? 'btn-success' : 'btn-danger';
                            var buttonText = data === 1 ? 'Deactivate' : 'Activate';
                            return `<button class="btn ${buttonClass} btn-sm change-status" data-id="${row.id}" data-status="${data === 1 ? 0 : 1}">${buttonText}</button>`;
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: true }
                ],
                pageLength: 15, // Set the default page length
                lengthMenu: [[15], [15]],
                "initComplete": function () {
                    $(".dataTables_filter input")
                        .unbind() // Unbind previous default bindings
                        .bind("input", function (e) { // Bind our desired behavior
                            // If the length is 3 or more characters, or the user pressed ENTER, search
                            if (this.value.length >= 3 || e.keyCode == 13) {
                                // Call the API search function
                                table.search(this.value).draw();
                            }
                            // Ensure we clear the search if they backspace far enough
                            if (this.value == "") {
                                table.search("").draw();
                            }
                            return;
                        });

                }

            })

        });



        // Status change handling
        $(document).on('click', '.change-status', function () {
            var id = $(this).data('id');
            var status = $(this).data('status');

            $.ajax({
                url: '{{ route('status.change', '') }}/' + id, // Append the ID to the URL
                type: 'POST',
                data: {
                    status: status,
                    _token: '{{ csrf_token() }}' // Include CSRF token
                },
                success: function (response) {
                    if (response.success) {
                        $('#listDataTable').DataTable().ajax.reload();
                        alert('Succesfully change status'); // Reload the table
                    } else {
                        alert('Status change failed.');
                    }
                },
            });
        });

        // // for session 
        document.addEventListener('DOMContentLoaded', function () {
            // Function to hide and remove the message
            function hideMessage(messageId) {
                const message = document.getElementById(messageId);
                if (message) {
                    setTimeout(() => {
                        message.classList.remove('show'); // Remove the Bootstrap show class
                        message.classList.add('fade'); // Add fade class to trigger fade-out
                        setTimeout(() => {
                            message.remove(); // Remove the message from the DOM
                        }, 150); // Wait for the fade transition to complete
                    }, 7000); // Wait for 7 seconds
                }
            }

            // Hide success and error messages if they exist
            hideMessage('successMessage');
            hideMessage('errorMessage');
        });

        // for pop up form 
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