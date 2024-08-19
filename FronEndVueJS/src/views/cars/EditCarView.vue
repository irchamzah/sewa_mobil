<template>
  <section class="bg-white dark:bg-gray-900">
    <div class="max-w-2xl px-4 py-8 mx-auto lg:py-16">
      <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">
        Edit Car
      </h2>
      <form @submit.prevent="updateCar">
        <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
          <div class="sm:col-span-2">
            <label
              for="brand"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Brand
            </label>
            <input
              type="text"
              v-model="car.brand"
              id="brand"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Enter car brand"
              required
            />
          </div>

          <div class="w-full">
            <label
              for="model"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Model
            </label>
            <input
              type="text"
              v-model="car.model"
              id="model"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Enter car model"
              required
            />
          </div>

          <div class="w-full">
            <label
              for="licensePlate"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              License Plate
            </label>
            <input
              type="text"
              v-model="car.license_plate"
              id="licensePlate"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Enter license plate"
              required
            />
          </div>

          <div class="w-full">
            <label
              for="dailyRent"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Daily Rent
            </label>
            <input
              type="number"
              v-model="car.daily_rent"
              min="0"
              step="1"
              id="dailyRent"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              placeholder="Enter daily rent"
              required
            />
            <span v-if="dailyRentError" class="text-red-600">{{
              dailyRentError
            }}</span>
          </div>

          <div class="w-full">
            <label
              for="availability"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Availability
            </label>
            <select
              id="availability"
              v-model="car.availability"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              required
            >
              <option value="available">Available</option>
              <option value="unavailable">Unavailable</option>
            </select>
          </div>

          <div class="sm:col-span-2">
            <label
              for="photos"
              class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            >
              Photos
            </label>

            <input
              type="file"
              id="photos"
              @change="handleFileChange"
              multiple
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            />

            <div class="mt-4 flex flex-wrap gap-4">
              <!-- Display existing photos -->
              <div>
                <label
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >Existing Photos</label
                >
                <div class="flex gap-2">
                  <img
                    v-for="(photo, index) in photos"
                    :key="'existing-' + index"
                    :src="`http://localhost:8000/storage/${photo}`"
                    alt="Existing Photo"
                    class="w-24 h-24 object-cover border border-gray-300 rounded"
                  />
                </div>
              </div>

              <div>
                <label
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >New Photos</label
                >
                <!-- Display new photos -->
                <div class="flex gap-2">
                  <img
                    v-for="(preview, index) in previewPhotos"
                    :key="'preview-' + index"
                    :src="preview"
                    alt="Preview"
                    class="w-24 h-24 object-cover border border-gray-300 rounded"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <button
            type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
          >
            Update Car
          </button>

          <button
            @click="deleteCar"
            type="button"
            class="text-red-600 inline-flex items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900"
          >
            <svg
              class="w-5 h-5 mr-1 -ml-1"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd"
              ></path>
            </svg>
            Delete
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
  name: "EditCarView",
  data() {
    return {
      car: {
        brand: "",
        model: "",
        licensePlate: "",
        dailyRent: 0,
        availability: "",
        photos: [],
      },
      previewPhotos: [],
      error: null,
      success: null,
      dailyRentError: null,
    };
  },
  methods: {
    async updateCar() {
      const formData = new FormData();
      formData.append("brand", this.car.brand);
      formData.append("model", this.car.model);
      formData.append("licensePlate", this.car.license_plate);
      formData.append("dailyRent", this.car.daily_rent);
      formData.append("availability", this.car.availability);

      if (Array.isArray(this.car.photos) && this.car.photos.length > 0) {
        this.car.photos.forEach((photo) => {
          formData.append("photos[]", photo);
        });
      }

      try {
        const response = await axios.post(
          `http://localhost:8000/api/cars/${this.$route.params.id}/update`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        this.success = "Car updated successfully!";
        this.$router.push("/car-management");
        console.log(response.data);
        this.error = null;
      } catch (error) {
        this.error = error.response.data.message || "Update failed.";
        this.success = null;
      }
    },

    async deleteCar() {
      if (confirm("Are you sure you want to delete this car?")) {
        try {
          await axios.delete(
            `http://localhost:8000/api/cars/${this.$route.params.id}`,
            {
              headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
              },
            }
          );
          this.$router.push("/car-management");
        } catch (error) {
          console.error("Delete failed", error);
        }
      }
    },

    handleFileChange(event) {
      this.car.photos = Array.from(event.target.files);
      this.previewPhotos = this.car.photos.map((file) =>
        URL.createObjectURL(file)
      );
    },
  },

  async created() {
    try {
      const response = await axios.get(
        `http://localhost:8000/api/cars/${this.$route.params.id}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        }
      );

      this.car = response.data;

      // Ensure this.car.photos is an array
      if (Array.isArray(this.car.photos)) {
        this.photos = this.car.photos; // Existing photos
        this.previewPhotos = []; // Reset preview photos
      } else {
        this.photos = []; // Default to an empty array if photos is not an array
      }
    } catch (error) {
      console.error("Failed to fetch car data", error);
    }
  },
};
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
