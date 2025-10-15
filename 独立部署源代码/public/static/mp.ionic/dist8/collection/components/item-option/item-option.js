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
 * @slot start - Content is placed to the left of the option text in LTR, and to the right in RTL.
 * @slot top - Content is placed above the option text.
 * @slot icon-only - Should be used on an icon in an option that has no text.
 * @slot bottom - Content is placed below the option text.
 * @slot end - Content is placed to the right of the option text in LTR, and to the left in RTL.
 *
 * @part native - The native HTML button or anchor element that wraps all child elements.
 */
export class ItemOption {
    constructor() {
        /**
         * If `true`, the user cannot interact with the item option.
         */
        this.disabled = false;
        /**
         * If `true`, the option will expand to take up the available width and cover any other options.
         */
        this.expandable = false;
        /**
         * The type of the button.
         */
        this.type = 'button';
        this.onClick = (ev) => {
            const el = ev.target.closest('ion-item-option');
            if (el) {
                ev.preventDefault();
            }
        };
    }
    render() {
        const { disabled, expandable, href } = this;
        const TagType = href === undefined ? 'button' : 'a';
        const mode = getIonMode(this);
        const attrs = TagType === 'button'
            ? { type: this.type }
            : {
                download: this.download,
                href: this.href,
                target: this.target,
            };
        return (h(Host, { key: '189a0040b97163b2336bf216baa71d584c5923a8', onClick: this.onClick, class: createColorClasses(this.color, {
                [mode]: true,
                'item-option-disabled': disabled,
                'item-option-expandable': expandable,
                'ion-activatable': true,
            }) }, h(TagType, Object.assign({ key: '5a7140eb99da5ec82fe2ea3ea134513130763399' }, attrs, { class: "button-native", part: "native", disabled: disabled }), h("span", { key: '9b8577e612706b43e575c9a20f2f9d35c0d1bcb1', class: "button-inner" }, h("slot", { key: '9acb82f04e4822bfaa363cc2c4d29d5c0fec0ad6', name: "top" }), h("div", { key: '66f5fb4fdd0c39f205574c602c793dcf109c7a17', class: "horizontal-wrapper" }, h("slot", { key: '3761a32bca7c6c41b7eb394045497cfde181a62a', name: "start" }), h("slot", { key: 'a96a568955cf6962883dc6771726d3d07462da00', name: "icon-only" }), h("slot", { key: 'af5dfe5eb41456b9359bafe3615b576617ed7b57' }), h("slot", { key: '00426958066ab7b949ff966fabad5cf8a0b54079', name: "end" })), h("slot", { key: 'ae66c8bd536a9f27865f49240980d7b4b831b229', name: "bottom" })), mode === 'md' && h("ion-ripple-effect", { key: '30df6c935ef8a3f28a6bc1f3bb162ca4f80aaf26' }))));
    }
    static get is() { return "ion-item-option"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["item-option.ios.scss"],
            "md": ["item-option.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["item-option.ios.css"],
            "md": ["item-option.md.css"]
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
                    "text": "If `true`, the user cannot interact with the item option."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "download": {
                "type": "string",
                "attribute": "download",
                "mutable": false,
                "complexType": {
                    "original": "string | undefined",
                    "resolved": "string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "This attribute instructs browsers to download a URL instead of navigating to\nit, so the user will be prompted to save it as a local file. If the attribute\nhas a value, it is used as the pre-filled file name in the Save prompt\n(the user can still change the file name if they want)."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "expandable": {
                "type": "boolean",
                "attribute": "expandable",
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
                    "text": "If `true`, the option will expand to take up the available width and cover any other options."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "href": {
                "type": "string",
                "attribute": "href",
                "mutable": false,
                "complexType": {
                    "original": "string | undefined",
                    "resolved": "string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Contains a URL or a URL fragment that the hyperlink points to.\nIf this property is set, an anchor tag will be rendered."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "rel": {
                "type": "string",
                "attribute": "rel",
                "mutable": false,
                "complexType": {
                    "original": "string | undefined",
                    "resolved": "string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Specifies the relationship of the target object to the link object.\nThe value is a space-separated list of [link types](https://developer.mozilla.org/en-US/docs/Web/HTML/Link_types)."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "target": {
                "type": "string",
                "attribute": "target",
                "mutable": false,
                "complexType": {
                    "original": "string | undefined",
                    "resolved": "string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Specifies where to display the linked URL.\nOnly applies when an `href` is provided.\nSpecial keywords: `\"_blank\"`, `\"_self\"`, `\"_parent\"`, `\"_top\"`."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "type": {
                "type": "string",
                "attribute": "type",
                "mutable": false,
                "complexType": {
                    "original": "'submit' | 'reset' | 'button'",
                    "resolved": "\"button\" | \"reset\" | \"submit\"",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The type of the button."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'button'"
            }
        };
    }
    static get elementRef() { return "el"; }
}
