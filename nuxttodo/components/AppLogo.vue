<template>
  <div>
    <NuxtLink to="/" class="font-black text-green-400" :class="size"
      >NuxtTodo</NuxtLink
    >
  </div>
</template>

<script lang="ts" setup>
const props = defineProps(["size"]);
const { size } = toRefs(props);
</script>
