<template>
    <Listbox as="div" v-model="model">
        <div class="relative mt-2">
            <ListboxButton
                class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <span class="block truncate">{{ model.value }}</span>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <IconChevronDown class="h-5 w-5 text-gray-400" aria-hidden="true"/>
        </span>
            </ListboxButton>

            <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100"
                        leave-to-class="opacity-0">
                <ListboxOptions
                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                    <ListboxOption as="template" v-for="item in items" :key="item.id" :value="item"
                                   v-slot="{ active, model }">
                        <li :class="[active ? 'bg-indigo-600 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-3 pr-9']">
                            <span
                                :class="[model ? 'font-semibold' : 'font-normal', 'block truncate']">{{
                                    item.value
                                }}</span>

                            <span v-if="model"
                                  :class="[active ? 'text-white' : 'text-indigo-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                <IconCheck class="h-5 w-5" aria-hidden="true"/>
              </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>
</template>

<script setup>
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from '@headlessui/vue'
import {IconCheck, IconChevronDown} from '@tabler/icons-vue'

defineProps({
    items: {
        type: Object,
        default: () => [],
    },
})

const model = defineModel({
    required: true,
});
</script>
