import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import axios from 'axios'
import VueAxios from 'vue-axios'

// Vuetify
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
  },
})
axios.defaults.baseURL = 'http://localhost/tech-exam/controllers';
createApp(App)
.use(vuetify)
.use(VueAxios, axios)
.mount('#app')
