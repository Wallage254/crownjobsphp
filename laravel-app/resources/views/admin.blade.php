@extends('layouts.app')

@section('title', 'Admin Dashboard - CrownOpportunities')
@section('description', 'Manage jobs, applications, testimonials and messages.')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 mr-4">
                        <i data-lucide="briefcase" class="h-6 w-6 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Jobs</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $jobs->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 mr-4">
                        <i data-lucide="users" class="h-6 w-6 text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Applications</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $applications->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 mr-4">
                        <i data-lucide="star" class="h-6 w-6 text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Testimonials</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $testimonials->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 mr-4">
                        <i data-lucide="mail" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Messages</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $messages->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button onclick="showTab('jobs')" id="jobs-tab" class="tab-button active py-4 px-6 border-b-2 border-blue-500 text-blue-600 font-medium">
                        Jobs
                    </button>
                    <button onclick="showTab('applications')" id="applications-tab" class="tab-button py-4 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        Applications
                    </button>
                    <button onclick="showTab('testimonials')" id="testimonials-tab" class="tab-button py-4 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        Testimonials
                    </button>
                    <button onclick="showTab('messages')" id="messages-tab" class="tab-button py-4 px-6 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        Messages
                    </button>
                </nav>
            </div>

            <!-- Jobs Tab -->
            <div id="jobs-content" class="tab-content p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Job Listings</h2>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Add New Job
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($jobs as $job)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $job->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $job->category }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->company }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->location }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($job->salary_min && $job->salary_max)
                                            £{{ number_format($job->salary_min) }} - £{{ number_format($job->salary_max) }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($job->is_urgent)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('job.detail', $job->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <button class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Applications Tab -->
            <div id="applications-content" class="tab-content p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Job Applications</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($applications as $application)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $application->first_name }} {{ $application->last_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->job->title ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->current_location }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($application->status === 'reviewed') bg-blue-100 text-blue-800
                                            @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($application->cv_file)
                                            <a href="{{ Storage::url($application->cv_file) }}" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3">CV</a>
                                        @endif
                                        <button class="text-green-600 hover:text-green-900 mr-3">Accept</button>
                                        <button class="text-red-600 hover:text-red-900">Reject</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Testimonials Tab -->
            <div id="testimonials-content" class="tab-content p-6 hidden">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Testimonials</h2>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Add Testimonial
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($testimonials as $testimonial)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                @if($testimonial->photo)
                                    <img src="{{ Storage::url($testimonial->photo) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover mr-4">
                                @endif
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $testimonial->name }}</h4>
                                    <p class="text-gray-600">{{ $testimonial->country }}</p>
                                </div>
                            </div>
                            
                            <div class="flex mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="h-4 w-4 {{ $i <= $testimonial->rating ? 'text-yellow-400 fill-current' : 'text-gray-300' }}"></i>
                                @endfor
                            </div>
                            
                            <p class="text-gray-700 text-sm mb-4">"{{ $testimonial->comment }}"</p>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $testimonial->created_at->format('M d, Y') }}</span>
                                <div>
                                    <button class="text-blue-600 hover:text-blue-900 text-sm mr-3">Edit</button>
                                    <button class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Messages Tab -->
            <div id="messages-content" class="tab-content p-6 hidden">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Contact Messages</h2>
                
                <div class="space-y-4">
                    @foreach($messages as $message)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $message->first_name }} {{ $message->last_name }}</h4>
                                    <p class="text-gray-600">{{ $message->email }}</p>
                                    <p class="text-sm text-gray-500">{{ $message->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                                @if(!$message->is_read)
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">New</span>
                                @endif
                            </div>
                            
                            <h5 class="font-medium text-gray-900 mb-2">{{ $message->subject }}</h5>
                            <p class="text-gray-700">{{ $message->message }}</p>
                            
                            <div class="mt-4 flex space-x-3">
                                @if(!$message->is_read)
                                    <button class="text-blue-600 hover:text-blue-900 text-sm">Mark as Read</button>
                                @endif
                                <button class="text-green-600 hover:text-green-900 text-sm">Reply</button>
                                <button class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTab(tabName) {
    // Hide all tab contents
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.add('hidden'));
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.tab-button');
    tabs.forEach(tab => {
        tab.classList.remove('active', 'border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-blue-500', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endpush
@endsection