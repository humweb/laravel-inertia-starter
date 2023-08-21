<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label dark:text-gray-300" :for="id">{{ label }}</label>
      <div class="relative flex flex-grow items-stretch focus-within:z-10">
    <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }"
           class="form-input focus:ring-inset" :class="{ error: error, 'rounded-r-none ': $slots.suffix }"
           :type="type" :value="modelValue" @input="update($event.target.value)"/>
      <button v-if="$slots.suffix" type="button" class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-gray-50  text-sm font-medium text-gray-700 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-400 dark:border-y-0 dark:border-r-0 dark:border-l dark:border-gray-700 dark:hover:bg-gray-900 focus:outline-none focus:ring-0 focus:ring-blue-500">
          <slot name="suffix" />
      </button>
      </div>
    <div v-if="help" class="mt-2 text-sm font-light" :class="[error ? 'text-red-700': 'text-gray-400 dark:text-gray-500']">
      {{ help }}
    </div>
    <div v-if="error && !help" class="form-error">{{ error }}</div>
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
        return `text-input-${randomId()}`;
      },
    },
    type: {
      type: String,
      default: 'text',
    },
    error: [String, Boolean],
    help: [String, Boolean],
    label: String,
    modelValue: [String, Number],
  },
  emits: ['update:modelValue', 'updated'],
  methods: {
    update(value) {
      this.$emit('updated', value)
      this.$emit('update:modelValue', value)
    },
    focus() {
      this.$refs.input.focus();
    },
    select() {
      this.$refs.input.select();
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end);
    },
    setCursorPosition(pos) {
      pos = pos || this.modelValue.length;

      this.$refs.input.focus();
      this.$nextTick(() => {
        this.setSelectionRange(pos, pos);
      });
    },
  },
};
</script>
