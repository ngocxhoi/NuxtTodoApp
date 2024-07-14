<template>
  <DashboardLayout>
    <UButton label="Get Project" @click="getProjects()" :loading="isLoading" />
    <div class="flex flex-col gap-10 items-center">
      <h1 class="text-2xl font-semibold">Your projects</h1>
      <ProjectAdd />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <ProjectCard
        v-for="project in projects"
        :key="project.id"
        :project="project"
      />
    </div>
  </DashboardLayout>
</template>

<script lang="ts" setup>
import DashboardLayout from "~/layouts/DashboardLayout.vue";
useSeoMeta({
  title: "Projects",
});

const { $useGlobalStore, $useAuthStore } = useNuxtApp();
const { projects } = storeToRefs($useGlobalStore);
const { isLoggedIn } = storeToRefs($useAuthStore);

const isLoading = ref(false);

onMounted(async () => {
  try {
    let res = await useUseSanctumFetch("/api/projects");

    if (res?.error.value) throw res.error.value;

    if (res?.data.value.data) {
      projects.value = res?.data.value.data;
    }
  } catch (error) {
    console.log(error);
  }
});

async function getProjects() {
  try {
    isLoading.value = true;
    let res = await useUseSanctumFetch("/api/projects", "GET");

    if (res?.error.value) throw res.error.value;

    projects.value = res?.data.value.data;
  } catch (error) {
    console.log(error);
  } finally {
    isLoading.value = false;
  }
}
</script>
