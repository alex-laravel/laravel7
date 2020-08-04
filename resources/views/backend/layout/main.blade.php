<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} | @yield('title')</title>
        <link href="{{ mix('css/backend.css') }}" rel="stylesheet">
        @yield('styles')
    </head>
    <body>
        <div>
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ routeHome() }}">
                        {{ config('app.name') }}
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">

                        </ul>

                        <ul class="navbar-nav ml-auto">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('auth.login') }}">{{ trans('labels.auth.login.title') }}</a>
                                </li>

                                @if (Route::has('auth.register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('auth.register') }}">{{ trans('labels.auth.register.title') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ auth()->user()->name }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('form-logout').submit()">
                                            {{ trans('labels.auth.logout.title') }}
                                        </a>

                                        <form id="form-logout" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
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
                @include('shared.messages')
                @yield('content')
            </main>
        </div>
        <script src="{{ mix('js/backend.js') }}"></script>
        @yield('scripts')
    </body>
</html>
