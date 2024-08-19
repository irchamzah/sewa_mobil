<!-- src/views/Logout.vue -->
<template>
  <div>Logging out...</div>
</template>

<script>
export default {
  mounted() {
    // Lakukan proses logout di sini, seperti menghapus token dan mengarahkan ulang ke halaman login
    this.$router.push("/login");
  },
};
</script>
