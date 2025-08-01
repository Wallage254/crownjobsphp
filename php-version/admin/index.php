<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$db = getDB();

// Get dashboard statistics
$stats = [];

// Total jobs
$stmt = $db->prepare("SELECT COUNT(*) as count FROM jobs");
$stmt->execute();
$stats['jobs'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total applications
$stmt = $db->prepare("SELECT COUNT(*) as count FROM applications");
$stmt->execute();
$stats['applications'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Pending applications
$stmt = $db->prepare("SELECT COUNT(*) as count FROM applications WHERE status = 'pending'");
$stmt->execute();
$stats['pending_applications'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Unread messages
$stmt = $db->prepare("SELECT COUNT(*) as count FROM messages WHERE is_read = false");
$stmt->execute();
$stats['unread_messages'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Recent applications
$stmt = $db->prepare("
    SELECT a.*, j.title as job_title, j.company as job_company 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    ORDER BY a.created_at DESC 
    LIMIT 5
");
$stmt->execute();
$recent_applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Recent messages
$stmt = $db->prepare("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$recent_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CrownOpportunities</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-blue-600">CrownOpportunities Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="../index.php" class="text-gray-700 hover:text-blue-600" target="_blank">
                        <i class="fas fa-external-link-alt mr-2"></i>View Site
                    </a>
                    <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <nav class="mt-8">
                <div class="px-4 space-y-2">
                    <a href="index.php" class="bg-blue-100 text-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                    </a>
                    <a href="jobs.php" class="text-gray-700 hover:bg-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-briefcase mr-3"></i>Jobs
                    </a>
                    <a href="applications.php" class="text-gray-700 hover:bg-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-file-alt mr-3"></i>Applications
                        <?php if ($stats['pending_applications'] > 0): ?>
                            <span class="bg-red-100 text-red-800 ml-auto px-2 py-1 text-xs rounded-full"><?php echo $stats['pending_applications']; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="categories.php" class="text-gray-700 hover:bg-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-tags mr-3"></i>Categories
                    </a>
                    <a href="testimonials.php" class="text-gray-700 hover:bg-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-star mr-3"></i>Testimonials
                    </a>
                    <a href="messages.php" class="text-gray-700 hover:bg-gray-100 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-envelope mr-3"></i>Messages
                        <?php if ($stats['unread_messages'] > 0): ?>
                            <span class="bg-red-100 text-red-800 ml-auto px-2 py-1 text-xs rounded-full"><?php echo $stats['unread_messages']; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Dashboard Overview</h2>
                <p class="text-gray-600">Welcome to the CrownOpportunities admin panel</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500">
                            <i class="fas fa-briefcase text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Jobs</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['jobs']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500">
                            <i class="fas fa-file-alt text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Applications</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['applications']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Applications</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['pending_applications']; ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-500">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Unread Messages</p>
                            <p class="text-2xl font-semibold text-gray-900"><?php echo $stats['unread_messages']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Applications -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Applications</h3>
                    </div>
                    <div class="p-6">
                        <?php if (empty($recent_applications)): ?>
                            <p class="text-gray-500 text-center">No applications yet</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($recent_applications as $app): ?>
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900"><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($app['job_title']); ?> at <?php echo htmlspecialchars($app['job_company']); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo date('M j, Y', strtotime($app['created_at'])); ?></p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full <?php 
                                            echo $app['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                ($app['status'] === 'hired' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800');
                                        ?>">
                                            <?php echo ucfirst($app['status']); ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="applications.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All Applications →</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Messages -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Recent Messages</h3>
                    </div>
                    <div class="p-6">
                        <?php if (empty($recent_messages)): ?>
                            <p class="text-gray-500 text-center">No messages yet</p>
                        <?php else: ?>
                            <div class="space-y-4">
                                <?php foreach ($recent_messages as $msg): ?>
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="font-medium text-gray-900"><?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?></p>
                                            <?php if (!$msg['is_read']): ?>
                                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">New</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700 mb-1"><?php echo htmlspecialchars($msg['subject']); ?></p>
                                        <p class="text-sm text-gray-600 line-clamp-2"><?php echo htmlspecialchars(substr($msg['message'], 0, 100)) . '...'; ?></p>
                                        <p class="text-xs text-gray-500 mt-2"><?php echo date('M j, Y', strtotime($msg['created_at'])); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4 text-center">
                                <a href="messages.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All Messages →</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>