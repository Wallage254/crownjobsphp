<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center">
                <i data-lucide="crown" class="text-primary text-2xl mr-2"></i>
                <span class="text-xl font-bold text-gray-900">CrownOpportunities</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('jobs') }}" class="text-gray-700 hover:text-primary transition-colors {{ request()->routeIs('jobs') ? 'text-primary font-medium' : '' }}">
                    Find Jobs
                </a>
                <a href="{{ route('home') }}#testimonials" class="text-gray-700 hover:text-primary transition-colors {{ request()->routeIs('home') ? 'text-primary font-medium' : '' }}">
                    Success Stories
                </a>
                <a href="{{ route('home') }}#contact" class="text-gray-700 hover:text-primary transition-colors {{ request()->routeIs('home') ? 'text-primary font-medium' : '' }}">
                    Contact
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-700 hover:text-primary">
                <i data-lucide="menu" class="h-6 w-6"></i>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden border-t border-gray-200 py-4 hidden">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('jobs') }}" class="block text-gray-700 hover:text-primary transition-colors {{ request()->routeIs('jobs') ? 'text-primary font-medium' : '' }}">
                    Find Jobs
                </a>
                <a href="{{ route('home') }}#testimonials" class="block text-gray-700 hover:text-primary transition-colors {{ request()->routeIs('home') ? 'text-primary font-medium' : '' }}">
                    Success Stories
                </a>
                <a href="{{ route('home') }}#contact" class="block text-gray-700 hover:text-primary transition-colors {{ request()->routeIs('home') ? 'text-primary font-medium' : '' }}">
                    Contact
                </a>
            </div>
        </div>
    </div>
</nav>