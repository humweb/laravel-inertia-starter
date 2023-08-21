const _ = require('lodash-es');
const defaultConfig = require('tailwindcss/defaultConfig');

const colors = require('tailwindcss/colors');
delete colors['lightBlue'];
delete colors['warmGray'];
delete colors['trueGray'];
delete colors['coolGray'];
delete colors['blueGray'];

const defaultColors = _.omit(colors, [
    'transparent',
    'current'
]);

/* Sets default colors options from Tailwindcss' config */
function setDefaultColorOptions() {

    const colorOptions = {};

    const override = {
        black: {
            background: defaultColors['gray'][900],
            text: defaultColors['gray'][50],
        },
        white: {
            background: defaultColors['gray'][50],
            text: defaultColors['gray'][900],
        },
    };

    _.forIn(defaultColors, (value, key) => {

        colorOptions[key] = {
            background: _.get(override[key], 'background', value[500]),
            text: _.get(override[key], 'text', defaultColors['white']),
        };
    });

    return colorOptions;
}

/* Sets the default theme colors */
function setDefaultThemeColors() {

    return {
        primary: {
            background: defaultColors['blue'][500],
            text: defaultColors['white'],
        },
        secondary: {
            background: defaultColors['gray'][700],
            text: defaultColors['white'],
        },
        danger: {
            background: defaultColors['red'][500],
            text: defaultColors['white'],
        },
        success: {
            background: defaultColors['green'][500],
            text: defaultColors['white'],
        },
        info: {
            background: defaultColors['blue'][300],
            text: defaultColors['white'],
        },
        warning: {
            background: defaultColors['yellow'][500],
            text: defaultColors['white'],
        },
        default: {
            background: '#e0e1e2',
            text: defaultColors['gray'][700],
            hoverText: defaultColors['gray'][700],
            activeText: defaultColors['gray'][700],
        },
        disabled: {
            background: '#e0e1e2',
            cursor: 'default',
            hoverBackground: '#e0e1e2',
            hoverText: defaultColors['gray'][700],
            opacity: '.45',
            pointerEvents: 'none',
            text: defaultColors['gray'][700],
        },
    };
}

module.exports = function() {
    return {
        baseClass: '.btn',
        borderRadius: '.275rem',
        borderWidth: '1',
        colors: {
            default: setDefaultColorOptions,
            theme: setDefaultThemeColors,
        },
        cursor: 'pointer',
        fontSize: '1rem',
        fontWeight: '500',
        padding: '.625rem 1rem',
        sizes: {
            xs: {
                fontSize: '0.75rem',
                padding: '0.4rem 0.625rem',
            },
            sm: {
                fontSize: '0.85rem',
                padding: '0.5rem 1rem',
            },
            md: {
                fontSize: '1rem',
                padding: '.625rem 1rem',
            },
            lg: {
                fontSize: '1rem',
                padding: '0.725rem 1rem',
            },
            xl: {
                fontSize: '1rem',
                padding: '0.75rem 1.5rem',
            },
        },
        transition: 'all .2s ease-out',
    };
};
