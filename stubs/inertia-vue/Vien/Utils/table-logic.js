import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import { useTableFilter } from "./table-filter";
import { useTableRow } from "./table-row";
import { useTableSort } from "./table-sort";
import { useTableToggleColumn } from "./table-toggle-column";
import { useTableAction } from "./table-action";

export function useTableLogic(props) {
  // spinner modal reference
  const isLoading = ref(false);

  const {
    selectedRows,
    selectAll,
    toggleSelectAll,
    selectAllRows,
    clearSelectedRows,
  } = useTableRow(props, isLoading);

  const {
    filter,
    resetFilter,
    onSearchInput,
    clearSearch,
    clearSearchAndFilter,
    removeFilter,
    filterCount,
    openFilter,
  } = useTableFilter(props, selectedRows, selectAll);

  const { sortKey, sortOrder, sortColumn } = useTableSort(props);

  const { columns, visibleColumns, openToggleColumn, toggleColumn } =
    useTableToggleColumn(props);

  const { openAction, confirmDeleteDialog, confirmDelete } = useTableAction();

  // page option handler section
  const pageOptionValue = ref(props.limit);
  const changePageOptions = () => {
    router.get(
      route(`${props.module}.index`),
      { limit: pageOptionValue.value },
      { preserveState: true },
    );
  };

  return {
    props,
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
  };
}
