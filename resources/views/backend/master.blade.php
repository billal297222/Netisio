{{-- views/backend/master.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Codescandy" />

    <!-- Favicon + CSS -->
    @include('backend.partials.style')

    <title>@yield('title', 'Analytics | Dash UI')</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.239/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.239/pdf.worker.min.js";
</script>

</head>


<body>
    <main id="main-wrapper" class="main-wrapper">
        <div class="header">
            <!-- navbar -->
            <div class="navbar-custom navbar navbar-expand-lg">
                <div class="container-fluid px-0">
                    @include('backend.partials.logo')

                    {{-- @include('backend.partials.search') --}}


                    <!--Navbar nav -->
                    <ul
                        class="navbar-nav navbar-right-wrap ms-lg-auto d-flex nav-top-wrap align-items-center ms-4 ms-lg-0">
                        @include('backend.partials.nav_mode')
                        {{-- @include('backend.partials.nav_notification') --}}
                        @include('backend.partials.nav_profile')
                    </ul>
                </div>
            </div>
        </div>

        <!-- navbar vertical -->
        @include('backend.partials.navbar_vertical')

        <!-- Page Content from child views -->
        <div id="app-content">
            <div class="app-content-area">
                <div class="pt-10 pb-21 mt-n6 mx-n4"></div>
                <div class="container-fluid mt-n22">
                    @yield('content')
                </div>
            </div>
        </div>


        <!-- Scripts -->
        @include('backend.partials.script')
        @stack('script')
</body>

</html>
