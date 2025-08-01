<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="w-64 bg-white shadow-lg min-h-screen">
    <nav class="mt-8">
        <div class="px-4 space-y-2">
            <a href="index.php" class="<?php echo $current_page === 'index.php' ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
            </a>
            <a href="jobs.php" class="<?php echo $current_page === 'jobs.php' ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-briefcase mr-3"></i>Jobs
            </a>
            <a href="applications.php" class="<?php echo $current_page === 'applications.php' ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-file-alt mr-3"></i>Applications
            </a>
            <a href="categories.php" class="<?php echo $current_page === 'categories.php' ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-tags mr-3"></i>Categories
            </a>
            <a href="testimonials.php" class="<?php echo $current_page === 'testimonials.php' ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-star mr-3"></i>Testimonials
            </a>
            <a href="messages.php" class="<?php echo $current_page === 'messages.php' ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100'; ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                <i class="fas fa-envelope mr-3"></i>Messages
            </a>
        </div>
    </nav>
</div>