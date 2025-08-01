<?php
require_once 'config/database.php';

$job_id = $_GET['job'] ?? '';
if (empty($job_id)) {
    header('Location: index.php');
    exit;
}

// Get job details
$db = getDB();
$stmt = $db->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for <?php echo htmlspecialchars($job['title']); ?> - CrownOpportunities</title>
    <meta name="description" content="Apply for <?php echo htmlspecialchars($job['title']); ?> at <?php echo htmlspecialchars($job['company']); ?> with visa sponsorship">
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-blue-600">CrownOpportunities</a>
                </div>
                <div class="flex items-center">
                    <a href="index.php" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md">← Back to Jobs</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Job Summary -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($job['title']); ?></h1>
                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="fas fa-building mr-2"></i><?php echo htmlspecialchars($job['company']); ?>
                        <span class="mx-2">•</span>
                        <i class="fas fa-map-marker-alt mr-2"></i><?php echo htmlspecialchars($job['location']); ?>
                    </div>
                </div>
                <?php if ($job['company_logo']): ?>
                    <img src="<?php echo htmlspecialchars($job['company_logo']); ?>" alt="<?php echo htmlspecialchars($job['company']); ?>" class="w-16 h-16 rounded">
                <?php endif; ?>
            </div>
            
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="badge bg-blue-100 text-blue-800"><?php echo htmlspecialchars($job['category']); ?></span>
                <span class="badge bg-green-100 text-green-800"><?php echo htmlspecialchars($job['job_type']); ?></span>
                <?php if ($job['is_urgent']): ?>
                    <span class="badge badge-urgent">Urgent</span>
                <?php endif; ?>
                <?php if ($job['visa_sponsored']): ?>
                    <span class="badge badge-visa">Visa Sponsored</span>
                <?php endif; ?>
            </div>
            
            <?php if ($job['salary_min'] && $job['salary_max']): ?>
                <div class="mb-4">
                    <p class="salary-range text-lg font-semibold">£<?php echo number_format($job['salary_min']); ?> - £<?php echo number_format($job['salary_max']); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Application Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Submit Your Application</h2>
            
            <form id="application-form" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">
                
                <!-- Personal Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required class="form-input">
                        </div>
                        <div>
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required class="form-input">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" id="email" name="email" required class="form-input">
                        </div>
                        <div>
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required class="form-input">
                        </div>
                    </div>
                    
                    <div>
                        <label for="current_location" class="form-label">Current Location *</label>
                        <input type="text" id="current_location" name="current_location" required class="form-input" placeholder="City, Country">
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h3>
                    
                    <div>
                        <label for="previous_role" class="form-label">Previous/Current Role</label>
                        <input type="text" id="previous_role" name="previous_role" class="form-input" placeholder="e.g., Senior Nurse, Construction Manager">
                    </div>
                    
                    <div>
                        <label for="experience" class="form-label">Years of Experience</label>
                        <select id="experience" name="experience" class="form-input">
                            <option value="">Select experience level</option>
                            <option value="0-1 years">0-1 years</option>
                            <option value="2-5 years">2-5 years</option>
                            <option value="5-10 years">5-10 years</option>
                            <option value="10+ years">10+ years</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="cover_letter" class="form-label">Cover Letter</label>
                        <textarea id="cover_letter" name="cover_letter" rows="5" class="form-input" placeholder="Tell us why you're the perfect fit for this role..."></textarea>
                    </div>
                </div>

                <!-- File Uploads -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Documents</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="form-input">
                            <p class="text-sm text-gray-500 mt-1">Optional: Upload a professional photo</p>
                        </div>
                        
                        <div>
                            <label for="cv_file" class="form-label">CV/Resume</label>
                            <input type="file" id="cv_file" name="cv_file" accept=".pdf,.doc,.docx" class="form-input">
                            <p class="text-sm text-gray-500 mt-1">Upload your CV (PDF, DOC, DOCX)</p>
                        </div>
                    </div>
                </div>

                <!-- Terms and Submit -->
                <div class="bg-blue-50 p-6 rounded-lg">
                    <div class="flex items-start mb-4">
                        <input type="checkbox" id="terms" name="terms" required class="mt-1 mr-3">
                        <label for="terms" class="text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-800">Terms of Service</a> and 
                            <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>. I consent to my data being 
                            processed for recruitment purposes.
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <script>
        document.getElementById('application-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            try {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
                submitButton.disabled = true;
                
                // Convert FormData to JSON for API
                const data = {};
                for (let [key, value] of formData.entries()) {
                    if (key !== 'profile_photo' && key !== 'cv_file') {
                        data[key] = value;
                    }
                }
                
                // TODO: Handle file uploads separately if needed
                // For now, we'll submit without files for simplicity
                
                const response = await fetch('api/applications.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Application submitted successfully! We\'ll be in touch soon.', 'success');
                    this.reset();
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 3000);
                } else {
                    throw new Error(result.message || 'Failed to submit application');
                }
            } catch (error) {
                showNotification('Failed to submit application: ' + error.message, 'error');
            } finally {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
        
        function showNotification(message, type) {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `p-4 rounded-lg shadow-lg mb-4 ${
                type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' : 
                'bg-red-100 text-red-800 border border-red-300'
            }`;
            notification.textContent = message;
            
            container.appendChild(notification);
            
            setTimeout(() => {
                container.removeChild(notification);
            }, 5000);
        }
    </script>
</body>
</html>