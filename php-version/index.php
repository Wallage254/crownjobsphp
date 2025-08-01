<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrownOpportunities - UK Jobs for African Professionals</title>
    <meta name="description" content="Find your dream job in the UK. CrownOpportunities connects African professionals with visa-sponsored opportunities in healthcare, construction, hospitality, and skilled trades.">
    
    <!-- Open Graph tags -->
    <meta property="og:title" content="CrownOpportunities - UK Jobs for African Professionals">
    <meta property="og:description" content="Connect with UK employers offering visa sponsorship for African professionals">
    <meta property="og:type" content="website">
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-blue-600">CrownOpportunities</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#jobs" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md">Jobs</a>
                    <a href="#categories" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md">Categories</a>
                    <a href="#testimonials" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md">Success Stories</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md">Contact</a>
                    <a href="admin/" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Your UK Career Awaits
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                Connect with top UK employers offering visa sponsorship for African professionals in healthcare, construction, hospitality, and skilled trades.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#jobs" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                    Browse Jobs
                </a>
                <a href="#contact" class="border border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-300">
                    Get Started
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Job Categories</h2>
                <p class="text-xl text-gray-600">Explore opportunities across various industries</p>
            </div>
            <div id="categories-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Categories will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Jobs Section -->
    <section id="jobs" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Latest Job Opportunities</h2>
                <p class="text-xl text-gray-600">Find your perfect role with visa sponsorship</p>
            </div>
            
            <!-- Search and Filter -->
            <div class="mb-8 flex flex-col md:flex-row gap-4">
                <input type="text" id="search-jobs" placeholder="Search jobs..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <select id="filter-category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                </select>
                <select id="filter-location" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Locations</option>
                </select>
            </div>
            
            <div id="jobs-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Jobs will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Success Stories</h2>
                <p class="text-xl text-gray-600">Hear from professionals who found their dream jobs</p>
            </div>
            <div id="testimonials-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonials will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Get In Touch</h2>
                <p class="text-xl text-gray-600">Have questions? We're here to help you succeed</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form id="contact-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" id="firstName" name="firstName" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" id="lastName" name="lastName" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" id="subject" name="subject" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                        <textarea id="message" name="message" rows="5" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">CrownOpportunities</h3>
                    <p class="text-gray-400">Connecting African professionals with UK career opportunities.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#jobs" class="hover:text-white">Jobs</a></li>
                        <li><a href="#categories" class="hover:text-white">Categories</a></li>
                        <li><a href="#testimonials" class="hover:text-white">Success Stories</a></li>
                        <li><a href="#contact" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Categories</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Healthcare</a></li>
                        <li><a href="#" class="hover:text-white">Construction</a></li>
                        <li><a href="#" class="hover:text-white">Hospitality</a></li>
                        <li><a href="#" class="hover:text-white">Skilled Trades</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i>info@crownopportunities.com</li>
                        <li><i class="fas fa-phone mr-2"></i>+44 20 1234 5678</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>London, UK</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 CrownOpportunities. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Job Modal -->
    <div id="job-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                <div id="job-modal-content">
                    <!-- Job details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="assets/script.js"></script>
</body>
</html>