<script setup>
import { onMounted, ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import Toast from "./Toast.vue";

const messages = ref([]);
onMounted(() => {
  if (usePage().props.flash.message) {
    messages.value.push(usePage().props.flash.message);
  }
});
watch(
  () => usePage().props.flash.message,
  (next) => {
    if (next) {
      messages.value.push(next);
    }
  },
);
</script>

<template>
  <div class="fixed top-16 right-5 z-50 flex flex-col gap-2">
    <Toast
      v-if="messages.length > 0"
      v-for="(message, index) in messages"
      :key="index"
      :message="message.message"
      :type="message.type"
    />
  </div>
</template>
