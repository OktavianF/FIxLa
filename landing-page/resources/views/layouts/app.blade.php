<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="Aplikasi pelaporan jalan rusak untuk masyarakat Lamongan">
    <title>@yield('title', 'FixLA') - Laporan Jalan Rusak</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1 0 auto;
        }
        
        footer {
            flex-shrink: 0;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        #navbar {
            position: sticky;
            top: 0;
            z-index: 9999 !important;
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .delay-100 {
            animation-delay: 0.1s;
        }
        
        .delay-200 {
            animation-delay: 0.2s;
        }
        
        .delay-300 {
            animation-delay: 0.3s;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    @include('partials.navbar')
    
    <main>
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>