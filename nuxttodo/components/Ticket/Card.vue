<template>
  <div
    class="w-full min-h-10 border border-gray-300 shadow rounded-lg flex-none p-2"
  >
    <h1
      class="text-base font-semibold select-none h-full"
      @click="openShowTicket = true"
    >
      {{ ticket.title }}
    </h1>

    <UModal v-model="openShowTicket">
      <UCard>
        <template #header>
          <h1 v-if="!editTitle" @click="editTitle = true" class="text-lg">
            {{ newTicket.title }}
          </h1>
          <UInput
            v-else
            @click="editTitle = false"
            placeholder="Type ticket title..."
            autofocus
            v-model="newTicket.title"
          />
        </template>

        <UInput
          placeholder="Type your description here..."
          v-model="newTicket.description"
        />

        <template #footer>
          <div class="mb-2 float-right">
            <UButton
              color="red"
              label="Delete"
              :loading="isDeleting"
              @click="deleteTicket()"
            />
            <UButton
              label="Update"
              :disabled="isDisabled"
              :loading="isUpdating"
              class="ml-4"
              @click="updateTicket()"
            />
          </div>
        </template>
      </UCard>
    </UModal>
  </div>
</template>

<script lang="ts" setup>
const props = defineProps(["ticket", "boardId"]);
const { ticket, boardId } = toRefs(props);

const emits = defineEmits(["deleteTicket", "updateTicket"]);

const editTitle = ref(false);
const isDeleting = ref(false);
const isUpdating = ref(false);
const openShowTicket = ref(false);
const newTicket = reactive({
  title: "",
  description: "",
});

const isDisabled = computed(() => {
  return (
    newTicket.title == ticket?.value.title &&
    newTicket.description == ticket?.value.description
  );
});

onMounted(() => {
  newTicket.title = ticket?.value.title;
  newTicket.description = ticket?.value.description;
});

async function deleteTicket() {
  try {
    isDeleting.value = true;
    let res = await useUseSanctumFetch(
      `/api/ticket/${ticket?.value.id}`,
      "DELETE"
    );

    if (res?.error.value) throw res.error.value;

    emits("deleteTicket", ticket?.value.id);
  } catch (error) {
    console.log(error);
  } finally {
    isDeleting.value = false;
  }
}

async function updateTicket() {
  const credential = {
    id: ticket?.value.id,
    title: newTicket.title ?? ticket?.value.title,
    description: newTicket.description ?? ticket?.value.description,
    board_id: boardId?.value,
    rank: ticket?.value.rank,
  };

  try {
    isUpdating.value = true;
    let res = await useUseSanctumFetch(
      `/api/ticket/${ticket?.value.id}`,
      "PUT",
      credential
    );
    if (res?.error.value) throw res.error.value;
    emits("updateTicket", credential);
  } catch (error) {
    console.log(error);
  } finally {
    isUpdating.value = false;
  }
}
</script>
