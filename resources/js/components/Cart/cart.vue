<template>
  <div>
    <!-- Cart Icon -->
    <div class="cart-icon position-relative" @click="openModal">
      <i class="fas fa-shopping-cart fa-lg"></i>
      <span v-if="cartItems.length > 0" class="badge bg-danger position-absolute top-0 start-100 translate-middle " style="padding-right: 17px;">
        {{ totalItems }}
      </span>
    </div>

    <!-- Cart Modal -->
    <div v-if="isModalOpen" class="custom-modal-overlay">
      <div class="custom-modal">
        <div class="custom-modal-header">
          <h5 class="modal-title">Your Cart</h5>
          <button @click="closeModal" class="close-btn">X</button>
        </div>

        <div class="custom-modal-body">
          <div v-if="cartItems.length === 0" class="text-muted text-center">
            <p>Your cart is empty.</p>
          </div>

          <ul v-else class="list-group">
            <li
              v-for="item in cartItems"
              :key="item.id"
              class="list-group-item d-flex justify-content-between align-items-center"
            >
              <div>
                <strong>{{ item.name }}</strong>
                <div class="text-muted small">৳ {{ item.price }} (each)</div>
              </div>

              <div class="quantity-controls">
                <button class="btn btn-light btn-sm" @click="decrementItem(item.id)">
                  <i class="fas fa-minus"></i>
                </button>
                <span>{{ item.quantity }}</span>
                <button class="btn btn-light btn-sm" @click="incrementItem(item.id)">
                  <i class="fas fa-plus"></i>
                </button>
              </div>

              <div class="d-flex align-items-center">
                <span class="me-3">৳ {{ item.price * item.quantity }}</span>
                <button class="btn btn-danger btn-sm" @click="removeFromCart(item.id)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </li>
          </ul>
        </div>

        <div class="custom-modal-footer">
          <div v-if="cartItems.length > 0" class="w-100 text-end">
            <h5>Total: ৳ {{ cartTotalPrice }}</h5>
            <button class="btn btn-primary btn-sm me-2" @click="submitCart">
              Order Now
            </button>
            
            <button class="btn btn-danger btn-sm" @click="clearCart">
              Clear Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  name: "Cart",
  data() {
    return {
      isModalOpen: false,
    };
  },
  computed: {
    ...mapGetters(["cartItems", "cartTotalPrice"]),
    totalItems() {
      return this.cartItems.reduce((total, item) => total + item.quantity, 0);
    },
  },
  methods: {
    ...mapActions([
      "incrementItemQuantity",
      "decrementItemQuantity",
      "removeFromCart",
      "clearCart",
    ]),
    openModal() {
      this.isModalOpen = true;
    },
    closeModal() {
      this.isModalOpen = false;
    },
    incrementItem(id) {
      this.incrementItemQuantity(id);
    },
    decrementItem(id) {
      this.decrementItemQuantity(id);
    },
    checkout() {
      if (this.cartItems.length === 0) {
        alert("Your cart is empty!");
        return;
      }

      if (confirm("Are you sure you want to place the order?")) {
        this.clearCart();
        alert("Order placed successfully!");
        this.closeModal();
      }
    },
    submitCart() {
      if (this.cartItems.length === 0) {
        console.log("Cart is empty. No data to submit.");
        return;
      }

      const cartData = {
        items: this.cartItems.map((item) => ({
          id: item.id,
          name: item.name,
          quantity: item.quantity,
          total: item.price * item.quantity,
        })),
        totalPrice: this.cartTotalPrice,
      };

      // Log the cart data to the console
      console.log("Cart Data Submitted:", cartData);

      // Show success message
      alert("Cart data submitted successfully!");

      // Close modal
      this.closeModal();
    },
  },
};
</script>

<style scoped>
/* Add your styles for the cart modal */
.custom-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.custom-modal {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  max-width: 500px;
  width: 100%;
  height: 100%; /* Full height of the viewport */
  display: flex;
  flex-direction: column; /* Ensures proper layout for header, body, and footer */
}

.custom-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 10px;
  border-bottom: 1px solid #ddd;
}

.custom-modal-body {
  flex: 1; /* Takes up all available space between header and footer */
  overflow-y: auto; /* Enables scrolling for overflowing content */
  margin-top: 10px;
  margin-bottom: 10px;
}

.custom-modal-body::-webkit-scrollbar {
  width: 8px; /* Width of the scrollbar */
}

.custom-modal-body::-webkit-scrollbar-thumb {
  background-color: #888; /* Scrollbar color */
  border-radius: 4px; /* Rounded corners for the scrollbar */
}

.custom-modal-body::-webkit-scrollbar-thumb:hover {
  background-color: #555; /* Darker color on hover */
}

.custom-modal-footer {
  padding-top: 10px;
  border-top: 1px solid #ddd;
  display: flex;
  justify-content: flex-end;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 10px;
}

.quantity-controls button {
  padding: 5px 10px;
}

.cart-icon {
  position: relative;
  cursor: pointer;
}

.cart-icon .badge {
  position: absolute;
  top: 0;
  right: 0;
}
</style>


