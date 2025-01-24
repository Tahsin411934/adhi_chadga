<template>
  <div class="container mt-4">
    <!-- Show header -->
    <div class="fs-3 mb-5 fw-semibold">Recommended: </div>

    <!-- Show loading state -->
    <div v-if="loading" class="text-center">
      <p>লোড হচ্ছে...</p>
    </div>

    <!-- Show error message -->
    <div v-else-if="error" class="text-center text-danger">
      <p>{{ error }}</p>
    </div>

    <!-- Show food items once data is fetched -->
    <div v-else class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
      <div v-for="item in foodItems" :key="item.id" class="col">
        <div class="card h-100" style="background-color: #f7fafc;">
          <img
            :src="item.image_url"
            :alt="item.name"
            class="card-img-top"
          />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ item.name }}</h5>
            <div class="d-flex justify-content-between align-items-center mt-auto">
              <p class="card-text mb-0">৳ {{ item.price }}</p>
              <!-- Add to cart button -->
              <button class="btn btn-dark btn-sm" @click="addToCart(item)">
                <i class="fas fa-cart-plus"></i> Add to Cart
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosInstance from "../../utils/axiosInstance";

export default {
  name: "FoodCardGrid",
  data() {
    return {
      foodItems: [], // Array to hold food data
      loading: true, // Loading state
      error: null, // Error message
    };
  },
  methods: {
    async fetchFoodItems() {
      try {
        // Fetch food items using Axios
        const response = await axiosInstance.get("/api/food-items");
        this.foodItems = response.data;
      } catch (err) {
        this.error = err.response?.data?.message || "ডাটা লোড করতে সমস্যা হয়েছে।";
      } finally {
        this.loading = false; // Set loading to false after fetching data
      }
    },
    addToCart(item) {
      this.$store.dispatch("addToCart", item); // Dispatch action to add item to cart
    },
  },
  mounted() {
    // Fetch data when the component is mounted
    this.fetchFoodItems();
  },
};
</script>

<style scoped>
.container {
  max-width: 1200px;
}
.card-img-top {
  height: 200px;
  object-fit: cover;
}
</style>
