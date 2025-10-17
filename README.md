# Livro de Receitas Históricas: Sabores e Sabores

## Descrição

O "Livro de Receitas Históricas: Sabores e Sabores" é uma aplicação web educacional desenvolvida para auxiliar no ensino de História do 6º ao 9º ano do Ensino Fundamental nas Escolas Municipais de Campo Grande. Através da culinária histórica, os alunos exploram diferentes períodos e culturas, conectando o passado com o presente de forma prática e saborosa.

## Funcionalidades

### Recursos Principais

- **Navegação por Receitas Históricas**: Explore receitas de diferentes períodos históricos e culturas
- **Criação de Receitas**: Crie e documente suas próprias receitas históricas com informações completas
- **Ferramentas de Pesquisa**: 
  - Pesquisa de preços de ingredientes
  - Calculadora nutricional
  - Biblioteca histórica
  - Ferramentas de desenho e ilustração
- **Trabalho em Equipe**: Colabore com colegas na criação de receitas e projetos
- **Apresentação de Degustações**: Prepare e apresente suas receitas históricas
- **Sistema de Configurações**: Personalize o aplicativo conforme suas preferências

### Internacionalização

- Suporte completo para Português (Brasil) e Inglês (US)
- Troca de idioma em tempo real
- Persistência de preferências de idioma

### Temas

- Tema Claro (padrão)
- Tema Escuro
- Detecção automática do tema do sistema operacional

## Tecnologias Utilizadas

- HTML5
- CSS3 (com variáveis para temas)
- JavaScript (Vanilla)
- Bootstrap 5
- FontAwesome 6.4.0
- localStorage para persistência de dados

## Estrutura do Projeto

```
website/
├── index.html              # Página inicial
├── recipe-details.html     # Detalhes da receita
├── create-recipe.html      # Criação de nova receita
├── tools.html              # Ferramentas de pesquisa
├── teams.html              # Trabalho em equipe
├── presentation.html       # Apresentação e degustação
├── settings.html           # Configurações
├── css/
│   └── themes.css          # Estilos para temas claro/escuro
└── js/
    └── i18n.js             # Sistema de internacionalização
```

## Como Executar

1. Clone o repositório:
   ```
   git clone https://github.com/lfgranja/livrodereceitas.git
   ```

2. Navegue até o diretório do projeto:
   ```
   cd livrodereceitas/website
   ```

3. Inicie um servidor HTTP simples:
   ```
   python3 -m http.server 8082
   ```

4. Acesse o aplicativo em:
   ```
   http://localhost:8082
   ```

## Recursos Educacionais

O projeto promove a interdisciplinaridade entre História, Ciências, Artes e Língua Portuguesa, permitindo que os alunos:

- Desenvolvam habilidades de pesquisa e leitura
- Compreendam a história como uma construção cultural
- Estabeleçam relações entre passado e presente
- Valorizem a diversidade cultural
- Apliquem conhecimentos de nutrição e ciências
- Desenvolvam criatividade e expressão artística

## Contribuição

Contribuições são bem-vindas! Para contribuir:

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## Licença

Este projeto é de uso educacional e seu código pode ser utilizado e adaptado para fins pedagógicos, mantendo-se os devidos créditos ao autor original.

## Autor

Prof. Luís Felipe Mesquita Granja - Professor de História e desenvolvedor educacional

## Contato

Para dúvidas sobre o uso pedagógico:
prof.lfelipemesquitagranja@gmail.com