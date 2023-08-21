<template>
    <div class="w-64">
        <div class="flex flex-row justify-between items-center w-full">
            <slot name="headerLeft"/>
            <b>{{ currentMonthName }} {{ year }}</b>
            <slot name="headerRight" />
        </div>
        <ul class="grid grid-cols-7">
            <li v-for="item in weekdays" :key="item" class="h-12 lg:w-10 lg:h-10 flex justify-center items-center">
                {{ item }}
            </li>
        </ul>
        <ul class="grid grid-cols-7">
            <li v-for="i in blankDays" class="w-full h-8 flex flex-shrink-0 items-center">&nbsp;</li>
            <li v-for="i in daysInMonth"
                :key="i"
                @click="handleClick(i)"
                @mouseover="$emit('hover', i, month, year)"
                v-html="i"
                class="w-full h-8 flex text-center justify-center flex-shrink-0 items-center hover:cursor-pointer"
                :class="getHighlightClass(i)"></li>
        </ul>
    </div>
</template>

<script>
import dayjs from 'dayjs';
import * as isBetween from 'dayjs/plugin/isBetween';
import * as weekday from 'dayjs/plugin/weekday';
import {dateInit} from '@/Components/UI/Forms/Datepicker/utils';
dayjs.extend(isBetween);
dayjs.extend(weekday);
export default {
    props: {
        format: {
            type: String,
            default: 'MM/DD/YYYY',
        },
        dateRange: {
            type: Object,
            default() {
                return {
                    start: '',
                    end: '',
                };
            },
        },
        month: {
            type: Number,
            default: new Date().getMonth(),
        },
        year: {
            type: Number,
            default: new Date().getFullYear(),
        },
        months: {
            type: Array,
            default: () => [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],
        },
        weekdays: {
            type: Array,
            default: () => ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        },
    },
    data() {
        return {
            currentDate: {},
            blankDays: 0,
            daysInMonth: 0,
        };
    },
    methods: {
        handleClick(day) {
            this.$nextTick(() => this.$emit('selected', day, this.month, this.year));
        },
        isDateInRange(day) {
            let dt = dateInit(this.year, this.month, day);
            if (!this.dateRange.end && dt.isSame(this.dateRange.start)) {
                return true;
            }
            if (this.dateRange.start && this.dateRange.end) {
                return dt.isBetween(this.dateRange.start, this.dateRange.end, null, '[]');
            }
            return false;
        },
        isFirstDateInRange(day) {
            let dt = dateInit(this.year, this.month, day);
            if (this.dateRange.start && dt.isSame(this.dateRange.start)) {
                return true;
            }
            return false;
        },
        isLastDateInRange(day) {
            let dt = dateInit(this.year, this.month, day);
            if (this.dateRange.end && dt.isSame(this.dateRange.end)) {
                return true;
            }
            return false;
        },
        getHighlightClass(day) {
            if (this.isFirstDateInRange(day) || this.isLastDateInRange(day)){
                return 'bg-green-500 dark:bg-green-700 text-white'
            }
            else if (this.isDateInRange(day)) {
                return 'bg-green-400 dark:bg-green-600 text-white'
            }
            return ''
        }
    },
    computed: {

        /**
         * Return array of week days
         *
         * @returns {*}
         */
        currentMonthName() {
            return this.months[this.month];
        },
    },
    created() {
        let dt = dateInit(this.year, this.month, 1);
        this.currentDate = dt;
        this.blankDays = dt.weekday();
        this.daysInMonth = dt.daysInMonth();
    },
    watch: {
        currentDate(v) {
            this.blankDays = v.weekday();
            this.daysInMonth = v.daysInMonth();
        },
        month(v) {
            this.currentDate = dateInit(this.year, v, 1);
        },
        year(v) {
            this.currentDate = dateInit(v, this.month, 1);
        },
    },
};

</script>

<style>

</style>
