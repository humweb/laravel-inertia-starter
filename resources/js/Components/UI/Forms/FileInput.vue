<template>
    <div>
        <label v-if="label" class="form-label dark:text-gray-300" v-html="label"></label>
        <div class="form-input dark:text-gray-400 dark:bg-gray-900 p-0 w-full" :class="{ error: errors.length }">
            <input ref="file" type="file" :accept="accept" class="hidden" :multiple="multiple" @change="change"/>
            <div v-if="!modelValue" class="p-2 w-full">
                <button type="button"
                        class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white"
                        @click="browse">
                    Browse
                </button>
            </div>
            <div v-else class="flex items-center justify-between p-2">
                <div class="flex-1 pr-1">
                    <div v-for="file in modelValue">
                    {{ file.name }}
                    <span class="text-gray-500 text-xs">({{ filesize(file.size) }})</span>
                    </div>
                </div>
                <button type="button"
                        class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white"
                        @click="remove">
                    Remove
                </button>
            </div>
        </div>
        <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>
    </div>
</template>

<script>
export default {
    props: {
        modelValue: [File, String, Object, Array],
        label: String,
        multiple: Boolean,
        accept: String,
        errors: {
            type: Array,
            default: () => [],
        },
    },
    emits: ['update:modelValue', 'preview'],
    watch: {
        modelValue(value) {
            if (!value) {
                this.$refs.file.value = '';
            }
        },
    },
    methods: {
        filesize(size) {
            var i = Math.floor(Math.log(size) / Math.log(1024));
            return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
        },
        browse() {
            this.$refs.file.click();
        },
        change(e) {
            this.$emit('preview', e.target.files);
            this.$emit('update:modelValue', e.target.files);
        },
        remove() {
            this.$emit('update:modelValue', null);
        },
    },
};
</script>
