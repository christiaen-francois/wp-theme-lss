/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./inc/**/*.php",
    "./parts/**/*.php",
    "./templates/**/*.php",
    "./blocks/**/*.php",
    "./assets/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        // Palette inspirée du logo Lion Select Safaris
        primary: {
          50: "#fef7ed",
          100: "#fdedd3",
          200: "#fbd9a5",
          300: "#f8c06d",
          400: "#f5a033", // Doré/orange principal (lion)
          500: "#d68910",
          600: "#b8730e",
          700: "#965a0c",
          800: "#7a480a",
          900: "#643c08",
          950: "#3a2204",
        },
        // Marron (fond du logo)
        brown: {
          50: "#faf8f5",
          100: "#f5f0e8",
          200: "#eadfd1",
          300: "#dcc8b3",
          400: "#caab8f",
          500: "#b8916f", // Marron moyen
          600: "#a67c5a",
          700: "#8b6549",
          800: "#72543e",
          900: "#5d4533",
          950: "#3d2d20", // Marron foncé (fond logo)
        },
        // Crème (fond principal)
        cream: {
          50: "#efe8e4", // Crème principal (couleur de fond)
          100: "#fdfaf5",
          200: "#faf5e8",
          300: "#f6eed6",
          400: "#f0e2be",
          500: "#e8d4a3",
          600: "#d4ba7f",
          700: "#b89a5f",
          800: "#9a7d4f",
          900: "#7d6542",
          950: "#423420",
        },
        // Neutres (fond sombre comme polar.sh)
        neutral: {
          50: "#fafafa",
          100: "#f5f5f5",
          200: "#e5e5e5",
          300: "#d4d4d4",
          400: "#a3a3a3",
          500: "#737373",
          600: "#525252",
          700: "#404040",
          800: "#262626",
          900: "#171717",
          950: "#0a0a0a", // Fond principal très sombre
        },
        // Accents et états
        success: {
          50: "#f0fdf4",
          100: "#dcfce7",
          500: "#22c55e",
          600: "#16a34a",
        },
        warning: {
          50: "#fffbeb",
          100: "#fef3c7",
          500: "#f59e0b",
          600: "#d97706",
        },
        error: {
          50: "#fef2f2",
          100: "#fee2e2",
          500: "#ef4444",
          600: "#dc2626",
        },
      },
      fontFamily: {
        heading: [
          "Cinzel",
          "Georgia",
          "Times New Roman",
          "serif",
        ],
        sans: [
          "Geist",
          "Geist Variable",
          "-apple-system",
          "BlinkMacSystemFont",
          "Segoe UI",
          "Roboto",
          "sans-serif",
        ],
        mono: [
          "Geist Mono",
          "Geist Mono Variable",
          "ui-monospace",
          "SFMono-Regular",
          "Menlo",
          "Monaco",
          "Consolas",
          "monospace",
        ],
      },
      fontSize: {
        xs: ["0.75rem", { lineHeight: "1rem" }], // 12px / 16px
        sm: ["0.875rem", { lineHeight: "1.25rem" }], // 14px / 20px
        base: ["1rem", { lineHeight: "1.5rem" }], // 16px / 24px
        lg: ["1.125rem", { lineHeight: "1.75rem" }], // 18px / 28px
        xl: ["1.25rem", { lineHeight: "1.75rem" }], // 20px / 28px
        "2xl": ["1.5rem", { lineHeight: "2rem" }], // 24px / 32px
        "3xl": ["1.875rem", { lineHeight: "2.25rem" }], // 30px / 36px
        "4xl": ["2.25rem", { lineHeight: "2.5rem" }], // 36px / 40px
        "5xl": ["3rem", { lineHeight: "1" }], // 48px / 48px
        "6xl": ["3.75rem", { lineHeight: "1" }], // 60px / 60px
        "7xl": ["4.5rem", { lineHeight: "1" }], // 72px / 72px
        "8xl": ["6rem", { lineHeight: "1" }], // 96px / 96px
        "9xl": ["8rem", { lineHeight: "1" }], // 128px / 128px
      },
      spacing: {
        18: "4.5rem", // 72px
        88: "22rem", // 352px
      },
      animation: {
        "fade-in": "fadeIn 0.6s ease-out",
        "slide-up": "slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1)",
        "slide-down": "slideDown 0.3s ease-out",
      },
      keyframes: {
        fadeIn: {
          "0%": { opacity: "0" },
          "100%": { opacity: "1" },
        },
        slideUp: {
          "0%": { opacity: "0", transform: "translateY(20px)" },
          "100%": { opacity: "1", transform: "translateY(0)" },
        },
        slideDown: {
          "0%": { opacity: "0", transform: "translateY(-10px)" },
          "100%": { opacity: "1", transform: "translateY(0)" },
        },
      },
    },
  },
  plugins: [require("@tailwindcss/typography"), require("@tailwindcss/forms")],
};
