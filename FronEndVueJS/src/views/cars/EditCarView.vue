<template>
  <div class="p-4">
    <h1 class="text-2xl font-semibold mb-4">Edit Car</h1>

    <form @submit.prevent="updateCar">
      <div class="mb-4">
        <label for="brand" class="block text-gray-700">Brand:</label>
        <input
          type="text"
          v-model="car.brand"
          id="brand"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <div class="mb-4">
        <label for="model" class="block text-gray-700">Model:</label>
        <input
          type="text"
          v-model="car.model"
          id="model"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <div class="mb-4">
        <label for="licensePlate" class="block text-gray-700"
          >License Plate:</label
        >
        <input
          type="text"
          v-model="car.licensePlate"
          id="licensePlate"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <div class="mb-4">
        <label for="dailyRent" class="block text-gray-700">Daily Rent:</label>
        <input
          type="number"
          v-model="car.dailyRent"
          id="dailyRent"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <div class="mb-4">
        <label for="availability" class="block text-gray-700"
          >Availability:</label
        >
        <select
          id="availability"
          v-model="car.availability"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        >
          <option value="available">Available</option>
          <option value="unavailable">Unavailable</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="photos" class="block text-gray-700">Photos:</label>
        <input
          type="file"
          id="photos"
          @change="handleFileChange"
          multiple
          class="border border-gray-300 rounded px-3 py-2 w-full"
        />
        <div v-if="previewPhotos.length" class="mt-4 flex flex-wrap gap-4">
          <img
            v-for="(preview, index) in previewPhotos"
            :key="index"
            :src="preview"
            alt="Preview"
            class="w-24 h-24 object-cover border border-gray-300 rounded"
          />
        </div>
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        Update Car
      </button>

      <button
        @click="deleteCar"
        type="button"
        class="bg-red-500 text-white px-4 py-2 rounded-md font-medium shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
      >
        Delete Car
      </button>

      <div v-if="error" class="mt-4 text-red-600">{{ error }}</div>
      <div v-if="success" class="mt-4 text-green-600">{{ success }}</div>
    </form>
  </div>
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
        dailyRent: "",
        availability: "",
        photos: [],
      },
      previewPhotos: [],
      error: null,
      success: null,
    };
  },
  methods: {
    async updateCar() {
      const formData = new FormData();
      formData.append("brand", this.car.brand);
      formData.append("model", this.car.model);
      formData.append("licensePlate", this.car.licensePlate);
      formData.append("dailyRent", this.car.dailyRent);
      formData.append("availability", this.car.availability);

      // Check if photos is an array and has elements before appending
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
          console.error("Error deleting car:", error);
        }
      }
    },

    handleFileChange(event) {
      this.car.photos = Array.from(event.target.files);
      this.previewPhotos = this.car.photos.map((file) =>
        URL.createObjectURL(file)
      );
    },
    async fetchCarDetails() {
      try {
        const response = await axios.get(
          `http://localhost:8000/api/cars/${this.$route.params.id}`,
          {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );
        this.car = {
          brand: response.data.brand,
          model: response.data.model,
          licensePlate: response.data.license_plate,
          dailyRent: response.data.daily_rent,
          availability: response.data.availability,
          photos: response.data.photos,
        };
        this.previewPhotos = this.car.photos.map(
          (photo) => `http://localhost:8000/storage/${photo}`
        );
      } catch (error) {
        this.error =
          error.response.data.message || "Failed to fetch car details.";
      }
    },
  },
  mounted() {
    this.fetchCarDetails();
  },
};
</script>

<style scoped>
/* Optional additional styling */
</style>
