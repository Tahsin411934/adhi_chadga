import { createStore } from 'vuex';

const store = createStore({
  state: {
    cart: JSON.parse(localStorage.getItem('cart')) || [], // Load cart data from localStorage or initialize with an empty array
  },
  getters: {
    cartItemCount(state) {
      return state.cart.reduce((total, item) => total + item.quantity, 0);
    },
    cartTotalPrice(state) {
      return state.cart.reduce(
        (total, item) => total + item.price * item.quantity,
        0
      );
    },
    cartItems(state) {
      return state.cart;
    },
  },
  mutations: {
    addToCart(state, item) {
      const existingItem = state.cart.find((cartItem) => cartItem.id === item.id);
      if (existingItem) {
        existingItem.quantity += item.quantity;
      } else {
        state.cart.push({ ...item, quantity: item.quantity });
      }
      localStorage.setItem('cart', JSON.stringify(state.cart)); // Save cart to localStorage
    },
    incrementQuantity(state, itemId) {
      const item = state.cart.find((cartItem) => cartItem.id === itemId);
      if (item) {
        item.quantity += 1;
      }
      localStorage.setItem('cart', JSON.stringify(state.cart)); // Save cart to localStorage
    },
    decrementQuantity(state, itemId) {
      const item = state.cart.find((cartItem) => cartItem.id === itemId);
      if (item && item.quantity > 1) {
        item.quantity -= 1;
      }
      localStorage.setItem('cart', JSON.stringify(state.cart)); // Save cart to localStorage
    },
    removeFromCart(state, itemId) {
      state.cart = state.cart.filter((item) => item.id !== itemId);
      localStorage.setItem('cart', JSON.stringify(state.cart)); // Save cart to localStorage
    },
    clearCart(state) {
      state.cart = [];
      localStorage.removeItem('cart'); // Remove cart from localStorage
    },
  },
  actions: {
    addToCart({ commit }, item) {
      commit('addToCart', item);
    },
    incrementItemQuantity({ commit }, itemId) {
      commit('incrementQuantity', itemId);
    },
    decrementItemQuantity({ commit }, itemId) {
      commit('decrementQuantity', itemId);
    },
    removeFromCart({ commit }, itemId) {
      commit('removeFromCart', itemId);
    },
    clearCart({ commit }) {
      commit('clearCart');
    },
  },
});

export default store;
