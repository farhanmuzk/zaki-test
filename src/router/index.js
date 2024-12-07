import { createRouter, createWebHistory } from 'vue-router';
import ProductPage from '../views/ProductPage.vue';
import OrderPage from '../views/OrderPage.vue';
import AddOrderPage from '../views/AddOrderPage.vue';

const routes = [
  { path: '/', component: ProductPage },
  { path: '/orders', component: OrderPage },
  { path: '/add-order', component: AddOrderPage },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
