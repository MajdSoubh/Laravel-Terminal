@extends('wshell::layouts.app')

@section('title', 'Web Shell')

@section('content')
    <div class="header">
        @foreach (config('wshell.terminal.header') as $header)
            {!! $header !!}
            <br />
        @endforeach
    </div>
    <div class="xterm">
        <span class="xterm-directory"></span>
        <span class="xterm-input"></span>
        <span class="xterm-dash"></span>
    </div>
@endsection
