import { ref } from "vue";

// select row handler section
export function useTableRow(props, isLoading) {
  const selectedRows = ref([]);
  const selectAll = ref(false);

  // select all visible rows
  const toggleSelectAll = () => {
    if (selectAll.value) {
      selectedRows.value = props.items.data.map((item) => item.id);
    } else {
      selectedRows.value = [];
    }
  };

  // select all rows even if it is not visible
  const selectAllRows = async () => {
    if (selectedRows.value.length < props.allIds.length) {
      isLoading.value = true;
      await new Promise((r) => setTimeout(r, 100));
      selectAll.value = true;
      selectedRows.value = props.allIds;
      await new Promise((r) => setTimeout(r, 100));
      isLoading.value = false;
    }
  };

  // clear all selected rows
  const clearSelectedRows = () => {
    selectedRows.value = [];
    selectAll.value = false;
  };

  return {
    selectedRows,
    selectAll,
    toggleSelectAll,
    selectAllRows,
    clearSelectedRows,
  };
}
