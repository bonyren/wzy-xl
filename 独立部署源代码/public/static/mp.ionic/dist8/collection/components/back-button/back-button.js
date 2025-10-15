/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { inheritAriaAttributes } from "../../utils/helpers";
import { createColorClasses, hostContext, openURL } from "../../utils/theme";
import { arrowBackSharp, chevronBack } from "ionicons/icons";
import { config } from "../../global/config";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @part native - The native HTML button element that wraps all child elements.
 * @part icon - The back button icon (uses ion-icon).
 * @part text - The back button text.
 */
export class BackButton {
    constructor() {
        this.inheritedAttributes = {};
        /**
         * If `true`, the user cannot interact with the button.
         */
        this.disabled = false;
        /**
         * The type of the button.
         */
        this.type = 'button';
        this.onClick = async (ev) => {
            const nav = this.el.closest('ion-nav');
            ev.preventDefault();
            if (nav && (await nav.canGoBack())) {
                return nav.pop({ animationBuilder: this.routerAnimation, skipIfBusy: true });
            }
            return openURL(this.defaultHref, ev, 'back', this.routerAnimation);
        };
    }
    componentWillLoad() {
        this.inheritedAttributes = inheritAriaAttributes(this.el);
        if (this.defaultHref === undefined) {
            this.defaultHref = config.get('backButtonDefaultHref');
        }
    }
    get backButtonIcon() {
        const icon = this.icon;
        if (icon != null) {
            // icon is set on the component or by the config
            return icon;
        }
        if (getIonMode(this) === 'ios') {
            // default ios back button icon
            return config.get('backButtonIcon', chevronBack);
        }
        // default md back button icon
        return config.get('backButtonIcon', arrowBackSharp);
    }
    get backButtonText() {
        const defaultBackButtonText = getIonMode(this) === 'ios' ? 'Back' : null;
        return this.text != null ? this.text : config.get('backButtonText', defaultBackButtonText);
    }
    get hasIconOnly() {
        return this.backButtonIcon && !this.backButtonText;
    }
    get rippleType() {
        // If the button only has an icon we use the unbounded
        // "circular" ripple effect
        if (this.hasIconOnly) {
            return 'unbounded';
        }
        return 'bounded';
    }
    render() {
        const { color, defaultHref, disabled, type, hasIconOnly, backButtonIcon, backButtonText, icon, inheritedAttributes, } = this;
        const showBackButton = defaultHref !== undefined;
        const mode = getIonMode(this);
        const ariaLabel = inheritedAttributes['aria-label'] || backButtonText || 'back';
        return (h(Host, { key: '5466624a10f1ab56f5469e6dc07080303880f2fe', onClick: this.onClick, class: createColorClasses(color, {
                [mode]: true,
                button: true, // ion-buttons target .button
                'back-button-disabled': disabled,
                'back-button-has-icon-only': hasIconOnly,
                'in-toolbar': hostContext('ion-toolbar', this.el),
                'in-toolbar-color': hostContext('ion-toolbar[color]', this.el),
                'ion-activatable': true,
                'ion-focusable': true,
                'show-back-button': showBackButton,
            }) }, h("button", { key: '63bc75ef0ad7cc9fb79e58217a3314b20acd73e3', type: type, disabled: disabled, class: "button-native", part: "native", "aria-label": ariaLabel }, h("span", { key: '5d3eacbd11af2245c6e1151cab446a0d96559ad8', class: "button-inner" }, backButtonIcon && (h("ion-icon", { key: '6439af0ae463764174e7d3207f02267811df666d', part: "icon", icon: backButtonIcon, "aria-hidden": "true", lazy: false, "flip-rtl": icon === undefined })), backButtonText && (h("span", { key: '8ee89fb18dfdb5b75948a8b197ff4cdbc008742f', part: "text", "aria-hidden": "true", class: "button-text" }, backButtonText))), mode === 'md' && h("ion-ripple-effect", { key: '63803a884998bc73bea5afe0b2a0a14e3fa4d6bf', type: this.rippleType }))));
    }
    static get is() { return "ion-back-button"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["back-button.ios.scss"],
            "md": ["back-button.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["back-button.ios.css"],
            "md": ["back-button.md.css"]
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
            "defaultHref": {
                "type": "string",
                "attribute": "default-href",
                "mutable": true,
                "complexType": {
                    "original": "string",
                    "resolved": "string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The url to navigate back to by default when there is no history."
                },
                "getter": false,
                "setter": false,
                "reflect": false
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
                    "text": "If `true`, the user cannot interact with the button."
                },
                "getter": false,
                "setter": false,
                "reflect": true,
                "defaultValue": "false"
            },
            "icon": {
                "type": "string",
                "attribute": "icon",
                "mutable": false,
                "complexType": {
                    "original": "string | null",
                    "resolved": "null | string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The built-in named SVG icon name or the exact `src` of an SVG file\nto use for the back button."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "text": {
                "type": "string",
                "attribute": "text",
                "mutable": false,
                "complexType": {
                    "original": "string | null",
                    "resolved": "null | string | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The text to display in the back button."
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
                    "text": "When using a router, it specifies the transition animation when navigating to\nanother page."
                },
                "getter": false,
                "setter": false
            }
        };
    }
    static get elementRef() { return "el"; }
}
