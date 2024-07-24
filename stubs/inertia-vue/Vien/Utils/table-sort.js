import { router } from "@inertiajs/vue3";
import { ref } from "vue";

// sorting handler section
export function useTableSort(props) {
  // example sort param ?sort=-created_at
  const defaultSorts = props.defaultSort.split("-");
  const sortKey = ref(defaultSorts[1] || defaultSorts[0]);
  const sortOrder = ref(defaultSorts.length > 1 ? "desc" : "asc");

  // function to sort column
  const sortColumn = (key) => {
    if (sortKey.value === key) {
      sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    } else {
      sortKey.value = key;
      sortOrder.value = "asc";
    }
    const sort =
      sortOrder.value === "desc" ? `-${sortKey.value}` : sortKey.value;
    router.get(
      route(`${props.module}.index`),
      { sort: sort },
      { preserveState: true },
    );
  };

  return {
    sortKey,
    sortOrder,
    sortColumn,
  };
}
