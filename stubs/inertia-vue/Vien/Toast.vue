<template>
  <Transition name="fade" :duration="1000" mode="in-out">
    <div
      v-if="isVisible"
      class="border border-gray-200 dark:border-gray-100 shadow-xl rounded-lg"
    >
      <div
        class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800 dark:divide-gray-700 divide-x divide-gray-200"
      >
        <!-- icon -->
        <div
          v-if="type !== 'empty'"
          :class="{
            'bg-blue-700 dark:bg-blue-600': true,
            'inline-flex flex-shrink-0 justify-center items-center w-8 h-8 rounded-lg': true,
            'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200':
              type == 'danger',
            'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200 ':
              type == 'success',
            'text-orange-500 bg-orange-100 dark:bg-orange-700 dark:text-orange-200':
              type == 'warning',
            'text-sky-500 bg-sky-100 dark:bg-sky-700 dark:text-sky-200':
              type == 'info',
          }"
        >
          <IconInfoCircle class="size-5" v-if="type == 'info'" />
          <IconX class="size-5" v-if="type == 'danger'" />
          <IconCheck class="size-5" v-if="type == 'success'" />
          <IconAlertTriangle class="size-5" v-if="type == 'warning'" />
        </div>
        <!-- end icon -->

        <!-- message -->
        <div
          :class="[
            'text-sm font-normal pl-3',
            {
              'ml-3': type !== 'empty',
            },
          ]"
        >
          {{ message }}
        </div>
        <!-- end message -->

        <!-- close button -->
        <button
          aria-label="Close"
          class="border-none ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
          type="button"
          @click="onClose"
        >
          <span class="sr-only">Close</span>
          <IconX class="w-5 h-5" />
        </button>
        <!-- end close button -->
      </div>
    </div>
  </Transition>
</template>

<script setup>
import {
  IconAlertTriangle,
  IconCheck,
  IconInfoCircle,
  IconX,
} from "@tabler/icons-vue";
import { onMounted, ref } from "vue";
const props = defineProps({
  message: String,
  type: String,
});
const isVisible = ref(true);

const hideMessage = () => {
  isVisible.value = false;
};

onMounted(() => {
  isVisible.value = true;
  setTimeout(() => hideMessage(), 5000);
});

const onClose = () => {
  isVisible.value = false;
};
</script>
