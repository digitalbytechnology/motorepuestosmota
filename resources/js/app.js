import './bootstrap'

// jQuery global (para DataTables / plugins)
import $ from 'jquery'
window.$ = window.jQuery = $

import 'bootstrap'
import 'admin-lte/dist/js/adminlte.min.js'

// módulos por vista (solo corren si existe el elemento en el DOM)
import './dashboard'
import './appointments'

// datatables por módulo
import './datatables-users'
import './datatables-clients'
import './datatables-vehicles'
