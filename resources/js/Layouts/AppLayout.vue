<script>

import {Disclosure, DisclosureButton, DisclosurePanel, MenuButton} from '@headlessui/vue';
import {Bars3Icon, CubeIcon, HomeIcon, LockClosedIcon, UserIcon, UsersIcon, XMarkIcon} from '@heroicons/vue/24/outline';
import FlashMessages from '@/Components/UI/FlashMessages.vue';
import ThemeSelector from '@/Components/ThemeSelector.vue';
import {Dropdown, DropdownItem} from '@/Components/UI/Dropdown';
import {Head} from '@inertiajs/vue3';
import Breadcrumbs from '@/Components/UI/Breadcrumbs.vue';

export default {
    computed: {},
    components: {
        Breadcrumbs,
        Head,
        Disclosure, DisclosureButton, DisclosurePanel, MenuButton,
        Bars3Icon, HomeIcon, UsersIcon, CubeIcon, LockClosedIcon, UserIcon, XMarkIcon,
        FlashMessages,
        ThemeSelector,
        Dropdown,
        DropdownItem,
    },
    props: {
        auth: Object,
        title: String,
        breadcrumbs: Array,
    },
    data() {
        return {
            navigation: [
                {name: 'Dashboard', href: route('dashboard'), icon: HomeIcon, current: this.isUrl('admin/dashboard')},
                {name: 'Users', href: route('admin.users.index'), icon: UsersIcon, current: this.isUrl('admin/users')},
                // {
                //     name: 'Products',
                //     href: route('admin.products.index'),
                //     icon: UsersIcon,
                //     current: this.isUrl('admin/products'),
                // },
                // {
                //     name: 'Categories',
                //     href: route('admin.categories.index'),
                //     icon: UsersIcon,
                //     current: this.isUrl('admin/categories'),
                // },
                {name: 'Roles', href: route('admin.roles.index'), icon: CubeIcon, current: this.isUrl('admin/roles')},
                {
                    name: 'Permission',
                    href: route('admin.permissions.index'),
                    icon: LockClosedIcon,
                    current: this.isUrl('admin/permissions'),
                },
            ],
            userNavigation: [
                {name: 'Profile', href: route('profile.show')},
                {name: 'Sign out', href: route('logout'), method: 'post'},
            ],
        };
    },
    methods: {
        isUrl(...urls) {
            let currentUrl = this.$page.url.substr(1);
            if (urls[0] === '') {
                return currentUrl === '';
            }
            return urls.filter((url) => currentUrl.startsWith(url)).length;
        },
    },
};
</script>

<template>
    <Head :title="title"/>
    <div class="min-h-full">
        <Disclosure as="nav"
                    class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-transparent"
                    v-slot="{ open }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 font-bold rounded-full dark:text-blue-400 bg-gray-200 dark:bg-gray-900 p-2">
                            BA
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <Link v-for="item in navigation" :key="item.name" :href="item.href"
                                      :class="[item.current ? 'bg-blue-600 text-white' : 'dark:text-gray-300 text-gray-700 hover:bg-gray-700 hover:text-white', 'px-3 py-2 rounded-md text-sm font-medium transition-all duration-100']"
                                      :aria-current="item.current ? 'page' : undefined">
                                    {{ item.name }}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">


                        <div class="ml-4 flex items-center md:ml-6">
                            <ThemeSelector/>
                            <!-- Profile dropdown -->
                            <Dropdown class="ml-3">
                                <div>
                                    <MenuButton
                                        class="flex max-w-xs items-center p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <span class="sr-only">Open user menu</span>
                                        <UserIcon class="h-6 w-6 text-gray-400 hover:text-gray-500" alt=""/>
                                    </MenuButton>
                                </div>
                                <template #menu-items>
                                    <DropdownItem v-for="item in userNavigation" :key="item.name" :href="item.href"
                                                  :method="'method' in item ? item.method : 'get'"
                                                  :as="'method' in item && item.method == 'post' ? 'button' : 'a'"
                                    >
                                        {{ item.name }}
                                    </DropdownItem>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton
                            class="inline-flex items-center justify-center rounded-md dark:bg-gray-800 p-2 text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true"/>
                            <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true"/>
                        </DisclosureButton>
                    </div>
                </div>
            </div>

            <DisclosurePanel class="md:hidden">
                <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                    <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="item.href"
                                      :class="[item.current ? 'bg-blue-600 text-white' : 'dark:text-gray-300 text-gray-700 hover:bg-gray-700 hover:text-white', 'flex items-center px-3 py-2 rounded-md text-base font-medium']"
                                      :aria-current="item.current ? 'page' : undefined">
                        <component :is="item.icon" class="w-4 h-4 inline-flex mr-2"/>
                        {{ item.name }}
                    </DisclosureButton>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 pb-3">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <div class="p-2 rounded-full bg-gray-100 dark:bg-gray-900">
                                <UserIcon class="h-6 w-6 text-gray-400 hover:text-gray-500" alt=""/>
                            </div>
                            <!--                            <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt=""/>-->
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium dark:text-white">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium dark:text-gray-400">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <!--                        <button type="button"-->
                        <!--                                class="ml-auto flex-shrink-0 rounded-full bg-gray-100 dark:bg-gray-900 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">-->
                        <!--                            <span class="sr-only">View notifications</span>-->
                        <!--                            <BellIcon class="h-6 w-6" aria-hidden="true"/>-->
                        <!--                        </button>-->
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <DisclosureButton v-for="item in userNavigation" :key="item.name" as="a" :href="item.href"
                                          class="block rounded-md px-3 py-2 text-base font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-700 hover:text-white">
                            {{ item.name }}
                        </DisclosureButton>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <header class="bg-white dark:bg-gray-900 shadow-sm dark:shadow-none dark:border-b dark:border-gray-800">
            <div class="mx-auto max-w-7xl py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <Breadcrumbs v-if="breadcrumbs" :crumbs="breadcrumbs" class="font-semibold"/>
                <slot name="header"/>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl py-8 sm:px-6 lg:px-8">
                <!-- Replace with your content -->
                <FlashMessages/>
                <!-- Replace with your content -->
                <slot/>
                <!-- /End replace -->
            </div>
        </main>
    </div>

</template>
