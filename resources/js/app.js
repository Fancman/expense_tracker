import Alpine from 'alpinejs'
import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'

Alpine.plugin(FormsAlpinePlugin)

require('./bootstrap');
 
window.Alpine = Alpine
 
Alpine.start()