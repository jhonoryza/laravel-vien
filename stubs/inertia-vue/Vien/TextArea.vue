<script setup>
const model = defineModel({
  type: String,
  required: true,
});
</script>

<template>
  <textarea v-model="model"
    class="rounded-md border-0 py-1.5 text-gray-900 placeholder:text-gray-400 sm:text-sm sm:leading-6 shadow-sm
    ring-1 ring-inset ring-gray-300  focus:ring-2 focus:ring-inset focus:ring-indigo-600 " />
</template>