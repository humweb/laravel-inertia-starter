<template>
    <MenuItem :class="styleClass">
        <component
            :is="'href' in $attrs ? 'Link' : 'button'"
            v-bind="{ ...$attrs}"
            class="block w-full text-left px-4 py-2 text-sm hover:bg-blue-500 hover:text-gray-50">
            <slot/>
        </component>
    </MenuItem>
</template>

<script>
import {MenuItem} from '@headlessui/vue';

export default {
    inheritAttrs: false,
    name: 'DropdownItem',
    props:{
        styleClass:{
            type: String,
            default: 'inline-flex items-center'
        }
    },
    components: {
        MenuItem
    },
};
</script>

<style scoped>

</style>
