<template>
  <input
    ref="inputRef"
    :value="modelValue"
    class="mt-1 block w-full rounded-md border-1 border-slate-300 px-3 py-2 text-slate-700 shadow-sm sm:text-sm sm:leading-6 focus:border-slate-300 focus:ring-slate-300 dark:border-gray-300 dark:bg-gray-900 dark:text-gray-300"
    autocomplete="off"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);

const inputRef = ref(null);

const focusInput = () => {
  inputRef.value.focus();
};

defineExpose({
  focusInput,
});
</script>
