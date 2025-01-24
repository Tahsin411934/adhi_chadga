import { createStore } from 'vuex';

const store = createStore({
  state: {
    cart: [],
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
        existingItem.quantity += 1;
      } else {
        state.cart.push({ ...item, quantity: 1 });
      }
    },
    incrementQuantity(state, itemId) {
      const item = state.cart.find((cartItem) => cartItem.id === itemId);
      if (item) {
        item.quantity += 1;
      }
    },
    decrementQuantity(state, itemId) {
      const item = state.cart.find((cartItem) => cartItem.id === itemId);
      if (item && item.quantity > 1) {
        item.quantity -= 1;
      }
    },
    removeFromCart(state, itemId) {
      state.cart = state.cart.filter((item) => item.id !== itemId);
    },
    clearCart(state) {
      state.cart = [];
    },
  },
  actions: {
    addToCart({ commit }, item) {
      commit("addToCart", item);
    },
    incrementItemQuantity({ commit }, itemId) {
      commit("incrementQuantity", itemId);
    },
    decrementItemQuantity({ commit }, itemId) {
      commit("decrementQuantity", itemId);
    },
    removeFromCart({ commit }, itemId) {
      commit("removeFromCart", itemId);
    },
    clearCart({ commit }) {
      commit("clearCart");
    },
  },
});

export default store;