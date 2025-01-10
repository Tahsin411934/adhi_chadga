<template>
  <header class="shadow-sm bg-white">
    <div class="container-sm mx-auto d-flex justify-content-between align-items-center py-3">
      <!-- Logo Section -->
      <div class="d-flex align-items-center">
        <img src="logo.png" alt="Logo" class="img-fluid" style="height: 30px;" />
      </div>

      <!-- Navigation Links -->
      <nav class="d-flex align-items-center">
        <router-link
          to="/"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link"
        >
          Home
        </router-link>
        <router-link
          to="/services"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link"
        >
          Services
        </router-link>
        <router-link
          to="/work"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link"
        >
          Work
        </router-link>
        <router-link
          to="/price"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link"
        >
          Price
        </router-link>
        <router-link
          to="/register"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link"
        >
          Sign Up
        </router-link>
        <router-link
          to="/login"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link"
        >
          Sign In
        </router-link>

        <button @click="logout" class="btn btn-danger btn-sm ms-3">
          Logout
        </button>
      </nav>
    </div>
  </header>
</template>

<script>
import axios from "axios";

export default {
  name: "Navbar",
  methods: {
    async logout() {
      try {
        // Call the logout API
        await axios.post(
          "http://127.0.0.1:8000/api/logout",
          {},
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        // Clear token from localStorage
        localStorage.removeItem("token");

        // Show a success alert
        alert("You have been logged out successfully!");

        // Redirect user to the login page
        this.$router.push("/login");
      } catch (error) {
        // Show an alert if logout fails
        alert("Logout failed! Please try again.");
        console.error("Logout failed:", error.response?.data?.message || error.message);
      }
    },
  },
};
</script>

<style scoped>
.hover-link:hover {
  color: #0056b3; /* Bootstrap primary color for hover effect */
  text-decoration: underline; /* Optional hover underline */
  transition: color 0.3s ease;
}
</style>
