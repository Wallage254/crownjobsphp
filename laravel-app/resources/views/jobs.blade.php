@extends('layouts.app')

@section('title', 'Find Jobs - CrownOpportunities')
@section('description', 'Browse available UK job opportunities with visa sponsorship for African professionals.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Search and Filters -->
    <section class="bg-white shadow-sm py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('jobs') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-3 top-3 h-5 w-5 text-gray-400"></i>
                    <input 
                        type="text" 
                        name="search"
                        placeholder="Job title, company..."
                        value="{{ request('search') }}"
                        class="pl-10 h-12 w-full rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                    />
                </div>

                <div class="relative">
                    <i data-lucide="briefcase" class="absolute left-3 top-3 h-5 w-5 text-gray-400 z-10"></i>
                    <select name="category" class="pl-10 h-12 w-full rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <i data-lucide="map-pin" class="absolute left-3 top-3 h-5 w-5 text-gray-400 z-10"></i>
                    <select name="location" class="pl-10 h-12 w-full rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">Location</option>
                        <option value="London" {{ request('location') == 'London' ? 'selected' : '' }}>London</option>
                        <option value="Manchester" {{ request('location') == 'Manchester' ? 'selected' : '' }}>Manchester</option>
                        <option value="Birmingham" {{ request('location') == 'Birmingham' ? 'selected' : '' }}>Birmingham</option>
                        <option value="Leeds" {{ request('location') == 'Leeds' ? 'selected' : '' }}>Leeds</option>
                        <option value="Liverpool" {{ request('location') == 'Liverpool' ? 'selected' : '' }}>Liverpool</option>
                    </select>
                </div>

                <div class="relative">
                    <i data-lucide="pound-sterling" class="absolute left-3 top-3 h-5 w-5 text-gray-400"></i>
                    <input 
                        type="number" 
                        name="salaryMin"
                        placeholder="Min Salary"
                        value="{{ request('salaryMin') }}"
                        class="pl-10 h-12 w-full rounded-lg border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                    />
                </div>

                <button type="submit" class="bg-primary hover:bg-blue-700 text-white font-bold h-12 px-8 rounded-lg transition-colors flex items-center justify-center">
                    <i data-lucide="search" class="mr-2 h-5 w-5"></i>
                    Search
                </button>
            </form>
        </div>
    </section>

    <!-- Job Results -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    Job Opportunities 
                    <span class="text-gray-500 text-lg font-normal">({{ $jobs->count() }} jobs found)</span>
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Job Listings -->
                <div class="lg:col-span-2 space-y-6">
                    @forelse($jobs as $job)
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow p-6 job-card">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    @if($job->company_logo)
                                        <img 
                                            src="{{ Storage::url($job->company_logo) }}" 
                                            alt="{{ $job->company }}"
                                            class="w-12 h-12 rounded-lg object-cover mr-4"
                                        />
                                    @else
                                        <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mr-4">
                                            <i data-lucide="building" class="h-6 w-6 text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $job->title }}</h3>
                                        <p class="text-gray-600">{{ $job->company }}</p>
                                    </div>
                                </div>
                                @if($job->is_urgent)
                                    <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                                        Urgent
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-4 mb-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i data-lucide="map-pin" class="h-4 w-4 mr-1"></i>
                                    {{ $job->location }}
                                </div>
                                <div class="flex items-center">
                                    <i data-lucide="briefcase" class="h-4 w-4 mr-1"></i>
                                    {{ $job->category }}
                                </div>
                                <div class="flex items-center">
                                    <i data-lucide="clock" class="h-4 w-4 mr-1"></i>
                                    {{ $job->job_type }}
                                </div>
                                @if($job->salary_min && $job->salary_max)
                                    <div class="flex items-center">
                                        <i data-lucide="pound-sterling" class="h-4 w-4 mr-1"></i>
                                        £{{ number_format($job->salary_min) }} - £{{ number_format($job->salary_max) }}
                                    </div>
                                @endif
                            </div>

                            <p class="text-gray-700 mb-4 line-clamp-3">{{ $job->description }}</p>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    @if($job->visa_sponsored)
                                        <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                                            <i data-lucide="check" class="h-3 w-3 mr-1 inline"></i>
                                            Visa Sponsored
                                        </span>
                                    @endif
                                </div>
                                <a 
                                    href="{{ route('job.detail', $job->id) }}" 
                                    class="bg-primary hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-colors"
                                >
                                    View Details
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i data-lucide="search" class="h-16 w-16 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No jobs found</h3>
                            <p class="text-gray-600">Try adjusting your search criteria to find more opportunities.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Job Tips -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Search Tips</h3>
                        <ul class="space-y-3 text-sm text-gray-600">
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="h-4 w-4 text-green-500 mr-2 mt-0.5"></i>
                                Update your CV regularly
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="h-4 w-4 text-green-500 mr-2 mt-0.5"></i>
                                Tailor your application for each job
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="h-4 w-4 text-green-500 mr-2 mt-0.5"></i>
                                Highlight relevant experience
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="h-4 w-4 text-green-500 mr-2 mt-0.5"></i>
                                Research the company beforehand
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Categories -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Categories</h3>
                        <div class="space-y-2">
                            @foreach($categories->take(5) as $category)
                                <a 
                                    href="{{ route('jobs') }}?category={{ $category->name }}" 
                                    class="block text-sm text-gray-600 hover:text-primary transition-colors"
                                >
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection