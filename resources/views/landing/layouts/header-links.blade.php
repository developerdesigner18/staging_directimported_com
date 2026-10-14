<!--favicon icon-->
<link rel="shortcut icon" href="{{asset('assets/logo/' . getSetting()->favicon)}}">
<link rel="icon" href="{{asset('assets/logo/' . getSetting()->favicon)}}">

<!--canonical-->
<link rel="canonical" href="{{ rtrim(config('app.url'), '/') . '/' . ltrim(request()->path(), '/') }}">
@if(request()->is('login', 'register', 'forgot-password', 'reset-password/*'))
    <meta name="robots" content="noindex, follow">
@endif

<!-- Inject:css -->

<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/bootstrap.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/progresscircle.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/magnific-popup.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/slick.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/style.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/car-card.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/responsive.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/landing/css/header-footer.css')}}?v={{ filemtime(public_path('assets/landing/css/header-footer.css')) }}">


<link rel="preload" href="{{asset('assets/admin/libs/sweetalert2/sweetalert2.min.css')}}" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{asset('assets/admin/libs/sweetalert2/sweetalert2.min.css')}}">
</noscript>

<!-- jQuery UI CSS -->
<link rel="preload" href="{{asset('assets/vendor/jquery-ui/jquery-ui.css')}}" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{asset('assets/vendor/jquery-ui/jquery-ui.css')}}">
</noscript>

<link rel="preload" href="{{asset('assets/vendor/boxicons/latest/boxicons-basic.min.css')}}" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{asset('assets/vendor/boxicons/latest/boxicons-basic.min.css')}}">
</noscript>

<link rel="preload" href="{{asset('assets/vendor/boxicons/latest/boxicons-brands.min.css')}}" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{asset('assets/vendor/boxicons/latest/boxicons-brands.min.css')}}">
</noscript>

<!--datatable css-->
<link rel="preload" href="{{asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css')}}" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css')}}">
</noscript>

<link rel="preload" href="{{asset('assets/admin/libs/datatables/css/responsive.bootstrap.min.css')}}" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{asset('assets/admin/libs/datatables/css/responsive.bootstrap.min.css')}}">
</noscript>

<link rel="preload" href="{{asset('assets/admin/libs/datatables/css/buttons.dataTables.min.css')}}" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{asset('assets/admin/libs/datatables/css/buttons.dataTables.min.css')}}">
</noscript>