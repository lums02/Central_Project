<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CENTRAL+ - Pour les Hôpitaux')</title>
    
    <!-- CSS externes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS personnalisé -->
    <link href="{{ asset('css/hopital.css') }}?v={{ time() }}" rel="stylesheet">
</head>
<body>

    <!-- Contenu principal -->
    <main>
        @yield('content')
    </main>


    <!-- JS externes -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- JS personnalisé -->
    <script src="{{ asset('js/hopital.js') }}?v={{ time() }}"></script>
</body>
</html>
