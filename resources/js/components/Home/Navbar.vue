<template>
  <header class="shadow-sm bg-white ">
    <div class="container-sm mx-auto d-flex justify-content-between align-items-center ">
      <!-- Logo Section -->
      <div class="d-flex align-items-center justify-content-center">
        <img src="/logo.png" alt="" height="100">
        <span class="logo mt-3" style="color: #d97706;">
          আদি  <span >চাঁটগা</span>
        </span>
      </div>

      <!-- Desktop Navigation Links -->
      <nav class="d-none d-md-flex align-items-center">
        <router-link
          to="/"
          class="fs-6 text-custom text-decoration-none mx-3 fw-medium hover-link"
        >
          Home
        </router-link>
        <router-link
          to="/overview"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link fs-6 text-custom"
        >
          Menu
        </router-link>
        <router-link
          to="/premiumcv"
          class="fs-6 text-custom text-decoration-none mx-3 fw-medium hover-link"
        >
          Our Services
        </router-link>
        <router-link
          to="/services"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link fs-6 text-custom"
        >
          About Us
        </router-link>

        <router-link
          to="/contact"
          class="text-dark text-decoration-none mx-3 fw-medium hover-link fs-6 text-custom"
        >
          Blog
        </router-link>
        

        <!-- Conditional Render: Show Create Account button or Logout button -->
        <div v-if="!isLoggedIn">
          <button class="btn  bg-hover text-white mx-3" @click="toggleDropdown" style="background-color: #d97706;">
            Create Account
          </button>
          <!-- Dropdown for Register and Login -->
          <div v-if="showDropdown" class="dropdown-menu show">
            <router-link to="/register" class="dropdown-item">Register</router-link>
            <router-link to="/login" class="dropdown-item">Login</router-link>
            <router-link to="/dashboard" class="dropdown-item">Dashboard</router-link>
          </div>
        </div>
        
        <div v-else>
          <!-- Profile Dropdown for Logged-in Users -->
          <div class="dropdown d-inline-block" ref="profileDropdown">
            <button
              class="btn bg-custom bg-hover text-white ms-3"
              @click="toggleProfileDropdown"
            >
              <i class="fas fa-user-circle"></i>
            </button>
            <div v-if="showProfileDropdown" class="dropdown-menu show">
              <router-link to="/profile" class="dropdown-item">Profile</router-link>
              <router-link to="/orders" class="dropdown-item">Orders</router-link>
              <router-link to="/creation" class="dropdown-item">Creation</router-link>
              <button @click="logout" class="dropdown-item">Logout</button>
            </div>
          </div>
        </div>
        <div class="">
          <Cart/>
        </div>
      </nav>
      <div class="d-flex d-lg-none">
        <Cart/>
      </div>
      
      <!-- Hamburger Button (Visible on Mobile) -->
      <button class="d-md-none btn btn-link" @click="toggleModal">
        <i class="fas fa-bars"></i>
      </button>
    </div>

    <!-- Modal for Mobile Navigation -->
    <div v-if="showModal" class="modal fade show" tabindex="-1" style="display: block;" aria-hidden="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Navigation</h5>
            <button type="button" class="btn-close" @click="toggleModal">X</button>
          </div>
          <div class="modal-body">
            <router-link to="/" class="d-block py-2 text-custom" @click="toggleModal">Home</router-link>
            <router-link to="/services" class="d-block py-2 text-custom" @click="toggleModal">Services</router-link>
            <router-link to="/work" class="d-block py-2 text-custom" @click="toggleModal">Work</router-link>
            <router-link to="/contact" class="d-block py-2 text-custom" @click="toggleModal">Contact</router-link>

            <!-- Conditional Render for Account Options in Modal -->
            <div v-if="!isLoggedIn">
              <router-link to="/register" class="d-block py-2 text-custom" @click="toggleModal">Register</router-link>
              <router-link to="/login" class="d-block py-2 text-custom" @click="toggleModal">Login</router-link>
            </div>
            <div v-else>
              <button @click="logout" class="btn bg-custom bg-hover text-white w-100 py-2">
                Logout
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showModal" class="modal-backdrop fade show" @click="toggleModal"></div>
  </header>
</template>

<script>
import axios from "axios";
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css'; // Import the CSS for Toastify
import Cart from "../Cart/cart.vue";

export default {
  name: "Navbar",
  components: {
    Cart
  },
  data() {
    return {
      showDropdown: false,
      isLoggedIn: false, // Track login status
      showModal: false, // Modal visibility
      showProfileDropdown: false, // Profile dropdown visibility
    };
  },
  methods: {
    // Check if user is logged in based on token
    checkLoginStatus() {
      this.isLoggedIn = !!localStorage.getItem("token");
    },
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
    toggleModal() {
      this.showModal = !this.showModal;
    },
    toggleProfileDropdown() {
      this.showProfileDropdown = !this.showProfileDropdown;
    },
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
        Toastify({
          text: "Logout successful!",
          duration: 3000,  // Show for 3 seconds
          gravity: "top",  // Position top or bottom
          position: "right", // Position left or right
          backgroundColor: "green",
        }).showToast();

        // Redirect user to the login page
        this.$router.push("/");

        // Update login status
        this.checkLoginStatus();
      } catch (error) {
        // Show an alert if logout fails
        alert("Logout failed! Please try again.");
        console.error("Logout failed:", error.response?.data?.message || error.message);
      }
    },
    // Close profile dropdown when clicked outside
    closeProfileDropdown(event) {
      if (this.$refs.profileDropdown && !this.$refs.profileDropdown.contains(event.target)) {
        this.showProfileDropdown = false;
      }
    },
  },
  mounted() {
    // Check login status on page load
    this.checkLoginStatus();
    // Add event listener to close profile dropdown on outside click
    document.addEventListener("click", this.closeProfileDropdown);
  },
  beforeDestroy() {
    // Remove event listener when the component is destroyed
    document.removeEventListener("click", this.closeProfileDropdown);
  },
  watch: {
    // Watch for changes in localStorage
    "$route"(to, from) {
      // Whenever the route changes, check if the token exists
      this.checkLoginStatus();
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

.dropdown-menu.show {
  display: block;
  position: absolute;
  right: 0;
}

.text-custom {
  color: #050748;
}

.bg-custom {
  background: #050748;
}

.bg-hover:hover {
  background-color: #0f17e9 !important;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
}

.modal-content {
  border-radius: 10px;
}

.btn-close {
  background: transparent;
  border: none;
}

@media (max-width: 767px) {
  /* Adjust modal content padding */
  .modal-content {
    padding: 15px;
  }
}

.logo {
  font-size: 1.7rem;
  font-weight: 700;
  color: #035ebe;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-family: 'Arial', sans-serif;
}

.logo span {
  color: #28a745;
}

.logo:hover {
  color: #ff4500;
}
</style>