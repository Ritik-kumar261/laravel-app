<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athlete Review Count</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
</head>

<body>
    <nav>
        <x-welcome-header/>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Athlete Review Count</h1>
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{route('reward.point')}}" class="mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="year" class="form-label">Year:</label>
                            <select name="year" id="year" class="form-select">
                                @for ($year = 2020; $year <= date('Y'); $year++)
                                    <option value="{{$year}}">{{$year}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="month" class="form-label">Month:</label>
                            <select name="month" id="month" class="form-select">
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{$month}}">{{$month}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table id="customerDataTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Zero</th>
                        <th>One to Two</th>
                        <th>Three to Five</th>
                        <th>Five Plus</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#customerDataTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: '{{ route('reward.point') }}', // Replace with your actual route
                columns: [
                    { data: 'date' },
                    { data: 'zero' },
                    { data: 'one_to_two' },
                    { data: 'three_to_five' },
                    { data: 'five_plus' },
                ]
            });
        });
    </script>
</body>

</html>
