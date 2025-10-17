<?php
// init_database.php
// Script to initialize the database with tables and sample data

require_once 'config/database.php';

try {
    $pdo = getDBConnection();
    
    // SQL statements to create tables
    $sqlStatements = [
        // Users table
        "CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            role ENUM('student', 'teacher', 'admin') DEFAULT 'student',
            class_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL,
            is_active BOOLEAN DEFAULT TRUE,
            profile_picture_url VARCHAR(255),
            email_verified BOOLEAN DEFAULT FALSE,
            reset_password_token VARCHAR(255),
            reset_password_expires TIMESTAMP
        )",
        
        // Classes table
        "CREATE TABLE IF NOT EXISTS classes (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            teacher_id INT NOT NULL,
            school_name VARCHAR(100),
            academic_year YEAR,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (teacher_id) REFERENCES users(id)
        )",
        
        // Teams table
        "CREATE TABLE IF NOT EXISTS teams (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            class_id INT NOT NULL,
            created_by INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (class_id) REFERENCES classes(id),
            FOREIGN KEY (created_by) REFERENCES users(id)
        )",
        
        // Team members table
        "CREATE TABLE IF NOT EXISTS team_members (
            id INT PRIMARY KEY AUTO_INCREMENT,
            team_id INT NOT NULL,
            user_id INT NOT NULL,
            joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            is_leader BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_team_user (team_id, user_id)
        )",
        
        // Historical periods table
        "CREATE TABLE IF NOT EXISTS historical_periods (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            start_year INT,
            end_year INT,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        // Recipe categories table
        "CREATE TABLE IF NOT EXISTS recipe_categories (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        // Recipes table
        "CREATE TABLE IF NOT EXISTS recipes (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(200) NOT NULL,
            description TEXT,
            historical_period_id INT NOT NULL,
            category_id INT NOT NULL,
            difficulty ENUM('Fácil', 'Médio', 'Difícil') DEFAULT 'Médio',
            image_url VARCHAR(255),
            history TEXT NOT NULL,
            preparation_original TEXT,
            preparation_modern TEXT,
            nutritional_info TEXT,
            context TEXT,
            who_prepared TEXT,
            who_consumed TEXT,
            rituals TEXT,
            reflection TEXT,
            created_by INT NOT NULL,
            team_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            is_published BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (historical_period_id) REFERENCES historical_periods(id),
            FOREIGN KEY (category_id) REFERENCES recipe_categories(id),
            FOREIGN KEY (created_by) REFERENCES users(id),
            FOREIGN KEY (team_id) REFERENCES teams(id)
        )",
        
        // Recipe ingredients table
        "CREATE TABLE IF NOT EXISTS recipe_ingredients (
            id INT PRIMARY KEY AUTO_INCREMENT,
            recipe_id INT NOT NULL,
            name VARCHAR(100) NOT NULL,
            amount VARCHAR(50),
            type ENUM('original', 'modern') NOT NULL,
            price DECIMAL(10,2),
            location VARCHAR(200),
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
        )",
        
        // User evaluations table
        "CREATE TABLE IF NOT EXISTS user_evaluations (
            id INT PRIMARY KEY AUTO_INCREMENT,
            evaluator_id INT NOT NULL,
            recipe_id INT NOT NULL,
            team_id INT NOT NULL,
            evaluation_type ENUM('research_quality', 'creativity', 'presentation', 'historical_connection') NOT NULL,
            rating TINYINT CHECK (rating >= 1 AND rating <= 5),
            comment TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (evaluator_id) REFERENCES users(id),
            FOREIGN KEY (recipe_id) REFERENCES recipes(id),
            FOREIGN KEY (team_id) REFERENCES teams(id),
            UNIQUE KEY unique_evaluation (evaluator_id, recipe_id, evaluation_type)
        )",
        
        // Recipe gallery table
        "CREATE TABLE IF NOT EXISTS recipe_gallery (
            id INT PRIMARY KEY AUTO_INCREMENT,
            recipe_id INT NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            description VARCHAR(200),
            uploaded_by INT NOT NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            is_featured BOOLEAN DEFAULT FALSE,
            FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
            FOREIGN KEY (uploaded_by) REFERENCES users(id)
        )",
        
        // Recipe comments table
        "CREATE TABLE IF NOT EXISTS recipe_comments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            recipe_id INT NOT NULL,
            user_id INT NOT NULL,
            parent_comment_id INT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (parent_comment_id) REFERENCES recipe_comments(id) ON DELETE CASCADE
        )",
        
        // User sessions table
        "CREATE TABLE IF NOT EXISTS user_sessions (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            session_token VARCHAR(255) NOT NULL UNIQUE,
            expires_at TIMESTAMP NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45),
            user_agent TEXT,
            is_active BOOLEAN DEFAULT TRUE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        
        // Recipe views table
        "CREATE TABLE IF NOT EXISTS recipe_views (
            id INT PRIMARY KEY AUTO_INCREMENT,
            recipe_id INT NOT NULL,
            user_id INT,
            view_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45),
            FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )"
    ];
    
    // Execute each SQL statement
    foreach ($sqlStatements as $sql) {
        $pdo->exec($sql);
        echo "Executado: " . substr($sql, 0, 50) . "...\n";
    }
    
    // Insert sample data
    $sampleData = [
        // Historical periods
        "INSERT IGNORE INTO historical_periods (id, name, start_year, end_year, description) VALUES 
        (1, 'Primeiros Povos', -10000, 1500, 'Período pré-colonial com povos indígenas'),
        (2, 'Idade Média', 476, 1453, 'Período da história europeia entre a queda do Império Romano e o Renascimento'),
        (3, 'Primeira República', 1889, 1930, 'Período da história do Brasil após a proclamação da República'),
        (4, 'Império Romano', -27, 476, 'Período de domínio do Império Romano')",
        
        // Recipe categories
        "INSERT IGNORE INTO recipe_categories (id, name, description) VALUES 
        (1, 'Indígena', 'Receitas dos povos originários do Brasil'),
        (2, 'Europeia', 'Receitas da Europa com influências medievais e modernas'),
        (3, 'Africana', 'Receitas com influências africanas'),
        (4, 'Brasileira', 'Receitas típicas do Brasil'),
        (5, 'Árabe', 'Receitas com influências árabes'),
        (6, 'Asiática', 'Receitas com influências asiáticas')",
        
        // Sample admin user (password: admin123)
        "INSERT IGNORE INTO users (id, username, email, password_hash, first_name, last_name, role, is_active) VALUES 
        (1, 'admin', 'admin@historicalrecipes.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'Admin', 'User', 'admin', TRUE)"
    ];
    
    foreach ($sampleData as $sql) {
        $pdo->exec($sql);
        echo "Dados inseridos: " . substr($sql, 0, 50) . "...\n";
    }
    
    echo "\nBanco de dados inicializado com sucesso!\n";
    echo "Usuário admin criado:\n";
    echo "  - Username: admin\n";
    echo "  - Email: admin@historicalrecipes.com\n";
    echo "  - Password: admin123\n";
    
} catch (Exception $e) {
    echo "Erro ao inicializar o banco de dados: " . $e->getMessage() . "\n";
}
?>