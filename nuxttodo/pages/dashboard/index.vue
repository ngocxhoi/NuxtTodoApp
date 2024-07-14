<template>
  <div class="h-screen flex items-center justify-center">
    <div v-if="isLoggedIn" class="mx-auto">
      <p class="text-red-500 text-2xl mb-4">Your email has been verified!</p>
      <p class="my-2">You are: {{ user.email }}</p>
      <UButton label="Go to Home" to="/" class="mx-auto" />
    </div>

    <div v-else class="text-blue-400 text-2xl">
      {{ message }}
    </div>
  </div>
</template>

<script lang="ts" setup>
useSeoMeta({
  title: "Dashboard",
});
const { $useAuthStore } = useNuxtApp();
const { user, isLoggedIn, token } = storeToRefs($useAuthStore);

const message = ref("We had send an email for your confirmation!");
onMounted(async () => {
  let data = await useUseSanctumFetch("/api/check-user");

  if (data?.data.value != null) {
    isLoggedIn.value = true;
    await useFetch("/api/setCookies", {
      method: "POST",
      body: {
        token: token.value,
      },
    });
    return;
  } else return (message.value = "Please check your email end confirm again!");
});
</script>
