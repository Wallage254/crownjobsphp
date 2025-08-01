<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$db = getDB();

try {
    switch ($method) {
        case 'POST':
            // Submit contact form
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
                break;
            }
            
            $required = ['firstName', 'lastName', 'email', 'subject', 'message'];
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
            
            $stmt = $db->prepare("
                INSERT INTO messages (first_name, last_name, email, subject, message)
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $success = $stmt->execute([
                sanitizeInput($input['firstName']),
                sanitizeInput($input['lastName']),
                sanitizeInput($input['email']),
                sanitizeInput($input['subject']),
                sanitizeInput($input['message'])
            ]);
            
            if ($success) {
                // Optional: Send email notification to admin
                // mail('admin@crownopportunities.com', 'New Contact Message', $input['message']);
                
                echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to send message']);
            }
            break;
            
        case 'GET':
            // Get messages (admin only - should check authentication)
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            $unread_only = isset($_GET['unread']) && $_GET['unread'] === 'true';
            
            $whereClause = $unread_only ? 'WHERE is_read = false' : '';
            
            $stmt = $db->prepare("
                SELECT * FROM messages 
                $whereClause
                ORDER BY created_at DESC 
                LIMIT $limit OFFSET $offset
            ");
            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get total count
            $countStmt = $db->prepare("SELECT COUNT(*) as total FROM messages $whereClause");
            $countStmt->execute();
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo json_encode([
                'success' => true, 
                'data' => $messages,
                'total' => (int)$total
            ]);
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