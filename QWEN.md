# Qwen Code Context File

## Project Overview

The "Livro de Receitas Históricas: Sabores e Sabores" (Historical Recipes Book: Knowledges and Flavors) is an educational mobile application developed by Prof. Luís Felipe Mesquita Granja to assist in teaching History to 6th to 9th grade students in Municipal Schools of Campo Grande, Brazil. The application uses historical culinary practices to connect students with different time periods and cultures, making history learning more practical and engaging.

## Project Structure

```
/home/lfgranja/development/livrodereceitas/
├── historical-recipes-app/           # Main project directory
│   └── HistoricalRecipesApp/         # React Native mobile app
│       ├── src/
│       │   ├── components/
│       │   ├── screens/
│       │   │   └── presentation/
│       │   ├── i18n/
│       │   └── utils/
│       ├── website/                  # Web version of the application
│       ├── App.js
│       ├── App.tsx
│       ├── package.json
│       └── README.md
├── HistoricalRecipesApp/             # Duplicate directory (likely staging)
├── Livreto de apoio - Livro de Receitas Históricas - Saberes e Sabores.pdf  # Educational documentation
├── livreto_text.txt                  # Text version of educational content
└── logs/
```

## Application Details

### Mobile App (React Native)
- **Framework**: React Native with TypeScript
- **Navigation**: React Navigation (Drawer and Stack navigators)
- **Backend**: Firebase (Authentication, Firestore, Storage)
- **Internationalization**: i18next with Portuguese (Brazil) and English support
- **State Management**: React hooks and AsyncStorage for local storage
- **Additional Libraries**: 
  - React Native Vector Icons
  - React Native Image Picker
  - React Native Sketch (for drawing tools)
  - React Native SQLite Storage

### Web Version
- **Technologies**: HTML5, CSS3, JavaScript (Vanilla)
- **Framework**: Bootstrap 5 for responsive design
- **Icons**: FontAwesome 6.4.0
- **Features**: Complete web-based version of the application with localStorage for data persistence

## Key Features

1. **Recipe Browser**: Navigate historical recipes by period, culture, and difficulty
2. **Recipe Creation**: Document historical recipes with complete information
3. **Research Tools**: Price search, nutritional calculator, and historical library
4. **Team Collaboration**: Work in teams on recipes and projects
5. **Drawing Tools**: Illustrate recipes with digital drawing capabilities
6. **Presentations**: Prepare and evaluate historical tasting presentations
7. **Internationalization**: Full support for Portuguese (Brazil) and English
8. **Theme Support**: Light and dark themes with automatic OS detection

## Technical Architecture

The React Native application uses a clean, well-organized architecture:
- **Components**: Reusable UI components
- **Screens**: Different views of the application
- **Utils**: Utility functions including Firebase configuration, nutritional calculator, and recipe service
- **i18n**: Internationalization files for multiple languages

### Navigation Structure
- Home Screen
- Recipe Browser Screen
- Recipe Detail Screen
- Recipe Creation Screen
- Team Screen
- Research Tools Screen
- Presentation Screen
- Drawing Gallery Screen
- Settings Screen

## Educational Context

This project is designed for the Brazilian educational context, specifically targeting 6th to 9th grade students in Campo Grande. The pedagogical approach connects History with culinary practices, allowing students to explore different historical periods and cultures through food. Students are expected to research, document, and potentially prepare historical recipes while learning about the cultural and historical context of these dishes.

## Building and Running

### Prerequisites
- Node.js (v18 or superior)
- npm or yarn
- Android Studio or Xcode (for simulators)
- React Native CLI

### Installation
```bash
# Navigate to the app directory
cd /home/lfgranja/development/livrodereceitas/historical-recipes-app/HistoricalRecipesApp

# Install dependencies
npm install

# Configure Firebase (add your own configuration)
# Create src/utils/firebaseConfig.js with your Firebase settings
```

### Running the Application
```bash
# For Android
npx react-native run-android

# For iOS
npx react-native run-ios

# Using package scripts
npm run android
npm run ios
npm run web  # For Expo web version
npm run start  # Start Expo development server
```

### Web Version
The web version can be served with a simple HTTP server:
```bash
cd /home/lfgranja/development/livrodereceitas/historical-recipes-app/website
python3 -m http.server 8082
```

## Development Conventions

### Code Style
- TypeScript is used for type safety
- ESLint and Prettier are configured for consistent code formatting
- React best practices are followed
- Component-based architecture is used

### Internationalization
- The app supports Portuguese (Brazil) and English
- Text is stored in JSON files in the `src/i18n/` directory
- Language preference is stored in AsyncStorage
- Automatic detection of device language with Portuguese as fallback

### Testing
- Testing scenarios are documented in `src/utils/testing-scenarios.md`
- Jest is configured for unit testing
- Manual testing scenarios cover the complete user journey

## Documentation

### Educational Guide
A comprehensive teacher-student guide is available in `src/utils/teacher-student-guide.md` that includes:
- Project objectives and pedagogical approach
- Step-by-step instructions for students
- Evaluation criteria and assessment guidelines
- Tips for historical research and recipe adaptation

### Support Files
- `livreto_text.txt`: Detailed text version of the educational content
- PDF documentation with project guidelines and methodology

## Key Files and Directories

- `App.tsx` / `App.js`: Main application component with navigation setup
- `/src/screens/`: Contains all screen components
- `/src/components/`: Reusable UI components
- `/src/utils/`: Utility functions, configuration files, and guides
- `/src/i18n/`: Internationalization files (pt.json, en.json)
- `package.json`: Project dependencies and scripts
- `README.md`: Project overview and setup instructions

## Special Features

### Drawing and Illustration Tools
The application includes tools for students to create digital drawings to accompany their historical recipes, enhancing the creative and educational experience.

### Research Tools
Integrated tools for researching ingredient prices and calculating nutritional information, connecting historical recipes with modern nutritional knowledge.

### Team Collaboration
Students can form teams to collaborate on recipe creation and research, promoting teamwork and shared learning experiences.

## Project Goals

This project aims to transform History learning into a sensory and collaborative experience where students research, document, and potentially prepare historical recipes, understanding how food reflects culture and living conditions of different peoples and time periods.

## Contributing

The project is designed to meet the specific educational needs of the "Sabores e Sabores" project in Campo Grande municipal schools. Contributions are welcome, particularly those that consider the Brazilian educational context and current curriculum guidelines.