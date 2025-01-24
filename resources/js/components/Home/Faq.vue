<template>
    <div class="container my-5">
      <h2 class="text-center mb-4">FAQ - আদি চাঁটগা</h2>
      <div class="accordion" id="faqAccordion">
        <div class="accordion-item" v-for="(item, index) in faqs" :key="index">
          <h2 class="accordion-header" :id="'heading' + index">
            <button
              class="accordion-button"
              :class="{ collapsed: selected !== index }"
              type="button"
              @click="toggleFAQ(index)"
              :aria-expanded="selected === index"
              :aria-controls="'collapse' + index"
            >
              {{ item.question }}
            </button>
          </h2>
          <div
            :id="'collapse' + index"
            class="accordion-collapse collapse"
            :class="{ show: selected === index }"
            :aria-labelledby="'heading' + index"
            data-bs-parent="#faqAccordion"
          >
            <div class="accordion-body">
              {{ item.answer }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        selected: null,
        faqs: [
          {
            question: "চাটগাঁর কোন খাবার সবচেয়ে জনপ্রিয়?",
            answer: "মেজবানি মাংস এবং কালাভুনা চাটগাঁর সবচেয়ে জনপ্রিয় খাবার।",
          },
          {
            question: "মেনুতে কীভাবে খুঁজব?",
            answer: "মেনুতে সার্চ অপশন ব্যবহার করে পছন্দের খাবার সহজেই খুঁজে নিতে পারেন।",
          },
          {
            question: "অর্ডার দেওয়ার পরে কত সময় লাগবে?",
            answer: "ডেলিভারি সময় এলাকা ভেদে ভিন্ন হতে পারে, তবে আমরা ৩০-৪৫ মিনিটের মধ্যে পৌঁছানোর চেষ্টা করি।",
          },
          {
            question: "বিশেষ অফার কীভাবে জানতে পারি?",
            answer: "বিশেষ অফার সম্পর্কে জানতে আমাদের ওয়েবসাইটের 'অফার' বিভাগ ভিজিট করুন।",
          },
          {
            question: "অর্ডার ক্যানসেল করতে পারবো কি?",
            answer: "হ্যাঁ, অর্ডার দেওয়ার ১০ মিনিটের মধ্যে ক্যানসেল করা যাবে।",
          },
        ],
      };
    },
    methods: {
      toggleFAQ(index) {
        this.selected = this.selected === index ? null : index;
      },
    },
  };
  </script>
  
  <style scoped>
  .accordion-button {
    font-size: 1.1rem;
    font-weight: 500;
  }
  .accordion-body {
    font-size: 1rem;
    color: #555;
  }
  </style>
  