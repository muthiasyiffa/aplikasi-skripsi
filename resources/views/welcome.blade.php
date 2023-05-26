<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <title>MISSO</title>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark bg-danger shadow-sm">
        <div class="container d-flex">
            <a class="navbar-brand me-auto p-2" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" width="45" height="30" alt="logo">
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light mx-2">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-light mx-2 ">Register</a>        
        </div>
    </nav>
    <div class="welcome-container">  
        <div class="welcome-banner">
            <div class="welcome-container1">
                <h1 class="welcome-text">Welcome to Monitoring Information System !</h1>
                <span class="welcome-text1">
                Log in to find out information about XL sales orders
                </span>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p>© 2023 Sales2 Dayamitra Telekomunikasi, All Rights Reserved.</p>
        </div>
    </footer>
  </body>
</html>
