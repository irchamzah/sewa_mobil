<template>
  <div class="p-4">
    <h1 class="text-2xl font-semibold mb-6">Car Management</h1>

    <!-- Add Car Button -->
    <div class="mb-4">
      <router-link to="/add-car">
        <button
          class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none"
        >
          Add Car
        </button>
      </router-link>
    </div>

    <!-- Filter Section -->
    <div class="mb-6">
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label for="brand" class="block text-gray-700">Brand:</label>
          <input
            type="text"
            id="brand"
            v-model="filters.brand"
            @input="fetchCars"
            class="border border-gray-300 rounded px-3 py-2 w-full"
          />
        </div>

        <div>
          <label for="model" class="block text-gray-700">Model:</label>
          <input
            type="text"
            id="model"
            v-model="filters.model"
            @input="fetchCars"
            class="border border-gray-300 rounded px-3 py-2 w-full"
          />
        </div>

        <div>
          <label for="availability" class="block text-gray-700"
            >Availability:</label
          >
          <select
            id="availability"
            v-model="filters.availability"
            @change="fetchCars"
            class="border border-gray-300 rounded px-3 py-2 w-full"
          >
            <option value="">All</option>
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Cars List -->
    <div v-if="cars.length">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div
          v-for="car in cars"
          :key="car.id"
          class="bg-white border border-gray-300 rounded-lg shadow-lg overflow-hidden"
        >
          <div class="p-6">
            <img
              v-if="car.photos.length"
              :src="`http://localhost:8000/storage/${car.photos[0]}`"
              alt="Car Image"
              class="w-full h-40 object-cover mb-4"
            />
            <h2 class="text-xl font-semibold text-gray-800 mb-2">
              {{ car.brand }} - {{ car.model }}
            </h2>
            <p class="text-gray-600 mb-4">Status: {{ car.availability }}</p>
            <div class="flex justify-between items-center">
              <button
                @click="editCar(car.id)"
                class="bg-yellow-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-yellow-600 focus:outline-none"
              >
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="flex justify-between mt-6">
        <button
          @click="fetchCars(page - 1)"
          :disabled="page <= 1"
          class="bg-blue-500 text-white px-4 py-2 rounded-md"
        >
          Previous
        </button>
        <span>Page {{ page }} of {{ totalPages }}</span>
        <button
          @click="fetchCars(page + 1)"
          :disabled="page >= totalPages"
          class="bg-blue-500 text-white px-4 py-2 rounded-md"
        >
          Next
        </button>
      </div>
    </div>
    <div v-else>
      <p>No cars available.</p>
    </div>
  </div>
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
    };
  },
  methods: {
    async fetchCars(page = 1) {
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
