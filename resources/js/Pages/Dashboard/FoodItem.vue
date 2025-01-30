<template>
  <div>
    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary" @click="openModal">
      Open Modal
    </button>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Item Name</th>
          <th scope="col">Description</th>
          <th scope="col">Price</th>
          <th scope="col">Image</th>
          <th scope="col">Available Days</th>
          <th scope="col">Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item, index) in items" :key="item.id">
          <td>{{ index + 1 }}</td>
          <td><input type="text" class="form-control" v-model="item.name" /></td>
          <td><input v-model="item.description" class="form-control" required /></td>
          <td><input type="number" v-model="item.price" class="form-control" step="0.01" required /></td>
          <img :src="`/storage/${item.image_url}`" alt="Category image" width="50" height="50" v-if="item.image_url" />

          <td>
            <div class="checkbox-group">
              <div v-for="day in days" :key="day">
                <!-- Bind each checkbox to item.available_day array -->
                <input
                  type="checkbox"
                  :value="day"
                  v-model="item.available_day"
                />
                <label>{{ day }}</label>
              </div>
            </div>
          </td>

          <td> 
            <button @click="updateForm(item)">Update</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div v-if="isModalOpen">
      <!-- Modal Backdrop -->
      <div class="modal-backdrop" @click="closeModal"></div>

      <!-- Modal Content -->
      <div class="modal modal-visible">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Food Item</h5>
              <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="submitForm">
                <!-- Name -->
                <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" v-model="FormData.name" class="form-control" required />
                </div>

                <!-- Description -->
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea v-model="FormData.description" class="form-control" required></textarea>
                </div>

                <!-- Price -->
                <div class="form-group">
                  <label for="price">Price</label>
                  <input type="number" v-model="FormData.price" class="form-control" step="0.01" required />
                </div>

                <!-- Image File Upload -->
                <div class="form-group">
                  <label for="image">Image</label>
                  <input type="file" @change="handleFileUpload" class="form-control" accept="image/*" required />
                </div>

                <!-- Category ID -->
                <div class="form-group">
                  <label for="category_id">Category ID</label>
                  <input type="number" v-model="FormData.category_id" class="form-control" required />
                </div>

                <!-- Available -->
                <div class="form-group">
                  <label for="available">Available</label>
                  <select v-model="FormData.available" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>

                <!-- Available Days (Multi-Select) -->
                <div class="form-group">
                  <label>Available Days</label>
                  <div class="checkbox-group">
                    <div v-for="day in days" :key="day">
                      <input type="checkbox" :value="day" v-model="FormData.available_day" />
                      <label>{{ day }}</label>
                    </div>
                  </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="closeModal">
                    Close
                  </button>
                  <button type="submit" class="btn btn-primary">
                    Save Changes
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosInstance from "../../utils/axiosInstance";

export default {
  data() {
    return {
      isModalOpen: false, // Controls modal visibility
      FormData: {
        name: "",
        description: "",
        price: 0,
        image: null, // Store the uploaded file
        category_id: null,
        available: 1, // Default to "Yes"
        available_day: [], // Array for multiple selected days
      },
      items: [], // Store the list of food items
      days: [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
      ], // List of days
    };
  },

  methods: {
    openModal() {
      this.isModalOpen = true;
    },

    closeModal() {
      this.isModalOpen = false;
      this.resetForm();
    },

    resetForm() {
      this.FormData = {
        name: "",
        description: "",
        price: 0,
        image: null,
        category_id: null,
        available: 1,
        available_day: [],
      };
    },

    handleFileUpload(event) {
      // Store the selected file in FormData
      this.FormData.image = event.target.files[0];
    },

    async getData() {
      const res = await axiosInstance.get("/api/food-items");
      this.items = res.data.map(item => {
        return {
          ...item,
          available_day: JSON.parse(item.available_day || '[]') // Parse available_day to array
        }
      });
    },

    async submitForm() {
      try {
        // Create a FormData object to send files
        const formData = new FormData();
        formData.append("name", this.FormData.name);
        formData.append("description", this.FormData.description);
        formData.append("price", this.FormData.price);
        formData.append("image", this.FormData.image); // Append the file
        formData.append("category_id", this.FormData.category_id);
        formData.append("available", this.FormData.available);

        // Append each available_day item individually
        this.FormData.available_day.forEach((day) => {
          formData.append("available_day[]", day);
        });

        // Send the form data to the backend
        const res = await axiosInstance.post(
          "/api/food-items",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data", // Required for file uploads
            },
          }
        );
        await this.getData();
        // Close the modal
        this.closeModal();
      } catch (error) {
        console.error(
          "Error creating food item:",
          error.response?.data || error.message
        );
      }
    },

    async updateForm(item) {
      try {
        // You can implement the update logic here
        const response = await axiosInstance.put(`api/food-items/${item.id}`, item); 
        console.log('Updated item:', response.data);  // Handle the response if necessary
      } catch (error) {
        console.error('Error updating item:', error);  // Handle errors
      }
    }

  },

  mounted() {
    this.getData();
  },
};
</script>

<style scoped>
/* Modal styles */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  justify-content: center;
  align-items: center;
  z-index: 1050;
}

.modal-visible {
  display: flex;
}

.modal-dialog {
  background: white;
  padding: 20px;
  border-radius: 5px;
  z-index: 1051;
  max-width: 500px;
  width: 100%;
}

/* Backdrop styles */
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1040;
}
</style>
