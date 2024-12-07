<!-- src/components/Navbar.vue -->
<template>
  <nav>
    <ul>
      <li>
        <router-link to="/" :class="{ active: $route.path === '/' }">
          Products
        </router-link>
      </li>
      <li>
        <router-link to="/orders" :class="{ active: $route.path === '/orders' }">
          Orders
        </router-link>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: "NavigationBar",
};
</script>

<style scoped>
nav {
  background-color: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 10px;
  border-radius: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: fit-content;
  margin: auto;
}
ul {
  display: flex;
  gap: 10px;
  list-style: none;
  padding: 0;
}
li {
  display: inline;
  text-decoration: none;
  padding: 10px;
}
li a.active {
  padding: 10px 15px 10px 15px;
  border-radius: 10px;
  background-color: #03b003;
}
router-link {
  color: white !important; /* Pastikan teks berwarna putih */
  text-decoration: none;
}
router-link.active {
  font-weight: bold;
}
a {
  text-decoration: none;
  color: black;
  font-family: "circular";
}
</style>
