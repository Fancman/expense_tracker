const colors = require('tailwindcss/colors')

module.exports = {
  content: [
	"./resources/**/*.blade.php",
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
	},
    extend: {},
  },
  plugins: [],
}
