<template>
    <div>
        <div class="flex items-center">
            <div class="form-input flex items-center">
                <div v-if="!$slots.tags && tags.length" class="inline">
                    <span v-for="(tag, index) in tags"
                          :key="tag"
                          class="inline-flex my-1 mr-4 items-center rounded bg-indigo-700 text-white px-4 py-0.5">
                        {{ tag }}
                        <XIcon class=" ml-3 w-4 h-4 hover:cursor-pointer" @click="remove(index)"/>
                    </span>
                </div>
                <input type="text"
                       ref="input"
                       v-model="text"
                       @keydown.enter="addTag($event.target.value)"
                       :placeholder="placeholder"
                       class="bg-transparent flex flex-grow border-none outline-none py-0 px-0 mx-0 h-10 -my-2 ring-0 focus:ring-0">
            </div>
            <div><button class="btn btn-sm btn-slate inline-flex ml-2" @click="addTag($refs.input.value)">Add</button></div>
        </div>
        <slot name="tags" :tags="tags" :remove="remove"></slot>
    </div>
</template>

<script>
export default {
    name: 'TagInput',
    emits: ['update:modelValue'],
    props: {
        modelValue: Array,
        valueField: {
            type: String,
            default: 'key',
        },
        labelField: {
            type: String,
            default: 'label',
        },
        autocomplete: Boolean,
        items: {
            type: Array,
            default: () => [],
        },
        objectValue: Boolean,
        placeholder: {
            type: String,
            default: 'Add tag',
        },
    },
    data: () => ({

        text: '',
    }),
    methods: {
        addTag(value) {
            this.tags.push(value);
            this.text = '';
        },
        remove(index) {
            this.tags.splice(index, 1);
        },
        getLabel(item) {
            return typeof item === 'object' ? item[this.labelField] : item;
        },
        getValue(item) {
            return !this.objectValue && typeof item === 'object' ? item[this.valueField] : item;
        },
    },
    computed: {
        tags: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            },
        },
    },
};
</script>

<style scoped>

</style>
