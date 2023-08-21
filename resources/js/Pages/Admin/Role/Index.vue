<template>
    <AppLayout title="Users" :breadcrumbs="breadcrumbs">
        <template #header>
        </template>


        <Table
            :records="records"
            :pagination="pagination"
            v-model="tableData"
        />

    </AppLayout>
</template>

<script>
import {defineComponent} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {Table, HasInertiaTable} from 'inertia-vue-table';

export default defineComponent({
    name: 'Roles',
    mixins: [HasInertiaTable],
    components: {
        AppLayout,
        Table
    },
    computed: {
        breadcrumbs() {
            return [
                {label: 'Roles'}
            ];
        },
    },
});
</script>

