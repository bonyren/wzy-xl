/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { focusFirstDescendant } from "../../utils/focus-trap";
import { CoreDelegate, attachComponent, detachComponent } from "../../utils/framework-delegate";
import { addEventListener, raf, hasLazyBuild } from "../../utils/helpers";
import { createLockController } from "../../utils/lock-controller";
import { printIonWarning } from "../../utils/logging/index";
import { BACKDROP, dismiss, eventMethod, prepareOverlay, present, setOverlayId, FOCUS_TRAP_DISABLE_CLASS, } from "../../utils/overlays";
import { isPlatform } from "../../utils/platform";
import { getClassMap } from "../../utils/theme";
import { deepReady, waitForMount } from "../../utils/transition/index";
import { getIonMode } from "../../global/ionic-global";
import { iosEnterAnimation } from "./animations/ios.enter";
import { iosLeaveAnimation } from "./animations/ios.leave";
import { mdEnterAnimation } from "./animations/md.enter";
import { mdLeaveAnimation } from "./animations/md.leave";
import { configureDismissInteraction, configureKeyboardInteraction, configureTriggerInteraction } from "./utils";
// TODO(FW-2832): types
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @slot - Content is placed inside of the `.popover-content` element.
 *
 * @part backdrop - The `ion-backdrop` element.
 * @part arrow - The arrow that points to the reference element. Only applies on `ios` mode.
 * @part content - The wrapper element for the default slot.
 */
export class Popover {
    constructor() {
        this.parentPopover = null;
        this.coreDelegate = CoreDelegate();
        this.lockController = createLockController();
        this.inline = false;
        this.focusDescendantOnPresent = false;
        this.presented = false;
        /** @internal */
        this.hasController = false;
        /**
         * If `true`, the keyboard will be automatically dismissed when the overlay is presented.
         */
        this.keyboardClose = true;
        /**
         * If `true`, the popover will be dismissed when the backdrop is clicked.
         */
        this.backdropDismiss = true;
        /**
         * If `true`, a backdrop will be displayed behind the popover.
         * This property controls whether or not the backdrop
         * darkens the screen when the popover is presented.
         * It does not control whether or not the backdrop
         * is active or present in the DOM.
         */
        this.showBackdrop = true;
        /**
         * If `true`, the popover will be translucent.
         * Only applies when the mode is `"ios"` and the device supports
         * [`backdrop-filter`](https://developer.mozilla.org/en-US/docs/Web/CSS/backdrop-filter#Browser_compatibility).
         */
        this.translucent = false;
        /**
         * If `true`, the popover will animate.
         */
        this.animated = true;
        /**
         * Describes what kind of interaction with the trigger that
         * should cause the popover to open. Does not apply when the `trigger`
         * property is `undefined`.
         * If `"click"`, the popover will be presented when the trigger is left clicked.
         * If `"hover"`, the popover will be presented when a pointer hovers over the trigger.
         * If `"context-menu"`, the popover will be presented when the trigger is right
         * clicked on desktop and long pressed on mobile. This will also prevent your
         * device's normal context menu from appearing.
         */
        this.triggerAction = 'click';
        /**
         * Describes how to calculate the popover width.
         * If `"cover"`, the popover width will match the width of the trigger.
         * If `"auto"`, the popover width will be set to a static default value.
         */
        this.size = 'auto';
        /**
         * If `true`, the popover will be automatically
         * dismissed when the content has been clicked.
         */
        this.dismissOnSelect = false;
        /**
         * Describes what to position the popover relative to.
         * If `"trigger"`, the popover will be positioned relative
         * to the trigger button. If passing in an event, this is
         * determined via event.target.
         * If `"event"`, the popover will be positioned relative
         * to the x/y coordinates of the trigger action. If passing
         * in an event, this is determined via event.clientX and event.clientY.
         */
        this.reference = 'trigger';
        /**
         * Describes which side of the `reference` point to position
         * the popover on. The `"start"` and `"end"` values are RTL-aware,
         * and the `"left"` and `"right"` values are not.
         */
        this.side = 'bottom';
        /**
         * If `true`, the popover will display an arrow that points at the
         * `reference` when running in `ios` mode. Does not apply in `md` mode.
         */
        this.arrow = true;
        /**
         * If `true`, the popover will open. If `false`, the popover will close.
         * Use this if you need finer grained control over presentation, otherwise
         * just use the popoverController or the `trigger` property.
         * Note: `isOpen` will not automatically be set back to `false` when
         * the popover dismisses. You will need to do that in your code.
         */
        this.isOpen = false;
        /**
         * @internal
         *
         * If `true` the popover will not register its own keyboard event handlers.
         * This allows the contents of the popover to handle their own keyboard interactions.
         *
         * If `false`, the popover will register its own keyboard event handlers for
         * navigating `ion-list` items within a popover (up/down/home/end/etc.).
         * This will also cancel browser keyboard event bindings to prevent scroll
         * behavior in a popover using a list of items.
         */
        this.keyboardEvents = false;
        /**
         * If `true`, focus will not be allowed to move outside of this overlay.
         * If `false`, focus will be allowed to move outside of the overlay.
         *
         * In most scenarios this property should remain set to `true`. Setting
         * this property to `false` can cause severe accessibility issues as users
         * relying on assistive technologies may be able to move focus into
         * a confusing state. We recommend only setting this to `false` when
         * absolutely necessary.
         *
         * Developers may want to consider disabling focus trapping if this
         * overlay presents a non-Ionic overlay from a 3rd party library.
         * Developers would disable focus trapping on the Ionic overlay
         * when presenting the 3rd party overlay and then re-enable
         * focus trapping when dismissing the 3rd party overlay and moving
         * focus back to the Ionic overlay.
         */
        this.focusTrap = true;
        /**
         * If `true`, the component passed into `ion-popover` will
         * automatically be mounted when the popover is created. The
         * component will remain mounted even when the popover is dismissed.
         * However, the component will be destroyed when the popover is
         * destroyed. This property is not reactive and should only be
         * used when initially creating a popover.
         *
         * Note: This feature only applies to inline popovers in JavaScript
         * frameworks such as Angular, React, and Vue.
         */
        this.keepContentsMounted = false;
        this.onBackdropTap = () => {
            this.dismiss(undefined, BACKDROP);
        };
        this.onLifecycle = (modalEvent) => {
            const el = this.usersElement;
            const name = LIFECYCLE_MAP[modalEvent.type];
            if (el && name) {
                const event = new CustomEvent(name, {
                    bubbles: false,
                    cancelable: false,
                    detail: modalEvent.detail,
                });
                el.dispatchEvent(event);
            }
        };
        this.configureTriggerInteraction = () => {
            const { trigger, triggerAction, el, destroyTriggerInteraction } = this;
            if (destroyTriggerInteraction) {
                destroyTriggerInteraction();
            }
            if (trigger === undefined) {
                return;
            }
            const triggerEl = (this.triggerEl = trigger !== undefined ? document.getElementById(trigger) : null);
            if (!triggerEl) {
                printIonWarning(`[ion-popover] - A trigger element with the ID "${trigger}" was not found in the DOM. The trigger element must be in the DOM when the "trigger" property is set on ion-popover.`, this.el);
                return;
            }
            this.destroyTriggerInteraction = configureTriggerInteraction(triggerEl, triggerAction, el);
        };
        this.configureKeyboardInteraction = () => {
            const { destroyKeyboardInteraction, el } = this;
            if (destroyKeyboardInteraction) {
                destroyKeyboardInteraction();
            }
            this.destroyKeyboardInteraction = configureKeyboardInteraction(el);
        };
        this.configureDismissInteraction = () => {
            const { destroyDismissInteraction, parentPopover, triggerAction, triggerEl, el } = this;
            if (!parentPopover || !triggerEl) {
                return;
            }
            if (destroyDismissInteraction) {
                destroyDismissInteraction();
            }
            this.destroyDismissInteraction = configureDismissInteraction(triggerEl, triggerAction, el, parentPopover);
        };
    }
    onTriggerChange() {
        this.configureTriggerInteraction();
    }
    onIsOpenChange(newValue, oldValue) {
        if (newValue === true && oldValue === false) {
            this.present();
        }
        else if (newValue === false && oldValue === true) {
            this.dismiss();
        }
    }
    connectedCallback() {
        const { configureTriggerInteraction, el } = this;
        prepareOverlay(el);
        configureTriggerInteraction();
    }
    disconnectedCallback() {
        const { destroyTriggerInteraction } = this;
        if (destroyTriggerInteraction) {
            destroyTriggerInteraction();
        }
    }
    componentWillLoad() {
        var _a, _b;
        const { el } = this;
        const popoverId = (_b = (_a = this.htmlAttributes) === null || _a === void 0 ? void 0 : _a.id) !== null && _b !== void 0 ? _b : setOverlayId(el);
        this.parentPopover = el.closest(`ion-popover:not(#${popoverId})`);
        if (this.alignment === undefined) {
            this.alignment = getIonMode(this) === 'ios' ? 'center' : 'start';
        }
    }
    componentDidLoad() {
        const { parentPopover, isOpen } = this;
        /**
         * If popover was rendered with isOpen="true"
         * then we should open popover immediately.
         */
        if (isOpen === true) {
            raf(() => this.present());
        }
        if (parentPopover) {
            addEventListener(parentPopover, 'ionPopoverWillDismiss', () => {
                this.dismiss(undefined, undefined, false);
            });
        }
        /**
         * When binding values in frameworks such as Angular
         * it is possible for the value to be set after the Web Component
         * initializes but before the value watcher is set up in Stencil.
         * As a result, the watcher callback may not be fired.
         * We work around this by manually calling the watcher
         * callback when the component has loaded and the watcher
         * is configured.
         */
        this.configureTriggerInteraction();
    }
    /**
     * When opening a popover from a trigger, we should not be
     * modifying the `event` prop from inside the component.
     * Additionally, when pressing the "Right" arrow key, we need
     * to shift focus to the first descendant in the newly presented
     * popover.
     *
     * @internal
     */
    async presentFromTrigger(event, focusDescendant = false) {
        this.focusDescendantOnPresent = focusDescendant;
        await this.present(event);
        this.focusDescendantOnPresent = false;
    }
    /**
     * Determines whether or not an overlay
     * is being used inline or via a controller/JS
     * and returns the correct delegate.
     * By default, subsequent calls to getDelegate
     * will use a cached version of the delegate.
     * This is useful for calling dismiss after
     * present so that the correct delegate is given.
     */
    getDelegate(force = false) {
        if (this.workingDelegate && !force) {
            return {
                delegate: this.workingDelegate,
                inline: this.inline,
            };
        }
        /**
         * If using overlay inline
         * we potentially need to use the coreDelegate
         * so that this works in vanilla JS apps.
         * If a developer has presented this component
         * via a controller, then we can assume
         * the component is already in the
         * correct place.
         */
        const parentEl = this.el.parentNode;
        const inline = (this.inline = parentEl !== null && !this.hasController);
        const delegate = (this.workingDelegate = inline ? this.delegate || this.coreDelegate : this.delegate);
        return { inline, delegate };
    }
    /**
     * Present the popover overlay after it has been created.
     * Developers can pass a mouse, touch, or pointer event
     * to position the popover relative to where that event
     * was dispatched.
     *
     * @param event The event to position the popover relative to.
     */
    async present(event) {
        const unlock = await this.lockController.lock();
        if (this.presented) {
            unlock();
            return;
        }
        const { el } = this;
        const { inline, delegate } = this.getDelegate(true);
        /**
         * Emit ionMount so JS Frameworks have an opportunity
         * to add the child component to the DOM. The child
         * component will be assigned to this.usersElement below.
         */
        this.ionMount.emit();
        this.usersElement = await attachComponent(delegate, el, this.component, ['popover-viewport'], this.componentProps, inline);
        if (!this.keyboardEvents) {
            this.configureKeyboardInteraction();
        }
        this.configureDismissInteraction();
        /**
         * When using the lazy loaded build of Stencil, we need to wait
         * for every Stencil component instance to be ready before presenting
         * otherwise there can be a flash of unstyled content. With the
         * custom elements bundle we need to wait for the JS framework
         * mount the inner contents of the overlay otherwise WebKit may
         * get the transition incorrect.
         */
        if (hasLazyBuild(el)) {
            await deepReady(this.usersElement);
            /**
             * If keepContentsMounted="true" then the
             * JS Framework has already mounted the inner
             * contents so there is no need to wait.
             * Otherwise, we need to wait for the JS
             * Framework to mount the inner contents
             * of this component.
             */
        }
        else if (!this.keepContentsMounted) {
            await waitForMount();
        }
        await present(this, 'popoverEnter', iosEnterAnimation, mdEnterAnimation, {
            event: event || this.event,
            size: this.size,
            trigger: this.triggerEl,
            reference: this.reference,
            side: this.side,
            align: this.alignment,
        });
        /**
         * If popover is nested and was
         * presented using the "Right" arrow key,
         * we need to move focus to the first
         * descendant inside of the popover.
         */
        if (this.focusDescendantOnPresent) {
            focusFirstDescendant(el);
        }
        unlock();
    }
    /**
     * Dismiss the popover overlay after it has been presented.
     * This is a no-op if the overlay has not been presented yet. If you want
     * to remove an overlay from the DOM that was never presented, use the
     * [remove](https://developer.mozilla.org/en-US/docs/Web/API/Element/remove) method.
     *
     * @param data Any data to emit in the dismiss events.
     * @param role The role of the element that is dismissing the popover. For example, `cancel` or `backdrop`.
     * @param dismissParentPopover If `true`, dismissing this popover will also dismiss
     * a parent popover if this popover is nested. Defaults to `true`.
     */
    async dismiss(data, role, dismissParentPopover = true) {
        const unlock = await this.lockController.lock();
        const { destroyKeyboardInteraction, destroyDismissInteraction } = this;
        if (dismissParentPopover && this.parentPopover) {
            this.parentPopover.dismiss(data, role, dismissParentPopover);
        }
        const shouldDismiss = await dismiss(this, data, role, 'popoverLeave', iosLeaveAnimation, mdLeaveAnimation, this.event);
        if (shouldDismiss) {
            if (destroyKeyboardInteraction) {
                destroyKeyboardInteraction();
                this.destroyKeyboardInteraction = undefined;
            }
            if (destroyDismissInteraction) {
                destroyDismissInteraction();
                this.destroyDismissInteraction = undefined;
            }
            /**
             * If using popover inline
             * we potentially need to use the coreDelegate
             * so that this works in vanilla JS apps
             */
            const { delegate } = this.getDelegate();
            await detachComponent(delegate, this.usersElement);
        }
        unlock();
        return shouldDismiss;
    }
    /**
     * @internal
     */
    async getParentPopover() {
        return this.parentPopover;
    }
    /**
     * Returns a promise that resolves when the popover did dismiss.
     */
    onDidDismiss() {
        return eventMethod(this.el, 'ionPopoverDidDismiss');
    }
    /**
     * Returns a promise that resolves when the popover will dismiss.
     */
    onWillDismiss() {
        return eventMethod(this.el, 'ionPopoverWillDismiss');
    }
    render() {
        const mode = getIonMode(this);
        const { onLifecycle, parentPopover, dismissOnSelect, side, arrow, htmlAttributes, focusTrap } = this;
        const desktop = isPlatform('desktop');
        const enableArrow = arrow && !parentPopover;
        return (h(Host, Object.assign({ key: '16866c02534968c982cf4730d2936d03a5107c8b', "aria-modal": "true", "no-router": true, tabindex: "-1" }, htmlAttributes, { style: {
                zIndex: `${20000 + this.overlayIndex}`,
            }, class: Object.assign(Object.assign({}, getClassMap(this.cssClass)), { [mode]: true, 'popover-translucent': this.translucent, 'overlay-hidden': true, 'popover-desktop': desktop, [`popover-side-${side}`]: true, [FOCUS_TRAP_DISABLE_CLASS]: focusTrap === false, 'popover-nested': !!parentPopover }), onIonPopoverDidPresent: onLifecycle, onIonPopoverWillPresent: onLifecycle, onIonPopoverWillDismiss: onLifecycle, onIonPopoverDidDismiss: onLifecycle, onIonBackdropTap: this.onBackdropTap }), !parentPopover && h("ion-backdrop", { key: '0df258601a4d30df3c27aa8234a7d5e056c3ecbb', tappable: this.backdropDismiss, visible: this.showBackdrop, part: "backdrop" }), h("div", { key: 'f94e80ed996b957b5cd09b826472b4f60e8fcc78', class: "popover-wrapper ion-overlay-wrapper", onClick: dismissOnSelect ? () => this.dismiss() : undefined }, enableArrow && h("div", { key: '185ce22f6386e8444a9cc7b8818dbfc16c463c99', class: "popover-arrow", part: "arrow" }), h("div", { key: '206202b299404e110de5397b229678cca18568d3', class: "popover-content", part: "content" }, h("slot", { key: 'ee543a0b92d6e35a837c0a0e4617c7b0fc4ad0b0' })))));
    }
    static get is() { return "ion-popover"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "ios": ["popover.ios.scss"],
            "md": ["popover.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["popover.ios.css"],
            "md": ["popover.md.css"]
        };
    }
    static get properties() {
        return {
            "hasController": {
                "type": "boolean",
                "attribute": "has-controller",
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
            "delegate": {
                "type": "unknown",
                "attribute": "delegate",
                "mutable": false,
                "complexType": {
                    "original": "FrameworkDelegate",
                    "resolved": "FrameworkDelegate | undefined",
                    "references": {
                        "FrameworkDelegate": {
                            "location": "import",
                            "path": "../../interface",
                            "id": "src/interface.d.ts::FrameworkDelegate"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": ""
                },
                "getter": false,
                "setter": false
            },
            "overlayIndex": {
                "type": "number",
                "attribute": "overlay-index",
                "mutable": false,
                "complexType": {
                    "original": "number",
                    "resolved": "number",
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
            "enterAnimation": {
                "type": "unknown",
                "attribute": "enter-animation",
                "mutable": false,
                "complexType": {
                    "original": "AnimationBuilder",
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
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Animation to use when the popover is presented."
                },
                "getter": false,
                "setter": false
            },
            "leaveAnimation": {
                "type": "unknown",
                "attribute": "leave-animation",
                "mutable": false,
                "complexType": {
                    "original": "AnimationBuilder",
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
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Animation to use when the popover is dismissed."
                },
                "getter": false,
                "setter": false
            },
            "component": {
                "type": "string",
                "attribute": "component",
                "mutable": false,
                "complexType": {
                    "original": "ComponentRef",
                    "resolved": "Function | HTMLElement | null | string | undefined",
                    "references": {
                        "ComponentRef": {
                            "location": "import",
                            "path": "../../interface",
                            "id": "src/interface.d.ts::ComponentRef"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The component to display inside of the popover.\nYou only need to use this if you are not using\na JavaScript framework. Otherwise, you can just\nslot your component inside of `ion-popover`."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "componentProps": {
                "type": "unknown",
                "attribute": "component-props",
                "mutable": false,
                "complexType": {
                    "original": "ComponentProps",
                    "resolved": "undefined | { [key: string]: any; }",
                    "references": {
                        "ComponentProps": {
                            "location": "import",
                            "path": "../../interface",
                            "id": "src/interface.d.ts::ComponentProps"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The data to pass to the popover component.\nYou only need to use this if you are not using\na JavaScript framework. Otherwise, you can just\nset the props directly on your component."
                },
                "getter": false,
                "setter": false
            },
            "keyboardClose": {
                "type": "boolean",
                "attribute": "keyboard-close",
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
                    "text": "If `true`, the keyboard will be automatically dismissed when the overlay is presented."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "true"
            },
            "cssClass": {
                "type": "string",
                "attribute": "css-class",
                "mutable": false,
                "complexType": {
                    "original": "string | string[]",
                    "resolved": "string | string[] | undefined",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": "Additional classes to apply for custom CSS. If multiple classes are\nprovided they should be separated by spaces."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "backdropDismiss": {
                "type": "boolean",
                "attribute": "backdrop-dismiss",
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
                    "text": "If `true`, the popover will be dismissed when the backdrop is clicked."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "true"
            },
            "event": {
                "type": "any",
                "attribute": "event",
                "mutable": false,
                "complexType": {
                    "original": "any",
                    "resolved": "any",
                    "references": {}
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "The event to pass to the popover animation."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "showBackdrop": {
                "type": "boolean",
                "attribute": "show-backdrop",
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
                    "text": "If `true`, a backdrop will be displayed behind the popover.\nThis property controls whether or not the backdrop\ndarkens the screen when the popover is presented.\nIt does not control whether or not the backdrop\nis active or present in the DOM."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "true"
            },
            "translucent": {
                "type": "boolean",
                "attribute": "translucent",
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
                    "text": "If `true`, the popover will be translucent.\nOnly applies when the mode is `\"ios\"` and the device supports\n[`backdrop-filter`](https://developer.mozilla.org/en-US/docs/Web/CSS/backdrop-filter#Browser_compatibility)."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "animated": {
                "type": "boolean",
                "attribute": "animated",
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
                    "text": "If `true`, the popover will animate."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "true"
            },
            "htmlAttributes": {
                "type": "unknown",
                "attribute": "html-attributes",
                "mutable": false,
                "complexType": {
                    "original": "{ [key: string]: any }",
                    "resolved": "undefined | { [key: string]: any; }",
                    "references": {}
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Additional attributes to pass to the popover."
                },
                "getter": false,
                "setter": false
            },
            "triggerAction": {
                "type": "string",
                "attribute": "trigger-action",
                "mutable": false,
                "complexType": {
                    "original": "TriggerAction",
                    "resolved": "\"click\" | \"context-menu\" | \"hover\"",
                    "references": {
                        "TriggerAction": {
                            "location": "import",
                            "path": "./popover-interface",
                            "id": "src/components/popover/popover-interface.ts::TriggerAction"
                        }
                    }
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Describes what kind of interaction with the trigger that\nshould cause the popover to open. Does not apply when the `trigger`\nproperty is `undefined`.\nIf `\"click\"`, the popover will be presented when the trigger is left clicked.\nIf `\"hover\"`, the popover will be presented when a pointer hovers over the trigger.\nIf `\"context-menu\"`, the popover will be presented when the trigger is right\nclicked on desktop and long pressed on mobile. This will also prevent your\ndevice's normal context menu from appearing."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'click'"
            },
            "trigger": {
                "type": "string",
                "attribute": "trigger",
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
                    "text": "An ID corresponding to the trigger element that\ncauses the popover to open. Use the `trigger-action`\nproperty to customize the interaction that results in\nthe popover opening."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "size": {
                "type": "string",
                "attribute": "size",
                "mutable": false,
                "complexType": {
                    "original": "PopoverSize",
                    "resolved": "\"auto\" | \"cover\"",
                    "references": {
                        "PopoverSize": {
                            "location": "import",
                            "path": "./popover-interface",
                            "id": "src/components/popover/popover-interface.ts::PopoverSize"
                        }
                    }
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Describes how to calculate the popover width.\nIf `\"cover\"`, the popover width will match the width of the trigger.\nIf `\"auto\"`, the popover width will be set to a static default value."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'auto'"
            },
            "dismissOnSelect": {
                "type": "boolean",
                "attribute": "dismiss-on-select",
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
                    "text": "If `true`, the popover will be automatically\ndismissed when the content has been clicked."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "reference": {
                "type": "string",
                "attribute": "reference",
                "mutable": false,
                "complexType": {
                    "original": "PositionReference",
                    "resolved": "\"event\" | \"trigger\"",
                    "references": {
                        "PositionReference": {
                            "location": "import",
                            "path": "./popover-interface",
                            "id": "src/components/popover/popover-interface.ts::PositionReference"
                        }
                    }
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Describes what to position the popover relative to.\nIf `\"trigger\"`, the popover will be positioned relative\nto the trigger button. If passing in an event, this is\ndetermined via event.target.\nIf `\"event\"`, the popover will be positioned relative\nto the x/y coordinates of the trigger action. If passing\nin an event, this is determined via event.clientX and event.clientY."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'trigger'"
            },
            "side": {
                "type": "string",
                "attribute": "side",
                "mutable": false,
                "complexType": {
                    "original": "PositionSide",
                    "resolved": "\"bottom\" | \"end\" | \"left\" | \"right\" | \"start\" | \"top\"",
                    "references": {
                        "PositionSide": {
                            "location": "import",
                            "path": "./popover-interface",
                            "id": "src/components/popover/popover-interface.ts::PositionSide"
                        }
                    }
                },
                "required": false,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "Describes which side of the `reference` point to position\nthe popover on. The `\"start\"` and `\"end\"` values are RTL-aware,\nand the `\"left\"` and `\"right\"` values are not."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "'bottom'"
            },
            "alignment": {
                "type": "string",
                "attribute": "alignment",
                "mutable": true,
                "complexType": {
                    "original": "PositionAlign",
                    "resolved": "\"center\" | \"end\" | \"start\" | undefined",
                    "references": {
                        "PositionAlign": {
                            "location": "import",
                            "path": "./popover-interface",
                            "id": "src/components/popover/popover-interface.ts::PositionAlign"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "Describes how to align the popover content with the `reference` point.\nDefaults to `\"center\"` for `ios` mode, and `\"start\"` for `md` mode."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "arrow": {
                "type": "boolean",
                "attribute": "arrow",
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
                    "text": "If `true`, the popover will display an arrow that points at the\n`reference` when running in `ios` mode. Does not apply in `md` mode."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "true"
            },
            "isOpen": {
                "type": "boolean",
                "attribute": "is-open",
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
                    "text": "If `true`, the popover will open. If `false`, the popover will close.\nUse this if you need finer grained control over presentation, otherwise\njust use the popoverController or the `trigger` property.\nNote: `isOpen` will not automatically be set back to `false` when\nthe popover dismisses. You will need to do that in your code."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "keyboardEvents": {
                "type": "boolean",
                "attribute": "keyboard-events",
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
                            "text": "If `true` the popover will not register its own keyboard event handlers.\nThis allows the contents of the popover to handle their own keyboard interactions.\n\nIf `false`, the popover will register its own keyboard event handlers for\nnavigating `ion-list` items within a popover (up/down/home/end/etc.).\nThis will also cancel browser keyboard event bindings to prevent scroll\nbehavior in a popover using a list of items."
                        }],
                    "text": ""
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            },
            "focusTrap": {
                "type": "boolean",
                "attribute": "focus-trap",
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
                    "text": "If `true`, focus will not be allowed to move outside of this overlay.\nIf `false`, focus will be allowed to move outside of the overlay.\n\nIn most scenarios this property should remain set to `true`. Setting\nthis property to `false` can cause severe accessibility issues as users\nrelying on assistive technologies may be able to move focus into\na confusing state. We recommend only setting this to `false` when\nabsolutely necessary.\n\nDevelopers may want to consider disabling focus trapping if this\noverlay presents a non-Ionic overlay from a 3rd party library.\nDevelopers would disable focus trapping on the Ionic overlay\nwhen presenting the 3rd party overlay and then re-enable\nfocus trapping when dismissing the 3rd party overlay and moving\nfocus back to the Ionic overlay."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "true"
            },
            "keepContentsMounted": {
                "type": "boolean",
                "attribute": "keep-contents-mounted",
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
                    "text": "If `true`, the component passed into `ion-popover` will\nautomatically be mounted when the popover is created. The\ncomponent will remain mounted even when the popover is dismissed.\nHowever, the component will be destroyed when the popover is\ndestroyed. This property is not reactive and should only be\nused when initially creating a popover.\n\nNote: This feature only applies to inline popovers in JavaScript\nframeworks such as Angular, React, and Vue."
                },
                "getter": false,
                "setter": false,
                "reflect": false,
                "defaultValue": "false"
            }
        };
    }
    static get states() {
        return {
            "presented": {}
        };
    }
    static get events() {
        return [{
                "method": "didPresent",
                "name": "ionPopoverDidPresent",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted after the popover has presented."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }, {
                "method": "willPresent",
                "name": "ionPopoverWillPresent",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted before the popover has presented."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }, {
                "method": "willDismiss",
                "name": "ionPopoverWillDismiss",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted before the popover has dismissed."
                },
                "complexType": {
                    "original": "OverlayEventDetail",
                    "resolved": "OverlayEventDetail<any>",
                    "references": {
                        "OverlayEventDetail": {
                            "location": "import",
                            "path": "../../utils/overlays-interface",
                            "id": "src/utils/overlays-interface.ts::OverlayEventDetail"
                        }
                    }
                }
            }, {
                "method": "didDismiss",
                "name": "ionPopoverDidDismiss",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted after the popover has dismissed."
                },
                "complexType": {
                    "original": "OverlayEventDetail",
                    "resolved": "OverlayEventDetail<any>",
                    "references": {
                        "OverlayEventDetail": {
                            "location": "import",
                            "path": "../../utils/overlays-interface",
                            "id": "src/utils/overlays-interface.ts::OverlayEventDetail"
                        }
                    }
                }
            }, {
                "method": "didPresentShorthand",
                "name": "didPresent",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted after the popover has presented.\nShorthand for ionPopoverWillDismiss."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }, {
                "method": "willPresentShorthand",
                "name": "willPresent",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted before the popover has presented.\nShorthand for ionPopoverWillPresent."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }, {
                "method": "willDismissShorthand",
                "name": "willDismiss",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted before the popover has dismissed.\nShorthand for ionPopoverWillDismiss."
                },
                "complexType": {
                    "original": "OverlayEventDetail",
                    "resolved": "OverlayEventDetail<any>",
                    "references": {
                        "OverlayEventDetail": {
                            "location": "import",
                            "path": "../../utils/overlays-interface",
                            "id": "src/utils/overlays-interface.ts::OverlayEventDetail"
                        }
                    }
                }
            }, {
                "method": "didDismissShorthand",
                "name": "didDismiss",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted after the popover has dismissed.\nShorthand for ionPopoverDidDismiss."
                },
                "complexType": {
                    "original": "OverlayEventDetail",
                    "resolved": "OverlayEventDetail<any>",
                    "references": {
                        "OverlayEventDetail": {
                            "location": "import",
                            "path": "../../utils/overlays-interface",
                            "id": "src/utils/overlays-interface.ts::OverlayEventDetail"
                        }
                    }
                }
            }, {
                "method": "ionMount",
                "name": "ionMount",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": "Emitted before the popover has presented, but after the component\nhas been mounted in the DOM.\nThis event exists for ion-popover to resolve an issue with the\npopover and the lazy build, that the transition is unable to get\nthe correct dimensions of the popover with auto sizing.\nThis is not required for other overlays, since the existing\noverlay transitions are not effected by auto sizing content."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }];
    }
    static get methods() {
        return {
            "presentFromTrigger": {
                "complexType": {
                    "signature": "(event?: any, focusDescendant?: boolean) => Promise<void>",
                    "parameters": [{
                            "name": "event",
                            "type": "any",
                            "docs": ""
                        }, {
                            "name": "focusDescendant",
                            "type": "boolean",
                            "docs": ""
                        }],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        }
                    },
                    "return": "Promise<void>"
                },
                "docs": {
                    "text": "When opening a popover from a trigger, we should not be\nmodifying the `event` prop from inside the component.\nAdditionally, when pressing the \"Right\" arrow key, we need\nto shift focus to the first descendant in the newly presented\npopover.",
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }]
                }
            },
            "present": {
                "complexType": {
                    "signature": "(event?: MouseEvent | TouchEvent | PointerEvent | CustomEvent) => Promise<void>",
                    "parameters": [{
                            "name": "event",
                            "type": "MouseEvent | PointerEvent | TouchEvent | CustomEvent<any> | undefined",
                            "docs": "The event to position the popover relative to."
                        }],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "MouseEvent": {
                            "location": "global",
                            "id": "global::MouseEvent"
                        },
                        "TouchEvent": {
                            "location": "global",
                            "id": "global::TouchEvent"
                        },
                        "PointerEvent": {
                            "location": "global",
                            "id": "global::PointerEvent"
                        },
                        "CustomEvent": {
                            "location": "global",
                            "id": "global::CustomEvent"
                        },
                        "PopoverPresentOptions": {
                            "location": "global",
                            "id": "global::PopoverPresentOptions"
                        }
                    },
                    "return": "Promise<void>"
                },
                "docs": {
                    "text": "Present the popover overlay after it has been created.\nDevelopers can pass a mouse, touch, or pointer event\nto position the popover relative to where that event\nwas dispatched.",
                    "tags": [{
                            "name": "param",
                            "text": "event The event to position the popover relative to."
                        }]
                }
            },
            "dismiss": {
                "complexType": {
                    "signature": "(data?: any, role?: string, dismissParentPopover?: boolean) => Promise<boolean>",
                    "parameters": [{
                            "name": "data",
                            "type": "any",
                            "docs": "Any data to emit in the dismiss events."
                        }, {
                            "name": "role",
                            "type": "string | undefined",
                            "docs": "The role of the element that is dismissing the popover. For example, `cancel` or `backdrop`."
                        }, {
                            "name": "dismissParentPopover",
                            "type": "boolean",
                            "docs": "If `true`, dismissing this popover will also dismiss\na parent popover if this popover is nested. Defaults to `true`."
                        }],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "PopoverDismissOptions": {
                            "location": "global",
                            "id": "global::PopoverDismissOptions"
                        }
                    },
                    "return": "Promise<boolean>"
                },
                "docs": {
                    "text": "Dismiss the popover overlay after it has been presented.\nThis is a no-op if the overlay has not been presented yet. If you want\nto remove an overlay from the DOM that was never presented, use the\n[remove](https://developer.mozilla.org/en-US/docs/Web/API/Element/remove) method.",
                    "tags": [{
                            "name": "param",
                            "text": "data Any data to emit in the dismiss events."
                        }, {
                            "name": "param",
                            "text": "role The role of the element that is dismissing the popover. For example, `cancel` or `backdrop`."
                        }, {
                            "name": "param",
                            "text": "dismissParentPopover If `true`, dismissing this popover will also dismiss\na parent popover if this popover is nested. Defaults to `true`."
                        }]
                }
            },
            "getParentPopover": {
                "complexType": {
                    "signature": "() => Promise<HTMLIonPopoverElement | null>",
                    "parameters": [],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "HTMLIonPopoverElement": {
                            "location": "global",
                            "id": "global::HTMLIonPopoverElement"
                        }
                    },
                    "return": "Promise<HTMLIonPopoverElement | null>"
                },
                "docs": {
                    "text": "",
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }]
                }
            },
            "onDidDismiss": {
                "complexType": {
                    "signature": "<T = any>() => Promise<OverlayEventDetail<T>>",
                    "parameters": [],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "OverlayEventDetail": {
                            "location": "import",
                            "path": "../../utils/overlays-interface",
                            "id": "src/utils/overlays-interface.ts::OverlayEventDetail"
                        },
                        "T": {
                            "location": "global",
                            "id": "global::T"
                        }
                    },
                    "return": "Promise<OverlayEventDetail<T>>"
                },
                "docs": {
                    "text": "Returns a promise that resolves when the popover did dismiss.",
                    "tags": []
                }
            },
            "onWillDismiss": {
                "complexType": {
                    "signature": "<T = any>() => Promise<OverlayEventDetail<T>>",
                    "parameters": [],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "OverlayEventDetail": {
                            "location": "import",
                            "path": "../../utils/overlays-interface",
                            "id": "src/utils/overlays-interface.ts::OverlayEventDetail"
                        },
                        "T": {
                            "location": "global",
                            "id": "global::T"
                        }
                    },
                    "return": "Promise<OverlayEventDetail<T>>"
                },
                "docs": {
                    "text": "Returns a promise that resolves when the popover will dismiss.",
                    "tags": []
                }
            }
        };
    }
    static get elementRef() { return "el"; }
    static get watchers() {
        return [{
                "propName": "trigger",
                "methodName": "onTriggerChange"
            }, {
                "propName": "triggerAction",
                "methodName": "onTriggerChange"
            }, {
                "propName": "isOpen",
                "methodName": "onIsOpenChange"
            }];
    }
}
const LIFECYCLE_MAP = {
    ionPopoverDidPresent: 'ionViewDidEnter',
    ionPopoverWillPresent: 'ionViewWillEnter',
    ionPopoverWillDismiss: 'ionViewWillLeave',
    ionPopoverDidDismiss: 'ionViewDidLeave',
};
