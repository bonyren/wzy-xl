/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { createColorClasses } from "../../utils/theme";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @slot - Content is placed between the named slots if provided without a slot.
 * @slot start - Content is placed to the left of the divider text in LTR, and to the right in RTL.
 * @slot end - Content is placed to the right of the divider text in LTR, and to the left in RTL.
 */
export class ItemDivider {
    constructor() {
        /**
         * When it's set to `true`, the item-divider will stay visible when it reaches the top
         * of the viewport until the next `ion-item-divider` replaces it.
         *
         * This feature relies in `position:sticky`:
         * https://caniuse.com/#feat=css-sticky
         */
        this.sticky = false;
    }
    render() {
        const mode = getIonMode(this);
        return (h(Host, { key: '1523095ce4af3f2611512ff0948ead659959ee4a', class: createColorClasses(this.color, {
                [mode]: true,
                'item-divider-sticky': this.sticky,
                item: true,
            }) }, h("slot", { key: '39105d888e115416c3a3fe588da44b4c61f4e5fe', name: "start" }), h("div", { key: '67e16f1056bd39187f3629c1bb383b7abbda829b', class: "item-divider-inner" }, h("div", { key: 'b3a218fdcc7b9aeab6e0155340152d39fa0b6329', class: "item-divider-wrapper" }, h("slot", { key: '69d8587533b387869d34b075d02f61396858fc90' })), h("slot", { key: 'b91c654699b3b26d0012ea0c719c4a07d1fcfbaa', name: "end" }))));
    }
    static get is() { return "ion-item-divider"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["item-divider.ios.scss"],
            "md": ["item-divider.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["item-divider.ios.css"],
            "md": ["item-divider.md.css"]
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
            "sticky": {
                "type": "boolean",
                "attribute": "sticky",
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
                    "text": "When it's set to `true`, the item-divider will stay visible when it reaches the top\nof the viewport until the next `ion-item-divider` replaces it.\n\nThis feature relies in `position:sticky`:\nhttps://caniuse.com/#feat=css-sticky"
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            }
        };
    }
    static get elementRef() { return "el"; }
}
