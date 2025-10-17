<?php
// settings.php
// User settings page

require_once 'auth/auth_functions.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$currentUser = getCurrentUser();
$error_message = '';
$success_message = '';

// Handle profile update
if ($_SERVER['POST'] ?? false && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error_message = 'Por favor, preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Por favor, insira um email válido.';
    } else {
        $result = updateUserProfile($currentUser['id'], $first_name, $last_name, $email);
        if ($result['success']) {
            $success_message = $result['message'];
            // Refresh user data
            $currentUser = getCurrentUser();
        } else {
            $error_message = $result['message'];
        }
    }
}

// Handle password change
if ($_SERVER['POST'] ?? false && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';
    
    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        $error_message = 'Por favor, preencha todos os campos.';
    } elseif ($new_password !== $confirm_new_password) {
        $error_message = 'As novas senhas não coincidem.';
    } else {
        $result = changePassword($currentUser['id'], $current_password, $new_password);
        if ($result['success']) {
            $success_message = $result['message'];
        } else {
            $error_message = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - Livro de Receitas Históricas</title>
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
            padding: 1rem 0;
        }
        
        .settings-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 2rem;
            margin: 2rem 0;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #3a7bc8;
            border-color: #3a7bc8;
        }
        
        .nav-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }
        
        .section-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h4 mb-0">Sabores e Sabores</h1>
                    <p class="mb-0">Livro de Receitas Históricas</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="index.php" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
                    <a href="logout.php" class="btn btn-outline-light btn-sm ms-2"><i class="fas fa-sign-out-alt"></i> Sair</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-circle" style="font-size: 4rem; color: var(--primary-color);"></i>
                        </div>
                        <h5><?php echo htmlspecialchars($currentUser['first_name'] . ' ' . $currentUser['last_name']); ?></h5>
                        <p class="text-muted"><?php echo htmlspecialchars($currentUser['role']); ?></p>
                        <p class="text-muted"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                    </div>
                </div>
                
                <div class="list-group mt-3">
                    <a href="#" class="list-group-item list-group-item-action active">
                        <i class="fas fa-cog me-2"></i>Configurações
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>Perfil
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i>Segurança
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-paint-brush me-2"></i>Aparência
                    </a>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="settings-container">
                    <h2>Configurações da Conta</h2>
                    <p>Gerencie suas informações pessoais e preferências de conta.</p>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>
                    
                    <!-- Profile Settings Tab -->
                    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Perfil</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">Segurança</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab">Aparência</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="settingsTabContent">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            <form method="POST" action="">
                                <input type="hidden" name="update_profile" value="1">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">Primeiro Nome</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($currentUser['first_name']); ?>" required>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Último Nome</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($currentUser['last_name']); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($currentUser['email']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nome de Usuário</label>
                                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($currentUser['username']); ?>" disabled>
                                    <div class="form-text">O nome de usuário não pode ser alterado.</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Atualizar Perfil
                                </button>
                            </form>
                        </div>
                        
                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <form method="POST" action="">
                                <input type="hidden" name="change_password" value="1">
                                <div class="section-card">
                                    <h5><i class="fas fa-key me-2"></i>Alterar Senha</h5>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="current_password" class="form-label">Senha Atual</label>
                                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="new_password" class="form-label">Nova Senha</label>
                                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="confirm_new_password" class="form-label">Confirmar Nova Senha</label>
                                                <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i>Alterar Senha
                                    </button>
                                </div>
                            </form>
                            
                            <div class="section-card">
                                <h5><i class="fas fa-shield-alt me-2"></i>Segurança da Conta</h5>
                                <p>Ative a autenticação de dois fatores para maior segurança.</p>
                                <button class="btn btn-outline-primary">
                                    <i class="fas fa-qrcode me-2"></i>Ativar 2FA
                                </button>
                            </div>
                        </div>
                        
                        <!-- Appearance Tab -->
                        <div class="tab-pane fade" id="appearance" role="tabpanel">
                            <div class="section-card">
                                <h5><i class="fas fa-palette me-2"></i>Tema</h5>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="darkModeToggle">
                                    <label class="form-check-label" for="darkModeToggle">Modo Escuro</label>
                                </div>
                                <p class="text-muted">O tema será aplicado a todo o aplicativo.</p>
                            </div>
                            
                            <div class="section-card">
                                <h5><i class="fas fa-text-height me-2"></i>Tamanho da Fonte</h5>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-secondary btn-sm me-2" id="decreaseFontSize">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span>Padrão</span>
                                    <button class="btn btn-outline-secondary btn-sm ms-2" id="increaseFontSize">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <p class="text-muted mt-2">Ajuste o tamanho da fonte para melhor leitura.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode toggle
        document.getElementById('darkModeToggle')?.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-theme');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-theme');
                localStorage.setItem('theme', 'light');
            }
        });
        
        // Font size controls
        document.getElementById('increaseFontSize')?.addEventListener('click', function() {
            const body = document.body;
            const currentSize = parseFloat(getComputedStyle(body).fontSize);
            body.style.fontSize = (currentSize * 1.1) + 'px';
        });
        
        document.getElementById('decreaseFontSize')?.addEventListener('click', function() {
            const body = document.body;
            const currentSize = parseFloat(getComputedStyle(body).fontSize);
            body.style.fontSize = (currentSize * 0.9) + 'px';
        });
        
        // Initialize theme from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-theme');
                document.getElementById('darkModeToggle').checked = true;
            }
        });
    </script>
</body>
</html>