<template>
  <section class="bg-white dark:bg-gray-900">
    <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
      <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
        Update Profile
      </h2>
      <form @submit.prevent="updateProfile">
        <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
          <!-- Name -->
          <div class="w-full">
            <label
              for="name"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Name</label
            >
            <input
              type="text"
              id="name"
              v-model="profile.name"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Type your name"
              required
            />
          </div>

          <!-- Email -->
          <div class="w-full">
            <label
              for="email"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Email</label
            >
            <input
              type="email"
              id="email"
              v-model="profile.email"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Type your email"
              required
            />
          </div>

          <!-- Address -->
          <div class="w-full col-span-2">
            <label
              for="address"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Address</label
            >
            <input
              type="text"
              id="address"
              v-model="profile.address"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Type your address"
            />
          </div>

          <!-- Phone -->
          <div class="w-full">
            <label
              for="phone"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Phone</label
            >
            <input
              type="text"
              id="phone"
              v-model="profile.phone"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Type your phone number"
              required
            />
          </div>

          <!-- Driver License -->
          <div class="w-full">
            <label
              for="driver_license"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Driver License</label
            >
            <input
              type="text"
              id="driver_license"
              v-model="profile.driver_license"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Type your driver license"
              required
            />
          </div>

          <!-- New Password -->
          <div class="w-full">
            <label
              for="password"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >New Password</label
            >
            <input
              type="password"
              id="password"
              v-model="password"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Type new password"
            />
          </div>

          <!-- Confirm New Password -->
          <div class="w-full">
            <label
              for="password_confirmation"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Confirm New Password</label
            >
            <input
              type="password"
              id="password_confirmation"
              v-model="passwordConfirmation"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Confirm new password"
            />
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <button
            type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Update Profile
          </button>
        </div>
      </form>
    </div>
  </section>
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
