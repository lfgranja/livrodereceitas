<?php
// index.php
// Main application page with authentication

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
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configurações</a></li>
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
            <?php endif; ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tools-tab" data-bs-toggle="tab" data-bs-target="#tools" type="button" role="tab" data-i18n="tools">Ferramentas</button>
            </li>
            <?php if ($isLoggedIn): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams" type="button" role="tab" data-i18n="teams">Equipes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="presentation-tab" data-bs-toggle="tab" data-bs-target="#presentation" type="button" role="tab" data-i18n="presentation">Apresentação</button>
            </li>
            <?php endif; ?>
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
                                <h5 class="card-title"><i class="fas fa-star text-warning"></i> Destaques</h5>
                                <ul class="list-group list-group-flush" id="featuredRecipes">
                                    <!-- Será populado dinamicamente -->
                                    <li class="list-group-item">Pão de Açúcar Medieval</li>
                                    <li class="list-group-item">Mingau dos Primeiros Povos</li>
                                    <li class="list-group-item">Feijoada da Primeira República</li>
                                </ul>
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
                                <option>Primeiros Povos</option>
                                <option>Idade Média</option>
                                <option>Primeira República</option>
                                <option>Afroamericana</option>
                            </select>
                            <select class="form-select" id="filterCulture">
                                <option value="">Todas as Culturas</option>
                                <option>Indígena</option>
                                <option>Europeia</option>
                                <option>Africana</option>
                                <option>Brasileira</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id="recipesContainer">
                    <!-- Será populado dinamicamente -->
                    <div class="col-md-4">
                        <div class="card recipe-card">
                            <img src="https://via.placeholder.com/300x200/4A90E2/FFFFFF?text=Pão+Medieval" class="recipe-image" alt="Pão de Açúcar Medieval">
                            <div class="card-body">
                                <h5 class="card-title">Pão de Açúcar Medieval</h5>
                                <p class="card-text">Receita tradicional dos tempos medievais com ingredientes simples</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">Idade Média</span>
                                    <span class="text-muted">Difícil</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card recipe-card">
                            <img src="https://via.placeholder.com/300x200/E94B3C/FFFFFF?text=Mingau+Indígena" class="recipe-image" alt="Mingau dos Primeiros Povos">
                            <div class="card-body">
                                <h5 class="card-title">Mingau dos Primeiros Povos</h5>
                                <p class="card-text">Receita ancestral dos povos indígenas brasileiros</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">Primeiros Povos</span>
                                    <span class="text-muted">Fácil</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card recipe-card">
                            <img src="https://via.placeholder.com/300x200/50C878/FFFFFF?text=Feijoada" class="recipe-image" alt="Feijoada da Primeira República">
                            <div class="card-body">
                                <h5 class="card-title">Feijoada da Primeira República</h5>
                                <p class="card-text">Receita popularizada durante a Primeira República Brasileira</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary">Primeira República</span>
                                    <span class="text-muted">Difícil</span>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                            <option>Primeiros Povos</option>
                                            <option>Idade Média</option>
                                            <option>Primeira República</option>
                                            <option>Afroamericana</option>
                                            <option>Império Romano</option>
                                            <option>Antigo Egito</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Cultura</label>
                                        <select class="form-select" id="recipeCulture">
                                            <option value="">Selecione...</option>
                                            <option>Indígena</option>
                                            <option>Europeia</option>
                                            <option>Africana</option>
                                            <option>Brasileira</option>
                                            <option>Árabe</option>
                                            <option>Asiática</option>
                                        </select>
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
                                    <!-- Será populado dinamicamente -->
                                </tbody>
                            </table>
                        </div>
                        
                        <button class="btn btn-success mt-3" id="recordNewPriceBtn">
                            <i class="fas fa-plus-circle me-1"></i> Registrar Novo Preço
                        </button>
                    </div>
                </div>
            </div>

            <!-- Teams Tab (only for logged in users) -->
            <?php if ($isLoggedIn): ?>
            <div class="tab-pane fade" id="teams" role="tabpanel">
                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="card p-4 text-center">
                            <h3 class="mb-4">Trabalho em Equipe</h3>
                            <p class="mb-4">Colabore com seus colegas para criar o melhor livro de receitas históricas.</p>
                            <button class="btn btn-primary btn-lg mb-3" id="createTeamBtn">
                                <i class="fas fa-plus-circle me-2"></i>Criar Nova Equipe
                            </button>
                            <button class="btn btn-outline-primary btn-lg mb-3" id="joinTeamBtn">
                                <i class="fas fa-user-plus me-2"></i>Entrar em Equipe
                            </button>
                            <p class="text-muted">Trabalhem juntos na criação e pesquisa de receitas históricas</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Presentation Tab (only for logged in users) -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to check if user is logged in and update UI accordingly
        function updateUIBasedOnAuth() {
            <?php if (!$isLoggedIn): ?>
            // Disable tabs that require authentication
            document.querySelectorAll('#create-tab, #teams-tab, #presentation-tab, #settingsLink').forEach(el => {
                el.parentElement.style.display = 'none';
            });
            <?php endif; ?>
        }

        // Add event listener for DOM content loaded
        document.addEventListener('DOMContentLoaded', function() {
            updateUIBasedOnAuth();
            
            // Event for recipe cards (for logged in users)
            <?php if ($isLoggedIn): ?>
            document.querySelectorAll('.recipe-card').forEach(card => {
                card.addEventListener('click', function() {
                    // In a full implementation, this would navigate to recipe details
                    const recipeId = this.getAttribute('data-recipe-id');
                    if (recipeId) {
                        window.location.href = `recipe-details.php?id=${recipeId}`;
                    } else {
                        alert('Receita selecionada! Em uma implementação completa, isso levaria aos detalhes da receita.');
                    }
                });
            });
            <?php endif; ?>
            
            // Form submission for recipe creation
            <?php if ($isLoggedIn): ?>
            document.getElementById('recipeForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // In a full implementation, this would send data to a PHP script
                alert('Receita salva com sucesso! Em uma implementação completa, isso salvaria no banco de dados.');
                
                // Reset form and switch to recipes tab
                this.reset();
                document.getElementById('recipes-tab').click();
            });
            <?php endif; ?>
            
            // Other event listeners remain the same as in the original
            // Event for adding ingredients
            document.getElementById('addOriginalIngredient')?.addEventListener('click', addIngredient);
            document.getElementById('addModernIngredient')?.addEventListener('click', addIngredient);

            // Event for search and filtering
            document.getElementById('searchRecipes')?.addEventListener('input', handleSearch);
            document.getElementById('filterPeriod')?.addEventListener('change', handleSearch);
            document.getElementById('filterCulture')?.addEventListener('change', handleSearch);

            // Events for tools
            document.getElementById('openPriceSearch')?.addEventListener('click', function() {
                document.getElementById('priceSearchSection').classList.remove('hidden');
            });

            // Events for teams
            document.getElementById('createTeamBtn')?.addEventListener('click', function() {
                const teamName = prompt('Nome da nova equipe:');
                if (teamName) {
                    alert(`Equipe "${teamName}" criada com sucesso!`);
                }
            });

            document.getElementById('joinTeamBtn')?.addEventListener('click', function() {
                const teamCode = prompt('Código ou nome da equipe:');
                if (teamCode) {
                    alert(`Solicitação para entrar na equipe "${teamCode}" enviada!`);
                }
            });

            // Event for logout
            <?php if ($isLoggedIn): ?>
            document.querySelector('.dropdown-item[href="logout.php"]')?.addEventListener('click', function(e) {
                if (!confirm('Tem certeza que deseja sair?')) {
                    e.preventDefault();
                }
            });
            <?php endif; ?>
        });

        // Function to add ingredient row
        function addIngredient(e) {
            const button = e.target;
            const containerId = button.previousElementSibling ? button.previousElementSibling.id : 
                               (button.parentElement.previousElementSibling ? button.parentElement.previousElementSibling.id : 'originalIngredients');
            const container = document.getElementById(containerId) || document.getElementById('modernIngredients');
            
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2 ingredient-row';
            
            if (containerId === 'modernIngredients' || button.id === 'addModernIngredient') {
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
            } else {
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
            }
            
            container.appendChild(newRow);
            
            // Add event for remove button
            newRow.querySelector('.remove-ingredient').addEventListener('click', function() {
                container.removeChild(newRow);
            });
        }

        // Function to handle search and filtering
        function handleSearch() {
            const searchTerm = document.getElementById('searchRecipes').value.toLowerCase();
            const periodFilter = document.getElementById('filterPeriod').value;
            const cultureFilter = document.getElementById('filterCulture').value;
            
            document.querySelectorAll('#recipesContainer .recipe-card').forEach(card => {
                const title = card.querySelector('.card-title').textContent.toLowerCase();
                const description = card.querySelector('.card-text').textContent.toLowerCase();
                const period = card.querySelector('.badge').textContent;
                const culture = card.querySelectorAll('.badge')[1] ? card.querySelectorAll('.badge')[1].textContent : '';
                
                const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                const matchesPeriod = !periodFilter || period === periodFilter;
                const matchesCulture = !cultureFilter || culture === cultureFilter;
                
                if (matchesSearch && matchesPeriod && matchesCulture) {
                    card.parentElement.style.display = 'block';
                } else {
                    card.parentElement.style.display = 'none';
                }
            });
        }

        // Language switcher functionality
        document.getElementById('portugueseBtn')?.addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('englishBtn').classList.remove('active');
            // In a full implementation, this would update the content language
        });

        document.getElementById('englishBtn')?.addEventListener('click', function() {
            this.classList.add('active');
            document.getElementById('portugueseBtn').classList.remove('active');
            // In a full implementation, this would update the content language
        });
    </script>
    <script src="js/i18n.js"></script>
</body>
</html>