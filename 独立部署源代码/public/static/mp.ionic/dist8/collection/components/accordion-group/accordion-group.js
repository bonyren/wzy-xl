/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { printIonWarning } from "../../utils/logging/index";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 */
export class AccordionGroup {
    constructor() {
        /**
         * If `true`, all accordions inside of the
         * accordion group will animate when expanding
         * or collapsing.
         */
        this.animated = true;
        /**
         * If `true`, the accordion group cannot be interacted with.
         */
        this.disabled = false;
        /**
         * If `true`, the accordion group cannot be interacted with,
         * but does not alter the opacity.
         */
        this.readonly = false;
        /**
         * Describes the expansion behavior for each accordion.
         * Possible values are `"compact"` and `"inset"`.
         * Defaults to `"compact"`.
         */
        this.expand = 'compact';
    }
    valueChanged() {
        const { value, multiple } = this;
        if (!multiple && Array.isArray(value)) {
            /**
             * We do some processing on the `value` array so
             * that it looks more like an array when logged to
             * the console.
             * Example given ['a', 'b']
             * Default toString() behavior: a,b
             * Custom behavior: ['a', 'b']
             */
            printIonWarning(`[ion-accordion-group] - An array of values was passed, but multiple is "false". This is incorrect usage and may result in unexpected behaviors. To dismiss this warning, pass a string to the "value" property when multiple="false".

  Value Passed: [${value.map((v) => `'${v}'`).join(', ')}]
`, this.el);
        }
        /**
         * Do not use `value` here as that will be
         * not account for the adjustment we make above.
         */
        this.ionValueChange.emit({ value: this.value });
    }
    async disabledChanged() {
        const { disabled } = this;
        const accordions = await this.getAccordions();
        for (const accordion of accordions) {
            accordion.disabled = disabled;
        }
    }
    async readonlyChanged() {
        const { readonly } = this;
        const accordions = await this.getAccordions();
        for (const accordion of accordions) {
            accordion.readonly = readonly;
        }
    }
    async onKeydown(ev) {
        const activeElement = document.activeElement;
        if (!activeElement) {
            return;
        }
        /**
         * Make sure focus is in the header, not the body, of the accordion. This ensures
         * that if there are any interactable elements in the body, their keyboard
         * interaction doesn't get stolen by the accordion. Example: using up/down keys
         * in ion-textarea.
         */
        const activeAccordionHeader = activeElement.closest('ion-accordion [slot="header"]');
        if (!activeAccordionHeader) {
            return;
        }
        const accordionEl = activeElement.tagName === 'ION-ACCORDION' ? activeElement : activeElement.closest('ion-accordion');
        if (!accordionEl) {
            return;
        }
        const closestGroup = accordionEl.closest('ion-accordion-group');
        if (closestGroup !== this.el) {
            return;
        }
        // If the active accordion is not in the current array of accordions, do not do anything
        const accordions = await this.getAccordions();
        const startingIndex = accordions.findIndex((a) => a === accordionEl);
        if (startingIndex === -1) {
            return;
        }
        let accordion;
        if (ev.key === 'ArrowDown') {
            accordion = this.findNextAccordion(accordions, startingIndex);
        }
        else if (ev.key === 'ArrowUp') {
            accordion = this.findPreviousAccordion(accordions, startingIndex);
        }
        else if (ev.key === 'Home') {
            accordion = accordions[0];
        }
        else if (ev.key === 'End') {
            accordion = accordions[accordions.length - 1];
        }
        if (accordion !== undefined && accordion !== activeElement) {
            accordion.focus();
        }
    }
    async componentDidLoad() {
        if (this.disabled) {
            this.disabledChanged();
        }
        if (this.readonly) {
            this.readonlyChanged();
        }
        /**
         * When binding values in frameworks such as Angular
         * it is possible for the value to be set after the Web Component
         * initializes but before the value watcher is set up in Stencil.
         * As a result, the watcher callback may not be fired.
         * We work around this by manually calling the watcher
         * callback when the component has loaded and the watcher
         * is configured.
         */
        this.valueChanged();
    }
    /**
     * Sets the value property and emits ionChange.
     * This should only be called when the user interacts
     * with the accordion and not for any update
     * to the value property. The exception is when
     * the app sets the value of a single-select
     * accordion group to an array.
     */
    setValue(accordionValue) {
        const value = (this.value = accordionValue);
        this.ionChange.emit({ value });
    }
    /**
     * This method is used to ensure that the value
     * of ion-accordion-group is being set in a valid
     * way. This method should only be called in
     * response to a user generated action.
     * @internal
     */
    async requestAccordionToggle(accordionValue, accordionExpand) {
        const { multiple, value, readonly, disabled } = this;
        if (readonly || disabled) {
            return;
        }
        if (accordionExpand) {
            /**
             * If group accepts multiple values
             * check to see if value is already in
             * in values array. If not, add it
             * to the array.
             */
            if (multiple) {
                const groupValue = value !== null && value !== void 0 ? value : [];
                const processedValue = Array.isArray(groupValue) ? groupValue : [groupValue];
                const valueExists = processedValue.find((v) => v === accordionValue);
                if (valueExists === undefined && accordionValue !== undefined) {
                    this.setValue([...processedValue, accordionValue]);
                }
            }
            else {
                this.setValue(accordionValue);
            }
        }
        else {
            /**
             * If collapsing accordion, either filter the value
             * out of the values array or unset the value.
             */
            if (multiple) {
                const groupValue = value !== null && value !== void 0 ? value : [];
                const processedValue = Array.isArray(groupValue) ? groupValue : [groupValue];
                this.setValue(processedValue.filter((v) => v !== accordionValue));
            }
            else {
                this.setValue(undefined);
            }
        }
    }
    findNextAccordion(accordions, startingIndex) {
        const nextAccordion = accordions[startingIndex + 1];
        if (nextAccordion === undefined) {
            return accordions[0];
        }
        return nextAccordion;
    }
    findPreviousAccordion(accordions, startingIndex) {
        const prevAccordion = accordions[startingIndex - 1];
        if (prevAccordion === undefined) {
            return accordions[accordions.length - 1];
        }
        return prevAccordion;
    }
    /**
     * @internal
     */
    async getAccordions() {
        return Array.from(this.el.querySelectorAll(':scope > ion-accordion'));
    }
    render() {
        const { disabled, readonly, expand } = this;
        const mode = getIonMode(this);
        return (h(Host, { key: 'd1a79a93179474fbba66fcf11a92f4871dacc975', class: {
                [mode]: true,
                'accordion-group-disabled': disabled,
                'accordion-group-readonly': readonly,
                [`accordion-group-expand-${expand}`]: true,
            }, role: "presentation" }, h("slot", { key: 'e6b8954b686d1fbb4fc92adb07fddc97a24b0a31' })));
    }
    static get is() { return "ion-accordion-group"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["accordion-group.ios.scss"],
            "md": ["accordion-group.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["accordion-group.ios.css"],
            "md": ["accordion-group.md.css"]
        };
    }
    static get properties() {
        return {
            "animated": {
                "type": "boolean",
                "attribute": "animated",
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
                    "text": "If `true`, all accordions inside of the\naccordion group will animate when expanding\nor collapsing."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "true"
            },
            "multiple": {
                "type": "boolean",
                "attribute": "multiple",
                "mutable": false,
                "complexType": {
                    "original": "boolean",
                    "resolved": "boolean | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "If `true`, the accordion group can have multiple\naccordion components expanded at the same time."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "value": {
                "type": "string",
                "attribute": "value",
                "mutable": true,
                "complexType": {
                    "original": "string | string[] | null",
                    "resolved": "null | string | string[] | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The value of the accordion group. This controls which\naccordions are expanded.\nThis should be an array of strings only when `multiple=\"true\"`"
                },
                "getter": false,
                "setter": false,
                "reflect": false
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
                    "text": "If `true`, the accordion group cannot be interacted with."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
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
                    "text": "If `true`, the accordion group cannot be interacted with,\nbut does not alter the opacity."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "expand": {
                "type": "string",
                "attribute": "expand",
                "mutable": false,
                "complexType": {
                    "original": "'compact' | 'inset'",
                    "resolved": "\"compact\" | \"inset\"",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Describes the expansion behavior for each accordion.\nPossible values are `\"compact\"` and `\"inset\"`.\nDefaults to `\"compact\"`."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'compact'"
            }
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
                    "text": "Emitted when the value property has changed as a result of a user action such as a click.\n\nThis event will not emit when programmatically setting the `value` property."
                },
                "complexType": {
                    "original": "AccordionGroupChangeEventDetail",
                    "resolved": "AccordionGroupChangeEventDetail<any>",
                    "references": {
                        "AccordionGroupChangeEventDetail": {
                            "location": "import",
                            "path": "./accordion-group-interface",
                            "id": "src/components/accordion-group/accordion-group-interface.ts::AccordionGroupChangeEventDetail"
                        }
                    }
                }
            }, {
                "method": "ionValueChange",
                "name": "ionValueChange",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": "Emitted when the value property has changed.\nThis is used to ensure that ion-accordion can respond\nto any value property changes."
                },
                "complexType": {
                    "original": "AccordionGroupChangeEventDetail",
                    "resolved": "AccordionGroupChangeEventDetail<any>",
                    "references": {
                        "AccordionGroupChangeEventDetail": {
                            "location": "import",
                            "path": "./accordion-group-interface",
                            "id": "src/components/accordion-group/accordion-group-interface.ts::AccordionGroupChangeEventDetail"
                        }
                    }
                }
            }];
    }
    static get methods() {
        return {
            "requestAccordionToggle": {
                "complexType": {
                    "signature": "(accordionValue: string | undefined, accordionExpand: boolean) => Promise<void>",
                    "parameters": [{
                            "name": "accordionValue",
                            "type": "string | undefined",
                            "docs": ""
                        }, {
                            "name": "accordionExpand",
                            "type": "boolean",
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
                    "text": "This method is used to ensure that the value\nof ion-accordion-group is being set in a valid\nway. This method should only be called in\nresponse to a user generated action.",
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }]
                }
            },
            "getAccordions": {
                "complexType": {
                    "signature": "() => Promise<HTMLIonAccordionElement[]>",
                    "parameters": [],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "HTMLIonAccordionElement": {
                            "location": "global",
                            "id": "global::HTMLIonAccordionElement"
                        }
                    },
                    "return": "Promise<HTMLIonAccordionElement[]>"
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
            }, {
                "propName": "disabled",
                "methodName": "disabledChanged"
            }, {
                "propName": "readonly",
                "methodName": "readonlyChanged"
            }];
    }
    static get listeners() {
        return [{
                "name": "keydown",
                "method": "onKeydown",
                "target": undefined,
                "capture": false,
                "passive": false
            }];
    }
}
