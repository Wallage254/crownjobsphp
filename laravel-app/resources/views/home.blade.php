@extends('layouts.app')

@section('title', 'CrownOpportunities - From Africa to UK – Your Next Job Awaits')
@section('description', 'Connect with premium UK job opportunities in construction, healthcare, hospitality, and skilled trades. Visa sponsorship available for African professionals.')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 via-emerald-600 to-orange-600 text-white py-20 lg:py-32 overflow-hidden">
        <!-- Animated background -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-emerald-600 to-orange-600 animate-gradient-x"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-slide-up">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    From Africa to UK –<br />
                    <span class="text-yellow-300">Your Next Job Awaits</span>
                </h1>
                <p class="text-xl md:text-2xl mb-12 max-w-3xl mx-auto opacity-90">
                    Connect with premium UK job opportunities in construction, healthcare, hospitality, and skilled trades
                </p>
            </div>
            
            <!-- Search Bar -->
            <div class="max-w-4xl mx-auto animate-slide-up-delay">
                <div class="bg-white rounded-2xl p-6 shadow-2xl">
                    <form action="{{ route('jobs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3 top-3 h-5 w-5 text-gray-400"></i>
                            <input 
                                type="text" 
                                name="search"
                                placeholder="Job title or keyword"
                                value="{{ request('search') }}"
                                class="pl-10 h-12 w-full rounded-lg border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary focus:border-transparent"
                            />
                        </div>
                        
                        <div class="relative">
                            <i data-lucide="map-pin" class="absolute left-3 top-3 h-5 w-5 text-gray-400 z-10"></i>
                            <select name="location" class="pl-10 h-12 w-full rounded-lg border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Your Location</option>
                                <option value="Nigeria" {{ request('location') == 'Nigeria' ? 'selected' : '' }}>Nigeria</option>
                                <option value="Kenya" {{ request('location') == 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                <option value="Ghana" {{ request('location') == 'Ghana' ? 'selected' : '' }}>Ghana</option>
                                <option value="South Africa" {{ request('location') == 'South Africa' ? 'selected' : '' }}>South Africa</option>
                                <option value="Uganda" {{ request('location') == 'Uganda' ? 'selected' : '' }}>Uganda</option>
                                <option value="Rwanda" {{ request('location') == 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                                <option value="Tanzania" {{ request('location') == 'Tanzania' ? 'selected' : '' }}>Tanzania</option>
                                <option value="Ethiopia" {{ request('location') == 'Ethiopia' ? 'selected' : '' }}>Ethiopia</option>
                            </select>
                        </div>
                        
                        <div class="relative">
                            <i data-lucide="briefcase" class="absolute left-3 top-3 h-5 w-5 text-gray-400 z-10"></i>
                            <select name="category" class="pl-10 h-12 w-full rounded-lg border border-gray-200 text-gray-900 focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Job Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="bg-primary hover:bg-blue-700 text-white font-bold h-12 px-8 rounded-lg transition-colors flex items-center justify-center">
                            <i data-lucide="search" class="mr-2 h-5 w-5"></i>
                            Search Jobs
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Categories -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Popular Job Categories</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($categories->where('is_active', true) as $category)
                    <a href="{{ route('jobs') }}?category={{ $category->name }}" class="bg-white border hover:shadow-lg transition-all cursor-pointer group rounded-lg p-6 text-center">
                        @if($category->gif_url)
                            <img 
                                src="{{ $category->gif_url }}" 
                                alt="{{ $category->name }}"
                                class="w-16 h-16 mx-auto mb-4 rounded-lg object-cover group-hover:scale-105 transition-transform"
                            />
                        @endif
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                        <span class="text-blue-600 font-semibold">View Jobs →</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Success Stories</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonials->take(6) as $testimonial)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center mb-4">
                            @if($testimonial->photo)
                                <img 
                                    src="{{ Storage::url($testimonial->photo) }}" 
                                    alt="{{ $testimonial->name }}"
                                    class="w-12 h-12 rounded-full object-cover mr-4"
                                />
                            @endif
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $testimonial->name }}</h4>
                                <p class="text-gray-600">{{ $testimonial->country }}</p>
                            </div>
                        </div>
                        
                        <div class="flex mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="h-5 w-5 {{ $i <= $testimonial->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        
                        <p class="text-gray-700 italic">"{{ $testimonial->comment }}"</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section id="contact" class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Get in Touch</h2>
            <form action="/api/contact" method="POST" class="space-y-6" id="contact-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input 
                            type="text" 
                            name="first_name" 
                            id="first_name"
                            required
                            class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input 
                            type="text" 
                            name="last_name" 
                            id="last_name"
                            required
                            class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                        />
                    </div>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        required
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                    />
                </div>
                
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input 
                        type="text" 
                        name="subject" 
                        id="subject"
                        required
                        class="w-full h-12 px-4 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                    />
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea 
                        name="message" 
                        id="message"
                        rows="6"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                    ></textarea>
                </div>
                
                <button 
                    type="submit" 
                    class="w-full bg-primary hover:bg-blue-700 text-white font-bold h-12 rounded-lg transition-colors"
                >
                    Send Message
                </button>
            </form>
        </div>
    </section>
</div>

@push('scripts')
<script>
document.getElementById('contact-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('/api/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            alert('Message sent successfully!');
            this.reset();
        } else {
            alert('Failed to send message. Please try again.');
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
});
</script>
@endpush
@endsection