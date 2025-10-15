/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { inheritAttributes } from "../../utils/helpers";
import { createColorClasses, openURL } from "../../utils/theme";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @part native - The native HTML button, anchor, or div element that wraps all child elements.
 */
export class Card {
    constructor() {
        this.inheritedAriaAttributes = {};
        /**
         * If `true`, a button tag will be rendered and the card will be tappable.
         */
        this.button = false;
        /**
         * The type of the button. Only used when an `onclick` or `button` property is present.
         */
        this.type = 'button';
        /**
         * If `true`, the user cannot interact with the card.
         */
        this.disabled = false;
        /**
         * When using a router, it specifies the transition direction when navigating to
         * another page using `href`.
         */
        this.routerDirection = 'forward';
    }
    componentWillLoad() {
        this.inheritedAriaAttributes = inheritAttributes(this.el, ['aria-label']);
    }
    isClickable() {
        return this.href !== undefined || this.button;
    }
    renderCard(mode) {
        const clickable = this.isClickable();
        if (!clickable) {
            return [h("slot", null)];
        }
        const { href, routerAnimation, routerDirection, inheritedAriaAttributes } = this;
        const TagType = clickable ? (href === undefined ? 'button' : 'a') : 'div';
        const attrs = TagType === 'button'
            ? { type: this.type }
            : {
                download: this.download,
                href: this.href,
                rel: this.rel,
                target: this.target,
            };
        return (h(TagType, Object.assign({}, attrs, inheritedAriaAttributes, { class: "card-native", part: "native", disabled: this.disabled, onClick: (ev) => openURL(href, ev, routerDirection, routerAnimation) }), h("slot", null), clickable && mode === 'md' && h("ion-ripple-effect", null)));
    }
    render() {
        const mode = getIonMode(this);
        return (h(Host, { key: '85e9b30bd81e79a0c7ac75cb3664bdcf9e4afc4d', class: createColorClasses(this.color, {
                [mode]: true,
                'card-disabled': this.disabled,
                'ion-activatable': this.isClickable(),
            }) }, this.renderCard(mode)));
    }
    static get is() { return "ion-card"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["card.ios.scss"],
            "md": ["card.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["card.ios.css"],
            "md": ["card.md.css"]
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
            "button": {
                "type": "boolean",
                "attribute": "button",
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
                    "text": "If `true`, a button tag will be rendered and the card will be tappable."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
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
                    "text": "The type of the button. Only used when an `onclick` or `button` property is present."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'button'"
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
                    "text": "If `true`, the user cannot interact with the card."
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
            "routerDirection": {
                "type": "string",
                "attribute": "router-direction",
                "mutable": false,
                "complexType": {
                    "original": "RouterDirection",
                    "resolved": "\"back\" | \"forward\" | \"root\"",
                    "references": {
                        "RouterDirection": {
                            "location": "import",
                            "path": "../router/utils/interface",
                            "id": "src/components/router/utils/interface.ts::RouterDirection"
                        }
                    }
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "When using a router, it specifies the transition direction when navigating to\nanother page using `href`."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'forward'"
            },
            "routerAnimation": {
                "type": "unknown",
                "attribute": "router-animation",
                "mutable": false,
                "complexType": {
                    "original": "AnimationBuilder | undefined",
                    "resolved": "((baseEl: any, opts?: any) => Animation) | undefined",
                    "references": {
                        "AnimationBuilder": {
                            "location": "import",
                            "path": "../../interface",
                            "id": "src/interface.d.ts::AnimationBuilder"
                        }
                    }
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "When using a router, it specifies the transition animation when navigating to\nanother page using `href`."
                },
                "getter": false,
                "setter": false
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
            }
        };
    }
    static get elementRef() { return "el"; }
}
