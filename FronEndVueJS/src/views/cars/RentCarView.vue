<template>
  <section
    class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-12 min-h-[600px]"
  >
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
      <div class="max-w-sm mx-auto p-6 bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-200 mb-4">Rent a Car</h2>

        <form @submit.prevent="submitRental">
          <div class="mb-6">
            <label
              for="startDate"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Start Date</label
            >
            <input
              type="date"
              id="startDate"
              v-model="startDate"
              class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              required
            />
          </div>

          <div class="mb-6">
            <label
              for="endDate"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >End Date</label
            >
            <input
              type="date"
              id="endDate"
              v-model="endDate"
              class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              required
            />
          </div>

          <p v-if="!isDateAvailable" class="text-red-500 mb-4">
            The selected dates are not available. Please choose different dates.
          </p>

          <button
            type="submit"
            :class="{
              'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-400 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-500':
                isDateAvailable,
              'bg-gray-400 cursor-not-allowed': !isDateAvailable,
            }"
            :disabled="!isDateAvailable"
            class="text-white px-4 py-2 rounded-md shadow-sm focus:outline-none focus:ring-2 dark:focus:ring-yellow-500"
          >
            Confirm Rental
          </button>
        </form>

        <div v-if="unavailableDates.length" class="mt-6">
          <h3 class="text-lg font-semibold text-gray-200 mb-2">
            Unavailable Dates:
          </h3>
          <ul class="list-disc pl-5">
            <li
              v-for="date in unavailableDates"
              :key="date"
              class="text-gray-600 dark:text-gray-400"
            >
              {{ date }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>
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
    const unavailableDates = ref([]);

    // Check unavailable dates when component is mounted
    onMounted(async () => {
      const carId = route.params.carId;
      try {
        const response = await axios.get(
          `http://localhost:8000/api/rental/${carId}/unavailable-dates`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        unavailableDates.value = response.data.map(
          (d) => `${d.start_date} to ${d.end_date}`
        );
      } catch (error) {
        console.error(
          "Error fetching unavailable dates:",
          error.response?.data || error.message
        );
      }
    });

    const isDateAvailable = computed(() => {
      const start = new Date(startDate.value);
      const end = new Date(endDate.value);

      if (end <= start) {
        return false;
      }

      return !unavailableDates.value.some((range) => {
        const [rangeStart, rangeEnd] = range
          .split(" to ")
          .map((d) => new Date(d));
        return start <= rangeEnd && end >= rangeStart;
      });
    });

    const submitRental = async () => {
      const carId = route.params.carId;

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
      unavailableDates,
      isDateAvailable,
    };
  },
};
</script>
