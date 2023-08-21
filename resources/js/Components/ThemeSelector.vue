<template>
    <div>
        <Dropdown>
            <div class="text-gray-400 hover:text-gray-500 ">
                <MoonIcon v-if="currentTheme == 'dark'" class="w-6 h-6"/>
                <SunIcon v-else-if="currentTheme == 'light'" class="w-6 h-6"/>
                <ComputerDesktopIcon v-else class="w-6 h-6"/>
            </div>
            <template #menu-items>
                <DropdownItem class="font-bold" @click="selectTheme('dark')">
                    <MoonIcon class="w-5 h-5 mr-3"/>
                    Dark
                </DropdownItem>
                <DropdownItem class="font-bold" @click="selectTheme('light')">
                    <SunIcon class="w-5 h-5 mr-3"/>
                    Light
                </DropdownItem>
                <DropdownItem class="font-bold border-t dark:border-gray-800" @click="selectTheme('system')">
                    <ComputerDesktopIcon class="w-5 h-5 mr-3"/>
                    System
                </DropdownItem>
            </template>
        </Dropdown>

    </div>
</template>

<script>
import {Dropdown, DropdownItem} from '@/Components/UI/Dropdown';
import {ComputerDesktopIcon, MoonIcon, SunIcon} from '@heroicons/vue/24/outline';
import {mixin as themeMixin} from '@/Plugins/theme';

export default {
    name: 'ThemeSelector',
    mixins:[themeMixin],
    components: {
        Dropdown, DropdownItem,
        MoonIcon, SunIcon, ComputerDesktopIcon,
    },
};
</script>

<style scoped>

</style>
