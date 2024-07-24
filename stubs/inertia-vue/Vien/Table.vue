<script setup>
import {
  IconDotsVertical,
  IconFilterFilled,
  IconTableColumn,
  IconSearch,
  IconX,
} from "@tabler/icons-vue";
import DeleteModal from "./DeleteModal.vue";
import Checkbox from "./Checkbox.vue";
import InputText from "./InputText.vue";
import Loading from "./Loading.vue";
import Pagination from "./Pagination.vue";
import { useTableLogic } from "./Utils/table-logic";

const props = defineProps({
  title: String,
  items: {
    type: Object,
    default: () => {},
  },
  pageOptions: {
    type: Array,
    default: () => [],
  },
  limit: {
    type: Number,
    default: 10,
  },
  allIds: {
    type: Array,
    default: () => [],
  },
  columns: {
    type: Array,
    default: () => [],
  },
  module: {
    type: String,
    default: "",
    required: true,
  },
  filters: {
    type: Array,
    default: () => ["search"],
  },
  defaultSort: {
    type: String,
    default: "-id",
  },
});

const {
  openFilter,
  filter,
  filterCount,
  selectedRows,
  selectAll,
  sortKey,
  sortOrder,
  openAction,
  confirmDeleteDialog,
  columns,
  visibleColumns,
  openToggleColumn,
  pageOptionValue,
  isLoading,
  resetFilter,
  onSearchInput,
  clearSearch,
  clearSearchAndFilter,
  removeFilter,
  toggleSelectAll,
  selectAllRows,
  clearSelectedRows,
  sortColumn,
  confirmDelete,
  toggleColumn,
  changePageOptions,
} = useTableLogic(props);
</script>

<template>
  <Loading :loading="isLoading" />
  <!-- table container -->
  <div
    class="mt-4 bg-white dark:bg-gray-800 dark:text-white border border-slate-300 rounded-lg overflow-hidden"
  >
    <!-- action, search dan filter -->
    <div class="flex items-center justify-between">
      <!-- action -->
      <div class="relative flex justify-start items-center gap-2 px-4">
        <button
          class="flex items-center border border-slate-300 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-gray-700"
          v-if="selectedRows.length"
          @click="openAction = !openAction"
        >
          <IconDotsVertical class="size-6 hover:cursor-pointer text-sm" />
          <span class="text-sm font-semibold">Action</span>
        </button>
        <Transition
          enter-active-class="transition-opacity duration-500 ease-in-out"
          leave-active-class="transition-opacity duration-500 ease-in-out"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div
            v-if="openAction"
            v-click-away="() => (openAction = false)"
            class="z-10 bg-white dark:bg-gray-800 dark:text-white w-40 absolute top-11 border border-slate-300 rounded-lg p-1"
          >
            <div class="flex flex-col text-slate-700 justify-start items-start">
              <template v-if="$slots.bulkaction">
                <slot
                  name="bulkaction"
                  :selectedRows="selectedRows"
                  :confirmDelete="confirmDelete"
                  :route="route"
                  :module="props.module"
                ></slot>
              </template>
            </div>
          </div>
        </Transition>
        <template v-if="$slots.moreaction">
          <slot name="moreaction"></slot>
        </template>
      </div>
      <!-- end action -->

      <!-- toggle columns, search and filter -->
      <div class="relative flex justify-end items-center gap-2 px-4">
        <!-- search -->
        <div class="rounded-tl rounded-tr p-2">
          <label for="search" class="sr-only">Search</label>
          <div class="flex items-center align-middle relative">
            <div class="pointer-events-none absolute flex items-center pl-3">
              <IconSearch class="size-4" />
            </div>
            <InputText
              autocomplete="off"
              @input="onSearchInput"
              v-model="filter.search"
              placeholder="Search"
              name="search"
              class="w-min-sm py-2 pl-9"
              v-on:keyup.escape="clearSearch"
            />
            <div
              v-if="filter.search"
              class="absolute left-[90%] hover:cursor-pointer flex items-center"
              @click="clearSearch"
            >
              <IconX class="size-4" />
            </div>
          </div>
        </div>
        <!-- end search -->

        <!-- custom filter -->
        <div class="relative" v-if="props.filters.length > 1">
          <IconFilterFilled
            class="size-6 hover:cursor-pointer text-gray-600 hover:text-gray-400 dark:hover:text-gray-300 dark:text-gray-400"
            @click="openFilter = !openFilter"
          />
          <span
            class="absolute -top-2 -right-2 bg-slate-100 dark:bg-inherit px-1 text-xs rounded-3xl"
          >
            {{ filterCount }}
          </span>
        </div>
        <Transition
          enter-active-class="transition-opacity duration-500 ease-in-out"
          leave-active-class="transition-opacity duration-500 ease-in-out"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div
            v-if="openFilter"
            v-click-away="() => (openFilter = false)"
            class="z-10 bg-white dark:bg-gray-800 dark:text-white w-1/2 absolute top-[85%] border border-slate-300 rounded-lg p-2"
          >
            <div class="flex flex-col flex-wrap gap-2 text-slate-700">
              <div class="flex justify-between">
                <span class="font-semibold dark:text-gray-100">Filter</span>
                <button
                  type="button"
                  class="text-red-400 font-semibold hover:text-red-500 text-sm"
                  @click="resetFilter"
                >
                  Reset
                </button>
              </div>

              <template v-if="$slots.filter">
                <slot name="filter" :filter="filter"></slot>
              </template>
            </div>
          </div>
        </Transition>
        <!-- end custom filter -->

        <!-- toggle columns -->
        <IconTableColumn
          class="size-6 hover:cursor-pointer text-gray-600 hover:text-gray-400 dark:hover:text-gray-300 dark:text-gray-400"
          @click="openToggleColumn = !openToggleColumn"
        />
        <Transition
          enter-active-class="transition-opacity duration-500 ease-in-out"
          leave-active-class="transition-opacity duration-500 ease-in-out"
          enter-from-class="opacity-0"
          enter-to-class="opacity-100"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div
            v-if="openToggleColumn"
            v-click-away="() => (openToggleColumn = false)"
            class="z-10 bg-white dark:bg-gray-800 dark:text-white w-1/2 absolute top-[85%] border border-slate-300 rounded-lg p-4"
          >
            <div class="flex flex-col flex-wrap gap-4 text-slate-500 text-sm">
              <span class="font-semibold dark:text-gray-100"
                >Toggle Column</span
              >
              <div class="flex flex-col gap-2 justify-between">
                <div
                  v-for="column in columns"
                  :key="column.key"
                  class="flex gap-3 items-center"
                >
                  <Checkbox
                    :id="column.key"
                    v-model="column.visible"
                    @change="toggleColumn(column.key)"
                    class="text-slate-500 focus:ring-0"
                  />
                  <label class="block font-medium dark:text-gray-100">{{
                    column.label
                  }}</label>
                </div>
              </div>
            </div>
          </div>
        </Transition>
        <!-- end toggle columns -->

        <template v-if="$slots.createaction">
          <slot name="createaction"></slot>
        </template>
      </div>
      <!-- end toggle columns, search and filter -->
    </div>
    <!-- end search dan filter -->

    <!-- selected rows indicator -->
    <div
      class="flex justify-between items-center border-t bg-slate-100 dark:bg-gray-800 dark:text-white py-2 px-4 text-sm font-bold"
      v-if="selectedRows.length"
    >
      <span>{{ selectedRows.length }} records selected</span>
      <div class="flex justify-between items-center gap-4" v-if="items.total">
        <button @click="selectAllRows">
          <span class="text-yellow-500 hover:text-yellow-700"
            >Select all ({{ items.total }})</span
          >
        </button>
        <button @click="clearSelectedRows">
          <span class="text-red-500 hover:text-red-700">Deselect all</span>
        </button>
      </div>
    </div>
    <!-- end selected rows indicator -->

    <!-- selected filter indicator -->
    <div
      class="flex justify-between items-center border-t border-slate-300 dark:border-gray-300 bg-slate-100 py-2 px-4 dark:bg-gray-800 dark:text-white"
      v-if="route().params.filter"
    >
      <div class="text-sm flex gap-2 items-center">
        <span>Filter aktif</span>
        <div
          class="border border-slate-300 px-1 rounded"
          v-for="key in Object.keys(route().params.filter)"
          :key="key"
        >
          <div
            class="flex gap-1 justify-between items-center hover:cursor-pointer hover:text-neutral-500"
            @click="removeFilter(key)"
          >
            <span>{{ key }} : {{ route().params.filter[key] }}</span>
            <IconX class="size-4" />
          </div>
        </div>
      </div>
      <button>
        <IconX class="size-4" @click="clearSearchAndFilter"></IconX>
      </button>
    </div>
    <!-- end selected filter indicator -->

    <!-- table title section -->
    <div
      v-if="title"
      class="px-4 py-2 text-slate-700 font-bold border-t border-slate-300 bg-slate-100 dark:bg-gray-800 dark:text-white"
    >
      <h1>{{ title }}</h1>
    </div>
    <!-- end table title section -->

    <!-- table -->
    <div
      class="overflow-x-auto border-t border-slate-300"
      v-if="items.data.length"
    >
      <table class="w-full table-auto text-left text-sm">
        <!-- table head -->
        <thead>
          <tr>
            <th class="bg-slate-100 dark:bg-gray-800 dark:text-white px-4 py-2">
              <input
                type="checkbox"
                id="selectAll"
                v-model="selectAll"
                @change="toggleSelectAll"
                class="text-slate-500 h-4 w-4 rounded focus:ring-0"
              />
            </th>
            <th
              v-for="column in visibleColumns"
              :key="column.key"
              class="bg-slate-100 dark:bg-gray-800 dark:text-white px-4 py-2"
              :class="
                column.sortable
                  ? 'hover:cursor-pointer hover:text-slate-500'
                  : ''
              "
              @click="column.sortable ? sortColumn(column.key) : ''"
            >
              <span> {{ column.label }}</span>
              <span
                v-if="sortKey === column.key"
                :class="{
                  'text-red-500': sortOrder === 'desc',
                  'text-green-500': sortOrder === 'asc',
                }"
              >
                {{ sortOrder === "asc" ? "↑" : "↓" }}
              </span>
            </th>
            <th class="bg-slate-100 dark:bg-gray-800 dark:text-white px-4 py-2">
              Action
            </th>
          </tr>
        </thead>
        <!-- end table head -->

        <!-- table body -->
        <tbody class="bg-white dark:bg-gray-800 dark:text-white">
          <tr
            class="border-t border-slate-300"
            v-for="item in items.data"
            :key="item.id"
          >
            <!-- checkbox -->
            <td class="whitespace-nowrap px-4 py-2 font-medium">
              <input
                type="checkbox"
                :id="item.id"
                v-model="selectedRows"
                :value="item.id"
                class="text-slate-500 h-4 w-4 rounded focus:ring-0"
              />
            </td>

            <!-- columns item -->
            <td
              class="whitespace-nowrap px-4 py-2 font-medium"
              v-for="column in visibleColumns"
              :key="column.key"
            >
              {{ item[column.key] }}
            </td>

            <!-- actions -->
            <td class="whitespace-nowrap px-4 py-2 font-medium">
              <template v-if="$slots.rowaction">
                <slot
                  name="rowaction"
                  :item-id="item.id"
                  :confirmDelete="confirmDelete"
                  :route="route"
                  :module="props.module"
                ></slot>
              </template>
            </td>
            <!-- end actions -->
          </tr>
        </tbody>
        <!-- end table body -->
      </table>
    </div>
    <!-- end table -->

    <!-- table empty state -->
    <div
      v-if="!items.data.length"
      class="text-center p-4 h-72 flex flex-col justify-center items-center gap-2 border-t border-slate-300 dark:border-gray-300"
    >
      <IconX class="size-7"></IconX>
      <span>No Data</span>
    </div>
    <!-- end table empty state -->

    <!-- table pagination -->
    <div
      class="border-t border-slate-300 dark:border-gray-300 flex justify-between items-center px-4 py-2 dark:text-white"
      v-if="items.data.length"
    >
      <span
        class="text-sm hidden md:block"
        v-if="items.total && items.from && items.to"
      >
        Showing {{ items.from }} to {{ items.to }} of {{ items.total }} entries
      </span>
      <span
        class="text-sm hidden md:block"
        v-else-if="items.total == undefined && items.from && items.to"
      >
        Showing {{ items.from }} to {{ items.to }}
      </span>
      <div
        class="rounded-lg text-sm grid grid-cols-2 divide-x-4 divide-slate-400 items-center justify-evenly"
      >
        <span class="hidden sm:block">Showing</span>
        <select
          v-model="pageOptionValue"
          @change="changePageOptions"
          class="hidden sm:block border-none focus:ring-0 dark:bg-gray-800"
        >
          <option v-for="option in pageOptions" :key="option" :value="option">
            <span class="text-sm">{{ option }}</span>
          </option>
        </select>
      </div>
      <Pagination
        :next-page-url="items.next_page_url"
        :prev-page-url="items.prev_page_url"
        :links="items.links"
      />
    </div>
    <!-- end table pagination -->
  </div>
  <!-- end table container -->

  <!-- confirm delete dialog -->
  <DeleteModal ref="confirmDeleteDialog" />
  <!-- end confirm delete dialog -->
</template>
