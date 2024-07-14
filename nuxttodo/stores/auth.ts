import { defineStore } from "pinia";
import type { User } from "~/types/User";

export const useMyAuthStore = defineStore({
  id: "myAuthStore",
  state: () => ({
    isLoggedIn: false,
    token: null,
    user: {} as User,
  }),
  actions: {
    logoutUser() {
      this.isLoggedIn = false;
      this.token = null;
      this.user = {} as User;
    },
  },
  persist: true,
});
