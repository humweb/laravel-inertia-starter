<script setup>
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from '@headlessui/vue';
import {ChevronUpDownIcon, CheckIcon} from '@heroicons/vue/24/outline/index.js';
</script>

<script>
export default {
    name: 'ListBox',
    inheritAttrs: false,
    emits: ['update:modelValue'],
    props: {
        modelValue: [String, Array],
        error: String,
        options: {
            type: Array,
            default: () => [],
        },
        label: {
            type: String,
            default: '',
        },
        valueKey: {
            type: String,
            default: 'value',
        },
        labelKey: {
            type: String,
            default: 'label',
        },
        multiple: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        selected: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            },
        },
    },
};
</script>
<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label dark:text-gray-300" v-html="label"></label>
        <Listbox v-model="selected" :multiple="multiple" as="div" class="">
            <div class="relative mt-2">
                <ListboxButton
                    class="relative w-full cursor-default form-input">
                    <span class="block truncate">{{ selected.length ? selected.map((item) => item[labelKey]).join(', ') : '&nbsp;' }}</span>
                    <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                    </span>
                </ListboxButton>

                <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100"
                            leave-to-class="opacity-0">
                    <ListboxOptions
                        class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-700 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <ListboxOption as="template" v-for="option in options" :key="option[valueKey]"
                                       :value="option" v-slot="{ active, selected }">
                            <li :class="[active ? 'bg-indigo-600 text-white' : 'text-gray-900 dark:text-gray-300', 'relative cursor-default select-none py-2 pl-3 pr-9']">
                                <span :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">
                                    {{ option[labelKey] }}
                                </span>

                                <span v-if="selected"
                                      :class="[active ? 'text-white' : 'text-indigo-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                                    <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                                </span>
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </transition>
            </div>
        </Listbox>
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>


