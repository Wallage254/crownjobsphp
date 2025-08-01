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
                // Get single testimonial
                $stmt = $db->prepare("SELECT * FROM testimonials WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($testimonial) {
                    echo json_encode(['success' => true, 'data' => $testimonial]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Testimonial not found']);
                }
            } else {
                // Get all active testimonials
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
                $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
                
                $stmt = $db->prepare("
                    SELECT * FROM testimonials 
                    WHERE is_active = true 
                    ORDER BY created_at DESC 
                    LIMIT $limit OFFSET $offset
                ");
                $stmt->execute();
                $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['success' => true, 'data' => $testimonials]);
            }
            break;
            
        case 'POST':
            // Create new testimonial (admin only)
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
                break;
            }
            
            $required = ['name', 'country', 'rating', 'comment'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => "Field '$field' is required"]);
                    exit;
                }
            }
            
            // Validate rating
            if ($input['rating'] < 1 || $input['rating'] > 5) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
                break;
            }
            
            $stmt = $db->prepare("
                INSERT INTO testimonials (name, country, rating, comment, photo, video_url, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $success = $stmt->execute([
                $input['name'],
                $input['country'],
                (int)$input['rating'],
                $input['comment'],
                $input['photo'] ?? null,
                $input['video_url'] ?? null,
                $input['is_active'] ?? true
            ]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Testimonial created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to create testimonial']);
            }
            break;
            
        case 'PUT':
            // Update testimonial (admin only)
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Testimonial ID is required']);
                break;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
                break;
            }
            
            // Validate rating if provided
            if (isset($input['rating']) && ($input['rating'] < 1 || $input['rating'] > 5)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
                break;
            }
            
            $stmt = $db->prepare("
                UPDATE testimonials SET
                    name = ?, country = ?, rating = ?, comment = ?,
                    photo = ?, video_url = ?, is_active = ?
                WHERE id = ?
            ");
            
            $success = $stmt->execute([
                $input['name'],
                $input['country'],
                (int)$input['rating'],
                $input['comment'],
                $input['photo'] ?? null,
                $input['video_url'] ?? null,
                $input['is_active'] ?? true,
                $_GET['id']
            ]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Testimonial updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update testimonial']);
            }
            break;
            
        case 'DELETE':
            // Delete testimonial (admin only)
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Testimonial ID is required']);
                break;
            }
            
            $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ?");
            $success = $stmt->execute([$_GET['id']]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Testimonial deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete testimonial']);
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