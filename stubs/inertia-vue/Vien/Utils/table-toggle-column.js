import { computed, ref } from "vue";

// toggle column handler section
export function useTableToggleColumn(props) {
  const columns = ref(props.columns);
  const openToggleColumn = ref(false);

  // recalculate visible columns if columns is changed
  const visibleColumns = computed(() =>
    columns.value.filter((column) => column.visible),
  );

  // function to toggle column make it visible or hidden
  const toggleColumn = (key) => {
    const column = columns.value.find((col) => col.key === key);
    column.visible = !column.visible;
  };

  return {
    columns,
    visibleColumns,
    openToggleColumn,
    toggleColumn,
  };
}
