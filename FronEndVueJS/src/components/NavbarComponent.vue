<template>
  <nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
      <div class="relative flex h-16 items-center justify-between">
        <!-- Mobile menu button -->
        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
          <button
            type="button"
            class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
            @click="toggleMobileMenu"
            aria-controls="mobile-menu"
            aria-expanded="false"
            id="MenuButton"
          >
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open main menu</span>
            <svg
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
              id="closedMenu"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
              />
            </svg>
            <svg
              class="hidden h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
              id="openMenu"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <!-- Navbar content -->
        <div
          class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-between"
        >
          <div class="flex flex-shrink-0 items-center">
            <a href="/" class="text-white text-xl font-bold h-8 w-auto">
              Sewa Mobil
            </a>
          </div>

          <div class="hidden sm:ml-6 sm:block">
            <div class="flex space-x-4">
              <router-link
                to="/"
                class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                :class="{ 'bg-gray-900 text-white': $route.path === '/' }"
              >
                Home
              </router-link>
              <template v-if="isLoggedIn">
                <router-link
                  to="/car-management"
                  class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                  :class="{
                    'bg-gray-900 text-white':
                      $route.path.includes('car-management'),
                  }"
                >
                  Car Management
                </router-link>
                <router-link
                  to="/car-rental"
                  class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                  :class="{
                    'bg-gray-900 text-white':
                      $route.path.includes('car-rental'),
                  }"
                >
                  Car Rental
                </router-link>
                <router-link
                  to="/profile"
                  class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                  :class="{
                    'bg-gray-900 text-white': $route.path.includes('profile'),
                  }"
                >
                  Profile
                </router-link>
                <a
                  @click.prevent="logout"
                  href="#"
                  class="text-red-500 hover:bg-gray-700 hover:text-red-500 px-3 py-2 rounded-md text-sm font-medium"
                >
                  Logout
                </a>
              </template>
              <template v-else>
                <router-link
                  to="/login"
                  class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                  :class="{
                    'bg-gray-900 text-white': $route.path.includes('login'),
                  }"
                >
                  Login
                </router-link>
                <router-link
                  to="/register"
                  class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                  :class="{
                    'bg-gray-900 text-white': $route.path.includes('register'),
                  }"
                >
                  Register
                </router-link>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div class="hidden" id="Menu">
      <div class="px-2 pb-3 pt-2">
        <router-link
          to="/"
          class="block rounded-md px-3 py-2 text-base font-medium text-gray-300"
          :class="{ 'bg-gray-900 text-white': $route.path === '/' }"
        >
          Home
        </router-link>
        <template v-if="isLoggedIn">
          <router-link
            to="/car-management"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-300"
            :class="{
              'bg-gray-900 text-white': $route.path.includes('car-management'),
            }"
          >
            Car Management
          </router-link>
          <router-link
            to="/car-rental"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-300"
            :class="{
              'bg-gray-900 text-white': $route.path.includes('car-rental'),
            }"
          >
            Car Rental
          </router-link>
          <router-link
            to="/profile"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-300"
            :class="{
              'bg-gray-900 text-white': $route.path.includes('profile'),
            }"
          >
            Profile
          </router-link>
          <a
            @click.prevent="logout"
            href="#"
            class="block rounded-md px-3 py-2 text-base font-medium text-red-500"
          >
            Logout
          </a>
        </template>
        <template v-else>
          <router-link
            to="/login"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-300"
          >
            Login
          </router-link>
          <router-link
            to="/register"
            class="block rounded-md px-3 py-2 text-base font-medium text-gray-300"
          >
            Register
          </router-link>
        </template>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  name: "NavbarComponent",
  computed: {
    ...mapGetters(["isLoggedIn"]),
  },
  methods: {
    ...mapActions(["logout"]),
    logout() {
      this.$store.dispatch("logout");
      localStorage.removeItem("token");
      this.$router.push("/login");
    },
    toggleMobileMenu() {
      const menu = document.getElementById("Menu");
      menu.classList.toggle("hidden");

      const closedMenu = document.getElementById("closedMenu");
      const openMenu = document.getElementById("openMenu");

      if (closedMenu.classList.contains("hidden")) {
        closedMenu.classList.remove("hidden");
        closedMenu.classList.add("block");
        openMenu.classList.remove("block");
        openMenu.classList.add("hidden");
      } else {
        closedMenu.classList.remove("block");
        closedMenu.classList.add("hidden");
        openMenu.classList.remove("hidden");
        openMenu.classList.add("block");
      }
    },
  },
};
</script>

<style scoped>
/* Additional styles if needed */
</style>
