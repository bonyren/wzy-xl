/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, forceUpdate, h } from "@stencil/core";
import { inheritAriaAttributes, hasShadowDom } from "../../utils/helpers";
import { printIonWarning } from "../../utils/logging/index";
import { createColorClasses, hostContext, openURL } from "../../utils/theme";
import { getIonMode } from "../../global/ionic-global";
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @slot - Content is placed between the named slots if provided without a slot.
 * @slot icon-only - Should be used on an icon in a button that has no text.
 * @slot start - Content is placed to the left of the button text in LTR, and to the right in RTL.
 * @slot end - Content is placed to the right of the button text in LTR, and to the left in RTL.
 *
 * @part native - The native HTML button or anchor element that wraps all child elements.
 */
export class Button {
    constructor() {
        this.inItem = false;
        this.inListHeader = false;
        this.inToolbar = false;
        this.formButtonEl = null;
        this.formEl = null;
        this.inheritedAttributes = {};
        /**
         * If `true`, the button only has an icon.
         */
        this.isCircle = false;
        /**
         * The type of button.
         */
        this.buttonType = 'button';
        /**
         * If `true`, the user cannot interact with the button.
         */
        this.disabled = false;
        /**
         * When using a router, it specifies the transition direction when navigating to
         * another page using `href`.
         */
        this.routerDirection = 'forward';
        /**
         * If `true`, activates a button with a heavier font weight.
         */
        this.strong = false;
        /**
         * The type of the button.
         */
        this.type = 'button';
        this.handleClick = (ev) => {
            const { el } = this;
            if (this.type === 'button') {
                openURL(this.href, ev, this.routerDirection, this.routerAnimation);
            }
            else if (hasShadowDom(el)) {
                this.submitForm(ev);
            }
        };
        this.onFocus = () => {
            this.ionFocus.emit();
        };
        this.onBlur = () => {
            this.ionBlur.emit();
        };
        this.slotChanged = () => {
            /**
             * Ensures that the 'has-icon-only' class is properly added
             * or removed from `ion-button` when manipulating the
             * `icon-only` slot.
             *
             * Without this, the 'has-icon-only' class is only checked
             * or added when `ion-button` component first renders.
             */
            this.isCircle = this.hasIconOnly;
        };
    }
    disabledChanged() {
        const { disabled } = this;
        if (this.formButtonEl) {
            this.formButtonEl.disabled = disabled;
        }
    }
    /**
     * This component is used within the `ion-input-password-toggle` component
     * to toggle the visibility of the password input.
     * These attributes need to update based on the state of the password input.
     * Otherwise, the values will be stale.
     *
     * @param newValue
     * @param _oldValue
     * @param propName
     */
    onAriaChanged(newValue, _oldValue, propName) {
        this.inheritedAttributes = Object.assign(Object.assign({}, this.inheritedAttributes), { [propName]: newValue });
        forceUpdate(this);
    }
    /**
     * This is responsible for rendering a hidden native
     * button element inside the associated form. This allows
     * users to submit a form by pressing "Enter" when a text
     * field inside of the form is focused. The native button
     * rendered inside of `ion-button` is in the Shadow DOM
     * and therefore does not participate in form submission
     * which is why the following code is necessary.
     */
    renderHiddenButton() {
        const formEl = (this.formEl = this.findForm());
        if (formEl) {
            const { formButtonEl } = this;
            /**
             * If the form already has a rendered form button
             * then do not append a new one again.
             */
            if (formButtonEl !== null && formEl.contains(formButtonEl)) {
                return;
            }
            // Create a hidden native button inside of the form
            const newFormButtonEl = (this.formButtonEl = document.createElement('button'));
            newFormButtonEl.type = this.type;
            newFormButtonEl.style.display = 'none';
            // Only submit if the button is not disabled.
            newFormButtonEl.disabled = this.disabled;
            formEl.appendChild(newFormButtonEl);
        }
    }
    componentWillLoad() {
        this.inToolbar = !!this.el.closest('ion-buttons');
        this.inListHeader = !!this.el.closest('ion-list-header');
        this.inItem = !!this.el.closest('ion-item') || !!this.el.closest('ion-item-divider');
        this.inheritedAttributes = inheritAriaAttributes(this.el);
    }
    get hasIconOnly() {
        return !!this.el.querySelector('[slot="icon-only"]');
    }
    get rippleType() {
        const hasClearFill = this.fill === undefined || this.fill === 'clear';
        // If the button is in a toolbar, has a clear fill (which is the default)
        // and only has an icon we use the unbounded "circular" ripple effect
        if (hasClearFill && this.hasIconOnly && this.inToolbar) {
            return 'unbounded';
        }
        return 'bounded';
    }
    /**
     * Finds the form element based on the provided `form` selector
     * or element reference provided.
     */
    findForm() {
        const { form } = this;
        if (form instanceof HTMLFormElement) {
            return form;
        }
        if (typeof form === 'string') {
            // Check if the string provided is a form id.
            const el = document.getElementById(form);
            if (el) {
                if (el instanceof HTMLFormElement) {
                    return el;
                }
                else {
                    /**
                     * The developer specified a string for the form attribute, but the
                     * element with that id is not a form element.
                     */
                    printIonWarning(`[ion-button] - Form with selector: "#${form}" could not be found. Verify that the id is attached to a <form> element.`, this.el);
                    return null;
                }
            }
            else {
                /**
                 * The developer specified a string for the form attribute, but the
                 * element with that id could not be found in the DOM.
                 */
                printIonWarning(`[ion-button] - Form with selector: "#${form}" could not be found. Verify that the id is correct and the form is rendered in the DOM.`, this.el);
                return null;
            }
        }
        if (form !== undefined) {
            /**
             * The developer specified a HTMLElement for the form attribute,
             * but the element is not a HTMLFormElement.
             * This will also catch if the developer tries to pass in null
             * as the form attribute.
             */
            printIonWarning(`[ion-button] - The provided "form" element is invalid. Verify that the form is a HTMLFormElement and rendered in the DOM.`, this.el);
            return null;
        }
        /**
         * If the form element is not set, the button may be inside
         * of a form element. Query the closest form element to the button.
         */
        return this.el.closest('form');
    }
    submitForm(ev) {
        // this button wants to specifically submit a form
        // climb up the dom to see if we're in a <form>
        // and if so, then use JS to submit it
        if (this.formEl && this.formButtonEl) {
            ev.preventDefault();
            this.formButtonEl.click();
        }
    }
    render() {
        const mode = getIonMode(this);
        const { buttonType, type, disabled, rel, target, size, href, color, expand, hasIconOnly, shape, strong, inheritedAttributes, } = this;
        const finalSize = size === undefined && this.inItem ? 'small' : size;
        const TagType = href === undefined ? 'button' : 'a';
        const attrs = TagType === 'button'
            ? { type }
            : {
                download: this.download,
                href,
                rel,
                target,
            };
        let fill = this.fill;
        /**
         * We check both undefined and null to
         * work around https://github.com/ionic-team/stencil/issues/3586.
         */
        if (fill == null) {
            fill = this.inToolbar || this.inListHeader ? 'clear' : 'solid';
        }
        /**
         * We call renderHiddenButton in the render function to account
         * for any properties being set async. For example, changing the
         * "type" prop from "button" to "submit" after the component has
         * loaded would warrant the hidden button being added to the
         * associated form.
         */
        {
            type !== 'button' && this.renderHiddenButton();
        }
        return (h(Host, { key: 'b105ad09215adb3ca2298acdadf0dc9154bbb9b0', onClick: this.handleClick, "aria-disabled": disabled ? 'true' : null, class: createColorClasses(color, {
                [mode]: true,
                [buttonType]: true,
                [`${buttonType}-${expand}`]: expand !== undefined,
                [`${buttonType}-${finalSize}`]: finalSize !== undefined,
                [`${buttonType}-${shape}`]: shape !== undefined,
                [`${buttonType}-${fill}`]: true,
                [`${buttonType}-strong`]: strong,
                'in-toolbar': hostContext('ion-toolbar', this.el),
                'in-toolbar-color': hostContext('ion-toolbar[color]', this.el),
                'in-buttons': hostContext('ion-buttons', this.el),
                'button-has-icon-only': hasIconOnly,
                'button-disabled': disabled,
                'ion-activatable': true,
                'ion-focusable': true,
            }) }, h(TagType, Object.assign({ key: '66b4e7112bcb9e41d5a723fbbadb0a3104f9ee1d' }, attrs, { class: "button-native", part: "native", disabled: disabled, onFocus: this.onFocus, onBlur: this.onBlur }, inheritedAttributes), h("span", { key: '1439fc3da280221028dcf7ce8ec9dab273c4d4bb', class: "button-inner" }, h("slot", { key: 'd5269ae1afc87ec7b99746032f59cbae93720a9f', name: "icon-only", onSlotchange: this.slotChanged }), h("slot", { key: '461c83e97aa246aa86d83e14f1e15a288d35041e', name: "start" }), h("slot", { key: '807170d47101f9f6a333dd4ff489c89284f306fe' }), h("slot", { key: 'e67f116dd0349a0d27893e4f3ff0ccef1d402f80', name: "end" })), mode === 'md' && h("ion-ripple-effect", { key: '273f0bd9645a36c1bfd18a5c2ab4f81e22b7b989', type: this.rippleType }))));
    }
    static get is() { return "ion-button"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["button.ios.scss"],
            "md": ["button.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["button.ios.css"],
            "md": ["button.md.css"]
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
            "buttonType": {
                "type": "string",
                "attribute": "button-type",
                "mutable": true,
                "complexType": {
                    "original": "string",
                    "resolved": "string",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The type of button."
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
                    "text": "If `true`, the user cannot interact with the button."
                },
                "getter": false,
                "setter": false,
                "reflect": true,
                "defaultValue": "false"
            },
            "expand": {
                "type": "string",
                "attribute": "expand",
                "mutable": false,
                "complexType": {
                    "original": "'full' | 'block'",
                    "resolved": "\"block\" | \"full\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Set to `\"block\"` for a full-width button or to `\"full\"` for a full-width button\nwith square corners and no left or right borders."
                },
                "getter": false,
                "setter": false,
                "reflect": true
            },
            "fill": {
                "type": "string",
                "attribute": "fill",
                "mutable": true,
                "complexType": {
                    "original": "'clear' | 'outline' | 'solid' | 'default'",
                    "resolved": "\"clear\" | \"default\" | \"outline\" | \"solid\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Set to `\"clear\"` for a transparent button that resembles a flat button, to `\"outline\"`\nfor a transparent button with a border, or to `\"solid\"` for a button with a filled background.\nThe default fill is `\"solid\"` except inside of a toolbar, where the default is `\"clear\"`."
                },
                "getter": false,
                "setter": false,
                "reflect": true
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
            "shape": {
                "type": "string",
                "attribute": "shape",
                "mutable": false,
                "complexType": {
                    "original": "'round'",
                    "resolved": "\"round\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Set to `\"round\"` for a button with more rounded corners."
                },
                "getter": false,
                "setter": false,
                "reflect": true
            },
            "size": {
                "type": "string",
                "attribute": "size",
                "mutable": false,
                "complexType": {
                    "original": "'small' | 'default' | 'large'",
                    "resolved": "\"default\" | \"large\" | \"small\" | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Set to `\"small\"` for a button with less height and padding, to `\"default\"`\nfor a button with the default height and padding, or to `\"large\"` for a button\nwith more height and padding. By default the size is unset, unless the button\nis inside of an item, where the size is `\"small\"` by default. Set the size to\n`\"default\"` inside of an item to make it a standard size button."
                },
                "getter": false,
                "setter": false,
                "reflect": true
            },
            "strong": {
                "type": "boolean",
                "attribute": "strong",
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
                    "text": "If `true`, activates a button with a heavier font weight."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
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
            },
            "form": {
                "type": "string",
                "attribute": "form",
                "mutable": false,
                "complexType": {
                    "original": "string | HTMLFormElement",
                    "resolved": "HTMLFormElement | string | undefined",
                    "references": {
                        "HTMLFormElement": {
                            "location": "global",
                            "id": "global::HTMLFormElement"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The HTML form element or form element id. Used to submit a form when the button is not a child of the form."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            }
        };
    }
    static get states() {
        return {
            "isCircle": {}
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
                    "text": "Emitted when the button has focus."
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
                    "text": "Emitted when the button loses focus."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }];
    }
    static get elementRef() { return "el"; }
    static get watchers() {
        return [{
                "propName": "disabled",
                "methodName": "disabledChanged"
            }, {
                "propName": "aria-checked",
                "methodName": "onAriaChanged"
            }, {
                "propName": "aria-label",
                "methodName": "onAriaChanged"
            }];
    }
}
