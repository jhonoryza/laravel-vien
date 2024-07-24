import { ref } from "vue";

// action and confirm dialog handler section
export function useTableAction() {
  // bulk action button state
  const openAction = ref(false);
  // reference to delete modal component -> DeleteModal.vue
  const confirmDeleteDialog = ref(null);

  const confirmDelete = (deleteRoute, message) => {
    confirmDeleteDialog.value.openModal(deleteRoute, message);
  };
  return {
    openAction,
    confirmDeleteDialog,
    confirmDelete,
  };
}
