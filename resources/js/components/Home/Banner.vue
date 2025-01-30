<template>
    <div class="banner position-relative text-center text-white" style="z-index: -1;">
      <!-- Background Image -->
      <div class="banner-image">
        <img src="banner-Image.png" alt="Traditional Chattogram Banner" />
      </div>
  
      <!-- Overlay for Heading, Subheading, and Search Bar -->
      <div class="overlay position-absolute top-50 start-50 translate-middle text-center w-100 px-3">
        <h1
          class="display-4 pt-2 fw-bold mb-3 gradient-text"
        >
          আদি চাঁটগা
        </h1>
  
        <h2 class="subheading fs-5 mb-4">
          চাটগাঁর ঐতিহ্যবাহী খাবার ও মেনুর স্বাদ উপভোগ করুন। সহজে খুঁজুন পছন্দের ডিশ এবং
          উপভোগ করুন ঐতিহ্যের স্বাদ।
        </h2>
  
        <!-- Search Bar -->
        <div class="search-bar mx-auto">
          <input
            type="text"
            class="form-control form-control-lg rounded-pill shadow-sm"
            placeholder="Search for dishes or menu..."
          />
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "BannerComponent",
  };
  </script>
  
  <style scoped>
  /* General Banner Styling */
  .banner {
    height: 500px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  /* Background Image */
  .banner-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1; /* Place behind the content */
  }
  
  .banner-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }
  
  /* Overlay Styling */
  .overlay {
    max-width: 800px;
    text-align: center;
    z-index: 2; /* Ensure overlay is on top */
  }
  
  /* Heading Styling */
  h1 {
    background: linear-gradient(to right, #f59e0b, #ffffff);
    -webkit-background-clip: text;
    color: transparent;
    font-size: 2.8rem;
    margin-bottom: 1rem;
  }
  
  .gradient-text {
    display: inline-block;
  }
  
  /* Subheading Styling */
  .subheading {
    color: #f3f4f6; /* Slightly softer white for better readability */
    font-size: 1.25rem;
    line-height: 1.7;
    margin-bottom: 1.5rem;
  }
  
  /* Search Bar Styling */
  .search-bar {
    width: 100%;
    max-width: 600px;
  }
  
  .search-bar input {
    padding: 1rem;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 50px;
    transition: all 0.3s ease;
  }
  
  /* Add hover and focus effects for search bar */
  .search-bar input:focus {
    outline: none;
    border-color: #f59e0b; /* Matches gradient color */
    box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
  }
  
  /* Responsive Design */
  @media (max-width: 768px) {
    .banner {
      height: 300px;
    }
  
    h1 {
      font-size: 2rem;
    }
  
    .subheading {
      font-size: 1rem;
    }
  
    .search-bar input {
      padding: 0.8rem;
      font-size: 0.9rem;
    }
  }
  </style>
  