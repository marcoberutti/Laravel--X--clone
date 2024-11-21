<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Home') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

<style>
    body{
        transition: background-color 1s ease, color 1s ease;
    }
    .navbar{
        transition: background-color 1s ease, color 1s ease;
        
    }
    .navbar{
    box-shadow: inset 0px -4px 10px rgb(136, 136, 136)!important;
    }

    #theme{
        border: 1px solid grey;
        width: 45px;
        height: 22px;
        border-radius: 15px;
        position: relative;
        cursor: pointer;
        background-color: rgb(173, 173, 173);
        transition: background-color 1s ease;
        box-shadow: inset -5px 0px 5px 0px #797575;        
    }
    #selectTheme{
        border: 1px solid grey;
        width: 20px;
        height: 20px;
        border-radius: 15px;
        position: absolute;
        cursor: pointer;
        background-color: rgb(255, 255, 254);
        transition: transform 0.3s ease-in-out;
        margin-left:65px;
        z-index: 1000;
        box-shadow: inset -5px 0 10px 0 rgb(168, 168, 168);
        
    }
    ion-icon{
        margin: 2px 0 0 2px;
    }
</style>
</head>
<body>
    <div id="app"> 
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-lg custom-navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    Home
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <span id="theme"></span><span id="selectTheme"><ion-icon name="moon-outline"></ion-icon></span>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @if (auth()->check())
                        <div style="width: 500px; display:flex; flex-direction:row; height:35px; align-items:center;">
                            <label style="margin: 3px 10px 0 0;" for="exampleDataList" class="form-label col-2">Search users</label>
                            <input id="exampleDataList" class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Type to search..." style="width: 250px; height: 28px">
                            <datalist id="datalistOptions">
                                @foreach ($users as $user)
                                <option value="{{ $user->name }}" data-id="{{ $user->id }}">
                                @endforeach
                            </datalist>
                            <button style="margin-left: 5px; height: 28px;" class="btn btn-secondary btn-sm" id="checkUserButton">Check user</button>
                            </div>
                            <li class="nav-item">
                                <a id="navbarDropdown" class="nav-link" role="button" aria-haspopup="true" aria-expanded="false" v-pre href="{{ url('/tweets/create') }}" style="margin-right: 20px;">Tweets</a>
                            </li>
                        @endif
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    
                                    <a class="dropdown-item" href="{{ url('users/chart') }}">Dasboard insight</a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container col-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                </div>
            @endif
        </div>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')
<script>
$(document).ready(function() {
    let toggled = false;

    // Remove transition during page load
    $('body').css('transition', 'none');
    $('.navbar').css('transition', 'none');

    // Check localStorage for saved theme on page load
    if (localStorage.getItem('theme') === 'dark') {
        toggled = true;
        applyDarkTheme(false); // Apply theme without transition
    } else {
        applyLightTheme(false); // Apply theme without transition
    }

    // Theme toggle function
    function toggleTheme() {
        toggled = !toggled;

        // Re-enable transitions for toggling
        $('body, .navbar, #theme, #selectTheme').css('transition', '');

        if (toggled) {
            applyDarkTheme(true); // Apply theme with transition
            localStorage.setItem('theme', 'dark');
        } else {
            applyLightTheme(true); // Apply theme with transition
            localStorage.setItem('theme', 'light');
        }
    }

    // Apply dark theme (with or without transition)
    function applyDarkTheme(withTransition) {
        if (!withTransition) {
            $('body, .navbar, #theme, #selectTheme').css('transition', 'none'); // No transition for page load
        }

        $('#selectTheme').css({
            'transform': 'translateX(25px)',
            'background-color': 'white',
        });
        $("#selectTheme").html('<ion-icon name="sunny-outline" style="color:black;"></ion-icon>');
        $("#theme").css('background-color', 'grey');
        $('body').css({
            'background-color': '#212529',
            'color': 'white',
        });
        $(".navbar").removeClass('navbar-light bg-white').addClass('navbar-dark bg-dark');
    }

    // Apply light theme (with or without transition)
    function applyLightTheme(withTransition) {
        if (!withTransition) {
            $('body, .navbar, #theme, #selectTheme').css('transition', 'none'); // No transition for page load
        }

        $('#selectTheme').css({
            'transform': 'translateX(0px)',
            'background-color': 'white',
        });
        $("#selectTheme").html('<ion-icon name="moon-outline" style="color:black;"></ion-icon>');
        $("#theme").css('background-color', 'yellow');
        $('body').css({
            'background-color': 'white',
            'color': '#212529',
        });
        $(".navbar").removeClass('navbar-dark bg-dark').addClass('navbar-light bg-white');
    }

    // Set up click event for toggling theme
    $('#selectTheme, #theme').on('click', toggleTheme);


    $("#checkUserButton").on('click', function(evt){
        evt.preventDefault()
        const userName = $("#exampleDataList").val();
        const options = Array.from($("#datalistOptions option"));
        console.log(userName)
        console.log(options)
        let userId = null;

        options.forEach(option=> {
            if (option.value === userName){
                userId = option.getAttribute('data-id');
                console.log(option.value)
            }
        });

        if(userId){
            console.log(userId)
            window.location.href = `http://127.0.0.1:8000/users/find2/${userId}`
            console.log(window.location.href)
        } else {
            alert('user not found')
        }
    })
});
</script>
</body>
</html>
