<template>
  <div>
    <Banner />
    <Item />
    <Categories />
    <Faq />
    <div
      class="bg-danger text-white text-center py-2 position-fixed bottom-0 w-100 d-lg-none"
    >
      <div class="container d-flex justify-content-around align-items-center">
        <!-- Title Section -->
        <div class="col-md-2 text-end">
          <Cart />
        </div>
        <div class="fs-6  fw-bold">Review Your Cart</div>
        <div>{{ cartTotalPrice }} TK</div>
       
       
      </div>
    </div>
  </div>
</template>

<script>
import Cart from "../components/Cart/cart.vue";
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
    Cart, // Declare the Banner component here
  },
  computed: {
    ...mapGetters(['cartTotalPrice']), // Maps the cartTotalPrice getter to a computed property
  },
};
</script>

<style scoped></style>
