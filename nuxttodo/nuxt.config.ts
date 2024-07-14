// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: false },
  modules: [
    "@nuxt/ui",
    "@pinia/nuxt",
    "@pinia-plugin-persistedstate/nuxt",
    // "nuxt-auth-sanctum",
  ],

  colorMode: {
    preference: "light",
  },

  runtimeConfig: {
    API_URL: "http://localhost:8000",
    public: {
      API_URL: "http://localhost:8000",
    },
  },

  appConfig: {
    title: "Todo",
    description: "Nuxt Todo App",
  },

  // sanctum: {
  //   baseUrl: "http://localhost:8000",
  //   redirect: {
  //     onLogin: "/",
  //     onLogout: "/auth/login",
  //     onAuthOnly: "/auth/login",
  //   },
  // },
});
