<template>
  <section class="bg-white dark:bg-gray-900">
    <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
      <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
        Add Car
      </h2>
      <form @submit.prevent="addCar">
        <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
          <!-- Brand -->
          <div class="w-full">
            <label
              for="brand"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Brand</label
            >
            <input
              type="text"
              v-model="form.brand"
              id="brand"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Enter car brand"
              required
            />
          </div>

          <!-- Model -->
          <div class="w-full">
            <label
              for="model"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Model</label
            >
            <input
              type="text"
              v-model="form.model"
              id="model"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Enter car model"
              required
            />
          </div>

          <!-- License Plate -->
          <div class="w-full">
            <label
              for="licensePlate"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >License Plate</label
            >
            <input
              type="text"
              v-model="form.licensePlate"
              id="licensePlate"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Enter license plate"
              required
            />
          </div>

          <!-- Daily Rent -->
          <div class="w-full">
            <label
              for="dailyRent"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Daily Rent</label
            >
            <input
              type="number"
              v-model="form.dailyRent"
              id="dailyRent"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Enter daily rent"
              required
            />
          </div>

          <!-- Availability -->
          <div class="w-full">
            <label
              for="availability"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Availability</label
            >
            <select
              id="availability"
              v-model="form.availability"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              required
            >
              <option value="available">Available</option>
              <option value="unavailable">Unavailable</option>
            </select>
          </div>

          <!-- Photos -->
          <div class="w-full sm:col-span-2">
            <label
              for="photos"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >Photos</label
            >
            <input
              required
              type="file"
              id="photos"
              @change="handleFileUpload"
              multiple
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            />
            <p class="text-gray-600 mt-2">You can select multiple images.</p>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <button
            type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Add Car
          </button>
        </div>

        <div v-if="error" class="mt-4 text-red-600">{{ error }}</div>
        <div v-if="success" class="mt-4 text-green-600">{{ success }}</div>
      </form>
    </div>
  </section>
</template>

<script>
import axios from "axios";

export default {
  name: "AddCarView",
  data() {
    return {
      form: {
        brand: "",
        model: "",
        licensePlate: "",
        dailyRent: "",
        availability: "available",
        photos: [],
      },
      error: null,
      success: null,
    };
  },
  methods: {
    handleFileUpload(event) {
      this.form.photos = Array.from(event.target.files);
    },
    async addCar() {
      try {
        const formData = new FormData();
        for (const key in this.form) {
          if (this.form.hasOwnProperty(key)) {
            if (key === "photos") {
              this.form.photos.forEach((photo) => {
                formData.append("photos[]", photo);
              });
            } else {
              formData.append(key, this.form[key]);
            }
          }
        }

        const response = await axios.post(
          "http://localhost:8000/api/cars",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        this.success = "Car added successfully!";
        this.$router.push("/car-management");
        console.log(response.data);
        this.error = null;
        this.form = {
          brand: "",
          model: "",
          licensePlate: "",
          dailyRent: "",
          availability: "available",
          photos: [],
        };
      } catch (error) {
        this.error = error.response.data.message || "Failed to add car.";
        this.success = null;
      }
    },
  },
};
</script>

<style scoped>
/* Optional additional styling */
</style>
