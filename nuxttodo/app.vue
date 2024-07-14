<template>
  <div class="bg-gray-100 dark:bg-gray-900">
    <NuxtLayout>
      <NuxtRouteAnnouncer />
      <LazyNuxtPage keep-alive />
      <UNotifications />
    </NuxtLayout>
  </div>
</template>

<script setup lang="ts">
const appConfig = useAppConfig();

useHead({
  titleTemplate(title): any {
    return title ? `${title} | ${appConfig.title}` : appConfig.title;
  },
});
</script>
