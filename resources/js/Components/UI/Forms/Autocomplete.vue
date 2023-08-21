<template>
    <div>
        <Combobox as="div" v-model="selected" :multiple="multiple" class="text-gray-700">
            <ComboboxLabel v-if="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ label }}
            </ComboboxLabel>
            <div class="relative">
                <ComboboxInput
                    class="w-full form-combo"
                    @change="query = $event.target.value"
                    :displayValue="(option) => (typeof option !== 'object' ? option : getLabel(option, true))"
                    :placeholder="placeholder">
                </ComboboxInput>
                <ComboboxButton
                    class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
                    <SelectorIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                </ComboboxButton>

                <ComboboxOptions v-if="options.length > 0"
                                 class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:text-gray-400 dark:bg-gray-800 dark:border-transparent py-1 text-base shadow-lg ring-1 dark:divide-gray-700 divide-y ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                    <slot />
                    <ComboboxOption v-for="option in options" :key="getValue(option)" :value="getValue(option)"
                                    as="template"
                                    v-slot="{ active, selected }">
                        <li :class="['relative cursor-default select-none py-2 pl-8 pr-4', active ? 'bg-indigo-600 text-white' : 'dark:text-gray-300 text-gray-900']">
                            <span :class="['block truncate', selected && 'font-semibold']">
                                <slot name="option" :option="option">
                                   <span v-html="getLabel(option)"/>
                                </slot>
                            </span>

                            <span v-if="selected"
                                  :class="['absolute inset-y-0 left-0 flex items-center pl-1.5', active ? 'text-white dark:text-gray-300' : 'text-indigo-600']">
                                <CheckIcon class="h-5 w-5" aria-hidden="true"/>
                            </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </div>
        </Combobox>
    </div>
</template>

<script>
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxLabel,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/vue';

export default {
    name: 'AutoComplete',
    components: {
        Combobox,
        ComboboxButton,
        ComboboxInput,
        ComboboxLabel,
        ComboboxOption,
        ComboboxOptions,
    },
    emits: ['update:modelValue', 'query', 'selected'],
    props: {
        modelValue: [String, Array, Object],
        options: {
            type: Array,
            default() {
                return [];
            },
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
        displayCallback: {
            type: Function,
            default: null,
        },
        valueDisplayCallback: {
            type: Function,
            default: null,
        },
        multiple: {
            type: Boolean,
            default: false,
        },
        placeholder: {
            type: String,
            default: 'Pick Item',
        },
    },
    data: () => ({
        query: '',
    }),
    methods: {
        getValue(option) {
            return typeof this.options === 'object' && this.valueKey !== 'object' ? option[this.valueKey] : option;
        },
        getLabel(option, selected = false) {

            if (selected && this.valueDisplayCallback) {
                return this.valueDisplayCallback(option);
            }
            if (this.displayCallback) {
                return this.displayCallback(option);
            }
            return typeof this.options === 'object' && this.valueKey !== 'object' ? option[this.labelKey] : option;
        },
    },
    watch: {
        query(value) {
            this.$emit('query', value);
        },
    },
    computed: {
        selected: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('selected', value);
                this.$emit('update:modelValue', value);
            },
        },

    },
};
</script>

<style scoped>

</style>
