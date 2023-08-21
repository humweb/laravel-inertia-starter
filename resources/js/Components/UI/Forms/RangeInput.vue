<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label  dark:text-gray-300" :for="id">{{ label }}</label>
    <div class="flex w-full items-center">
    <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="overflow-hidden rounded-lg bg-gray-400 dark:bg-gray-500 appearance-none h-4 w-full" :class="{ error: error }" :type="type" :value="modelValue" @input="triggerUpdates" />
      <div class="text-right ml-4 w-10 dark:text-gray-400">{{modelValue}}</div>
    </div>
    <div v-if="description" class="mt-2 text-gray-500 text-sm">{{ description }}</div>
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
        return `range-input-${randomId()}`
      },
    },
    type: {
      type: String,
      default: 'range',
    },
    description: String,
    error: String,
    label: String,
    modelValue: [Number, String],
  },
  emits: ['update:modelValue', 'updated'],
  methods: {
    triggerUpdates(e){
      this.$emit('updated', e.target.value)
      this.$emit('update:modelValue', e.target.value)
    },
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
<style>
@media screen and (-webkit-min-device-pixel-ratio: 0) {

  input[type="range"]::-webkit-slider-thumb {
    width: 15px;
    -webkit-appearance: none;
    appearance: none;
    height: 15px;
    cursor: ew-resize;
    background: #FFF;
    box-shadow: -405px 0 0 400px #4f45e4;
    border-radius: 50%;

  }
}
</style>
