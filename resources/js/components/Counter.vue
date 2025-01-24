<template>
    <div>
      <div class="cart-icon position-relative" data-bs-toggle="modal" data-bs-target="#cartModal">
        <i class="fas fa-shopping-cart fa-2x"></i>
        <span v-if="cartItems.length > 0" class="badge bg-danger position-absolute top-0 start-100 translate-middle">
          {{ totalItems }}
        </span>
      </div>
  
      <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
  
            <div class="modal-body">
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
                    <div class="text-muted small">৳ {{ item.price }} each</div>
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
  
            <div class="modal-footer">
              <div v-if="cartItems.length > 0" class="w-100 text-end">
                <h5>Total: ৳ {{ cartTotalPrice }}</h5>
                <button class="btn btn-primary btn-sm me-2" @click="submitCart">
                  Submit Data
                </button>
                <button class="btn btn-success btn-sm me-2" @click="checkout">
                  Checkout
                </button>
                <button class="btn btn-danger btn-sm" @click="clearCart">
                  Clear Cart
                </button>
              </div>
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
    computed: {
      ...mapGetters(["cartItems", "cartTotalPrice"]),
      totalItems() {
        return this.cartItems.length; // Count unique items
      },
    },
    methods: {
      ...mapActions([
        "incrementItemQuantity",
        "decrementItemQuantity",
        "removeFromCart",
        "clearCart",
      ]),
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
  
          const modal = bootstrap.Modal.getInstance(document.getElementById("cartModal"));
          modal.hide();
        }
      },
      submitCart() {
        if (this.cartItems.length === 0) {
          console.log("Cart is empty. No data to submit.");
          return;
        }
  
        const cartData = {
          items: this.cartItems.map(item => ({
            id: item.id,
            name: item.name,
            quantity: item.quantity,
            total: item.price * item.quantity,
          })),
          totalPrice: this.cartTotalPrice,
        };
  
        // Log the cart data to the console
        console.log("Cart Data Submitted:", cartData);
  
        // Future: Replace console.log with an API call
        // Example:
        // axios.post('/api/submit-cart', cartData)
        //   .then(response => {
        //     console.log('Cart data submitted successfully:', response.data);
        //   })
        //   .catch(error => {
        //     console.error('Error submitting cart data:', error);
        //   });
      },
    },
  };
  </script>
  
  <style scoped>
  .cart-icon {
    cursor: pointer;
    display: inline-block;
    position: relative;
  }
  .badge {
    font-size: 0.75rem;
  }
  .quantity-controls {
    display: flex;
    align-items: center;
  }
  .quantity-controls button {
    margin: 0 5px; /* Consistent margin on both sides of the buttons */
    padding: 5px 10px; /* Proper padding for buttons */
    font-size: 0.9rem; /* Adjust size to match quantity text */
  }
  .quantity-controls span {
    margin: 0 5px; /* Ensure spacing between buttons and quantity */
    font-weight: bold;
  }
  </style>
  