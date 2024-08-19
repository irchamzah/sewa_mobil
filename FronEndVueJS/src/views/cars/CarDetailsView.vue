<template>
  <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
    <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
      <div v-if="isLoading" class="animate-pulse">
        <!-- Skeleton Loader -->
        <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
          <div class="shrink-0 max-w-md lg:max-w-lg">
            <div class="bg-gray-200 h-96 rounded-md w-full"></div>
          </div>
          <div class="mt-6 sm:mt-8 lg:mt-0">
            <div class="bg-gray-200 h-8 w-3/4 rounded-md mb-4"></div>
            <div class="bg-gray-200 h-6 w-1/4 rounded-md mb-4"></div>
            <div class="bg-gray-200 h-6 w-1/2 rounded-md mb-4"></div>
            <div class="bg-gray-200 h-6 w-1/4 rounded-md mb-4"></div>
            <div class="bg-gray-200 h-6 w-1/2 rounded-md mb-4"></div>
          </div>
        </div>
      </div>

      <div v-else>
        <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
          <div class="shrink-0 max-w-md lg:max-w-lg mx-auto">
            <div
              v-if="car.photos && car.photos.length"
              class="grid grid-cols-1 gap-4"
            >
              <img
                v-for="(photo, index) in car.photos"
                :key="index"
                class="w-full"
                :src="`http://localhost:8000/storage/${photo}`"
                alt="Car Photo"
              />
            </div>
          </div>

          <div class="mt-6 sm:mt-8 lg:mt-0">
            <h1
              class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
            >
              {{ car.brand }} {{ car.model }}
            </h1>
            <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
              <p
                class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-white"
              >
                Daily Rent:
                {{
                  Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                  }).format(car.daily_rent)
                }}
              </p>
            </div>

            <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
              <button
                @click="rentCar(car.id)"
                :class="{
                  'bg-yellow-500 hover:bg-yellow-600':
                    car.availability === 'available',
                  'bg-gray-400 cursor-not-allowed':
                    car.availability === 'unavailable',
                }"
                :disabled="car.availability === 'unavailable'"
                class="text-white mt-4 sm:mt-0 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:focus:ring-yellow-800 flex items-center justify-center"
                role="button"
              >
                Rent Car
              </button>
            </div>

            <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />

            <div class="space-y-4">
              <p class="text-gray-500 dark:text-gray-400">
                <strong>License Plate:</strong> {{ car.license_plate }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                <strong>Availability: </strong>
                <span
                  :class="{
                    'text-green-600': car.availability === 'available',
                    'text-red-600': car.availability !== 'available',
                  }"
                  >{{ car.availability }}</span
                >
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                <strong>Owner:</strong> {{ car.user }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                <strong>Owner's phone:</strong> {{ car.user_phone }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                <strong>Created At:</strong>
                {{ new Date(car.created_at).toLocaleDateString() }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                <strong>Updated At:</strong>
                {{ new Date(car.updated_at).toLocaleDateString() }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      car: null,
      isLoading: true,
    };
  },
  created() {
    this.fetchCarDetails();
  },
  methods: {
    async fetchCarDetails() {
      const carId = this.$route.params.id;
      try {
        const response = await axios.get(
          `http://localhost:8000/api/cars/details/${carId}`
        );
        this.car = response.data;
      } catch (error) {
        console.error("Error fetching car details:", error);
      } finally {
        this.isLoading = false;
      }
    },
    rentCar(carId) {
      this.$router.push({ name: "RentCar", params: { carId } });
    },
  },
};
</script>
