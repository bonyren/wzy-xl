/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { menuController } from "../../utils/menu-controller/index";
import { getIonMode } from "../../global/ionic-global";
import { updateVisibility } from "./menu-toggle-util";
/**
 * @slot - Content is placed inside the toggle to act as the click target.
 */
export class MenuToggle {
    constructor() {
        this.visible = false;
        /**
         * Automatically hides the content when the corresponding menu is not active.
         *
         * By default, it's `true`. Change it to `false` in order to
         * keep `ion-menu-toggle` always visible regardless the state of the menu.
         */
        this.autoHide = true;
        this.onClick = () => {
            return menuController.toggle(this.menu);
        };
    }
    connectedCallback() {
        this.visibilityChanged();
    }
    async visibilityChanged() {
        this.visible = await updateVisibility(this.menu);
    }
    render() {
        const mode = getIonMode(this);
        const hidden = this.autoHide && !this.visible;
        return (h(Host, { key: 'cd567114769a30bd3871ed5d15bf42aed39956e1', onClick: this.onClick, "aria-hidden": hidden ? 'true' : null, class: {
                [mode]: true,
                'menu-toggle-hidden': hidden,
            } }, h("slot", { key: '773d4cff95ca75f23578b1e1dca53c9933f28a33' })));
    }
    static get is() { return "ion-menu-toggle"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "$": ["menu-toggle.scss"]
        };
    }
    static get styleUrls() {
        return {
            "$": ["menu-toggle.css"]
        };
    }
    static get properties() {
        return {
            "menu": {
                "type": "string",
                "attribute": "menu",
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
                    "text": "Optional property that maps to a Menu's `menuId` prop.\nCan also be `start` or `end` for the menu side.\nThis is used to find the correct menu to toggle.\n\nIf this property is not used, `ion-menu-toggle` will toggle the\nfirst menu that is active."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "autoHide": {
                "type": "boolean",
                "attribute": "auto-hide",
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
                    "text": "Automatically hides the content when the corresponding menu is not active.\n\nBy default, it's `true`. Change it to `false` in order to\nkeep `ion-menu-toggle` always visible regardless the state of the menu."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "true"
            }
        };
    }
    static get states() {
        return {
            "visible": {}
        };
    }
    static get listeners() {
        return [{
                "name": "ionMenuChange",
                "method": "visibilityChanged",
                "target": "body",
                "capture": false,
                "passive": false
            }, {
                "name": "ionSplitPaneVisible",
                "method": "visibilityChanged",
                "target": "body",
                "capture": false,
                "passive": false
            }];
    }
}
