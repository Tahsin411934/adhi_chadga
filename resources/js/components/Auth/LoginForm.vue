<template>
  <div class="container-sm mx-auto shadow-lg p-5 mt-5 mb-5" id="responsive-container">
    <h2 class="text-center mb-4 text-custom">Login to your Account</h2>

    <!-- Login Form -->
    <form @submit.prevent="submitForm">
      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          id="email"
          class="form-control"
          v-model="form.email"
          :class="{'is-invalid': errors.email}"
          required
        />
        <div v-if="errors.email" class="invalid-feedback">{{ errors.email }}</div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          id="password"
          class="form-control"
          v-model="form.password"
          :class="{'is-invalid': errors.password}"
          required
        />
        <div v-if="errors.password" class="invalid-feedback">{{ errors.password }}</div>
      </div>

      <!-- Login Button with Spinner -->
      <button 
        type="submit" 
        class="btn bg-custom text-white w-100" 
        :disabled="loading"
      >
        <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        <span v-else>Login</span>
      </button>
    </form>

    <!-- Google Login -->
    <button 
      class="btn mt-4 w-100 d-flex align-items-center justify-content-center bg-white border border-dark h-10 px-6 rounded shadow-lg transition duration-300"
    >
      <img src="/google.png" width="50" alt="Google logo" class="w-5 h-5 mr-2" />
      <span class="text-dark">Login with Google</span>
    </button>
  </div>
</template>

<script>
import axios from 'axios';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css'; // Import the CSS for Toastify

export default {
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      errors: {},
      loading: false, // Manage the spinner state
    };
  },
  methods: {
    async submitForm() {
      this.errors = {};
      this.loading = true; // Show spinner

      try {
        // Send login request to the Laravel API
        const response = await axios.post('/api/login', this.form);

        // Save token and user data in localStorage or Vuex store
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));

        // Show Toastify success message
        Toastify({
          text: "Login successful!",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "green",
        }).showToast();

        // Redirect after successful login
        this.$router.push('/');
      } catch (error) {
        // Handle error response
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert('Something went wrong! Please try again.');
        }

        // Show Toastify error message
        Toastify({
          text: "Login failed! Please check your credentials.",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "red",
        }).showToast();
      } finally {
        this.loading = false; // Hide spinner
      }
    }
  }
};
</script>

<style scoped>
.bg-custom {
  background: #050748;
}

.text-custom {
  color: #050748;
}

.spinner-border {
  border-width: 2px;
  border-color: white transparent white transparent;
}

#responsive-container {
  width: 100%; /* Default for smaller screens */
}

@media (min-width: 768px) {
  /* For medium devices and up (tablets and larger) */
  #responsive-container {
    width: 60%;
  }
}

@media (min-width: 992px) { /* For larger devices */
  #responsive-container {
    width: 40%;
  }
}

@media (max-width: 767px) { 
  /* Ensure full width on small devices (portrait phones) */
  #responsive-container {
    width: 100%;
  }
}

/* Add some padding and margins for small devices */
@media (max-width: 576px) {
  #responsive-container {
    padding: 10px; /* Smaller padding on very small devices */
  }
}
</style>