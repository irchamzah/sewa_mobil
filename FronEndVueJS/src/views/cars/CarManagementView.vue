<template>
  <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-2xl px-4 2xl:px-0 py-8 sm:py-12 lg:max-w-7xl">
      <div class="flex justify-start mb-6">
        <router-link to="/add-car">
          <button
            class="bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Add Car
          </button>
        </router-link>
      </div>
      <div class="flex flex-col">
        <div class="rounded-xl bg-white dark:bg-gray-800 p-6 shadow-lg">
          <div
            class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3"
          >
            <div class="flex flex-col">
              <label for="brand" class="block text-gray-100 font-medium mb-1">
                Brand:
              </label>
              <input
                type="text"
                id="brand"
                v-model="filters.brand"
                @input="fetchCars"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:hover:text-white dark:hover:bg-gray-700"
              />
            </div>

            <div class="flex flex-col">
              <label for="model" class="block text-gray-100 font-medium mb-1">
                Model:
              </label>
              <input
                type="text"
                id="model"
                v-model="filters.model"
                @input="fetchCars"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:hover:text-white dark:hover:bg-gray-700"
              />
            </div>

            <div class="flex flex-col">
              <label
                for="availability"
                class="block text-gray-100 font-medium mb-1"
              >
                Availability:
              </label>
              <select
                id="availability"
                v-model="filters.availability"
                @change="fetchCars"
                class="w-full md:w-auto flex items-center justify-center py-2 px-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
              >
                <option value="">All</option>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-12">
    <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
      <!-- Heading -->
      <div
        class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8"
      >
        <div>
          <h2
            class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl"
          >
            Car List
          </h2>
        </div>
      </div>

      <!-- Loading -->
      <!-- Skeleton Loader -->
      <div
        v-if="isLoading"
        class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4"
      >
        <div
          v-for="i in 8"
          :key="i"
          class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 animate-pulse"
        >
          <div class="h-56 bg-gray-300 dark:bg-gray-700"></div>
          <div class="mt-4 h-4 bg-gray-300 dark:bg-gray-700"></div>
          <div class="mt-4 h-4 bg-gray-300 dark:bg-gray-700"></div>
          <div class="mt-4 h-8 bg-gray-300 dark:bg-gray-700"></div>
        </div>
      </div>

      <!-- Cars List -->
      <div
        v-if="cars.length"
        class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4"
      >
        <div
          v-for="car in cars"
          :key="car.id"
          class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
        >
          <div class="h-56 w-full">
            <img
              v-if="car.photos.length"
              :src="`http://localhost:8000/storage/${car.photos[0]}`"
              alt="Car Image"
              class="mx-auto h-full object-cover cursor-pointer"
            />
          </div>
          <div class="pt-6 flex flex-col">
            <div>
              <h2
                class="text-lg font-semibold leading-tight text-gray-900 dark:text-white"
              >
                {{ car.brand }} - {{ car.model }}
              </h2>
            </div>
            <div class="mt-2 flex items-center gap-2">
              <p
                class="text-sm font-medium"
                :class="{
                  'text-green-600': car.availability === 'available',
                  'text-red-600': car.availability === 'unavailable',
                }"
              >
                {{ car.availability }}
              </p>
            </div>
            <div class="mt-4 flex justify-between">
              <button
                @click="editCar(car.id)"
                class="inline-flex items-center rounded-lg bg-yellow-500 px-5 py-2 text-sm font-medium text-white hover:bg-yellow-600 focus:outline-none focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-800 w-min"
              >
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <p class="text-gray-700 dark:text-gray-300">No cars available.</p>
      </div>

      <!-- Pagination -->
      <div class="my-20 flex justify-center">
        <nav aria-label="Page navigation example">
          <ul class="inline-flex -space-x-px text-base h-10">
            <li>
              <button
                @click.prevent="fetchCars(page - 1)"
                :disabled="page <= 1"
                class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                :aria-disabled="page <= 1"
              >
                Previous
              </button>
            </li>

            <li v-for="i in totalPages" :key="i">
              <button
                @click.prevent="fetchCars(i)"
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
                @click.prevent="fetchCars(page + 1)"
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
  </section>
</template>

<script>
import axios from "axios";

export default {
  name: "CarManagementView",
  data() {
    return {
      cars: [],
      filters: {
        brand: "",
        model: "",
        availability: "",
      },
      page: 1,
      totalPages: 1,
      isLoading: false,
    };
  },
  methods: {
    async fetchCars(page = 1) {
      this.isLoading = true;
      try {
        const params = {
          ...this.filters,
          page,
        };
        const token = localStorage.getItem("token");
        const response = await axios.get("http://localhost:8000/api/cars", {
          params,
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.cars = response.data.data; // 'data' contains the actual cars
        this.page = response.data.current_page;
        this.totalPages = response.data.last_page;
      } catch (error) {
        console.error("Error fetching cars:", error);
      } finally {
        this.isLoading = false; // Selesai loading
      }
    },
    editCar(id) {
      this.$router.push(`/edit-car/${id}`); // Navigate to edit car page
    },
  },
  mounted() {
    this.fetchCars();
  },
};
</script>

<style scoped>
/* Optional additional styling */
</style>
