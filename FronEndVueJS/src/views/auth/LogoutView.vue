<template>
  <div>Logging out...</div>
</template>

<script>
export default {
  mounted() {
    this.$router.push("/login");
  },
};
</script>
