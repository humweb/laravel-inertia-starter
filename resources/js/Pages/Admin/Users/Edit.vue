<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto">

            <form class="card rounded-lg" @submit.prevent="submit">
                <div class="card-body">
                    <div>
                        <TextInput
                            label="Name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            :errors="form.errors.name"
                        />
                    </div>
                    <div class="mt-4">
                        <TextInput
                            label="Email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            :errors="form.errors.email"
                        />
                    </div>
                    <div class="mt-4">
                        <PasswordInput
                            label="Password"
                            type="password"
                            v-model="form.password"
                            :errors="form.errors.password"
                        />
                    </div>
                </div>
                <div class="card-footer justify-end">
                    <Link class="btn btn-gray mr-4" :href="route('admin.users.index')">
                        Cancel
                    </Link>
                    <button class="btn btn-blue" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                        Save
                    </button>
                </div>
            </form>

        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import {TextInput, PasswordInput} from '@/Components/UI/Forms';

export default {
    components: {
        AppLayout,
        TextInput,
        PasswordInput
    },
    props: {
        user: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.user.name,
                password: '',
                email: this.user.email,
            }),
            breadcrumbs:[
                {label: 'Users', url: route('admin.users.index')},
                {label: `Edit ${this.user.name}`}
            ]
        };
    },

    methods: {
        submit() {
            this.form.transform(data => ({
                ...data,
            })).patch(this.route('admin.users.update', this.user.id), {
                onFinish: () => this.form.reset('password'),
            });
        },
    },
};
</script>
