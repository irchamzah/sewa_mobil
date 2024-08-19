import { createStore } from "vuex";

export default createStore({
  state: {
    isLoggedIn: !!localStorage.getItem("token"),
  },
  mutations: {
    login(state) {
      state.isLoggedIn = true;
    },
    logout(state) {
      state.isLoggedIn = false;
    },
  },
  actions: {
    login({ commit }) {
      commit("login");
    },
    logout({ commit }) {
      commit("logout");
    },
  },
  getters: {
    isLoggedIn: (state) => state.isLoggedIn,
  },
});
