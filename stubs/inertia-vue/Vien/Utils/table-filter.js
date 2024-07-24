import debounce from "./debounce";
import { computed, reactive, ref, watchEffect } from "vue";
import { router } from "@inertiajs/vue3";

// filter handler section
export function useTableFilter(props, selectedRows, selectAll) {
  const openFilter = ref(false);
  // example filter param ?filter[name]=budi
  // example filter param ?filter[search]=budi
  const filter = reactive({});

  // function to initialize default value from dynamic filter
  const updateReactiveFilters = (items) => {
    Object.keys(filter).forEach((key) => {
      delete filter[key];
    });
    items.forEach((item) => {
      filter[item] = "";
    });
  };

  // initialize default value params filter
  updateReactiveFilters(props.filters);

  /**
   * watch changed params filter
   * when user typing in filter input
   */
  props.filters.forEach((item) => {
    watchEffect(() => {
      if (filter[item] !== "") {
        debouncedFilter();
      }
    });
  });

  // input debounce filter
  const debouncedFilter = debounce(() => {
    applyFilter();
  }, 300);

  // function to apply filter
  const applyFilter = () => {
    let params = {
      filter: {},
    };
    for (const [key, value] of Object.entries(props.filters)) {
      if (filter[value] !== "") {
        params.filter[value] = filter[value];
      }
    }
    router.get(route(`${props.module}.index`), params, {
      preserveState: true,
    });
    selectAll.value = false;
    selectedRows.value = [];
  };

  // reset filter
  const resetFilter = () => {
    for (const [key, value] of Object.entries(props.filters)) {
      if (value == "search") {
        continue;
      }
      filter[value] = "";
    }
    applyFilter();
  };

  // when user typing on input search
  const onSearchInput = () => {
    debouncedFilter();
  };

  // clear search
  const clearSearch = () => {
    filter.search = "";
    applyFilter();
  };

  // clear search and filter
  const clearSearchAndFilter = () => {
    clearSearch();
    resetFilter();
  };

  // clear specific filter or clear search
  const removeFilter = (key) => {
    if (key == "search") {
      clearSearch();
    } else {
      filter[key] = "";
    }
    applyFilter();
  };

  // count applied filter
  const filterCount = computed(() => {
    let count = 0;
    for (const [key, value] of Object.entries(props.filters)) {
      if (filter[value] != "") {
        count++;
      }
    }
    return count;
  });

  return {
    filterCount,
    openFilter,
    filter,
    resetFilter,
    onSearchInput,
    clearSearch,
    clearSearchAndFilter,
    removeFilter,
  };
}
