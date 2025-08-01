<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Logo and Description -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-4">
                    <i data-lucide="crown" class="text-primary text-2xl mr-2"></i>
                    <span class="text-xl font-bold">CrownOpportunities</span>
                </div>
                <p class="text-gray-300 mb-4">
                    Connecting African professionals with premium UK job opportunities. 
                    Your pathway to career success in the United Kingdom.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="facebook" class="h-5 w-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="twitter" class="h-5 w-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="linkedin" class="h-5 w-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="instagram" class="h-5 w-5"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('jobs') }}" class="text-gray-300 hover:text-white transition-colors">Find Jobs</a></li>
                    <li><a href="{{ route('home') }}#testimonials" class="text-gray-300 hover:text-white transition-colors">Success Stories</a></li>
                    <li><a href="{{ route('home') }}#contact" class="text-gray-300 hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="#" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>
                </ul>
            </div>

            <!-- Categories -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Job Categories</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('jobs') }}?category=Construction" class="text-gray-300 hover:text-white transition-colors">Construction</a></li>
                    <li><a href="{{ route('jobs') }}?category=Healthcare" class="text-gray-300 hover:text-white transition-colors">Healthcare</a></li>
                    <li><a href="{{ route('jobs') }}?category=Hospitality" class="text-gray-300 hover:text-white transition-colors">Hospitality</a></li>
                    <li><a href="{{ route('jobs') }}?category=Engineering" class="text-gray-300 hover:text-white transition-colors">Engineering</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center">
            <p class="text-gray-300">
                © {{ date('Y') }} CrownOpportunities. All rights reserved. 
                <span class="mx-2">|</span>
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <span class="mx-2">|</span>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </p>
        </div>
    </div>
</footer>