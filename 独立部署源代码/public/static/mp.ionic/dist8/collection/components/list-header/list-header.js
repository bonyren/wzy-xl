/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { createColorClasses } from "../../utils/theme";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 */
export class ListHeader {
    render() {
        const { lines } = this;
        const mode = getIonMode(this);
        return (h(Host, { key: 'd9bc827ad8cc77231efddc2435831a7fc080f77d', class: createColorClasses(this.color, {
                [mode]: true,
                [`list-header-lines-${lines}`]: lines !== undefined,
            }) }, h("div", { key: '02dd9698304a7b2997ea1487e2f308bebea2b44c', class: "list-header-inner" }, h("slot", { key: '01d63a572c003286ae467a1ab23631e37e695042' }))));
    }
    static get is() { return "ion-list-header"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["list-header.ios.scss"],
            "md": ["list-header.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["list-header.ios.css"],
            "md": ["list-header.md.css"]
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
            "lines": {
                "type": "string",
                "attribute": "lines",
                "mutable": false,
                "complexType": {
                    "original": "'full' | 'inset' | 'none'",
                    "resolved": "\"full\" | \"inset\" | \"none\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "How the bottom border should be displayed on the list header."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            }
        };
    }
}
