<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    {{-- CSS Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- CSS personnalisé --}}
    @vite('resources/css/permissions.css')
</head>

<body>



       <div class="container-fluid">
        <div class="row">
            <div class="sidebar me-3"> <!-- "me-3" = margin-end 1rem -->
                 @include('layouts.partials.admin.leftsidebar')
            </div>
            <div class="topbar me-3"> <!-- "me-3" = margin-end 1rem -->
                 @include('layouts.partials.admin.topbar')
            </div>
            <div class="main-content me-3">
                @yield('content')
            </div>
        </div>
    </div>



    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS personnalisé --}}
    @vite('resources/js/dashboard.js')

</body>

</html>