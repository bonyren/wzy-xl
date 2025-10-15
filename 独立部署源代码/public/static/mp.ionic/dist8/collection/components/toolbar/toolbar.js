/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, forceUpdate, h } from "@stencil/core";
import { createColorClasses, hostContext } from "../../utils/theme";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @slot - Content is placed between the named slots if provided without a slot.
 * @slot start - Content is placed to the left of the toolbar text in LTR, and to the right in RTL.
 * @slot secondary - Content is placed to the left of the toolbar text in `ios` mode, and directly to the right in `md` mode.
 * @slot primary - Content is placed to the right of the toolbar text in `ios` mode, and to the far right in `md` mode.
 * @slot end - Content is placed to the right of the toolbar text in LTR, and to the left in RTL.
 *
 * @part background - The background of the toolbar, covering the entire area behind the toolbar content.
 * @part container - The container that wraps all toolbar content, including the default slot and named slot content.
 * @part content - The container for the default slot, wrapping content provided without a named slot.
 */
export class Toolbar {
    constructor() {
        this.childrenStyles = new Map();
    }
    componentWillLoad() {
        const buttons = Array.from(this.el.querySelectorAll('ion-buttons'));
        const firstButtons = buttons.find((button) => {
            return button.slot === 'start';
        });
        if (firstButtons) {
            firstButtons.classList.add('buttons-first-slot');
        }
        const buttonsReversed = buttons.reverse();
        const lastButtons = buttonsReversed.find((button) => button.slot === 'end') ||
            buttonsReversed.find((button) => button.slot === 'primary') ||
            buttonsReversed.find((button) => button.slot === 'secondary');
        if (lastButtons) {
            lastButtons.classList.add('buttons-last-slot');
        }
    }
    childrenStyle(ev) {
        ev.stopPropagation();
        const tagName = ev.target.tagName;
        const updatedStyles = ev.detail;
        const newStyles = {};
        const childStyles = this.childrenStyles.get(tagName) || {};
        let hasStyleChange = false;
        Object.keys(updatedStyles).forEach((key) => {
            const childKey = `toolbar-${key}`;
            const newValue = updatedStyles[key];
            if (newValue !== childStyles[childKey]) {
                hasStyleChange = true;
            }
            if (newValue) {
                newStyles[childKey] = true;
            }
        });
        if (hasStyleChange) {
            this.childrenStyles.set(tagName, newStyles);
            forceUpdate(this);
        }
    }
    render() {
        const mode = getIonMode(this);
        const childStyles = {};
        this.childrenStyles.forEach((value) => {
            Object.assign(childStyles, value);
        });
        return (h(Host, { key: 'f6c4f669a6a61c5eac4cbb5ea0aa97c48ae5bd46', class: Object.assign(Object.assign({}, childStyles), createColorClasses(this.color, {
                [mode]: true,
                'in-toolbar': hostContext('ion-toolbar', this.el),
            })) }, h("div", { key: '9c81742ffa02de9ba7417025b077d05e67305074', class: "toolbar-background", part: "background" }), h("div", { key: '5fc96d166fa47894a062e41541a9beee38078a36', class: "toolbar-container", part: "container" }, h("slot", { key: 'b62c0d9d59a70176bdbf769aec6090d7a166853b', name: "start" }), h("slot", { key: 'd01d3cc2c50e5aaa49c345b209fe8dbdf3d48131', name: "secondary" }), h("div", { key: '3aaa3a2810aedd38c37eb616158ec7b9638528fc', class: "toolbar-content", part: "content" }, h("slot", { key: '357246690f8d5e1cc3ca369611d4845a79edf610' })), h("slot", { key: '06ed3cca4f7ebff4a54cd877dad3cc925ccf9f75', name: "primary" }), h("slot", { key: 'e453d43d14a26b0d72f41e1b81a554bab8ece811', name: "end" }))));
    }
    static get is() { return "ion-toolbar"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["toolbar.ios.scss"],
            "md": ["toolbar.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["toolbar.ios.css"],
            "md": ["toolbar.md.css"]
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
            }
        };
    }
    static get elementRef() { return "el"; }
    static get listeners() {
        return [{
                "name": "ionStyle",
                "method": "childrenStyle",
                "target": undefined,
                "capture": false,
                "passive": false
            }];
    }
}
