<script>
import FooterComponent from "./components/FooterComponent.vue";
import NavbarComponent from "./components/NavbarComponent.vue";

export default {
  components: {
    NavbarComponent,
    FooterComponent,
  },
};
</script>

<template>
  <div id="app">
    <NavbarComponent />
    <router-view></router-view>
    <FooterComponent />
  </div>
</template>

<style scoped></style>
