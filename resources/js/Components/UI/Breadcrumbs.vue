<template>
    <nav aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex text-gray-500 dark:text-gray-400">
            <template v-for="(crumb, i) in crumbs">
                <li v-if="crumb.url" class="flex items-center text-blue-400 hover:opacity-90">
                    <Link :href="crumb.url">{{ crumb.label }}</Link>
                    <span><ChevronRightIcon class="text-gray-300 w-5 h-5"></ChevronRightIcon></span>
                </li>
                <li v-else aria-current="page" class="flex items-center">
                    {{ crumb.label }}
                    <span v-if="i < crumbs.length-1"><ChevronRightIcon class="text-gray-300 w-5 h-5"></ChevronRightIcon></span>
                </li>
            </template>
        </ol>
    </nav>
</template>

<script>
import {ChevronRightIcon} from '@heroicons/vue/20/solid';

export default {
    name: "Breadcrumbs",
    props: {
        crumbs: Array,
    },
    components: {
        ChevronRightIcon
    }
}
</script>

<style scoped>

</style>
