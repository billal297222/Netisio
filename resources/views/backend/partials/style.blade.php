<!-- Favicon icon-->
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('backend') }}/assets/images/favicon/favicon.ico" />

<!-- Color modes -->
<script src="{{ asset('backend') }}/assets/js/vendors/color-modes.js"></script>

<!-- Libs CSS -->
<link href="{{ asset('backend') }}/assets/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet" />
<link href="{{ asset('backend') }}/assets/libs/%40mdi/font/css/materialdesignicons.min.css" rel="stylesheet" />
<link href="{{ asset('backend') }}/assets/libs/simplebar/dist/simplebar.min.css" rel="stylesheet" />

<!-- Theme CSS -->
<link rel="stylesheet"href="{{ asset('backend') }}/assets/css/theme.min.css">

<link rel="stylesheet" href="{{ asset('backend/assets/css/theme.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">
 <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
 

<style>
    .nav-link.active {
        background-color: #378beb !important;
        /* orange background */
        color: #fff !important;
        /* white text */
        border-radius: 6px;
        /* rounded corners */
        font-weight: bold;
    }

   .nav-link .arrow-icon {
    transition: transform 0.3s;
}

.nav-link.collapsed .arrow-icon {
    transform: rotate(0deg); /* dropdown closed → arrow points down */
}

.nav-link:not(.collapsed) .arrow-icon {
    transform: rotate(-180deg); /* dropdown open → arrow points up */
}


</style>
