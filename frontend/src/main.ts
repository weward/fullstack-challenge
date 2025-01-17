import { createApp } from "vue";
import { createPinia } from "pinia";
import PrimeVue from 'primevue/config';
import App from "./App.vue";
import router from "./router";

import 'primevue/resources/primevue.min.css'            
import 'primevue/resources/themes/vela-green/theme.css' // theme
import 'primeicons/primeicons.css'                      
import "./assets/main.css";


const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(PrimeVue);

app.mount("#app");
