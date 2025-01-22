<template>
  <div class="container mt-4">
    <!-- Show loading state -->
    <div class="fs-3 mb-5 fw-semibold">Recomanded: </div>
    <div v-if="loading" class="text-center">
      <p>লোড হচ্ছে...</p>
    </div>

    <!-- Show error message -->
    <div v-else-if="error" class="text-center text-danger">
      <p>{{ error }}</p>
    </div>

    <!-- Show food items once data is fetched -->
    <div v-else class="row row-cols-1 row-cols-sm-1 row-cols-md-3 g-4" >
      <div
        v-for="item in foodItems"
        :key="item.id"
        class="col"
      >
        <div class="card h-100" style="background-color: #f7fafc;">
          <img
            :src="item.image_url"
            :alt="item.name"
            class="card-img-top"
          />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ item.name }}</h5>
            <div class="d-flex justify-content-between align-items-center">
              <p class="card-text">৳ {{ item.price }}</p>
              <!-- Add Font Awesome icon here -->
              <button class="btn btn-primary" @click="addToCart(item)">
                <i class="fas fa-cart-plus"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
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
        const response = await fetch("http://localhost:8000/api/food-items");
        if (!response.ok) {
          throw new Error("ডাটা লোড করতে সমস্যা হয়েছে।");
        }
        const data = await response.json();
        this.foodItems = data;
      } catch (err) {
        this.error = err.message;
      } finally {
        this.loading = false;
      }
    },
    addToCart(item) {
      alert(`${item.name} added to cart!`);
      // Implement actual add-to-cart functionality here
    },
  },
  mounted() {
    this.fetchFoodItems(); // Fetch data when component is mounted
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
