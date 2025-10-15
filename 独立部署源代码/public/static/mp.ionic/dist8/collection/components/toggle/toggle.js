/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { renderHiddenInput, inheritAriaAttributes } from "../../utils/helpers";
import { hapticSelection } from "../../utils/native/haptic";
import { isPlatform } from "../../utils/platform";
import { isRTL } from "../../utils/rtl/index";
import { createColorClasses, hostContext } from "../../utils/theme";
import { checkmarkOutline, removeOutline, ellipseOutline } from "ionicons/icons";
import { config } from "../../global/config";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @slot - The label text to associate with the toggle. Use the "labelPlacement" property to control where the label is placed relative to the toggle.
 *
 * @part track - The background track of the toggle.
 * @part handle - The toggle handle, or knob, used to change the checked state.
 * @part label - The label text describing the toggle.
 * @part supporting-text - Supporting text displayed beneath the toggle label.
 * @part helper-text - Supporting text displayed beneath the toggle label when the toggle is valid.
 * @part error-text - Supporting text displayed beneath the toggle label when the toggle is invalid and touched.
 */
export class Toggle {
    constructor() {
        this.inputId = `ion-tg-${toggleIds++}`;
        this.inputLabelId = `${this.inputId}-lbl`;
        this.helperTextId = `${this.inputId}-helper-text`;
        this.errorTextId = `${this.inputId}-error-text`;
        this.lastDrag = 0;
        this.inheritedAttributes = {};
        this.didLoad = false;
        this.activated = false;
        /**
         * The name of the control, which is submitted with the form data.
         */
        this.name = this.inputId;
        /**
         * If `true`, the toggle is selected.
         */
        this.checked = false;
        /**
         * If `true`, the user cannot interact with the toggle.
         */
        this.disabled = false;
        /**
         * The value of the toggle does not mean if it's checked or not, use the `checked`
         * property for that.
         *
         * The value of a toggle is analogous to the value of a `<input type="checkbox">`,
         * it's only used when the toggle participates in a native `<form>`.
         */
        this.value = 'on';
        /**
         * Enables the on/off accessibility switch labels within the toggle.
         */
        this.enableOnOffLabels = config.get('toggleOnOffLabels');
        /**
         * Where to place the label relative to the input.
         * `"start"`: The label will appear to the left of the toggle in LTR and to the right in RTL.
         * `"end"`: The label will appear to the right of the toggle in LTR and to the left in RTL.
         * `"fixed"`: The label has the same behavior as `"start"` except it also has a fixed width. Long text will be truncated with ellipses ("...").
         * `"stacked"`: The label will appear above the toggle regardless of the direction. The alignment of the label can be controlled with the `alignment` property.
         */
        this.labelPlacement = 'start';
        /**
         * If true, screen readers will announce it as a required field. This property
         * works only for accessibility purposes, it will not prevent the form from
         * submitting if the value is invalid.
         */
        this.required = false;
        this.setupGesture = async () => {
            const { toggleTrack } = this;
            if (toggleTrack) {
                this.gesture = (await import('../../utils/gesture')).createGesture({
                    el: toggleTrack,
                    gestureName: 'toggle',
                    gesturePriority: 100,
                    threshold: 5,
                    passive: false,
                    onStart: () => this.onStart(),
                    onMove: (ev) => this.onMove(ev),
                    onEnd: (ev) => this.onEnd(ev),
                });
                this.disabledChanged();
            }
        };
        this.onKeyDown = (ev) => {
            if (ev.key === ' ') {
                ev.preventDefault();
                if (!this.disabled) {
                    this.toggleChecked();
                }
            }
        };
        this.onClick = (ev) => {
            /**
             * The haptics for the toggle on tap is
             * an iOS-only feature. As such, it should
             * only trigger on iOS.
             */
            const enableHaptics = isPlatform('ios');
            if (this.disabled) {
                return;
            }
            ev.preventDefault();
            if (this.lastDrag + 300 < Date.now()) {
                this.toggleChecked();
                enableHaptics && hapticSelection();
            }
        };
        /**
         * Stops propagation when the display label is clicked,
         * otherwise, two clicks will be triggered.
         */
        this.onDivLabelClick = (ev) => {
            ev.stopPropagation();
        };
        this.onFocus = () => {
            this.ionFocus.emit();
        };
        this.onBlur = () => {
            this.ionBlur.emit();
        };
        this.getSwitchLabelIcon = (mode, checked) => {
            if (mode === 'md') {
                return checked ? checkmarkOutline : removeOutline;
            }
            return checked ? removeOutline : ellipseOutline;
        };
    }
    disabledChanged() {
        if (this.gesture) {
            this.gesture.enable(!this.disabled);
        }
    }
    toggleChecked() {
        const { checked, value } = this;
        const isNowChecked = !checked;
        this.checked = isNowChecked;
        this.setFocus();
        this.ionChange.emit({
            checked: isNowChecked,
            value,
        });
    }
    async connectedCallback() {
        /**
         * If we have not yet rendered
         * ion-toggle, then toggleTrack is not defined.
         * But if we are moving ion-toggle via appendChild,
         * then toggleTrack will be defined.
         */
        if (this.didLoad) {
            this.setupGesture();
        }
    }
    componentDidLoad() {
        this.setupGesture();
        this.didLoad = true;
    }
    disconnectedCallback() {
        if (this.gesture) {
            this.gesture.destroy();
            this.gesture = undefined;
        }
    }
    componentWillLoad() {
        this.inheritedAttributes = Object.assign({}, inheritAriaAttributes(this.el));
    }
    onStart() {
        this.activated = true;
        // touch-action does not work in iOS
        this.setFocus();
    }
    onMove(detail) {
        if (shouldToggle(isRTL(this.el), this.checked, detail.deltaX, -10)) {
            this.toggleChecked();
            hapticSelection();
        }
    }
    onEnd(ev) {
        this.activated = false;
        this.lastDrag = Date.now();
        ev.event.preventDefault();
        ev.event.stopImmediatePropagation();
    }
    getValue() {
        return this.value || '';
    }
    setFocus() {
        if (this.focusEl) {
            this.focusEl.focus();
        }
    }
    renderOnOffSwitchLabels(mode, checked) {
        const icon = this.getSwitchLabelIcon(mode, checked);
        return (h("ion-icon", { class: {
                'toggle-switch-icon': true,
                'toggle-switch-icon-checked': checked,
            }, icon: icon, "aria-hidden": "true" }));
    }
    renderToggleControl() {
        const mode = getIonMode(this);
        const { enableOnOffLabels, checked } = this;
        return (h("div", { class: "toggle-icon", part: "track", ref: (el) => (this.toggleTrack = el) }, enableOnOffLabels &&
            mode === 'ios' && [this.renderOnOffSwitchLabels(mode, true), this.renderOnOffSwitchLabels(mode, false)], h("div", { class: "toggle-icon-wrapper" }, h("div", { class: "toggle-inner", part: "handle" }, enableOnOffLabels && mode === 'md' && this.renderOnOffSwitchLabels(mode, checked)))));
    }
    get hasLabel() {
        return this.el.textContent !== '';
    }
    getHintTextID() {
        const { el, helperText, errorText, helperTextId, errorTextId } = this;
        if (el.classList.contains('ion-touched') && el.classList.contains('ion-invalid') && errorText) {
            return errorTextId;
        }
        if (helperText) {
            return helperTextId;
        }
        return undefined;
    }
    /**
     * Responsible for rendering helper text and error text.
     * This element should only be rendered if hint text is set.
     */
    renderHintText() {
        const { helperText, errorText, helperTextId, errorTextId } = this;
        /**
         * undefined and empty string values should
         * be treated as not having helper/error text.
         */
        const hasHintText = !!helperText || !!errorText;
        if (!hasHintText) {
            return;
        }
        return (h("div", { class: "toggle-bottom" }, h("div", { id: helperTextId, class: "helper-text", part: "supporting-text helper-text" }, helperText), h("div", { id: errorTextId, class: "error-text", part: "supporting-text error-text" }, errorText)));
    }
    render() {
        const { activated, alignment, checked, color, disabled, el, errorTextId, hasLabel, inheritedAttributes, inputId, inputLabelId, justify, labelPlacement, name, required, } = this;
        const mode = getIonMode(this);
        const value = this.getValue();
        const rtl = isRTL(el) ? 'rtl' : 'ltr';
        renderHiddenInput(true, el, name, checked ? value : '', disabled);
        return (h(Host, { key: '21037ea2e8326f58c84becadde475f007f931924', role: "switch", "aria-checked": `${checked}`, "aria-describedby": this.getHintTextID(), "aria-invalid": this.getHintTextID() === errorTextId, onClick: this.onClick, "aria-labelledby": hasLabel ? inputLabelId : null, "aria-label": inheritedAttributes['aria-label'] || null, "aria-disabled": disabled ? 'true' : null, tabindex: disabled ? undefined : 0, onKeyDown: this.onKeyDown, class: createColorClasses(color, {
                [mode]: true,
                'in-item': hostContext('ion-item', el),
                'toggle-activated': activated,
                'toggle-checked': checked,
                'toggle-disabled': disabled,
                [`toggle-justify-${justify}`]: justify !== undefined,
                [`toggle-alignment-${alignment}`]: alignment !== undefined,
                [`toggle-label-placement-${labelPlacement}`]: true,
                [`toggle-${rtl}`]: true,
            }) }, h("label", { key: '4d153679d118d01286f6633d1c19558a97745ff6', class: "toggle-wrapper", htmlFor: inputId }, h("input", Object.assign({ key: '0dfcd4df15b8d41bec5ff5f8912503afbb7bec53', type: "checkbox", role: "switch", "aria-checked": `${checked}`, checked: checked, disabled: disabled, id: inputId, onFocus: () => this.onFocus(), onBlur: () => this.onBlur(), ref: (focusEl) => (this.focusEl = focusEl), required: required }, inheritedAttributes)), h("div", { key: 'ffed3a07ba2ab70e5b232e6041bc3b6b34be8331', class: {
                'label-text-wrapper': true,
                'label-text-wrapper-hidden': !hasLabel,
            }, part: "label", id: inputLabelId, onClick: this.onDivLabelClick }, h("slot", { key: 'd88e1e3dcdd8293f6b61f237cd7a0511dcbce300' }), this.renderHintText()), h("div", { key: '0e924225f5f0caf3c88738acb6c557bd8c1b68f6', class: "native-wrapper" }, this.renderToggleControl()))));
    }
    static get is() { return "ion-toggle"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["toggle.ios.scss"],
            "md": ["toggle.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["toggle.ios.css"],
            "md": ["toggle.md.css"]
        };
    }
    static get properties() {
        return {
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
            "name": {
                "type": "string",
                "attribute": "name",
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
                    "text": "The name of the control, which is submitted with the form data."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "this.inputId"
            },
            "checked": {
                "type": "boolean",
                "attribute": "checked",
                "mutable": true,
                "complexType": {
                    "original": "boolean",
                    "resolved": "boolean",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "If `true`, the toggle is selected."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
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
                    "text": "If `true`, the user cannot interact with the toggle."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "errorText": {
                "type": "string",
                "attribute": "error-text",
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
                    "text": "Text that is placed under the toggle label and displayed when an error is detected."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "helperText": {
                "type": "string",
                "attribute": "helper-text",
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
                    "text": "Text that is placed under the toggle label and displayed when no error is detected."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "value": {
                "type": "string",
                "attribute": "value",
                "mutable": false,
                "complexType": {
                    "original": "string | null",
                    "resolved": "null | string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The value of the toggle does not mean if it's checked or not, use the `checked`\nproperty for that.\n\nThe value of a toggle is analogous to the value of a `<input type=\"checkbox\">`,\nit's only used when the toggle participates in a native `<form>`."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'on'"
            },
            "enableOnOffLabels": {
                "type": "boolean",
                "attribute": "enable-on-off-labels",
                "mutable": false,
                "complexType": {
                    "original": "boolean | undefined",
                    "resolved": "boolean | undefined",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Enables the on/off accessibility switch labels within the toggle."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "config.get('toggleOnOffLabels')"
            },
            "labelPlacement": {
                "type": "string",
                "attribute": "label-placement",
                "mutable": false,
                "complexType": {
                    "original": "'start' | 'end' | 'fixed' | 'stacked'",
                    "resolved": "\"end\" | \"fixed\" | \"stacked\" | \"start\"",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Where to place the label relative to the input.\n`\"start\"`: The label will appear to the left of the toggle in LTR and to the right in RTL.\n`\"end\"`: The label will appear to the right of the toggle in LTR and to the left in RTL.\n`\"fixed\"`: The label has the same behavior as `\"start\"` except it also has a fixed width. Long text will be truncated with ellipses (\"...\").\n`\"stacked\"`: The label will appear above the toggle regardless of the direction. The alignment of the label can be controlled with the `alignment` property."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'start'"
            },
            "justify": {
                "type": "string",
                "attribute": "justify",
                "mutable": false,
                "complexType": {
                    "original": "'start' | 'end' | 'space-between'",
                    "resolved": "\"end\" | \"space-between\" | \"start\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "How to pack the label and toggle within a line.\n`\"start\"`: The label and toggle will appear on the left in LTR and\non the right in RTL.\n`\"end\"`: The label and toggle will appear on the right in LTR and\non the left in RTL.\n`\"space-between\"`: The label and toggle will appear on opposite\nends of the line with space between the two elements.\nSetting this property will change the toggle `display` to `block`."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "alignment": {
                "type": "string",
                "attribute": "alignment",
                "mutable": false,
                "complexType": {
                    "original": "'start' | 'center'",
                    "resolved": "\"center\" | \"start\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "How to control the alignment of the toggle and label on the cross axis.\n`\"start\"`: The label and control will appear on the left of the cross axis in LTR, and on the right side in RTL.\n`\"center\"`: The label and control will appear at the center of the cross axis in both LTR and RTL.\nSetting this property will change the toggle `display` to `block`."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "required": {
                "type": "boolean",
                "attribute": "required",
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
                    "text": "If true, screen readers will announce it as a required field. This property\nworks only for accessibility purposes, it will not prevent the form from\nsubmitting if the value is invalid."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            }
        };
    }
    static get states() {
        return {
            "activated": {}
        };
    }
    static get events() {
        return [{
                "method": "ionChange",
                "name": "ionChange",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted when the user switches the toggle on or off.\n\nThis event will not emit when programmatically setting the `checked` property."
                },
                "complexType": {
                    "original": "ToggleChangeEventDetail",
                    "resolved": "ToggleChangeEventDetail<any>",
                    "references": {
                        "ToggleChangeEventDetail": {
                            "location": "import",
                            "path": "./toggle-interface",
                            "id": "src/components/toggle/toggle-interface.ts::ToggleChangeEventDetail"
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
                    "text": "Emitted when the toggle has focus."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }, {
                "method": "ionBlur",
                "name": "ionBlur",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted when the toggle loses focus."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }];
    }
    static get elementRef() { return "el"; }
    static get watchers() {
        return [{
                "propName": "disabled",
                "methodName": "disabledChanged"
            }];
    }
}
const shouldToggle = (rtl, checked, deltaX, margin) => {
    if (checked) {
        return (!rtl && margin > deltaX) || (rtl && -margin < deltaX);
    }
    else {
        return (!rtl && -margin < deltaX) || (rtl && margin > deltaX);
    }
};
let toggleIds = 0;
