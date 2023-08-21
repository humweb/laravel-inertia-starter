<script setup>
import {computed, useSlots} from 'vue';

const props = defineProps({
    noPadding: Boolean,
    isForm: Boolean,
    footerJustifyEnd: Boolean,
});

const emit = defineEmits(['submit']);

const slots = useSlots();

const hasHeaderSlot = computed(() => slots.header && !!slots.header());
const hasFooterSlot = computed(() => slots.footer && !!slots.footer());

const submit = (event) => {
    emit('submit', event);
};
</script>

<template>
    <component
        :is="isForm ? 'form' : 'div'"
        class="card"
        @submit="submit"
    >
        <div v-if="hasHeaderSlot" class="card-header">
            <slot name="header"/>
        </div>
        <div class="card-body" :class="{'p-0': noPadding}">
            <slot/>
        </div>
        <div v-if="hasFooterSlot" class="card-footer" :class="{'justify-end': footerJustifyEnd}">
            <slot name="footer"/>
        </div>
    </component>
</template>
