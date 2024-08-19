<template>
  <section
    class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-12 min-h-[600px]"
  >
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <!-- Tabel Rental -->
        <table
          v-if="!isLoading"
          class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
        >
          <thead
            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
          >
            <tr class="text-center">
              <th scope="col" class="px-6 py-3">Image</th>
              <th scope="col" class="px-6 py-3">Car</th>
              <th scope="col" class="px-6 py-3">License Plate</th>
              <th scope="col" class="px-6 py-3">Daily Rent</th>
              <th scope="col" class="px-6 py-3">Start Date</th>
              <th scope="col" class="px-6 py-3">End Date</th>
              <th scope="col" class="px-6 py-3">Total Cost</th>
              <th scope="col" class="px-6 py-3">Status</th>
              <th scope="col" class="px-6 py-3">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="rental in rentals"
              :key="rental.id"
              class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center"
            >
              <td class="p-4">
                <img
                  :src="`http://localhost:8000/storage/${rental.car.photos[0]}`"
                  class="w-16 md:w-32 max-w-full max-h-full"
                  alt="Car Image"
                />
              </td>
              <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                {{ rental.car.brand }} - {{ rental.car.model }}
              </td>
              <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                {{ rental.car.license_plate }}
              </td>
              <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                {{
                  Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                  }).format(rental.car.daily_rent)
                }}
              </td>
              <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                {{ rental.start_date }}
              </td>
              <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                {{ rental.end_date }}
              </td>
              <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                {{
                  Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                  }).format(
                    calculateTotalCost(
                      rental.car.daily_rent,
                      rental.start_date,
                      rental.end_date
                    )
                  )
                }}
              </td>
              <td class="px-6 py-4">
                <span
                  class="px-2 py-1 text-xs font-semibold rounded flex justify-center"
                  :class="{
                    'bg-blue-500 text-white': rental.status === 'completed',
                    'bg-green-500 text-white': rental.status === 'active',
                  }"
                >
                  {{ rental.status }}
                </span>
              </td>
              <td class="px-6 py-4">
                <button
                  v-if="rental.status === 'active'"
                  @click="completeRental(rental.id)"
                  class="font-medium text-yellow-600 dark:text-yellow-500 hover:underline"
                >
                  Complete Rental
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Skeleton Loader -->
        <div v-if="isLoading" class="animate-pulse">
          <div
            v-for="n in 4"
            :key="n"
            class="flex justify-between p-4 bg-white border-b dark:bg-gray-800 dark:border-gray-700"
          >
            <div
              class="w-16 md:w-32 h-16 md:h-32 bg-gray-300 dark:bg-gray-700"
            ></div>
            <div class="flex-1 ml-4">
              <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded mb-2"></div>
              <div
                class="h-6 bg-gray-300 dark:bg-gray-700 rounded mb-2 w-1/2"
              ></div>
              <div
                class="h-6 bg-gray-300 dark:bg-gray-700 rounded mb-2 w-1/3"
              ></div>
            </div>
            <div class="flex-1 ml-4">
              <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded mb-2"></div>
              <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded mb-2"></div>
              <div
                class="h-6 bg-gray-300 dark:bg-gray-700 rounded mb-2 w-1/2"
              ></div>
            </div>
            <div class="flex-1 ml-4">
              <div class="h-6 bg-gray-300 dark:bg-gray-700 rounded mb-2"></div>
              <div
                class="h-6 bg-gray-300 dark:bg-gray-700 rounded mb-2 w-1/2"
              ></div>
            </div>
          </div>
        </div>

        <div v-if="!rentals.length && !isLoading" class="p-6">
          <p class="text-gray-600 dark:text-gray-400">No rentals found.</p>
        </div>

        <!-- Pagination -->
        <div class="my-20 flex justify-center">
          <nav aria-label="Page navigation example">
            <ul class="inline-flex -space-x-px text-base h-10">
              <li>
                <button
                  @click.prevent="fetchRentals(page - 1)"
                  :disabled="page <= 1"
                  class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  :aria-disabled="page <= 1"
                >
                  Previous
                </button>
              </li>

              <li v-for="i in totalPages" :key="i">
                <button
                  @click.prevent="fetchRentals(i)"
                  :class="{
                    'bg-gray-100 text-gray-700': i === page,
                    'text-gray-500': i !== page,
                  }"
                  class="flex items-center justify-center px-4 h-10 leading-tight border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                  {{ i }}
                </button>
              </li>

              <li>
                <button
                  @click.prevent="fetchRentals(page + 1)"
                  :disabled="page >= totalPages"
                  class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                  :aria-disabled="page >= totalPages"
                >
                  Next
                </button>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";

export default {
  setup() {
    const rentals = ref([]);
    const isLoading = ref(true);
    const page = ref(1);
    const totalPages = ref(1); // Initialize with 1, update with actual total pages from API

    const fetchRentals = async (pageNum) => {
      isLoading.value = true;
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get(
          `http://localhost:8000/api/rental-history?page=${pageNum}`,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );
        rentals.value = response.data.data; // Assuming API returns paginated data in 'data' key
        page.value = response.data.current_page;
        totalPages.value = response.data.last_page;
      } catch (error) {
        console.error(
          "Error fetching rentals:",
          error.response?.data || error.message
        );
      } finally {
        isLoading.value = false;
      }
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
        fetchRentals(page.value);
      } catch (error) {
        console.error(
          "Error completing rental:",
          error.response?.data || error.message
        );
      }
    };

    const calculateTotalCost = (dailyRent, startDate, endDate) => {
      const start = new Date(startDate);
      const end = new Date(endDate);
      const diffInTime = end.getTime() - start.getTime();
      const diffInDays = diffInTime / (1000 * 3600 * 24);
      return diffInDays * dailyRent;
    };

    onMounted(() => {
      fetchRentals(page.value);
    });

    return {
      rentals,
      isLoading,
      page,
      totalPages,
      fetchRentals,
      completeRental,
      calculateTotalCost,
    };
  },
};
</script>

<style scoped>
/* Add your custom styles here */
</style>
