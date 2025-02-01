<template>
  <div>
    <Banner />
    <Item />
    <Categories />
    <div class="d-none checkout d-lg-block">
      <!-- Floating Cart, positioned below Categories -->
      <div class="floating-cart">
        <Cart />
      </div>
    </div>
    <Faq />
   
    <div class="bg-danger text-white position-fixed bottom-0 w-100 d-lg-none z-3 p-2">
      <div class="container">
        <!-- Title Section -->
        <SmCart />
      </div>
    </div>
  </div>
</template>

<script>
import Cart from "../components/Cart/cart.vue";
import SmCart from "../components/Cart/SmCart.vue";
import Counter from "../components/Counter.vue";
import Banner from "../components/Home/Banner.vue";
import Categories from "../components/Home/Categories.vue";
import Faq from "../components/Home/Faq.vue";
import Item from "../components/Home/Item.vue";
import { mapGetters } from 'vuex';

export default {
  name: "Home",
  components: {
    Banner,
    Item,
    Faq,
    Categories,
    Counter,
    Cart,
    SmCart
  },
  computed: {
    ...mapGetters(['cartTotalPrice']),
  },
};
</script>

<style scoped>
/* Floating cart positioned below Categories */
.floating-cart {
  padding: 10px;
  background-color: #ec7445; /* Equivalent to Tailwind's bg-yellow-700 */
  position: fixed;
  right: 50px;
  top: 550px;
  z-index: 50;
  border-radius: 50%;
}

.floating-cart .cart {
  margin: 10px;
  padding: 15px;
}

.checkout {
 
}
</style>