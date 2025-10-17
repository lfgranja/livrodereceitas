# Database Schema for Historical Recipes Application

## Overview
This document outlines the database schema for transforming the static historical recipes website into a dynamic application with user registration, evaluation system, team collaboration, and more.

## Database Technologies
- Primary: MySQL or PostgreSQL
- Alternative: MongoDB (NoSQL) for more flexible document storage
- Session storage: Redis or database tables

## Database Tables/ Collections

### 1. Users Table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,  -- Hashed password
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('student', 'teacher', 'admin') DEFAULT 'student',
    class_id INT,  -- For students to identify their class
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    profile_picture_url VARCHAR(255),
    email_verified BOOLEAN DEFAULT FALSE,
    reset_password_token VARCHAR(255),
    reset_password_expires TIMESTAMP
);
```

### 2. Classes/School Groups Table
```sql
CREATE TABLE classes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,  -- e.g., "6º A", "7º B"
    teacher_id INT NOT NULL,  -- Teacher in charge
    school_name VARCHAR(100),
    academic_year YEAR,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);
```

### 3. Teams Table
```sql
CREATE TABLE teams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    class_id INT NOT NULL,
    created_by INT NOT NULL,  -- User who created the team
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### 4. Team Members Table
```sql
CREATE TABLE team_members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    team_id INT NOT NULL,
    user_id INT NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_leader BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_team_user (team_id, user_id)
);
```

### 5. Historical Periods Table
```sql
CREATE TABLE historical_periods (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,  -- e.g., "Primeiros Povos", "Idade Média"
    start_year INT,  -- Approximate start year (can be negative for BC)
    end_year INT,    -- Approximate end year
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 6. Recipe Categories Table
```sql
CREATE TABLE recipe_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,  -- e.g., "Indígena", "Europeia", "Africana"
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 7. Recipes Table
```sql
CREATE TABLE recipes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    historical_period_id INT NOT NULL,
    category_id INT NOT NULL,
    difficulty ENUM('Fácil', 'Médio', 'Difícil') DEFAULT 'Médio',
    image_url VARCHAR(255),
    history TEXT NOT NULL,  -- Historical context of the recipe
    preparation_original TEXT,  -- Original preparation method
    preparation_modern TEXT,    -- Modern adapted preparation
    nutritional_info TEXT,      -- Nutritional information
    context TEXT,               -- Cultural and historical context
    who_prepared TEXT,          -- Who prepared it historically
    who_consumed TEXT,          -- Who consumed it historically
    rituals TEXT,               -- Associated rituals or festivities
    reflection TEXT,            -- Reflection section
    created_by INT NOT NULL,    -- User who created the recipe
    team_id INT,                -- Team that created the recipe
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_published BOOLEAN DEFAULT FALSE,  -- Whether the recipe is visible to others
    FOREIGN KEY (historical_period_id) REFERENCES historical_periods(id),
    FOREIGN KEY (category_id) REFERENCES recipe_categories(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (team_id) REFERENCES teams(id)
);
```

### 8. Recipe Ingredients Table
```sql
CREATE TABLE recipe_ingredients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recipe_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    amount VARCHAR(50),
    type ENUM('original', 'modern') NOT NULL,  -- Original historical vs modern substitute
    price DECIMAL(10,2),      -- Modern price if applicable
    location VARCHAR(200),    -- Where to find in modern times
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);
```

### 9. User Evaluations Table
```sql
CREATE TABLE user_evaluations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evaluator_id INT NOT NULL,  -- User performing the evaluation
    recipe_id INT NOT NULL,     -- Recipe being evaluated
    team_id INT NOT NULL,       -- Team that created the recipe
    evaluation_type ENUM('research_quality', 'creativity', 'presentation', 'historical_connection') NOT NULL,
    rating TINYINT CHECK (rating >= 1 AND rating <= 5),  -- 1-5 star rating
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (evaluator_id) REFERENCES users(id),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id),
    FOREIGN KEY (team_id) REFERENCES teams(id),
    UNIQUE KEY unique_evaluation (evaluator_id, recipe_id, evaluation_type)
);
```

### 10. Recipe Evaluations Summary View
```sql
CREATE VIEW recipe_evaluations_summary AS
SELECT 
    recipe_id,
    AVG(CASE WHEN evaluation_type = 'research_quality' THEN rating END) AS avg_research_quality,
    AVG(CASE WHEN evaluation_type = 'creativity' THEN rating END) AS avg_creativity,
    AVG(CASE WHEN evaluation_type = 'presentation' THEN rating END) AS avg_presentation,
    AVG(CASE WHEN evaluation_type = 'historical_connection' THEN rating END) AS avg_historical_connection,
    AVG(rating) AS overall_rating,
    COUNT(*) AS total_evaluations
FROM user_evaluations
GROUP BY recipe_id;
```

### 11. Recipe Images/Gallery Table
```sql
CREATE TABLE recipe_gallery (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recipe_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    description VARCHAR(200),
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_featured BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);
```

### 12. Recipe Comments Table
```sql
CREATE TABLE recipe_comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recipe_id INT NOT NULL,
    user_id INT NOT NULL,
    parent_comment_id INT NULL,  -- For nested replies
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (parent_comment_id) REFERENCES recipe_comments(id) ON DELETE CASCADE
);
```

### 13. User Sessions Table (for authentication)
```sql
CREATE TABLE user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 14. Recipe Views/Analytics Table
```sql
CREATE TABLE recipe_views (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recipe_id INT NOT NULL,
    user_id INT,  -- NULL for anonymous views
    view_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Relationships Summary

- Users can create multiple recipes and be part of multiple teams
- Teams have multiple members and can create multiple recipes
- Recipes belong to one category and one historical period
- Recipes can have multiple ingredients (original and modern)
- Recipes can be evaluated by multiple users based on multiple criteria
- Recipes can have multiple gallery images and comments

## Indexes for Performance

```sql
-- Indexes for common queries
CREATE INDEX idx_recipes_period ON recipes(historical_period_id);
CREATE INDEX idx_recipes_category ON recipes(category_id);
CREATE INDEX idx_recipes_creator ON recipes(created_by);
CREATE INDEX idx_recipes_team ON recipes(team_id);
CREATE INDEX idx_recipes_published ON recipes(is_published);

CREATE INDEX idx_evaluations_recipe ON user_evaluations(recipe_id);
CREATE INDEX idx_evaluations_evaluator ON user_evaluations(evaluator_id);

CREATE INDEX idx_team_members_team ON team_members(team_id);
CREATE INDEX idx_team_members_user ON team_members(user_id);

CREATE INDEX idx_recipe_ingredients_recipe ON recipe_ingredients(recipe_id);
```

This database schema provides a solid foundation for the dynamic historical recipes application with user registration, team collaboration, evaluation system, and all the required features.