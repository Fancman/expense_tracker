import FormsAlpinePlugin from '../../vendor/filament/forms/dist/module.esm'
//import DateTimePickerFormComponentAlpinePlugin from '../../vendor/filament/forms/resources/js/components/date-time-picker'
import Alpine from 'alpinejs'

import ApexCharts from 'apexcharts'

Alpine.plugin(FormsAlpinePlugin)
//Alpine.plugin(DateTimePickerFormComponentAlpinePlugin)

require('./bootstrap');
 
window.Alpine = Alpine
window.ApexCharts = ApexCharts
window.Pikaday = require("../../node_modules/pikaday");
 
Alpine.start()
