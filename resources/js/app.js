import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue'; // Ensure this is your root Vue component
import router from './router'; // Import the router
// Import Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css';

// Import Bootstrap JS
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const app = createApp(App);

app.use(router); // Register the router
app.mount('#app');
