<template>
  <DashboardLayout>
    <div class="flex flex-col items-start">
      <UButton label="To Login" to="/auth/login" />
      <p>isLogin: {{ isLoggedIn }}</p>
      <p>user: {{ user }}</p>
      <p>token: {{ token }}</p>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from "~/layouts/DashboardLayout.vue";
useSeoMeta({
  title: "Welcome",
});

const { $useAuthStore } = useNuxtApp();
const { isLoggedIn, token, user } = storeToRefs($useAuthStore);
</script>

<style></style>
