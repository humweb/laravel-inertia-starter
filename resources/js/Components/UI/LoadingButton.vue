<template>
    <button :disabled="loading || disabled" class="btn">
        <div v-if="loading" class="btn-spinner mr-2"/>
        <slot/>
    </button>
</template>

<script>
export default {
    name: 'LoadingButton',
    props: {
        disabled: Boolean,
        loading: Boolean,
    },
};
</script>
