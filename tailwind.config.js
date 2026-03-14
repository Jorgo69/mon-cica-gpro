import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms'; // Assurez-vous d'avoir installé @tailwindcss/forms si vous l'utilisez

/** @type {import('tailwindcss').Config} */
export default {
  // Active le mode sombre basé sur la classe 'dark' sur l'élément HTML
  darkMode: 'class',
  important: true,

  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/**/*.{blade.php,js,html}',
  ],

  theme: {
    extend: {
      // Définition des polices
      fontFamily: {
        // 'sans' utilisera Inter, Poppins, Roboto ou la police par défaut du système
        sans: ['Inter', 'Poppins', 'Roboto', ...defaultTheme.fontFamily.sans], // J'ai ajouté defaultTheme.fontFamily.sans comme dernière option
      },
      // Palette de couleurs personnalisée pour une interface moderne
      colors: {
        primary: {
          light: '#334155', // Slate 700
          DEFAULT: 'rgb(var(--primary) / <alpha-value>)',
          dark: 'rgb(var(--primary-dark) / <alpha-value>)',
        },
        accent: {
          light: 'rgb(var(--accent-dark) / <alpha-value>)',
          DEFAULT: 'rgb(var(--accent) / <alpha-value>)',
          dark: 'rgb(var(--accent-dark) / <alpha-value>)',
        },
        secondary: {
          light: '#94A3B8', // Slate 400
          DEFAULT: '#64748B', // Slate 500
          dark: '#475569', // Slate 600
        },
        // Couleurs de fond pour les modes clair et sombre
        background: {
          light: '#F9FAFB', // Gray 50
          dark: '#1F2937',  // Gray 800 (Note: assurez-vous que cette couleur contraste bien avec le texte dark)
        },
        // Couleurs de texte pour les modes clair et sombre
        text: {
          light: '#111827', // Gray 900
          dark: '#F9FAFB',  // Gray 50
        },
        // Couleurs pour les cartes et conteneurs
        card: {
          light: '#FFFFFF', // White
          dark: '#374151',  // Gray 700
        },
        // Couleurs des bordures
        border: {
          light: '#E5E7EB', // Gray 200
          dark: '#4B5563',  // Gray 600
        },
        // Couleurs de succès, erreur, avertissement, info
        success: {
          DEFAULT: 'rgb(var(--success) / <alpha-value>)',
          dark: 'rgb(var(--success-dark) / <alpha-value>)',
        },
        error: {
          DEFAULT: 'rgb(var(--error) / <alpha-value>)',
          dark: 'rgb(var(--error-dark) / <alpha-value>)',
        },
        warning: {
          DEFAULT: '#F59E0B', // Amber 500
          dark: '#D97706', // Amber 600
        },
        info: {
          DEFAULT: '#3B82F6', // Blue 500
          dark: '#2563EB', // Blue 600
        }
      },
    },
  },
  plugins: [forms], // J'ai remis 'forms' ici car il est souvent utile avec Breeze
}