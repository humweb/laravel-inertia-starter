<script setup>
import {useForm} from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';
import {TextInput, ToggleInput} from '@/Components/UI/Forms';
import LoadingButton from '@/Components/UI/LoadingButton.vue';
import {Tab, Tabs} from '@/Components/UI/Tabs';
import {computed} from 'vue';

const props = defineProps({
    role: {
        type: Object,
        default: () => ({}),
    },
    permissions: {
        type: Object,
        default: () => ({}),
    },
    roleHasPermissions: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    _method: 'put',
    name: props.role.name,
    permissions: props.roleHasPermissions,
});

const breadcrumbs = computed(function() {
    return [
        {label: 'Roles', url: route('admin.roles.index')},
        {label: 'Edit'},
    ];
});
</script>

<template>
    <AppLayout title="Edit role" :breadcrumbs="breadcrumbs">
        <template #header>

        </template>
        <form @submit.prevent="form.post(route('admin.roles.update', props.role.id))">
            <div class="card mb-12">
                <div class="card-header">Role</div>
                <div class="card-body">
                    <TextInput label="Name" v-model="form.name" :errors="form.errors.name" class="mb-4"/>
                </div>
            </div>

            <h3 class="font-semibold text-xl leading-tight mb-8">Permissions</h3>
            <Tabs>
                <Tab v-for="(permission, group) in permissions" :name="group">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 gap-y-8">
                        <ToggleInput v-for="perm in permission"
                                     :label="perm.name"
                                     v-model="form.permissions[perm.id]"
                                     class=""/>
                    </div>
                </Tab>
            </Tabs>
            <div class="flex w-full mt-8 justify-end">
                <button class="btn btn-gray mr-4" @click.prevent="$inertia.visit(route('admin.roles.index'))">
                    Cancel
                </button>
                <LoadingButton class="btn-blue" :loading="form.processing">
                    Save
                </LoadingButton>
            </div>

        </form>
    </AppLayout>
</template>
