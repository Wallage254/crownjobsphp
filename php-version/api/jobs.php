<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Get single job
                $stmt = $db->prepare("SELECT * FROM jobs WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $job = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($job) {
                    // Parse workplace_images array
                    if ($job['workplace_images']) {
                        $job['workplace_images'] = json_decode($job['workplace_images'], true) ?: [];
                    } else {
                        $job['workplace_images'] = [];
                    }
                    
                    echo json_encode(['success' => true, 'data' => $job]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Job not found']);
                }
            } else {
                // Get all jobs with optional filters
                $where = [];
                $params = [];
                
                if (isset($_GET['category']) && !empty($_GET['category'])) {
                    $where[] = "category = ?";
                    $params[] = $_GET['category'];
                }
                
                if (isset($_GET['location']) && !empty($_GET['location'])) {
                    $where[] = "location = ?";
                    $params[] = $_GET['location'];
                }
                
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $where[] = "(title ILIKE ? OR company ILIKE ? OR description ILIKE ?)";
                    $search = '%' . $_GET['search'] . '%';
                    $params[] = $search;
                    $params[] = $search;
                    $params[] = $search;
                }
                
                $whereClause = '';
                if (!empty($where)) {
                    $whereClause = 'WHERE ' . implode(' AND ', $where);
                }
                
                $orderBy = isset($_GET['urgent']) && $_GET['urgent'] === 'true' 
                    ? 'ORDER BY is_urgent DESC, created_at DESC'
                    : 'ORDER BY created_at DESC';
                
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
                $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
                
                $sql = "SELECT * FROM jobs $whereClause $orderBy LIMIT $limit OFFSET $offset";
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
                $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Parse workplace_images for each job
                foreach ($jobs as &$job) {
                    if ($job['workplace_images']) {
                        $job['workplace_images'] = json_decode($job['workplace_images'], true) ?: [];
                    } else {
                        $job['workplace_images'] = [];
                    }
                }
                
                echo json_encode(['success' => true, 'data' => $jobs]);
            }
            break;
            
        case 'POST':
            // Create new job (admin only)
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
                break;
            }
            
            $required = ['title', 'company', 'category', 'location', 'description', 'requirements'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => "Field '$field' is required"]);
                    exit;
                }
            }
            
            $workplace_images = isset($input['workplace_images']) && is_array($input['workplace_images']) 
                ? json_encode($input['workplace_images'])
                : null;
            
            $stmt = $db->prepare("
                INSERT INTO jobs (
                    title, company, category, location, description, requirements,
                    salary_min, salary_max, job_type, is_urgent, visa_sponsored,
                    company_logo, workplace_images
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $success = $stmt->execute([
                $input['title'],
                $input['company'],
                $input['category'],
                $input['location'],
                $input['description'],
                $input['requirements'],
                $input['salary_min'] ?? null,
                $input['salary_max'] ?? null,
                $input['job_type'] ?? 'Full-time',
                $input['is_urgent'] ?? false,
                $input['visa_sponsored'] ?? true,
                $input['company_logo'] ?? null,
                $workplace_images
            ]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Job created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to create job']);
            }
            break;
            
        case 'PUT':
            // Update job (admin only)
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Job ID is required']);
                break;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
                break;
            }
            
            $workplace_images = isset($input['workplace_images']) && is_array($input['workplace_images']) 
                ? json_encode($input['workplace_images'])
                : null;
            
            $stmt = $db->prepare("
                UPDATE jobs SET
                    title = ?, company = ?, category = ?, location = ?, description = ?,
                    requirements = ?, salary_min = ?, salary_max = ?, job_type = ?,
                    is_urgent = ?, visa_sponsored = ?, company_logo = ?, workplace_images = ?,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = ?
            ");
            
            $success = $stmt->execute([
                $input['title'],
                $input['company'],
                $input['category'],
                $input['location'],
                $input['description'],
                $input['requirements'],
                $input['salary_min'] ?? null,
                $input['salary_max'] ?? null,
                $input['job_type'] ?? 'Full-time',
                $input['is_urgent'] ?? false,
                $input['visa_sponsored'] ?? true,
                $input['company_logo'] ?? null,
                $workplace_images,
                $_GET['id']
            ]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Job updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update job']);
            }
            break;
            
        case 'DELETE':
            // Delete job (admin only)
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Job ID is required']);
                break;
            }
            
            $stmt = $db->prepare("DELETE FROM jobs WHERE id = ?");
            $success = $stmt->execute([$_GET['id']]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Job deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete job']);
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