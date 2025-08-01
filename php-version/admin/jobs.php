<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db = getDB();

// Get all jobs
$stmt = $db->prepare("SELECT * FROM jobs ORDER BY created_at DESC");
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - CrownOpportunities Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include 'includes/nav.php'; ?>
    
    <div class="flex">
        <?php include 'includes/sidebar.php'; ?>
        
        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Manage Jobs</h2>
                    <p class="text-gray-600">Create and manage job postings</p>
                </div>
                <button onclick="showJobModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Add New Job
                </button>
            </div>
            
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($jobs as $job): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($job['title']); ?></div>
                                            <?php if ($job['salary_min'] && $job['salary_max']): ?>
                                                <div class="text-sm text-gray-500">£<?php echo number_format($job['salary_min']); ?> - £<?php echo number_format($job['salary_max']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($job['company']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full"><?php echo htmlspecialchars($job['category']); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($job['location']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-1">
                                            <?php if ($job['is_urgent']): ?>
                                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Urgent</span>
                                            <?php endif; ?>
                                            <?php if ($job['visa_sponsored']): ?>
                                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Visa</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('M j, Y', strtotime($job['created_at'])); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editJob('<?php echo $job['id']; ?>')" class="text-blue-600 hover:text-blue-900 mr-4">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteJob('<?php echo $job['id']; ?>')" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Modal -->
    <div id="job-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 id="modal-title" class="text-lg font-medium text-gray-900">Add New Job</h3>
                        <button onclick="closeJobModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <form id="job-form" class="space-y-6">
                        <input type="hidden" id="job-id" name="job_id">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Job Title *</label>
                                <input type="text" id="title" name="title" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700">Company *</label>
                                <input type="text" id="company" name="company" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                                <select id="category" name="category" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select category</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Construction">Construction</option>
                                    <option value="Hospitality">Hospitality</option>
                                    <option value="Skilled Trades">Skilled Trades</option>
                                    <option value="Education">Education</option>
                                    <option value="Technology">Technology</option>
                                </select>
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                                <input type="text" id="location" name="location" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="salary_min" class="block text-sm font-medium text-gray-700">Min Salary (£)</label>
                                <input type="number" id="salary_min" name="salary_min" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="salary_max" class="block text-sm font-medium text-gray-700">Max Salary (£)</label>
                                <input type="number" id="salary_max" name="salary_max" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="job_type" class="block text-sm font-medium text-gray-700">Job Type</label>
                                <select id="job_type" name="job_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Temporary">Temporary</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Job Description *</label>
                            <textarea id="description" name="description" rows="4" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <div>
                            <label for="requirements" class="block text-sm font-medium text-gray-700">Requirements *</label>
                            <textarea id="requirements" name="requirements" rows="4" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_urgent" name="is_urgent" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_urgent" class="ml-2 block text-sm text-gray-900">Mark as Urgent</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="visa_sponsored" name="visa_sponsored" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="visa_sponsored" class="ml-2 block text-sm text-gray-900">Visa Sponsored</label>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="closeJobModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Job</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let editingJobId = null;
        
        function showJobModal(jobId = null) {
            editingJobId = jobId;
            const modal = document.getElementById('job-modal');
            const title = document.getElementById('modal-title');
            const form = document.getElementById('job-form');
            
            if (jobId) {
                title.textContent = 'Edit Job';
                // Load job data for editing
                loadJobData(jobId);
            } else {
                title.textContent = 'Add New Job';
                form.reset();
                document.getElementById('visa_sponsored').checked = true;
            }
            
            modal.classList.remove('hidden');
        }
        
        function closeJobModal() {
            document.getElementById('job-modal').classList.add('hidden');
            editingJobId = null;
        }
        
        async function loadJobData(jobId) {
            try {
                const response = await fetch(`../api/jobs.php?id=${jobId}`);
                const result = await response.json();
                
                if (result.success) {
                    const job = result.data;
                    document.getElementById('job-id').value = job.id;
                    document.getElementById('title').value = job.title;
                    document.getElementById('company').value = job.company;
                    document.getElementById('category').value = job.category;
                    document.getElementById('location').value = job.location;
                    document.getElementById('salary_min').value = job.salary_min || '';
                    document.getElementById('salary_max').value = job.salary_max || '';
                    document.getElementById('job_type').value = job.job_type;
                    document.getElementById('description').value = job.description;
                    document.getElementById('requirements').value = job.requirements;
                    document.getElementById('is_urgent').checked = job.is_urgent;
                    document.getElementById('visa_sponsored').checked = job.visa_sponsored;
                }
            } catch (error) {
                alert('Failed to load job data');
            }
        }
        
        function editJob(jobId) {
            showJobModal(jobId);
        }
        
        async function deleteJob(jobId) {
            if (confirm('Are you sure you want to delete this job? This will also delete all applications for this job.')) {
                try {
                    const response = await fetch(`../api/jobs.php?id=${jobId}`, {
                        method: 'DELETE'
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete job: ' + result.message);
                    }
                } catch (error) {
                    alert('Failed to delete job');
                }
            }
        }
        
        document.getElementById('job-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                if (key === 'is_urgent' || key === 'visa_sponsored') {
                    data[key] = this.querySelector(`[name="${key}"]`).checked;
                } else if (key === 'salary_min' || key === 'salary_max') {
                    data[key] = value ? parseInt(value) : null;
                } else {
                    data[key] = value;
                }
            }
            
            try {
                const url = editingJobId ? `../api/jobs.php?id=${editingJobId}` : '../api/jobs.php';
                const method = editingJobId ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    location.reload();
                } else {
                    alert('Failed to save job: ' + result.message);
                }
            } catch (error) {
                alert('Failed to save job');
            }
        });
    </script>
</body>
</html>