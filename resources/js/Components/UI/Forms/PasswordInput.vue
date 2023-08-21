<template>
    <div :class="$attrs.class">
        <TextInput
                   :label="label"
                   :id="id"
                   :type="type"
                   :modelValue="modelValue"
                   :error="error"
                   :help="help"
                   class="focus:outline-none focus:ring-0 focus:ring-blue-500 group-active:outline"
                   @input="update($event.target.value)">
            <template #suffix>
                <button class="py-2 px-4" title="Show password" @click.prevent="toggle">
                    <component :is="type === 'password' ? 'EyeIcon': 'EyeSlashIcon'" class="w-4 h-4"></component>
                </button>
            </template>
        </TextInput>
    </div>
</template>

<script>

import randomId from '@/strings/randomid';
import {TextInput} from '@/Components/UI/Forms';
import {EyeIcon, EyeSlashIcon} from '@heroicons/vue/24/outline';

export default {
    name: 'PasswordInput',
    inheritAttrs: false,
    props: {
        id: {
            type: String,
            default() {
                return `password-input-${randomId()}`;
            },
        },
        error: [String, Boolean],
        help: [String, Boolean],
        label: String,
        modelValue: [String, Number],
    },
    data() {
        return {
            type: 'password',
        };
    },
    components: {TextInput, EyeIcon, EyeSlashIcon},
    emits: ['update:modelValue', 'updated'],
    methods: {
        update(value) {
            this.$emit('updated', value);
            this.$emit('update:modelValue', value);
        },
        toggle() {
            this.type = this.type === 'password' ? 'text' : 'password';
        },
    },
};
</script>
