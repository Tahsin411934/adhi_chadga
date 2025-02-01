<template>
  <div class="container-sm mx-auto shadow-lg p-5 mt-2" id="responsive-container">
    <h2 class="text-center mb-4">Create an Account</h2>

    <!-- Registration Form -->
    <form @submit.prevent="submitForm">
      <!-- Name -->
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input
          type="text"
          id="name"
          class="form-control"
          v-model="form.name"
          :class="{'is-invalid': errors.name}"
          required
        />
        <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
      </div>

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

      <!-- Confirm Password -->
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input
          type="password"
          id="password_confirmation"
          class="form-control"
          v-model="form.password_confirmation"
          :class="{'is-invalid': errors.password_confirmation}"
          required
        />
        <div v-if="errors.password_confirmation" class="invalid-feedback">
          {{ errors.password_confirmation }}
        </div>
      </div>

      <!-- Submit Button -->
      <button style="background: #050748;" type="submit" class="btn  w-100 text-white" :disabled="loading">
        <span v-if="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        <span  v-else>Register</span>
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';
export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      errors: {},
      loading: false // Manage the loading state
    };
  },
  methods: {
    async submitForm() {
      this.errors = {};  // Reset errors
      this.loading = true; // Show loading spinner

      try {
        const response = await axios.post('/api/register', this.form);

        // Handle success response
        
        
        Toastify({
          text: "Registration successful! Please Login with Credential ",
          duration: 3000,
          gravity: "top",
          position: "right",
          backgroundColor: "green",
        }).showToast();
        this.$router.push('/login');
      } catch (error) {
        // Handle error response and display validation errors
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert('Something went wrong! Please try again.');
        }
      } finally {
        this.loading = false; // Hide loading spinner after submission
      }
    }
  }
};
</script>

<style scoped>
#responsive-container {
  width: 100%; /* Default for smaller screens */
}

@media (min-width: 768px) {
  /* For medium devices and up (tablets and larger) */
  #responsive-container {
    width: 70%;
  }
}

@media (min-width: 992px) { /* For larger devices */
  #responsive-container {
    width: 50%;
  }
}

/* Optional: Adjust form padding for smaller screens */
@media (max-width: 576px) {
  #responsive-container {
    padding: 15px; /* Reduce padding for very small screens */
  }
}

.spinner-border {
  border-width: 2px;
  border-color: white transparent white transparent;
}
</style>