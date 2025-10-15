import type { ComponentInterface, EventEmitter } from '../../stencil-public-runtime';
import type { Color } from '../../interface';
import type { InputOtpChangeEventDetail, InputOtpCompleteEventDetail, InputOtpInputEventDetail } from './input-otp-interface';
export declare class InputOTP implements ComponentInterface {
    private inheritedAttributes;
    private inputRefs;
    private inputId;
    private parsedSeparators;
    /**
     * Stores the initial value of the input when it receives focus.
     * Used to determine if the value changed during the focus session
     * to avoid emitting unnecessary change events on blur.
     */
    private focusedValue?;
    /**
     * Tracks whether the user is navigating through input boxes using keyboard navigation
     * (arrow keys, tab) versus mouse clicks. This is used to determine the appropriate
     * focus behavior when an input box is focused.
     */
    private isKeyboardNavigation;
    el: HTMLIonInputOtpElement;
    private inputValues;
    hasFocus: boolean;
    private previousInputValues;
    /**
     * Indicates whether and how the text value should be automatically capitalized as it is entered/edited by the user.
     * Available options: `"off"`, `"none"`, `"on"`, `"sentences"`, `"words"`, `"characters"`.
     */
    autocapitalize: string;
    /**
     * The color to use from your application's color palette.
     * Default options are: `"primary"`, `"secondary"`, `"tertiary"`, `"success"`, `"warning"`, `"danger"`, `"light"`, `"medium"`, and `"dark"`.
     * For more information on colors, see [theming](/docs/theming/basics).
     */
    color?: Color;
    /**
     * If `true`, the user cannot interact with the input.
     */
    disabled: boolean;
    /**
     * The fill for the input boxes. If `"solid"` the input boxes will have a background. If
     * `"outline"` the input boxes will be transparent with a border.
     */
    fill?: 'outline' | 'solid';
    /**
     * A hint to the browser for which keyboard to display.
     * Possible values: `"none"`, `"text"`, `"tel"`, `"url"`,
     * `"email"`, `"numeric"`, `"decimal"`, and `"search"`.
     *
     * For numbers (type="number"): "numeric"
     * For text (type="text"): "text"
     */
    inputmode?: 'none' | 'text' | 'tel' | 'url' | 'email' | 'numeric' | 'decimal' | 'search';
    /**
     * The number of input boxes to display.
     */
    length: number;
    /**
     * A regex pattern string for allowed characters. Defaults based on type.
     *
     * For numbers (`type="number"`): `"[\p{N}]"`
     * For text (`type="text"`): `"[\p{L}\p{N}]"`
     */
    pattern?: string;
    /**
     * If `true`, the user cannot modify the value.
     */
    readonly: boolean;
    /**
     * Where separators should be shown between input boxes.
     * Can be a comma-separated string or an array of numbers.
     *
     * For example:
     * `"3"` will show a separator after the 3rd input box.
     * `[1,4]` will show a separator after the 1st and 4th input boxes.
     * `"all"` will show a separator between every input box.
     */
    separators?: 'all' | string | number[];
    /**
     * The shape of the input boxes.
     * If "round" they will have an increased border radius.
     * If "rectangular" they will have no border radius.
     * If "soft" they will have a soft border radius.
     */
    shape: 'round' | 'rectangular' | 'soft';
    /**
     * The size of the input boxes.
     */
    size: 'small' | 'medium' | 'large';
    /**
     * The type of input allowed in the input boxes.
     */
    type: 'text' | 'number';
    /**
     * The value of the input group.
     */
    value?: string | number | null;
    /**
     * The `ionInput` event is fired each time the user modifies the input's value.
     * Unlike the `ionChange` event, the `ionInput` event is fired for each alteration
     * to the input's value. This typically happens for each keystroke as the user types.
     *
     * For elements that accept text input (`type=text`, `type=tel`, etc.), the interface
     * is [`InputEvent`](https://developer.mozilla.org/en-US/docs/Web/API/InputEvent); for others,
     * the interface is [`Event`](https://developer.mozilla.org/en-US/docs/Web/API/Event). If
     * the input is cleared on edit, the type is `null`.
     */
    ionInput: EventEmitter<InputOtpInputEventDetail>;
    /**
     * The `ionChange` event is fired when the user modifies the input's value.
     * Unlike the `ionInput` event, the `ionChange` event is only fired when changes
     * are committed, not as the user types.
     *
     * The `ionChange` event fires when the `<ion-input-otp>` component loses
     * focus after its value has changed.
     *
     * This event will not emit when programmatically setting the `value` property.
     */
    ionChange: EventEmitter<InputOtpChangeEventDetail>;
    /**
     * Emitted when all input boxes have been filled with valid values.
     */
    ionComplete: EventEmitter<InputOtpCompleteEventDetail>;
    /**
     * Emitted when the input group loses focus.
     */
    ionBlur: EventEmitter<FocusEvent>;
    /**
     * Emitted when the input group has focus.
     */
    ionFocus: EventEmitter<FocusEvent>;
    /**
     * Sets focus to an input box.
     * @param index - The index of the input box to focus (0-based).
     * If provided and the input box has a value, the input box at that index will be focused.
     * Otherwise, the first empty input box or the last input if all are filled will be focused.
     */
    setFocus(index?: number): Promise<void>;
    valueChanged(): void;
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
    private processSeparators;
    componentWillLoad(): void;
    componentDidLoad(): void;
    /**
     * Get the regex pattern for allowed characters.
     * If a pattern is provided, use it to create a regex pattern
     * Otherwise, use the default regex pattern based on type
     */
    private get validKeyPattern();
    /**
     * Gets the string pattern to pass to the input element
     * and use in the regex for allowed characters.
     */
    private getPattern;
    /**
     * Get the default value for inputmode.
     * If inputmode is provided, use it.
     * Otherwise, use the default inputmode based on type
     */
    private getInputmode;
    /**
     * Initializes the input values array based on the current value prop.
     * This splits the value into individual characters and validates them against
     * the allowed pattern. The values are then used as the values in the native
     * input boxes and the value of the input group is updated.
     */
    private initializeValues;
    /**
     * Updates the value of the input group.
     * This updates the value of the input group and emits an `ionChange` event.
     * If all of the input boxes are filled, it emits an `ionComplete` event.
     */
    private updateValue;
    /**
     * Emits an `ionChange` event.
     * This API should be called for user committed changes.
     * This API should not be used for external value changes.
     */
    private emitIonChange;
    /**
     * Emits an `ionInput` event.
     * This is used to emit the input value when the user types,
     * backspaces, or pastes.
     */
    private emitIonInput;
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
    private onFocus;
    /**
     * Handles the blur behavior for the input OTP component.
     * Emits the `ionBlur` event when the input group loses focus.
     */
    private onBlur;
    /**
     * Focuses the next input box.
     */
    private focusNext;
    /**
     * Focuses the previous input box.
     */
    private focusPrevious;
    /**
     * Searches through the input values and returns the index
     * of the first empty input.
     * Returns -1 if all inputs are filled.
     */
    private getFirstEmptyIndex;
    /**
     * Returns the index of the input that should be tabbed to.
     * If all inputs are filled, returns the last input's index.
     * Otherwise, returns the index of the first empty input.
     */
    private getTabbableIndex;
    /**
     * Updates the tabIndexes for the input boxes.
     * This is used to ensure that the correct input is
     * focused when the user navigates using the tab key.
     */
    private updateTabIndexes;
    /**
     * Handles keyboard navigation for the OTP component.
     *
     * Navigation:
     * - Backspace: Clears current input and moves to previous box if empty
     * - Arrow Left/Right: Moves focus between input boxes
     * - Tab: Allows normal tab navigation between components
     */
    private onKeyDown;
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
    private onInput;
    /**
     * Handles pasting text into the input OTP component.
     * This function prevents the default paste behavior and
     * validates the pasted text against the allowed pattern.
     * It then updates the value of the input group and focuses
     * the next empty input after pasting.
     */
    private onPaste;
    /**
     * Determines if a separator should be shown for a given index by
     * checking if the index is included in the parsed separators array.
     */
    private showSeparator;
    render(): any;
}
