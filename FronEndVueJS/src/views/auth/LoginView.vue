<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
      <h1 class="text-2xl font-semibold mb-6 text-gray-800">Login</h1>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label for="email" class="block text-gray-700 font-medium mb-2"
            >Email:</label
          >
          <input
            type="email"
            v-model="form.email"
            id="email"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
        <div class="mb-4">
          <label for="password" class="block text-gray-700 font-medium mb-2"
            >Password:</label
          >
          <input
            type="password"
            v-model="form.password"
            id="password"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
        <button
          type="submit"
          class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Login
        </button>
        <div v-if="error" class="mt-4 text-red-600">{{ error }}</div>
        <div v-if="success" class="mt-4 text-green-600">{{ success }}</div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapActions } from "vuex";

export default {
  name: "LoginView",
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      error: null,
      success: null,
    };
  },
  methods: {
    ...mapActions(["login"]),
    async login() {
      try {
        const response = await axios.post(
          "http://localhost:8000/api/login",
          this.form
        );
        this.success = "Login successful!";
        this.error = null;
        // Store token or user info if needed
        localStorage.setItem("token", response.data.token);
        this.$store.dispatch("login");
        this.$router.push("/home"); // Redirect to home page or another page after successful login
      } catch (error) {
        this.error = error.response.data.message || "Login failed.";
        this.success = null;
      }
    },
  },
};
</script>

<style scoped>
/* Optional additional styling */
</style>
