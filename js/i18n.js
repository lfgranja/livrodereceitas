// i18n.js - Gerenciamento de internacionalização e temas

// Definição dos textos em diferentes idiomas
const translations = {
    'pt': {
        'home': 'Início',
        'recipes': 'Receitas',
        'createRecipe': 'Criar Receita',
        'tools': 'Ferramentas',
        'teams': 'Equipes',
        'presentation': 'Apresentação',
        'about': 'Sobre',
        'settings': 'Configurações',
        'welcome': 'Bem-vindos, futuros chefs e historiadores!',
        'projectDescription': 'Este guia foi feito especialmente para você e sua equipe embarcarem em uma deliciosa aventura pelo tempo. Com o projeto "Livro de Receitas Históricas: Sabores e Sabores", vocês vão descobrir que a História também se encontra na nossa mesa!',
        'letsGo': 'Vamos aprender História na Cozinha!',
        'prepareNotebooks': 'Preparem seus cadernos, lápis de cor e muita curiosidade, porque vamos juntos explorar os ingredientes, os modos de preparo e os segredos culinários de povos e épocas passadas.',
        'featuredRecipes': 'Receitas em Destaque',
        'exploreRecipes': 'Explorar Receitas',
        'createNewRecipe': 'Criar Nova Receita',
        'researchTools': 'Ferramentas de Pesquisa',
        'myTeams': 'Minhas Equipes',
        'tasteAndFlavors': 'Sabores e Saberes',
        'historicalRecipeBook': 'Livro de Receitas Históricas',
        'adventure': 'embarcarem em uma deliciosa aventura pelo tempo',
        'historyInTable': 'Este livro de receitas não será uma janela apenas um conjunto de instruções, mas uma janela para entender como as pessoas viviam, o que valorizavam e como se relacionavam com o mundo através dos alimentos.',
        'whatIsHistoricalRecipe': 'O que é um livro de receitas históricas?',
        'cookingHistory': 'Imagine um livro que conta Histórias através da comida. Não apenas receitas comuns: receitas que nos levam para outros tempos e lugares. Cada ingrediente, cada forma de cozinhar, cada detalhe, nos diz algo sobre as pessoas que viveram naquela época.',
        'whyExploreCooking': 'Por que explorar a culinária antiga?',
        'knowingPast': 'Conhecer o passado de um jeito mais gostoso: A comida é uma parte muito importante da vida das pessoas. Ao descobrir o que elas comiam, entendemos melhor como viviam, trabalhavam e celebravam.',
        'discoverIngredients': 'Descobrir ingredientes e sabores diferentes: Muitas vezes, os povos antigos usavam ingredientes que talvez não conheçamos hoje, ou preparavam os alimentos de maneiras surpreendentes.',
        'valuingOurCulture': 'Valorizar nossa cultura alimentar: A comida que comemos hoje tem raízes no passado. Ao conhecer as receitas antigas, podemos entender de onde vieram alguns dos nossos pratos preferidos.',
        'learningNature': 'Aprender sobre a natureza e a agricultura: Os ingredientes antigos nos mostram como as pessoas cultivavam a terra e aproveitavam os recursos naturais.',
        'thisProject': 'Este projeto é como uma investigação histórica na cozinha! Vocês serão detetives do sabor, buscando pistas sobre o passado em cada receita.',
        'searchPlaceholder': 'Buscar receitas...',
        'historicalPeriod': 'Período Histórico',
        'culture': 'Cultura',
        'difficulty': 'Dificuldade',
        'allPeriods': 'Todos os Períodos',
        'allCultures': 'Todas as Culturas',
        'easy': 'Fácil',
        'medium': 'Médio',
        'hard': 'Difícil',
        'recipeTitle': 'Título da Receita',
        'recipeDescription': 'Descrição',
        'recipeImage': 'Imagem',
        'originalIngredients': 'Ingredientes Originais',
        'adaptedIngredients': 'Ingredientes Adaptados',
        'preparationMethod': 'Modo de Preparo',
        'historicalInformation': 'Informações Históricas',
        'nutritionalInformation': 'Informações Nutricionais',
        'reflection': 'Reflexão',
        'saveRecipe': 'Salvar Receita',
        'cancel': 'Cancelar',
        'addIngredient': 'Adicionar Ingrediente',
        'name': 'Nome',
        'amount': 'Quantidade',
        'price': 'Preço',
        'location': 'Localização',
        'originalPreparation': 'Original (época histórica)',
        'adaptedPreparation': 'Adaptado (atual)',
        'originAndContext': 'Origem e Contexto Social e Cultural',
        'importanceOfIngredients': 'Importância dos Ingredientes',
        'whoPrepared': 'Quem Preparava?',
        'whoConsumed': 'Quem Consumia?',
        'rituals': 'Rituais ou Festividades?',
        'connectingPoints': 'Ligando os pontos',
        'valuingPast': 'Valorizando o passado',
        'thinkingFuture': 'Pensando no futuro',
        'principalNutrients': 'Principais Nutrientes',
        'searchPrices': 'Buscar Preços',
        'calculatenutrition': 'Calcular Nutrientes',
        'accessLibrary': 'Acessar Biblioteca',
        'createIllustration': 'Criar Ilustração',
        'viewGallery': 'Ver Galeria',
        'createTeam': 'Criar Equipe',
        'joinTeam': 'Entrar em Equipe',
        'teamName': 'Nome da Equipe',
        'teamMembers': 'Membros',
        'teamRecipes': 'Receitas',
        'teamStatus': 'Status',
        'active': 'Ativo',
        'presentationGuide': 'Guia para a Apresentação',
        'chooseRecipe': 'Escolham uma receita: Decidam qual das receitas vocês vão preparar para a degustação',
        'prepareCarefully': 'Preparem com cuidado: Sigam o modo de preparo e peçam ajuda se precisarem',
        'setTable': 'Montem a mesa: Deixem a mesa da degustação bonita e organizada',
        'showBook': 'Apresentem o livro: Mostrem o livro de receitas para as outras equipes e expliquem a história da receita',
        'offerTasting': 'Ofereçam a degustação: Sirvam um pedacinho da receita para cada pessoa experimentar',
        'evaluationCriteria': 'Critérios de Avaliação',
        'qualityResearch': 'Qualidade da pesquisa culinária e histórica',
        'creativity': 'Criatividade e apresentação',
        'presentationQuality': 'Apresentação da degustação',
        'historicalConnection': 'Conexão com a história e cultura',
        'evaluate': 'Avaliar',
        'comments': 'Comentários',
        'projectAbout': 'Sobre o Projeto',
        'projectObjectives': 'Objetivos do Projeto',
        'connectHistory': 'Conectar História e culinária para um aprendizado mais envolvente',
        'promoteTeamwork': 'Promover o trabalho colaborativo entre estudantes',
        'developResearch': 'Desenvolver habilidades de pesquisa histórica',
        'fosterCritical': 'Fomentar o pensamento crítico sobre a cultura e alimentação',
        'valueAncestral': 'Valorizar saberes culinários ancestrais',
        'language': 'Idioma',
        'theme': 'Tema',
        'fontSize': 'Tamanho da Fonte',
        'notifications': 'Notificações',
        'privacy': 'Privacidade',
        'profile': 'Perfil',
        'appearance': 'Aparência',
        'dark': 'Escuro',
        'light': 'Claro',
        'auto': 'Automático'
    },
    'en': {
        'home': 'Home',
        'recipes': 'Recipes',
        'createRecipe': 'Create Recipe',
        'tools': 'Tools',
        'teams': 'Teams',
        'presentation': 'Presentation',
        'about': 'About',
        'settings': 'Settings',
        'welcome': 'Welcome, future chefs and historians!',
        'projectDescription': 'This guide was made especially for you and your team to embark on a delicious journey through time. With the project "Historical Recipe Book: Flavors and Knowledge", you will discover that History is also found on our table!',
        'letsGo': 'Let\'s learn History in the Kitchen!',
        'prepareNotebooks': 'Prepare your notebooks, colored pencils and lots of curiosity, because we will explore together the ingredients, preparation methods and culinary secrets of peoples and past eras.',
        'featuredRecipes': 'Featured Recipes',
        'exploreRecipes': 'Explore Recipes',
        'createNewRecipe': 'Create New Recipe',
        'researchTools': 'Research Tools',
        'myTeams': 'My Teams',
        'tasteAndFlavors': 'Flavors and Knowledge',
        'historicalRecipeBook': 'Historical Recipe Book',
        'adventure': 'embark on a delicious journey through time',
        'historyInTable': 'This recipe book will not be just a window to a set of instructions, but a window to understand how people lived, what they valued, and how they related to the world through food.',
        'whatIsHistoricalRecipe': 'What is a historical recipe book?',
        'cookingHistory': 'Imagine a book that tells stories through food. Not just common recipes: recipes that take us to other times and places. Each ingredient, each way of cooking, each detail, tells us something about the people who lived in that era.',
        'whyExploreCooking': 'Why explore ancient cooking?',
        'knowingPast': 'Getting to know the past in a more delicious way: Food is a very important part of people\'s lives. By discovering what they ate, we better understand how they lived, worked and celebrated.',
        'discoverIngredients': 'Discover different ingredients and flavors: Often, ancient peoples used ingredients that we may not know today, or prepared foods in surprising ways.',
        'valuingOurCulture': 'Valuing our food culture: The food we eat today has roots in the past. By knowing the old recipes, we can understand where some of our favorite dishes came from.',
        'learningNature': 'Learning about nature and agriculture: Ancient ingredients show us how people cultivated the land and made use of natural resources.',
        'thisProject': 'This project is like a historical investigation in the kitchen! You will be flavor detectives, looking for clues about the past in each recipe.',
        'searchPlaceholder': 'Search recipes...',
        'historicalPeriod': 'Historical Period',
        'culture': 'Culture',
        'difficulty': 'Difficulty',
        'allPeriods': 'All Periods',
        'allCultures': 'All Cultures',
        'easy': 'Easy',
        'medium': 'Medium',
        'hard': 'Hard',
        'recipeTitle': 'Recipe Title',
        'recipeDescription': 'Description',
        'recipeImage': 'Image',
        'originalIngredients': 'Original Ingredients',
        'adaptedIngredients': 'Adapted Ingredients',
        'preparationMethod': 'Preparation Method',
        'historicalInformation': 'Historical Information',
        'nutritionalInformation': 'Nutritional Information',
        'reflection': 'Reflection',
        'saveRecipe': 'Save Recipe',
        'cancel': 'Cancel',
        'addIngredient': 'Add Ingredient',
        'name': 'Name',
        'amount': 'Amount',
        'price': 'Price',
        'location': 'Location',
        'originalPreparation': 'Original (historical period)',
        'adaptedPreparation': 'Adapted (current)',
        'originAndContext': 'Origin and Social and Cultural Context',
        'importanceOfIngredients': 'Importance of Ingredients',
        'whoPrepared': 'Who Prepared?',
        'whoConsumed': 'Who Consumed?',
        'rituals': 'Rituals or Festivities?',
        'connectingPoints': 'Connecting the dots',
        'valuingPast': 'Valuing the past',
        'thinkingFuture': 'Thinking about the future',
        'principalNutrients': 'Principal Nutrients',
        'searchPrices': 'Search Prices',
        'calculatenutrition': 'Calculate Nutrients',
        'accessLibrary': 'Access Library',
        'createIllustration': 'Create Illustration',
        'viewGallery': 'View Gallery',
        'createTeam': 'Create Team',
        'joinTeam': 'Join Team',
        'teamName': 'Team Name',
        'teamMembers': 'Members',
        'teamRecipes': 'Recipes',
        'teamStatus': 'Status',
        'active': 'Active',
        'presentationGuide': 'Presentation Guide',
        'chooseRecipe': 'Choose a recipe: Decide which of the recipes you will prepare for the tasting',
        'prepareCarefully': 'Prepare carefully: Follow the preparation method and ask for help if needed',
        'setTable': 'Set the table: Make the tasting table beautiful and organized',
        'showBook': 'Show the book: Show the recipe book to the other teams and explain the history of the recipe',
        'offerTasting': 'Offer the tasting: Serve a small piece of the recipe for each person to try',
        'evaluationCriteria': 'Evaluation Criteria',
        'qualityResearch': 'Quality of culinary and historical research',
        'creativity': 'Creativity and presentation',
        'presentationQuality': 'Tasting presentation',
        'historicalConnection': 'Connection with history and culture',
        'evaluate': 'Evaluate',
        'comments': 'Comments',
        'projectAbout': 'About the Project',
        'projectObjectives': 'Project Objectives',
        'connectHistory': 'Connect History and cooking for a more engaging learning experience',
        'promoteTeamwork': 'Promote collaborative work among students',
        'developResearch': 'Develop historical research skills',
        'fosterCritical': 'Foster critical thinking about culture and food',
        'valueAncestral': 'Value ancestral culinary knowledge',
        'language': 'Language',
        'theme': 'Theme',
        'fontSize': 'Font Size',
        'notifications': 'Notifications',
        'privacy': 'Privacy',
        'profile': 'Profile',
        'appearance': 'Appearance',
        'dark': 'Dark',
        'light': 'Light',
        'auto': 'Automatic'
    }
};

// Estado da aplicação
let currentState = {
    language: 'pt',
    theme: 'light'
};

// Função para carregar o estado do localStorage
function loadState() {
    try {
        const savedState = localStorage.getItem('appState');
        if (savedState) {
            currentState = JSON.parse(savedState);
        }
    } catch (e) {
        console.warn('Erro ao carregar o estado:', e);
    }
}

// Função para salvar o estado no localStorage
function saveState() {
    try {
        localStorage.setItem('appState', JSON.stringify(currentState));
    } catch (e) {
        console.warn('Erro ao salvar o estado:', e);
    }
}

// Função para mudar o idioma
function changeLanguage(lang) {
    currentState.language = lang;
    saveState();
    updateUI();
    
    // Atualizar botões de idioma
    document.getElementById('portugueseBtn').classList.toggle('active', lang === 'pt');
    document.getElementById('englishBtn').classList.toggle('active', lang === 'en');
}

// Função para mudar o tema
function changeTheme(theme) {
    currentState.theme = theme;
    saveState();
    applyTheme();
}

// Função para aplicar o tema
function applyTheme() {
    const theme = currentState.theme;
    
    if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.body.classList.add('dark-theme');
        document.body.classList.remove('light-theme');
    } else {
        document.body.classList.add('light-theme');
        document.body.classList.remove('dark-theme');
    }
}

// Função para atualizar a interface com base no idioma
function updateUI() {
    const lang = currentState.language;
    const texts = translations[lang] || translations.pt;
    
    // Atualizar elementos com atributo data-i18n
    document.querySelectorAll('[data-i18n]').forEach(element => {
        const key = element.getAttribute('data-i18n');
        if (texts[key]) {
            element.textContent = texts[key];
        }
    });
    
    // Atualizar placeholders
    const searchInput = document.getElementById('searchRecipes');
    if (searchInput) {
        searchInput.placeholder = texts.searchPlaceholder || 'Buscar receitas...';
    }
    
    // Atualizar textos específicos
    updateSpecificTexts(texts);
}

// Função para atualizar textos específicos
function updateSpecificTexts(texts) {
    // Atualizar título da página
    document.title = texts.historicalRecipeBook + ': ' + texts.tasteAndFlavors;
    
    // Atualizar cabeçalho
    const headerTitle = document.querySelector('.header h1');
    if (headerTitle) {
        headerTitle.textContent = texts.tasteAndFlavors;
    }
    
    const headerSubtitle = document.querySelector('.header .lead');
    if (headerSubtitle) {
        headerSubtitle.textContent = texts.historicalRecipeBook;
    }
    
    const headerWelcome = document.querySelector('.header p:last-child');
    if (headerWelcome) {
        headerWelcome.textContent = texts.welcome;
    }
    
    // Atualizar seções da página inicial
    updateHomePageTexts(texts);
}

// Função para atualizar textos da página inicial
function updateHomePageTexts(texts) {
    // Seção de boas-vindas
    const homeTitle = document.querySelector('#home h2');
    if (homeTitle) {
        homeTitle.textContent = texts.letsGo;
    }
    
    const projectDescription = document.querySelector('#home p:first-of-type');
    if (projectDescription) {
        projectDescription.textContent = texts.projectDescription;
    }
    
    const prepareNotebooks = document.querySelector('#home p:nth-of-type(2)');
    if (prepareNotebooks) {
        prepareNotebooks.textContent = texts.prepareNotebooks;
    }
    
    // Seção de destaques
    const featuredTitle = document.querySelector('#home .card-title');
    if (featuredTitle) {
        featuredTitle.innerHTML = '<i class="fas fa-star text-warning"></i> ' + texts.featuredRecipes;
    }
    
    // Botões da página inicial
    updateHomeButtons(texts);
}

// Função para atualizar botões da página inicial
function updateHomeButtons(texts) {
    // Botão de explorar receitas
    const exploreBtn = document.querySelector('#home .btn:nth-child(1)');
    if (exploreBtn) {
        exploreBtn.innerHTML = '<i class="fas fa-book-open me-2"></i>' + texts.exploreRecipes;
    }
    
    // Botão de criar nova receita
    const createBtn = document.querySelector('#home .btn:nth-child(2)');
    if (createBtn) {
        createBtn.innerHTML = '<i class="fas fa-plus-circle me-2"></i>' + texts.createNewRecipe;
    }
    
    // Botão de ferramentas de pesquisa
    const toolsBtn = document.querySelector('#home .btn:nth-child(3)');
    if (toolsBtn) {
        toolsBtn.innerHTML = '<i class="fas fa-search me-2"></i>' + texts.researchTools;
    }
    
    // Botão de equipes
    const teamsBtn = document.querySelector('#home .btn:nth-child(4)');
    if (teamsBtn) {
        teamsBtn.innerHTML = '<i class="fas fa-users me-2"></i>' + texts.myTeams;
    }
}

// Função para inicializar a internacionalização
function initI18n() {
    loadState();
    updateUI();
    applyTheme();
    
    // Adicionar eventos aos botões de idioma
    document.getElementById('portugueseBtn').addEventListener('click', () => changeLanguage('pt'));
    document.getElementById('englishBtn').addEventListener('click', () => changeLanguage('en'));
    
    // Adicionar evento para mudança de tema do sistema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyTheme);
}

// Exportar funções
window.i18n = {
    changeLanguage,
    changeTheme,
    getCurrentLanguage: () => currentState.language,
    getCurrentTheme: () => currentState.theme
};

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', initI18n);