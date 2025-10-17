<?php
// auth/auth_functions.php
// User authentication functions

require_once '../config/database.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Register a new user
 */
function registerUser($username, $email, $password, $first_name, $last_name, $role = 'student') {
    try {
        $pdo = getDBConnection();
        
        // Validate input
        if (empty($username) || empty($email) || empty($password) || empty($first_name) || empty($last_name)) {
            return ['success' => false, 'message' => 'Todos os campos são obrigatórios.'];
        }
        
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $existingUser = $stmt->fetch();
        
        if ($existingUser) {
            return ['success' => false, 'message' => 'Nome de usuário ou email já está em uso.'];
        }
        
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password_hash, $first_name, $last_name, $role]);
        
        $user_id = $pdo->lastInsertId();
        
        // Auto-login the user after registration
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        
        return ['success' => true, 'message' => 'Usuário registrado com sucesso!', 'user_id' => $user_id];
        
    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro ao registrar usuário: ' . $e->getMessage()];
    }
}

/**
 * Login a user
 */
function loginUser($username, $password) {
    try {
        $pdo = getDBConnection();
        
        // Find user by username or email
        $stmt = $pdo->prepare("SELECT id, username, password_hash, first_name, last_name, role, is_active FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return ['success' => false, 'message' => 'Usuário ou senha incorretos.'];
        }
        
        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Sua conta está desativada.'];
        }
        
        if (password_verify($password, $user['password_hash'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['role'];
            
            // Update last login
            $updateStmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $updateStmt->execute([$user['id']]);
            
            return ['success' => true, 'message' => 'Login realizado com sucesso!', 'user' => $user];
        } else {
            return ['success' => false, 'message' => 'Usuário ou senha incorretos.'];
        }
        
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro ao fazer login: ' . $e->getMessage()];
    }
}

/**
 * Logout user
 */
function logoutUser() {
    // Destroy session
    session_destroy();
    
    // Regenerate session ID for security
    session_start();
    session_regenerate_id(true);
    
    return ['success' => true, 'message' => 'Logout realizado com sucesso.'];
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current logged in user
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, username, first_name, last_name, email, role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Get user error: " . $e->getMessage());
        return null;
    }
}

/**
 * Update user profile
 */
function updateUserProfile($user_id, $first_name, $last_name, $email) {
    try {
        $pdo = getDBConnection();
        
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
        $stmt->execute([$first_name, $last_name, $email, $user_id]);
        
        // Update session data
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        
        return ['success' => true, 'message' => 'Perfil atualizado com sucesso!'];
    } catch (Exception $e) {
        error_log("Update profile error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro ao atualizar perfil: ' . $e->getMessage()];
    }
}

/**
 * Change user password
 */
function changePassword($user_id, $current_password, $new_password) {
    try {
        $pdo = getDBConnection();
        
        // Get current password hash
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($current_password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Senha atual incorreta.'];
        }
        
        // Validate new password
        if (strlen($new_password) < 6) {
            return ['success' => false, 'message' => 'A nova senha deve ter pelo menos 6 caracteres.'];
        }
        
        // Hash and update new password
        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $updateStmt->execute([$new_hash, $user_id]);
        
        return ['success' => true, 'message' => 'Senha alterada com sucesso!'];
    } catch (Exception $e) {
        error_log("Change password error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro ao alterar senha: ' . $e->getMessage()];
    }
}
?>