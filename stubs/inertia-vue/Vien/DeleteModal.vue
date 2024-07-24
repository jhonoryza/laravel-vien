<script setup>
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const confirmingDeletion = ref(false);
const deleteItemRoute = ref(null);
const title = ref("Are you sure you want to delete ?");

const openModal = (deleteRoute, message) => {
  deleteItemRoute.value = deleteRoute;
  confirmingDeletion.value = true;
  title.value = message ? message : title.value;
};
const closeModal = () => {
  deleteItemRoute.value = null;
  confirmingDeletion.value = false;
};

const form = useForm({});

const deleteItem = () => {
  // router.visit(deleteItemRoute.value, {
  //   method: "delete",
  // });
  form.delete(deleteItemRoute.value, {
    preserveState: true,
  });
  closeModal();
};
defineExpose({
  openModal,
});
</script>

<template>
  <Modal :show="confirmingDeletion" @close="closeModal()">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Are you sure want to proceed ?
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        {{ title }}
      </p>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeModal()"> Cancel </SecondaryButton>

        <DangerButton class="ms-3" @click="deleteItem()">
          Confirmed
        </DangerButton>
      </div>
    </div>
  </Modal>
</template>
