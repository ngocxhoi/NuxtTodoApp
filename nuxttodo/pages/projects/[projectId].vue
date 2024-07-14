<template>
  <DashboardLayout>
    <div v-if="Boolean(selectedProject)" class="flex flex-col gap-5">
      <h1 class="text-2xl font-semibold">{{ selectedProject.title }}</h1>
      <p class="text-gray-500">{{ selectedProject.description }}</p>

      <div @click="openAddBoard = true" class="w-fit">
        <UButton label="Add Board" />
      </div>

      <draggableComponent
        v-if="selectedProject"
        :list="selectedProject.boards"
        itemKey="id"
        class="w-full flex gap-7 overflow-x-auto custom-scroll"
      >
        <template #item="{ element }">
          <BoardCard
            :board="element"
            :key="element.id"
            @updateBoard="openUpdateBoardFunc"
            @deleteBoard="openDeleteBoardFunc"
          />
        </template>
      </draggableComponent>
    </div>

    <UModal v-model="openAddBoard">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Add your new board</h1>
            <div class="cursor-pointer" @click="openAddBoard = false">
              <Icon name="mdi:close" size="27" />
            </div>
          </div>
        </template>

        <UFormGroup label="Title" required>
          <UInput v-model="boardTitle.title" placeholder="Add board title..." />
        </UFormGroup>

        <template #footer>
          <div @click="addBoard()">
            <UButton label="Save" :loading="isAddingBoard" />
          </div>
        </template>
      </UCard>
    </UModal>

    <UModal v-model="openUpdateBoard">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">Update your board</h1>
            <div class="cursor-pointer" @click="openUpdateBoard = false">
              <Icon name="mdi:close" size="27" />
            </div>
          </div>
        </template>

        <UFormGroup label="Title" required>
          <UInput
            v-model="dataUpdateBoard.title"
            placeholder="Add board title..."
          />
        </UFormGroup>

        <template #footer>
          <div @click="updateBoard()">
            <UButton label="Save" :loading="isUpdatingBoard" />
          </div>
        </template>
      </UCard>
    </UModal>

    <UModal v-model="openDeleteBoard">
      <UCard>
        <template #header>
          <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold">
              Do you want to delete this board?
            </h1>
            <div class="cursor-pointer" @click="openDeleteBoard = false">
              <Icon name="mdi:close" size="27" />
            </div>
          </div>
        </template>

        <UCard>
          <h1 class="text-xl">{{ boardDelete.title }}</h1>
        </UCard>

        <template #footer>
          <div @click="deleteBoard()">
            <UButton color="red" label="Yes" :loading="isDeletingBoard" />
          </div>
        </template>
      </UCard>
    </UModal>
  </DashboardLayout>
</template>

<script lang="ts" setup>
import DashboardLayout from "~/layouts/DashboardLayout.vue";
import draggableComponent from "vuedraggable";

const { $useGlobalStore } = useNuxtApp();
const { selectedProject, nameTitle } = storeToRefs($useGlobalStore);
useSeoMeta({
  title: nameTitle.value || "Project",
});
const route = useRoute();
const url = computed(() => "/api/project/" + route.params.projectId);

const openAddBoard = ref(false);
const openUpdateBoard = ref(false);
const openDeleteBoard = ref(false);

const isAddingBoard = ref(false);
const isUpdatingBoard = ref(false);
const isDeletingBoard = ref(false);
const boardTitle = reactive({
  title: "",
  project_id: selectedProject?.value.id,
});

onMounted(async () => {
  try {
    let res = await useUseSanctumFetch(url.value, "GET");

    if (res?.error.value) throw res.error.value;
    selectedProject.value = res?.data.value.data;
  } catch (error) {
    console.log(error);
  }
});

async function addBoard() {
  try {
    isAddingBoard.value = true;

    let res = await useUseSanctumFetch("/api/board", "POST", boardTitle);

    if (res?.error.value) throw res.error.value;
    selectedProject.value.boards.push(res?.data.value.data);
  } catch (error) {
    console.log(error);
  } finally {
    isAddingBoard.value = false;
    openAddBoard.value = false;
    boardTitle.title = "";
  }
}

const dataUpdateBoard = reactive({
  id: "",
  title: "",
});
function openUpdateBoardFunc(board: any) {
  dataUpdateBoard.id = board.id;
  dataUpdateBoard.title = board.title;
  openUpdateBoard.value = true;
}
async function updateBoard() {
  const credential = {
    title: dataUpdateBoard.title,
    project_id: selectedProject.value.id,
  };
  try {
    isUpdatingBoard.value = true;

    let res = await useUseSanctumFetch(
      `/api/board/${dataUpdateBoard.id}`,
      "PUT",
      credential
    );

    if (res?.error.value) throw res.error.value;
    selectedProject.value.boards.forEach((el: any) => {
      if (el.id === dataUpdateBoard.id) {
        el.title = dataUpdateBoard.title;
        return;
      }
    });
  } catch (error) {
    console.log(error);
  } finally {
    isUpdatingBoard.value = false;
    openUpdateBoard.value = false;
  }
}

const boardDelete = reactive({
  id: "",
  title: "",
});
function openDeleteBoardFunc(board: any) {
  boardDelete.id = board.id;
  boardDelete.title = board.title;
  openDeleteBoard.value = true;
}
async function deleteBoard() {
  try {
    isDeletingBoard.value = true;

    let res = await useUseSanctumFetch(
      `/api/board/${boardDelete.id}`,
      "DELETE"
    );

    if (res?.error.value) throw res.error.value;
    selectedProject.value.boards = selectedProject.value.boards.filter(
      (el: any) => el.id !== boardDelete.id
    );
  } catch (error) {
    console.log(error);
  } finally {
    isDeletingBoard.value = false;
    openDeleteBoard.value = false;
  }
}
</script>
