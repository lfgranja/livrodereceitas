<?php
// Enhanced search API endpoint

// api/search_advanced.php
// Advanced search API with filtering

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

// Handle GET request - Advanced search with filters
if ($method === 'GET') {
    $query = $_GET['q'] ?? '';
    $type = $_GET['type'] ?? 'all'; // all, recipes, users, teams
    $limit = (int)($_GET['limit'] ?? 10);
    $offset = (int)($_GET['offset'] ?? 0);
    
    // Filters
    $period = $_GET['period'] ?? '';
    $culture = $_GET['culture'] ?? '';
    $difficulty = $_GET['difficulty'] ?? '';
    $team = $_GET['team'] ?? '';
    $author = $_GET['author'] ?? '';
    
    $results = [
        'recipes' => [],
        'users' => [],
        'teams' => [],
        'total' => 0
    ];
    
    if ($type === 'all' || $type === 'recipes') {
        // Build the SQL query with filters
        $sql = "SELECT r.*, hp.name as period_name, rc.name as category_name,
                       u.first_name as author_first_name, u.last_name as author_last_name,
                       t.name as team_name
                FROM recipes r
                LEFT JOIN historical_periods hp ON r.historical_period_id = hp.id
                LEFT JOIN recipe_categories rc ON r.category_id = rc.id
                LEFT JOIN users u ON r.created_by = u.id
                LEFT JOIN teams t ON r.team_id = t.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($query)) {
            $sql .= " AND (r.title LIKE ? OR r.description LIKE ? OR r.history LIKE ?)";
            $params[] = "%$query%";
            $params[] = "%$query%";
            $params[] = "%$query%";
        }
        
        if (!empty($period)) {
            $sql .= " AND r.historical_period_id = ?";
            $params[] = $period;
        }
        
        if (!empty($culture)) {
            $sql .= " AND r.category_id = ?";
            $params[] = $culture;
        }
        
        if (!empty($difficulty)) {
            $sql .= " AND r.difficulty = ?";
            $params[] = $difficulty;
        }
        
        if (!empty($team)) {
            $sql .= " AND r.team_id = ?";
            $params[] = $team;
        }
        
        if (!empty($author)) {
            $sql .= " AND r.created_by = ?";
            $params[] = $author;
        }
        
        // Only show published recipes to other users, but show own recipes regardless of status
        $sql .= " AND (r.is_published = 1 OR r.created_by = ?)";
        $params[] = $currentUser['id'];
        
        $sql .= " ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $results['recipes'] = $stmt->fetchAll();
        
        // Count total recipes
        $countSql = "SELECT COUNT(*) FROM recipes r
                     LEFT JOIN historical_periods hp ON r.historical_period_id = hp.id
                     LEFT JOIN recipe_categories rc ON r.category_id = rc.id
                     LEFT JOIN users u ON r.created_by = u.id
                     LEFT JOIN teams t ON r.team_id = t.id
                     WHERE 1=1";
        
        $countParams = [];
        
        if (!empty($query)) {
            $countSql .= " AND (r.title LIKE ? OR r.description LIKE ? OR r.history LIKE ?)";
            $countParams[] = "%$query%";
            $countParams[] = "%$query%";
            $countParams[] = "%$query%";
        }
        
        if (!empty($period)) {
            $countSql .= " AND r.historical_period_id = ?";
            $countParams[] = $period;
        }
        
        if (!empty($culture)) {
            $countSql .= " AND r.category_id = ?";
            $countParams[] = $culture;
        }
        
        if (!empty($difficulty)) {
            $countSql .= " AND r.difficulty = ?";
            $countParams[] = $difficulty;
        }
        
        if (!empty($team)) {
            $countSql .= " AND r.team_id = ?";
            $countParams[] = $team;
        }
        
        if (!empty($author)) {
            $countSql .= " AND r.created_by = ?";
            $countParams[] = $author;
        }
        
        $countSql .= " AND (r.is_published = 1 OR r.created_by = ?)";
        $countParams[] = $currentUser['id'];
        
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($countParams);
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
        'filters' => [
            'period' => $period,
            'culture' => $culture,
            'difficulty' => $difficulty,
            'team' => $team,
            'author' => $author
        ],
        'results' => $results,
        'limit' => $limit,
        'offset' => $offset
    ]);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>