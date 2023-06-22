<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MISSO XL') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        html{
            height: 100%;
        }

        body {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            background: url('https://images.unsplash.com/photo-1533664488202-6af66d26c44a?ixid=Mnw5MTMyMXwwfDF8c2VhcmNofDF8fHRlbGVjb21tdW5pY2F0aW9ufGVufDB8fHx8MTY4MzA4MzgzOA&ixlib=rb-4.0.3&w=1400') center center;
            background-size: contain;
            background-repeat: repeat;
            background-position: center;
        }

        footer {
            background-color: #f2f2f2;
            padding-top: 10px;
            margin-top: auto;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.6);
        }
    </style>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-danger shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'MISSO XL') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="btn btn-outline-light mx-2" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-outline-light mx-2" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ route('home') }}">Home</a>
                            </li>
                            @if(Auth::check())
                                @if(Auth::user()->role == 'operator')
                                    <li class="nav-item ">
                                        <a class="nav-link" href="{{ route('upload-data') }}">Upload Data</a>
                                    </li>
                                @elseif(Auth::user()->role == 'admin')
                                    <li class="nav-item ">
                                        <a class="nav-link" href="{{ route('user-management') }}">User Management</a>
                                    </li>
                                @endif
                            @endif

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    Monitoring
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" >
                                    @php
                                        $salesOrderTahun = \App\Models\SalesOrder::select('tahun')->groupBy('tahun')->get();
                                    @endphp
                                    @foreach ($salesOrderTahun as $item)
                                        <a class="dropdown-item" href="{{ route('sales-order.tahun', ['tahun' => $item->tahun]) }}">Sales Order {{ $item->tahun }}</a>
                                    @endforeach
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <footer class="footer ">
        <div class="container">
            <p>© 2023 Sales2 Dayamitra Telekomunikasi, All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="dropdown"]').click(function (e) {
                e.preventDefault();
                var $parent = $(this).parent();
                var $dropdownMenu = $parent.find('.dropdown-menu');

                // Tutup semua dropdown menu yang sedang terbuka
                $('.dropdown-menu.show').not($dropdownMenu).removeClass('show');
                $('.dropdown.show').not($parent).removeClass('show');

                // Buka atau tutup dropdown menu saat ini
                $parent.toggleClass('show');
                $dropdownMenu.toggleClass('show');
            });

            $(document).click(function (e) {
                var $target = $(e.target);

                // Tutup dropdown menu saat mengklik di luar dropdown
                if (!$target.is('.dropdown') && !$target.parents().is('.dropdown')) {
                    $('div.dropdown-menu.show').removeClass('show');
                    $('div.dropdown.show, li.dropdown.show').removeClass('show');
                }
            });
        });
    </script>
</body>
</html>
