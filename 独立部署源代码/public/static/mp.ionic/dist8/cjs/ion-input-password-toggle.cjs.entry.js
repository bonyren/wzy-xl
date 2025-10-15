/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
'use strict';

var index = require('./index-DNh170BW.js');
var theme = require('./theme-CeDs6Hcv.js');
var index$1 = require('./index-DqmRDbxg.js');
var ionicGlobal = require('./ionic-global-UI5YPSi-.js');

const iosInputPasswordToggleCss = "";

const mdInputPasswordToggleCss = "";

const InputPasswordToggle = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
        /**
         * @internal
         */
        this.type = 'password';
        this.togglePasswordVisibility = () => {
            const { inputElRef } = this;
            if (!inputElRef) {
                return;
            }
            inputElRef.type = inputElRef.type === 'text' ? 'password' : 'text';
        };
    }
    /**
     * Whenever the input type changes we need to re-run validation to ensure the password
     * toggle is being used with the correct input type. If the application changes the type
     * outside of this component we also need to re-render so the correct icon is shown.
     */
    onTypeChange(newValue) {
        if (newValue !== 'text' && newValue !== 'password') {
            index.printIonWarning(`[ion-input-password-toggle] - Only inputs of type "text" or "password" are supported. Input of type "${newValue}" is not compatible.`, this.el);
            return;
        }
    }
    connectedCallback() {
        const { el } = this;
        const inputElRef = (this.inputElRef = el.closest('ion-input'));
        if (!inputElRef) {
            index.printIonWarning('[ion-input-password-toggle] - No ancestor ion-input found. This component must be slotted inside of an ion-input.', el);
            return;
        }
        /**
         * Important: Set the type in connectedCallback because the default value
         * of this.type may not always be accurate. Usually inputs have the "password" type
         * but it is possible to have the input to initially have the "text" type. In that scenario
         * the wrong icon will show briefly before switching to the correct icon. Setting the
         * type here allows us to avoid that flicker.
         */
        this.type = inputElRef.type;
    }
    disconnectedCallback() {
        this.inputElRef = null;
    }
    render() {
        var _a, _b;
        const { color, type } = this;
        const mode = ionicGlobal.getIonMode(this);
        const showPasswordIcon = (_a = this.showIcon) !== null && _a !== void 0 ? _a : index$1.eye;
        const hidePasswordIcon = (_b = this.hideIcon) !== null && _b !== void 0 ? _b : index$1.eyeOff;
        const isPasswordVisible = type === 'text';
        return (index.h(index.Host, { key: '91bc55664d496fe457518bd112865dd7811d0c17', class: theme.createColorClasses(color, {
                [mode]: true,
            }) }, index.h("ion-button", { key: 'f3e436422110c9cb4d5c0b83500255b24ab4cdef', mode: mode, color: color, fill: "clear", shape: "round", "aria-checked": isPasswordVisible ? 'true' : 'false', "aria-label": isPasswordVisible ? 'Hide password' : 'Show password', role: "switch", type: "button", onPointerDown: (ev) => {
                /**
                 * This prevents mobile browsers from
                 * blurring the input when the password toggle
                 * button is activated.
                 */
                ev.preventDefault();
            }, onClick: this.togglePasswordVisibility }, index.h("ion-icon", { key: '5c8b121153f148f92aa7cba0447673a4f6f3ad1e', slot: "icon-only", "aria-hidden": "true", icon: isPasswordVisible ? hidePasswordIcon : showPasswordIcon }))));
    }
    get el() { return index.getElement(this); }
    static get watchers() { return {
        "type": ["onTypeChange"]
    }; }
};
InputPasswordToggle.style = {
    ios: iosInputPasswordToggleCss,
    md: mdInputPasswordToggleCss
};

exports.ion_input_password_toggle = InputPasswordToggle;
