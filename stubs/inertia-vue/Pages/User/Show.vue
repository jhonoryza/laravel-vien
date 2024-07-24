<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Container from "@/Components/Vien/Container.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { IconChevronRight } from "@tabler/icons-vue";
import { Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
  user: {
    type: Object,
  },
});
</script>
<template>
  <Head title="User Detail" />

  <AuthenticatedLayout>
    <Container>
      <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <section class="max-w-xl">
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
                @click="$inertia.get(route('users.index'))"
              >
                Users
              </h2>
              <IconChevronRight class="size-4" />
              <h2>User {{ user.id }}</h2>
            </div>
          </header>

          <div class="mt-6 space-y-6">
            <div>
              <InputLabel for="name" value="Name" />

              <TextInput
                id="name"
                v-model="user.name"
                type="text"
                class="mt-1 block w-full"
                disabled
              />
            </div>
            <div>
              <InputLabel for="email" value="Email" />

              <TextInput
                id="email"
                v-model="user.email"
                type="email"
                class="mt-1 block w-full"
                disabled
              />
            </div>
            <div>
              <InputLabel for="created" value="Created At" />

              <TextInput
                id="created"
                v-model="user.created_at"
                type="text"
                class="mt-1 block w-full"
                disabled
              />
            </div>
            <div class="flex items-center gap-4">
              <PrimaryButton @click="$inertia.get(route('users.edit', user.id))"
                >Edit
              </PrimaryButton>
            </div>
          </div>
        </section>
      </div>
    </Container>
  </AuthenticatedLayout>
</template>
