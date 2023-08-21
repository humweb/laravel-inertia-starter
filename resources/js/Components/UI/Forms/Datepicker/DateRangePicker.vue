<template>
    <div class="relative">
        <input
            ref="trigger"
            class="form-input"
            :value="displayDate"
            @click="toggleCalendar()"
            readonly>
        <Teleport to="body">

            <div
                ref="container"
                class="absolute overflow-hidden z-50 rounded-lg bg-white dark:bg-gray-900 dark:border-gray-700 border p-4 inline-flex flex-wrap sm:flex-col"
                v-if="isOpen" v-click-outside="toggleCalendar">
                <div class="justify-end text-right">
                    <XMarkIcon class="w-4 h-4 ml-auto" @click="toggleCalendar"></XMarkIcon>
                </div>

                <div
                    class="text-sm flex-wrap md:grid grid-cols-2 gap-8 justify-center"
                    :class="[presetsEnabled ? 'grid-cols-3' : 'grid-cols-2']">
                    <month-picker :month="month"
                                  :year="year"
                                  :date-range="tmpDateRange"
                                  @hover="hoverDate"
                                  @selected="selectFirstItem">
                        <template #headerLeft>
                            <ArrowLeftIcon class="w-4 h-4 cursor-pointer" @click="backMonth"></ArrowLeftIcon>
                        </template>
                    </month-picker>
                    <month-picker :month="nextCalendar.month"
                                  :year="nextCalendar.year"
                                  :date-range="tmpDateRange"
                                  @hover="hoverDate"
                                  @selected="selectFirstItem">
                        <template class="months-text" #headerRight>
                            <ArrowRightIcon class="w-4 h-4 cursor-pointer" @click="forwardMonth"></ArrowRightIcon>
                        </template>
                    </month-picker>
                    <div v-if="presetsEnabled" class="border-l dark:border-gray-700 pl-4">
                        <ul class="py-2 space-y-2">
                            <li v-for="(item, idx) in finalPresetRanges"
                                class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg cursor-pointer"
                                :key="idx"
                                @click="updatePreset(item)"
                                :class="{'hover:bg-blue-400 bg-blue-400 text-white': presetActive === item.label}">
                                {{ item.label }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-row w-full px-8 pt-4 border-t dark:border-gray-700">
                    <button class="btn btn-sm btn-primary ml-auto" @click="toggleCalendar()">Apply</button>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script>
import dayjs from 'dayjs';
import MonthPicker from './Month.vue';
import {ArrowLeftIcon, ArrowRightIcon, XMarkIcon} from '@heroicons/vue/24/outline';
import {createPopper} from '@popperjs/core';
import * as isSameOrBefore from 'dayjs/plugin/isSameOrBefore';
import defaultPresets from '@/Components/UI/Forms/Datepicker/presets';
import {dateInit} from '@/Components/UI/Forms/Datepicker/utils';

dayjs.extend(isSameOrBefore);


const defaultCaptions = {
    'title': 'Choose Dates',
    'ok_button': 'Apply',
};


export default {
    components: {
        MonthPicker,
        XMarkIcon,
        ArrowRightIcon, ArrowLeftIcon,
    },
    props: {
        value: {
            type: Object,
            default() {
                return {
                    start: '',
                    end: '',
                };
            },
        },
        configs: {
            type: Object,
            // default: () => defaultConfig,
        },
        captions: {
            type: Object,
            default: () => defaultCaptions,
        },
        format: {
            type: String,
            default: 'MM/DD/YYYY',
        },
        presetsEnabled: {
            type: Boolean,
            default: true,
        },
        presetRanges: {
            type: Object,
            default: () => null,
        },
        placement: {
            type: String,
            default: 'bottom-end',
        },
        boundary: {
            type: String,
            default: 'scrollParent',
        },
        autoClose: {
            type: Boolean,
            default: true,
        },
    },
    data() {
        return {
            dateRange: this.value,
            popper: null,
            isOpen: false,
            presetActive: '',
            month: new Date().getMonth(),
            year: new Date().getFullYear(),
            isSelecting: false,
            tmpEndDate: null,
            startDate: '',
            endDate: '',
        };
    },
    methods: {
        toggleCalendar: function() {
            this.isOpen = !this.isOpen;
        },
        backMonth() {
            if (this.month === 0) {
                this.year = this.year - 1;
                this.month = 11;
            } else {
                this.month = this.month - 1;
            }
        },
        forwardMonth() {
            if (this.month === 11) {
                this.year = this.year + 1;
                this.month = 0;
            } else {
                this.month = this.month + 1;
            }
        },

        selectFirstItem(day, month, year) {

            if (!this.isSelecting) {
                this.presetActive = '';
                this.isSelecting = true;
                this.dateRange.end = '';
                this.tmpEndDate = null;
                this.dateRange.start = dateInit(year, month, day);

            } else if (this.dateRange.start.isSameOrBefore(dateInit(year, month, day))) {
                this.isSelecting = false;
                this.dateRange.end = dateInit(year, month, day);
                this.$emit('input', this.dateRange);
                this.endDate = '';
            } else {
                this.isSelecting = false;
                this.dateRange.start = '';
                this.dateRange.end = '';
                this.endDate = '';
                this.tmpEndDate = null;
                this.$nextTick(() => this.selectFirstItem(day, month, year));
            }

        },

        hoverDate(day, month, year) {
            let dt = dateInit(year, month, day);
            if (this.isSelecting && dt.isAfter(this.dateRange.start)) {
                this.tmpEndDate = dt;
            }
        },
        updatePreset(item) {
            this.presetActive = item.label;
            this.dateRange.end = item.dateRange.end;
            this.dateRange.start = item.dateRange.start;

            this.setDateRange(this.dateRange);
        },

        setDateRange(v) {
            if (v instanceof String) {
                v = this.fromString(v);
            }
            this.month = v.start.month();
            this.year = v.start.year();
            this.$emit('input', v);
        },

        fromString(str) {
            let [start, end = ''] = str.split('-').map(v => v.trim());
            return {
                start: (start !== '') ? dayjs(start, this.format) : '',
                end: (end !== '') ? dayjs(end, this.format) : '',
            };
        },
    },

    watch: {
        value: function(v) {
            if (v instanceof String) {
                v = this.fromString(v);
                this.$emit('input', v);
            } else {
                let dt = dateInit(v.start.year, v.start.month);
            }
            this.month = v.start.month;
            this.year = v.start.year;
        },
        isOpen(show) {
            if (show) {
                this.$nextTick(() => {
                    this.popper = createPopper(this.$refs.trigger, this.$refs.container, {
                        placement: this.placement,
                        modifiers: [{
                            name: 'preventOverflow',
                            options: {
                                boundary: this.boundary,
                            },
                        }],
                    });
                });
            } else if (this.popper) {
                setTimeout(() => this.popper.destroy(), 100);
            }
        },

    },
    computed: {
        tmpDateRange() {
            return {
                start: this.dateRange.start,
                end: (this.isSelecting) ? this.tmpEndDate : this.dateRange.end,
            };
        },

        displayDate() {
            let str = '';
            if (this.dateRange.start !== '') {
                str = this.dateRange.start.format(this.format);
            }
            if (this.dateRange.end !== '') {
                str += ' - ' + this.dateRange.end.format(this.format);
            }
            return str;
        },

        nextCalendar() {
            if (this.month === 11) {
                // next year
                return {
                    month: 1,
                    year: this.year + 1,
                };
            }
            return {
                month: this.month + 1,
                year: this.year,
            };
        },
        finalPresetRanges: function() {
            const tmp = {};
            const presets = this.presetRanges || defaultPresets();
            for (const i in presets) {
                const item = presets[i];
                let plainItem = item;
                if (typeof item === 'function') {
                    plainItem = item();
                }
                tmp[i] = plainItem;
            }
            return tmp;
        },
    },
};
</script>

