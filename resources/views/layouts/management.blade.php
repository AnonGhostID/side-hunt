<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Management Pekerjaan</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/management.css') }}">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>

<body id="body-pd">
    <div id="app">
        <header class="header" id="header">
            <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
            <div class="header_img"> <img src="https://i.imgur.com/hczKIze.jpeg" alt=""> </div>
            <!-- <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Profile
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </div> -->
        </header>
        <div class="l-navbar show" id="nav-bar">
            <nav class="nav">
                <div>
                    <div class="nav_logo">
                        <span class="nav_logo-name">Side-Hunt</span>
                    </div>
                    <div class="nav_list nav">
                        <a href="#overview" class="nav_link active" data-bs-toggle="tab">
                            <i class='bx bx-grid-alt nav_icon'></i>
                            <span class="nav_name">Overview</span>
                        </a>
                        <a href="#pengguna" class="nav_link" data-bs-toggle="tab">
                            <i class='bx bx-user nav_icon'></i>
                            <span class="nav_name">Dashboard Pekerjaan</span>
                        </a>
                        <a href="#pekerjaan" class="nav_link" data-bs-toggle="tab">
                            <i class="bi bi-suitcase-lg"></i>
                            <span class="nav_name">Status Pekerjaan</span>
                        </a>
                        <a href="#pelamars" class="nav_link" data-bs-toggle="tab">
                            <i class="bi bi-envelope-paper"></i>
                            <span class="nav_name">Upload Bukti</span>
                        </a>
                        <a href="#transaksi" class="nav_link" data-bs-toggle="tab">
                            <i class="bi bi-wallet2"></i>
                            <span class="nav_name">Transaksi</span>
                        </a>
                    </div>
                </div>
                <a class="logoutbtn" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class='bx bx-log-out nav_icon'></i>
                    <span class="nav_name">{{ __('Logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </nav>
        </div>
        <!--Container Main start-->
        <div>
            <main class="py-3">
                <div class="tab-content">
                    @yield('content')
                </div>
            </main>
        </div>
        <!--Container Main end-->
    </div>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {

            const showNavbar = (toggleId, navId, bodyId, headerId) => {
                const toggle = document.getElementById(toggleId),
                    nav = document.getElementById(navId),
                    bodypd = document.getElementById(bodyId),
                    headerpd = document.getElementById(headerId)

                // Validate that all variables exist
                if (toggle && nav && bodypd && headerpd) {
                    toggle.addEventListener('click', () => {
                        // show navbar
                        nav.classList.toggle('show')
                        // change icon
                        toggle.classList.toggle('bx-x')
                        // add padding to body
                        bodypd.classList.toggle('body-pd')
                        // add padding to header
                        headerpd.classList.toggle('body-pd')
                    })
                }
            }

            showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header')

            /*===== LINK ACTIVE =====*/
            const linkColor = document.querySelectorAll('.nav_link')

            function colorLink() {
                if (linkColor) {
                    linkColor.forEach(l => l.classList.remove('active'))
                    this.classList.add('active')
                }
            }
            linkColor.forEach(l => l.addEventListener('click', colorLink))

            // Your code to run since DOM is loaded and ready
        });
    </script>
</body>
</html>