/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        container: {
            center: true,

            screens: {
                "4xl": "1920px",
            },

            padding: {
                DEFAULT: "16px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1440px",
            "3xl": "1680px",
            "4xl": "1920px",
        },

        extend: {
            colors: {
                brandColor: "var(--brand-color)",
            },

            fontFamily: {
                inter: ['Inter'],
                icon: ['icomoon']
            },

            keyframes: {
                'kpi-card-glow': {
                    '0%, 100%': {
                        boxShadow: '0 0 0 1px rgb(var(--kpi-accent) / 0.22), 0 0 0 0 rgb(var(--kpi-accent) / 0)',
                    },
                    '50%': {
                        boxShadow: '0 0 0 1px rgb(var(--kpi-accent) / 0.55), 0 0 16px 2px rgb(var(--kpi-accent) / 0.30)',
                    },
                },
                'kpi-icon-pulse': {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.08)' },
                },
                'kpi-accent-bar': {
                    '0%, 100%': { opacity: '0.5', transform: 'scaleX(0.94)' },
                    '50%': { opacity: '1', transform: 'scaleX(1)' },
                },
                'kpi-ring-ping': {
                    '0%': { boxShadow: '0 0 0 0 rgb(var(--kpi-accent) / 0.45)' },
                    '100%': { boxShadow: '0 0 0 14px rgb(var(--kpi-accent) / 0)' },
                },
            },

            animation: {
                'kpi-card-glow': 'kpi-card-glow 2.6s ease-in-out infinite',
                'kpi-icon-pulse': 'kpi-icon-pulse 3.2s ease-in-out infinite',
                'kpi-accent-bar': 'kpi-accent-bar 2.6s ease-in-out infinite',
                'kpi-ring-ping': 'kpi-ring-ping 900ms ease-out 1',
            },
        },
    },
    
    darkMode: 'class',
    
    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        }
    ]
};