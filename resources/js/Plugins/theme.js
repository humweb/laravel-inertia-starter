export const toDarkMode = () => {
    localStorage.theme = 'dark';
    updateTheme();
};

export const toLightMode = () => {
    localStorage.theme = 'light';
    updateTheme();
};

export const toSystemMode = () => {
    localStorage.theme = 'system';
    updateTheme();
};

export const currentTheme = () => {
    if ('theme' in localStorage) {
        return localStorage.theme;
    }
    return 'system';
};
export const updateTheme = () => {
    if (!('theme' in localStorage)) {
        localStorage.theme = 'system';
    }
    setPageTheme();
};

export const setPageTheme = () => {
    let el = document.documentElement;
    let classList = el.classList;
    switch (localStorage.theme) {
        case 'system':
            if (window.matchMedia('(prefers-color-scheme: dark').matches) {
                classList.add('dark');
            } else {
                classList.remove('dark');
            }
            el.setAttribute('color-theme', 'system');
            break;
        case 'dark':
            classList.add('dark');
            el.setAttribute('color-theme', 'dark');
            break;
        case 'light':
            classList.remove('dark');
            el.setAttribute('color-theme', 'light');
            break;
    }
};

export const mixin = {
    data() {
        return {
            currentTheme: currentTheme(),
        };
    },
    methods: {
        selectTheme(theme) {
            switch (theme) {
                case 'light':
                    toLightMode();
                    break;
                case 'dark':
                    toDarkMode();
                    break;
                default:
                case 'system':
                    toSystemMode();
            }
            this.currentTheme = theme;
        },
    },

    created() {
        this.selectTheme(this.currentTheme)

    }
};
