import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import { library, dom } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import VueGoodTablePlugin from 'vue-good-table-next';
import 'vue-good-table-next/dist/vue-good-table-next.css';

library.add(fas, far, fab)
dom.watch();

import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css'

axios.defaults.baseURL = 'http://localhost:8000/api/';
axios.defaults.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
axios.defaults.headers.common['Access-Control-Allow-Origin'] = `*`;

createApp(App)
    .component("font-awesome-icon", FontAwesomeIcon)
    .use(router)
    .use(VueGoodTablePlugin)
    .mount('#app')
