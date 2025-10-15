/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { inheritAttributes } from "../../utils/helpers";
import { config } from "../../global/config";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @part native - The native HTML anchor element that wraps all child elements.
 */
export class TabButton {
    constructor() {
        this.inheritedAttributes = {};
        /**
         * If `true`, the user cannot interact with the tab button.
         */
        this.disabled = false;
        /**
         * The selected tab component
         */
        this.selected = false;
        this.onKeyUp = (ev) => {
            if (ev.key === 'Enter' || ev.key === ' ') {
                this.selectTab(ev);
            }
        };
        this.onClick = (ev) => {
            this.selectTab(ev);
        };
    }
    onTabBarChanged(ev) {
        const dispatchedFrom = ev.target;
        const parent = this.el.parentElement;
        if (ev.composedPath().includes(parent) || (dispatchedFrom === null || dispatchedFrom === void 0 ? void 0 : dispatchedFrom.contains(this.el))) {
            this.selected = this.tab === ev.detail.tab;
        }
    }
    componentWillLoad() {
        this.inheritedAttributes = Object.assign({}, inheritAttributes(this.el, ['aria-label']));
        if (this.layout === undefined) {
            this.layout = config.get('tabButtonLayout', 'icon-top');
        }
    }
    selectTab(ev) {
        if (this.tab !== undefined) {
            if (!this.disabled) {
                this.ionTabButtonClick.emit({
                    tab: this.tab,
                    href: this.href,
                    selected: this.selected,
                });
            }
            ev.preventDefault();
        }
    }
    get hasLabel() {
        return !!this.el.querySelector('ion-label');
    }
    get hasIcon() {
        return !!this.el.querySelector('ion-icon');
    }
    render() {
        const { disabled, hasIcon, hasLabel, href, rel, target, layout, selected, tab, inheritedAttributes } = this;
        const mode = getIonMode(this);
        const attrs = {
            download: this.download,
            href,
            rel,
            target,
        };
        return (h(Host, { key: 'ce9d29ced0c781d6b2fa62cd5feb801c11fc42e8', onClick: this.onClick, onKeyup: this.onKeyUp, id: tab !== undefined ? `tab-button-${tab}` : null, class: {
                [mode]: true,
                'tab-selected': selected,
                'tab-disabled': disabled,
                'tab-has-label': hasLabel,
                'tab-has-icon': hasIcon,
                'tab-has-label-only': hasLabel && !hasIcon,
                'tab-has-icon-only': hasIcon && !hasLabel,
                [`tab-layout-${layout}`]: true,
                'ion-activatable': true,
                'ion-selectable': true,
                'ion-focusable': true,
            } }, h("a", Object.assign({ key: '01cb0ed2e77c5c1a8abd48da1bb07ac1b305d0b6' }, attrs, { class: "button-native", part: "native", role: "tab", "aria-selected": selected ? 'true' : null, "aria-disabled": disabled ? 'true' : null, tabindex: disabled ? '-1' : undefined }, inheritedAttributes), h("span", { key: 'd0240c05f42217cfb186b86ff8a0c9cd70b9c8df', class: "button-inner" }, h("slot", { key: '0a20b84925037dbaa8bb4a495b813d3f7c2e58ac' })), mode === 'md' && h("ion-ripple-effect", { key: '4c92c27178cdac89d69cffef8d2c39c3644914e8', type: "unbounded" }))));
    }
    static get is() { return "ion-tab-button"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["tab-button.ios.scss"],
            "md": ["tab-button.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["tab-button.ios.css"],
            "md": ["tab-button.md.css"]
        };
    }
    static get properties() {
        return {
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
                    "text": "If `true`, the user cannot interact with the tab button."
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
            "layout": {
                "type": "string",
                "attribute": "layout",
                "mutable": true,
                "complexType": {
                    "original": "TabButtonLayout",
                    "resolved": "\"icon-bottom\" | \"icon-end\" | \"icon-hide\" | \"icon-start\" | \"icon-top\" | \"label-hide\" | undefined",
                    "references": {
                        "TabButtonLayout": {
                            "location": "import",
                            "path": "../tab-bar/tab-bar-interface",
                            "id": "src/components/tab-bar/tab-bar-interface.ts::TabButtonLayout"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Set the layout of the text and icon in the tab bar.\nIt defaults to `\"icon-top\"`."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "selected": {
                "type": "boolean",
                "attribute": "selected",
                "mutable": true,
                "complexType": {
                    "original": "boolean",
                    "resolved": "boolean",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The selected tab component"
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "tab": {
                "type": "string",
                "attribute": "tab",
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
                    "text": "A tab id must be provided for each `ion-tab`. It's used internally to reference\nthe selected tab or by the router to switch between them."
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
            }
        };
    }
    static get events() {
        return [{
                "method": "ionTabButtonClick",
                "name": "ionTabButtonClick",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": "Emitted when the tab bar is clicked"
                },
                "complexType": {
                    "original": "TabButtonClickEventDetail",
                    "resolved": "TabButtonClickEventDetail",
                    "references": {
                        "TabButtonClickEventDetail": {
                            "location": "import",
                            "path": "../tab-bar/tab-bar-interface",
                            "id": "src/components/tab-bar/tab-bar-interface.ts::TabButtonClickEventDetail"
                        }
                    }
                }
            }];
    }
    static get elementRef() { return "el"; }
    static get listeners() {
        return [{
                "name": "ionTabBarChanged",
                "method": "onTabBarChanged",
                "target": "window",
                "capture": false,
                "passive": false
            }];
    }
}
