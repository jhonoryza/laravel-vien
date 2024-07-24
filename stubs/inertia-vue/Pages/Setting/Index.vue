<script setup>
import Table from "@/Components/Vien/Table.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import {
  IconTrash,
  IconEdit,
  IconFilePlus,
  IconEye,
  IconChevronRight,
} from "@tabler/icons-vue";
import InputText from "@/Components/Vien/InputText.vue";
import Container from "@/Components/Vien/Container.vue";

const props = defineProps({
  table: {
    type: Object,
    default: () => {},
  },
});
</script>

<template>
  <Head title="List Setting" />

  <AuthenticatedLayout>
    <Container>
      <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <section class="max-w-screen-xl">
          <header>
            <div
              class="flex gap-2 items-center text-sm font-bold text-zinc-600 dark:text-zinc-100"
            >
              <h2
                class="hover:text-zinc-900 hover:cursor-pointer"
                @click="$inertia.get(route('dashboard'))"
              >
                Home
              </h2>
              <IconChevronRight class="size-4" />
              <h2
                class="hover:text-zinc-900 hover:cursor-pointer"
                @click="$inertia.get(route('settings.index'))"
              >
                Settings
              </h2>
            </div>
          </header>
          <Table
            :items="table.settings"
            :page-options="table.pageOptions"
            :limit="table.limit"
            :all-ids="table.allIds"
            :columns="table.columns"
            :filters="table.filters"
            :default-sort="table.defaultSort"
            module="settings"
            title="Settings Table"
          >
            <!-- bulk action slot -->
            <template
              #bulkaction="{ selectedRows, confirmDelete, route, module }"
            >
              <button
                type="button"
                class="text-sm hover:bg-slate-100 dark:hover:bg-gray-700 dark:text-white rounded-lg flex gap-2 justify-start items-center p-2 w-full"
                @click="
                  () => {
                    const ids = { ids: selectedRows };
                    confirmDelete(
                      route(`${module}.bulk-delete`, ids),
                      `Delete item id: ${selectedRows}`,
                    );
                  }
                "
              >
                <IconTrash class="size-4" />
                <span>Bulk Delete</span>
              </button>
            </template>
            <!-- end bulk action slot -->

            <!-- more action slot -->
            <template #moreaction> </template>
            <!-- end more action slot -->

            <!-- create action slot -->
            <template #createaction>
              <!-- create record -->
              <button
                type="button"
                class="flex items-center hover:cursor-pointer text-gray-600 hover:text-gray-400 dark:hover:text-gray-300 dark:text-gray-400"
                @click="$inertia.get(route(`settings.create`))"
              >
                <IconFilePlus class="size-6" />
                <span>New</span>
              </button>
              <!-- end create record -->
            </template>
            <!-- end create action slot -->

            <!-- row action slot -->
            <template #rowaction="{ itemId, confirmDelete, route, module }">
              <!-- view item -->
              <div class="inline-block mr-2">
                <button
                  type="button"
                  class="flex gap-1 justify-center items-center min-h-8 hover:text-slate-400"
                  @click="$inertia.visit(route(`${module}.show`, itemId))"
                >
                  <IconEye class="size-4" />
                  View
                </button>
              </div>
              <!-- edit item -->
              <div class="inline-block mr-2">
                <button
                  type="button"
                  class="flex gap-1 justify-center items-center min-h-8 hover:text-yellow-400"
                  @click="$inertia.visit(route(`${module}.edit`, itemId))"
                >
                  <IconEdit class="size-4" />
                  Edit
                </button>
              </div>

              <!-- delete item -->
              <div class="inline-block mr-2">
                <button
                  type="button"
                  class="flex gap-1 justify-center items-center min-h-8 hover:text-red-400"
                  @click="
                    confirmDelete(
                      route(`${module}.destroy`, itemId),
                      `Delete item id ${itemId}`,
                    )
                  "
                >
                  <IconTrash class="size-4" />
                  Delete
                </button>
              </div>
            </template>
            <!-- end row action slot -->

            <!-- filter slot -->
            <template #filter="{ filter }">
              <label
                class="block font-medium text-sm dark:text-gray-100"
                for="key"
                >Key</label
              >
              <InputText v-model="filter.key" />
              <label
                class="block font-medium text-sm dark:text-gray-100"
                for="value"
                >Value</label
              >
              <InputText v-model="filter.value" />
            </template>
            <!-- end filter slot -->
          </Table>
        </section>
      </div>
    </Container>
  </AuthenticatedLayout>
</template>
