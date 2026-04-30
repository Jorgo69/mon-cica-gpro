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
        /* -- Brand -- */
        primary: {
          light: '#334155',
          DEFAULT: 'rgb(var(--primary) / <alpha-value>)',
          dark: 'rgb(var(--primary-dark) / <alpha-value>)',
        },
        accent: {
          light: 'rgb(var(--accent-dark) / <alpha-value>)',
          DEFAULT: 'rgb(var(--accent) / <alpha-value>)',
          dark: 'rgb(var(--accent-dark) / <alpha-value>)',
        },

        /* -- Surfaces -- */
        surface: {
          DEFAULT: 'rgb(var(--surface) / <alpha-value>)',
          alt: 'rgb(var(--surface-alt) / <alpha-value>)',
        },
        card: {
          DEFAULT: 'rgb(var(--card) / <alpha-value>)',
        },

        /* -- Text -- */
        heading: 'rgb(var(--heading) / <alpha-value>)',
        body: 'rgb(var(--body) / <alpha-value>)',
        subtle: 'rgb(var(--subtle) / <alpha-value>)',
        muted: 'rgb(var(--muted) / <alpha-value>)',

        /* -- Borders -- */
        border: {
          DEFAULT: 'rgb(var(--border) / <alpha-value>)',
          light: 'rgb(var(--border-light) / <alpha-value>)',
        },

        /* -- Status -- */
        success: {
          DEFAULT: 'rgb(var(--success) / <alpha-value>)',
          dark: 'rgb(var(--success-dark) / <alpha-value>)',
        },
        error: {
          DEFAULT: 'rgb(var(--error) / <alpha-value>)',
          dark: 'rgb(var(--error-dark) / <alpha-value>)',
        },
        warning: {
          DEFAULT: 'rgb(var(--warning) / <alpha-value>)',
          dark: 'rgb(var(--warning-dark) / <alpha-value>)',
        },
        info: {
          DEFAULT: 'rgb(var(--info) / <alpha-value>)',
          dark: 'rgb(var(--info-dark) / <alpha-value>)',
        },
      },
    },
  },
  plugins: [forms], // J'ai remis 'forms' ici car il est souvent utile avec Breeze
}