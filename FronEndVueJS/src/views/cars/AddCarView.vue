<template>
  <div class="p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-semibold mb-6">Add Car</h1>
    <form @submit.prevent="addCar">
      <!-- Brand -->
      <div class="mb-4">
        <label for="brand" class="block text-gray-700">Brand:</label>
        <input
          type="text"
          v-model="form.brand"
          id="brand"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <!-- Model -->
      <div class="mb-4">
        <label for="model" class="block text-gray-700">Model:</label>
        <input
          type="text"
          v-model="form.model"
          id="model"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <!-- License Plate -->
      <div class="mb-4">
        <label for="licensePlate" class="block text-gray-700"
          >License Plate:</label
        >
        <input
          type="text"
          v-model="form.licensePlate"
          id="licensePlate"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <!-- Daily Rent -->
      <div class="mb-4">
        <label for="dailyRent" class="block text-gray-700">Daily Rent:</label>
        <input
          type="number"
          v-model="form.dailyRent"
          id="dailyRent"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        />
      </div>

      <!-- Availability -->
      <div class="mb-4">
        <label for="availability" class="block text-gray-700"
          >Availability:</label
        >
        <select
          id="availability"
          v-model="form.availability"
          class="border border-gray-300 rounded px-3 py-2 w-full"
          required
        >
          <option value="available">Available</option>
          <option value="unavailable">Unavailable</option>
        </select>
      </div>

      <!-- Photos -->
      <div class="mb-4">
        <label for="photos" class="block text-gray-700">Photos:</label>
        <input
          type="file"
          id="photos"
          @change="handleFileUpload"
          multiple
          class="border border-gray-300 rounded px-3 py-2 w-full"
        />
        <p class="text-gray-600 mt-2">You can select multiple images.</p>
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        Add Car
      </button>

      <div v-if="error" class="mt-4 text-red-600">{{ error }}</div>
      <div v-if="success" class="mt-4 text-green-600">{{ success }}</div>
    </form>
  </div>
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
