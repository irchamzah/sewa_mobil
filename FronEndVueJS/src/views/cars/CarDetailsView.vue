<template>
  <div class="p-6">
    <h1 class="text-2xl font-semibold mb-4">Car Details</h1>
    <div v-if="car">
      <p><strong>ID:</strong> {{ car.id }}</p>
      <p><strong>Brand:</strong> {{ car.brand }}</p>
      <p><strong>Model:</strong> {{ car.model }}</p>
      <p><strong>License Plate:</strong> {{ car.license_plate }}</p>
      <p><strong>Daily Rent:</strong> {{ car.daily_rent }}</p>
      <p><strong>Owner:</strong> {{ car.user }}</p>
      <p><strong>Availability:</strong> {{ car.availability }}</p>
      <p><strong>Created At:</strong> {{ car.timestamps }}</p>
      <div v-if="car.photos && car.photos.length">
        <h3 class="mt-4 mb-2 font-semibold">Photos:</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          <img
            v-for="photo in car.photos"
            :key="photo"
            :src="`http://localhost:8000/storage/${photo}`"
            class="rounded shadow-md"
            alt="Car Photo"
          />
        </div>
        <button
          @click="rentCar(car.id)"
          class="bg-yellow-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500"
        >
          Rent car
        </button>
      </div>
    </div>

    <div v-else>
      <p>Loading...</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      car: null,
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
      }
    },
    rentCar(carId) {
      this.$router.push({ name: "RentCar", params: { carId } });
    },
  },
};
</script>
