export default defineNuxtPlugin((nuxtApp) => {
  return {
    provide: {
      useAuthStore: useMyAuthStore(),
      useGlobalStore: useMyGlobalStore(),
    },
  };
});
