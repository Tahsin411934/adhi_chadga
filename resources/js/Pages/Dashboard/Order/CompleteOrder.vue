<template>
    <div>
        <h2>Pending Orders</h2>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Order Items</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="order in data" :key="order.id">
                    <td>{{ order.id }}</td>
                    <td>{{ order.user_id }}</td>
                    <td>{{ order.total_price }}</td>
                    <td>{{ order.status }}</td>
                    <td>
                        <ul class="list-unstyled">
                            <li v-for="item in order.order_items" :key="item.id">
                                 {{ item.food_item_name }} | 
                                <strong>Quantity:</strong> {{ item.quantity }} | 
                                <strong>Price:</strong> {{ item.price }}
                            </li>
                        </ul>
                    </td>
                    <td>
                        
                       

                        <!-- Delete Button -->
                        <button 
                            class="btn btn-danger " style="margin-left: 10px;" 
                            @click="deleteOrder(order.id)">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axiosInstance from '../../../utils/axiosInstance';

export default {
    name: "completeOrder",
    data() {
        return {
            data: []
        };
    },
    methods: {
        async fetchData() {
            const res = await axiosInstance.get("/api/completeOrders");
            this.data = res.data;
            console.log(res.data);
        },

        // Shift Order Method
       

        // Complete Order Method
        async completeOrder(orderId) {
            try {
                const res = await axiosInstance.patch(`/api/orders/${orderId}`, { status: 'confirmed' });
                this.fetchData(); // Refresh the orders list
                alert('Order completed successfully!');
            } catch (error) {
                console.error('Error completing order:', error);
                alert('Failed to complete order.');
            }
        },

        // Delete Order Method
        async deleteOrder(orderId) {
            try {
                const res = await axiosInstance.delete(`/api/orders/${orderId}`);
                this.fetchData(); // Refresh the orders list
                alert('Order deleted successfully!');
            } catch (error) {
                console.error('Error deleting order:', error);
                alert('Failed to delete order.');
            }
        }
    },
    mounted() {
        this.fetchData();
    }
};
</script>

<style lang="scss" scoped>
/* Optional: Add custom styling here if needed */
</style>
