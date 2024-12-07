<template>
  <div class="container">
    <div class="header">
      <h2>Data Produk</h2>
      <button type="button" class="btn save" @click="openCreateModal">
        Buat Produk
      </button>
    </div>
    <div v-if="loading" class="spinner">Loading...</div>
    <table v-else class="product-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Harga</th>
          <th>Stok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.name }}</td>
          <td>{{ product.price }}</td>
          <td>{{ product.stock }}</td>
          <td>
            <button class="btn edit" @click="openEditModal(product)">Edit</button>
            <button class="btn delete" @click="confirmDelete(product)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal for add/edit -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal">
        <div class="modal-header">
          <h3>{{ isEditing ? "Edit Produk" : "Tambah Produk" }}</h3>
          <button class="btn close" @click="closeModal">Close</button>
        </div>
        <form @submit.prevent="isEditing ? updateProduct() : addProduct()">
          <input
            v-model="currentProduct.name"
            class="input"
            placeholder="Nama"
            required
            minlength="3"
          />
          <input
            v-model.number="currentProduct.price"
            type="number"
            class="input"
            placeholder="Harga"
            min="0"
            required
          />
          <input
            v-model.number="currentProduct.stock"
            type="number"
            class="input"
            placeholder="Stok"
            min="0"
            required
          />
          <div class="form-actions">
            <button type="submit" class="btn save">
              {{ isEditing ? "Update" : "Tambah" }}
            </button>
            <button v-if="isEditing" type="button" class="btn cancel" @click="cancelEdit">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Confirmation Modal for Delete -->
    <div v-if="showConfirmModal" class="modal-overlay" @click.self="closeConfirmModal">
      <div class="modal">
        <div class="modal-header">
          <h3>Hapus Produk</h3>
        </div>
        <p class="confirm-message">Apakah Anda yakin ingin menghapus produk ini?</p>
        <div class="form-actions">
          <button class="btn delete" @click="deleteProduct(confirmProductId)">Hapus</button>
          <button class="btn cancel" @click="closeConfirmModal">Batal</button>
        </div>
      </div>
    </div>

    <div v-if="notification" class="notification">{{ notification }}</div>
  </div>
</template>

<script>
import api from "../axios";

export default {
  data() {
    return {
      products: [],
      isEditing: false,
      loading: true,
      notification: "",
      showModal: false,
      showConfirmModal: false,
      currentProduct: {
        id: null,
        name: "",
        price: 0,
        stock: 0,
      },
      confirmProductId: null,
    };
  },
  methods: {
    async fetchProducts() {
      this.loading = true;
      try {
        const response = await api.get("/products");
        this.products = response.data;
      } catch (error) {
        console.error("Error fetching products:", error);
        this.setNotification("Gagal memuat produk");
      } finally {
        this.loading = false;
      }
    },
    openCreateModal() {
      this.isEditing = false;
      this.currentProduct = { id: null, name: "", price: 0, stock: 0 };
      this.showModal = true;
    },
    openEditModal(product) {
      this.isEditing = true;
      this.currentProduct = { ...product };
      this.showModal = true;
    },
    async addProduct() {
      try {
        const response = await api.post("/products", this.currentProduct);
        this.products.push(response.data);
        this.closeModal();
        this.setNotification("Produk berhasil ditambahkan");
      } catch (error) {
        console.error("Error adding product:", error);
        this.setNotification("Gagal menambahkan produk");
      }
    },
    async updateProduct() {
      try {
        const response = await api.put(`/products/${this.currentProduct.id}`, this.currentProduct);
        const index = this.products.findIndex(product => product.id === this.currentProduct.id);
        if (index !== -1) {
          this.products.splice(index, 1, response.data);
        }
        this.closeModal();
        this.setNotification("Produk berhasil diperbarui");
      } catch (error) {
        console.error("Error updating product:", error);
        this.setNotification("Gagal memperbarui produk");
      }
    },
    async deleteProduct(productId) {
      try {
        await api.delete(`/products/${productId}`);
        this.products = this.products.filter(product => product.id !== productId);
        this.setNotification("Produk berhasil dihapus");
        this.closeConfirmModal();
      } catch (error) {
        console.error("Error deleting product:", error);
        this.setNotification("Gagal menghapus produk");
      }
    },
    confirmDelete(product) {
      this.confirmProductId = product.id;
      this.showConfirmModal = true;
    },
    closeConfirmModal() {
      this.showConfirmModal = false;
      this.confirmProductId = null;
    },
    cancelEdit() {
      this.closeModal();
    },
    closeModal() {
      this.showModal = false;
      this.currentProduct = { id: null, name: "", price: 0, stock: 0 };
      this.isEditing = false;
    },
    setNotification(message) {
      this.notification = message;
      setTimeout(() => {
        this.notification = "";
      }, 3000);
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

.notification {
  margin-top: 10px;
  padding: 10px;
  background-color: #dff0d8;
  color: #3c763d;
  border: 1px solid #d6e9c6;
  border-radius: 4px;
  text-align: center;
}

h2 {
  text-align: left;
  color: #333;
  margin-bottom: 0px;
}

.modal-header{
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.spinner {
  font-size: 1.5em;
  font-weight: bold;
  text-align: center;
  margin: 20px;
}
.confirm-message{
    margin: 40px 0px;
    text-align: center;
}
.product-table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  border-radius: 15px;
  background-color: #f9f9f9;
}

.product-table th,
.product-table td {
  padding: 10px;
  text-align: left;
  border: 1px solid #ddd;
}

.product-table th {
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
}

.btn.cancel {
  background-color: #9e9e9e;
}

.input {
  display: block;
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 1em;
}

.form-container {
  margin-top: 20px;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background-color: #fff;
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

.form-actions {
  display: flex;
  justify-content: space-between;
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 1010;
}

.modal .btn.close {
  background-color: black;
  color: #fff;
  display: block;
  border: none;
  border-radius: 10%;
  cursor: pointer;
  text-align: center;
}

.modal .btn.close:hover {
  background-color: gray;
}
</style>
