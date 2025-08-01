// CrownOpportunities JavaScript Functions

// Global variables
let jobs = [];
let categories = [];
let testimonials = [];
let filteredJobs = [];

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

async function initializeApp() {
    try {
        showLoading('categories-grid');
        showLoading('jobs-grid');
        showLoading('testimonials-grid');
        
        await Promise.all([
            loadCategories(),
            loadJobs(),
            loadTestimonials()
        ]);
        
        setupEventListeners();
    } catch (error) {
        console.error('Error initializing app:', error);
        showError('Failed to load application data');
    }
}

// API Functions
async function apiRequest(endpoint, options = {}) {
    try {
        const response = await fetch(`api/${endpoint}`, {
            headers: {
                'Content-Type': 'application/json',
            },
            ...options
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error(`API request failed for ${endpoint}:`, error);
        throw error;
    }
}

// Load data functions
async function loadCategories() {
    try {
        const response = await apiRequest('categories.php');
        categories = response.data || [];
        renderCategories();
        populateFilterOptions();
    } catch (error) {
        showError('Failed to load categories', 'categories-grid');
    }
}

async function loadJobs() {
    try {
        const response = await apiRequest('jobs.php');
        jobs = response.data || [];
        filteredJobs = [...jobs];
        renderJobs();
        populateLocationFilter();
    } catch (error) {
        showError('Failed to load jobs', 'jobs-grid');
    }
}

async function loadTestimonials() {
    try {
        const response = await apiRequest('testimonials.php');
        testimonials = response.data || [];
        renderTestimonials();
    } catch (error) {
        showError('Failed to load testimonials', 'testimonials-grid');
    }
}

// Render functions
function renderCategories() {
    const grid = document.getElementById('categories-grid');
    
    if (categories.length === 0) {
        grid.innerHTML = '<p class="text-center text-gray-500 col-span-full">No categories available</p>';
        return;
    }
    
    grid.innerHTML = categories.map(category => `
        <div class="category-card p-6 rounded-lg cursor-pointer fade-in-up" onclick="filterJobsByCategory('${category.name}')">
            <div class="text-center">
                ${category.gif_url ? `<img src="${category.gif_url}" alt="${category.name}" class="w-16 h-16 mx-auto mb-4 rounded-full">` : '<div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center"><i class="fas fa-briefcase text-blue-600 text-2xl"></i></div>'}
                <h3 class="text-xl font-semibold text-gray-900 mb-2">${category.name}</h3>
                <p class="text-gray-600">${category.description || 'Explore opportunities in this field'}</p>
                <div class="mt-4">
                    <span class="text-blue-600 font-medium">View Jobs →</span>
                </div>
            </div>
        </div>
    `).join('');
}

function renderJobs() {
    const grid = document.getElementById('jobs-grid');
    
    if (filteredJobs.length === 0) {
        grid.innerHTML = '<p class="text-center text-gray-500 col-span-full">No jobs found matching your criteria</p>';
        return;
    }
    
    grid.innerHTML = filteredJobs.map(job => `
        <div class="job-card bg-white p-6 rounded-lg cursor-pointer fade-in-up" onclick="showJobDetails('${job.id}')">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">${job.title}</h3>
                    <p class="text-gray-600 mb-2"><i class="fas fa-building mr-2"></i>${job.company}</p>
                    <p class="text-gray-600 mb-2"><i class="fas fa-map-marker-alt mr-2"></i>${job.location}</p>
                </div>
                ${job.company_logo ? `<img src="${job.company_logo}" alt="${job.company}" class="w-12 h-12 rounded">` : ''}
            </div>
            
            <div class="mb-4">
                <span class="badge bg-blue-100 text-blue-800 mr-2">${job.category}</span>
                <span class="badge bg-green-100 text-green-800 mr-2">${job.job_type}</span>
                ${job.is_urgent ? '<span class="badge badge-urgent mr-2">Urgent</span>' : ''}
                ${job.visa_sponsored ? '<span class="badge badge-visa">Visa Sponsored</span>' : ''}
            </div>
            
            ${job.salary_min && job.salary_max ? `
                <div class="mb-4">
                    <p class="salary-range text-lg font-semibold">£${job.salary_min.toLocaleString()} - £${job.salary_max.toLocaleString()}</p>
                </div>
            ` : ''}
            
            <p class="text-gray-600 mb-4 line-clamp-3">${job.description}</p>
            
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Posted ${formatDate(job.created_at)}</span>
                <button class="btn btn-primary">View Details</button>
            </div>
        </div>
    `).join('');
}

function renderTestimonials() {
    const grid = document.getElementById('testimonials-grid');
    
    if (testimonials.length === 0) {
        grid.innerHTML = '<p class="text-center text-gray-500 col-span-full">No testimonials available</p>';
        return;
    }
    
    grid.innerHTML = testimonials.map(testimonial => `
        <div class="testimonial-card p-6 rounded-lg fade-in-up">
            <div class="flex items-center mb-4">
                ${testimonial.photo ? `<img src="${testimonial.photo}" alt="${testimonial.name}" class="w-12 h-12 rounded-full mr-4">` : '<div class="w-12 h-12 rounded-full bg-gray-300 mr-4 flex items-center justify-center"><i class="fas fa-user text-gray-600"></i></div>'}
                <div>
                    <h4 class="font-semibold text-gray-900">${testimonial.name}</h4>
                    <p class="text-gray-600">${testimonial.country}</p>
                </div>
            </div>
            
            <div class="rating-stars mb-3">
                ${Array.from({length: 5}, (_, i) => `<span class="star ${i < testimonial.rating ? '' : 'empty'}">★</span>`).join('')}
            </div>
            
            <p class="text-gray-700 mb-4">"${testimonial.comment}"</p>
            
            ${testimonial.video_url ? `
                <div class="mt-4">
                    <a href="${testimonial.video_url}" target="_blank" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-play-circle mr-2"></i>Watch Video
                    </a>
                </div>
            ` : ''}
        </div>
    `).join('');
}

// Event listeners
function setupEventListeners() {
    // Search functionality
    const searchInput = document.getElementById('search-jobs');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterJobs, 300));
    }
    
    // Filter functionality
    const categoryFilter = document.getElementById('filter-category');
    const locationFilter = document.getElementById('filter-location');
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterJobs);
    }
    
    if (locationFilter) {
        locationFilter.addEventListener('change', filterJobs);
    }
    
    // Contact form
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmit);
    }
    
    // Modal close functionality
    const modal = document.getElementById('job-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeJobModal();
            }
        });
    }
}

// Filter and search functions
function filterJobs() {
    const searchTerm = document.getElementById('search-jobs')?.value.toLowerCase() || '';
    const categoryFilter = document.getElementById('filter-category')?.value || '';
    const locationFilter = document.getElementById('filter-location')?.value || '';
    
    filteredJobs = jobs.filter(job => {
        const matchesSearch = !searchTerm || 
            job.title.toLowerCase().includes(searchTerm) ||
            job.company.toLowerCase().includes(searchTerm) ||
            job.description.toLowerCase().includes(searchTerm);
        
        const matchesCategory = !categoryFilter || job.category === categoryFilter;
        const matchesLocation = !locationFilter || job.location === locationFilter;
        
        return matchesSearch && matchesCategory && matchesLocation;
    });
    
    renderJobs();
}

function filterJobsByCategory(category) {
    const categoryFilter = document.getElementById('filter-category');
    if (categoryFilter) {
        categoryFilter.value = category;
        filterJobs();
    }
    
    // Scroll to jobs section
    document.getElementById('jobs').scrollIntoView({ behavior: 'smooth' });
}

// Populate filter options
function populateFilterOptions() {
    const categoryFilter = document.getElementById('filter-category');
    if (categoryFilter && categories.length > 0) {
        const categoryOptions = categories.map(cat => 
            `<option value="${cat.name}">${cat.name}</option>`
        ).join('');
        categoryFilter.innerHTML = '<option value="">All Categories</option>' + categoryOptions;
    }
}

function populateLocationFilter() {
    const locationFilter = document.getElementById('filter-location');
    if (locationFilter && jobs.length > 0) {
        const locations = [...new Set(jobs.map(job => job.location))];
        const locationOptions = locations.map(location => 
            `<option value="${location}">${location}</option>`
        ).join('');
        locationFilter.innerHTML = '<option value="">All Locations</option>' + locationOptions;
    }
}

// Job modal functions
async function showJobDetails(jobId) {
    const job = jobs.find(j => j.id === jobId);
    if (!job) return;
    
    const modalContent = document.getElementById('job-modal-content');
    modalContent.innerHTML = `
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div class="flex-1">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">${job.title}</h2>
                    <div class="flex items-center text-gray-600 mb-4">
                        <i class="fas fa-building mr-2"></i>${job.company}
                        <span class="mx-2">•</span>
                        <i class="fas fa-map-marker-alt mr-2"></i>${job.location}
                    </div>
                </div>
                <button onclick="closeJobModal()" class="text-gray-400 hover:text-gray-600 text-2xl">×</button>
            </div>
            
            <div class="mb-6">
                <span class="badge bg-blue-100 text-blue-800 mr-2">${job.category}</span>
                <span class="badge bg-green-100 text-green-800 mr-2">${job.job_type}</span>
                ${job.is_urgent ? '<span class="badge badge-urgent mr-2">Urgent</span>' : ''}
                ${job.visa_sponsored ? '<span class="badge badge-visa">Visa Sponsored</span>' : ''}
            </div>
            
            ${job.salary_min && job.salary_max ? `
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Salary Range</h3>
                    <p class="salary-range text-2xl font-bold">£${job.salary_min.toLocaleString()} - £${job.salary_max.toLocaleString()}</p>
                </div>
            ` : ''}
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Job Description</h3>
                <div class="text-gray-700 whitespace-pre-line">${job.description}</div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Requirements</h3>
                <div class="text-gray-700 whitespace-pre-line">${job.requirements}</div>
            </div>
            
            ${job.workplace_images && job.workplace_images.length > 0 ? `
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Workplace Images</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        ${job.workplace_images.map(img => `
                            <img src="${img}" alt="Workplace" class="rounded-lg shadow-md">
                        `).join('')}
                    </div>
                </div>
            ` : ''}
            
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="showApplicationForm('${job.id}')" class="btn btn-primary flex-1">
                    <i class="fas fa-paper-plane mr-2"></i>Apply Now
                </button>
                <button onclick="closeJobModal()" class="btn btn-secondary">
                    Close
                </button>
            </div>
        </div>
    `;
    
    document.getElementById('job-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeJobModal() {
    document.getElementById('job-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Application form
function showApplicationForm(jobId) {
    window.location.href = `apply.php?job=${jobId}`;
}

// Contact form handler
async function handleContactSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const data = {
        firstName: formData.get('firstName'),
        lastName: formData.get('lastName'),
        email: formData.get('email'),
        subject: formData.get('subject'),
        message: formData.get('message')
    };
    
    const submitButton = e.target.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    try {
        submitButton.textContent = 'Sending...';
        submitButton.disabled = true;
        
        const response = await apiRequest('contact.php', {
            method: 'POST',
            body: JSON.stringify(data)
        });
        
        if (response.success) {
            showSuccess('Message sent successfully! We\'ll get back to you soon.');
            e.target.reset();
        } else {
            throw new Error(response.message || 'Failed to send message');
        }
    } catch (error) {
        showError('Failed to send message. Please try again.');
    } finally {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    }
}

// Utility functions
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.innerHTML = '<div class="loading-spinner"></div>';
    }
}

function showError(message, elementId = null) {
    const content = `<div class="text-center text-red-600 p-4">${message}</div>`;
    
    if (elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = content;
        }
    } else {
        // Show global error notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 5000);
    }
}

function showSuccess(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 5000);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) return 'yesterday';
    if (diffDays < 7) return `${diffDays} days ago`;
    if (diffDays < 30) return `${Math.ceil(diffDays / 7)} weeks ago`;
    return date.toLocaleDateString();
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}