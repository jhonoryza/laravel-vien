<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Container from "@/Components/Vien/Container.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { IconChevronRight } from "@tabler/icons-vue";
import { Head } from "@inertiajs/vue3";

const form = useForm({
  key: "",
  value: "",
});

const createNewSetting = () => {
  form.post(route("settings.store"), {
    preserveState: true,
    onSuccess: () => form.reset(),
  });
};
</script>
<template>
  <Head title="Create New Setting" />

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
                @click="$inertia.get(route('settings.index'))"
              >
                Settings
              </h2>
              <IconChevronRight class="size-4" />
              <h2>New Setting</h2>
            </div>
          </header>

          <form @submit.prevent="createNewSetting" class="mt-6 space-y-6">
            <div>
              <InputLabel for="key" value="Key" />

              <TextInput
                id="key"
                v-model="form.key"
                type="text"
                class="mt-1 block w-full"
                autocomplete="key"
              />

              <InputError :message="form.errors.key" class="mt-2" />
            </div>
            <div>
              <InputLabel for="value" value="Value" />

              <TextInput
                id="value"
                v-model="form.value"
                type="text"
                class="mt-1 block w-full"
                autocomplete="value"
              />

              <InputError :message="form.errors.value" class="mt-2" />
            </div>
            <div class="flex items-center gap-4">
              <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

              <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
              >
                <p
                  v-if="form.recentlySuccessful"
                  class="text-sm text-gray-600 dark:text-gray-400"
                >
                  Saved.
                </p>
              </Transition>
            </div>
          </form>
        </section>
      </div>
    </Container>
  </AuthenticatedLayout>
</template>
