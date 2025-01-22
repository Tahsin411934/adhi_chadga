<template>
  <div>
    <Banner />
    <Item />
  </div>
</template>

<script>
import Banner from '../components/Home/Banner.vue'; // Ensure the path is correct
import Item from '../components/Home/Item.vue';

export default {
  name: "Home",
  components: {
    Banner, Item // Declare the Banner component here
  },
};
</script>

<style scoped></style>
