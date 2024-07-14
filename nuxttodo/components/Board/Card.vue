<template>
  <div class="w-72 h-96 border border-gray-300 shadow rounded-lg flex-none p-4">
    <div class="flex items-center justify-between select-none">
      <h1 class="text-xl font-semibold">{{ board?.title }}</h1>
      <UDropdown :items="items">
        <Icon name="mdi:plus-circle-outline" size="24" />
      </UDropdown>
    </div>

    <hr class="mt-5 border-1" />

    <div class="flex flex-col gap-3 py-5">
      <vuedraggable
        group="board"
        v-if="board.tickets"
        :list="board.tickets"
        itemKey="id"
        :scroll-sensitivity="250"
        :force-fallback="true"
        class="flex flex-col min-h-60 gap-3"
        @change="handleChange"
      >
        <template #item="{ element }">
          <TicketCard
            :ticket="element"
            :boardId="board?.id"
            :key="element.id"
            @deleteTicket="deleteTicket"
            @updateTicket="updateTicket"
          />
        </template>
      </vuedraggable>
    </div>
  </div>

  <UModal v-model="openAddTicket">
    <UCard>
      <template #header>
        <div class="flex items-center justify-between">
          <h1 class="text-xl font-semibold">Add your new ticket</h1>
          <div class="cursor-pointer" @click="openAddTicket = false">
            <Icon name="mdi:close" size="27" />
          </div>
        </div>
      </template>

      <UForm
        ref="form"
        :schema="ticketSchema"
        :state="ticketState"
        @submit="addTicket"
      >
        <UFormGroup label="Title" required>
          <UInput v-model="ticketState.title" placeholder="Title..." />
        </UFormGroup>

        <UFormGroup label="Description" required class="my-4">
          <UInput
            v-model="ticketState.description"
            placeholder="Description..."
          />
        </UFormGroup>

        <div class="float-right mb-4">
          <UButton type="submit" label="Add Ticket" :loading="isAddingTicket" />
        </div>
      </UForm>
    </UCard>
  </UModal>
</template>

<script lang="ts" setup>
import { ticketSchema, type TicketSchema } from "../../schemas/ticket.schema";
import type { FormSubmitEvent, Form, FormError } from "#ui/types";
import vuedraggable from "vuedraggable";

const props = defineProps(["board"]);
const { board } = toRefs(props);
const emits = defineEmits(["updateBoard", "deleteBoard"]);
// const form = ref<Form<TicketSchema>>();
const form = ref();

const openAddTicket = ref(false);
const isAddingTicket = ref(false);

const ticketState = reactive({
  title: "",
  description: "",
  board_id: board?.value.id,
  rank: board?.value.tickets.length + 1,
});

const items = [
  [
    {
      label: "Update board",
      icon: "i-heroicons-cog-8-tooth",
      click: () => {
        emits("updateBoard", board?.value);
      },
    },
  ],
  [
    {
      label: "Add ticket",
      icon: "i-heroicons-plus",
      click: () => {
        openAddTicket.value = true;
      },
    },
  ],
  [
    {
      label: "Delete board",
      icon: "i-heroicons-x-mark",
      click: () => {
        emits("deleteBoard", board?.value);
      },
    },
  ],
];

async function addTicket(event: FormSubmitEvent<TicketSchema>) {
  form.value.clear();
  const credential = event.data;
  try {
    isAddingTicket.value = true;
    let res = await useUseSanctumFetch("/api/ticket", "POST", credential);
    if (res?.error.value) throw res.error.value;
    board?.value.tickets.push(res?.data.value.data);
    ticketState.title = "";
    ticketState.description = "";
  } catch (error: any) {
    if (error.statusCode === 422) {
      form.value.setErrors(
        error.data.errors.map((err: any) => ({
          // Map validation errors to { path: string, message: string }
          message: err.message,
          path: err.path,
        }))
      );
    }
    console.log(error);
  } finally {
    openAddTicket.value = false;
    isAddingTicket.value = false;
  }
}

function handleChange(event: any) {
  if (event?.moved) {
    const { element, newIndex, oldIndex } = event.moved;

    if (newIndex == oldIndex) return;

    ticketMoved(element, newIndex, oldIndex);
  } else if (event?.added) {
    const { element, newIndex, oldIndex } = event.added;

    ticketMoved(element, newIndex, oldIndex, board?.value.id);
  }
}

async function ticketMoved(
  ticket: any,
  newIndex: number,
  oldIndex: number,
  boardToId?: string
) {
  if (boardToId) {
    ticket.board_id = boardToId;
  }

  ticket.rank = newIndex + 1;

  try {
    let res = await useUseSanctumFetch(
      `/api/ticket/${ticket.id}/move`,
      "POST",
      ticket
    );
    if (res?.error.value) throw res.error.value;
  } catch (error) {
    console.log(error);
  }
}

function deleteTicket(ticketId: string) {
  board?.value.tickets.forEach((el: any) => {
    if (el.id == ticketId) {
      board?.value.tickets.splice(board?.value.tickets.indexOf(el), 1);
      return;
    }
  });
}

function updateTicket(credential: any) {
  let ticket = board?.value.tickets.find((el: any) => el.id == credential.id);
  ticket.title = credential.title;
  ticket.description = credential.description;
}
</script>
