<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            display: flex;
            height: 100%;
            overflow-y: auto;
            padding: 40px;
        }
        .sidebar {
          
            padding: 20px;
            min-width: 200px;
            background-color: #f8f9fa;
            height: 100%; /* Full height for the sidebar */
            top: 20px; /* Stick to the top */
            overflow-y: auto; /* Enable scrolling for the sidebar */
        }
        .sidebar .nav-link {
            color: #333;
        }
        .sidebar .nav-link.active {
            font-weight: bold;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto; /* Allows scrolling for content */
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <nav class="nav flex-column sticky-top">
            <a class="nav-link active" href="{{route('account.chartdashboard')}}">Dashboard</a>
            <a class="nav-link" href="{{route('account.updatepage') }}">Update</a>
            <a class="nav-link" href="{{route('getdata')}}">Level-Data</a>
            <a class="nav-link" href="{{ route('account.logout') }}">Logout</a>
            <a class="nav-link" href="{{route('reward.point')}}">reward-points</a>

            <!-- Add more links as needed -->
        </nav>
    </div>



</body>
</html>
