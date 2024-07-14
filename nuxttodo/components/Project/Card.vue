<template>
  <NuxtLink :to="url" @click="nameTitle = project.title">
    <div
      class="flex flex-col gap-2 p-4 rounded-lg shadow cursor-pointer dark:border-white dark:border-2"
    >
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-semibold">{{ project.title }}</h1>
        <div class="cursor-pointer" @click.prevent="deleteAlertOpen = true">
          <Icon name="mdi:trash-can-outline" size="20" class="text-red-500" />
        </div>
      </div>
      <p class="text-gray-500 dark:text-gray-200">{{ project?.description }}</p>
      <p class="text-gray-500 dark:text-gray-200">{{ project?.id }}</p>
    </div>
  </NuxtLink>

  <UModal v-model="deleteAlertOpen">
    <UCard>
      <template #header>
        <div class="flex items-center justify-between">
          <p class="text-red-500 text-lg">
            Do you want to delete this project?
          </p>
          <div class="cursor-pointer" @click="deleteAlertOpen = false">
            <Icon name="heroicons:x-mark" size="27" />
          </div>
        </div>
      </template>

      <div class="flex flex-col gap-2 p-4 rounded-lg shadow cursor-pointer">
        <h1 class="text-xl font-semibold">{{ project.title }}</h1>
        <p class="text-gray-500">{{ project?.description }}</p>
      </div>

      <template #footer>
        <div class="float-right mb-2">
          <UButton
            color="red"
            label="Yes"
            :loading="isDeleting"
            @click="deleteProject()"
          />
          <UButton label="No" class="ml-4" @click="deleteAlertOpen = false" />
        </div>
      </template>
    </UCard>
  </UModal>
</template>

<script lang="ts" setup>
const props = defineProps(["project"]);
const { project } = toRefs(props);
const { $useGlobalStore } = useNuxtApp();
const { nameTitle } = storeToRefs($useGlobalStore);

const url = computed(() => {
  return "/projects/" + project?.value.id;
});
const deleteAlertOpen = ref(false);
const isDeleting = ref(false);

async function deleteProject() {
  try {
    isDeleting.value = true;
    let res = await useUseSanctumFetch(
      `/api/project/${project?.value.id}`,
      "DELETE"
    );
    if (res?.error.value) throw res.error.value;
    $useGlobalStore.deleteProject(project?.value.id);
  } catch (error) {
    console.log(error);
    $useGlobalStore.transformValidationErrors(error);
  } finally {
    isDeleting.value = false;
    deleteAlertOpen.value = false;
  }
}
</script>
