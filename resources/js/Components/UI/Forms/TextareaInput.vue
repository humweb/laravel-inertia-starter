<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label dark:text-gray-300" :for="id">{{ label }}</label>
    <textarea :id="id" ref="input"
              v-bind="{ ...$attrs, class: null }"
              class="form-textarea dark:text-gray-400 dark:bg-gray-900"
              :class="{ error: error }"
              :value="modelValue"
              @input="$emit('update:modelValue', $event.target.value)"
              :rows="rows"
    />
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
        return `textarea-input-${randomId()}`
      },
    },
    rows: {
      type: [Number, String],
      default: 3
    },
    type: {
      type: String,
      default: 'text',
    },
    error: String,
    label: String,
    modelValue: String,
  },
  emits: ['update:modelValue'],
  methods: {
    focus() {
      this.$refs.input.focus()
    },
    select() {
      this.$refs.input.select()
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end)
    },
  },
}
</script>
