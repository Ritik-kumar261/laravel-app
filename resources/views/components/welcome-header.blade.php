<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 11 Multi Auth</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
    /* Style for the hover effect on the links */
   
</style>
</head>

 <body class="bg-light">
    <nav class="navbar navbar-expand-md  shadow">
        <div class="row text-center m-3 ">
            <div class="col-md-3">
                <a class="dropdown-item fw-light  rounded" href="{{ route('account.dashboard') }}">My List</a>
            </div>
            <div class="col-md-3">
                <a class="dropdown-item fw-light  rounded" href="{{ route('account.chartdashboard') }}">Dashboard</a>
            </div>
            <div class="col-md-3">
                <a class="dropdown-item fw-light box rounded" href="{{ route('getdata') }}">Level Data</a>
            </div>
            <div class="col-md-3">
                <a class="dropdown-item fw-light   object-fit  " href="{{ route('reward.point') }}">Reward Points</a>
            </div>
        </div>
        <ul class="navbar-nav justify-content-end flex-grow-1">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#!" id="accountDropdown" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Hello, {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu border-0 shadow" aria-labelledby="accountDropdown">
                    <li><a class="dropdown-item" href="{{ route('account.logout') }}">Logout</a></li>
                    <li><a class="dropdown-item" href="{{route('account.updatepage') }}">Update</a></li>
                    

                </ul>
            </li>
        </ul>

        </div>

    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
</body> 

</html>