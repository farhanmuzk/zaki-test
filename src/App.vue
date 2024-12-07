<!-- src/App.vue -->
<template>
  <div id="app">
    <Navbar />
    <router-view />
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue';

export default {
  name: 'App',
  components: {
    Navbar
  }
};
</script>

<style>
    *{
        margin: 0px;
    }
    #app{
        margin: 0px;
        padding: 10px;
        background-color: #EEEEEE;
        height: 100vh;
    }
</style>
