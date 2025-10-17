<?php
// api/search.php
// API for search functionality

require_once '../auth/auth_functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado. Faça login para continuar.']);
    exit();
}

$currentUser = getCurrentUser();

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

try {
    $pdo = getDBConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados']);
    exit();
}

// Handle GET request - Search for recipes, users, etc.
if ($method === 'GET') {
    $query = $_GET['q'] ?? '';
    $type = $_GET['type'] ?? 'all'; // all, recipes, users, teams
    $limit = (int)($_GET['limit'] ?? 10);
    $offset = (int)($_GET['offset'] ?? 0);
    
    if (empty($query)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Parâmetro de busca é obrigatório.']);
        exit();
    }
    
    $results = [
        'recipes' => [],
        'users' => [],
        'teams' => [],
        'total' => 0
    ];
    
    if ($type === 'all' || $type === 'recipes') {
        // Search in recipes
        $sql = "SELECT r.*, hp.name as period_name, rc.name as category_name,
                       u.first_name as author_first_name, u.last_name as author_last_name,
                       t.name as team_name
                FROM recipes r
                LEFT JOIN historical_periods hp ON r.historical_period_id = hp.id
                LEFT JOIN recipe_categories rc ON r.category_id = rc.id
                LEFT JOIN users u ON r.created_by = u.id
                LEFT JOIN teams t ON r.team_id = t.id
                WHERE (r.title LIKE ? OR r.description LIKE ? OR r.history LIKE ?)
                AND (r.is_published = 1 OR r.created_by = ?)
                ORDER BY r.created_at DESC
                LIMIT ? OFFSET ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$query%", "%$query%", "%$query%", $currentUser['id'], $limit, $offset]);
        $results['recipes'] = $stmt->fetchAll();
        
        // Count total recipes
        $countSql = "SELECT COUNT(*) FROM recipes r
                     WHERE (r.title LIKE ? OR r.description LIKE ? OR r.history LIKE ?)
                     AND (r.is_published = 1 OR r.created_by = ?)";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute(["%$query%", "%$query%", "%$query%", $currentUser['id']]);
        $results['total'] += $countStmt->fetchColumn();
    }
    
    if ($type === 'all' || $type === 'users') {
        // Search in users
        $sql = "SELECT id, username, first_name, last_name, role
                FROM users
                WHERE (username LIKE ? OR first_name LIKE ? OR last_name LIKE ?)
                AND id != ?
                ORDER BY first_name, last_name
                LIMIT ? OFFSET ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$query%", "%$query%", "%$query%", $currentUser['id'], $limit, $offset]);
        $results['users'] = $stmt->fetchAll();
        
        // Count total users
        $countSql = "SELECT COUNT(*) FROM users
                     WHERE (username LIKE ? OR first_name LIKE ? OR last_name LIKE ?)
                     AND id != ?";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute(["%$query%", "%$query%", "%$query%", $currentUser['id']]);
        $results['total'] += $countStmt->fetchColumn();
    }
    
    if ($type === 'all' || $type === 'teams') {
        // Search in teams
        $sql = "SELECT t.*, u.first_name as creator_first_name, u.last_name as creator_last_name
                FROM teams t
                LEFT JOIN users u ON t.created_by = u.id
                WHERE t.name LIKE ?
                AND t.id IN (
                    SELECT team_id FROM team_members WHERE user_id = ?
                    UNION
                    SELECT id FROM teams WHERE created_by = ?
                )
                ORDER BY t.name
                LIMIT ? OFFSET ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$query%", $currentUser['id'], $currentUser['id'], $limit, $offset]);
        $results['teams'] = $stmt->fetchAll();
        
        // Count total teams
        $countSql = "SELECT COUNT(*) FROM teams t
                     WHERE t.name LIKE ?
                     AND t.id IN (
                         SELECT team_id FROM team_members WHERE user_id = ?
                         UNION
                         SELECT id FROM teams WHERE created_by = ?
                     )";
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute(["%$query%", $currentUser['id'], $currentUser['id']]);
        $results['total'] += $countStmt->fetchColumn();
    }
    
    echo json_encode([
        'success' => true,
        'query' => $query,
        'type' => $type,
        'results' => $results,
        'limit' => $limit,
        'offset' => $offset
    ]);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>