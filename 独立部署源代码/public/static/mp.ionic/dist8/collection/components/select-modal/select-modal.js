/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { getIonMode } from "../../global/ionic-global";
import { Host, forceUpdate, h } from "@stencil/core";
import { safeCall } from "../../utils/overlays";
import { getClassMap } from "../../utils/theme";
export class SelectModal {
    constructor() {
        this.options = [];
    }
    closeModal() {
        const modal = this.el.closest('ion-modal');
        if (modal) {
            modal.dismiss();
        }
    }
    findOptionFromEvent(ev) {
        const { options } = this;
        return options.find((o) => o.value === ev.target.value);
    }
    getValues(ev) {
        const { multiple, options } = this;
        if (multiple) {
            // this is a modal with checkboxes (multiple value select)
            // return an array of all the checked values
            return options.filter((o) => o.checked).map((o) => o.value);
        }
        // this is a modal with radio buttons (single value select)
        // return the value that was clicked, otherwise undefined
        const option = ev ? this.findOptionFromEvent(ev) : null;
        return option ? option.value : undefined;
    }
    callOptionHandler(ev) {
        const option = this.findOptionFromEvent(ev);
        const values = this.getValues(ev);
        if (option === null || option === void 0 ? void 0 : option.handler) {
            safeCall(option.handler, values);
        }
    }
    setChecked(ev) {
        const { multiple } = this;
        const option = this.findOptionFromEvent(ev);
        // this is a modal with checkboxes (multiple value select)
        // we need to set the checked value for this option
        if (multiple && option) {
            option.checked = ev.detail.checked;
        }
    }
    renderRadioOptions() {
        const checked = this.options.filter((o) => o.checked).map((o) => o.value)[0];
        return (h("ion-radio-group", { value: checked, onIonChange: (ev) => this.callOptionHandler(ev) }, this.options.map((option) => (h("ion-item", { lines: "none", class: Object.assign({
                // TODO FW-4784
                'item-radio-checked': option.value === checked
            }, getClassMap(option.cssClass)) }, h("ion-radio", { value: option.value, disabled: option.disabled, justify: "start", labelPlacement: "end", onClick: () => this.closeModal(), onKeyUp: (ev) => {
                if (ev.key === ' ') {
                    /**
                     * Selecting a radio option with keyboard navigation,
                     * either through the Enter or Space keys, should
                     * dismiss the modal.
                     */
                    this.closeModal();
                }
            } }, option.text))))));
    }
    renderCheckboxOptions() {
        return this.options.map((option) => (h("ion-item", { class: Object.assign({
                // TODO FW-4784
                'item-checkbox-checked': option.checked
            }, getClassMap(option.cssClass)) }, h("ion-checkbox", { value: option.value, disabled: option.disabled, checked: option.checked, justify: "start", labelPlacement: "end", onIonChange: (ev) => {
                this.setChecked(ev);
                this.callOptionHandler(ev);
                // TODO FW-4784
                forceUpdate(this);
            } }, option.text))));
    }
    render() {
        return (h(Host, { key: 'b6c0dec240b2e41985b15fdf4e5a6d3a145c1567', class: getIonMode(this) }, h("ion-header", { key: 'cd177e85ee0f62a60a3a708342d6ab6eb19a44dc' }, h("ion-toolbar", { key: 'aee8222a5a4daa540ad202b2e4cac1ef93d9558c' }, this.header !== undefined && h("ion-title", { key: '5f8fecc764d97bf840d3d4cfddeeccd118ab4436' }, this.header), h("ion-buttons", { key: '919033950d7c2b0101f96a9c9698219de9f568ea', slot: "end" }, h("ion-button", { key: '34b571cab6dced4bde555a077a21e91800829931', onClick: () => this.closeModal() }, "Close")))), h("ion-content", { key: '3c9153d26ba7a5a03d3b20fcd628d0c3031661a7' }, h("ion-list", { key: 'e00b222c071bc97c82ad1bba4db95a8a5c43ed6d' }, this.multiple === true ? this.renderCheckboxOptions() : this.renderRadioOptions()))));
    }
    static get is() { return "ion-select-modal"; }
    static get encapsulation() { return "scoped"; }
    static get originalStyleUrls() {
        return {
            "ios": ["select-modal.ios.scss"],
            "md": ["select-modal.md.scss"],
            "ionic": ["select-modal.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["select-modal.ios.css"],
            "md": ["select-modal.md.css"],
            "ionic": ["select-modal.md.css"]
        };
    }
    static get properties() {
        return {
            "header": {
                "type": "string",
                "attribute": "header",
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
                    "text": ""
                },
                "getter": false,
                "setter": false,
                "reflect": false
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
                    "text": ""
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "options": {
                "type": "unknown",
                "attribute": "options",
                "mutable": false,
                "complexType": {
                    "original": "SelectModalOption[]",
                    "resolved": "SelectModalOption[]",
                    "references": {
                        "SelectModalOption": {
                            "location": "import",
                            "path": "./select-modal-interface",
                            "id": "src/components/select-modal/select-modal-interface.ts::SelectModalOption"
                        }
                    }
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": ""
                },
                "getter": false,
                "setter": false,
                "defaultValue": "[]"
            }
        };
    }
    static get elementRef() { return "el"; }
}
