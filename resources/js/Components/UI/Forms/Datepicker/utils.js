import dayjs from 'dayjs';

export const dateInit = function(y, m, d) {
    return dayjs(new Date(y, m, d));
};
