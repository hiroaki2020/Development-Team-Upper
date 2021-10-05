const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        zIndex: {
            '0': 0,
            '10': 10,
            '20': 20,
            '30': 30,
            '40': 40,
            '50': 50,
            '60': 60,
            '70': 70,
            '80': 80,
            '90': 90,
            '100': 100,
            'auto': 'auto',
        },
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            zIndex: {
                '-10': '-10',
                '-20': '-20',
                '-30': '-30',
            },
            backgroundImage: theme => ({
                'app-top': "url('../js/images/Top/app-top.jpg')",
                'app-top-swiper': "url('../js/images/Top/app-top-swiper.jpg')",
                'speech-balloon': "url('../js/images/Profile/speechballoon3.svg')",
            })
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
            animation: ['hover'],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        plugin(function({ addUtilities }) {
            const newUtilities = {
                '.h-lp-top': {
                    height: '116vh',
                },
                '.h-12vh': {
                    height: '12vh',
                },
            }
            addUtilities(newUtilities)
        })
    ],
};
