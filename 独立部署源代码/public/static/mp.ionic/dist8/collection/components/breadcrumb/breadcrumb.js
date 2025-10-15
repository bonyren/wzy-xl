/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { inheritAriaAttributes } from "../../utils/helpers";
import { createColorClasses, hostContext, openURL } from "../../utils/theme";
import { chevronForwardOutline, ellipsisHorizontal } from "ionicons/icons";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @part native - The native HTML anchor or div element that wraps all child elements.
 * @part separator - The separator element between each breadcrumb.
 * @part collapsed-indicator - The indicator element that shows the breadcrumbs are collapsed.
 */
export class Breadcrumb {
    constructor() {
        this.inheritedAttributes = {};
        /** @internal */
        this.collapsed = false;
        /**
         * If `true`, the breadcrumb will take on a different look to show that
         * it is the currently active breadcrumb. Defaults to `true` for the
         * last breadcrumb if it is not set on any.
         */
        this.active = false;
        /**
         * If `true`, the user cannot interact with the breadcrumb.
         */
        this.disabled = false;
        /**
         * When using a router, it specifies the transition direction when navigating to
         * another page using `href`.
         */
        this.routerDirection = 'forward';
        this.onFocus = () => {
            this.ionFocus.emit();
        };
        this.onBlur = () => {
            this.ionBlur.emit();
        };
        this.collapsedIndicatorClick = () => {
            this.collapsedClick.emit({ ionShadowTarget: this.collapsedRef });
        };
    }
    componentWillLoad() {
        this.inheritedAttributes = inheritAriaAttributes(this.el);
    }
    isClickable() {
        return this.href !== undefined;
    }
    render() {
        const { color, active, collapsed, disabled, download, el, inheritedAttributes, last, routerAnimation, routerDirection, separator, showCollapsedIndicator, target, } = this;
        const clickable = this.isClickable();
        const TagType = this.href === undefined ? 'span' : 'a';
        // Links can still be tabbed to when set to disabled if they have an href
        // in order to truly disable them we can keep it as an anchor but remove the href
        const href = disabled ? undefined : this.href;
        const mode = getIonMode(this);
        const attrs = TagType === 'span'
            ? {}
            : {
                download,
                href,
                target,
            };
        // If the breadcrumb is collapsed, check if it contains the collapsed indicator
        // to show the separator as long as it isn't also the last breadcrumb
        // otherwise if not collapsed use the value in separator
        const showSeparator = last ? false : collapsed ? (showCollapsedIndicator && !last ? true : false) : separator;
        return (h(Host, { key: '32ca61c83721dff52b5e97171ed449dce3584a55', onClick: (ev) => openURL(href, ev, routerDirection, routerAnimation), "aria-disabled": disabled ? 'true' : null, class: createColorClasses(color, {
                [mode]: true,
                'breadcrumb-active': active,
                'breadcrumb-collapsed': collapsed,
                'breadcrumb-disabled': disabled,
                'in-breadcrumbs-color': hostContext('ion-breadcrumbs[color]', el),
                'in-toolbar': hostContext('ion-toolbar', this.el),
                'in-toolbar-color': hostContext('ion-toolbar[color]', this.el),
                'ion-activatable': clickable,
                'ion-focusable': clickable,
            }) }, h(TagType, Object.assign({ key: '479feb845f4a6d8009d5422b33eb423730b9722b' }, attrs, { class: "breadcrumb-native", part: "native", disabled: disabled, onFocus: this.onFocus, onBlur: this.onBlur }, inheritedAttributes), h("slot", { key: '3c5dcaeb0d258235d1b7707868026ff1d1404099', name: "start" }), h("slot", { key: 'f1cfb934443cd97dc220882c5e3596ea879d66cf' }), h("slot", { key: '539710121b5b1f3ee8d4c24a9651b67c2ae08add', name: "end" })), showCollapsedIndicator && (h("button", { key: 'ed53a95ccd89022c8b7bee0658a221ec62a5c73b', part: "collapsed-indicator", "aria-label": "Show more breadcrumbs", onClick: () => this.collapsedIndicatorClick(), ref: (collapsedEl) => (this.collapsedRef = collapsedEl), class: {
                'breadcrumbs-collapsed-indicator': true,
            } }, h("ion-icon", { key: 'a849e1142a86f06f207cf11662fa2a560ab7fc6a', "aria-hidden": "true", icon: ellipsisHorizontal, lazy: false }))), showSeparator && (
        /**
         * Separators should not be announced by narrators.
         * We add aria-hidden on the span so that this applies
         * to any custom separators too.
         */
        h("span", { key: 'fc3c741cb01fafef8b26046c7ee5b190efc69a7c', class: "breadcrumb-separator", part: "separator", "aria-hidden": "true" }, h("slot", { key: '4871932ae1dae520767e0713e7cee2d11b0bba6d', name: "separator" }, mode === 'ios' ? (h("ion-icon", { icon: chevronForwardOutline, lazy: false, "flip-rtl": true })) : (h("span", null, "/")))))));
    }
    static get is() { return "ion-breadcrumb"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["breadcrumb.ios.scss"],
            "md": ["breadcrumb.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["breadcrumb.ios.css"],
            "md": ["breadcrumb.md.css"]
        };
    }
    static get properties() {
        return {
            "collapsed": {
                "type": "boolean",
                "attribute": "collapsed",
                "mutable": false,
                "complexType": {
                    "original": "boolean",
                    "resolved": "boolean",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": ""
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "last": {
                "type": "boolean",
                "attribute": "last",
                "mutable": false,
                "complexType": {
                    "original": "boolean",
                    "resolved": "boolean",
                    "references": {}
                },
                "required": true,
                "optional": false,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": ""
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "showCollapsedIndicator": {
                "type": "boolean",
                "attribute": "show-collapsed-indicator",
                "mutable": false,
                "complexType": {
                    "original": "boolean",
                    "resolved": "boolean",
                    "references": {}
                },
                "required": true,
                "optional": false,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": ""
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
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
                "reflect": false
            },
            "active": {
                "type": "boolean",
                "attribute": "active",
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
                    "text": "If `true`, the breadcrumb will take on a different look to show that\nit is the currently active breadcrumb. Defaults to `true` for the\nlast breadcrumb if it is not set on any."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
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
                    "text": "If `true`, the user cannot interact with the breadcrumb."
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
            "separator": {
                "type": "boolean",
                "attribute": "separator",
                "mutable": false,
                "complexType": {
                    "original": "boolean | undefined",
                    "resolved": "boolean | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "If true, show a separator between this breadcrumb and the next.\nDefaults to `true` for all breadcrumbs except the last."
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
            }
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
                    "text": "Emitted when the breadcrumb has focus."
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
                    "text": "Emitted when the breadcrumb loses focus."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }, {
                "method": "collapsedClick",
                "name": "collapsedClick",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": "Emitted when the collapsed indicator is clicked on.\n`ion-breadcrumbs` will listen for this and emit ionCollapsedClick.\nNormally we could just emit this as `ionCollapsedClick`\nand let the event bubble to `ion-breadcrumbs`,\nbut if the event custom event is not set on `ion-breadcrumbs`,\nTypeScript will throw an error in user applications."
                },
                "complexType": {
                    "original": "BreadcrumbCollapsedClickEventDetail",
                    "resolved": "BreadcrumbCollapsedClickEventDetail",
                    "references": {
                        "BreadcrumbCollapsedClickEventDetail": {
                            "location": "import",
                            "path": "./breadcrumb-interface",
                            "id": "src/components/breadcrumb/breadcrumb-interface.ts::BreadcrumbCollapsedClickEventDetail"
                        }
                    }
                }
            }];
    }
    static get elementRef() { return "el"; }
}
