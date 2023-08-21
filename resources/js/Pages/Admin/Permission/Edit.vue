<script setup>
import {useForm} from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';
import {TextInput} from '@/Components/UI/Forms';
import LoadingButton from '@/Components/UI/LoadingButton.vue';
import {computed} from 'vue';

const props = defineProps({
    permission: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    _method: 'put',
    name: props.permission.name,
});

const breadcrumbs = computed(function() {
    return [
        {label: 'Permissions', url: route('admin.permissions.index')},
        {label: 'Edit'},
    ];
});
</script>

<template>
    <AppLayout title="Edit permissions" :breadcrumbs="breadcrumbs">
        <template #header>

        </template>

        <form @submit.prevent="form.post(route('admin.permissions.update', props.permission.id))">
            <div class="card">
                <div class="card-body rounded-t-lg">
                    <TextInput
                        label="Name"
                        v-model="form.name"
                        placeholder="Enter Name"
                        :error="form.errors.name"
                    />
                </div>
                <div class="card-footer justify-end">
                    <button class="btn btn-gray mr-4" @click.prevent="$inertia.visit(route('admin.permissions.index'))">
                        Cancel
                    </button>
                    <LoadingButton class="btn-primary" :loading="form.processing">
                        Save
                    </LoadingButton>
                </div>
            </div>
        </form>
    </AppLayout>
</template>
