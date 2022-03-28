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
			'blue': '#1D4ED8',
			'white': '#ffff',
			'transparent': 'transparent',
			'current': 'currentColor',
			'black': colors.black,
			'white': colors.white,
			'gray': colors.slate,
			'green': colors.emerald,
			'purple': colors.violet,
			'yellow': colors.amber,
			'pink': colors.fuchsia,
			'danger': colors.rose,
            'primary': colors.blue,
		},
		extend: {},
	},
	plugins: [
		forms, 
        typography,
	],
}
