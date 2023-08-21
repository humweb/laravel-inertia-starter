<template>
    <Modal
        :show="dialog"
        @close="cancel"
        :max-width="options.width"
        :closeable="false"
        @keydown.esc="cancel"
    >
        <div class="py-12 px-6 text-center">
            <div v-show="!!message" class="text-2xl dark:text-gray-300" v-html="message"></div>
            <div class="w-full mt-8 text-center">
                <button
                    class="btn btn-gray mr-2 ml-auto"
                    @click="cancel"
                >
                    <slot name="btn-cancel">
                        {{cancelLabel}}
                    </slot>
                </button>
                <button
                    class="btn btn-blue"
                    @click="agree">
                    <slot name="btn-confirm">
                        {{confirmLabel}}
                    </slot>
                </button>
            </div>
        </div>
    </Modal>

</template>

<script>
import Modal from '@/Components/UI/Modal.vue';

export default {
    name: 'ConfirmModal',
    components: {Modal},
    props:{
        cancelLabel:{
            type: String,
            default: 'Cancel'
        },
        confirmLabel:{
            type: String,
            default: 'OK'
        }
    },
    data() {
        return {
            dialog: false,
            resolve: null,
            reject: null,
            message: null,
            options: {
                width: '2xl',
                zIndex: 200,
            },
        };
    },

    methods: {
        open(message, options) {
            this.dialog = true;
            this.message = message;
            this.options = Object.assign(this.options, options);
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        agree() {
            this.resolve(true);
            this.dialog = false;
        },
        cancel() {
            this.resolve(false);
            this.dialog = false;
        },
    },
};
</script>
