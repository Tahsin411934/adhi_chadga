<template>
  <div>
    <!-- Button to trigger modal -->
    <button
      type="button"
      class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#exampleModal"
    >
      Open Modal
    </button>

    <!-- Modal -->
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
      ref="exampleModal"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal Title</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitForm">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" v-model="FormData.name" />
              </div>
              <div class="form-group">
                <label for="age">Age</label>
                <input type="number" v-model="FormData.age" />
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal"
                >
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
</template>

<script>
import axiosInstance from "../../utils/axiosInstance";

export default {
  data() {
    return {
      FormData: {
        name: "",
        age: "",
      },
    };
  },

  methods: {
    async submitForm() {
      console.log(this.FormData);
      try {
        const res = await axiosInstance.post("/api/test/store", this.FormData);
        console.log(res);

        // Close the modal
        const modalElement = this.$refs.exampleModal;
        const bootstrapModal = bootstrap.Modal.getInstance(modalElement);
        bootstrapModal.hide();

        // Optionally clear the form
        this.FormData.name = "";
        this.FormData.age = "";
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>

<style scoped></style>
