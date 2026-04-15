<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    {{-- CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- CSS personnalisé --}}
    @vite('resources/css/dashboard.css')
</head>

<body>
    <!-- Sidebar -->
    @include('layouts.partials.admin.leftsidebar')
    
    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            @include('layouts.partials.admin.topbar')
        </div>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS personnalisé --}}
    @vite('resources/js/dashboard.js')

</body>

</html>