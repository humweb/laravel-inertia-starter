<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label dark:text-gray-300" :for="id" v-html="label"></label>
        <select :id="id" ref="input" v-model="selected" v-bind="{ ...$attrs, class: null }" class="form-select dark:text-gray-400 dark:bg-gray-800" :class="{ error: error, 'py-1 text-sm': small }">
<!--            <option value="">Clear</option>-->
            <slot />
        </select>
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import randomId from '@/strings/randomid'

export default {
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `select-input-${randomId()}`
            },
        },
        error: String,
        label: String,
        small: Boolean,
        modelValue: [String, Number, Boolean],
    },
    emits: ['update:modelValue', 'selected'],
    data() {
        return {
            selected: this.modelValue,
        }
    },
    watch: {
        selected(selected) {
            this.$emit('update:modelValue', selected)
            this.$emit('selected', selected)
        },
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
    },
}
</script>
