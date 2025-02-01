<template>
  <div>
    <Banner />
    <Item />
    <Categories />
    <Faq />
   
    <div 
      class="bg-danger text-white   position-fixed bottom-0 w-100 d-lg-none z-3 p-2"
    >
      <div class=" container ">
        <!-- Title Section -->
        
          <SmCart  />
       
        
      </div>
    </div>
    
    
  </div>
</template>

<script>
import Cart from "../components/Cart/cart.vue";
import SmCart from "../components/Cart/SmCart.vue";

import Counter from "../components/Counter.vue";
import Banner from "../components/Home/Banner.vue"; // Ensure the path is correct
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
    SmCart // Declare the Banner component here
  },
  computed: {
    ...mapGetters(['cartTotalPrice']), // Maps the cartTotalPrice getter to a computed property
  },
};
</script>

<style scoped></style>
