<template>
    <div
        :tabindex="searchable ? -1 : 0"
        :class="{ 'multiselect--active': isOpen, 'multiselect--disabled': disabled, 'multiselect--above': !hasEnoughSpace }"
        @focus="activate()"
        @blur="searchable ? false : deactivate()"
        @keydown.self.down.prevent="pointerForward()"
        @keydown.self.up.prevent="pointerBackward()"
        @keydown.enter.tab.stop.self="addPointerElement($event)"
        @keyup.esc="deactivate()"
        class="multiselect">
        <slot name="carret">
            <div @mousedown.prevent="toggle()" class="multiselect__select"></div>
        </slot>
        <div ref="tags" class="form-input">
            <span
                v-if="!this.$slots.tags"
                v-for="option in visibleValue"
                @mousedown.prevent
                class="multiselect__tag bg-blue-500">
                <span v-text="getOptionLabel(option)"></span>
                <i
                    aria-hidden="true"
                    tabindex="1"
                    @keydown.enter.prevent="removeElement(option)"
                    @mousedown.prevent="removeElement(option)"
                    class="multiselect__tag-icon">
                </i>
            </span>
            <template v-if="internalValue && internalValue.length > limit">
                <strong v-text="limitText(internalValue.length - limit)"></strong>
            </template>
            <transition name="multiselect__loading">
                <slot name="loading">
                    <div v-show="loading" class="multiselect__spinner"></div>
                </slot>
            </transition>
            <input
                ref="search"
                type="text"
                autocomplete="off"
                :placeholder="placeholder"
                v-if="searchable"
                :value="isOpen ? search : currentOptionLabel"
                :disabled="disabled"
                @input="updateSearch($event.target.value)"
                @focus.prevent="activate()"
                @blur.prevent="deactivate()"
                @keyup.esc="deactivate()"
                @keydown.down.prevent="pointerForward()"
                @keydown.up.prevent="pointerBackward()"
                @keydown.enter.prevent
                @keydown.enter.tab.stop.self="addPointerElement($event)"
                @keydown.delete="removeLastElement()"
                class="multiselect__input ring-opacity-0 dark:bg-gray-900 focus:ring-0"/>
            <span
                v-if="!searchable"
                class="multiselect__single"
                v-text="currentOptionLabel">
            </span>
        </div>
        <transition name="multiselect">
            <ul
                :style="{ maxHeight: maxHeight + 'px' }"
                ref="list"
                v-show="isOpen"
                @mousedown.prevent
                class="multiselect__content border border-1 border-gray-300 dark:border-gray-800 bg-gray-50 dark:text-gray-300 dark:bg-gray-700">
                <slot name="beforeList"></slot>
                <li v-if="multiple && max === internalValue.length">
                    <span class="multiselect__option">
                        <slot name="maxElements">
                            Maximum of {{ max }} options selected. First remove a selected option to select another.
                        </slot>
                    </span>
                </li>
                <template v-if="!max || internalValue.length < max">
                    <li class="multiselect__element" v-for="(option, index) in filteredOptions" :key="index">
                        <span
                            tabindex="0"
                            v-if="!(option && option.$isLabel) && !isSelected(option)"
                            :class="optionHighlight(index, option)"
                            @mousedown.prevent="select(option)"
                            @mouseenter="pointerSet(index)"
                            :data-select="option && option.isTag ? tagPlaceholder : selectLabelText"
                            :data-selected="selectedLabelText"
                            :data-deselect="deselectLabelText"
                            class="multiselect__option hover:text-gray-50 hover:bg-blue-500">
                            <slot name="option" :option="option" :search="search">
                                <span>{{ getOptionLabel(option) }}</span>
                            </slot>
                        </span>
                        <span
                            v-if="option && option.$isLabel"
                            :class="optionHighlight(index, option)"
                            class="multiselect__option multiselect__option--disabled">
                            {{ option.$groupLabel }}
                        </span>
                    </li>
                </template>
                <li v-show="filteredOptions.length === 0 && search && !loading">
                    <span class="multiselect__option">
                        <slot name="noResult">No elements found. Consider changing the search query.</slot>
                    </span>
                </li>
                <slot name="afterList"></slot>
            </ul>
        </transition>
        <slot name="tags" :values="visibleValue" :remove="removeElement" />
    </div>
</template>

<script>
import multiselectMixin from './multiselectMixin';
import pointerMixin from './pointerMixin';

export default {
    name: 'vue-multiselect',
    mixins: [multiselectMixin, pointerMixin],
    props: {
        /**
         * String to show when pointing to an option
         * @default 'Press enter to select'
         * @type {String}
         */
        selectLabel: {
            type: String,
            default: 'Select',
        },
        /**
         * String to show next to selected option
         * @default 'Selected'
         * @type {String}
         */
        selectedLabel: {
            type: String,
            default: 'Selected',
        },
        /**
         * String to show when pointing to an alredy selected option
         * @default 'Press enter to remove'
         * @type {String}
         */
        deselectLabel: {
            type: String,
            default: 'Remove',
        },
        /**
         * Decide whether to show pointer labels
         * @default true
         * @type {Boolean}
         */
        showLabels: {
            type: Boolean,
            default: true,
        },
        /**
         * Limit the display of selected options. The rest will be hidden within the limitText string.
         * @default 'label'
         * @type {String}
         */
        limit: {
            type: Number,
            default: 99999,
        },
        /**
         * Sets maxHeight style value of the dropdown
         * @default 300
         * @type {Integer}
         */
        maxHeight: {
            type: Number,
            default: 300,
        },
        /**
         * Function that process the message shown when selected
         * elements pass the defined limit.
         * @default 'and * more'
         * @param {Int} count Number of elements more than limit
         * @type {Function}
         */
        limitText: {
            type: Function,
            default: count => `and ${count} more`,
        },
        /**
         * Set true to trigger the loading spinner.
         * @default False
         * @type {Boolean}
         */
        loading: {
            type: Boolean,
            default: false,
        },
        /**
         * Disables the multiselect if true.
         * @default false
         * @type {Boolean}
         */
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        visibleValue() {
            return this.multiple
                ? this.internalValue.slice(0, this.limit)
                : [];
        },
        deselectLabelText() {
            return this.showLabels
                ? this.deselectLabel
                : '';
        },
        selectLabelText() {
            return this.showLabels
                ? this.selectLabel
                : '';
        },
        selectedLabelText() {
            return this.showLabels
                ? this.selectedLabel
                : '';
        },
    },
};
</script>

<style>
fieldset[disabled] .multiselect {
    pointer-events: none;
}

.multiselect__spinner {
    position: absolute;
    right: 1px;
    top: 1px;
    width: 48px;
    height: 34px;
    background: #fff;
    display: block;
}

.multiselect__spinner:before,
.multiselect__spinner:after {
    position: absolute;
    content: "";
    top: 50%;
    left: 50%;
    margin: -8px 0 0 -8px;
    width: 16px;
    height: 16px;
    border-radius: 100%;
    border-width: 2px;
    box-shadow: 0 0 0 1px transparent;
}

.multiselect__spinner:before {
    animation: spinning 2.4s cubic-bezier(0.41, 0.26, 0.2, 0.62);
    animation-iteration-count: infinite;
}

.multiselect__spinner:after {
    animation: spinning 2.4s cubic-bezier(0.51, 0.09, 0.21, 0.8);
    animation-iteration-count: infinite;
}

.multiselect__loading-enter-active,
.multiselect__loading-leave-active {
    transition: opacity 0.4s ease-in-out;
    opacity: 1;
}

.multiselect__loading-enter,
.multiselect__loading-leave-active {
    opacity: 0;
}

.multiselect,
.multiselect__input,
.multiselect__single {
    font-family: inherit;
    font-size: 14px;
    touch-action: manipulation;
}

.multiselect {
    box-sizing: content-box;
    display: block;
    position: relative;
    width: 100%;
    min-height: 34px;
    text-align: left;
    color: #35495E;
}

.multiselect * {
    box-sizing: border-box;
}

.multiselect:focus {
    outline: none;
}

.multiselect--disabled {
    pointer-events: none;
    opacity: 0.6;
}

.multiselect--active {
    z-index: 50;
}

.multiselect--active .multiselect__current,
.multiselect--active .multiselect__input,
.multiselect--active .multiselect__tags {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}

.multiselect--active .multiselect__select {
    transform: rotateZ(180deg);
}

.multiselect--above.multiselect--active .multiselect__current,
.multiselect--above.multiselect--active .multiselect__input,
.multiselect--above.multiselect--active .multiselect__tags {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}

.multiselect__input,
.multiselect__single {
    position: relative;
    display: inline-block;
    min-height: 20px;
    line-height: 20px;
    border: none;
    border-radius: 5px;
    padding: 0px 0 0 5px;
    width: calc(100%);
    transition: border 0.1s ease;
    box-sizing: border-box;
}

.multiselect__tag ~ .multiselect__input,
.multiselect__tag ~ .multiselect__single {
    width: auto;
}

.multiselect__input:hover,
.multiselect__single:hover {
    border-color: #cfcfcf;
}

.multiselect__input:focus,
.multiselect__single:focus {
    border-color: #a8a8a8;
    outline: none;
}

.multiselect__single {
    padding-left: 6px;
    margin-bottom: 8px;
}

.multiselect__tags {
    min-height: 34px;
    display: block;
    padding: 0.594rem;
    border-radius: 5px;
    border: 1px solid #64758b;
    background: #fff;
    overflow: hidden;
}

.multiselect__tag {
    position: relative;
    display: inline-block;
    padding: 4px 26px 4px 10px;
    border-radius: 5px;
    margin-right: 10px;
    color: #fff;
    line-height: 1;
//margin-bottom: 5px; white-space: nowrap;
}

.multiselect__tag-icon {
    cursor: pointer;
    margin-left: 7px;
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    font-weight: 700;
    font-style: initial;
    width: 22px;
    text-align: center;
    line-height: 22px;
    transition: all 0.2s ease;
    border-radius: 5px;
}

.multiselect__tag-icon:after {
    content: "×";
    color: #C7D2FE;
    font-size: 14px;
}

.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
    background: #316fd3;
}

.multiselect__tag-icon:focus:after,
.multiselect__tag-icon:hover:after {
    color: white;
}

.multiselect__current {
    line-height: 16px;
    min-height: 34px;
    box-sizing: border-box;
    display: block;
    overflow: hidden;
    padding: 8px 12px 0;
    padding-right: 30px;
    white-space: nowrap;
    margin: 0;
    text-decoration: none;
    border-radius: 5px;
    border: 1px solid #E8E8E8;
    cursor: pointer;
}

.multiselect__select {
    line-height: 16px;
    display: block;
    position: absolute;
    box-sizing: border-box;
    widthmin-height: 34px;
    height: 34px;
    right: 1px;
    top: 1px;
    padding: 4px 8px;
    margin: 0;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.multiselect__select:before {
    position: relative;
    right: 0;
    top: 69%;
    color: #999;
    margin-top: 4px;
    border-style: solid;
    border-width: 5px 5px 0 5px;
    border-color: #999999 transparent transparent transparent;
    content: "";
}

.multiselect__placeholder {
    color: #ADADAD;
    display: inline-block;
    margin-bottom: 10px;
    padding-top: 2px;
}

.multiselect--active .multiselect__placeholder {
    display: none;
}

.multiselect__content {
    position: absolute;
    list-style: none;
    display: block;
    width: 100%;
    max-height: 240px;
    overflow: auto;
    padding: 0;
    margin: 0;
    margin-top: -2px;

//border-top: none; border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    z-index: 50;
}

.multiselect--above .multiselect__content {
    bottom: 100%;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-bottom: none;
    border-top: 1px solid #E8E8E8;
}

.multiselect__content::webkit-scrollbar {
    display: none;
}

.multiselect__element {
    display: block;
}

.multiselect__option {
    display: block;
    padding: 12px;
    min-height: 34px;
    line-height: 16px;
    text-decoration: none;
    text-transform: none;
    vertical-align: middle;
    position: relative;
    cursor: pointer;
    white-space: nowrap;
}

.multiselect__option:after {
    top: 0;
    right: 0;
    position: absolute;
    line-height: 34px;
    padding-right: 12px;
    padding-left: 20px;
}

.multiselect__option--highlight {
    background: #3b82f6;
    outline: none;
    color: white;
}

.multiselect__option--highlight:after {
    content: attr(data-select);
    background: #3b82f6;
    color: white;
}

.multiselect__option--selected {
    background: #F3F3F3;
    color: #35495e;
    font-weight: bold;
}

.multiselect__option--selected:after {
    content: attr(data-selected);
    color: silver;
}

.multiselect__option--selected.multiselect__option--highlight {
    background: #FF6A6A;
    color: #fff;
}

.multiselect__option--selected.multiselect__option--highlight:after {
    background: #FF6A6A;
    content: attr(data-deselect);
    color: #fff;
}

.multiselect--disabled {
    background: #ededed;
    pointer-events: none;
}

.multiselect--disabled .multiselect__current,
.multiselect--disabled .multiselect__select {
    background: #ededed;
    color: #a6a6a6;
}

.multiselect__option--disabled {
    background: #ededed;
    color: #a6a6a6;
    cursor: text;
    pointer-events: none;
}

.multiselect__option--disabled.multiselect__option--highlight {
    background: #dedede !important;
}

.multiselect-enter-active,
.multiselect-leave-active {
    transition: all 0.15s ease;
}

.multiselect-enter,
.multiselect-leave-active {
    opacity: 0;
}

@keyframes spinning {
    from {
        transform: rotate(0)
    }
    to {
        transform: rotate(2turn)
    }
}
</style>
