<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>PENS HEALTH INFORMATION</title>

        @include('layouts.includes._header-script')

        {{-- jika ada tambahan custom script pada header --}}
        @stack('header-scripts')
    </head>
    <body>
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    @include('layouts.includes.navbar')
                </nav>
            </div>

            {{-- membaca request http sesuai route --}}
            @if (Request::is('dashboard'))
                @yield('content')
            @else
                @yield('page-heading')

                <div class="wrapper wrapper-content animated fadeInRight">
                    @yield('content')
                </div>
            @endif

            <div class="footer">
                <div class="float-right">
                    
                </div>
                <div>
                    <strong>Copyright</strong> Politeknik Elektronika Negeri Surabaya &copy; {{date('Y')}}
                </div>
            </div>
        </div>
    </div>

    @include('layouts.includes._footer-script')

    {{-- jika ada tambahan custom script pada footer --}}
    @stack('footer-scripts')
    </body>
</html>
