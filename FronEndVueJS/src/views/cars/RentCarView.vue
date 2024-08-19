<template>
  <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Rent a Car</h2>

    <form @submit.prevent="submitRental">
      <div class="mb-4">
        <label for="startDate" class="block text-gray-700">Start Date</label>
        <input
          type="date"
          id="startDate"
          v-model="startDate"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <div class="mb-4">
        <label for="endDate" class="block text-gray-700">End Date</label>
        <input
          type="date"
          id="endDate"
          v-model="endDate"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <button
        type="submit"
        class="bg-yellow-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-yellow-600"
      >
        Confirm Rental
      </button>
    </form>

    <div v-if="availableDates.length" class="mt-6">
      <h3 class="text-lg font-semibold text-gray-800">Unavailable Dates:</h3>
      <ul class="list-disc pl-5">
        <li v-for="date in unavailableDates" :key="date" class="text-gray-600">
          {{ date }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { computed, onMounted, ref } from "vue";

export default {
  setup() {
    const route = useRoute();
    const router = useRouter();

    const startDate = ref("");
    const endDate = ref("");
    const availableDates = ref([]);
    const unavailableDates = ref([]);

    // Check available dates when component is mounted
    onMounted(async () => {
      const carId = route.params.carId;
      try {
        const response = await axios.get(
          `http://localhost:8000/api/rental/${carId}/available-dates`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        availableDates.value = response.data;
        unavailableDates.value = availableDates.value.map(
          (d) => `${d.start_date} to ${d.end_date}`
        );
      } catch (error) {
        console.error(
          "Error fetching available dates:",
          error.response?.data || error.message
        );
      }
    });

    const isDateAvailable = computed(() => {
      // Check if the selected date range is within the unavailable dates
      const start = new Date(startDate.value);
      const end = new Date(endDate.value);

      return !unavailableDates.value.some((range) => {
        const [startDate, endDate] = range
          .split(" to ")
          .map((d) => new Date(d));
        return start <= endDate && end >= startDate;
      });
    });

    const submitRental = async () => {
      const carId = route.params.carId;

      console.log(localStorage.getItem("token"));

      try {
        await axios.post(
          "http://localhost:8000/api/rent-car",
          {
            car_id: carId,
            start_date: startDate.value,
            end_date: endDate.value,
          },
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        router.push("/car-rental");
      } catch (error) {
        console.error(
          "Error renting car:",
          error.response?.data || error.message
        );
      }
    };

    return {
      startDate,
      endDate,
      submitRental,
      availableDates,
      unavailableDates,
      isDateAvailable,
    };
  },
};
</script>
