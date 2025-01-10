<template>
  <div class="container-sm mx-auto">
    <h2 class="text-center mb-4">Login</h2>

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

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        email: '',
        password: ''
      },
      errors: {},
    };
  },
  methods: {
    async submitForm() {
      // Reset errors before sending the form
      this.errors = {};

      try {
        // Send login request to the Laravel API
        const response = await axios.post('/api/login', this.form);

        // Save token and user data in localStorage or Vuex store
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));

        // Handle success response
        console.log('Login successful', response.data);
        alert('Login successful!');
        // Redirect or do something after successful login
        this.$router.push('/dashboard'); // Example redirect to dashboard
      } catch (error) {
        // Handle error response
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors;
        } else {
          alert('Something went wrong! Please try again.');
        }
      }
    }
  }
};
</script>

<style scoped>
/* Bootstrap handles the layout, no additional custom styles needed */
</style>
