<?php
// api/recipes.php
// API for handling recipes

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
if (isset($_GET['id'])) {
    $recipeId = (int)$_GET['id'];
} elseif (isset($_POST['recipe_id'])) {
    $recipeId = (int)$_POST['recipe_id'];
} elseif (isset($_GET['recipe_id'])) {
    $recipeId = (int)$_GET['recipe_id'];
}

try {
    $pdo = getDBConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados']);
    exit();
}

// Handle GET request - Fetch recipes
if ($method === 'GET') {
    if ($recipeId) {
        // Fetch single recipe
        $stmt = $pdo->prepare("
            SELECT r.*, hp.name as period_name, rc.name as category_name, 
                   u.first_name as author_first_name, u.last_name as author_last_name,
                   t.name as team_name
            FROM recipes r
            LEFT JOIN historical_periods hp ON r.historical_period_id = hp.id
            LEFT JOIN recipe_categories rc ON r.category_id = rc.id
            LEFT JOIN users u ON r.created_by = u.id
            LEFT JOIN teams t ON r.team_id = t.id
            WHERE r.id = ? AND (r.is_published = 1 OR r.created_by = ?)
        ");
        $stmt->execute([$recipeId, $currentUser['id']]);
        $recipe = $stmt->fetch();
        
        if ($recipe) {
            // Get ingredients for the recipe
            $ingredientStmt = $pdo->prepare("SELECT * FROM recipe_ingredients WHERE recipe_id = ?");
            $ingredientStmt->execute([$recipeId]);
            $recipe['ingredients'] = $ingredientStmt->fetchAll();
            
            echo json_encode(['success' => true, 'recipe' => $recipe]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Receita não encontrada.']);
        }
    } else {
        // Fetch multiple recipes with filtering options
        $limit = (int)($_GET['limit'] ?? 12);
        $offset = (int)($_GET['offset'] ?? 0);
        $searchTerm = $_GET['search'] ?? '';
        $periodId = $_GET['period'] ?? '';
        $categoryId = $_GET['category'] ?? '';
        $difficulty = $_GET['difficulty'] ?? '';
        
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
        
        // Add filters
        if ($searchTerm) {
            $sql .= " AND (r.title LIKE ? OR r.description LIKE ?)";
            $params[] = "%$searchTerm%";
            $params[] = "%$searchTerm%";
        }
        
        if ($periodId) {
            $sql .= " AND r.historical_period_id = ?";
            $params[] = $periodId;
        }
        
        if ($categoryId) {
            $sql .= " AND r.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($difficulty) {
            $sql .= " AND r.difficulty = ?";
            $params[] = $difficulty;
        }
        
        // Only show published recipes to other users, but show own recipes regardless of status
        $sql .= " AND (r.is_published = 1 OR r.created_by = ?)";
        $params[] = $currentUser['id'];
        
        $sql .= " ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $recipes = $stmt->fetchAll();
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) FROM recipes r WHERE 1=1";
        $countParams = [];
        
        if ($searchTerm) {
            $countSql .= " AND (r.title LIKE ? OR r.description LIKE ?)";
            $countParams[] = "%$searchTerm%";
            $countParams[] = "%$searchTerm%";
        }
        
        if ($periodId) {
            $countSql .= " AND r.historical_period_id = ?";
            $countParams[] = $periodId;
        }
        
        if ($categoryId) {
            $countSql .= " AND r.category_id = ?";
            $countParams[] = $categoryId;
        }
        
        if ($difficulty) {
            $countSql .= " AND r.difficulty = ?";
            $countParams[] = $difficulty;
        }
        
        $countSql .= " AND (r.is_published = 1 OR r.created_by = ?)";
        $countParams[] = $currentUser['id'];
        
        $countStmt = $pdo->prepare($countSql);
        $countStmt->execute($countParams);
        $totalCount = $countStmt->fetchColumn();
        
        echo json_encode([
            'success' => true, 
            'recipes' => $recipes, 
            'pagination' => [
                'total' => $totalCount,
                'limit' => $limit,
                'offset' => $offset,
                'has_more' => ($offset + $limit) < $totalCount
            ]
        ]);
    }
}

// Handle POST request - Create or update recipe
elseif ($method === 'POST') {
    // Check if it's an update
    if ($recipeId) {
        // Update existing recipe
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $historicalPeriodId = (int)$_POST['historical_period_id'] ?? 0;
        $categoryId = (int)$_POST['category_id'] ?? 0;
        $difficulty = $_POST['difficulty'] ?? '';
        $image_url = $_POST['image_url'] ?? '';
        $history = $_POST['history'] ?? '';
        $preparationOriginal = $_POST['preparation_original'] ?? '';
        $preparationModern = $_POST['preparation_modern'] ?? '';
        $nutritionalInfo = $_POST['nutritional_info'] ?? '';
        $context = $_POST['context'] ?? '';
        $whoPrepared = $_POST['who_prepared'] ?? '';
        $whoConsumed = $_POST['who_consumed'] ?? '';
        $rituals = $_POST['rituals'] ?? '';
        $reflection = $_POST['reflection'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        
        // Authorization check - only recipe owner or admin can update
        $checkStmt = $pdo->prepare("SELECT created_by FROM recipes WHERE id = ?");
        $checkStmt->execute([$recipeId]);
        $recipeData = $checkStmt->fetch();
        
        if (!$recipeData || ($recipeData['created_by'] !== $currentUser['id'] && $currentUser['role'] !== 'admin')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso negado. Você não pode editar esta receita.']);
            exit();
        }
        
        // Update recipe
        $stmt = $pdo->prepare("
            UPDATE recipes 
            SET title = ?, description = ?, historical_period_id = ?, category_id = ?, 
                difficulty = ?, image_url = ?, history = ?, preparation_original = ?, 
                preparation_modern = ?, nutritional_info = ?, context = ?, 
                who_prepared = ?, who_consumed = ?, rituals = ?, reflection = ?, 
                is_published = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        $result = $stmt->execute([
            $title, $description, $historicalPeriodId, $categoryId, $difficulty,
            $image_url, $history, $preparationOriginal, $preparationModern,
            $nutritionalInfo, $context, $whoPrepared, $whoConsumed, $rituals,
            $reflection, $isPublished, $recipeId
        ]);
        
        if ($result) {
            // Update ingredients
            $ingredients = json_decode($_POST['ingredients'] ?? '[]', true);
            if ($ingredients !== null) {
                // Delete existing ingredients
                $deleteStmt = $pdo->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
                $deleteStmt->execute([$recipeId]);
                
                // Insert new ingredients
                $ingredientStmt = $pdo->prepare("
                    INSERT INTO recipe_ingredients (recipe_id, name, amount, type, price, location, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                
                foreach ($ingredients as $ingredient) {
                    $ingredientStmt->execute([
                        $recipeId, 
                        $ingredient['name'] ?? '', 
                        $ingredient['amount'] ?? '', 
                        $ingredient['type'] ?? 'modern', 
                        $ingredient['price'] ?? null, 
                        $ingredient['location'] ?? '', 
                        $ingredient['notes'] ?? ''
                    ]);
                }
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Receita atualizada com sucesso!',
                'recipe_id' => $recipeId
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a receita.']);
        }
    } else {
        // Create new recipe
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $historicalPeriodId = (int)$_POST['historical_period_id'] ?? 0;
        $categoryId = (int)$_POST['category_id'] ?? 0;
        $difficulty = $_POST['difficulty'] ?? '';
        $image_url = $_POST['image_url'] ?? '';
        $history = $_POST['history'] ?? '';
        $preparationOriginal = $_POST['preparation_original'] ?? '';
        $preparationModern = $_POST['preparation_modern'] ?? '';
        $nutritionalInfo = $_POST['nutritional_info'] ?? '';
        $context = $_POST['context'] ?? '';
        $whoPrepared = $_POST['who_prepared'] ?? '';
        $whoConsumed = $_POST['who_consumed'] ?? '';
        $rituals = $_POST['rituals'] ?? '';
        $reflection = $_POST['reflection'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        $teamId = (int)$_POST['team_id'] ?? null;
        
        if (empty($title) || empty($description)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Título e descrição são obrigatórios.']);
            exit();
        }
        
        // Check if user is part of the team if team is specified
        if ($teamId) {
            $teamCheck = $pdo->prepare("SELECT id FROM team_members WHERE team_id = ? AND user_id = ?");
            $teamCheck->execute([$teamId, $currentUser['id']]);
            if (!$teamCheck->fetch()) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Você não faz parte desta equipe.']);
                exit();
            }
        }
        
        // Insert recipe
        $stmt = $pdo->prepare("
            INSERT INTO recipes (
                title, description, historical_period_id, category_id, difficulty,
                image_url, history, preparation_original, preparation_modern,
                nutritional_info, context, who_prepared, who_consumed, rituals,
                reflection, created_by, team_id, is_published
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $title, $description, $historicalPeriodId, $categoryId, $difficulty,
            $image_url, $history, $preparationOriginal, $preparationModern,
            $nutritionalInfo, $context, $whoPrepared, $whoConsumed, $rituals,
            $reflection, $currentUser['id'], $teamId, $isPublished
        ]);
        
        if ($result) {
            $newRecipeId = $pdo->lastInsertId();
            
            // Insert ingredients
            $ingredients = json_decode($_POST['ingredients'] ?? '[]', true);
            if ($ingredients !== null) {
                $ingredientStmt = $pdo->prepare("
                    INSERT INTO recipe_ingredients (recipe_id, name, amount, type, price, location, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                
                foreach ($ingredients as $ingredient) {
                    $ingredientStmt->execute([
                        $newRecipeId, 
                        $ingredient['name'] ?? '', 
                        $ingredient['amount'] ?? '', 
                        $ingredient['type'] ?? 'modern', 
                        $ingredient['price'] ?? null, 
                        $ingredient['location'] ?? '', 
                        $ingredient['notes'] ?? ''
                    ]);
                }
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Receita criada com sucesso!', 
                'recipe_id' => $newRecipeId
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao criar a receita.']);
        }
    }
}

// Handle DELETE request - Delete recipe
elseif ($method === 'DELETE') {
    if (!$recipeId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID da receita não fornecido.']);
        exit();
    }
    
    // Authorization check - only recipe owner or admin can delete
    $checkStmt = $pdo->prepare("SELECT created_by FROM recipes WHERE id = ?");
    $checkStmt->execute([$recipeId]);
    $recipeData = $checkStmt->fetch();
    
    if (!$recipeData || ($recipeData['created_by'] !== $currentUser['id'] && $currentUser['role'] !== 'admin')) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Acesso negado. Você não pode excluir esta receita.']);
        exit();
    }
    
    // Delete recipe and related data
    $pdo->beginTransaction();
    
    try {
        // Delete ingredients
        $deleteIngredients = $pdo->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
        $deleteIngredients->execute([$recipeId]);
        
        // Delete gallery images
        $deleteGallery = $pdo->prepare("DELETE FROM recipe_gallery WHERE recipe_id = ?");
        $deleteGallery->execute([$recipeId]);
        
        // Delete comments
        $deleteComments = $pdo->prepare("DELETE FROM recipe_comments WHERE recipe_id = ?");
        $deleteComments->execute([$recipeId]);
        
        // Delete evaluations
        $deleteEvaluations = $pdo->prepare("DELETE FROM user_evaluations WHERE recipe_id = ?");
        $deleteEvaluations->execute([$recipeId]);
        
        // Delete recipe
        $deleteRecipe = $pdo->prepare("DELETE FROM recipes WHERE id = ?");
        $deleteRecipe->execute([$recipeId]);
        
        $pdo->commit();
        
        echo json_encode(['success' => true, 'message' => 'Receita excluída com sucesso!']);
    } catch (Exception $e) {
        $pdo->rollback();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro ao excluir a receita.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>