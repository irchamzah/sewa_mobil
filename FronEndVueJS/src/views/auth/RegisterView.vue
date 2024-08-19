<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
      <h1 class="text-2xl font-semibold mb-6 text-gray-800">Register</h1>
      <form @submit.prevent="register">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 font-medium mb-2"
            >Full Name:</label
          >
          <input
            type="text"
            v-model="form.name"
            id="name"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
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
        <div class="mb-4">
          <label for="address" class="block text-gray-700 font-medium mb-2"
            >Address:</label
          >
          <input
            type="text"
            v-model="form.address"
            id="address"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
        <div class="mb-4">
          <label for="phone" class="block text-gray-700 font-medium mb-2"
            >Phone:</label
          >
          <input
            type="text"
            v-model="form.phone"
            id="phone"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
        <div class="mb-4">
          <label
            for="driverLicense"
            class="block text-gray-700 font-medium mb-2"
            >Driver License:</label
          >
          <input
            type="text"
            v-model="form.driverLicense"
            id="driverLicense"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>
        <button
          type="submit"
          class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Register
        </button>
        <div v-if="error" class="mt-4 text-red-600">{{ error }}</div>
        <div v-if="success" class="mt-4 text-green-600">{{ success }}</div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "RegisterView",
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        address: "",
        phone: "",
        driverLicense: "",
      },
      error: null,
      success: null,
    };
  },
  methods: {
    async register() {
      try {
        const response = await axios.post(
          "http://localhost:8000/api/register",
          this.form
        );
        this.success = "Registration successful!";
        this.error = null;

        console.log(response.data);

        this.$router.push("/login");
      } catch (error) {
        this.error = error.response.data.message || "Registration failed.";
        this.success = null;
      }
    },
  },
};
</script>

<style scoped>
/* Add your styles here */
</style>
