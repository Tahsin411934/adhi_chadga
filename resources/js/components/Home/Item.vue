<template>
  <div class="container mt-4">
    <!-- Show header -->
    <div class="fs-3 mb-5 fw-semibold">Recommended:</div>

    <!-- Show loading state -->
    <div v-if="loading" class="text-center">
      <p>লোড হচ্ছে...</p>
    </div>

    <!-- Show error message -->
    <div v-else-if="error" class="text-center text-danger">
      <p>{{ error }}</p>
    </div>

    <!-- Show food items once data is fetched -->
    <div v-else class="row row-cols-2 row-cols-sm-2 row-cols-md-3 g-4">
      <div v-for="item in foodItems" :key="item.id" class="col">
        <!-- Add click event to open the modal -->
        <div class="card" style="background-color: #f7fafc" @click="viewCartModal(item)">
          <div style="height: 210px">
            <img :src="item.image_url" :alt="item.name" class="card-img-top h-50" />
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">{{ item.name }}</h5>
              <div class="d-flex justify-content-between align-items-center mt-auto">
                <p class="card-text mb-0">৳ {{ item.price }}</p>
                <!-- Add to cart button -->
                <button class="btn btn-dark btn-sm" @click.stop="addToCart(item, 1)">
                  <i class="fas fa-cart-plus"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Cart Modal for viewing and modifying cart -->
    <div v-if="isModalOpen" class="custom-modal-overlay" @click.self="closeModal">
      <div class="custom-modal position-relative">
        <div class="custom-modal-header d-flex justify-content-between position-absolute">
          <button @click="closeModal" class="close-btn rounded-circle p-2" >X</button>
        </div>
        <div class="custom-modal-body">
          <div v-if="selectedItem">
            <!-- Main Item Details -->
            <img :src="selectedItem.image_url" alt="item-image" class="img-fluid" />
            <div class="p-3">
              <h5 class="fw-bold">{{ selectedItem.name }}</h5>
              <p>৳ {{ selectedItem.price }}</p>
              <p>{{ selectedItem.description }}</p>
              <hr>
              <!-- Multiple Select Options for Other Items -->
              <div class="form-group mt-1">
                <label for="other-items" class="fw-semibold">Select Other Items (optional)</label>
                <div
                  v-for="item in foodItems"
                  :key="item.id"
                  class="form-check d-flex justify-content-between align-content-center mt-3"
                >
                  <div>
                    <input
                      v-model="selectedOtherItemsChecked[item.id]"
                      class="form-check-input border border-dark"
                      type="checkbox"
                      :id="`item-${item.id}`"
                      :value="item.id"
                    />
                    <label class="form-check-label" :for="`item-${item.id}`">
                      {{ item.name }} - ৳ {{ item.price }}
                    </label>
                  </div>
                  <!-- Quantity controls for selected other items -->
                  <div
                    v-if="selectedOtherItemsChecked[item.id]"
                    class="quantity-controls d-flex align-items-center mt-2"
                  >
                    <button
                      class="btn btn-outline-dark btn-sm"
                      @click="decreaseQuantityForOther(item)"
                    >
                      -
                    </button>
                    <span class="mx-3">
                      {{ getSelectedItemQuantity(item) || 1 }}
                    </span>
                    <button
                      class="btn btn-outline-dark btn-sm"
                      @click="increaseQuantityForOther(item)"
                    >
                      +
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="custom-modal-footer d-flex justify-content-between">
          <div></div>
            <button class="btn btn-danger" @click="addAllToCart">
              Add Items to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Toastify from "toastify-js";
import axiosInstance from "../../utils/axiosInstance";

export default {
  name: "FoodCardGrid",
  data() {
    return {
      foodItems: [], // Array to hold food data
      loading: true, // Loading state
      error: null, // Error message
      isModalOpen: false, // Modal visibility state
      selectedItem: null, // Selected item for modal
      selectedOtherItemsChecked: {}, // Object to track checked items
      selectedOtherItemsQuantities: {}, // Object to track item quantities
    };
  },
  methods: {
    async fetchFoodItems() {
      try {
        const response = await axiosInstance.get("/api/food-items");
        this.foodItems = response.data;
      } catch (err) {
        this.error = err.response?.data?.message || "ডাটা লোড করতে সমস্যা হয়েছে।";
      } finally {
        this.loading = false;
      }
    },
    addToCart(item, quantity) {
      this.$store.dispatch("addToCart", { ...item, quantity });
      Toastify({
        text: `${item.name} added to cart!`,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: "#4CAF50",
      }).showToast();
    },
    addAllToCart() {
      const itemsToAdd = [
       
        ...this.foodItems
          .filter((item) => this.selectedOtherItemsChecked[item.id])
          .map((item) => ({
            ...item,
            quantity: this.selectedOtherItemsQuantities[item.id] || 1,
          })),
      ];

      itemsToAdd.forEach((item) => {
        this.addToCart(item, item.quantity);
      });

      this.closeModal();
      
    },
    increaseQuantityForOther(item) {
      if (!this.selectedOtherItemsQuantities[item.id]) {
        this.selectedOtherItemsQuantities[item.id] = 2;
      } else {
        this.selectedOtherItemsQuantities[item.id] += 1;
      }
    },
    decreaseQuantityForOther(item) {
      if (this.selectedOtherItemsQuantities[item.id] > 1) {
        this.selectedOtherItemsQuantities[item.id] -= 1;
      }
    },
    viewCartModal(item) {
      this.selectedItem = { ...item, quantity: 1 };
      this.selectedOtherItemsChecked = { [item.id]: true };
      this.selectedOtherItemsQuantities = {};
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
      this.selectedItem = null;
      this.selectedOtherItemsChecked = {};
      this.selectedOtherItemsQuantities = {};
    },
    getSelectedItemQuantity(item) {
      return this.selectedOtherItemsQuantities[item.id] || 1;
    },
  },
  mounted() {
    this.fetchFoodItems();
  },
};
</script>

<style scoped>
.container {
  max-width: 1200px;
}

.card-img-top {
  height: 200px; /* Fixed height for all images */
  width: 100%; /* Make the width 100% of the card */
  object-fit: cover;
}

.custom-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1050;
}

.custom-modal {
  background-color: white;
  border-radius: 8px;
  width: 80%;
  max-width: 600px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden; /* Hide scrollbars for the modal */
  max-height: 100vh; /* Limit the modal height */
  display: flex;
  flex-direction: column;
}

.custom-modal-body {
  padding: 0px;
  flex-grow: 1; /* Allow the body to take up available space */
  overflow-y: auto; /* Make the body scrollable */
}

.custom-modal-footer {
  padding: 10px 20px;
  border-top: 1px solid #ddd;
  text-align: right;
  position: sticky;
  bottom: 0; /* Stick the footer at the bottom */
  background-color: white; /* Ensure background stays white */
  z-index: 1; /* Keep footer above modal body content */
}

.custom-modal-body img {
  width: 100%;
  border-radius: 5px;
}

.quantity-controls {
  margin-top: 10px;
}

@media (max-width: 750px) {
  .custom-modal {
    width: 100%;
    height: 100%;
    /* margin-top: 20px; */
    border-radius: 0;
  }

  .custom-modal-header,
  .custom-modal-footer {
    padding: 15px;
  }

  .custom-modal-body {
  }
}
</style>
