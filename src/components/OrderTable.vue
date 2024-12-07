<template>
  <div class="container">
    <div class="header">
      <h2>Data Pesanan</h2>
      <router-link to="/add-order" class="btn save"> Buat Pesanan</router-link>
    </div>
    <div v-if="loading" class="spinner">Loading...</div>
    <table v-else class="order-table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer Name</th>
          <th>Order Date</th>
          <th>Total Items</th>
          <th>Total Price</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="order in orders" :key="order.id" @click="selectOrder(order)" class="clickable-row">
          <td>{{ order.id }}</td>
          <td>{{ order.customer_name }}</td>
          <td>{{ order.order_date }}</td>
          <td>{{ order.total_items }}</td>
          <td>{{ order.total_price }}</td>
        </tr>
      </tbody>
    </table>

    <div v-if="notification" class="notification">{{ notification }}</div>

    <div v-if="selectedOrder">
      <h3>Detail Pesanan</h3>
      <table class="order-details-table">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in selectedOrder.items" :key="item.id">
            <td>{{ item.product.name }}</td>
            <td>{{ item.quantity }}</td>
            <td>{{ item.price }}</td>
            <td>{{ (item.quantity * item.price).toFixed(2) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import api from "../axios";

export default {
  data() {
    return {
      orders: [],
      selectedOrder: null,
      loading: true,
      notification: "",
    };
  },
  methods: {
    async fetchOrders() {
      this.loading = true;
      try {
        const response = await api.get("/orders");
        this.orders = response.data.map((order) => {
          let totalItems = 0;
          let totalPrice = 0;
          order.items.forEach((item) => {
            totalItems += item.quantity;
            totalPrice += item.quantity * item.price;
          });

          return {
            ...order,
            total_items: totalItems,
            total_price: totalPrice.toFixed(2),
          };
        });
        this.setNotification("Pesanan berhasil dimuat");
      } catch (error) {
        console.error("Error fetching orders:", error);
        this.setNotification("Gagal memuat pesanan");
      } finally {
        this.loading = false;
      }
    },

    selectOrder(order) {
      this.selectedOrder = order;
    },

    setNotification(message) {
      this.notification = message;
      setTimeout(() => {
        this.notification = "";
      }, 3000);
    },
  },
  mounted() {
    this.fetchOrders();
  },
};
</script>

<style>
.container {
  max-width: 100%;
  margin: 20px auto;
  padding: 20px;
  border-radius: 8px;
  font-family: Arial, sans-serif;
}

.clickable-row{
  cursor: pointer;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.notification {
  margin-top: 10px;
  padding: 10px;
  background-color: #dff0d8;
  color: #3c763d;
  border: 1px solid #d6e9c6;
  border-radius: 4px;
  text-align: center;
}

.spinner {
  font-size: 1.5em;
  font-weight: bold;
  text-align: center;
  margin: 20px;
}

.order-table,
.order-details-table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  border-radius: 15px;
  background-color: #f9f9f9;
}

.order-table th,
.order-table td,
.order-details-table th,
.order-details-table td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
}

.order-table th,
.order-details-table th {
  background-color: #f4f4f4;
}

.btn {
  padding: 8px 12px;
  margin: 0 4px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color: #fff;
  font-size: 0.9em;
}

.btn.edit {
  background-color: #4caf50;
}

.btn.delete {
  background-color: #f44336;
}

.btn.save {
  background-color: #2196f3;
  text-decoration: none;
}

.btn.cancel {
  background-color: #9e9e9e;
}

.form-actions {
  display: flex;
  justify-content: space-between;
}
</style>
