<?php
// recipe-details.php
// Recipe details page

require_once 'auth/auth_functions.php';

// Check if user is logged in (not required for viewing recipes)
$currentUser = getCurrentUser();
$isLoggedIn = isLoggedIn();

// Get recipe ID from URL parameter
$recipeId = $_GET['id'] ?? 1; // Default to first recipe if none specified

// In a real implementation, we would fetch the recipe from the database
// For now, we'll use sample data
$sampleRecipes = [
    1 => [
        'id' => 1,
        'title' => 'Pão de Açúcar Medieval',
        'description' => 'Receita tradicional dos tempos medievais com ingredientes simples',
        'historicalPeriod' => 'Idade Média',
        'culture' => 'Europeia',
        'difficulty' => 'Difícil',
        'image' => 'https://via.placeholder.com/800x400/4A90E2/FFFFFF?text=Pão+de+Açúcar+Medieval',
        'history' => 'Esta receita remonta ao período medieval europeu, quando o açúcar era um produto raro e caro, acessível apenas às classes mais abastadas. O \'pão de açúcar\' era uma denominação para pães que continham açúcar ou mel como adoçante, diferenciando-se dos pães comuns feitos apenas com ingredientes básicos.',
        'originalIngredients' => [
            ['name' => 'Farinha antiga', 'amount' => '1 xícara'],
            ['name' => 'Mel de engenho', 'amount' => '3 colheres'],
            ['name' => 'Ervas aromáticas', 'amount' => 'a gosto']
        ],
        'modernIngredients' => [
            ['name' => 'Farinha de trigo', 'amount' => '1 xícara', 'price' => 'R$ 2,50', 'location' => 'Mercado A'],
            ['name' => 'Mel', 'amount' => '3 colheres', 'price' => 'R$ 8,00', 'location' => 'Mercado B'],
            ['name' => 'Ervas frescas', 'amount' => 'a gosto', 'price' => 'R$ 3,00', 'location' => 'Mercado C']
        ],
        'preparationOriginal' => 'Misture todos os ingredientes e deixe descansar por 30 minutos. Originalmente cozido em forno de barro por aproximadamente 45 minutos.',
        'preparationModern' => "1. Pré-aqueça o forno a 180°C\n2. Misture os ingredientes secos em uma vasilha\n3. Adicione os líquidos e misture até formar uma massa homogênea\n4. Deixe descansar por 30 minutos\n5. Asse por 40-45 minutos em forno preaquecido",
        'nutritionalInfo' => 'Rico em carboidratos e vitaminas do complexo B, esta receita fornece energia necessária para as atividades diárias. Contém também fibras provenientes dos grãos integrais utilizados na farinha antiga.',
        'context' => 'Este pão era consumido por camponeses e nobres, sendo um alimento básico na dieta medieval. O açúcar, quando disponível, era considerado um produto de luxo e era utilizado em ocasiões especiais. A forma de preparo em forno de barro era comum nas cozinhas medievais, refletindo as técnicas culinárias da época.',
        'whoPrepared' => 'Principalmente mulheres da casa, com variações conforme a condição social',
        'whoConsumed' => 'Toda a família, com variações conforme a classe social',
        'rituals' => 'Algumas variações deste pão eram preparadas em festividades religiosas e datas comemorativas',
        'reflection' => 'Pense sobre como a comida se relaciona com a cultura e a história. A forma de preparar alimentos reflete as condições sociais, econômicas e tecnológicas de cada época. Por que é importante conhecer e valorizar os saberes culinários dos nossos antepassados? Porque eles representam um patrimônio cultural que moldou nossos hábitos alimentares atuais. Como podemos ter alimentos melhores hoje e no futuro? Aprendendo com as técnicas do passado e combinando com os conhecimentos e tecnologias atuais.'
    ],
    2 => [
        'id' => 2,
        'title' => 'Mingau dos Primeiros Povos',
        'description' => 'Receita ancestral dos povos indígenas brasileiros',
        'historicalPeriod' => 'Primeiros Povos',
        'culture' => 'Indígena',
        'difficulty' => 'Fácil',
        'image' => 'https://via.placeholder.com/800x400/E94B3C/FFFFFF?text=Mingau+dos+Primeiros+Povos',
        'history' => 'Esta receita ancestral dos povos indígenas brasileiros baseia-se no aproveitamento de ingredientes da floresta tropical. A mandioca, principal ingrediente, era fundamental na dieta das populações nativas e seu processamento envolvia técnicas sofisticadas de extração de polvilho.',
        'originalIngredients' => [
            ['name' => 'Farinha de mandioca', 'amount' => '2 xícaras'],
            ['name' => 'Coco ralado', 'amount' => '1 xícara'],
            ['name' => 'Mel de abelha', 'amount' => '2 colheres']
        ],
        'modernIngredients' => [
            ['name' => 'Farinha de mandioca', 'amount' => '2 xícaras', 'price' => 'R$ 4,00', 'location' => 'Mercado A'],
            ['name' => 'Coco ralado', 'amount' => '1 xícara', 'price' => 'R$ 3,50', 'location' => 'Mercado B'],
            ['name' => 'Mel', 'amount' => '2 colheres', 'price' => 'R$ 8,00', 'location' => 'Mercado C']
        ],
        'preparationOriginal' => 'Misture a farinha com o coco e adicione água morna lentamente, mexendo constantemente. Adoce com mel. Originalmente servido em cerimônias religiosas e festividades comunitárias.',
        'preparationModern' => "1. Em uma panela, misture a farinha de mandioca com o coco\n2. Adicione água morna gradualmente, mexendo sem parar\n3. Continue mexendo até obter uma consistência cremosa\n4. Adoce com mel a gosto\n5. Sirva quente",
        'nutritionalInfo' => 'Fonte de carboidratos e gorduras saudáveis provenientes do coco. Rico em fibras e minerais como potássio e magnésio.',
        'context' => 'Este mingau era preparado em cerimônias e festividades, simbolizando união e partilha. O processo de preparação era coletivo, envolvendo várias famílias da comunidade.',
        'whoPrepared' => 'Mulheres da aldeia, especialmente as mais experientes',
        'whoConsumed' => 'Toda a comunidade, especialmente durante cerimônias e celebrações',
        'rituals' => 'Preparado em ocasiões especiais como iniciações, celebrações sazonais e ritos de passagem',
        'reflection' => 'A conexão com a natureza era fundamental na cultura indígena. Cada ingrediente tinha um significado espiritual além do valor nutricional. Valorizar o passado significa reconhecer a sabedoria milenar sobre aproveitamento sustentável dos recursos naturais. O futuro da alimentação pode aprender com essa sabedoria ancestral para criar sistemas mais sustentáveis.'
    ]
];

$recipe = $sampleRecipes[$recipeId] ?? $sampleRecipes[1];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['title']); ?> - Livro de Receitas Históricas</title>
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
        
        .recipe-image {
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .ingredient-card, .section-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #3a7bc8;
            border-color: #3a7bc8;
        }
        
        .badge {
            font-size: 0.8rem;
        }
        
        .preparation-step {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            position: relative;
        }
        
        .preparation-step:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.5rem;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--primary-color);
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
                    <?php if ($isLoggedIn): ?>
                    <a href="logout.php" class="btn btn-outline-light btn-sm ms-2"><i class="fas fa-sign-out-alt"></i> Sair</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="recipe-image w-100">
                
                <div class="d-flex justify-content-between align-items-start mt-3">
                    <div>
                        <h1 class="mb-2"><?php echo htmlspecialchars($recipe['title']); ?></h1>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($recipe['historicalPeriod']); ?></span>
                            <span class="badge bg-success"><?php echo htmlspecialchars($recipe['culture']); ?></span>
                            <span class="badge bg-warning text-dark"><?php echo htmlspecialchars($recipe['difficulty']); ?></span>
                        </div>
                    </div>
                    <?php if ($isLoggedIn): ?>
                    <button class="btn btn-primary" id="prepareRecipeBtn">
                        <i class="fas fa-play-circle"></i> Preparar Receita
                    </button>
                    <?php endif; ?>
                </div>
                
                <!-- Descrição -->
                <div class="section-card">
                    <h4>Descrição</h4>
                    <p><?php echo htmlspecialchars($recipe['description']); ?></p>
                </div>
                
                <!-- Histórico -->
                <div class="section-card">
                    <h4>Histórico</h4>
                    <p><?php echo htmlspecialchars($recipe['history']); ?></p>
                    <button class="btn btn-outline-primary btn-sm" id="learnMoreBtn">
                        <i class="fas fa-book-open"></i> Saiba mais sobre o período
                    </button>
                </div>
                
                <!-- Ingredientes -->
                <div class="section-card">
                    <h4>Ingredientes</h4>
                    
                    <h5>Originais:</h5>
                    <ul class="list-group mb-3">
                        <?php foreach ($recipe['originalIngredients'] as $ing): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($ing['name']); ?>
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($ing['amount']); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <h5>Adaptados para Hoje:</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ingrediente</th>
                                    <th>Quantidade</th>
                                    <th>Preço</th>
                                    <th>Localização</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recipe['modernIngredients'] as $ing): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ing['name']); ?></td>
                                    <td><?php echo htmlspecialchars($ing['amount']); ?></td>
                                    <td><?php echo htmlspecialchars($ing['price'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($ing['location'] ?? '-'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Modo de Preparo -->
                <div class="section-card">
                    <h4>Modo de Preparo</h4>
                    
                    <h5>Original:</h5>
                    <p><?php echo htmlspecialchars($recipe['preparationOriginal']); ?></p>
                    
                    <h5>Adaptado (Atual):</h5>
                    <div>
                        <?php 
                        $steps = explode("\n", $recipe['preparationModern']);
                        foreach ($steps as $step):
                        ?>
                        <div class="preparation-step"><?php echo htmlspecialchars(ltrim($step, "0123456789.- \t")); ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Informações Nutricionais -->
                <div class="section-card">
                    <h4>Informações Nutricionais</h4>
                    <p><?php echo htmlspecialchars($recipe['nutritionalInfo']); ?></p>
                </div>
                
                <!-- Contexto Histórico -->
                <div class="section-card">
                    <h4>Contexto Histórico</h4>
                    <p><?php echo htmlspecialchars($recipe['context']); ?></p>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Origem e Contexto Social e Cultural</h6>
                            <p>Como era a vida das pessoas naquela época? Por que essa receita era importante?</p>
                            <p><?php echo htmlspecialchars($recipe['context']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Importância dos Ingredientes</h6>
                            <p>Qual a relevância de cada um dos ingredientes para aquele povo?</p>
                            <?php foreach ($recipe['originalIngredients'] as $ing): ?>
                            <p><strong><?php echo htmlspecialchars($ing['name']); ?>:</strong> Era importante porque...</p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <h6>Quem Preparava e Quem Consumia</h6>
                            <p><strong>Preparava:</strong> <?php echo htmlspecialchars($recipe['whoPrepared']); ?></p>
                            <p><strong>Consumia:</strong> <?php echo htmlspecialchars($recipe['whoConsumed']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Rituais ou Festividades</h6>
                            <p><?php echo htmlspecialchars($recipe['rituals']); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Reflexão -->
                <div class="section-card">
                    <h4>Reflexão</h4>
                    <h6>Ligando os pontos</h6>
                    <p>Pense sobre como a comida se relaciona com a cultura e a história.</p>
                    
                    <h6>Valorizando o passado</h6>
                    <p>Por que é importante conhecer e valorizar os saberes culinários dos nossos antepassados?</p>
                    
                    <h6>Pensando no futuro</h6>
                    <p>Como podemos ter alimentos melhores hoje e no futuro?</p>
                    
                    <p><?php echo htmlspecialchars($recipe['reflection']); ?></p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-utensils text-primary"></i> Detalhes da Receita</h5>
                        <p class="card-text">
                            <strong>Período:</strong> <?php echo htmlspecialchars($recipe['historicalPeriod']); ?><br>
                            <strong>Cultura:</strong> <?php echo htmlspecialchars($recipe['culture']); ?><br>
                            <strong>Dificuldade:</strong> <?php echo htmlspecialchars($recipe['difficulty']); ?><br>
                            <strong>Origem:</strong> <?php echo htmlspecialchars($recipe['historicalPeriod']); ?> (<?php echo htmlspecialchars($recipe['culture']); ?>)<br>
                        </p>
                    </div>
                </div>
                
                <?php if ($isLoggedIn): ?>
                <div class="evaluation-card">
                    <h5 class="card-title"><i class="fas fa-star text-warning"></i> Avaliação</h5>
                    <div class="d-flex align-items-center mb-2">
                        <div class="fs-4 me-2">4.5</div>
                        <div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <small class="text-muted">Baseado em 12 avaliações</small>
                        </div>
                    </div>
                    <button class="btn btn-outline-primary w-100 mt-2" id="rateRecipeBtn">
                        <i class="fas fa-star"></i> Avaliar Receita
                    </button>
                    <div class="mt-3">
                        <h6>Avalie os critérios:</h6>
                        <div class="mb-2">
                            <label class="form-label">Qualidade da Pesquisa</label>
                            <div class="rating" id="researchRating">
                                <i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Criatividade</label>
                            <div class="rating" id="creativityRating">
                                <i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Conexão Histórica</label>
                            <div class="rating" id="historicalRating">
                                <i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i><i class="fa-star far"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-paint-brush text-success"></i> Ilustrações</h5>
                        <p>Adicione ilustrações para complementar sua receita:</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-success btn-sm" id="addIllustrationBtn">
                                <i class="fas fa-plus"></i> Adicionar Ilustração
                            </button>
                            <button class="btn btn-outline-info btn-sm" id="viewGalleryBtn">
                                <i class="fas fa-image"></i> Ver Galeria
                            </button>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><i class="fas fa-lock text-primary"></i> Avalie esta receita</h5>
                        <p class="card-text">Faça login para avaliar esta receita e adicionar comentários.</p>
                        <a href="login.php" class="btn btn-primary">Fazer Login</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to handle rating clicks
        function setupRating(elementId) {
            const container = document.getElementById(elementId);
            if (!container) return;
            
            const stars = container.querySelectorAll('i');
            
            stars.forEach((star, index) => {
                star.addEventListener('click', () => {
                    // Reset all stars to empty
                    stars.forEach(s => {
                        s.className = 'far fa-star';
                    });
                    
                    // Fill stars up to the clicked one
                    for (let i = 0; i <= index; i++) {
                        stars[i].className = 'fas fa-star';
                    }
                });
                
                star.addEventListener('mouseover', () => {
                    // Reset all stars to empty
                    stars.forEach(s => {
                        s.className = 'far fa-star';
                    });
                    
                    // Fill stars up to the hovered one
                    for (let i = 0; i <= index; i++) {
                        stars[i].className = 'fas fa-star text-warning';
                    }
                });
                
                star.parentElement.addEventListener('mouseleave', () => {
                    // Reset all stars to empty
                    stars.forEach(s => {
                        s.className = 'far fa-star';
                    });
                });
            });
        }

        // Initialize ratings
        document.addEventListener('DOMContentLoaded', function() {
            setupRating('researchRating');
            setupRating('creativityRating');
            setupRating('historicalRating');
            
            // Add event listeners for buttons
            <?php if ($isLoggedIn): ?>
            document.getElementById('prepareRecipeBtn').addEventListener('click', function() {
                alert('Iniciando preparação da receita!\n\nNa versão completa, isso iniciaria um guia passo a passo para preparar a receita.');
            });
            
            document.getElementById('learnMoreBtn').addEventListener('click', function() {
                alert('Abrindo informações sobre o período histórico!\n\nNa versão completa, isso abriria uma página com mais informações sobre o período histórico da receita.');
            });
            
            document.getElementById('rateRecipeBtn').addEventListener('click', function() {
                const researchRating = document.querySelectorAll('#researchRating .fas').length;
                const creativityRating = document.querySelectorAll('#creativityRating .fas').length;
                const historicalRating = document.querySelectorAll('#historicalRating .fas').length;
                
                if (researchRating === 0 && creativityRating === 0 && historicalRating === 0) {
                    alert('Por favor, avalie pelo menos um dos critérios.');
                    return;
                }
                
                // In a real implementation, this would send the evaluation to the server
                alert(`Receita avaliada com sucesso!\n\nPesquisa: ${researchRating}/5\nCriatividade: ${creativityRating}/5\nConexão Histórica: ${historicalRating}/5\n\nObrigado pela sua avaliação.`);
            });
            
            document.getElementById('addIllustrationBtn').addEventListener('click', function() {
                alert('Abrindo ferramenta de desenho!\n\nNa versão completa, isso abriria uma ferramenta para criar ilustrações para a receita.');
            });
            
            document.getElementById('viewGalleryBtn').addEventListener('click', function() {
                alert('Abrindo galeria de ilustrações!\n\nNa versão completa, isso mostraria as ilustrações associadas a esta receita.');
            });
            <?php endif; ?>
        });
    </script>
    <script src="js/i18n.js"></script>
</body>
</html>