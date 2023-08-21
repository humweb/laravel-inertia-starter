<template>
    <AppLayout title="Users" :breadcrumbs="breadcrumbs">
        <template #header>
            <Link :href="route('admin.users.create')" class="btn btn-xs btn-outline-primary">
                <PlusIcon class="h-4 w-4 mr-1"/> Create
            </Link>
        </template>


        <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <Table
            :records="records"
            :pagination="pagination"
            v-model="tableData"
        />
        </div>
    </AppLayout>
</template>

<script>
import {defineComponent} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {HasInertiaTable, Table} from 'inertia-vue-table';
import {PlusIcon} from '@heroicons/vue/24/outline';

export default defineComponent({
    name: 'Users',
    mixins: [HasInertiaTable],
    components: {
        AppLayout,
        Table,
        PlusIcon
    },
    computed:{
        breadcrumbs() {
            return [
                {label: 'Users'},
            ]
        }
    }
});
</script>

