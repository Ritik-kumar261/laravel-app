<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
        }
        .welcome-header {
            margin-top: 50px;
            text-align: center;
        }
        .welcome-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px 0;
        }
        .btn-custom {
            border-radius: 20px;
            padding: 10px 20px;
        }
        .logout-btn {
            position: absolute;
            top: 47px; 
             right: 20px; 
        } 
    </style>
</head>
<body>
  
     
   

   
       

        <div class="welcome-card text-center">
            <a href="{{route('account.dashboard')}}" class="btn btn-primary btn-custom"><button class="btn btn-primary btn-custom">My List</button></a>
            <a href="{{route('account.chartdashboard')}}" class="btn btn-secondary btn-custom">  <button class="btn btn-primary btn-custom">My Dashboard?</button> </a>
                          
        </div>
        </div>
        <x-welcome-header Auth::user() />
        <div class="welcome-header">
            <h1>Welcome, {{Auth::user()->name}}</h1>
            <p class="lead">We're glad to have you here. Explore the options below to get started.</p>
        </div>
       
        <x-welcome-header />
        <div class="mt-4">
            <p class="text-center"></p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>
</html>
