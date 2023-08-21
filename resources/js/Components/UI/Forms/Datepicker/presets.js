import dayjs from 'dayjs';
import {dateInit} from '@/Components/UI/Forms/Datepicker/utils';
export default function() {
    return {

        thisMonth: function() {
            return {
                label: 'This Month',
                active: false,
                dateRange: {
                    start: dayjs().startOf('month'),
                    end: dayjs().endOf('month'),
                },
            };
        },
        lastMonth: function() {
            const n = new Date();
            const year = n.getMonth() == 0 ? n.getFullYear() - 1 : n.getFullYear();
            const month = (n.getMonth() - 1) <= 0 ? 12 : n.getMonth() - 1;
            const startMonth = dateInit(year, month, 1);
            const endMonth = dateInit(year, month, startMonth.daysInMonth());
            return {
                label: 'Last Month',
                active: false,
                dateRange: {
                    start: startMonth,
                    end: endMonth,
                },
            };
        },
        last7days: function() {
            const n = new Date();
            const start = dateInit(n.getFullYear(), n.getMonth(), n.getDate() - 5);
            const end = dateInit(n.getFullYear(), n.getMonth(), n.getDate() + 1);
            return {
                label: 'Last 7 Days',
                active: false,
                dateRange: {
                    start: start,
                    end: end,
                },
            };
        },
        thisWeek: function() {

            return {
                label: 'Current Week',
                active: false,
                dateRange: {
                    start: dayjs().startOf('week'),
                    end: dayjs().endOf('week'),
                },
            };
        },
        last30days: function() {
            const end = dayjs();
            const start = dayjs().subtract(30, 'day');
            return {
                label: 'Last 30 Days',
                active: false,
                dateRange: {
                    start: start,
                    end: end,
                },
            };
        },
        pastYear: function() {
            const end = dayjs();
            const start = dayjs().subtract(1, 'year');
            return {
                label: 'Year to Date',
                active: false,
                dateRange: {
                    start: start,
                    end: end,
                },
            };
        },
    };
};
