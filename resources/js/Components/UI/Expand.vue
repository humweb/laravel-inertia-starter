<template>
    <div>
        <div @click="open = ! open" class="hover:cursor-pointer flex justify-between items-center">
            <div class="flex items-center">
                <slot name="trigger" :open="open"></slot>
            </div>
            <ChevronDownIcon class="w-5 h-5"></ChevronDownIcon>
        </div>

        <transition
            appear
            enter-active-class="transform transition duration-300 ease-in"
            enter-class="-translate-y-1/2 scale-y-0 opacity-0"
            enter-to-class="translate-y-0 scale-y-100 opacity-100"
            leave-active-class="transform transition duration-300 ease-out"
            leave-class="translate-y-0 scale-y-100 opacity-100"
            leave-to-class="-translate-y-1/2 scale-y-0 opacity-0">
            <div v-show="open">
                <slot/>
            </div>
        </transition>
    </div>
</template>

<script>

import {ChevronDownIcon} from '@heroicons/vue/24/outline';

export default {
    name: 'Expand',
    components: {
        ChevronDownIcon,
    },
    data() {
        return {
            open: false,
        };
    },

};
</script>

<style scoped>

</style>
