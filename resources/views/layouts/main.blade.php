<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta id="token" name="token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('theme/css/sb-admin-2.css') }}">
    <title>Video PlatForm</title>
    {{-- Pusher --}}
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <!-- Bootstrap CDN Links -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Tailwind CSS CDN Links -->
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    {{-- FontAwesome  --}}
    <script src="https://kit.fontawesome.com/ad7a78e71f.js" crossorigin="anonymous"></script>

    {{-- videojs --}}
    <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />

    @vite(['resources/css/style.css', 'resources/js/app.js'])


</head>

<body dir="rtl" style="text-align: right">
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Video PlatForm</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="mx-auto navbar-nav">
                    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('main') }}">
                            <i class="fas fa-home">
                                ???????????? ????????????????
                            </i>
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('history') ? 'active' : '' }}"
                                href="{{ route('history.index') }}">
                                <i class="fas fa-history">
                                    ?????? ????????????????
                                </i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('videos/create') ? 'active' : '' }}"
                                href="{{ route('videos.create') }}">
                                <i class="fas fa-upload">
                                    ?????? ??????????
                                </i>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('videos') ? 'active' : '' }}"
                                href="{{ route('videos.index') }}">
                                <i class="fas fa-play-circle">
                                    ????????????????
                                </i>
                            </a>
                        </li>
                    @endauth

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('channel') ? 'active' : '' }}"
                            href="{{ route('channel.index') }}">
                            <i class="fas fa-film">
                                ??????????????
                            </i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav mr-auto">

                    <div class="topbar" style="z-index: 1">
                        @auth
                            <li class="nav-item dropdown no-arrow mx-1 alert-dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter notif-count"
                                        data-count="{{ App\Models\Alert::where('user_id', Auth::user()->id)->first()->alert }}">{{ App\Models\Alert::where('user_id', Auth::user()->id)->first()->alert }}</span>
                                </a>
                                </a>
                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right text-right mt-2 shadow animated--grow-in"
                                    aria-labelledby="alertsDropdown">
                                    <h6 class="dropdown-header">
                                        ??????????????????
                                    </h6>

                                    <div class="alert-body">

                                    </div>
                                </div>
                            </li>
                        @endauth

                    </div>

                    @guest
                        <li class="mt-2 nav-item">
                            <a href="{{ route('login') }}" class="nav_link">{{ __('?????????? ????????????') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="nav-link">{{ __('?????????? ????????') }}</a>
                        </li>
                        @if (Route::has('regester'))
                            <li class="mt-2 nav-item">
                                <a href="{{ route('register') }}" class="nav-link">{{ __('?????????? ????????') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="mt-2 nav-item dropdown justify-content-left ">
                            <a id="navbarDropdown" class="nav-link" href="#" data-toggle="dropdown">
                                <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                                    alt="{{ Auth::user()->name }}" />
                            </a>
                            <div class="px-2 mt-2 text-right dropdown-menu dropdown-menu-left">
                                @can('update-video')
                                    <a class="dropdown-item" href="{{ route('admin.index') }}">
                                        <i class="fas fa-user-shield">
                                            ???????? ????????????
                                        </i>
                                    </a>
                                @endcan
                                <div class="pt-4 pb-1 border-t border-gray-200">
                                    <div class="flex items-center px-4">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <div class="mr-3 shrink-0">
                                                <img class="object-cover w-10 h-10 rounded-full"
                                                    src="{{ Auth::user()->profile_photo_url }}"
                                                    alt="{{ Auth::user()->name }}" />
                                            </div>
                                        @endif

                                        <div>
                                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}
                                            </div>
                                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                                        </div>
                                    </div>

                                    <div class="mt-3 space-y-1">
                                        <!-- Account Management -->
                                        <x-jet-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                                            {{ __('site.profile') }}
                                        </x-jet-responsive-nav-link>

                                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                            <x-jet-responsive-nav-link href="{{ route('api-tokens.index') }}"
                                                :active="request()->routeIs('api-tokens.index')">
                                                {{ __('API Tokens') }}
                                            </x-jet-responsive-nav-link>
                                        @endif

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}" x-data>
                                            @csrf

                                            <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                                @click.prevent="$root.submit();">
                                                {{ __('site.logout') }}
                                            </x-jet-responsive-nav-link>
                                        </form>

                                        <!-- Team Management -->
                                        @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                            <div class="border-t border-gray-200"></div>

                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('site.manage_team') }}
                                            </div>

                                            <!-- Team Settings -->
                                            <x-jet-responsive-nav-link
                                                href="{{ route('teams.show', Auth::user()->currentTeam->id) }}"
                                                :active="request()->routeIs('teams.show')">
                                                {{ __('site.team_settings') }}
                                            </x-jet-responsive-nav-link>

                                            @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                                <x-jet-responsive-nav-link href="{{ route('teams.create') }}"
                                                    :active="request()->routeIs('teams.create')">
                                                    {{ __('site.new_team') }}
                                                </x-jet-responsive-nav-link>
                                            @endcan

                                            <div class="border-t border-gray-200"></div>

                                            <!-- Team Switcher -->
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('site.team_switch') }}
                                            </div>

                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-jet-switchable-team :team="$team"
                                                    component="jet-responsive-nav-link" />
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>
        <main class="py-4">
            @if (Session::has('success'))
                <div class="p-3 mx-auto mb-2 text-white rounded bg-success col-8">
                    <span class="text-center">{{ session('success') }}</span>
                </div>
            @endif
            @yield('content')
        </main>
    </div>

    <script>
        var token = '{{ Session::token() }}';
        var urlNotify = '{{ route('notification') }}';
        $('#alertsDropdown').on('click', function(event) {
            event.preventDefault();
            var notificationsWrapper = $('.alert-dropdown');
            var notificationsToggle = notificationsWrapper.find('a[data-toggle]');
            var notificationsCountElem = notificationsToggle.find('span[data-count]');

            notificationsCount = 0;
            notificationsCountElem.attr('data-count', notificationsCount);
            notificationsWrapper.find('.notif-count').text(notificationsCount);
            notificationsWrapper.show();

            $.ajax({
                method: 'POST',
                url: urlNotify,
                data: {
                    _token: token
                },
                success: function(data) {
                    var resposeNotifications = "";
                    $.each(data.someNotifications, function(i, item) {
                        var responseDate = new Date(item.created_at);
                        var date = responseDate.getFullYear() + '-' + (responseDate.getMonth() +
                            1) + '-' + responseDate.getDate();
                        var time = responseDate.getHours() + ":" + responseDate.getMinutes() +
                            ":" + responseDate.getSeconds();

                        if (item.success) {
                            resposeNotifications += '<a class="dropdown-item d-flex align-items-center" href="#">\
                                                                                <div class="ml-3">\
                                                                                    <div class="icon-circle bg-secondary">\
                                                                                        <i class="far fa-bell text-white"></i>\
                                                                                    </div>\
                                                                                </div>\
                                                                                <div>\
                                                                                    <div class="small text-gray-500">' +
                                date +
                                ' ???????????? ' +
                                time +
                                '</div>\
                                                                                    <span>?????????????? ?????? ???? ???????????? ???????? ?????????????? <b>' +
                                item
                                .notification + '</b> ??????????</span>\
                                                                                </div>\
                                                                            </a>';
                        } else {
                            resposeNotifications += '<a class="dropdown-item d-flex align-items-center" href="#">\
                                                                                <div class="ml-3">\
                                                                                    <div class="icon-circle bg-secondary">\
                                                                                        <i class="far fa-bell text-white"></i>\
                                                                                    </div>\
                                                                                </div>\
                                                                                <div>\
                                                                                    <div class="small text-gray-500">' +
                                date +
                                ' ???????????? ' +
                                time +
                                '</div>\
                                                                                    <span>?????????? ?????? ?????? ?????? ?????????? ?????????? ???????????? ???????? ?????????????? <b>' +
                                item.notification + '</b> ???????? ???????? ?????? ????????</span>\
                                                                                </div>\
                                                                            </a>';
                        }

                        $('.alert-body').html(resposeNotifications);
                    });
                }
            });
        });
    </script>



    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('cca5b55f98667b555a47', {
            cluster: 'mt1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            alert(JSON.stringify(data));
        });
    </script>

    <script src="{{ asset('theme/js/pushNotifications.js') }}"></script>
    <script src="{{ asset('theme/js/failedNotifications.js.js') }}"></script>

    @yield('script')
</body>

</html>
