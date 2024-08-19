<template>
  <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold mb-4">Profile</h1>

    <form @submit.prevent="updateProfile">
      <div class="mb-4">
        <label for="name" class="block text-gray-700">Name</label>
        <input
          type="text"
          id="name"
          v-model="profile.name"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <div class="mb-4">
        <label for="email" class="block text-gray-700">Email</label>
        <input
          type="email"
          id="email"
          v-model="profile.email"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <div class="mb-4">
        <label for="address" class="block text-gray-700">Address</label>
        <input
          type="text"
          id="address"
          v-model="profile.address"
          class="border border-gray-300 rounded px-3 py-2 w-full"
        />
      </div>

      <div class="mb-4">
        <label for="phone" class="block text-gray-700">Phone</label>
        <input
          type="text"
          id="phone"
          v-model="profile.phone"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <div class="mb-4">
        <label for="driver_license" class="block text-gray-700"
          >Driver License</label
        >
        <input
          type="text"
          id="driver_license"
          v-model="profile.driver_license"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <!-- Password Update Section -->
      <div class="mb-4">
        <label for="password" class="block text-gray-700">New Password</label>
        <input
          type="password"
          id="password"
          v-model="password"
          class="border border-gray-300 rounded px-3 py-2 w-full"
        />
      </div>

      <div class="mb-4">
        <label for="password_confirmation" class="block text-gray-700"
          >Confirm New Password</label
        >
        <input
          type="password"
          id="password_confirmation"
          v-model="passwordConfirmation"
          class="border border-gray-300 rounded px-3 py-2 w-full"
        />
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600"
      >
        Update Profile
      </button>
    </form>
  </div>
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

export default {
  setup() {
    const profile = ref({
      name: "",
      email: "",
      address: "",
      phone: "",
      driver_license: "",
    });
    const password = ref("");
    const passwordConfirmation = ref("");
    const router = useRouter();

    // Fetch profile when the component is mounted
    onMounted(async () => {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get("http://localhost:8000/api/profile", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        profile.value = response.data;
      } catch (error) {
        console.error(
          "Error fetching profile:",
          error.response?.data || error.message
        );
        // Redirect to login if not authenticated
        if (error.response?.status === 401) {
          router.push("/login");
        }
      }
    });

    const updateProfile = async () => {
      try {
        if (password.value !== passwordConfirmation.value) {
          alert("Passwords do not match");
          return;
        }

        const token = localStorage.getItem("token");
        await axios.post(
          "http://localhost:8000/api/profile/update",
          {
            ...profile.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value,
          },
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        alert("Profile updated successfully");
      } catch (error) {
        console.error(
          "Error updating profile:",
          error.response?.data || error.message
        );
      }
    };

    return {
      profile,
      password,
      passwordConfirmation,
      updateProfile,
    };
  },
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
