<template>
    <div class="container py-4">
      <!-- Heading -->
      <h2 class="text-center mb-4 ">Categories - আদি চাঁটগা</h2>
      
      <div class="row gy-4">
        <div
          v-for="category in categories"
          :key="category.id"
          class="col-6 col-md-4 col-lg-2 text-center"
        >
          <img
            :src="`storage/${category.image}`"
            :alt="category.name"
            class="rounded-circle img-fluid"
            style="width: 100px; height: 100px; object-fit: cover;"
          />
          <p class="mt-2 fw-medium">{{ category.name }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    name: "CategoryList",
    data() {
      return {
        categories: [], // Store category data here
      };
    },
    created() {
      this.fetchCategories(); // Fetch categories when the component is created
    },
    methods: {
      async fetchCategories() {
        try {
          const response = await axios.get("/api/categories");
          this.categories = response.data; // Assuming the API returns an array of categories
        } catch (error) {
          console.error("Error fetching categories:", error);
        }
      },
    },
  };
  </script>
  
  <style scoped>
  /* Add any additional styles if needed */
  </style>
  