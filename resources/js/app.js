import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue'; // Root Vue component
import router from './router'; // Import router
import store from './store/index'; // Import Vuex store

// Import Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css';

// Import Bootstrap JS
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const app = createApp(App);
app.use(store); // Register Vuex store
app.use(router); // Register the router
app.mount('#app'); // Mount the app
