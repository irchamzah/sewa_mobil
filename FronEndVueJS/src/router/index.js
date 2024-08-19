import { createRouter, createWebHistory } from "vue-router";
import RegisterView from "../views/auth/RegisterView.vue";
import LoginView from "../views/auth/LoginView.vue";
import ProfileView from "../views/profile/ProfileView.vue";
import CarManagementView from "../views/cars/CarManagementView.vue";
import CarRentalView from "../views/cars/RentalHistoryView.vue";
import HomeView from "../views/HomeView.vue";
import AddCarView from "../views/cars/AddCarView.vue";
import EditCarView from "../views/cars/EditCarView.vue";
import CarDetailsView from "../views/cars/CarDetailsView.vue";
import RentCarView from "../views/cars/RentCarView.vue";

const routes = [
  { path: "/", name: "Home", component: HomeView }, // Perbaiki nama route di sini
  { path: "/home", component: HomeView },
  { path: "/register", name: "Register", component: RegisterView },
  { path: "/login", name: "Login", component: LoginView },
  {
    path: "/profile",
    name: "Profile",
    component: ProfileView,
    meta: { requiresAuth: true },
  },
  {
    path: "/car-management",
    name: "CarManagement",
    component: CarManagementView,
    meta: { requiresAuth: true },
  },
  {
    path: "/add-car",
    name: "AddCar",
    component: AddCarView,
    meta: { requiresAuth: true },
  },
  {
    path: "/edit-car/:id",
    name: "EditCar",
    component: EditCarView,
    meta: { requiresAuth: true },
  },
  {
    path: "/car-rental",
    name: "CarRental",
    component: CarRentalView,
    meta: { requiresAuth: true },
  },
  {
    path: "/rent-car/:carId",
    name: "RentCar",
    component: RentCarView,
    meta: { requiresAuth: true },
  },
  {
    path: "/car/:id",
    name: "CarDetails",
    component: CarDetailsView,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isLoggedIn = !!localStorage.getItem("token");
  if (to.meta.requiresAuth && !isLoggedIn) {
    next("/login");
  } else {
    next();
  }
});

export default router;
