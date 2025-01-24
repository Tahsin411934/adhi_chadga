<template>
    <div>
      <h2>Child Count: {{ count }}</h2>
    </div>
  </template>
  
  <script>
  export default {
    name: "ChildComponent",
    data() {
      return {
        count: 0, // Initialize the count
      };
    },
    methods: {
      increaseCount() {
        this.count += 1; // Increment count
        localStorage.setItem("childCount", this.count); // Update local storage
        this.$emit("countUpdated", this.count); // Emit the updated count to the parent
      },
    },
    mounted() {
      // Load the count from local storage when the component mounts
      const savedCount = localStorage.getItem("childCount");
      if (savedCount !== null) {
        this.count = parseInt(savedCount, 10); // Parse and set the saved count
        this.$emit("countUpdated", this.count); // Notify the parent about the initial count
      }
    },
  };
  </script>
  