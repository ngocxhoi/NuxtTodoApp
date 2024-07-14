export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useMyAuthStore();

  const cookie = useCookie("token").value;
  if (!cookie) {
    authStore.logoutUser();
    return;
  }

  if (authStore.isLoggedIn) {
    if (to.path === "/auth/login" || to.path === "/auth/register")
      return navigateTo("/");
  }
});
