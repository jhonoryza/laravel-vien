<template>
  <div>
    <button
      @click="toggleSubmenu"
      class="flex items-center justify-between w-full py-2 px-4 mb-2 rounded focus:outline-none hover:bg-gray-100 hover:dark:bg-gray-700"
      :class="item.class"
    >
      <span>{{ item.title }}</span>
      <svg
        class="w-4 h-4 transition-transform"
        :class="{ 'rotate-180': isOpen }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M19 9l-7 7-7-7"
        ></path>
      </svg>
    </button>
    <ul v-show="isOpen" class="mx-4 mb-2">
      <li v-for="child in item.children" :key="child.id">
        <SideBarMenuItem :item="child" />
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import SideBarMenuItem from "./SideBarMenuItem.vue";

const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
});

onMounted(() => {
  const active = checkActive(props.item);
  if (active) {
    toggleSubmenu();
  }
});

const page = usePage();
const isOpen = ref(false);

function containSamePrefix(first, second) {
  if (first.split("/")[0] == second.split("/")[0]) {
    return true;
  }
  return false;
}

function checkActive(item) {
  if (item.component) {
    if (page.component === item.component) {
      return true;
    }
    if (containSamePrefix(page.component, item.component)) {
      return true;
    }
  }
  if (item.children) {
    return item.children.some((child) => checkActive(child));
  }
  return false;
}

const toggleSubmenu = () => {
  isOpen.value = !isOpen.value;
};
</script>
