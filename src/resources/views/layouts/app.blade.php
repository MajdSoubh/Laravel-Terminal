<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf" content="{{ csrf_token() }}">
    <meta name="prompt" content="{{ config('wshell.terminal.prompt') }}">
    <meta name="directory" content="{{ route(config('wshell.route.as') . 'directory.show') }}">
    <meta name="command" content="{{ route(config('wshell.route.as') . 'command') }}">
    <link rel="stylesheet" href="{{ route(config('wshell.route.as') . 'asset.show', 'style.css') }}">
    <title>@yield('title', 'Web Shell')</title>
    @stack('styles')
</head>

<body>

    <main id='app'>
        @yield('content')
    </main>

    <script src="{{ route(config('wshell.route.as') . 'asset.show', 'main.js') }}"></script>
    @stack('scripts')

</body>

</html>
