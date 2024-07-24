<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Container from "@/Components/Vien/Container.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { IconChevronRight } from "@tabler/icons-vue";
import { Head } from "@inertiajs/vue3";

const passwordInput = ref(null);

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const createNewUser = () => {
  form.post(route("users.store"), {
    preserveState: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset("password", "password_confirmation");
        passwordInput.value.focus();
      }
    },
  });
};
</script>
<template>
  <Head title="Create New User" />

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
              <h2>New User</h2>
            </div>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
              Ensure this account is using a long, random password to stay
              secure.
            </p>
          </header>

          <form @submit.prevent="createNewUser" class="mt-6 space-y-6">
            <div>
              <InputLabel for="name" value="Name" />

              <TextInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                autocomplete="name"
              />

              <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div>
              <InputLabel for="email" value="Email" />

              <TextInput
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                autocomplete="email"
              />

              <InputError :message="form.errors.email" class="mt-2" />
            </div>
            <div>
              <InputLabel for="password" value="Password" />

              <TextInput
                id="password"
                ref="passwordInput"
                v-model="form.password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="password"
              />

              <InputError :message="form.errors.password" class="mt-2" />
            </div>
            <div>
              <InputLabel
                for="password_confirmation"
                value="Confirm Password"
              />

              <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password"
              />

              <InputError
                :message="form.errors.password_confirmation"
                class="mt-2"
              />
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
