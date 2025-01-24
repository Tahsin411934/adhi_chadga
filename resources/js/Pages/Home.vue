<template>
  <div>
  <Counter/>
    <Banner />
    <Item />
    <Categories />
    <Faq/>
  </div>
</template>

<script>
import Counter from '../components/Counter.vue';
import Banner from '../components/Home/Banner.vue'; // Ensure the path is correct
import Categories from '../components/Home/Categories.vue';
import Faq from '../components/Home/Faq.vue';
import Item from '../components/Home/Item.vue';

export default {
  name: "Home",
  components: {
    Banner, Item,Faq, Categories,Counter // Declare the Banner component here
  },
};
</script>

<style scoped></style>
