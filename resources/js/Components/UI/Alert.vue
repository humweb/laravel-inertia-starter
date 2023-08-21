<template>
    <transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
    <div v-if="show" :class="['alert', style.bgColor]">
        <div class="flex-shrink-0">
            <CheckCircleIcon v-if="level == 'success'" class="h-5 w-5"/>
            <InformationCircleIcon v-if="level == 'info'||level == 'primary'" class="h-5 w-5"/>
            <ExclamationCircleIcon v-if="level == 'warning'" class="h-5 w-5"/>
            <XCircleIcon v-if="level == 'danger'" class="h-5 w-5"/>
        </div>
        <div class="ml-3 items-center">
            <h3 v-if="$slots.title" class="mb-1 text-sm font-semibold" :class="style.titleColor">
                <slot name="title"/>
            </h3>
            <div class="text-sm">
                <slot/>
            </div>
        </div>
        <div v-if="closeable" class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button"
                        @click="close"
                        class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-transparent focus:ring-transparent">
                    <span class="sr-only">Dismiss</span>
                    <XMarkIcon class="h-5 w-5" aria-hidden="true"/>
                </button>
            </div>
        </div>
    </div>
    </transition>
</template>

<script>

import {CheckCircleIcon, InformationCircleIcon, XCircleIcon, XMarkIcon, ExclamationCircleIcon} from '@heroicons/vue/24/outline';

export default {
    name: 'AlertBox',
    emits: ['close'],
    components: {
        CheckCircleIcon, InformationCircleIcon, XCircleIcon, XMarkIcon,ExclamationCircleIcon
    },
    props: {
        level: {
            type:String,
            default: 'primary'
        },
        autoClose: Boolean,
        timeout: Number,
        closeable: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            show: true,
            timer: null,
        };
    },
    mounted() {
        if (this.autoClose) {
            this.timer = setTimeout(() => {
                this.close();
            }, this.timeout || 3000);
        }
    },

    methods: {
        close() {
            this.show = false
            clearTimeout(this.timer);
            this.$emit('close');
        },
    },
    computed: {
        style() {
            switch (this.level) {
                case 'success':
                    return {
                        bgColor: 'alert-success',
                        titleColor: 'text-green-100 dark:text-green-200',
                    };

                case 'danger':
                    return {
                        bgColor: 'alert-danger',
                        titleColor: 'text-red-100 dark:text-red-200',
                    };
                case 'warning':
                    return {
                        bgColor: 'alert-warning',
                        titleColor: 'text-orange-100 dark:text-red-200',
                    };

                case 'secondary':
                    return {
                        bgColor: 'alert-secondary',
                        titleColor: 'text-gray-100',
                    };
                default:
                case 'primary':
                case 'info':
                    return {
                        bgColor: 'alert-info',
                        titleColor: 'text-blue-100 dark:text-blue-200',
                    };
            }

        },
    },
};
</script>

<style scoped>

</style>
