@extends('layouts.app')

@section('title', $job->title . ' at ' . $job->company . ' - CrownOpportunities')
@section('description', 'Apply for ' . $job->title . ' position at ' . $job->company . '. ' . Str::limit($job->description, 150))

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Job Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-8">
                    <!-- Job Header -->
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center">
                            @if($job->company_logo)
                                <img 
                                    src="{{ Storage::url($job->company_logo) }}" 
                                    alt="{{ $job->company }}"
                                    class="w-16 h-16 rounded-lg object-cover mr-6"
                                />
                            @else
                                <div class="w-16 h-16 bg-primary rounded-lg flex items-center justify-center mr-6">
                                    <i data-lucide="building" class="h-8 w-8 text-white"></i>
                                </div>
                            @endif
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ $job->title }}</h1>
                                <p class="text-xl text-gray-600">{{ $job->company }}</p>
                            </div>
                        </div>
                        @if($job->is_urgent)
                            <span class="bg-red-100 text-red-800 text-sm font-medium px-4 py-2 rounded-full">
                                Urgent Hiring
                            </span>
                        @endif
                    </div>

                    <!-- Job Meta -->
                    <div class="flex flex-wrap items-center gap-6 mb-8 text-gray-600">
                        <div class="flex items-center">
                            <i data-lucide="map-pin" class="h-5 w-5 mr-2"></i>
                            {{ $job->location }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="briefcase" class="h-5 w-5 mr-2"></i>
                            {{ $job->category }}
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="clock" class="h-5 w-5 mr-2"></i>
                            {{ $job->job_type }}
                        </div>
                        @if($job->salary_min && $job->salary_max)
                            <div class="flex items-center">
                                <i data-lucide="pound-sterling" class="h-5 w-5 mr-2"></i>
                                £{{ number_format($job->salary_min) }} - £{{ number_format($job->salary_max) }}
                            </div>
                        @endif
                        @if($job->visa_sponsored)
                            <div class="flex items-center">
                                <i data-lucide="check-circle" class="h-5 w-5 mr-2 text-green-600"></i>
                                Visa Sponsored
                            </div>
                        @endif
                    </div>

                    <!-- Job Description -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Job Description</h2>
                        <div class="prose prose-lg max-w-none text-gray-700">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Requirements</h2>
                        <div class="prose prose-lg max-w-none text-gray-700">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>

                    <!-- Workplace Images -->
                    @if($job->workplace_images && count($job->workplace_images) > 0)
                        <div class="mb-8">
                            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Workplace Images</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($job->workplace_images as $image)
                                    <img 
                                        src="{{ Storage::url($image) }}" 
                                        alt="Workplace"
                                        class="w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                        onclick="openImageModal('{{ Storage::url($image) }}')"
                                    />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Application Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-24">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Apply for this Job</h3>
                    
                    <form id="application-form" enctype="multipart/form-data">
                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input 
                                        type="text" 
                                        name="first_name" 
                                        id="first_name"
                                        required
                                        class="w-full h-10 px-3 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                                    />
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input 
                                        type="text" 
                                        name="last_name" 
                                        id="last_name"
                                        required
                                        class="w-full h-10 px-3 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                                    />
                                </div>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email"
                                    required
                                    class="w-full h-10 px-3 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                                />
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    id="phone"
                                    required
                                    class="w-full h-10 px-3 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                                />
                            </div>
                            
                            <div>
                                <label for="current_location" class="block text-sm font-medium text-gray-700 mb-1">Current Location</label>
                                <input 
                                    type="text" 
                                    name="current_location" 
                                    id="current_location"
                                    required
                                    class="w-full h-10 px-3 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                                />
                            </div>
                            
                            <div>
                                <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-1">Profile Photo (Optional)</label>
                                <input 
                                    type="file" 
                                    name="profile_photo" 
                                    id="profile_photo"
                                    accept="image/*"
                                    class="w-full h-10 px-3 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-700"
                                />
                            </div>
                            
                            <div>
                                <label for="cv_file" class="block text-sm font-medium text-gray-700 mb-1">CV/Resume (Required)</label>
                                <input 
                                    type="file" 
                                    name="cv_file" 
                                    id="cv_file"
                                    accept=".pdf,.doc,.docx"
                                    required
                                    class="w-full h-10 px-3 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-700"
                                />
                            </div>
                            
                            <div>
                                <label for="previous_role" class="block text-sm font-medium text-gray-700 mb-1">Previous Role</label>
                                <input 
                                    type="text" 
                                    name="previous_role" 
                                    id="previous_role"
                                    class="w-full h-10 px-3 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                                />
                            </div>
                            
                            <div>
                                <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">Experience</label>
                                <textarea 
                                    name="experience" 
                                    id="experience"
                                    rows="3"
                                    class="w-full px-3 py-2 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                                ></textarea>
                            </div>
                            
                            <div>
                                <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-1">Cover Letter</label>
                                <textarea 
                                    name="cover_letter" 
                                    id="cover_letter"
                                    rows="4"
                                    class="w-full px-3 py-2 rounded-md border border-gray-200 focus:ring-2 focus:ring-primary focus:border-transparent"
                                ></textarea>
                            </div>
                            
                            <button 
                                type="submit" 
                                class="w-full bg-primary hover:bg-blue-700 text-white font-bold h-12 rounded-md transition-colors"
                            >
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="max-w-4xl max-h-full p-4">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        <button 
            onclick="closeImageModal()" 
            class="absolute top-4 right-4 text-white hover:text-gray-300 text-3xl"
        >
            ×
        </button>
    </div>
</div>

@push('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

document.getElementById('application-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('/api/applications', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            alert('Application submitted successfully! We will review your application and get back to you soon.');
            this.reset();
        } else {
            const errorData = await response.json();
            alert('Failed to submit application. Please check your information and try again.');
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    }
});

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endpush
@endsection