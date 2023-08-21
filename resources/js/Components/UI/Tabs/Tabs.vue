<template>
    <div :class="wrapperClass">
        <nav class="flex" aria-label="Tabs" role="tablist">

            <div class="sm:hidden px-3 pt-4 w-full">
                <label for="tabs" class="sr-only">Select a tab</label>
                <select id="tabs" name="tabs" :value="state.activeTabHash"
                        @change="selectTab($event.target.value, $event)"
                        class="form-select">
                    <option v-for="(tab, i) in tabs" :key="tab.name" :value="tab.hash" :selected="tab.isActive">
                        {{ tab.header }}
                    </option>
                </select>
            </div>
            <div class="hidden sm:flex w-full space-x-8 border-b dark:border-gray-600"  :class="navClass">
                <div
                    v-for="(tab, i) in tabs"
                    :key="i"
                    class="flex -mb-px"
                    role="presentation"
                >

                    <a
                        v-if="type == 'link'"
                        role="tab"
                        :class="[tab.isActive ? 'border-blue-500 text-blue-500' : 'border-transparent text-gray-500 dark:text-gray-400 dark:hover:text-gray-300 hover:text-gray-700 hover:border-gray-300 dark:hover:border-gray-600', tab.isDisabled ? navItemDisabledClass : '', 'whitespace-nowrap py-4 px-12 border-b-2 font-medium -mt-4 -mb-4 ']"
                        :aria-current="tab.isActive ? 'page' : undefined"
                        :aria-controls="tab.hash"
                        :aria-selected="tab.isActive"
                        :href="tab.hash"
                        @click="selectTab(tab.hash, $event)"
                        v-html="tab.header"
                    />

                    <a
                        v-if="type=='pills'"
                        role="tab"
                        :class="[tab.isActive ? 'bg-blue-500 text-blue-50' : 'hover:text-blue-50 hover:bg-blue-500', 'px-4 py-2 font-medium rounded-full']"
                        :aria-current="tab.isActive ? 'page' : undefined"
                        :aria-controls="tab.hash"
                        :aria-selected="tab.isActive"
                        :href="tab.hash"
                        @click="selectTab(tab.hash, $event)"
                        v-html="tab.header"
                    />
                </div>
            </div>
        </nav>
        <div class="card-body" :class="panelsWrapperClass">
            <slot/>
        </div>

        <div v-if="$slots.footer" class="card-footer justify-between items-center">
            <slot name="footer" />
        </div>
    </div>
</template>

<script>
// import expiringStorage from '../expiringStorage';
import {onMounted, provide, reactive, toRefs} from 'vue';

export default {
    props: {
        cacheLifetime: {
            type: Number,
            default: 5,
        },
        type: {
            type: String,
            default: 'link',
        },
        options: {
            type: Object,
            required: false,
            default: () => ({
                useUrlFragment: true,
                defaultTabHash: null,
            }),
        },

        wrapperClass: {
            type: String,
            default: 'card',
        },
        panelsWrapperClass: {
            type: String,
            default: 'card-body',
        },
        navClass: {
            type: String,
            default: 'card-header w-full px-0',
        },
        navItemClass: {
            type: String,
            default: '',
        },
        navItemDisabledClass: {
            type: String,
            default: 'is-disabled',
        },
        navItemActiveClass: {
            type: String,
            default: 'is-active',
        },

        navItemLinkActiveClass: {
            type: String,
            default: 'is-active',
        },
        navItemLinkDisabledClass: {
            type: String,
            default: 'is-disabled',
        },
    },

    emits: ['changed', 'clicked', 'updated', 'removed', 'added'],

    setup(props, context) {
        const state = reactive({
            activeTabHash: '',
            lastActiveTabHash: '',
            tabs: [],
        });

        provide('tabsProvider', state);

        provide('addTab', (tab) => {
            state.tabs.push(tab);
            context.emit('added', state.tabs);
        });

        provide('updateTab', (computedId, data) => {
            let tabIndex = state.tabs.findIndex((tab) => tab.computedId === computedId);

            data.isActive = state.tabs[tabIndex].isActive;

            state.tabs[tabIndex] = data;
        });

        provide('deleteTab', (computedId) => {
            let tabIndex = state.tabs.findIndex((tab) => tab.computedId === computedId);

            state.tabs.splice(tabIndex, 1);
            context.emit('removed', state.tabs);
            context.emit('updated', state.tabs);
        });

        const selectTab = (selectedTabHash, event) => {
            if (event && !props.options.useUrlFragment) {
                event.preventDefault();
            }

            const selectedTab = findTab(selectedTabHash);

            if (!selectedTab) {
                return;
            }

            if (event && selectedTab.isDisabled) {
                event.preventDefault();
                return;
            }

            if (state.lastActiveTabHash === selectedTab.hash) {
                context.emit('clicked', {tab: selectedTab});
                return;
            }

            state.tabs.forEach(tab => {
                tab.isActive = (tab.hash === selectedTab.hash);
            });

            context.emit('changed', {tab: selectedTab});

            state.lastActiveTabHash = state.activeTabHash = selectedTab.hash;

            // const storageKey = `vue-tabs-component.cache.${window.location.host}${window.location.pathname}`;
            // expiringStorage.set(storageKey, selectedTab.hash, props.cacheLifetime);
        };

        const nextTab = () => {
            this.currentTab++;
            if (this.isTabDisabled(this.currentTab)) {
                this.enabledTabs.push(this.currentTab);
            }
        };

        const prevTab = () => {
            if (this.currentTab != 0) {
                this.currentTab--;
            }
        };

        const selectIndex = (index) => {
            if (event && !props.options.useUrlFragment) {
                event.preventDefault();
            }

            const selectedTab = state.tabs[index];

            if (!selectedTab) {
                return;
            }

            if (event && selectedTab.isDisabled) {
                event.preventDefault();
                return;
            }

            if (state.lastActiveTabHash === selectedTab.hash) {
                context.emit('clicked', {tab: selectedTab});
                return;
            }

            state.tabs.forEach(tab => {
                tab.isActive = (tab.hash === selectedTab.hash);
            });

            context.emit('changed', {tab: selectedTab});

            state.lastActiveTabHash = state.activeTabHash = selectedTab.hash;
        };

        const findTab = (hash) => {
            return state.tabs.find(tab => tab.hash === hash);
        };

        onMounted(() => {
            if (!state.tabs.length) {
                return;
            }

            window.addEventListener('hashchange', () => selectTab(window.location.hash));

            if (findTab(window.location.hash)) {
                selectTab(window.location.hash);
                return;
            }

            if (props.options.defaultTabHash && findTab('#' + props.options.defaultTabHash)) {
                selectTab('#' + props.options.defaultTabHash);
                return;
            }

            selectTab(state.tabs[0].hash);
        });

        return {
            ...toRefs(state),
            selectTab,
            findTab,
            selectIndex,
            state,
        };
    },
};
</script>
