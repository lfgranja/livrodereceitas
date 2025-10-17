<?php
// api/teams.php
// API for handling teams

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

// Get the team ID if provided in the URL
$teamId = null;
if (isset($_GET['id'])) {
    $teamId = (int)$_GET['id'];
} elseif (isset($_POST['team_id'])) {
    $teamId = (int)$_POST['team_id'];
} elseif (isset($_GET['team_id'])) {
    $teamId = (int)$_GET['team_id'];
}

try {
    $pdo = getDBConnection();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco de dados']);
    exit();
}

// Handle GET request - Fetch teams
if ($method === 'GET') {
    if ($teamId) {
        // Fetch single team with members
        $stmt = $pdo->prepare("
            SELECT t.*, u.first_name as creator_first_name, u.last_name as creator_last_name,
                   c.name as class_name
            FROM teams t
            LEFT JOIN users u ON t.created_by = u.id
            LEFT JOIN classes c ON t.class_id = c.id
            WHERE t.id = ?
        ");
        $stmt->execute([$teamId]);
        $team = $stmt->fetch();
        
        if ($team) {
            // Get team members
            $memberStmt = $pdo->prepare("
                SELECT tm.is_leader, u.id, u.username, u.first_name, u.last_name
                FROM team_members tm
                LEFT JOIN users u ON tm.user_id = u.id
                WHERE tm.team_id = ?
                ORDER BY tm.is_leader DESC, u.first_name, u.last_name
            ");
            $memberStmt->execute([$teamId]);
            $team['members'] = $memberStmt->fetchAll();
            
            echo json_encode(['success' => true, 'team' => $team]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Equipe não encontrada.']);
        }
    } else {
        // Fetch teams based on query parameters
        $userId = (int)($_GET['user_id'] ?? $currentUser['id']); // Default to current user
        $classId = (int)($_GET['class_id'] ?? 0);
        
        $sql = "SELECT t.*, u.first_name as creator_first_name, u.last_name as creator_last_name,
                       c.name as class_name
                FROM teams t
                LEFT JOIN users u ON t.created_by = u.id
                LEFT JOIN classes c ON t.class_id = c.id
                WHERE 1=1";
        $params = [];
        
        if ($userId) {
            // Get teams where user is member
            $sql .= " AND t.id IN (SELECT team_id FROM team_members WHERE user_id = ?)";
            $params[] = $userId;
        }
        
        if ($classId) {
            $sql .= " AND t.class_id = ?";
            $params[] = $classId;
        }
        
        $sql .= " ORDER BY t.created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $teams = $stmt->fetchAll();
        
        echo json_encode(['success' => true, 'teams' => $teams]);
    }
}

// Handle POST request - Create or update team
elseif ($method === 'POST') {
    if ($teamId) {
        // Update existing team (only name and class)
        $name = $_POST['name'] ?? '';
        
        // Authorization check - only team leader or creator can update
        $checkStmt = $pdo->prepare("
            SELECT t.created_by, tm.is_leader 
            FROM teams t 
            LEFT JOIN team_members tm ON t.id = tm.team_id AND tm.user_id = ?
            WHERE t.id = ?
        ");
        $checkStmt->execute([$currentUser['id'], $teamId]);
        $teamData = $checkStmt->fetch();
        
        $isCreator = $teamData && $teamData['created_by'] === $currentUser['id'];
        $isLeader = $teamData && $teamData['is_leader'] == 1;
        
        if (!$isCreator && !$isLeader) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso negado. Apenas líderes ou criadores podem editar a equipe.']);
            exit();
        }
        
        $stmt = $pdo->prepare("UPDATE teams SET name = ? WHERE id = ?");
        $result = $stmt->execute([$name, $teamId]);
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Equipe atualizada com sucesso!',
                'team_id' => $teamId
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar a equipe.']);
        }
    } else {
        // Create new team
        $name = $_POST['name'] ?? '';
        $classId = (int)$_POST['class_id'] ?? 0;
        
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Nome da equipe é obrigatório.']);
            exit();
        }
        
        // Verify user is part of the class
        if ($classId) {
            $classCheck = $pdo->prepare("SELECT id FROM classes WHERE id = ? AND (teacher_id = ? OR id IN (SELECT class_id FROM users u LEFT JOIN teams t ON u.id = t.created_by WHERE t.created_by = ?))");
            $classCheck->execute([$classId, $currentUser['id'], $currentUser['id']]);
            if (!$classCheck->fetch()) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Você não tem permissão para criar equipes nesta turma.']);
                exit();
            }
        }
        
        // Start transaction
        $pdo->beginTransaction();
        
        try {
            // Insert team
            $stmt = $pdo->prepare("INSERT INTO teams (name, class_id, created_by) VALUES (?, ?, ?)");
            $result = $stmt->execute([$name, $classId, $currentUser['id']]);
            
            if ($result) {
                $newTeamId = $pdo->lastInsertId();
                
                // Add creator as team leader
                $memberStmt = $pdo->prepare("INSERT INTO team_members (team_id, user_id, is_leader) VALUES (?, ?, 1)");
                $memberResult = $memberStmt->execute([$newTeamId, $currentUser['id']]);
                
                if ($memberResult) {
                    $pdo->commit();
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Equipe criada com sucesso!', 
                        'team_id' => $newTeamId
                    ]);
                } else {
                    $pdo->rollback();
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar líder à equipe.']);
                }
            } else {
                $pdo->rollback();
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Erro ao criar a equipe.']);
            }
        } catch (Exception $e) {
            $pdo->rollback();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao criar a equipe: ' . $e->getMessage()]);
        }
    }
}

// Handle PUT request - Add user to team
elseif ($method === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    $userId = (int)($input['user_id'] ?? 0);
    $isLeader = (bool)($input['is_leader'] ?? false);
    
    if (!$teamId || !$userId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID da equipe e ID do usuário são obrigatórios.']);
        exit();
    }
    
    // Authorization check - only team leader or creator can add members
    $checkStmt = $pdo->prepare("
        SELECT t.created_by, tm.is_leader 
        FROM teams t 
        LEFT JOIN team_members tm ON t.id = tm.team_id AND tm.user_id = ?
        WHERE t.id = ?
    ");
    $checkStmt->execute([$currentUser['id'], $teamId]);
    $teamData = $checkStmt->fetch();
    
    $isCreator = $teamData && $teamData['created_by'] === $currentUser['id'];
    $isCurrentLeader = $teamData && $teamData['is_leader'] == 1;
    
    if (!$isCreator && !$isCurrentLeader) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Acesso negado. Apenas líderes ou criadores podem adicionar membros.']);
        exit();
    }
    
    // Check if user is already in the team
    $checkMember = $pdo->prepare("SELECT id FROM team_members WHERE team_id = ? AND user_id = ?");
    $checkMember->execute([$teamId, $userId]);
    if ($checkMember->fetch()) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Usuário já é membro desta equipe.']);
        exit();
    }
    
    // Add user to team
    $stmt = $pdo->prepare("INSERT INTO team_members (team_id, user_id, is_leader) VALUES (?, ?, ?)");
    $result = $stmt->execute([$teamId, $userId, $isLeader ? 1 : 0]);
    
    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Membro adicionado com sucesso!',
            'member_id' => $pdo->lastInsertId()
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar membro à equipe.']);
    }
}

// Handle DELETE request - Remove user from team or delete team
elseif ($method === 'DELETE') {
    if (!$teamId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID da equipe não fornecido.']);
        exit();
    }
    
    $userId = (int)($_GET['user_id'] ?? 0);
    
    if ($userId) {
        // Remove specific user from team
        if ($userId == $currentUser['id']) {
            // Users can remove themselves
            $checkStmt = $pdo->prepare("SELECT is_leader FROM team_members WHERE team_id = ? AND user_id = ?");
            $checkStmt->execute([$teamId, $userId]);
            $memberData = $checkStmt->fetch();
            
            if ($memberData && $memberData['is_leader'] == 1) {
                // Check if this is the only leader
                $leaderCheck = $pdo->prepare("SELECT COUNT(*) as leader_count FROM team_members WHERE team_id = ? AND is_leader = 1");
                $leaderCheck->execute([$teamId]);
                $leaderCount = $leaderCheck->fetch();
                
                if ($leaderCount['leader_count'] <= 1) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Você é o único líder. Promova outro membro antes de sair.']);
                    exit();
                }
            }
        } else {
            // Authorization check - only team leader or creator can remove other members
            $checkStmt = $pdo->prepare("
                SELECT t.created_by, tm.is_leader 
                FROM teams t 
                LEFT JOIN team_members tm ON t.id = tm.team_id AND tm.user_id = ?
                WHERE t.id = ?
            ");
            $checkStmt->execute([$currentUser['id'], $teamId]);
            $teamData = $checkStmt->fetch();
            
            $isCreator = $teamData && $teamData['created_by'] === $currentUser['id'];
            $isCurrentLeader = $teamData && $teamData['is_leader'] == 1;
            
            if (!$isCreator && !$isCurrentLeader) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Acesso negado. Apenas líderes ou criadores podem remover membros.']);
                exit();
            }
        }
        
        // Remove user from team
        $stmt = $pdo->prepare("DELETE FROM team_members WHERE team_id = ? AND user_id = ?");
        $result = $stmt->execute([$teamId, $userId]);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Membro removido com sucesso!']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao remover membro da equipe.']);
        }
    } else {
        // Delete entire team (only creator can do this)
        $checkStmt = $pdo->prepare("SELECT created_by FROM teams WHERE id = ?");
        $checkStmt->execute([$teamId]);
        $teamData = $checkStmt->fetch();
        
        if (!$teamData || $teamData['created_by'] !== $currentUser['id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso negado. Apenas o criador da equipe pode excluí-la.']);
            exit();
        }
        
        // Delete team and all related data
        $pdo->beginTransaction();
        
        try {
            // Delete team members
            $deleteMembers = $pdo->prepare("DELETE FROM team_members WHERE team_id = ?");
            $deleteMembers->execute([$teamId]);
            
            // Delete recipes associated with the team
            $deleteRecipes = $pdo->prepare("DELETE FROM recipes WHERE team_id = ?");
            $deleteRecipes->execute([$teamId]);
            
            // Update any evaluations that reference this team to use individual user's team
            // For now, we'll just remove them
            $deleteEvals = $pdo->prepare("DELETE FROM user_evaluations WHERE team_id = ?");
            $deleteEvals->execute([$teamId]);
            
            // Delete team
            $deleteTeam = $pdo->prepare("DELETE FROM teams WHERE id = ?");
            $deleteTeam->execute([$teamId]);
            
            $pdo->commit();
            
            echo json_encode(['success' => true, 'message' => 'Equipe excluída com sucesso!']);
        } catch (Exception $e) {
            $pdo->rollback();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao excluir a equipe.']);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
}
?>