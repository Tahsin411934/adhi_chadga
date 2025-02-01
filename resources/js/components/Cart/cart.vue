<template>
    <div>
        <!-- Cart Icon -->
        <div
            class="cart-icon position-relative d-flex align-items-center justify-content-around"
            @click="openModal"
        >
            <!-- Cart Icon with Badge -->
            <div
                class="cart-icon position-relative"
                style="z-index: 1"
                @click="openModal"
            >
                <i class="fas fa-shopping-cart fa-lg"></i>
                <span
                    v-if="cartItems.length > 0"
                    class="badge bg-danger position-absolute top-0 start-100 translate-middle"
                    style="padding-right: 17px"
                >
                    {{ totalItems }}
                </span>
            </div>

            <!-- Review Your Cart Text -->
        </div>

        <!-- Cart Modal -->
        <div v-if="isModalOpen" class="custom-modal-overlay">
            <div class="custom-modal">
                <div class="custom-modal-header">
                    <h5 class="modal-title">Your Cart</h5>
                    <button @click="closeModal" class="close-btn">X</button>
                </div>

                <div class="custom-modal-body">
                    <div
                        v-if="cartItems.length === 0"
                        class="text-muted text-center"
                    >
                        <p>Your cart is empty.</p>
                    </div>

                    <ul v-else class="list-group">
                        <li
                            v-for="item in cartItems"
                            :key="item.id"
                            class="list-group-item d-flex justify-content-between align-items-center"
                        >
                            <div class="d-flex w-100 align-items-center">
                                <!-- Item Name with fixed width -->
                                <div
                                    class="item-name me-2"
                                    style="flex: 1; min-width: 150px"
                                >
                                    <strong>{{ item.name }}</strong>
                                    <div class="text-muted small">
                                        ৳ {{ item.price }} (each)
                                    </div>

                                    <!-- Display availability -->
                                    <div class="text-muted small">
                                        <span
                                            v-if="
                                                isAvailableToday(
                                                    JSON.parse(
                                                        item.available_day
                                                    )
                                                )
                                            "
                                            >Available Today</span
                                        >
                                        <span v-else>
                                            Next Available:
                                            {{
                                                findClosestDay(
                                                    JSON.parse(
                                                        item.available_day
                                                    )
                                                )
                                            }}
                                            ({{
                                                calculateClosestDate(
                                                    findClosestDay(
                                                        JSON.parse(
                                                            item.available_day
                                                        )
                                                    )
                                                )
                                            }})
                                        </span>
                                    </div>

                                    <!-- Dropdown for selecting available day with corresponding date -->
                                    <div
                                        class="d-flex justify-content-between w-100"
                                        v-if="
                                            JSON.parse(item.available_day)
                                                .length > 1
                                        "
                                    >
                                        <span>Select Date: </span>
                                        <select
                                            v-model="item.selectedDay"
                                            class="form-select form-select-sm mt-2"
                                        >
                                            <option
                                                v-for="(
                                                    day, index
                                                ) in parsedAvailableDays(item)"
                                                :key="index"
                                                :value="day.name"
                                            >
                                                {{ day.name }} - {{ day.date }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Quantity controls -->
                                <div
                                    class="quantity-controls d-flex align-items-center me-3"
                                >
                                    <button
                                        class="btn btn-light btn-sm"
                                        @click="decrementItem(item.id)"
                                    >
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="mx-2">{{
                                        item.quantity
                                    }}</span>
                                    <button
                                        class="btn btn-light btn-sm"
                                        @click="incrementItem(item.id)"
                                    >
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <!-- Price and Remove button -->
                                <div class="d-flex align-items-center">
                                    <span class="me-3"
                                        >৳
                                        {{ item.price * item.quantity }}</span
                                    >
                                    <button
                                        class="btn btn-danger btn-sm"
                                        @click="removeFromCart(item.id)"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="d-flex justify-content-end m-2">
                        <h5>Total: ৳ {{ cartTotalPrice }}</h5>
                    </div>
                </div>

                <div class="custom-modal-footer">
                    <div v-if="cartItems.length > 0" class="w-100 text-end">
                        <button
                            @click="closeModal"
                            class="close-btn m-1 btn btn-sm bg-secondary text-white"
                        >
                            Close
                        </button>
                        <button
                            class="btn btn-primary btn-sm me-2"
                            @click="submitCart"
                        >
                            Order Now
                        </button>
                        <button
                            class="btn btn-danger btn-sm"
                            @click="clearCart"
                        >
                            Clear Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
    name: "Cart",
    data() {
        return {
            isModalOpen: false,
        };
    },
    computed: {
        ...mapGetters(["cartItems", "cartTotalPrice"]),
        totalItems() {
            return this.cartItems.reduce(
                (total, item) => total + item.quantity,
                0
            );
        },
    },
    methods: {
        ...mapActions([
            "incrementItemQuantity",
            "decrementItemQuantity",
            "removeFromCart",
            "clearCart",
        ]),
        openModal() {
            this.isModalOpen = true;
            // Set default selectedDay for each item when the modal is opened
            this.cartItems.forEach((item) => {
                if (!item.selectedDay) {
                    item.selectedDay = this.findClosestDay(
                        JSON.parse(item.available_day)
                    );
                }
            });
        },
        closeModal() {
            this.isModalOpen = false;
        },
        incrementItem(id) {
            this.incrementItemQuantity(id);
        },
        decrementItem(id) {
            this.decrementItemQuantity(id);
        },
        submitCart() {
            if (this.cartItems.length === 0) {
                console.log("Cart is empty. No data to submit.");
                return;
            }

            const cartData = {
                items: this.cartItems.map((item) => ({
                    id: item.id,
                    name: item.name,
                    quantity: item.quantity,
                    total: item.price * item.quantity,
                    selectedDay: item.selectedDay, // Include selected day in the submitted data
                })),
                totalPrice: this.cartTotalPrice,
            };

            this.$router.push({
                path: "/order/confirm",
                query: {
                    cartData: JSON.stringify(cartData),
                },
            });
            this.closeModal();
        },

        // Check if the item is available today
        isAvailableToday(availableDays) {
            const daysOfWeek = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ];
            const today = new Date();
            const currentDay = daysOfWeek[today.getDay()];
            return availableDays.some((day) => day === currentDay);
        },

        // Find the closest available day
        findClosestDay(availableDays) {
            const daysOfWeek = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ];
            const today = new Date();
            const currentDayIndex = today.getDay(); // 0 (Sunday) to 6 (Saturday)

            // Find the closest day
            const closestDay = availableDays.reduce((prev, curr) => {
                const prevDiff =
                    (daysOfWeek.indexOf(prev) - currentDayIndex + 7) % 7;
                const currDiff =
                    (daysOfWeek.indexOf(curr) - currentDayIndex + 7) % 7;
                return currDiff < prevDiff ? curr : prev;
            });

            return closestDay;
        },

        // Calculate the closest date for the closest day
        calculateClosestDate(closestDay) {
            const daysOfWeek = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ];
            const today = new Date();
            const currentDayIndex = today.getDay();
            const closestDayIndex = daysOfWeek.indexOf(closestDay);

            // Calculate the date for the closest day
            const diff = (closestDayIndex - currentDayIndex + 7) % 7;
            const closestDate = new Date(today);
            closestDate.setDate(today.getDate() + diff);

            return closestDate.toDateString();
        },

        // Parse available day and show day with corresponding date
        parsedAvailableDays(item) {
            const availableDays = JSON.parse(item.available_day);
            const daysOfWeek = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday",
            ];
            const today = new Date();
            const currentDayIndex = today.getDay();

            return availableDays.map((day) => {
                const dayIndex = daysOfWeek.indexOf(day);
                const diff = (dayIndex - currentDayIndex + 7) % 7;
                const closestDate = new Date(today);
                closestDate.setDate(today.getDate() + diff);
                return {
                    name: day,
                    date: closestDate.toDateString(),
                };
            });
        },
    },
};
</script>

<style scoped>
/* Add your styles for the cart modal */
.custom-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.custom-modal {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    max-width: 600px;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}

.custom-modal-body {
    flex: 1;
    overflow-y: auto;
    margin-top: 10px;
    margin-bottom: 10px;
}

.custom-modal-footer {
    padding-top: 10px;
    border-top: 1px solid #ddd;
    display: flex;
    justify-content: flex-end;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-controls button {
    padding: 5px 10px;
}

.cart-icon {
    position: relative;
    cursor: pointer;
}

.cart-icon .badge {
    position: absolute;
    top: 0;
    right: 0;
}

.item-name {
    display: flex;
    flex-direction: column;
}

.form-select {
    width: auto;
}

/* Make text smaller and modal responsive for small devices */
@media (max-width: 576px) {
    .custom-modal {
        padding: 10px;
    }

    .custom-modal-header h5 {
        font-size: 14px;
    }

    .custom-modal-body .item-name {
        font-size: 12px;
    }

    .custom-modal-footer h5 {
        font-size: 14px;
    }

    .quantity-controls span {
        font-size: 12px;
    }

    .custom-modal-body .list-group-item {
        padding: 8px 10px;
    }

    .cart-icon .badge {
        font-size: 10px;
    }
}
</style>
