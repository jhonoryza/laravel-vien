<script setup>
import { onMounted, ref } from "vue";
import { IconMoon, IconSun } from "@tabler/icons-vue";

const colorMode = ref(localStorage.getItem("color-mode") || "light");

onMounted(() => {
  const rootElement = document.documentElement;
  if (colorMode.value === "light") {
    rootElement.classList.remove("dark");
  } else {
    rootElement.classList.add("dark");
  }
});

const toggleColorMode = () => {
  colorMode.value = colorMode.value === "light" ? "dark" : "light";
  localStorage.setItem("color-mode", colorMode.value);
  const rootElement = document.documentElement;
  if (colorMode.value === "light") {
    rootElement.classList.remove("dark");
  } else {
    rootElement.classList.add("dark");
  }
};
</script>

<template>
  <button
    class="inline-flex items-center p-2 focus:outline-none rounded-md"
    @click="toggleColorMode"
  >
    <IconSun class="size-4" v-if="colorMode === 'light'" />
    <IconMoon class="size-4 stroke-white" v-else />
  </button>
</template>
