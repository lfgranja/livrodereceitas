<?php
// index_final.php
// Final index page with all dynamic functionality

require_once 'auth/auth_functions.php';

// Check if user is logged in
$currentUser = getCurrentUser();
$isLoggedIn = isLoggedIn();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livro de Receitas Históricas: Sabores e Sabores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/themes.css">
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #50C878;
            --accent-color: #E94B3C;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color), #3a7bc8);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .recipe-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            margin-bottom: 1.5rem;
            border: none;
            cursor: pointer;
        }
        
        .recipe-card:hover {
            transform: translateY(-5px);
        }
        
        .recipe-image {
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }
        
        .nav-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #3a7bc8;
            border-color: #3a7bc8;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: #43a866;
            border-color: #43a866;
        }
        
        .feature-card {
            text-align: center;
            padding: 2rem 1rem;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
            margin-bottom: 1.5rem;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .language-switcher {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
        
        .footer {
            background-color: #e9ecef;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        .hidden {
            display: none;
        }
        
        .price-table {
            border-collapse: collapse;
            width: 100%;
        }
        
        .price-table th, .price-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .price-table th {
            background-color: #e9ecef;
        }
        
        .user-greeting {
            margin-right: 1rem;
        }
        
        .user-menu {
            position: relative;
        }
        
        .team-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .member-badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem;
        }
        
        .leader-badge {
            background-color: var(--primary-color);
            color: white;
        }
        
        .evaluation-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .rating {
            color: #ffc107;
        }
        
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold">Sabores e Sabores</h1>
                    <p class="lead">Livro de Receitas Históricas</p>
                    <p>Bem-vindos, futuros chefs e historiadores!</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="language-switcher">
                        <div class="btn-group">
                            <button class="btn btn-light btn-sm active" id="portugueseBtn">PT</button>
                            <button class="btn btn-light btn-sm" id="englishBtn">EN</button>
                        </div>
                    </div>
                    
                    <?php if ($isLoggedIn): ?>
                    <div class="dropdown user-menu mt-2">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?php echo htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']); ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Meu Perfil</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Sair</a></li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <div class="mt-2">
                        <a href="login.php" class="btn btn-light me-2">Entrar</a>
                        <a href="register.php" class="btn btn-outline-light">Registrar</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" id="appTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" data-i18n="home">Início</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="recipes-tab" data-bs-toggle="tab" data-bs-target="#recipes" type="button" role="tab" data-i18n="recipes">Receitas</button>
            </li>
            <?php if ($isLoggedIn): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button" role="tab" data-i18n="createRecipe">Criar Receita</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams" type="button" role="tab" data-i18n="teams">Equipes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="presentation-tab" data-bs-toggle="tab" data-bs-target="#presentation" type="button" role="tab" data-i18n="presentation">Apresentação</button>
            </li>
            <?php endif; ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tools-tab" data-bs-toggle="tab" data-bs-target="#tools" type="button" role="tab" data-i18n="tools">Ferramentas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" data-i18n="about">Sobre</button>
            </li>
            <?php if ($isLoggedIn): ?>
            <li class="nav-item ms-auto" role="presentation">
                <a class="nav-link" href="settings.php" id="settingsLink" data-i18n="settings"><i class="fas fa-cog"></i> Configurações</a>
            </li>
            <?php endif; ?>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="appTabsContent">
            <!-- Home Tab -->
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="mb-4">Vamos aprender História na Cozinha!</h2>
                        <p>Este guia foi feito especialmente para você e sua equipe embarcarem em uma deliciosa aventura pelo tempo. Com o projeto "Livro de Receitas Históricas: Sabores e Sabores", vocês vão descobrir que a História também se encontra na nossa mesa!</p>
                        <p>Preparem seus cadernos, lápis de cor e muita curiosidade, porque vamos juntos explorar os ingredientes, os modos de preparo e os segredos culinários de povos e épocas passadas.</p>
                        
                        <?php if (!$isLoggedIn): ?>
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i>Registre-se para explorar mais!</h5>
                            <p>Crie sua conta para poder criar receitas, fazer avaliações e colaborar com sua equipe.</p>
                            <a href="register.php" class="btn btn-primary">Criar Conta</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-star text-warning"></i> Receitas em Destaque</h5>
                                <div id="featuredRecipesContainer">
                                    <!-- Will be populated dynamically -->
                                    <div class="loader" id="featuredLoader"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="row mt-5">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h4>Explorar Receitas</h4>
                            <p>Descubra receitas históricas de diferentes períodos e culturas</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-paint-brush"></i>
                            </div>
                            <h4>Desenhos & Ilustrações</h4>
                            <p>Crie ilustrações para complementar suas receitas históricas</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>Trabalho em Equipe</h4>
                            <p>Colabore com colegas na criação de seu livro de receitas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipes Tab -->
            <div class="tab-pane fade" id="recipes" role="tabpanel">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchRecipes" placeholder="Buscar receitas...">
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <select class="form-select" id="filterPeriod">
                                <option value="">Todos os Períodos</option>
                                <option value="1">Primeiros Povos</option>
                                <option value="2">Idade Média</option>
                                <option value="3">Primeira República</option>
                                <option value="4">Afroamericana</option>
                            </select>
                            <select class="form-select" id="filterCulture">
                                <option value="">Todas as Culturas</option>
                                <option value="1">Indígena</option>
                                <option value="2">Europeia</option>
                                <option value="3">Africana</option>
                                <option value="4">Brasileira</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id="recipesContainer">
                    <!-- Will be populated dynamically -->
                    <div class="loader" id="recipesLoader"></div>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    <button class="btn btn-outline-primary" id="loadMoreRecipes">Carregar Mais</button>
                </div>
            </div>

            <!-- Create Recipe Tab (only for logged in users) -->
            <?php if ($isLoggedIn): ?>
            <div class="tab-pane fade" id="create" role="tabpanel">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-4">
                            <h3 class="mb-4">Criar Nova Receita</h3>
                            <form id="recipeForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nome da Receita *</label>
                                        <input type="text" class="form-control" id="recipeName" required>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Período Histórico *</label>
                                        <select class="form-select" id="recipePeriod" required>
                                            <option value="">Selecione...</option>
                                            <option value="1">Primeiros Povos</option>
                                            <option value="2">Idade Média</option>
                                            <option value="3">Primeira República</option>
                                            <option value="4">Afroamericana</option>
                                            <option value="5">Império Romano</option>
                                            <option value="6">Antigo Egito</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Cultura</label>
                                        <select class="form-select" id="recipeCulture">
                                            <option value="">Selecione...</option>
                                            <option value="1">Indígena</option>
                                            <option value="2">Europeia</option>
                                            <option value="3">Africana</option>
                                            <option value="4">Brasileira</option>
                                            <option value="5">Árabe</option>
                                            <option value="6">Asiática</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Equipe (opcional)</label>
                                        <select class="form-select" id="recipeTeam">
                                            <option value="">Nenhuma - Receita individual</option>
                                            <!-- Options will be loaded dynamically -->
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Dificuldade</label>
                                        <select class="form-select" id="recipeDifficulty">
                                            <option value="Fácil">Fácil</option>
                                            <option value="Médio" selected>Médio</option>
                                            <option value="Difícil">Difícil</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Publicar</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="isPublished">
                                            <label class="form-check-label" for="isPublished">Tornar pública</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Descrição *</label>
                                    <textarea class="form-control" id="recipeDescription" rows="3" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Imagem (URL)</label>
                                    <input type="text" class="form-control" id="recipeImage">
                                    <div class="form-text">Cole o link de uma imagem online</div>
                                </div>

                                <h4 class="mt-4">Ingredientes</h4>
                                <h5>Originais (época histórica)</h5>
                                <div id="originalIngredients">
                                    <div class="row mb-2 ingredient-row">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control ingredient-name" placeholder="Nome do ingrediente">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control ingredient-amount" placeholder="Quantidade">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-outline-danger remove-ingredient"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-success mb-3" id="addOriginalIngredient">
                                    <i class="fas fa-plus"></i> Adicionar Ingrediente Original
                                </button>

                                <h5 class="mt-3">Adaptados (atual)</h5>
                                <div id="modernIngredients">
                                    <div class="row mb-2 ingredient-row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control ingredient-name" placeholder="Nome do ingrediente">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control ingredient-amount" placeholder="Quantidade">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control ingredient-price" placeholder="Preço (R$)">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control ingredient-location" placeholder="Localização">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-outline-danger remove-ingredient"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-success mb-4" id="addModernIngredient">
                                    <i class="fas fa-plus"></i> Adicionar Ingrediente Atual
                                </button>

                                <h4>Modo de Preparo</h4>
                                <div class="mb-3">
                                    <label class="form-label">Original (época histórica)</label>
                                    <textarea class="form-control" id="recipePreparationOriginal" rows="4"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Adaptado (atual)</label>
                                    <textarea class="form-control" id="recipePreparationModern" rows="4"></textarea>
                                </div>

                                <h4>Informações Históricas</h4>
                                <div class="mb-3">
                                    <label class="form-label">Origem e Contexto</label>
                                    <textarea class="form-control" id="recipeOrigin" rows="3"></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quem Preparava?</label>
                                        <input type="text" class="form-control" id="recipeWhoPrepared">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quem Consumia?</label>
                                        <input type="text" class="form-control" id="recipeWhoConsumed">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Rituais ou Festividades?</label>
                                    <input type="text" class="form-control" id="recipeRituals">
                                </div>

                                <h4>Informações Nutricionais</h4>
                                <div class="mb-3">
                                    <label class="form-label">Principais Nutrientes</label>
                                    <textarea class="form-control" id="recipeNutrition" rows="2"></textarea>
                                </div>

                                <h4>Reflexão</h4>
                                <div class="mb-3">
                                    <label class="form-label">Ligando os Pontos</label>
                                    <textarea class="form-control" id="recipeReflection" rows="3"></textarea>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="button" class="btn btn-outline-secondary me-md-2" id="cancelCreate">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Salvar Receita
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Teams Tab (only for logged in users) -->
            <?php if ($isLoggedIn): ?>
            <div class="tab-pane fade" id="teams" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Minhas Equipes</h5>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-primary w-100 mb-3" id="createTeamBtn">
                                    <i class="fas fa-plus-circle me-2"></i>Criar Nova Equipe
                                </button>
                                
                                <div id="myTeamsList">
                                    <div class="loader" id="teamsLoader"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Encontrar Equipes</h5>
                            </div>
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="searchTeams" placeholder="Buscar por nome da equipe...">
                                    <button class="btn btn-outline-secondary" type="button" id="searchTeamsBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                
                                <div id="searchResults">
                                    <p class="text-muted">Digite um nome para buscar equipes.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Entrar por código</h5>
                            </div>
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="teamCode" placeholder="Código da equipe">
                                    <button class="btn btn-success" type="button" id="joinTeamCodeBtn">
                                        <i class="fas fa-user-plus me-2"></i>Entrar na Equipe
                                    </button>
                                </div>
                                <p class="text-muted">Peça ao líder da equipe o código de acesso.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Presentation Tab (only for logged in users) -->
            <?php if ($isLoggedIn): ?>
            <div class="tab-pane fade" id="presentation" role="tabpanel">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="card p-4 text-center">
                            <h3 class="mb-4">Apresentação e Avaliação</h3>
                            <p class="mb-4">Prepare sua apresentação de degustação histórica e saiba como será avaliado.</p>
                            <button class="btn btn-success btn-lg mb-3" id="startPresentationBtn">
                                <i class="fas fa-chalkboard-teacher me-2"></i>Iniciar Apresentação
                            </button>
                            <p class="text-muted">Siga o guia para uma excelente apresentação final</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Tools Tab -->
            <div class="tab-pane fade" id="tools" role="tabpanel">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="feature-icon mx-auto">
                                    <i class="fas fa-search-dollar"></i>
                                </div>
                                <h5>Pesquisa de Preços</h5>
                                <p class="text-muted">Compare preços de ingredientes na sua região</p>
                                <button class="btn btn-outline-primary" id="openPriceSearch">Acessar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="feature-icon mx-auto">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <h5>Calculadora Nutricional</h5>
                                <p class="text-muted">Calcule os valores nutricionais das receitas</p>
                                <button class="btn btn-outline-primary" id="openNutritionCalc">Acessar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="feature-icon mx-auto">
                                    <i class="fas fa-book"></i>
                                </div>
                                <h5>Biblioteca Histórica</h5>
                                <p class="text-muted">Acesse referências sobre culinária histórica</p>
                                <button class="btn btn-outline-primary" id="openLibrary">Acessar</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card mb-4">
                            <div class="card-body text-center">
                                <div class="feature-icon mx-auto">
                                    <i class="fas fa-paint-brush"></i>
                                </div>
                                <h5>Desenhos & Ilustrações</h5>
                                <p class="text-muted">Crie ilustrações para suas receitas</p>
                                <button class="btn btn-outline-primary" id="openDrawings">Acessar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Price Search Section -->
                <div class="card mb-4 hidden" id="priceSearchSection">
                    <div class="card-header">
                        <h5><i class="fas fa-search-dollar me-2"></i> Pesquisa de Preços de Ingredientes</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="ingredientSearch" placeholder="Nome do ingrediente (ex: farinha, mel, açúcar)">
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100" id="searchPricesBtn">
                                    <i class="fas fa-search me-1"></i> Buscar
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="price-table" id="priceResults">
                                <thead>
                                    <tr>
                                        <th>Ingrediente</th>
                                        <th>Preço</th>
                                        <th>Localização</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Will be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                        
                        <button class="btn btn-success mt-3" id="recordNewPriceBtn">
                            <i class="fas fa-plus-circle me-1"></i> Registrar Novo Preço
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- About Tab -->
            <div class="tab-pane fade" id="about" role="tabpanel">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="card p-4">
                            <h3>Sobre o Projeto</h3>
                            <p>O projeto "Livro de Receitas Históricas: Sabores e Sabores" é uma ferramenta educacional desenvolvida para auxiliar no ensino de História do 6º ao 9º ano do Ensino Fundamental nas Escolas Municipais de Campo Grande.</p>
                            <p>Através da culinária histórica, os alunos exploram diferentes períodos e culturas, conectando o passado com o presente de forma prática e saborosa.</p>
                            <h4 class="mt-4">Objetivos:</h4>
                            <ul>
                                <li>Conectar História e culinária para um aprendizado mais envolvente</li>
                                <li>Promover o trabalho colaborativo entre estudantes</li>
                                <li>Desenvolver habilidades de pesquisa histórica</li>
                                <li>Fomentar o pensamento crítico sobre a cultura e alimentação</li>
                                <li>Valorizar saberes culinários ancestrais</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Livro de Receitas Históricas</h5>
                    <p>Projeto educacional para o ensino de História através da culinária.</p>
                </div>
                <div class="col-md-4">
                    <h5>Contato</h5>
                    <p>Para dúvidas sobre o uso pedagógico:</p>
                    <p>Prof. Luís Felipe Mesquita Granja</p>
                </div>
                <div class="col-md-4">
                    <h5>Recursos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Documentação para Professores</a></li>
                        <li><a href="#">Guia de Estudantes</a></li>
                        <li><a href="#">Exemplos de Receitas</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2025 Sabores e Sabores - Livro de Receitas Históricas. Todos os direitos reservados.</p>
            </div>
        </div>
    </div>

    <!-- Team Creation Modal -->
    <div class="modal fade" id="createTeamModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Criar Nova Equipe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createTeamForm">
                        <div class="mb-3">
                            <label for="teamName" class="form-label">Nome da Equipe</label>
                            <input type="text" class="form-control" id="teamName" required>
                        </div>
                        <div class="mb-3">
                            <label for="teamDescription" class="form-label">Descrição (Opcional)</label>
                            <textarea class="form-control" id="teamDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveTeamBtn">Criar Equipe</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Current user data
        const currentUser = <?php echo json_encode($currentUser); ?>;
        const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        
        // API endpoints
        const API_BASE = 'api/';
        
        // State management
        let currentView = 'home';
        let recipesPage = 0;
        const recipesPerPage = 12;
        
        // Function to make API calls
        async function apiCall(endpoint, method = 'GET', data = null) {
            const options = {
                method: method,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                credentials: 'same-origin' // Include cookies for session
            };
            
            if (data && method !== 'GET') {
                // Convert data to URL-encoded format
                const urlEncodedData = Object.keys(data).map(key => 
                    encodeURIComponent(key) + '=' + encodeURIComponent(data[key])
                ).join('&');
                options.body = urlEncodedData;
            } else if (data && method === 'GET') {
                // Add query parameters
                const queryString = Object.keys(data).map(key => 
                    encodeURIComponent(key) + '=' + encodeURIComponent(data[key])
                ).join('&');
                endpoint += '?' + queryString;
            }
            
            try {
                const response = await fetch(API_BASE + endpoint, options);
                return await response.json();
            } catch (error) {
                console.error('API call error:', error);
                return { success: false, message: 'Erro de conexão com o servidor.' };
            }
        }
        
        // Show loading state in a container
        function showLoader(containerId) {
            document.getElementById(containerId).innerHTML = '<div class="loader mx-auto"></div>';
        }
        
        // Load featured recipes
        async function loadFeaturedRecipes() {
            showLoader('featuredLoader');
            
            const response = await apiCall('recipes.php', 'GET', { limit: 6 });
            
            if (response.success) {
                const container = document.getElementById('featuredRecipesContainer');
                const recipes = response.recipes.slice(0, 3); // Show only first 3
                
                if (recipes.length > 0) {
                    let html = '<ul class="list-group list-group-flush">';
                    recipes.forEach(recipe => {
                        html += `<li class="list-group-item">${recipe.title}</li>`;
                    });
                    html += '</ul>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="alert alert-info">Nenhuma receita em destaque.</div>';
                }
            } else {
                document.getElementById('featuredRecipesContainer').innerHTML = `<div class="alert alert-danger">${response.message || 'Erro ao carregar receitas em destaque.'}</div>`;
            }
        }
        
        // Load recipes with pagination
        async function loadRecipes(page = 0) {
            if (page === 0) {
                showLoader('recipesLoader');
            }
            
            // Get filters
            const periodFilter = document.getElementById('filterPeriod').value;
            const cultureFilter = document.getElementById('filterCulture').value;
            
            const params = {
                limit: recipesPerPage,
                offset: page * recipesPerPage
            };
            
            if (periodFilter) params.period = periodFilter;
            if (cultureFilter) params.culture = cultureFilter;
            
            const response = await apiCall('recipes.php', 'GET', params);
            
            if (response.success) {
                const container = document.getElementById('recipesContainer');
                
                if (page === 0) {
                    // First page - replace content
                    container.innerHTML = '';
                } else {
                    // Additional page - append to content
                }
                
                if (response.recipes && response.recipes.length > 0) {
                    response.recipes.forEach(recipe => {
                        const recipeCard = document.createElement('div');
                        recipeCard.className = 'col-md-4';
                        recipeCard.innerHTML = `
                            <div class="card recipe-card">
                                <img src="${recipe.image_url || 'https://via.placeholder.com/300x200/4A90E2/FFFFFF?text=Sem+Imagem'}" class="recipe-image" alt="${recipe.title}">
                                <div class="card-body">
                                    <h5 class="card-title">${recipe.title}</h5>
                                    <p class="card-text">${recipe.description}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">${recipe.period_name}</span>
                                        <span class="text-muted">${recipe.difficulty}</span>
                                    </div>
                                    ${recipe.team_name ? `<div class="mt-2"><small class="text-muted">Equipe: ${recipe.team_name}</small></div>` : ''}
                                </div>
                            </div>
                        `;
                        container.appendChild(recipeCard);
                        
                        // Add click event to view recipe details
                        recipeCard.querySelector('.recipe-card').addEventListener('click', () => {
                            window.location.href = `recipe-details.php?id=${recipe.id}`;
                        });
                    });
                    
                    recipesPage = page;
                    
                    // Show load more button if there are more recipes
                    if (response.pagination && response.pagination.has_more) {
                        document.getElementById('loadMoreRecipes').style.display = 'block';
                    } else {
                        document.getElementById('loadMoreRecipes').style.display = 'none';
                    }
                } else {
                    if (page === 0) {
                        container.innerHTML = '<div class="col-12"><p class="text-center">Nenhuma receita encontrada.</p></div>';
                    }
                }
            } else {
                if (page === 0) {
                    document.getElementById('recipesContainer').innerHTML = `<div class="col-12"><p class="text-center text-danger">${response.message || 'Erro ao carregar receitas.'}</p></div>`;
                }
            }
        }
        
        // Load teams for the user
        async function loadUserTeams() {
            if (!isLoggedIn) return;
            
            showLoader('teamsLoader');
            
            const response = await apiCall('teams.php', 'GET', { user_id: currentUser.id });
            
            if (response.success) {
                const container = document.getElementById('myTeamsList');
                
                if (response.teams && response.teams.length > 0) {
                    let html = '';
                    
                    response.teams.forEach(team => {
                        html += `
                            <div class="team-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">${team.name}</h5>
                                    <span class="badge bg-primary">${team.class_name || 'Sem turma'}</span>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Criada em: ${new Date(team.created_at).toLocaleDateString()}</small>
                                </div>
                                <div class="mt-2">
                                    <strong>Membros:</strong>
                                    ${team.members && team.members.length > 0 ? 
                                        team.members.map(m => 
                                            `${m.first_name} ${m.last_name}${m.is_leader ? ' <span class="member-badge leader-badge">Líder</span>' : ''}`
                                        ).join(', ') : 
                                        'Nenhum membro'}
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewTeamDetails(${team.id})">Ver Detalhes</button>
                                    <button class="btn btn-sm btn-outline-success">Ir para Receitas</button>
                                </div>
                            </div>
                        `;
                    });
                    
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="alert alert-info">Você ainda não faz parte de nenhuma equipe.</div>';
                }
            } else {
                document.getElementById('myTeamsList').innerHTML = `<div class="alert alert-danger">${response.message || 'Erro ao carregar equipes.'}</div>`;
            }
        }
        
        // View team details
        function viewTeamDetails(teamId) {
            // In a full implementation, this would show team details in a modal or navigate to a team page
            alert(`Detalhes da equipe ${teamId} seriam exibidos aqui.`);
        }
        
        // Create a new team
        async function createTeam() {
            const name = document.getElementById('teamName').value;
            const description = document.getElementById('teamDescription').value;
            
            if (!name) {
                alert('Por favor, informe o nome da equipe.');
                return;
            }
            
            const response = await apiCall('teams.php', 'POST', {
                name: name,
                description: description
            });
            
            if (response.success) {
                // Close modal and reset form
                const modal = bootstrap.Modal.getInstance(document.getElementById('createTeamModal'));
                modal.hide();
                document.getElementById('createTeamForm').reset();
                
                // Reload teams
                loadUserTeams();
                
                alert(response.message);
            } else {
                alert(response.message || 'Erro ao criar equipe.');
            }
        }
        
        // Load teams for recipe creation
        async function loadTeamsForRecipe() {
            if (!isLoggedIn) return;
            
            const response = await apiCall('teams.php', 'GET', { user_id: currentUser.id });
            
            if (response.success) {
                const select = document.getElementById('recipeTeam');
                select.innerHTML = '<option value="">Nenhuma - Receita individual</option>';
                
                if (response.teams && response.teams.length > 0) {
                    response.teams.forEach(team => {
                        select.innerHTML += `<option value="${team.id}">${team.name}</option>`;
                    });
                }
            }
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Load initial data if user is logged in
            if (isLoggedIn) {
                loadUserTeams();
                loadTeamsForRecipe();
            }
            
            // Load featured recipes and initial recipes
            loadFeaturedRecipes();
            loadRecipes();
            
            // Team creation modal
            document.getElementById('createTeamBtn')?.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('createTeamModal'));
                modal.show();
            });
            
            document.getElementById('saveTeamBtn')?.addEventListener('click', createTeam);
            
            // Recipe form submission
            document.getElementById('recipeForm')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Collect form data
                const formData = {
                    title: document.getElementById('recipeName').value,
                    description: document.getElementById('recipeDescription').value,
                    historical_period_id: document.getElementById('recipePeriod').value,
                    category_id: document.getElementById('recipeCulture').value,
                    difficulty: document.getElementById('recipeDifficulty').value,
                    image_url: document.getElementById('recipeImage').value,
                    history: document.getElementById('recipeOrigin').value,
                    preparation_original: document.getElementById('recipePreparationOriginal').value,
                    preparation_modern: document.getElementById('recipePreparationModern').value,
                    nutritional_info: document.getElementById('recipeNutrition').value,
                    context: document.getElementById('recipeWhoPrepared').value,
                    who_prepared: document.getElementById('recipeWhoPrepared').value,
                    who_consumed: document.getElementById('recipeWhoConsumed').value,
                    rituals: document.getElementById('recipeRituals').value,
                    reflection: document.getElementById('recipeReflection').value,
                    team_id: document.getElementById('recipeTeam').value || null,
                    is_published: document.getElementById('isPublished').checked ? 1 : 0,
                };
                
                // Collect ingredients
                const ingredients = [];
                
                // Original ingredients
                document.querySelectorAll('#originalIngredients .ingredient-row').forEach(row => {
                    const name = row.querySelector('.ingredient-name').value;
                    const amount = row.querySelector('.ingredient-amount').value;
                    if (name && amount) {
                        ingredients.push({
                            name: name,
                            amount: amount,
                            type: 'original'
                        });
                    }
                });
                
                // Modern ingredients
                document.querySelectorAll('#modernIngredients .ingredient-row').forEach(row => {
                    const name = row.querySelector('.ingredient-name').value;
                    const amount = row.querySelector('.ingredient-amount').value;
                    const price = row.querySelector('.ingredient-price')?.value || null;
                    const location = row.querySelector('.ingredient-location')?.value || null;
                    if (name && amount) {
                        ingredients.push({
                            name: name,
                            amount: amount,
                            price: price,
                            location: location,
                            type: 'modern'
                        });
                    }
                });
                
                formData.ingredients = JSON.stringify(ingredients);
                
                // Submit to API
                const response = await apiCall('recipes.php', 'POST', formData);
                
                if (response.success) {
                    alert(response.message);
                    
                    // Reset form
                    document.getElementById('recipeForm').reset();
                    
                    // Switch to recipes tab
                    document.getElementById('recipes-tab').click();
                    
                    // Reload recipes
                    loadRecipes();
                } else {
                    alert(response.message || 'Erro ao salvar receita.');
                }
            });
            
            // Add ingredient functionality
            document.getElementById('addOriginalIngredient')?.addEventListener('click', function() {
                const container = document.getElementById('originalIngredients');
                const newRow = document.createElement('div');
                newRow.className = 'row mb-2 ingredient-row';
                newRow.innerHTML = `
                    <div class="col-md-8">
                        <input type="text" class="form-control ingredient-name" placeholder="Nome do ingrediente">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control ingredient-amount" placeholder="Quantidade">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger remove-ingredient"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                container.appendChild(newRow);
                
                // Add event to remove button
                newRow.querySelector('.remove-ingredient').addEventListener('click', function() {
                    container.removeChild(newRow);
                });
            });
            
            document.getElementById('addModernIngredient')?.addEventListener('click', function() {
                const container = document.getElementById('modernIngredients');
                const newRow = document.createElement('div');
                newRow.className = 'row mb-2 ingredient-row';
                newRow.innerHTML = `
                    <div class="col-md-5">
                        <input type="text" class="form-control ingredient-name" placeholder="Nome do ingrediente">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control ingredient-amount" placeholder="Quantidade">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control ingredient-price" placeholder="Preço (R$)">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control ingredient-location" placeholder="Localização">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger remove-ingredient"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                container.appendChild(newRow);
                
                // Add event to remove button
                newRow.querySelector('.remove-ingredient').addEventListener('click', function() {
                    container.removeChild(newRow);
                });
            });
            
            // Add event delegation for remove ingredient buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-ingredient')) {
                    const row = e.target.closest('.ingredient-row');
                    if (row) {
                        row.parentElement.removeChild(row);
                    }
                }
            });
            
            // Search recipes with advanced filtering
            document.getElementById('searchRecipes')?.addEventListener('input', async function() {
                const searchTerm = this.value;
                
                if (searchTerm.length > 2 || searchTerm.length === 0) {
                    // Build filters
                    const filters = {
                        q: searchTerm,
                        type: 'recipes',
                        period: document.getElementById('filterPeriod').value,
                        culture: document.getElementById('filterCulture').value
                    };
                    
                    const response = await apiCall('search_advanced.php', 'GET', filters);
                    
                    if (response.success) {
                        const container = document.getElementById('recipesContainer');
                        container.innerHTML = '';
                        
                        if (response.results.recipes && response.results.recipes.length > 0) {
                            response.results.recipes.forEach(recipe => {
                                const recipeCard = document.createElement('div');
                                recipeCard.className = 'col-md-4';
                                recipeCard.innerHTML = `
                                    <div class="card recipe-card">
                                        <img src="${recipe.image_url || 'https://via.placeholder.com/300x200/4A90E2/FFFFFF?text=Sem+Imagem'}" class="recipe-image" alt="${recipe.title}">
                                        <div class="card-body">
                                            <h5 class="card-title">${recipe.title}</h5>
                                            <p class="card-text">${recipe.description}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-primary">${recipe.period_name}</span>
                                                <span class="text-muted">${recipe.difficulty}</span>
                                            </div>
                                            ${recipe.team_name ? `<div class="mt-2"><small class="text-muted">Equipe: ${recipe.team_name}</small></div>` : ''}
                                        </div>
                                    </div>
                                `;
                                container.appendChild(recipeCard);
                                
                                // Add click event to view recipe details
                                recipeCard.querySelector('.recipe-card').addEventListener('click', () => {
                                    window.location.href = `recipe-details.php?id=${recipe.id}`;
                                });
                            });
                        } else {
                            container.innerHTML = '<div class="col-12"><p class="text-center">Nenhuma receita encontrada.</p></div>';
                        }
                    } else {
                        document.getElementById('recipesContainer').innerHTML = `<div class="col-12"><p class="text-center text-danger">${response.message || 'Erro ao buscar receitas.'}</p></div>`;
                    }
                }
            });
            
            // Filter recipes by period and culture
            document.getElementById('filterPeriod')?.addEventListener('change', async function() {
                const searchInput = document.getElementById('searchRecipes');
                // Trigger search with the same term but new filter
                await new Promise(resolve => setTimeout(resolve, 100)); // Small delay for UI update
                searchInput.dispatchEvent(new Event('input'));
            });
            
            document.getElementById('filterCulture')?.addEventListener('change', async function() {
                const searchInput = document.getElementById('searchRecipes');
                // Trigger search with the same term but new filter
                await new Promise(resolve => setTimeout(resolve, 100)); // Small delay for UI update
                searchInput.dispatchEvent(new Event('input'));
            });
            
            // Load more recipes
            document.getElementById('loadMoreRecipes')?.addEventListener('click', function() {
                loadRecipes(recipesPage + 1);
            });
            
            // Language switcher functionality
            document.getElementById('portugueseBtn')?.addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('englishBtn').classList.remove('active');
            });

            document.getElementById('englishBtn')?.addEventListener('click', function() {
                this.classList.add('active');
                document.getElementById('portugueseBtn').classList.remove('active');
            });
            
            // Tab change event
            document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
                tab.addEventListener('shown.bs.tab', function() {
                    currentView = this.getAttribute('data-bs-target').substring(1);
                    
                    // Load content based on the active tab
                    if (currentView === 'home' && document.getElementById('featuredRecipesContainer').innerHTML === '') {
                        loadFeaturedRecipes();
                    } else if (currentView === 'recipes') {
                        loadRecipes();
                    } else if (currentView === 'teams') {
                        loadUserTeams();
                    }
                });
            });
        });
    </script>
    <script src="js/i18n.js"></script>
</body>
</html>