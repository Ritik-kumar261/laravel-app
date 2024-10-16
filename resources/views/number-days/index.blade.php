<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <style>
        .container {
            position: relative;

        }

        .input-container {
            text-align: center;
            margin-bottom: 20px;
            margin-top: 40px;
        }

        .table th {
            background-color: #28a745;
            /* Green background for header */
            color: white;
            /* White text color */
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
            /* Light gray background on hover */
        }

        h3 {
            text-align: center;
            /* Center align h3 tags */
            margin: 20px 0;
            /* Space above and below */
        }

        .form-row {
            justify-content: center;
            /* Center align items in the row */
        }
    </style>
</head>

<?php
$currentLevel = $level;
?>

<body>
    <nav class="">
        <x-welcome-header />
    </nav>
    <div class="  rounded  mt-3">
        <h1 class="text-center"><em>level-data</em></h1>
    </div>
    <div class="container">
        <div class="input-container">
            <form id="filterForm" action="{{ route('getdata') }}" method="GET" class="mb-4">
                <div class="row form-row">
                    <div class="col-md-2 mb-3">
                        <label for="level">Level:</label>
                        <select name="level" id="level" class="form-select">
                            @for ($level = 0; $level <= 8; $level++)
                                <option value="{{$level}}" @if($currentLevel == $level) selected @endif>{{$level}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="power">Power:</label>
                        <input type="text" id="power" name="power" value="{{ $power }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="core">Core:</label>
                        <input type="text" id="core" name="core" value="{{ $core }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="casual">Casual:</label>
                        <input type="text" id="casual" name="casual" value="{{ $casual }}" class="form-control">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="filter">Filter:</label>
                        <button type="submit" class="btn btn-primary w-100"><span>Filter</span></button>
                    </div>
                </div>
            </form>
            <div class="col-md-2 mb-3">
                <form action="{{ route('export-excel') }}" method="post" class="text-center mb-4">
                    @csrf
                    <input type="hidden" name="level" value="{{ $currentLevel }}">
                    <input type="hidden" name="power" value="{{ $power }}">
                    <input type="hidden" name="core" value="{{ $core }}">
                    <input type="hidden" name="casual" value="{{ $casual }}">
                    <button type="submit" class="btn btn-secondary w-100">Download</button>
                </form>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <h3>Review Statistics</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Total</th>
                        <th>Power: (≤{{$power}}) Days</th>
                        <th>Core: ({{$power}} - {{$core}}) Days</th>
                        <th>Casual: ({{$core}} - {{$casual}}) Days</th>
                        <th>Cold: ({{$casual}}++) Days</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ 
                            ($powerData->CustomerCount ?? 0) +
    ($casualData->CustomerCount ?? 0) +
    ($coreData->CustomerCount ?? 0) +
    ($coldData->CustomerCount ?? 0) 
                        }}</td>
                        <td>{{ $powerData->CustomerCount ?? 0 }}</td>
                        <td>{{ $casualData->CustomerCount ?? 0 }}</td>
                        <td>{{ $coreData->CustomerCount ?? 0 }}</td>
                        <td>{{ $coldData->CustomerCount ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td>Percentage</td>
                        <td>{{ $powerData->Percentage ?? 0 }}%</td>
                        <td>{{ $casualData->Percentage ?? 0 }}%</td>
                        <td>{{ $coreData->Percentage ?? 0 }}%</td>
                        <td>{{ $coldData->Percentage ?? 0 }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <h3>Customer Details</h3>
            <table id="levelDataTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Customer Id</th>
                        <th>User Name</th>
                        <th>First Name</th>
                        <th>Level Id</th>
                        <th>Start</th>
                        <th>Start Date</th>
                        <th>Days</th>
                    </tr>
                </thead>

            </table>

        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#levelDataTable').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajex: '{{route('getdata')}}',
                columns: [
                    {
                        data: 'CustomerID',
                        name: 'CustomerID',
                    },
                    {
                        data: 'Username',
                        name: 'Username',
                    },
                    {
                        data: 'FirstName',
                        name: 'FirstName',
                    },
                    {
                        data: 'LevelID',
                        name: 'LevelID',
                    },
                    {
                        data: 'Start',
                        name: 'Start',
                    },
                    {
                        data: 'Start_Date',
                        name: 'Start_Date',
                    },
                    {
                        data: 'Days',
                        name: 'Days',
                    },
                    // Add action column
                ]

            });
        });
    </script>
</body>

</html>