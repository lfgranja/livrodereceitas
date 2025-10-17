<?php
// api/evaluations.php
// API for handling recipe evaluations

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

// Get the recipe ID if provided in the URL
$recipeId = null;
if (isset($_GET['recipe_id'])) {
    $recipeId = (int)$_GET['recipe_id'];
} elseif (isset($_POST['recipe_id'])) {
    $recipeId = (int)$_POST['recipe_id'];
}

try {
    $pdo = getDBConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados']);
    exit();
}

// Handle GET request - Fetch evaluations for a recipe
if ($method === 'GET') {
    if (!$recipeId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID da receita não fornecido.']);
        exit();
    }
    
    // Get all evaluations for the recipe
    $stmt = $pdo->prepare("
        SELECT ue.*, u.first_name, u.last_name, t.name as team_name
        FROM user_evaluations ue
        LEFT JOIN users u ON ue.evaluator_id = u.id
        LEFT JOIN teams t ON ue.team_id = t.id
        WHERE ue.recipe_id = ?
        ORDER BY ue.created_at DESC
    ");
    $stmt->execute([$recipeId]);
    $evaluations = $stmt->fetchAll();
    
    // Calculate average ratings
    $avgStmt = $pdo->prepare("
        SELECT 
            AVG(CASE WHEN evaluation_type = 'research_quality' THEN rating END) AS avg_research_quality,
            AVG(CASE WHEN evaluation_type = 'creativity' THEN rating END) AS avg_creativity,
            AVG(CASE WHEN evaluation_type = 'presentation' THEN rating END) AS avg_presentation,
            AVG(CASE WHEN evaluation_type = 'historical_connection' THEN rating END) AS avg_historical_connection,
            AVG(rating) AS overall_rating,
            COUNT(*) AS total_evaluations
        FROM user_evaluations
        WHERE recipe_id = ?
    ");
    $avgStmt->execute([$recipeId]);
    $avgRatings = $avgStmt->fetch();
    
    echo json_encode([
        'success' => true, 
        'evaluations' => $evaluations,
        'average_ratings' => $avgRatings
    ]);
}

// Handle POST request - Submit or update evaluation
elseif ($method === 'POST') {
    if (!$recipeId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID da receita não fornecido.']);
        exit();
    }
    
    $evaluationType = $_POST['evaluation_type'] ?? '';
    $rating = (int)$_POST['rating'] ?? 0;
    $comment = $_POST['comment'] ?? '';
    $teamId = (int)$_POST['team_id'] ?? null;
    
    if (empty($evaluationType) || $rating < 1 || $rating > 5) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Tipo de avaliação e classificação válida são obrigatórios.']);
        exit();
    }
    
    // Check if the recipe exists and get team_id if not provided
    $recipeStmt = $pdo->prepare("SELECT id, team_id FROM recipes WHERE id = ?");
    $recipeStmt->execute([$recipeId]);
    $recipe = $recipeStmt->fetch();
    
    if (!$recipe) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Receita não encontrada.']);
        exit();
    }
    
    if ($teamId === null) {
        $teamId = $recipe['team_id'];
    }
    
    // Check if user is allowed to evaluate (not the creator of the recipe)
    if ($recipe['created_by'] === $currentUser['id']) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Você não pode avaliar sua própria receita.']);
        exit();
    }
    
    // Check if evaluation already exists for this user, recipe, and type
    $checkStmt = $pdo->prepare("
        SELECT id FROM user_evaluations 
        WHERE evaluator_id = ? AND recipe_id = ? AND evaluation_type = ?
    ");
    $checkStmt->execute([$currentUser['id'], $recipeId, $evaluationType]);
    $existingEval = $checkStmt->fetch();
    
    if ($existingEval) {
        // Update existing evaluation
        $updateStmt = $pdo->prepare("
            UPDATE user_evaluations 
            SET rating = ?, comment = ?, created_at = NOW()
            WHERE id = ?
        ");
        $result = $updateStmt->execute([$rating, $comment, $existingEval['id']]);
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Avaliação atualizada com sucesso!',
                'evaluation_id' => $existingEval['id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a avaliação.']);
        }
    } else {
        // Insert new evaluation
        $insertStmt = $pdo->prepare("
            INSERT INTO user_evaluations (evaluator_id, recipe_id, team_id, evaluation_type, rating, comment)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $result = $insertStmt->execute([
            $currentUser['id'], $recipeId, $teamId, $evaluationType, $rating, $comment
        ]);
        
        if ($result) {
            $newEvalId = $pdo->lastInsertId();
            echo json_encode([
                'success' => true, 
                'message' => 'Avaliação registrada com sucesso!',
                'evaluation_id' => $newEvalId
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao registrar a avaliação.']);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>