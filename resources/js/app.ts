import './bootstrap';

import {createApp} from 'vue';
import App from './src/App.vue';
import router from './src/router/index';
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css'
import Error from './src/components/Error.vue';
import BaseInput from './src/components/BaseInput.vue';
import BaseBtn from './src/components/BaseBtn.vue';
import { createPinia } from 'pinia'
import VueApexCharts from 'vue3-apexcharts'

createApp(App)
.use(router)
.use(createPinia())
.use(ToastPlugin)
.use(VueApexCharts)
.component('Error', Error)
.component('BaseInput', BaseInput)
.component('BaseBtn', BaseBtn)
.mount("#app");