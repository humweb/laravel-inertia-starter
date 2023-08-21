<script setup>
import {computed} from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    label: String,
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <label class="flex items-center">
        <input
            type="checkbox"
            :value="value"
            v-model="proxyChecked"
            class="rounded border-gray-300 dark:border-gray-800 dark:bg-gray-800 text-blue-600 focus:ring-blue-500 focus:ring-opacity-50"
        />
        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
            <slot>
                {{ label }}
            </slot>
        </span>
    </label>
</template>
