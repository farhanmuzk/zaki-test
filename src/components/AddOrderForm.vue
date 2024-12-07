<template>
  <div class="container">
    <div class="header">
      <h2>Tambah Pesanan</h2>
    </div>

    <div v-if="loading" class="spinner">Loading...</div>

    <form v-if="!loading" @submit.prevent="addOrder">
      <div class="form-group">
        <label>Customer Name</label>
        <input
          v-model="order.customer_name"
          class="input"
          placeholder="Customer Name"
          required
        />
      </div>
      <div class="form-group">
        <label>Order Date</label>
        <input v-model="order.order_date" type="date" class="input" required />
      </div>
      <div v-for="(item, index) in orderItems" :key="index" class="form-group item-group">
        <label>Product</label>
        <select
          v-model="item.product_id"
          class="input"
          @change="updateProductDetails(item)"
        >
          <option value="">Pilih Produk</option>
          <option v-for="product in products" :value="product.id" :key="product.id">
            {{ product.name }}
          </option>
        </select>
        <label>Quantity</label>
        <input
          v-model.number="item.quantity"
          type="number"
          class="input"
          placeholder="Quantity"
          min="1"
          :max="getProductStock(item.product_id)"
          required
        />
        <button type="button" class="btn delete" @click="removeItem(index)">
          Hapus Item
        </button>
      </div>
      <div class="form-actions">
        <button type="button" class="btn add-item" @click="addItem">Tambah Item</button>
        <button type="submit" class="btn save">Tambah Pesanan</button>
      </div>
    </form>

    <div v-if="notification" class="notification">{{ notification }}</div>
  </div>
</template>
<script>
import api from "../axios";
import {} from "vue-router";

export default {
  data() {
    return {
      order: {
        customer_name: "",
        order_date: "",
      },
      products: [],
      orderItems: [],
      loading: false,
      notification: "",
    };
  },
  methods: {
    async fetchProducts() {
      this.loading = true;
      try {
        const { data } = await api.get("/products");
        this.products = data;
      } catch (error) {
        console.error("Error fetching products:", error);
        this.showNotification("Gagal memuat produk");
      } finally {
        this.loading = false;
      }
    },
    getProductStock(productId) {
      const product = this.products.find((p) => p.id === productId);
      return product ? product.stock : 0;
    },
    addItem() {
      this.orderItems.push({ product_id: null, quantity: 1 });
    },
    removeItem(index) {
      this.orderItems.splice(index, 1);
    },
    updateProductDetails(item) {
      const product = this.products.find((p) => p.id === item.product_id);
      if (product) {
        this.showNotification(
          `Produk dipilih: ${product.name}. Sisa stok: ${product.stock}`
        );
      }
    },

    showNotification(message) {
      this.notification = message;
      setTimeout(() => {
        this.notification = "";
      }, 3000);
    },
    async addOrder() {
      if (!this.order.customer_name || !this.order.order_date) {
        this.showNotification("Harap isi semua informasi pelanggan dan tanggal pesanan.");
        return;
      }

      if (
        this.orderItems.some(
          (item) =>
            !item.product_id ||
            item.quantity < 1 ||
            item.quantity > this.getProductStock(item.product_id)
        )
      ) {
        this.showNotification("Harap isi semua produk dengan quantity yang valid.");
        return;
      }

      const payload = {
        customer_name: this.order.customer_name,
        order_date: this.order.order_date,
        items: this.orderItems.map((item) => ({
          product_id: item.product_id,
          quantity: item.quantity,
        })),
      };

      try {
        this.loading = true;
        await api.post("/orders", payload);
        this.showNotification("Pesanan berhasil dibuat");

        for (let item of this.orderItems) {
          const product = this.products.find((p) => p.id === item.product_id);
          if (product) {
            const updatedProduct = {
              name: product.name,
              price: product.price,
              stock: product.stock - item.quantity,
            };
            await api.put(`/products/${product.id}`, updatedProduct);
          }
        }

        this.order = { customer_name: "", order_date: "" };
        this.orderItems = [];

        // Navigate to the /orders route after successful order creation
        this.$router.push("/orders");
      } catch (error) {
        console.error("Error saat mengirim pesanan:", error);
        if (error.response) {
          this.showNotification(`Terjadi kesalahan: ${error.response.data.message}`);
        } else {
          this.showNotification("Terjadi kesalahan saat mengirim data.");
        }
      } finally {
        this.loading = false;
      }
    },
  },
  mounted() {
    this.fetchProducts();
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

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  color: #333;
}

.input {
  display: block;
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1em;
}

.item-group {
  margin-top: 10px;
}

.btn {
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  color: #fff;
  font-size: 0.9em;
}

.btn.save {
  background-color: #2196f3;
}

.btn.delete {
  background-color: #f44336;
}

.btn.add-item {
  background-color: #4caf50;
  margin-top: 10px;
}

.form-actions {
  display: flex;
  justify-content: space-between;
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
</style>
