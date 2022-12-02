<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta id="token" name="token" content="{{ csrf_token() }}">

    <title>Video PlatForm</title>
    {{-- BootStrap --}}

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
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-home">
                                الصفحه الرئيسية
                            </i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('history.index') }}">
                            <i class="fas fa-history">
                                سجل المشاهدة
                            </i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('videos.create') }}">
                            <i class="fas fa-upload">
                                رفع فيديو
                            </i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('videos.index') }}">
                            <i class="fas fa-play-circle">
                                فديوهاتى
                            </i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-film">
                                القنوات
                            </i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ">
                    @guest
                        <li class="mt-2 nav-item">
                            <a href="{{ route('login') }}" class="nav_link">{{ __('تسجيل الدخول') }}</a>
                        </li>
                        @if (Route::has('regester'))
                            <li class="mt-2 nav-item">
                                <a href="{{ route('register') }}" class="nav-link">{{ __('انشاء حساب') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="mt-2 nav-item dropdown justify-content-left ">
                            <a id="navbarDropdown" class="nav-link" href="#" data-toggle="dropdown">
                                <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                                    alt="{{ Auth::user()->name }}" />
                            </a>
                            <div class="px-2 mt-2 text-right dropdown-menu dropdown-menu-left">
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
                                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
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
    @yield('script')
</body>

</html>
