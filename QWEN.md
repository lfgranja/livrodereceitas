# Qwen Code Context File

## Project Overview

The "Livro de Receitas Históricas: Sabores e Sabores" (Historical Recipes Book: Knowledges and Flavors) is an educational web application that has been transformed from a static website into a comprehensive dynamic system. The application uses historical culinary practices to connect students with different time periods and cultures, making history learning more practical and engaging.

The system now includes user registration, authentication, team collaboration, evaluation system, search functionality, and a fully dynamic UI that integrates with the backend database. It's designed for the Brazilian educational context, specifically targeting 6th to 9th grade students in Campo Grande.

## Project Structure

```
/home/lfgranja/development/livrodereceitas/
├── historical-recipes-app/
│   └── website/                 # Main PHP web application (transformed from static)
│       ├── api/                 # API endpoints for dynamic functionality
│       │   ├── recipes.php      # Recipe management API
│       │   ├── evaluations.php  # Evaluation/rating system API
│       │   ├── teams.php        # Team management API
│       │   ├── search.php       # Search functionality API
│       │   └── search_advanced.php # Advanced search with filters
│       ├── auth/                # Authentication system
│       │   └── auth_functions.php # User authentication functions
│       ├── config/              # Configuration files
│       │   └── database.php     # Database configuration
│       ├── css/                 # Styling
│       │   └── themes.css       # Theme (light/dark) styles
│       ├── js/                  # JavaScript files
│       │   └── i18n.js          # Internationalization support
│       ├── index.php            # Main application page with authentication
│       ├── index_dynamic.php    # Dynamic version with API integration
│       ├── index_final.php      # Final version with all features
│       ├── login.php            # User login page
│       ├── register.php         # User registration page
│       ├── settings.php         # User settings page
│       ├── recipe-details.php   # Recipe details page
│       ├── logout.php           # Logout functionality
│       └── init_database.php    # Database initialization script
├── historical-recipes-app/HistoricalRecipesApp/ # Original React Native app
├── HistoricalRecipesApp/        # Duplicate directory (likely staging)
├── Livreto de apoio - Livro de Receitas Históricas - Saberes e Sabores.pdf # Educational documentation
├── livreto_text.txt             # Text version of educational content
└── QWEN.md                      # This file
```

## Key Features Implemented

### 1. User Authentication System
- **Registration/Login**: Secure user registration and login with password hashing
- **Session Management**: Proper session handling with security measures
- **User Roles**: Student, teacher, and admin roles with different permissions
- **Profile Management**: Users can update their profile information

### 2. Database Integration
- **Schema Design**: Comprehensive database schema with 14+ tables
- **User Management**: Users, classes, teams, and team members
- **Recipe Management**: Recipes with historical periods and categories
- **Evaluation System**: Multi-dimensional rating system with research quality, creativity, presentation, and historical connection criteria

### 3. Recipe Management
- **Create/Read/Update/Delete**: Full CRUD operations for recipes
- **Ingredient Tracking**: Both historical and modern ingredients with pricing
- **Historical Context**: Detailed historical information for each recipe
- **Team-Based Creation**: Recipes can be created by individuals or teams

### 4. Team Collaboration
- **Team Creation**: Users can create and manage teams
- **Team Membership**: Users can join teams and designate leaders
- **Collaborative Recipe Creation**: Teams can work together on recipes

### 5. Evaluation System
- **Multi-Dimensional Rating**: Research quality, creativity, presentation, and historical connection
- **Feedback Mechanism**: Comments and ratings for recipes
- **Average Calculations**: Automatic calculation of average ratings

### 6. Search and Filtering
- **Advanced Search**: Search across multiple criteria
- **Filtering**: Filter recipes by historical period, culture, difficulty
- **Real-time Results**: Dynamic search that updates results in real-time

### 7. Dynamic UI
- **Responsive Design**: Works well on different screen sizes
- **Interactive Elements**: Forms, modals, and dynamic content loading
- **Loading States**: Proper feedback during data loading

### 8. Additional Features
- **Internationalization**: Support for Portuguese and English
- **Theme Support**: Light and dark themes with automatic OS detection
- **Price Tracking**: Record and view ingredient prices
- **Illustration Tools**: Integration for creating recipe illustrations

## Database Schema

The system uses a MySQL database with the following key tables:

### Core Tables:
- **users**: User accounts with authentication and profile data
- **classes**: School classes with teacher assignment
- **teams**: Team management for collaborative work
- **team_members**: Team membership with leader designation
- **historical_periods**: Historical periods (e.g., "Primeiros Povos", "Idade Média")
- **recipe_categories**: Cultural categories (e.g., "Indígena", "Europeia")
- **recipes**: Detailed recipe information with historical context
- **recipe_ingredients**: Both historical and modern ingredient information
- **user_evaluations**: Multi-dimensional rating system
- **recipe_gallery**: Recipe images and illustrations
- **recipe_comments**: Recipe discussion and feedback

## API Endpoints

### /api/recipes.php
- GET: Fetch single recipe or multiple recipes with filtering
- POST: Create or update recipes (includes ingredients)
- DELETE: Remove recipes (with proper authorization)

### /api/evaluations.php
- GET: Fetch evaluations for a recipe with average ratings
- POST: Submit or update evaluation ratings

### /api/teams.php
- GET: Fetch teams with members
- POST: Create or update teams
- PUT: Add users to teams
- DELETE: Remove users from teams or delete teams

### /api/search.php and /api/search_advanced.php
- GET: Search across recipes, users, and teams with filters

## Technical Architecture

### Frontend
- **HTML/CSS/JavaScript**: Base technologies with Bootstrap 5 for responsive design
- **PHP**: Server-side rendering with dynamic content
- **AJAX**: Dynamic updates without page refresh
- **Internationalization**: Support for Portuguese and English

### Backend
- **PHP 7.4+**: Server-side logic with proper security measures
- **MySQL**: Database management with proper relationships
- **PDO**: Database abstraction with prepared statements
- **Sessions**: Secure authentication with proper session management

### Security Features
- **Password Hashing**: Using PHP's password_hash() with PASSWORD_DEFAULT
- **SQL Injection Prevention**: Prepared statements for all database queries
- **XSS Prevention**: Input sanitization with htmlspecialchars()
- **Session Security**: Regenerated session IDs and secure session handling
- **Authorization**: Role-based access control for different operations

## Development Conventions

### Code Style
- PHP files follow PSR-1 and PSR-12 standards with proper documentation
- Database queries use prepared statements to prevent SQL injection
- User input is validated and sanitized before processing
- Error handling with try-catch blocks and proper logging

### File Organization
- API endpoints are in the `/api/` directory
- Authentication functions in `/auth/`
- Configuration files in `/config/`
- Static assets (CSS, JS) in respective directories

## Building and Running

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx) with PHP support
- Composer for dependency management (if any)

### Installation
1. Clone the repository
2. Configure database connection in `/config/database.php`
3. Run the database initialization script: `php init_database.php`
4. Set up the web server to point to the `website` directory
5. Configure URL rewriting rules if needed

### Configuration
- Update database credentials in `/config/database.php`
- Set up appropriate security settings for production use
- Configure SSL for secure authentication in production

## Educational Context

This application is designed for the Brazilian educational context, specifically targeting 6th to 9th grade students in Campo Grande. The pedagogical approach connects History with culinary practices, allowing students to explore different historical periods and cultures through food. Students research, document, and potentially prepare historical recipes while learning about the cultural and historical context of these dishes.

The system promotes:
- Collaborative learning through team features
- Critical thinking about culture and food
- Practical application of historical knowledge
- Value of ancestral culinary knowledge

## Project Status

The transformation from static website to dynamic system is complete with all requested features implemented:

✅ User registration and authentication system  
✅ Database integration with comprehensive schema  
✅ Team collaboration features  
✅ Evaluation/rating system  
✅ Search and filtering functionality  
✅ Dynamic UI with proper API integration  
✅ Settings and user management pages  
✅ Recipe creation and management  
✅ Internationalization support  
✅ Theme support (light/dark)  

The system is fully functional and ready for educational use in the classroom environment for which it was designed.