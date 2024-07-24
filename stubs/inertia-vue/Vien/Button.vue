<template>
  <button type="button" @click="$emit('click')">
    <slot></slot>
  </button>
</template>

<script setup>
const emit = defineEmits(["click"]);
</script>

<style scoped lang="postcss">
.btn {
  @apply z-0 flex items-center rounded-md px-4 py-2 text-sm font-medium transition-colors focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2;
}

.btn-primary {
  @apply bg-slate-700 text-white hover:bg-slate-400 focus-visible:outline-slate-700;
}

.btn-secondary {
  @apply bg-slate-300 text-white hover:bg-slate-400 focus-visible:outline-slate-300;
}

.btn-neutral {
  @apply bg-slate-300 text-white hover:bg-slate-400 focus-visible:outline-slate-300;
}

.btn-destructive {
  @apply bg-red-400 text-white hover:bg-red-500 focus:ring-red-400;
}

.btn-primary-outline {
  @apply border border-slate-700 text-slate-700 hover:bg-slate-400 focus-visible:outline-slate-700;
}

.btn-neutral-outline {
  @apply border border-slate-700 text-slate-700 hover:bg-slate-400 focus-visible:outline-slate-700;
}

.btn-icon {
  @apply h-8 rounded-md px-3;
}
</style>
