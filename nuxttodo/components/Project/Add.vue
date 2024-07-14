<template>
  <div class="flex flex-col">
    <div class="flex flex-col gap-4 md:items-center md:justify-between mb-4">
      <UForm
        ref="form"
        :schema="projectSchema"
        :state="project"
        v-if="showForm"
        @submit="addProject"
        class="grid grid-cols-1 md:grid-cols-3 gap-2 flex-grow items-end"
      >
        <UFormGroup label="Title" required>
          <UInput placeholder="Title.." v-model="project.title" />
        </UFormGroup>

        <UFormGroup label="Description">
          <UInput placeholder="Description..." v-model="project.description" />
        </UFormGroup>

        <UButton
          type="submit"
          label="Save"
          :loading="isAdding"
          class="w-40"
          block
        />
      </UForm>

      <UButton
        block
        label="Add project"
        class="w-52"
        @click="showForm = !showForm"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import type { FormSubmitEvent, Form } from "#ui/types";
import {
  projectSchema,
  type ProjectSchema,
} from "../../schemas/project.schema";

const { $useGlobalStore } = useNuxtApp();
const { projects } = storeToRefs($useGlobalStore);
const form = ref<Form<ProjectSchema>>();

const project = reactive({
  title: "",
  description: "",
});
const showForm = ref(false);
const isAdding = ref(false);

async function addProject(event: FormSubmitEvent<ProjectSchema>) {
  const credential = event.data;
  try {
    isAdding.value = true;
    $useGlobalStore.resetErrorBag();
    let res = await useUseSanctumFetch("/api/project", "POST", credential);
    if (res?.error.value) throw res.error.value;
    projects.value.push(res?.data.value.data);
    console.log("data: ", res?.data.value);
  } catch (error) {
    console.log(error);
    $useGlobalStore.transformValidationErrors(error);
  } finally {
    isAdding.value = false;
    project.title = "";
    project.description = "";
  }
}
</script>
