const colors = require('tailwindcss/colors')
const forms = require('@tailwindcss/forms');
const typography =require('@tailwindcss/typography'); 

module.exports = {
	darkMode: 'class',
	content: [
		"./resources/**/*.blade.php",
		'./vendor/filament/**/*.blade.php', 
		"./resources/**/*.js",
	],
	theme: {
		colors: {
			'light-blue': '#42b3e52b',
			'navy-blue': '#4551e5',
			'red': '#EA4335',
			'blue': '#1D4ED8',
			'light-blue': '#0075DD',
			'white': '#ffff',
			'transparent': 'transparent',
			'current': 'currentColor',
			'black': colors.black,
			'white': colors.white,
			'gray': colors.slate,
			'green': colors.emerald,
			'purple': '#6366F1',
			'yellow': colors.amber,
			'pink': colors.fuchsia,
			'danger': colors.rose,
            'primary': colors.blue,
			'light-black': '#272B2E',
			'cod-grey': '#191919',
		},
		extend: {},
	},
	plugins: [
		forms, 
        typography,
	],
}
