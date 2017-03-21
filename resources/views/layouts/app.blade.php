
<?php

    $uri = $_SERVER["REQUEST_URI"];
    session_start();

?>

@if( !isset( $_SESSION['usuario']) )

    @if( $uri != '/login' )

        <script type="text/javascript">
            window.location="{{ url('/login')  }}";
        </script>

    @endif

@endif


    @include('layouts.header')

    @yield('content')

    @include('layouts.footer')




