<template>
    <div>
        
      <!-- Managed Categories Header -->
      <div class="d-flex justify-content-between mb-3">
        <h5>Managed Categories</h5>
        <button class="btn btn-success" @click="openModal">Add New</button>
      </div>

      <!-- Loader -->
      <div v-if="isLoading" class="d-flex justify-content-center">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <!-- Categories Table -->
      <table v-if="!isLoading" class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Category Name</th>
            <th scope="col">Image</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(category, index) in data" :key="category.id">
            <td>{{ index + 1 }}</td>
            <td>
              <input 
                type="text" 
                v-model="category.name" 
                :disabled="!category.isEditable" 
              />
            </td>
            <td>
              <img 
                :src="`/storage/${category.image}`"
                alt="Category image" 
                width="50" 
                height="50" 
                v-if="category.image"
              />
              <span v-else>No Image</span> <!-- Fallback if image URL is not available -->
            </td>
            <td>
              <button 
                class="btn btn-primary m-2" 
                @click="toggleEdit(category)"
              >
                {{ category.isEditable ? 'Save' : 'Edit' }}
              </button>
              <button 
                class="btn btn-danger" 
                @click="confirmDelete(category.id)"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Modal for Adding New Category -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
              <button type="button" class="close" @click="closeModal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Form for new category -->
              <div class="form-group">
                <label for="categoryName">Category Name</label>
                <input 
                  type="text" 
                  v-model="newCategoryName" 
                  id="categoryName"
                  placeholder="Enter category name" 
                  class="form-control"
                />
              </div>
              <div class="form-group">
                <label for="categoryImage">Category Image</label>
                <input 
                  type="file" 
                  @change="handleImageChange" 
                  id="categoryImage" 
                  class="form-control"
                  accept="image/*"
                />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
              <button type="button" class="btn btn-primary" @click="saveCategory">Save</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
import axiosInstance from '../../utils/axiosInstance';
import { Modal } from 'bootstrap';

export default {
  data() {
    return {
      data: [],
      newCategoryName: '',
      newCategoryImage: null,
      modal: null,
      isLoading: false, // Loading state
      alertMessage: '',  // Message for alert
      alertType: '',  // Type of alert (success/error)
      categoryToDelete: null // Temporary store category ID to be deleted
    };
  },
  mounted() {
    // Fetch categories data
    this.fetchData();

    // Initialize the modal using Bootstrap JS
    const modalElement = document.getElementById('exampleModal');
    this.modal = new Modal(modalElement);
  },
  methods: {
    // Open the modal programmatically
    openModal() {
      this.modal.show();
    },

    // Close the modal programmatically
    closeModal() {
      this.modal.hide();
    },
    
    // Fetch categories from API
    async fetchData() {
      this.isLoading = true; // Set loading state to true when fetching data
      try {
        const res = await axiosInstance.get('/api/categories');
        this.data = res.data.map(category => ({
          ...category,
          isEditable: false,
        }));
        console.log('Fetched categories:', res.data);
      } catch (error) {
        console.error('Error fetching categories:', error);
      } finally {
        this.isLoading = false; // Set loading state to false after data is fetched
      }
    },

    // Toggle editing mode for category name
    toggleEdit(category) {
      if (category.isEditable) {
        // Save the updated category name
        this.updateCategory(category);
      } else {
        // Enable editing
        category.isEditable = true;
      }
    },

    // Update category in the API
    async updateCategory(category) {
      try {
        const res = await axiosInstance.put(`/api/categories/${category.id}`, {
          name: category.name,
        });
        console.log('Category updated:', res.data);
        
        // Disable editing mode after successful update
        category.isEditable = false;
      } catch (error) {
        console.error('Error updating category:', error);
      }
    },

    // Confirm Deletion of Category
    confirmDelete(categoryId) {
      if (confirm('Are you sure you want to delete this category?')) {
        // If the user clicks "OK" in the confirmation dialog, delete the category
        this.deleteCategory(categoryId);
      }
    },

    // Delete category by ID
    async deleteCategory(categoryId) {
      try {
        const res = await axiosInstance.delete(`/api/categories/${categoryId}`);
        console.log('Category deleted:', res.data);

        // Remove the deleted category from the local data array
        this.data = this.data.filter(category => category.id !== categoryId);

       
      } catch (error) {
        console.error('Error deleting category:', error);

        // Show error alert if something went wrong
        this.alertMessage = 'Error deleting category!';
        this.alertType = 'error';
        this.showAlert();
      }
    },

    // Handle image change (when a file is selected)
    handleImageChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.newCategoryImage = file;
      }
    },

    // Save new category to the API
    async saveCategory() {
      try {
        const formData = new FormData();
        formData.append('name', this.newCategoryName);
        
        // Append image if available
        if (this.newCategoryImage) {
          formData.append('image', this.newCategoryImage);
        }

        // Send the FormData to the API
        const res = await axiosInstance.post('/api/categories', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });

        console.log('Category created:', res.data);
        
        // Close the modal after saving
        this.modal.hide();
        
        // Clear the input fields
        this.newCategoryName = '';
        this.newCategoryImage = null;
        
        // Refetch the categories to include the newly added one
        this.fetchData();
      } catch (error) {
        console.error('Error saving category:', error);
      }
    },

    // Show alert message after delete or error
    showAlert() {
      alert(this.alertMessage); // Show alert with message
      setTimeout(() => {
        this.alertMessage = '';  // Clear the message after the alert is shown
      }, 3000);
    }
  },
};
</script>
