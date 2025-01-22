<template>
    <div>
        <Sidebar/>
        <router-view></router-view>
    </div>
</template>

<script>
import Sidebar from './Sidebar.vue';

    export default {
        components: {
            Sidebar
  },
    }
</script>

<style scoped>

</style>