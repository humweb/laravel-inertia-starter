const _ = require('lodash-es');
const Color = require('color');
const buttons = require('./buttons');
const defaultOptions = require('./defaultOptions');
const plugin = require('tailwindcss/plugin')

/* Sets a color config object from the options */
function setColorConfig(options) {

    const colorConfig = _.omit({
        ..._.isFunction(options.colors.default) ? options.colors.default() : options.colors.default,
        ..._.isFunction(options.colors.theme) ? options.colors.theme() : options.colors.theme
    });

    return colorConfig;
}

/* Combines all button styles */
function combineStyles(colorConfig, options, custom) {

    let customStyles = {};

    if (typeof custom === 'function') {

        customStyles = custom(colorConfig, options);
    } else if (typeof custom === 'object') {

        customStyles = custom;
    }

    return {
        ...buttons.createDefault(options),
        ...buttons.createRounded(options),
        ...buttons.createSolid(colorConfig, options),
        ...buttons.createOutlined(colorConfig, options),
        ...buttons.createGradient(colorConfig, options),
        ...customStyles
    }
}


// function(options, custom) {
module.exports = plugin(function({ addComponents }) {
    // Add your custom styles here


    const options = defaultOptions();

    const colorConfig = setColorConfig(options);

    const buttonStyles = combineStyles(colorConfig, options);

    const buttonSizes = buttons.createSizes(options);


        addComponents([
            buttonStyles,
            buttonSizes
        ]);
})
