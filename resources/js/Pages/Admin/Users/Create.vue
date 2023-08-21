<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="">
            <div class="mx-auto">

                <form @submit.prevent="submit" class="card">
                    <div class="card-body pb-12 rounded-t-lg">
                        <div>

                            <TextInput v-model="form.name"
                                       type="text"
                                       label="Name"
                                       class="mt-1 block w-full"
                                       required
                                       autofocus/>
                        </div>
                        <div class="mt-4">
                            <TextInput v-model="form.email"
                                       type="email"
                                       label="Email"
                                       class="mt-1 block w-full"
                                       required
                                       autofocus/>
                        </div>
                        <div class="mt-4">
                            <PasswordInput
                                v-model="form.password"
                                type="password"
                                label="Password"
                                class="mb-4 block w-full"
                                required
                                autocomplete="current-password"/>
                            <PasswordInput
                                label="Confirm Password"
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                :error="form.errors.password_confirmation"
                            />
                        </div>
                        <ListBox v-model="form.roles" label="Roles" :options="roles" valueKey="id" labelKey="name" multiple></ListBox>
                    </div>
                    <div class="card-footer justify-end">
                        <button class="btn btn-gray mr-4" @click="$inertia.visit(route('admin.users.index'))">
                            Cancel
                        </button>
                        <button class="btn btn-blue" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {PasswordInput, TextInput, ComboBox} from '@/Components/UI/Forms';
import ListBox from '@/Components/UI/Forms/ListBox.vue';</script>

<script>

export default {
    components: {},
    props: {
        roles: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                password: '',
                password_confirmation: '',
                email: '',
                roles: [],
            }),
        };
    },

    methods: {
        submit() {
            this.form.transform(data => ({
                ...data,
            })).post(this.route('admin.users.store'), {
                onFinish: () => this.form.reset('password'),
            });
        },
    },
    computed: {
        breadcrumbs() {
            return [
                {label: 'Users', url: route('admin.users.index')},
                {label: 'Create'},
            ];
        },
    },
};
</script>
