<template>
  <div class="p-4">
    <h1 class="text-2xl font-semibold mb-4">Your Rentals</h1>

    <!-- Rentals List -->
    <div v-if="rentals.length">
      <ul class="space-y-4">
        <li
          v-for="rental in rentals"
          :key="rental.id"
          class="bg-white border border-gray-300 rounded-lg shadow-lg p-6"
        >
          <h2 class="text-xl font-semibold text-gray-800 mb-2">
            {{ rental.car.brand }} - {{ rental.car.model }}
          </h2>
          <p class="text-gray-600">
            License Plate: {{ rental.car.license_plate }}
          </p>
          <p class="text-gray-600">Daily Rent: {{ rental.car.daily_rent }}</p>
          <p class="text-gray-600">Start Date: {{ rental.start_date }}</p>
          <p class="text-gray-600">End Date: {{ rental.end_date }}</p>
          <p class="text-gray-600 font-semibold">
            Total Cost:
            {{
              calculateTotalCost(
                rental.car.daily_rent,
                rental.start_date,
                rental.end_date
              ) | currency
            }}
          </p>
          <p class="text-gray-600 font-semibold">Status: {{ rental.status }}</p>
          <button
            v-if="rental.status === 'active'"
            @click="completeRental(rental.id)"
            class="bg-green-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-600"
          >
            Complete Rental
          </button>
        </li>
      </ul>
    </div>
    <div v-else>
      <p>No rentals found.</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

export default {
  setup() {
    const rentals = ref([]);
    const router = useRouter();

    // Fetch rentals when the component is mounted
    onMounted(async () => {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get(
          "http://localhost:8000/api/rental-history",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        rentals.value = response.data;
      } catch (error) {
        console.error(
          "Error fetching rentals:",
          error.response?.data || error.message
        );
        // Redirect to login if not authenticated
        if (error.response?.status === 401) {
          router.push("/login");
        }
      }
    });

    const calculateTotalCost = (dailyRent, startDate, endDate) => {
      const start = new Date(startDate);
      const end = new Date(endDate);
      const timeDiff = end - start;
      const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
      return dailyRent * days;
    };

    const completeRental = async (rentalId) => {
      try {
        const token = localStorage.getItem("token");
        await axios.post(
          `http://localhost:8000/api/rental/${rentalId}/complete`,
          {},
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        // Refresh rentals list
        const response = await axios.get(
          "http://localhost:8000/api/rental-history",
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        rentals.value = response.data;
      } catch (error) {
        console.error(
          "Error completing rental:",
          error.response?.data || error.message
        );
      }
    };

    return {
      rentals,
      calculateTotalCost,
      completeRental,
    };
  },
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
