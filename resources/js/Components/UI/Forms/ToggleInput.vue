<template>
    <SwitchGroup as="div" class="flex items-center" :class="$attrs.class">
        <Switch v-model="proxyChecked"
                :class="[modelValue ? activeColor.bg : 'bg-gray-200 dark:bg-gray-700', styles.container, 'border relative inline-flex flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2', activeColor.ring]">
            <span class="sr-only">Use setting</span>
            <span aria-hidden="true"
                  :class="[modelValue ? styles.translate : 'translate-x-0', styles.switch, 'pointer-events-none inline-block transform rounded-full bg-white dark:bg-gray-300 shadow ring-0 transition duration-200 ease-in-out']"/>
        </Switch>
        <SwitchLabel v-if="label" as="span" class="ml-3">
            <span class=" font-medium text-gray-700 dark:text-gray-200"
            :class="[styles.label]">
                {{ label }}
            </span>
        </SwitchLabel>
    </SwitchGroup>
</template>

<script>
import {Switch, SwitchGroup, SwitchLabel} from '@headlessui/vue';
import randomId from '@/strings/randomid';

export default {
    inheritAttrs: false,
    components: {
        Switch,
        SwitchGroup,
        SwitchLabel,
    },
    props: {
        id: {
            type: String,
            default() {
                return `toggle-input-${randomId()}`;
            },
        },
        toggleLabels: {
            type: Array,
            default: () => ['Off', 'On'],
        },
        size: {
            type: String,
            default: 'md',
        },
        color: {
            type: String,
            default: 'blue',
        },
        help: String,
        error: String,
        label: String,
        modelValue: Boolean,
    },
    emits: ['update:modelValue', 'updated'],

    methods: {
        toggle() {
            this.$emit('updated', !this.val);
            this.$emit('update:modelValue', !this.val);
        },
    },
    computed: {
        proxyChecked: {
            get() {
                return this.modelValue;
            },

            set(val) {
                this.$emit('update:modelValue', val);
                this.$emit('updated', val);
            },
        },
        activeColor(){
            switch (this.color) {
                case 'green':
                    return {
                        bg: 'bg-green-600',
                        ring: 'focus:ring-green-500'
                    }
                    case 'red':
                    return {
                        bg: 'bg-red-600',
                        ring: 'focus:ring-red-500'
                    }
                    case 'indigo':
                    return {
                        bg: 'bg-indigo-600',
                        ring: 'focus:ring-indigo-500'
                    }
                case 'blue':
                default:
                    return {
                        bg: 'bg-blue-600',
                        ring: 'focus:ring-blue-500'
                    }
            }
        },
        styles() {
            switch (this.size) {
                case 'sm':
                    return {
                        label: 'text-sm',
                        container: 'h-5 w-9',
                        switch: 'h-4 w-4',
                        translate: 'translate-x-4'
                    };
                case 'lg':
                    return {
                        label: 'text-normal',
                        container: 'h-7 w-14',
                        switch: 'h-6 w-6',
                        translate: 'translate-x-7'
                    };
                case 'xl':
                    return {
                        label: 'text-lg',
                        container: 'h-8 w-16',
                        switch: 'h-7 w-7',
                        translate: 'translate-x-8'
                    };

                default:
                case 'md':
                    return {
                        label: 'text-normal',
                        container: 'h-6 w-11',
                        switch: 'h-5 w-5',
                        translate: 'translate-x-5'
                    };
            }
        },
    },
};
</script>
