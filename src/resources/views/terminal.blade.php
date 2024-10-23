@extends('laravel-terminal::layouts.app')

@section('title', 'Laravel Terminal')

@section('content')
    <div class="header">
        @foreach (config('laravel-terminal.terminal.header') as $header)
            {!! $header !!}
            <br />
        @endforeach
        @if (config('laravel-terminal.terminal.showInteractiveWarning'))
            <div style="margin-top:10px; overflow: hidden;"><span style="color: #ff5c57">Warning:</span><span>
                    Running interactive
                    commands </span>can freeze your server until it's restarted<span>
                </span>
            </div>
        @endif
    </div>
    <div class="xterm">
        <span class="xterm-directory"></span>
        <span class="xterm-input"></span>
        <span class="xterm-dash"></span>
    </div>
@endsection
