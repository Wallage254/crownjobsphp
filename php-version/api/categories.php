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
                // Get single category
                $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $category = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($category) {
                    echo json_encode(['success' => true, 'data' => $category]);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Category not found']);
                }
            } else {
                // Get all active categories
                $stmt = $db->prepare("SELECT * FROM categories WHERE is_active = true ORDER BY name ASC");
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo json_encode(['success' => true, 'data' => $categories]);
            }
            break;
            
        case 'POST':
            // Create new category (admin only)
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
                break;
            }
            
            if (empty($input['name'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Category name is required']);
                break;
            }
            
            $stmt = $db->prepare("
                INSERT INTO categories (name, description, gif_url, is_active)
                VALUES (?, ?, ?, ?)
            ");
            
            $success = $stmt->execute([
                $input['name'],
                $input['description'] ?? null,
                $input['gif_url'] ?? null,
                $input['is_active'] ?? true
            ]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Category created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to create category']);
            }
            break;
            
        case 'PUT':
            // Update category (admin only)
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Category ID is required']);
                break;
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
                break;
            }
            
            $stmt = $db->prepare("
                UPDATE categories SET
                    name = ?, description = ?, gif_url = ?, is_active = ?
                WHERE id = ?
            ");
            
            $success = $stmt->execute([
                $input['name'],
                $input['description'] ?? null,
                $input['gif_url'] ?? null,
                $input['is_active'] ?? true,
                $_GET['id']
            ]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to update category']);
            }
            break;
            
        case 'DELETE':
            // Delete category (admin only)
            if (!isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Category ID is required']);
                break;
            }
            
            $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
            $success = $stmt->execute([$_GET['id']]);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to delete category']);
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