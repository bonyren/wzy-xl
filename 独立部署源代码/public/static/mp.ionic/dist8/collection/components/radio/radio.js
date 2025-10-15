/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { isOptionSelected } from "../../utils/forms/index";
import { addEventListener, removeEventListener } from "../../utils/helpers";
import { createColorClasses, hostContext } from "../../utils/theme";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @slot - The label text to associate with the radio. Use the "labelPlacement" property to control where the label is placed relative to the radio.
 *
 * @part container - The container for the radio mark.
 * @part label - The label text describing the radio.
 * @part mark - The checkmark or dot used to indicate the checked state.
 */
export class Radio {
    constructor() {
        this.inputId = `ion-rb-${radioButtonIds++}`;
        this.radioGroup = null;
        /**
         * If `true`, the radio is selected.
         */
        this.checked = false;
        /**
         * The tabindex of the radio button.
         * @internal
         */
        this.buttonTabindex = -1;
        /**
         * The name of the control, which is submitted with the form data.
         */
        this.name = this.inputId;
        /**
         * If `true`, the user cannot interact with the radio.
         */
        this.disabled = false;
        /**
         * Where to place the label relative to the radio.
         * `"start"`: The label will appear to the left of the radio in LTR and to the right in RTL.
         * `"end"`: The label will appear to the right of the radio in LTR and to the left in RTL.
         * `"fixed"`: The label has the same behavior as `"start"` except it also has a fixed width. Long text will be truncated with ellipses ("...").
         * `"stacked"`: The label will appear above the radio regardless of the direction. The alignment of the label can be controlled with the `alignment` property.
         */
        this.labelPlacement = 'start';
        this.updateState = () => {
            if (this.radioGroup) {
                const { compareWith, value: radioGroupValue } = this.radioGroup;
                this.checked = isOptionSelected(radioGroupValue, this.value, compareWith);
            }
        };
        this.onClick = () => {
            const { radioGroup, checked, disabled } = this;
            if (disabled) {
                return;
            }
            /**
             * The modern control does not use a native input
             * inside of the radio host, so we cannot rely on the
             * ev.preventDefault() behavior above. If the radio
             * is checked and the parent radio group allows for empty
             * selection, then we can set the checked state to false.
             * Otherwise, the checked state should always be set
             * to true because the checked state cannot be toggled.
             */
            if (checked && (radioGroup === null || radioGroup === void 0 ? void 0 : radioGroup.allowEmptySelection)) {
                this.checked = false;
            }
            else {
                this.checked = true;
            }
        };
        this.onFocus = () => {
            this.ionFocus.emit();
        };
        this.onBlur = () => {
            this.ionBlur.emit();
        };
    }
    valueChanged() {
        /**
         * The new value of the radio may
         * match the radio group's value,
         * so we see if it should be checked.
         */
        this.updateState();
    }
    componentDidLoad() {
        /**
         * The value may be `undefined` if it
         * gets set before the radio is
         * rendered. This ensures that the radio
         * is checked if the value matches. This
         * happens most often when Angular is
         * rendering the radio.
         */
        this.updateState();
    }
    /** @internal */
    async setFocus(ev) {
        if (ev !== undefined) {
            ev.stopPropagation();
            ev.preventDefault();
        }
        this.el.focus();
    }
    /** @internal */
    async setButtonTabindex(value) {
        this.buttonTabindex = value;
    }
    connectedCallback() {
        if (this.value === undefined) {
            this.value = this.inputId;
        }
        const radioGroup = (this.radioGroup = this.el.closest('ion-radio-group'));
        if (radioGroup) {
            this.updateState();
            addEventListener(radioGroup, 'ionValueChange', this.updateState);
        }
    }
    disconnectedCallback() {
        const radioGroup = this.radioGroup;
        if (radioGroup) {
            removeEventListener(radioGroup, 'ionValueChange', this.updateState);
            this.radioGroup = null;
        }
    }
    get hasLabel() {
        return this.el.textContent !== '';
    }
    renderRadioControl() {
        return (h("div", { class: "radio-icon", part: "container" }, h("div", { class: "radio-inner", part: "mark" }), h("div", { class: "radio-ripple" })));
    }
    render() {
        const { checked, disabled, color, el, justify, labelPlacement, hasLabel, buttonTabindex, alignment } = this;
        const mode = getIonMode(this);
        const inItem = hostContext('ion-item', el);
        return (h(Host, { key: '3353b28172b7f837d4b38964169b5b5f4ba02788', onFocus: this.onFocus, onBlur: this.onBlur, onClick: this.onClick, class: createColorClasses(color, {
                [mode]: true,
                'in-item': inItem,
                'radio-checked': checked,
                'radio-disabled': disabled,
                [`radio-justify-${justify}`]: justify !== undefined,
                [`radio-alignment-${alignment}`]: alignment !== undefined,
                [`radio-label-placement-${labelPlacement}`]: true,
                // Focus and active styling should not apply when the radio is in an item
                'ion-activatable': !inItem,
                'ion-focusable': !inItem,
            }), role: "radio", "aria-checked": checked ? 'true' : 'false', "aria-disabled": disabled ? 'true' : null, tabindex: buttonTabindex }, h("label", { key: '418a0a48366ff900e97da123abf665bbbda87fb7', class: "radio-wrapper" }, h("div", { key: '6e5acdd8c8f5d0ad26632a65396afef8094153d1', class: {
                'label-text-wrapper': true,
                'label-text-wrapper-hidden': !hasLabel,
            }, part: "label" }, h("slot", { key: '10b157162cd283d624153c747679609cf0bbf11e' })), h("div", { key: '4c45cca95cb105cd6df1025a26e3c045272184a0', class: "native-wrapper" }, this.renderRadioControl()))));
    }
    static get is() { return "ion-radio"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["radio.ios.scss"],
            "md": ["radio.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["radio.ios.css"],
            "md": ["radio.md.css"]
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
                    "text": "If `true`, the user cannot interact with the radio."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "value": {
                "type": "any",
                "attribute": "value",
                "mutable": false,
                "complexType": {
                    "original": "any | null",
                    "resolved": "any",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "the value of the radio."
                },
                "getter": false,
                "setter": false,
                "reflect": false
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
                    "text": "Where to place the label relative to the radio.\n`\"start\"`: The label will appear to the left of the radio in LTR and to the right in RTL.\n`\"end\"`: The label will appear to the right of the radio in LTR and to the left in RTL.\n`\"fixed\"`: The label has the same behavior as `\"start\"` except it also has a fixed width. Long text will be truncated with ellipses (\"...\").\n`\"stacked\"`: The label will appear above the radio regardless of the direction. The alignment of the label can be controlled with the `alignment` property."
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
                    "text": "How to pack the label and radio within a line.\n`\"start\"`: The label and radio will appear on the left in LTR and\non the right in RTL.\n`\"end\"`: The label and radio will appear on the right in LTR and\non the left in RTL.\n`\"space-between\"`: The label and radio will appear on opposite\nends of the line with space between the two elements.\nSetting this property will change the radio `display` to `block`."
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
                    "text": "How to control the alignment of the radio and label on the cross axis.\n`\"start\"`: The label and control will appear on the left of the cross axis in LTR, and on the right side in RTL.\n`\"center\"`: The label and control will appear at the center of the cross axis in both LTR and RTL.\nSetting this property will change the radio `display` to `block`."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            }
        };
    }
    static get states() {
        return {
            "checked": {},
            "buttonTabindex": {}
        };
    }
    static get events() {
        return [{
                "method": "ionFocus",
                "name": "ionFocus",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted when the radio button has focus."
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
                    "text": "Emitted when the radio button loses focus."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }];
    }
    static get methods() {
        return {
            "setFocus": {
                "complexType": {
                    "signature": "(ev?: globalThis.Event) => Promise<void>",
                    "parameters": [{
                            "name": "ev",
                            "type": "Event | undefined",
                            "docs": ""
                        }],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "globalThis": {
                            "location": "global",
                            "id": "global::globalThis"
                        }
                    },
                    "return": "Promise<void>"
                },
                "docs": {
                    "text": "",
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }]
                }
            },
            "setButtonTabindex": {
                "complexType": {
                    "signature": "(value: number) => Promise<void>",
                    "parameters": [{
                            "name": "value",
                            "type": "number",
                            "docs": ""
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
                    "text": "",
                    "tags": [{
                            "name": "internal",
                            "text": undefined
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
            }];
    }
}
let radioButtonIds = 0;
