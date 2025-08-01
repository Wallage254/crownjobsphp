<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CrownOpportunities - From Africa to UK – Your Next Job Awaits')</title>
    <meta name="description" content="@yield('description', 'Connect with premium UK job opportunities in construction, healthcare, hospitality, and skilled trades. Visa sponsorship available for African professionals.')">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="@yield('title', 'CrownOpportunities - From Africa to UK – Your Next Job Awaits')">
    <meta property="og:description" content="@yield('description', 'Connect with premium UK job opportunities in construction, healthcare, hospitality, and skilled trades. Visa sponsorship available for African professionals.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'CrownOpportunities - From Africa to UK – Your Next Job Awaits')">
    <meta name="twitter:description" content="@yield('description', 'Connect with premium UK job opportunities in construction, healthcare, hospitality, and skilled trades. Visa sponsorship available for African professionals.')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'royal-blue': 'hsl(220, 91%, 42%)',
                        'emerald': 'hsl(158, 83%, 39%)',
                        'crown-orange': 'hsl(20, 91%, 48%)',
                        'yellow-accent': 'hsl(48, 100%, 67%)',
                        'primary': 'hsl(207, 90%, 54%)',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <style>
        /* Custom animations and styles matching the original design */
        @keyframes gradient-x {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .animate-gradient-x {
            background-size: 400% 400%;
            animation: gradient-x 6s ease infinite;
        }
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
        .animate-slide-up-delay {
            animation: slideUp 0.8s ease-out 0.3s both;
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        .job-card:hover {
            transform: translateY(-4px);
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, hsl(220, 91%, 42%), hsl(158, 83%, 39%), hsl(20, 91%, 48%));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    
    <!-- Lucide Icons via CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <!-- Navigation -->
    @include('components.navbar')
    
    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('components.footer')
    
    <!-- Scripts -->
    <script>
        lucide.createIcons();
        
        // Initialize mobile menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>