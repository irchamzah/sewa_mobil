<template>
  <section class="bg-white dark:bg-gray-900">
    <div
      class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12"
    >
      <div class="mr-auto place-self-center lg:col-span-7">
        <h1
          class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white"
        >
          Welcome to Our Shop!
        </h1>
        <p
          class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400"
        >
          Discover our exclusive collection of products.
        </p>
        <a
          href="#"
          class="inline-flex items-center justify-center px-5 py-3 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900"
        >
          Get started
          <svg
            class="w-5 h-5 ml-2 -mr-1"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </a>
        <a
          href="#"
          class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800"
        >
          Speak to Sales
        </a>
      </div>
      <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
        <img src="http://localhost:8000/images/hero.jpg" alt="mockup" />
      </div>
    </div>
  </section>

  <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-2xl px-4 2xl:px-0 py-8 sm:py-12 lg:max-w-7xl">
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
                @change="fetchCars"
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
                @change="fetchCars"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:hover:text-white dark:hover:bg-gray-700"
              />
            </div>
            <div class="flex flex-col">
              <label
                for="availability"
                class="block text-gray-100 font-medium mb-1"
                >Availability:</label
              >
              <select
                id="availability"
                v-model="filters.availability"
                @change="fetchCars"
                class="w-full md:w-auto flex items-center justify-center py-2 px-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
              >
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
      <!-- Heading & Filters -->
      <div
        class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8"
      >
        <div>
          <h2
            class="mt-3 text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl"
          >
            Available Cars
          </h2>
        </div>
      </div>

      <div
        class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4"
      >
        <div
          v-for="car in cars"
          :key="car.id"
          class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800"
        >
          <div class="h-56 w-full">
            <a @click="viewDetails(car.id)">
              <img
                :src="`http://localhost:8000/storage/${car.photos[0]}`"
                alt="Car Image"
                class="mx-auto h-full object-cover cursor-pointer"
              />
            </a>
          </div>
          <div class="pt-6 flex flex-col">
            <div>
              <a
                @click="viewDetails(car.id)"
                class="cursor-pointer text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white"
              >
                {{ car.brand }} - {{ car.model }}
              </a>
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
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                ID: {{ car.id }}
              </p>
            </div>
            <div class="mt-4 flex flex-col justify-between gap-4">
              <p
                class="text-2xl font-bold leading-tight text-gray-900 dark:text-white"
              >
                {{
                  Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                  }).format(car.daily_rent)
                }}
              </p>
              <a
                @click="viewDetails(car.id)"
                class="cursor-pointer inline-flex items-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-min text-nowrap"
              >
                View Details
              </a>
            </div>
          </div>
        </div>
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

  <div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Available Cars</h1>

    <!-- Filter Section -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4 text-gray-800">Filter Cars</h2>

      <div class="mb-4">
        <label for="brand" class="block text-gray-700 font-medium mb-1"
          >Brand:</label
        >
        <input
          type="text"
          id="brand"
          v-model="filters.brand"
          @change="fetchCars"
          class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div class="mb-4">
        <label for="model" class="block text-gray-700 font-medium mb-1"
          >Model:</label
        >
        <input
          type="text"
          id="model"
          v-model="filters.model"
          @change="fetchCars"
          class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
        />
      </div>

      <div class="mb-4">
        <label for="availability" class="block text-gray-700 font-medium mb-1"
          >Availability:</label
        >
        <select
          id="availability"
          v-model="filters.availability"
          @change="fetchCars"
          class="border border-gray-300 rounded-md px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">All</option>
          <option value="available">Available</option>
          <option value="unavailable">Unavailable</option>
        </select>
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
          <img
            :src="`http://localhost:8000/storage/${car.photos[0]}`"
            alt="Car Image"
            class="w-full h-48 object-cover"
          />
          <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">
              {{ car.brand }} - {{ car.model }}
            </h2>
            <p class="text-gray-600 mb-4">Status: {{ car.availability }}</p>
            <div class="flex justify-between items-center">
              <span class="text-gray-500 text-sm">Car ID: {{ car.id }}</span>
              <button
                @click="viewDetails(car.id)"
                class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                View Details
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div class="flex justify-between items-center mt-6">
        <button
          @click="fetchCars(page - 1)"
          :disabled="page <= 1"
          class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
          Previous
        </button>
        <span class="text-gray-700">Page {{ page }} of {{ totalPages }}</span>
        <button
          @click="fetchCars(page + 1)"
          :disabled="page >= totalPages"
          class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
          Next
        </button>
      </div>
    </div>
    <div v-else class="text-center text-gray-700 mt-6">
      <p>No cars available.</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "HomeView",
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
        const response = await axios.get("http://localhost:8000/api/home", {
          params,
        });
        this.cars = response.data.data; // 'data' contains the actual cars
        this.page = response.data.current_page;
        this.totalPages = response.data.last_page;
      } catch (error) {
        console.error("Error fetching cars:", error);
      }
    },
    viewDetails(id) {
      this.$router.push({ name: "CarDetails", params: { id } });
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
