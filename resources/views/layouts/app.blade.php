<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Event Management System</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
    <!-- Custom CSS -->
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="min-vh-100 d-flex flex-column">
    <header class="bg-pink-600 text-white shadow-sm">
        <nav class="container d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none text-white">
                    <h1 class="h3 mb-0 fw-bold">Event Management System</h1>
                </a>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('dashboard') }}" class="text-white text-decoration-none hover:text-gray-300">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <a href="{{ route('section') }}" class="text-white text-decoration-none hover:text-gray-300">
                    <i class="fas fa-clock me-2"></i> Session / Cookie
                </a>
                <a href="{{ route('eloquent') }}" class="text-white text-decoration-none hover:text-gray-300">
                    <i class="fas fa-database me-2"></i> Some Statistic
                </a>
                <a href="{{ route('send.email') }}" class="text-white text-decoration-none hover:text-gray-300">
                    <i class="fas fa-envelope me-2"></i> Send Email
                </a>
                <x-language-switcher />
            </div>
        </nav>
    </header>

    <main class="container flex-grow-1 py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>

    @stack('scripts')

    <footer class="bg-pink-600 text-white text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Event Management System. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>