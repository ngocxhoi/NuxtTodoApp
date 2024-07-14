export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useMyAuthStore();
  const protectedRoutes = ["/auth/login", "/auth/signin", "/", "/dashboard"];
  const isProtectedRoute = protectedRoutes.some((route) => to.path == route);
  if (isProtectedRoute) return;

  let key: string = "token";
  if (import.meta.server) {
    const token = useCookie(key);

    if (!token.value) {
      authStore.logoutUser();
      // authStore.isLoggedIn = false;
      return navigateTo("/auth/login");
      // throw createError({
      //   status: 401,
      //   statusMessage: "Unauthenticated",
      //   message: "You must be logged to access this resource",
      // });
    } else return;
  }
});
