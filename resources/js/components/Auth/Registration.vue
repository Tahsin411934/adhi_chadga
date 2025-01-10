<template>
  <div class="container-sm mx-auto">
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

      <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      errors: {}
    };
  },
  methods: {
    async submitForm() {
      // Reset errors before sending the form
      this.errors = {};

      try {
        const response = await axios.post('/api/register', this.form);

        // Handle success response
        console.log(response.data);
        alert('Registration successful!');
      } catch (error) {
        // Handle error response and display validation errors
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
/* No custom styles required since Bootstrap is handling the layout */
</style>
