<script setup>
import SideBarMenuItem from "./SideBarMenuItem.vue";
import { useMenuItems } from "./Utils/menu-items";
const { menuItems } = useMenuItems();
</script>

<template>
  <aside
    class="absolute top-0 left-0 z-40 w-64 h-screen transition-transform"
    aria-label="Sidebar"
  >
    <div
      class="h-full px-3 overflow-y-auto bg-white dark:bg-gray-800 text-sm dark:text-white"
    >
      <ul class="font-medium flex flex-col">
        <li v-for="item in menuItems" :key="item.id">
          <SideBarMenuItem :item="item" />
        </li>
      </ul>
    </div>
  </aside>
</template>
