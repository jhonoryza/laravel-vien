<script setup>
import { Link } from "@inertiajs/vue3";
import SideBarSubMenuItem from "./SideBarSubMenuItem.vue";
defineProps({
  item: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <!-- jika tidak mempunyai sub menu -->
  <template v-if="!item.children">
    <Link
      :method="item.method ? item.method : 'get'"
      :as="item.method ? 'button' : 'a'"
      :href="route(item.routeName)"
      :class="[
        'flex w-full gap-2 py-2 px-4 rounded mb-2',
        $page.component === item.component
          ? 'bg-gray-100 dark:bg-gray-700'
          : 'hover:bg-gray-100 dark:hover:bg-gray-700',
        item.class,
      ]"
    >
      <component v-if="item.icon" :is="item.icon" class="size-5" />
      {{ item.title }}
    </Link>
  </template>
  <!-- jika mempunyai sub menu -->
  <template v-else>
    <SideBarSubMenuItem :item="item" />
  </template>
</template>
