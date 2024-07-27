<template>
    <flat-pickr
        :id="id"
        v-model="selectedDate"
        :config="config"
        @input="updateValue"
        class="rounded-md border-1 border-slate-300 px-3 py-2 text-slate-700 shadow-sm sm:text-sm sm:leading-6 focus:border-slate-300 focus:ring-slate-300 dark:border-gray-300 dark:bg-gray-900 dark:text-gray-300"
    ></flat-pickr>
</template>

<script setup>
import {defineEmits, defineProps, ref, watch} from 'vue';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Date],
        default: null,
    },
    label: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:value']);

const selectedDate = ref(props.value);
const config = ref({
    enableTime: true,
    dateFormat: 'Y-m-d H:i',
});

watch(
    () => props.value,
    (newValue) => {
        selectedDate.value = newValue;
    }
);

const updateValue = (date) => {
    emit('update:value', date);
};
</script>

<style scoped>
label {
    display: block;
    margin-bottom: 0.5em;
}
</style>
