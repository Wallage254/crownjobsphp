<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Get single application
                $stmt = $db->prepare("
                    SELECT a.*, j.title as job_title, j.company as job_company 
                    FROM applications a 
                    JOIN jobs j ON a.job_id = j.id 
                    WHERE a.id = ?
                ");
                $stmt->execute([$_GET['id']]);
                $application = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($application) {
                    echo json_encode(['success' => true, 'data' => $application]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Application not found']);
                }
            } else if (isset($_GET['job_id'])) {
                // Get applications for a specific job
                $stmt = $db->prepare("
                    SELECT a.*, j.title as job_title, j.company as job_company 
                    FROM applications a 
                    JOIN jobs j ON a.job_id = j.id 
                    WHERE a.job_id = ? 
                    ORDER BY a.created_at DESC
                ");
                $stmt->execute([$_GET['job_id']]);
                $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['success' => true, 'data' => $applications]);
            } else {
                // Get all applications (admin only)
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
                $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
                $status = isset($_GET['status']) ? $_GET['status'] : '';
                
                $whereClause = '';
                $params = [];
                
                if (!empty($status)) {
                    $whereClause = 'WHERE a.status = ?';
                    $params[] = $status;
                }
                
                $stmt = $db->prepare("
                    SELECT a.*, j.title as job_title, j.company as job_company 
                    FROM applications a 
                    JOIN jobs j ON a.job_id = j.id 
                    $whereClause
                    ORDER BY a.created_at DESC 
                    LIMIT $limit OFFSET $offset
                ");
                $stmt->execute($params);
                $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['success' => true, 'data' => $applications]);
            }
            break;
            
        case 'POST':
            // Submit job application
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
                break;
            }
            
            $required = ['job_id', 'first_name', 'last_name', 'email', 'phone', 'current_location'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => "Field '$field' is required"]);
                    exit;
                }
            }
            
            // Validate email
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid email address']);
                break;
            }
            
            // Check if job exists
            $jobStmt = $db->prepare("SELECT id FROM jobs WHERE id = ?");
            $jobStmt->execute([$input['job_id']]);
            if (!$jobStmt->fetch()) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Job not found']);
                break;
            }
            
            // Check for duplicate application
            $dupStmt = $db->prepare("SELECT id FROM applications WHERE job_id = ? AND email = ?");
            $dupStmt->execute([$input['job_id'], $input['email']]);
            if ($dupStmt->fetch()) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'You have already applied for this job']);
                break;
            }
            
            $stmt = $db->prepare("
                INSERT INTO applications (
                    job_id, first_name, last_name, email, phone, current_location,
                    profile_photo, cv_file, cover_letter, experience, previous_role
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $success = $stmt->execute([
                $input['job_id'],
                sanitizeInput($input['first_name']),
                sanitizeInput($input['last_name']),
                sanitizeInput($input['email']),
                sanitizeInput($input['phone']),
                sanitizeInput($input['current_location']),
                $input['profile_photo'] ?? null,
                $input['cv_file'] ?? null,
                sanitizeInput($input['cover_letter'] ?? ''),
                sanitizeInput($input['experience'] ?? ''),
                sanitizeInput($input['previous_role'] ?? '')
            ]);
            
            if ($success) {
                // Optional: Send confirmation email
                // Optional: Notify admin
                
                echo json_encode(['success' => true, 'message' => 'Application submitted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to submit application']);
            }
            break;
            
        case 'PUT':
            // Update application status (admin only)
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Application ID is required']);
                break;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['status'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Status is required']);
                break;
            }
            
            $allowedStatuses = ['pending', 'reviewed', 'shortlisted', 'interview', 'hired', 'rejected'];
            if (!in_array($input['status'], $allowedStatuses)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid status']);
                break;
            }
            
            $stmt = $db->prepare("UPDATE applications SET status = ? WHERE id = ?");
            $success = $stmt->execute([$input['status'], $_GET['id']]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Application status updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update application status']);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Database error', 
        'error' => $e->getMessage()
    ]);
}
?>