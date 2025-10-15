/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Fragment, Host, h } from "@stencil/core";
import { inheritAriaAttributes } from "../../utils/helpers";
import { printIonWarning } from "../../utils/logging/index";
import { isRTL } from "../../utils/rtl/index";
import { createColorClasses } from "../../utils/theme";
import { getIonMode } from "../../global/ionic-global";
export class InputOTP {
    constructor() {
        this.inheritedAttributes = {};
        this.inputRefs = [];
        this.inputId = `ion-input-otp-${inputIds++}`;
        this.parsedSeparators = [];
        /**
         * Tracks whether the user is navigating through input boxes using keyboard navigation
         * (arrow keys, tab) versus mouse clicks. This is used to determine the appropriate
         * focus behavior when an input box is focused.
         */
        this.isKeyboardNavigation = false;
        this.inputValues = [];
        this.hasFocus = false;
        this.previousInputValues = [];
        /**
         * Indicates whether and how the text value should be automatically capitalized as it is entered/edited by the user.
         * Available options: `"off"`, `"none"`, `"on"`, `"sentences"`, `"words"`, `"characters"`.
         */
        this.autocapitalize = 'off';
        /**
         * If `true`, the user cannot interact with the input.
         */
        this.disabled = false;
        /**
         * The fill for the input boxes. If `"solid"` the input boxes will have a background. If
         * `"outline"` the input boxes will be transparent with a border.
         */
        this.fill = 'outline';
        /**
         * The number of input boxes to display.
         */
        this.length = 4;
        /**
         * If `true`, the user cannot modify the value.
         */
        this.readonly = false;
        /**
         * The shape of the input boxes.
         * If "round" they will have an increased border radius.
         * If "rectangular" they will have no border radius.
         * If "soft" they will have a soft border radius.
         */
        this.shape = 'round';
        /**
         * The size of the input boxes.
         */
        this.size = 'medium';
        /**
         * The type of input allowed in the input boxes.
         */
        this.type = 'number';
        /**
         * The value of the input group.
         */
        this.value = '';
        /**
         * Handles the focus behavior for the input OTP component.
         *
         * Focus behavior:
         * 1. Keyboard navigation: Allow normal focus movement
         * 2. Mouse click:
         *    - If clicked box has value: Focus that box
         *    - If clicked box is empty: Focus first empty box
         *
         * Emits the `ionFocus` event when the input group gains focus.
         */
        this.onFocus = (index) => (event) => {
            var _a;
            const { inputRefs } = this;
            // Only emit ionFocus and set the focusedValue when the
            // component first gains focus
            if (!this.hasFocus) {
                this.ionFocus.emit(event);
                this.focusedValue = this.value;
            }
            this.hasFocus = true;
            let finalIndex = index;
            if (!this.isKeyboardNavigation) {
                // If the clicked box has a value, focus it
                // Otherwise focus the first empty box
                const targetIndex = this.inputValues[index] ? index : this.getFirstEmptyIndex();
                finalIndex = targetIndex === -1 ? this.length - 1 : targetIndex;
                // Focus the target box
                (_a = this.inputRefs[finalIndex]) === null || _a === void 0 ? void 0 : _a.focus();
            }
            // Update tabIndexes to match the focused box
            inputRefs.forEach((input, i) => {
                input.tabIndex = i === finalIndex ? 0 : -1;
            });
            // Reset the keyboard navigation flag
            this.isKeyboardNavigation = false;
        };
        /**
         * Handles the blur behavior for the input OTP component.
         * Emits the `ionBlur` event when the input group loses focus.
         */
        this.onBlur = (event) => {
            const { inputRefs } = this;
            const relatedTarget = event.relatedTarget;
            // Do not emit blur if we're moving to another input box in the same component
            const isInternalFocus = relatedTarget != null && inputRefs.includes(relatedTarget);
            if (!isInternalFocus) {
                this.hasFocus = false;
                // Reset tabIndexes when focus leaves the component
                this.updateTabIndexes();
                // Always emit ionBlur when focus leaves the component
                this.ionBlur.emit(event);
                // Only emit ionChange if the value has actually changed
                if (this.focusedValue !== this.value) {
                    this.emitIonChange(event);
                }
            }
        };
        /**
         * Handles keyboard navigation for the OTP component.
         *
         * Navigation:
         * - Backspace: Clears current input and moves to previous box if empty
         * - Arrow Left/Right: Moves focus between input boxes
         * - Tab: Allows normal tab navigation between components
         */
        this.onKeyDown = (index) => (event) => {
            const { length } = this;
            const rtl = isRTL(this.el);
            const input = event.target;
            // Meta shortcuts are used to copy, paste, and select text
            // We don't want to handle these keys here
            const metaShortcuts = ['a', 'c', 'v', 'x', 'r', 'z', 'y'];
            const isTextSelection = input.selectionStart !== input.selectionEnd;
            // Return if the key is a meta shortcut or the input value
            // text is selected and let the onPaste / onInput handler manage it
            if (isTextSelection || ((event.metaKey || event.ctrlKey) && metaShortcuts.includes(event.key.toLowerCase()))) {
                return;
            }
            if (event.key === 'Backspace') {
                if (this.inputValues[index]) {
                    // Shift all values to the right of the current index left by one
                    for (let i = index; i < length - 1; i++) {
                        this.inputValues[i] = this.inputValues[i + 1];
                    }
                    // Clear the last box
                    this.inputValues[length - 1] = '';
                    // Update all inputRefs to match inputValues
                    for (let i = 0; i < length; i++) {
                        this.inputRefs[i].value = this.inputValues[i] || '';
                    }
                    this.updateValue(event);
                    event.preventDefault();
                }
                else if (!this.inputValues[index] && index > 0) {
                    // If current input is empty, move to previous input
                    this.focusPrevious(index);
                }
            }
            else if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                this.isKeyboardNavigation = true;
                event.preventDefault();
                const isLeft = event.key === 'ArrowLeft';
                const shouldMoveNext = (isLeft && rtl) || (!isLeft && !rtl);
                // Only allow moving to the next input if the current has a value
                if (shouldMoveNext) {
                    if (this.inputValues[index] && index < length - 1) {
                        this.focusNext(index);
                    }
                }
                else {
                    this.focusPrevious(index);
                }
            }
            else if (event.key === 'Tab') {
                this.isKeyboardNavigation = true;
                // Let all tab events proceed normally
                return;
            }
        };
        /**
         * Processes all input scenarios for each input box.
         *
         * This function manages:
         * 1. Autofill handling
         * 2. Input validation
         * 3. Full selection replacement or typing in an empty box
         * 4. Inserting in the middle with available space (shifting)
         * 5. Single character replacement
         */
        this.onInput = (index) => (event) => {
            var _a, _b;
            const { length, validKeyPattern } = this;
            const input = event.target;
            const value = input.value;
            const previousValue = this.previousInputValues[index] || '';
            // 1. Autofill handling
            // If the length of the value increases by more than 1 from the previous
            // value, treat this as autofill. This is to prevent the case where the
            // user is typing a single character into an input box containing a value
            // as that will trigger this function with a value length of 2 characters.
            const isAutofill = value.length - previousValue.length > 1;
            if (isAutofill) {
                // Distribute valid characters across input boxes
                const validChars = value
                    .split('')
                    .filter((char) => validKeyPattern.test(char))
                    .slice(0, length);
                // If there are no valid characters coming from the
                // autofill, all input refs have to be cleared after the
                // browser has finished the autofill behavior
                if (validChars.length === 0) {
                    requestAnimationFrame(() => {
                        this.inputRefs.forEach((input) => {
                            input.value = '';
                        });
                    });
                }
                for (let i = 0; i < length; i++) {
                    this.inputValues[i] = validChars[i] || '';
                    this.inputRefs[i].value = validChars[i] || '';
                }
                this.updateValue(event);
                // Focus the first empty input box or the last input box if all boxes
                // are filled after a small delay to ensure the input boxes have been
                // updated before moving the focus
                setTimeout(() => {
                    var _a;
                    const nextIndex = validChars.length < length ? validChars.length : length - 1;
                    (_a = this.inputRefs[nextIndex]) === null || _a === void 0 ? void 0 : _a.focus();
                }, 20);
                this.previousInputValues = [...this.inputValues];
                return;
            }
            // 2. Input validation
            // If the character entered is invalid (does not match the pattern),
            // restore the previous value and exit
            if (value.length > 0 && !validKeyPattern.test(value[value.length - 1])) {
                input.value = this.inputValues[index] || '';
                this.previousInputValues = [...this.inputValues];
                return;
            }
            // 3. Full selection replacement or typing in an empty box
            // If the user selects all text in the input box and types, or if the
            // input box is empty, replace only this input box. If the box is empty,
            // move to the next box, otherwise stay focused on this box.
            const isAllSelected = input.selectionStart === 0 && input.selectionEnd === value.length;
            const isEmpty = !this.inputValues[index];
            if (isAllSelected || isEmpty) {
                this.inputValues[index] = value;
                input.value = value;
                this.updateValue(event);
                this.focusNext(index);
                this.previousInputValues = [...this.inputValues];
                return;
            }
            // 4. Inserting in the middle with available space (shifting)
            // If typing in a filled input box and there are empty boxes at the end,
            // shift all values starting at the current box to the right, and insert
            // the new character at the current box.
            const hasAvailableBoxAtEnd = this.inputValues[this.inputValues.length - 1] === '';
            if (this.inputValues[index] && hasAvailableBoxAtEnd && value.length === 2) {
                // Get the inserted character (from event or by diffing value/previousValue)
                let newChar = event.data;
                if (!newChar) {
                    newChar = value.split('').find((c, i) => c !== previousValue[i]) || value[value.length - 1];
                }
                // Validate the new character before shifting
                if (!validKeyPattern.test(newChar)) {
                    input.value = this.inputValues[index] || '';
                    this.previousInputValues = [...this.inputValues];
                    return;
                }
                // Shift values right from the end to the insertion point
                for (let i = this.inputValues.length - 1; i > index; i--) {
                    this.inputValues[i] = this.inputValues[i - 1];
                    this.inputRefs[i].value = this.inputValues[i] || '';
                }
                this.inputValues[index] = newChar;
                this.inputRefs[index].value = newChar;
                this.updateValue(event);
                this.previousInputValues = [...this.inputValues];
                return;
            }
            // 5. Single character replacement
            // Handles replacing a single character in a box containing a value based
            // on the cursor position. We need the cursor position to determine which
            // character was the last character typed. For example, if the user types "2"
            // in an input box with the cursor at the beginning of the value of "6",
            // the value will be "26", but we want to grab the "2" as the last character
            // typed.
            const cursorPos = (_a = input.selectionStart) !== null && _a !== void 0 ? _a : value.length;
            const newCharIndex = cursorPos - 1;
            const newChar = (_b = value[newCharIndex]) !== null && _b !== void 0 ? _b : value[0];
            // Check if the new character is valid before updating the value
            if (!validKeyPattern.test(newChar)) {
                input.value = this.inputValues[index] || '';
                this.previousInputValues = [...this.inputValues];
                return;
            }
            this.inputValues[index] = newChar;
            input.value = newChar;
            this.updateValue(event);
            this.previousInputValues = [...this.inputValues];
        };
        /**
         * Handles pasting text into the input OTP component.
         * This function prevents the default paste behavior and
         * validates the pasted text against the allowed pattern.
         * It then updates the value of the input group and focuses
         * the next empty input after pasting.
         */
        this.onPaste = (event) => {
            var _a, _b;
            const { inputRefs, length, validKeyPattern } = this;
            event.preventDefault();
            const pastedText = (_a = event.clipboardData) === null || _a === void 0 ? void 0 : _a.getData('text');
            // If there is no pasted text, still emit the input change event
            // because this is how the native input element behaves
            // but return early because there is nothing to paste.
            if (!pastedText) {
                this.emitIonInput(event);
                return;
            }
            const validChars = pastedText
                .split('')
                .filter((char) => validKeyPattern.test(char))
                .slice(0, length);
            // Always paste starting at the first box
            validChars.forEach((char, index) => {
                if (index < length) {
                    this.inputRefs[index].value = char;
                    this.inputValues[index] = char;
                }
            });
            // Update the value so that all input boxes are updated
            this.value = validChars.join('');
            this.updateValue(event);
            // Focus the next empty input after pasting
            // If all boxes are filled, focus the last input
            const nextEmptyIndex = validChars.length < length ? validChars.length : length - 1;
            (_b = inputRefs[nextEmptyIndex]) === null || _b === void 0 ? void 0 : _b.focus();
        };
    }
    /**
     * Sets focus to an input box.
     * @param index - The index of the input box to focus (0-based).
     * If provided and the input box has a value, the input box at that index will be focused.
     * Otherwise, the first empty input box or the last input if all are filled will be focused.
     */
    async setFocus(index) {
        var _a, _b;
        if (typeof index === 'number') {
            const validIndex = Math.max(0, Math.min(index, this.length - 1));
            (_a = this.inputRefs[validIndex]) === null || _a === void 0 ? void 0 : _a.focus();
        }
        else {
            const tabbableIndex = this.getTabbableIndex();
            (_b = this.inputRefs[tabbableIndex]) === null || _b === void 0 ? void 0 : _b.focus();
        }
    }
    valueChanged() {
        this.initializeValues();
        this.updateTabIndexes();
    }
    /**
     * Processes the separators prop into an array of numbers.
     *
     * If the separators prop is not provided, returns an empty array.
     * If the separators prop is 'all', returns an array of all valid positions (1 to length-1).
     * If the separators prop is an array, returns it as is.
     * If the separators prop is a string, splits it by commas and parses each part as a number.
     *
     * If the separators are greater than the input length, it will warn and ignore the separators.
     */
    processSeparators() {
        const { separators, length } = this;
        if (separators === undefined) {
            this.parsedSeparators = [];
            return;
        }
        if (typeof separators === 'string' && separators !== 'all') {
            const isValidFormat = /^(\d+)(,\d+)*$/.test(separators);
            if (!isValidFormat) {
                printIonWarning(`[ion-input-otp] - Invalid separators format. Expected a comma-separated list of numbers, an array of numbers, or "all". Received: ${separators}`, this.el);
                this.parsedSeparators = [];
                return;
            }
        }
        let separatorValues;
        if (separators === 'all') {
            separatorValues = Array.from({ length: length - 1 }, (_, i) => i + 1);
        }
        else if (Array.isArray(separators)) {
            separatorValues = separators;
        }
        else {
            separatorValues = separators
                .split(',')
                .map((pos) => parseInt(pos, 10))
                .filter((pos) => !isNaN(pos));
        }
        // Check for duplicate separator positions
        const duplicates = separatorValues.filter((pos, index) => separatorValues.indexOf(pos) !== index);
        if (duplicates.length > 0) {
            printIonWarning(`[ion-input-otp] - Duplicate separator positions are not allowed. Received: ${separators}`, this.el);
        }
        const invalidSeparators = separatorValues.filter((pos) => pos > length);
        if (invalidSeparators.length > 0) {
            printIonWarning(`[ion-input-otp] - The following separator positions are greater than the input length (${length}): ${invalidSeparators.join(', ')}. These separators will be ignored.`, this.el);
        }
        this.parsedSeparators = separatorValues.filter((pos) => pos <= length);
    }
    componentWillLoad() {
        this.inheritedAttributes = inheritAriaAttributes(this.el);
        this.processSeparators();
        this.initializeValues();
    }
    componentDidLoad() {
        this.updateTabIndexes();
    }
    /**
     * Get the regex pattern for allowed characters.
     * If a pattern is provided, use it to create a regex pattern
     * Otherwise, use the default regex pattern based on type
     */
    get validKeyPattern() {
        return new RegExp(`^${this.getPattern()}$`, 'u');
    }
    /**
     * Gets the string pattern to pass to the input element
     * and use in the regex for allowed characters.
     */
    getPattern() {
        const { pattern, type } = this;
        if (pattern) {
            return pattern;
        }
        return type === 'number' ? '[\\p{N}]' : '[\\p{L}\\p{N}]';
    }
    /**
     * Get the default value for inputmode.
     * If inputmode is provided, use it.
     * Otherwise, use the default inputmode based on type
     */
    getInputmode() {
        const { inputmode } = this;
        if (inputmode) {
            return inputmode;
        }
        if (this.type == 'number') {
            return 'numeric';
        }
        else {
            return 'text';
        }
    }
    /**
     * Initializes the input values array based on the current value prop.
     * This splits the value into individual characters and validates them against
     * the allowed pattern. The values are then used as the values in the native
     * input boxes and the value of the input group is updated.
     */
    initializeValues() {
        // Clear all input values
        this.inputValues = Array(this.length).fill('');
        // If the value is null, undefined, or an empty string, return
        if (this.value == null || String(this.value).length === 0) {
            return;
        }
        // Split the value into individual characters and validate
        // them against the allowed pattern
        const chars = String(this.value).split('').slice(0, this.length);
        chars.forEach((char, index) => {
            if (this.validKeyPattern.test(char)) {
                this.inputValues[index] = char;
            }
        });
        // Update the value without emitting events
        this.value = this.inputValues.join('');
        this.previousInputValues = [...this.inputValues];
    }
    /**
     * Updates the value of the input group.
     * This updates the value of the input group and emits an `ionChange` event.
     * If all of the input boxes are filled, it emits an `ionComplete` event.
     */
    updateValue(event) {
        const { inputValues, length } = this;
        const newValue = inputValues.join('');
        this.value = newValue;
        this.emitIonInput(event);
        if (newValue.length === length) {
            this.ionComplete.emit({ value: newValue });
        }
    }
    /**
     * Emits an `ionChange` event.
     * This API should be called for user committed changes.
     * This API should not be used for external value changes.
     */
    emitIonChange(event) {
        const { value } = this;
        // Checks for both null and undefined values
        const newValue = value == null ? value : value.toString();
        this.ionChange.emit({ value: newValue, event });
    }
    /**
     * Emits an `ionInput` event.
     * This is used to emit the input value when the user types,
     * backspaces, or pastes.
     */
    emitIonInput(event) {
        const { value } = this;
        // Checks for both null and undefined values
        const newValue = value == null ? value : value.toString();
        this.ionInput.emit({ value: newValue, event });
    }
    /**
     * Focuses the next input box.
     */
    focusNext(currentIndex) {
        var _a;
        const { inputRefs, length } = this;
        if (currentIndex < length - 1) {
            (_a = inputRefs[currentIndex + 1]) === null || _a === void 0 ? void 0 : _a.focus();
        }
    }
    /**
     * Focuses the previous input box.
     */
    focusPrevious(currentIndex) {
        var _a;
        const { inputRefs } = this;
        if (currentIndex > 0) {
            (_a = inputRefs[currentIndex - 1]) === null || _a === void 0 ? void 0 : _a.focus();
        }
    }
    /**
     * Searches through the input values and returns the index
     * of the first empty input.
     * Returns -1 if all inputs are filled.
     */
    getFirstEmptyIndex() {
        var _a;
        const { inputValues, length } = this;
        // Create an array of the same length as the input OTP
        // and fill it with the input values
        const values = Array.from({ length }, (_, i) => inputValues[i] || '');
        return (_a = values.findIndex((value) => !value || value === '')) !== null && _a !== void 0 ? _a : -1;
    }
    /**
     * Returns the index of the input that should be tabbed to.
     * If all inputs are filled, returns the last input's index.
     * Otherwise, returns the index of the first empty input.
     */
    getTabbableIndex() {
        const { length } = this;
        const firstEmptyIndex = this.getFirstEmptyIndex();
        return firstEmptyIndex === -1 ? length - 1 : firstEmptyIndex;
    }
    /**
     * Updates the tabIndexes for the input boxes.
     * This is used to ensure that the correct input is
     * focused when the user navigates using the tab key.
     */
    updateTabIndexes() {
        const { inputRefs, inputValues, length } = this;
        // Find first empty index after any filled boxes
        let firstEmptyIndex = -1;
        for (let i = 0; i < length; i++) {
            if (!inputValues[i] || inputValues[i] === '') {
                firstEmptyIndex = i;
                break;
            }
        }
        // Update tabIndex and aria-hidden for all inputs
        inputRefs.forEach((input, index) => {
            const shouldBeTabbable = firstEmptyIndex === -1 ? index === length - 1 : firstEmptyIndex === index;
            input.tabIndex = shouldBeTabbable ? 0 : -1;
            // If the input is empty and not the first empty input,
            // it should be hidden from screen readers.
            const isEmpty = !inputValues[index] || inputValues[index] === '';
            input.setAttribute('aria-hidden', isEmpty && !shouldBeTabbable ? 'true' : 'false');
        });
    }
    /**
     * Determines if a separator should be shown for a given index by
     * checking if the index is included in the parsed separators array.
     */
    showSeparator(index) {
        const { length } = this;
        return this.parsedSeparators.includes(index + 1) && index < length - 1;
    }
    render() {
        var _a, _b;
        const { autocapitalize, color, disabled, el, fill, hasFocus, inheritedAttributes, inputId, inputRefs, inputValues, length, readonly, shape, size, } = this;
        const mode = getIonMode(this);
        const inputmode = this.getInputmode();
        const tabbableIndex = this.getTabbableIndex();
        const pattern = this.getPattern();
        const hasDescription = ((_b = (_a = el.querySelector('.input-otp-description')) === null || _a === void 0 ? void 0 : _a.textContent) === null || _b === void 0 ? void 0 : _b.trim()) !== '';
        return (h(Host, { key: 'f15a29fb17b681ef55885ca36d3d5f899cbaca83', class: createColorClasses(color, {
                [mode]: true,
                'has-focus': hasFocus,
                [`input-otp-size-${size}`]: true,
                [`input-otp-shape-${shape}`]: true,
                [`input-otp-fill-${fill}`]: true,
                'input-otp-disabled': disabled,
                'input-otp-readonly': readonly,
            }) }, h("div", Object.assign({ key: 'd7e1d4edd8aafcf2ed4313301287282e90fc7e82', role: "group", "aria-label": "One-time password input", class: "input-otp-group" }, inheritedAttributes), Array.from({ length }).map((_, index) => (h(Fragment, null, h("div", { class: "native-wrapper" }, h("input", { class: "native-input", id: `${inputId}-${index}`, "aria-label": `Input ${index + 1} of ${length}`, type: "text", autoCapitalize: autocapitalize, inputmode: inputmode, pattern: pattern, disabled: disabled, readOnly: readonly, tabIndex: index === tabbableIndex ? 0 : -1, value: inputValues[index] || '', autocomplete: "one-time-code", ref: (el) => (inputRefs[index] = el), onInput: this.onInput(index), onBlur: this.onBlur, onFocus: this.onFocus(index), onKeyDown: this.onKeyDown(index), onPaste: this.onPaste })), this.showSeparator(index) && h("div", { class: "input-otp-separator" }))))), h("div", { key: '3724a3159d02860971879a906092f9965f5a7c47', class: {
                'input-otp-description': true,
                'input-otp-description-hidden': !hasDescription,
            } }, h("slot", { key: '11baa2624926a08274508afe0833d9237a8dc35c' }))));
    }
    static get is() { return "ion-input-otp"; }
    static get encapsulation() { return "scoped"; }
    static get originalStyleUrls() {
        return {
            "ios": ["input-otp.ios.scss"],
            "md": ["input-otp.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["input-otp.ios.css"],
            "md": ["input-otp.md.css"]
        };
    }
    static get properties() {
        return {
            "autocapitalize": {
                "type": "string",
                "attribute": "autocapitalize",
                "mutable": false,
                "complexType": {
                    "original": "string",
                    "resolved": "string",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Indicates whether and how the text value should be automatically capitalized as it is entered/edited by the user.\nAvailable options: `\"off\"`, `\"none\"`, `\"on\"`, `\"sentences\"`, `\"words\"`, `\"characters\"`."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'off'"
            },
            "color": {
                "type": "string",
                "attribute": "color",
                "mutable": false,
                "complexType": {
                    "original": "Color",
                    "resolved": "\"danger\" | \"dark\" | \"light\" | \"medium\" | \"primary\" | \"secondary\" | \"success\" | \"tertiary\" | \"warning\" | string & Record<never, never> | undefined",
                    "references": {
                        "Color": {
                            "location": "import",
                            "path": "../../interface",
                            "id": "src/interface.d.ts::Color"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The color to use from your application's color palette.\nDefault options are: `\"primary\"`, `\"secondary\"`, `\"tertiary\"`, `\"success\"`, `\"warning\"`, `\"danger\"`, `\"light\"`, `\"medium\"`, and `\"dark\"`.\nFor more information on colors, see [theming](/docs/theming/basics)."
                },
                "getter": false,
                "setter": false,
                "reflect": true
            },
            "disabled": {
                "type": "boolean",
                "attribute": "disabled",
                "mutable": false,
                "complexType": {
                    "original": "boolean",
                    "resolved": "boolean",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "If `true`, the user cannot interact with the input."
                },
                "getter": false,
                "setter": false,
                "reflect": true,
                "defaultValue": "false"
            },
            "fill": {
                "type": "string",
                "attribute": "fill",
                "mutable": false,
                "complexType": {
                    "original": "'outline' | 'solid'",
                    "resolved": "\"outline\" | \"solid\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The fill for the input boxes. If `\"solid\"` the input boxes will have a background. If\n`\"outline\"` the input boxes will be transparent with a border."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'outline'"
            },
            "inputmode": {
                "type": "string",
                "attribute": "inputmode",
                "mutable": false,
                "complexType": {
                    "original": "'none' | 'text' | 'tel' | 'url' | 'email' | 'numeric' | 'decimal' | 'search'",
                    "resolved": "\"decimal\" | \"email\" | \"none\" | \"numeric\" | \"search\" | \"tel\" | \"text\" | \"url\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "A hint to the browser for which keyboard to display.\nPossible values: `\"none\"`, `\"text\"`, `\"tel\"`, `\"url\"`,\n`\"email\"`, `\"numeric\"`, `\"decimal\"`, and `\"search\"`.\n\nFor numbers (type=\"number\"): \"numeric\"\nFor text (type=\"text\"): \"text\""
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "length": {
                "type": "number",
                "attribute": "length",
                "mutable": false,
                "complexType": {
                    "original": "number",
                    "resolved": "number",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The number of input boxes to display."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "4"
            },
            "pattern": {
                "type": "string",
                "attribute": "pattern",
                "mutable": false,
                "complexType": {
                    "original": "string",
                    "resolved": "string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "A regex pattern string for allowed characters. Defaults based on type.\n\nFor numbers (`type=\"number\"`): `\"[\\p{N}]\"`\nFor text (`type=\"text\"`): `\"[\\p{L}\\p{N}]\"`"
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "readonly": {
                "type": "boolean",
                "attribute": "readonly",
                "mutable": false,
                "complexType": {
                    "original": "boolean",
                    "resolved": "boolean",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "If `true`, the user cannot modify the value."
                },
                "getter": false,
                "setter": false,
                "reflect": true,
                "defaultValue": "false"
            },
            "separators": {
                "type": "string",
                "attribute": "separators",
                "mutable": false,
                "complexType": {
                    "original": "'all' | string | number[]",
                    "resolved": "number[] | string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Where separators should be shown between input boxes.\nCan be a comma-separated string or an array of numbers.\n\nFor example:\n`\"3\"` will show a separator after the 3rd input box.\n`[1,4]` will show a separator after the 1st and 4th input boxes.\n`\"all\"` will show a separator between every input box."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "shape": {
                "type": "string",
                "attribute": "shape",
                "mutable": false,
                "complexType": {
                    "original": "'round' | 'rectangular' | 'soft'",
                    "resolved": "\"rectangular\" | \"round\" | \"soft\"",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The shape of the input boxes.\nIf \"round\" they will have an increased border radius.\nIf \"rectangular\" they will have no border radius.\nIf \"soft\" they will have a soft border radius."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'round'"
            },
            "size": {
                "type": "string",
                "attribute": "size",
                "mutable": false,
                "complexType": {
                    "original": "'small' | 'medium' | 'large'",
                    "resolved": "\"large\" | \"medium\" | \"small\"",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The size of the input boxes."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'medium'"
            },
            "type": {
                "type": "string",
                "attribute": "type",
                "mutable": false,
                "complexType": {
                    "original": "'text' | 'number'",
                    "resolved": "\"number\" | \"text\"",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The type of input allowed in the input boxes."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'number'"
            },
            "value": {
                "type": "any",
                "attribute": "value",
                "mutable": true,
                "complexType": {
                    "original": "string | number | null",
                    "resolved": "null | number | string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The value of the input group."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "''"
            }
        };
    }
    static get states() {
        return {
            "inputValues": {},
            "hasFocus": {},
            "previousInputValues": {}
        };
    }
    static get events() {
        return [{
                "method": "ionInput",
                "name": "ionInput",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "The `ionInput` event is fired each time the user modifies the input's value.\nUnlike the `ionChange` event, the `ionInput` event is fired for each alteration\nto the input's value. This typically happens for each keystroke as the user types.\n\nFor elements that accept text input (`type=text`, `type=tel`, etc.), the interface\nis [`InputEvent`](https://developer.mozilla.org/en-US/docs/Web/API/InputEvent); for others,\nthe interface is [`Event`](https://developer.mozilla.org/en-US/docs/Web/API/Event). If\nthe input is cleared on edit, the type is `null`."
                },
                "complexType": {
                    "original": "InputOtpInputEventDetail",
                    "resolved": "InputOtpInputEventDetail",
                    "references": {
                        "InputOtpInputEventDetail": {
                            "location": "import",
                            "path": "./input-otp-interface",
                            "id": "src/components/input-otp/input-otp-interface.ts::InputOtpInputEventDetail"
                        }
                    }
                }
            }, {
                "method": "ionChange",
                "name": "ionChange",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "The `ionChange` event is fired when the user modifies the input's value.\nUnlike the `ionInput` event, the `ionChange` event is only fired when changes\nare committed, not as the user types.\n\nThe `ionChange` event fires when the `<ion-input-otp>` component loses\nfocus after its value has changed.\n\nThis event will not emit when programmatically setting the `value` property."
                },
                "complexType": {
                    "original": "InputOtpChangeEventDetail",
                    "resolved": "InputOtpChangeEventDetail",
                    "references": {
                        "InputOtpChangeEventDetail": {
                            "location": "import",
                            "path": "./input-otp-interface",
                            "id": "src/components/input-otp/input-otp-interface.ts::InputOtpChangeEventDetail"
                        }
                    }
                }
            }, {
                "method": "ionComplete",
                "name": "ionComplete",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted when all input boxes have been filled with valid values."
                },
                "complexType": {
                    "original": "InputOtpCompleteEventDetail",
                    "resolved": "InputOtpCompleteEventDetail",
                    "references": {
                        "InputOtpCompleteEventDetail": {
                            "location": "import",
                            "path": "./input-otp-interface",
                            "id": "src/components/input-otp/input-otp-interface.ts::InputOtpCompleteEventDetail"
                        }
                    }
                }
            }, {
                "method": "ionBlur",
                "name": "ionBlur",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted when the input group loses focus."
                },
                "complexType": {
                    "original": "FocusEvent",
                    "resolved": "FocusEvent",
                    "references": {
                        "FocusEvent": {
                            "location": "global",
                            "id": "global::FocusEvent"
                        }
                    }
                }
            }, {
                "method": "ionFocus",
                "name": "ionFocus",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted when the input group has focus."
                },
                "complexType": {
                    "original": "FocusEvent",
                    "resolved": "FocusEvent",
                    "references": {
                        "FocusEvent": {
                            "location": "global",
                            "id": "global::FocusEvent"
                        }
                    }
                }
            }];
    }
    static get methods() {
        return {
            "setFocus": {
                "complexType": {
                    "signature": "(index?: number) => Promise<void>",
                    "parameters": [{
                            "name": "index",
                            "type": "number | undefined",
                            "docs": "- The index of the input box to focus (0-based).\nIf provided and the input box has a value, the input box at that index will be focused.\nOtherwise, the first empty input box or the last input if all are filled will be focused."
                        }],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        }
                    },
                    "return": "Promise<void>"
                },
                "docs": {
                    "text": "Sets focus to an input box.",
                    "tags": [{
                            "name": "param",
                            "text": "index - The index of the input box to focus (0-based).\nIf provided and the input box has a value, the input box at that index will be focused.\nOtherwise, the first empty input box or the last input if all are filled will be focused."
                        }]
                }
            }
        };
    }
    static get elementRef() { return "el"; }
    static get watchers() {
        return [{
                "propName": "value",
                "methodName": "valueChanged"
            }, {
                "propName": "separators",
                "methodName": "processSeparators"
            }, {
                "propName": "length",
                "methodName": "processSeparators"
            }];
    }
}
let inputIds = 0;
