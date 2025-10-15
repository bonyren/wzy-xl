/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { getIonMode } from "../../global/ionic-global";
export class FabList {
    constructor() {
        /**
         * If `true`, the fab list will show all fab buttons in the list.
         */
        this.activated = false;
        /**
         * The side the fab list will show on relative to the main fab button.
         */
        this.side = 'bottom';
    }
    activatedChanged(activated) {
        const fabs = Array.from(this.el.querySelectorAll('ion-fab-button'));
        // if showing the fabs add a timeout, else show immediately
        const timeout = activated ? 30 : 0;
        fabs.forEach((fab, i) => {
            setTimeout(() => (fab.show = activated), i * timeout);
        });
    }
    render() {
        const mode = getIonMode(this);
        return (h(Host, { key: '64b33366447f66c7f979cfac56307fbb1a6fac1c', class: {
                [mode]: true,
                'fab-list-active': this.activated,
                [`fab-list-side-${this.side}`]: true,
            } }, h("slot", { key: 'd9f474f7f20fd7e813db358fddc720534ca05bb6' })));
    }
    static get is() { return "ion-fab-list"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "$": ["fab-list.scss"]
        };
    }
    static get styleUrls() {
        return {
            "$": ["fab-list.css"]
        };
    }
    static get properties() {
        return {
            "activated": {
                "type": "boolean",
                "attribute": "activated",
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
                    "text": "If `true`, the fab list will show all fab buttons in the list."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "side": {
                "type": "string",
                "attribute": "side",
                "mutable": false,
                "complexType": {
                    "original": "'start' | 'end' | 'top' | 'bottom'",
                    "resolved": "\"bottom\" | \"end\" | \"start\" | \"top\"",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The side the fab list will show on relative to the main fab button."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'bottom'"
            }
        };
    }
    static get elementRef() { return "el"; }
    static get watchers() {
        return [{
                "propName": "activated",
                "methodName": "activatedChanged"
            }];
    }
}
