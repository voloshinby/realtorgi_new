<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<script src="{{ asset('js/app.js') }}" defer></script>
<div id="app">
<router-view></router-view>
</div>
