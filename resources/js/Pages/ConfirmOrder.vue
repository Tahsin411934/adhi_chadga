<template>
    <div class="container m-3 mx-auto">
        <div class="d-md-flex justify-content-between gap-2">
            <!-- Items Section -->
            <div class="col-md-6 col-12 shadow-lg p-4 mx-auto" style="flex: 1">
                <h5>Your order from</h5>
                <div class="list-group">
                    <div
                        class="list-group-item"
                        v-for="(item, index) in cartData.items"
                        :key="index"
                    >
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <p class="mb-0">
                                    {{ item.quantity }} X {{ item.name }}
                                </p>
                                <p>Delivery at: {{ item.selectedDay }}</p>
                            </div>
                            <div class="col-6 text-end">
                                Tk {{ item.total }}
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="text-end">
                    Total Price: Tk {{ cartData.totalPrice }}
                </h5>
            </div>

            <!-- Form Section -->
            <div class="col-md-6 col-12 shadow-lg p-4 mx-auto" style="flex: 1">
                <h5 class="modal-title">Delivery Address</h5>
                <form @submit.prevent="submitForm">
                    <div class="row g-1">
                        <div class="col-md-6 mb-1 col-12">
                            <label for="area" class="form-label small-text">Area *</label>
                            <input
                                type="text"
                                id="area"
                                v-model="form.area"
                                class="form-control input-sm"
                                required
                                placeholder="Enter Area"
                            />
                        </div>
                        <div class="col-md-6 mb-1 col-12">
                            <label for="roadStreet" class="form-label small-text">Road/Street *</label>
                            <input
                                type="text"
                                id="roadStreet"
                                v-model="form.roadStreet"
                                class="form-control input-sm"
                                required
                                placeholder="Enter Road/Street"
                            />
                        </div>
                    </div>
                    <div class="row g-1">
                        <div class="col-md-6 mb-1 col-12">
                            <label for="houseNo" class="form-label small-text">House No *</label>
                            <input
                                type="text"
                                id="houseNo"
                                v-model="form.houseNo"
                                class="form-control input-sm"
                                required
                                placeholder="Enter House No"
                            />
                        </div>
                        <div class="col-md-6 mb-1 col-12">
                            <label for="floorNo" class="form-label small-text">Floor No</label>
                            <input
                                type="text"
                                id="floorNo"
                                v-model="form.floorNo"
                                class="form-control input-sm"
                                placeholder="Enter Floor No"
                            />
                        </div>
                    </div>
                    <div class="row g-1">
                        <div class="col-md-6 mb-1 col-12">
                            <label for="blockSector" class="form-label small-text">Block/Sector</label>
                            <input
                                type="text"
                                id="blockSector"
                                v-model="form.blockSector"
                                class="form-control input-sm"
                                placeholder="Enter Block/Sector"
                            />
                        </div>
                        <div class="col-md-6 mb-1 col-12">
                            <label for="flatNo" class="form-label small-text">Flat No</label>
                            <input
                                type="text"
                                id="flatNo"
                                v-model="form.flatNo"
                                class="form-control input-sm"
                                placeholder="Enter Flat No"
                            />
                        </div>
                    </div>

                    <div class="mb-1">
                        <label for="name" class="form-label small-text">Name *</label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="form-control input-sm"
                            required
                        />
                    </div>
                    <div class="mb-1">
                        <label for="number" class="form-label small-text">Phone Number *</label>
                        <input
                            type="text"
                            id="number"
                            v-model="form.number"
                            class="form-control input-sm"
                            required
                            placeholder="Enter Phone Number"
                        />
                    </div>
                    <div class="mb-1">
                        <label for="deliveryNotes" class="form-label small-text">Delivery Notes</label>
                        <textarea
                            id="deliveryNotes"
                            v-model="form.deliveryNotes"
                            class="form-control input-sm"
                            placeholder="Enter Delivery Notes"
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="btn w-full m-2"
                        style="background-color: #c21760; color: white"
                    >
                        Confirm Order 
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { mapActions } from "vuex";
export default {
    data() {
        const user = JSON.parse(localStorage.getItem("user") || "{}");

        return {
            form: {
                area: "",
                roadStreet: "",
                houseNo: "",
                floorNo: "",
                blockSector: "",
                flatNo: "",
                name: user.name || "", 
                user_id: user.id || "",
                number: "",
                deliveryNotes: "",
            },
            cartData: this.getCartData(),
        };
    },
    methods: {
        ...mapActions([
            "incrementItemQuantity",
            "decrementItemQuantity",
            "removeFromCart",
            "clearCart",
        ]),

        getCartData() {
            try {
                return JSON.parse(this.$route.query.cartData || "{}");
            } catch (error) {
                console.error("Error parsing cart data:", error);
                return { items: [], totalPrice: 0 };
            }
        },

        submitForm() {
            const orderData = {
                user_id: this.form.user_id,
                total_price: this.cartData.totalPrice,
                order_items: this.cartData.items.map((item) => ({
                    food_item_id: item.id,
                    quantity: item.quantity,
                    price: item.price,
                })),
                delivery_address: this.form,
            };

            axios
                .post("/api/orders", orderData)
                .then((response) => {
                    console.log("Order placed successfully:", response.data);
                    alert("Your order has been placed successfully!");
                    this.clearCart();
                    // Reset form
                    this.form = {
                        area: "",
                        roadStreet: "",
                        houseNo: "",
                        floorNo: "",
                        blockSector: "",
                        flatNo: "",
                        name: localStorage.getItem("userName") || "",
                        number: "",
                        deliveryNotes: "",
                    };
                })
                .catch((error) => {
                    console.error("Error placing order:", error);
                    alert("Error placing order, please try again.");
                });
        },
    },
};
</script>

<style scoped>
.small-text {
    font-size: 12px !important;
}
.input-sm {
    font-size: 10px !important;
    padding: 5px !important;
    border-radius: 0 !important;
}
.row.g-1 {
    margin-bottom: 6px !important;
}
.btn.w-full {
    width: 100%;
}
@media (max-width: 768px) {
    .col-md-6 {
        max-width: 100%;
    }
}
</style>
