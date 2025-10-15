/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { r as registerInstance, i as forceUpdate, h, d as Host, g as getElement, c as createEvent, e as config } from './index-4DxY6_gG.js';
import { b as inheritAttributes, r as raf } from './helpers-8KSQQGQy.js';
import { h as hostContext, c as createColorClasses, o as openURL } from './theme-DiVJyqlX.js';
import { p as chevronForward } from './index-DV3sJJW8.js';
import { b as getIonMode } from './ionic-global-CTSyufhF.js';

const itemIosCss = ":host{--border-radius:0px;--border-width:0px;--border-style:solid;--padding-top:0px;--padding-bottom:0px;--padding-end:0px;--padding-start:0px;--inner-border-width:0px;--inner-padding-top:0px;--inner-padding-bottom:0px;--inner-padding-start:0px;--inner-padding-end:0px;--inner-box-shadow:none;--detail-icon-color:initial;--detail-icon-font-size:1.25em;--detail-icon-opacity:0.25;--color-activated:var(--color);--color-focused:var(--color);--color-hover:var(--color);--ripple-color:currentColor;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:block;position:relative;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;outline:none;color:var(--color);font-family:var(--ion-font-family, inherit);text-align:initial;text-decoration:none;overflow:hidden;-webkit-box-sizing:border-box;box-sizing:border-box}:host(.ion-color) .item-native{background:var(--ion-color-base);color:var(--ion-color-contrast)}:host(.ion-color) .item-native,:host(.ion-color) .item-inner{border-color:var(--ion-color-shade)}:host(.ion-activated) .item-native{color:var(--color-activated)}:host(.ion-activated) .item-native::after{background:var(--background-activated);opacity:var(--background-activated-opacity)}:host(.ion-color.ion-activated) .item-native{color:var(--ion-color-contrast)}:host(.ion-focused) .item-native{color:var(--color-focused)}:host(.ion-focused) .item-native::after{background:var(--background-focused);opacity:var(--background-focused-opacity)}:host(.ion-color.ion-focused) .item-native{color:var(--ion-color-contrast)}:host(.ion-color.ion-focused) .item-native::after{background:var(--ion-color-contrast)}@media (any-hover: hover){:host(.ion-activatable:not(.ion-focused):hover) .item-native{color:var(--color-hover)}:host(.ion-activatable:not(.ion-focused):hover) .item-native::after{background:var(--background-hover);opacity:var(--background-hover-opacity)}:host(.ion-color.ion-activatable:not(.ion-focused):hover) .item-native{color:var(--ion-color-contrast)}:host(.ion-color.ion-activatable:not(.ion-focused):hover) .item-native::after{background:var(--ion-color-contrast)}}:host(.item-control-needs-pointer-cursor){cursor:pointer}:host(.item-interactive-disabled:not(.item-multiple-inputs)){cursor:default;pointer-events:none}:host(.item-disabled){cursor:default;opacity:0.3;pointer-events:none}.item-native{border-radius:var(--border-radius);margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-top:var(--padding-top);padding-bottom:var(--padding-bottom);font-family:inherit;font-size:inherit;font-style:inherit;font-weight:inherit;letter-spacing:inherit;text-decoration:inherit;text-indent:inherit;text-overflow:inherit;text-transform:inherit;text-align:inherit;white-space:inherit;color:inherit;padding-right:var(--padding-end);padding-left:calc(var(--padding-start) + var(--ion-safe-area-left, 0px));display:-ms-flexbox;display:flex;position:relative;-ms-flex-align:inherit;align-items:inherit;-ms-flex-pack:inherit;justify-content:inherit;width:100%;min-height:var(--min-height);-webkit-transition:var(--transition);transition:var(--transition);border-width:var(--border-width);border-style:var(--border-style);border-color:var(--border-color);outline:none;background:var(--background);overflow:inherit;z-index:1;-webkit-box-sizing:border-box;box-sizing:border-box}:host-context([dir=rtl]) .item-native{padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}[dir=rtl] .item-native{padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}@supports selector(:dir(rtl)){.item-native:dir(rtl){padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}}.item-native::-moz-focus-inner{border:0}.item-native::after{left:0;right:0;top:0;bottom:0;position:absolute;content:\"\";opacity:0;-webkit-transition:var(--transition);transition:var(--transition);z-index:-1}button,a{cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-user-drag:none}.item-inner{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-top:var(--inner-padding-top);padding-bottom:var(--inner-padding-bottom);padding-right:calc(var(--ion-safe-area-right, 0px) + var(--inner-padding-end));padding-left:var(--inner-padding-start);display:-ms-flexbox;display:flex;position:relative;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;min-height:inherit;border-width:var(--inner-border-width);border-style:var(--border-style);border-color:var(--border-color);-webkit-box-shadow:var(--inner-box-shadow);box-shadow:var(--inner-box-shadow);overflow:inherit;-webkit-box-sizing:border-box;box-sizing:border-box}:host-context([dir=rtl]) .item-inner{padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}[dir=rtl] .item-inner{padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}@supports selector(:dir(rtl)){.item-inner:dir(rtl){padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}}.item-detail-icon{-webkit-margin-start:calc(var(--inner-padding-end) / 2);margin-inline-start:calc(var(--inner-padding-end) / 2);-webkit-margin-end:-6px;margin-inline-end:-6px;color:var(--detail-icon-color);font-size:var(--detail-icon-font-size);opacity:var(--detail-icon-opacity)}::slotted(ion-icon){font-size:1.6em}::slotted(ion-button){--margin-top:0;--margin-bottom:0;--margin-start:0;--margin-end:0;z-index:1}::slotted(ion-label:not([slot=end])){-ms-flex:1;flex:1;width:-webkit-min-content;width:-moz-min-content;width:min-content;max-width:100%}:host(.item-input){-ms-flex-align:center;align-items:center}.input-wrapper{display:-ms-flexbox;display:flex;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;text-overflow:ellipsis;overflow:inherit;-webkit-box-sizing:border-box;box-sizing:border-box}:host(.item-label-stacked),:host(.item-label-floating){-ms-flex-align:start;align-items:start}:host(.item-label-stacked) .input-wrapper,:host(.item-label-floating) .input-wrapper{-ms-flex:1;flex:1;-ms-flex-direction:column;flex-direction:column}:host(.item-multiple-inputs) ::slotted(ion-checkbox),:host(.item-multiple-inputs) ::slotted(ion-datetime),:host(.item-multiple-inputs) ::slotted(ion-radio){position:relative}:host(.item-textarea){-ms-flex-align:stretch;align-items:stretch}::slotted(ion-reorder[slot]){margin-top:0;margin-bottom:0}ion-ripple-effect{color:var(--ripple-color)}:host{--min-height:44px;--transition:background-color 200ms linear, opacity 200ms linear;--padding-start:16px;--inner-padding-end:16px;--inner-border-width:0px 0px 0.55px 0px;--background:var(--ion-item-background, var(--ion-background-color, #fff));--background-activated:var(--ion-text-color, #000);--background-focused:var(--ion-text-color, #000);--background-hover:currentColor;--background-activated-opacity:.12;--background-focused-opacity:.15;--background-hover-opacity:.04;--border-color:var(--ion-item-border-color, var(--ion-border-color, var(--ion-color-step-250, var(--ion-background-color-step-250, #c8c7cc))));--color:var(--ion-item-color, var(--ion-text-color, #000));font-size:1rem}:host(.ion-activated){--transition:none}:host(.ion-color.ion-focused) .item-native::after{background:#000;opacity:0.15}:host(.ion-color.ion-activated) .item-native::after{background:#000;opacity:0.12}:host(.item-lines-full){--border-width:0px 0px 0.55px 0px}:host(.item-lines-inset){--inner-border-width:0px 0px 0.55px 0px}:host(.item-lines-inset),:host(.item-lines-none){--border-width:0px}:host(.item-lines-full),:host(.item-lines-none){--inner-border-width:0px}::slotted([slot=start]){-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:16px;margin-inline-end:16px;margin-top:2px;margin-bottom:2px}::slotted(ion-icon[slot=start]),::slotted(ion-icon[slot=end]){margin-top:7px;margin-bottom:7px}::slotted(ion-toggle[slot=start]),::slotted(ion-toggle[slot=end]){margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}:host(.item-label-stacked) ::slotted([slot=end]),:host(.item-label-floating) ::slotted([slot=end]){margin-top:7px;margin-bottom:7px}::slotted(.button-small){--padding-top:1px;--padding-bottom:1px;--padding-start:.5em;--padding-end:.5em;min-height:24px;font-size:0.8125rem}::slotted(ion-avatar){width:36px;height:36px}::slotted(ion-thumbnail){--size:56px}::slotted(ion-avatar[slot=end]),::slotted(ion-thumbnail[slot=end]){-webkit-margin-start:8px;margin-inline-start:8px;-webkit-margin-end:8px;margin-inline-end:8px;margin-top:8px;margin-bottom:8px}:host(.item-radio) ::slotted(ion-label),:host(.item-toggle) ::slotted(ion-label){-webkit-margin-start:0px;margin-inline-start:0px}::slotted(ion-label){-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:8px;margin-inline-end:8px;margin-top:10px;margin-bottom:10px}:host(.item-label-floating),:host(.item-label-stacked){--min-height:68px}";

const itemMdCss = ":host{--border-radius:0px;--border-width:0px;--border-style:solid;--padding-top:0px;--padding-bottom:0px;--padding-end:0px;--padding-start:0px;--inner-border-width:0px;--inner-padding-top:0px;--inner-padding-bottom:0px;--inner-padding-start:0px;--inner-padding-end:0px;--inner-box-shadow:none;--detail-icon-color:initial;--detail-icon-font-size:1.25em;--detail-icon-opacity:0.25;--color-activated:var(--color);--color-focused:var(--color);--color-hover:var(--color);--ripple-color:currentColor;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:block;position:relative;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;outline:none;color:var(--color);font-family:var(--ion-font-family, inherit);text-align:initial;text-decoration:none;overflow:hidden;-webkit-box-sizing:border-box;box-sizing:border-box}:host(.ion-color) .item-native{background:var(--ion-color-base);color:var(--ion-color-contrast)}:host(.ion-color) .item-native,:host(.ion-color) .item-inner{border-color:var(--ion-color-shade)}:host(.ion-activated) .item-native{color:var(--color-activated)}:host(.ion-activated) .item-native::after{background:var(--background-activated);opacity:var(--background-activated-opacity)}:host(.ion-color.ion-activated) .item-native{color:var(--ion-color-contrast)}:host(.ion-focused) .item-native{color:var(--color-focused)}:host(.ion-focused) .item-native::after{background:var(--background-focused);opacity:var(--background-focused-opacity)}:host(.ion-color.ion-focused) .item-native{color:var(--ion-color-contrast)}:host(.ion-color.ion-focused) .item-native::after{background:var(--ion-color-contrast)}@media (any-hover: hover){:host(.ion-activatable:not(.ion-focused):hover) .item-native{color:var(--color-hover)}:host(.ion-activatable:not(.ion-focused):hover) .item-native::after{background:var(--background-hover);opacity:var(--background-hover-opacity)}:host(.ion-color.ion-activatable:not(.ion-focused):hover) .item-native{color:var(--ion-color-contrast)}:host(.ion-color.ion-activatable:not(.ion-focused):hover) .item-native::after{background:var(--ion-color-contrast)}}:host(.item-control-needs-pointer-cursor){cursor:pointer}:host(.item-interactive-disabled:not(.item-multiple-inputs)){cursor:default;pointer-events:none}:host(.item-disabled){cursor:default;opacity:0.3;pointer-events:none}.item-native{border-radius:var(--border-radius);margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-top:var(--padding-top);padding-bottom:var(--padding-bottom);font-family:inherit;font-size:inherit;font-style:inherit;font-weight:inherit;letter-spacing:inherit;text-decoration:inherit;text-indent:inherit;text-overflow:inherit;text-transform:inherit;text-align:inherit;white-space:inherit;color:inherit;padding-right:var(--padding-end);padding-left:calc(var(--padding-start) + var(--ion-safe-area-left, 0px));display:-ms-flexbox;display:flex;position:relative;-ms-flex-align:inherit;align-items:inherit;-ms-flex-pack:inherit;justify-content:inherit;width:100%;min-height:var(--min-height);-webkit-transition:var(--transition);transition:var(--transition);border-width:var(--border-width);border-style:var(--border-style);border-color:var(--border-color);outline:none;background:var(--background);overflow:inherit;z-index:1;-webkit-box-sizing:border-box;box-sizing:border-box}:host-context([dir=rtl]) .item-native{padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}[dir=rtl] .item-native{padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}@supports selector(:dir(rtl)){.item-native:dir(rtl){padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}}.item-native::-moz-focus-inner{border:0}.item-native::after{left:0;right:0;top:0;bottom:0;position:absolute;content:\"\";opacity:0;-webkit-transition:var(--transition);transition:var(--transition);z-index:-1}button,a{cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;-webkit-user-drag:none}.item-inner{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-top:var(--inner-padding-top);padding-bottom:var(--inner-padding-bottom);padding-right:calc(var(--ion-safe-area-right, 0px) + var(--inner-padding-end));padding-left:var(--inner-padding-start);display:-ms-flexbox;display:flex;position:relative;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;min-height:inherit;border-width:var(--inner-border-width);border-style:var(--border-style);border-color:var(--border-color);-webkit-box-shadow:var(--inner-box-shadow);box-shadow:var(--inner-box-shadow);overflow:inherit;-webkit-box-sizing:border-box;box-sizing:border-box}:host-context([dir=rtl]) .item-inner{padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}[dir=rtl] .item-inner{padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}@supports selector(:dir(rtl)){.item-inner:dir(rtl){padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}}.item-detail-icon{-webkit-margin-start:calc(var(--inner-padding-end) / 2);margin-inline-start:calc(var(--inner-padding-end) / 2);-webkit-margin-end:-6px;margin-inline-end:-6px;color:var(--detail-icon-color);font-size:var(--detail-icon-font-size);opacity:var(--detail-icon-opacity)}::slotted(ion-icon){font-size:1.6em}::slotted(ion-button){--margin-top:0;--margin-bottom:0;--margin-start:0;--margin-end:0;z-index:1}::slotted(ion-label:not([slot=end])){-ms-flex:1;flex:1;width:-webkit-min-content;width:-moz-min-content;width:min-content;max-width:100%}:host(.item-input){-ms-flex-align:center;align-items:center}.input-wrapper{display:-ms-flexbox;display:flex;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;text-overflow:ellipsis;overflow:inherit;-webkit-box-sizing:border-box;box-sizing:border-box}:host(.item-label-stacked),:host(.item-label-floating){-ms-flex-align:start;align-items:start}:host(.item-label-stacked) .input-wrapper,:host(.item-label-floating) .input-wrapper{-ms-flex:1;flex:1;-ms-flex-direction:column;flex-direction:column}:host(.item-multiple-inputs) ::slotted(ion-checkbox),:host(.item-multiple-inputs) ::slotted(ion-datetime),:host(.item-multiple-inputs) ::slotted(ion-radio){position:relative}:host(.item-textarea){-ms-flex-align:stretch;align-items:stretch}::slotted(ion-reorder[slot]){margin-top:0;margin-bottom:0}ion-ripple-effect{color:var(--ripple-color)}:host{--min-height:48px;--background:var(--ion-item-background, var(--ion-background-color, #fff));--background-activated:transparent;--background-focused:currentColor;--background-hover:currentColor;--background-activated-opacity:0;--background-focused-opacity:.12;--background-hover-opacity:.04;--border-color:var(--ion-item-border-color, var(--ion-border-color, var(--ion-color-step-150, var(--ion-background-color-step-150, rgba(0, 0, 0, 0.13)))));--color:var(--ion-item-color, var(--ion-text-color, #000));--transition:opacity 15ms linear, background-color 15ms linear;--padding-start:16px;--inner-padding-end:16px;--inner-border-width:0 0 1px 0;font-size:1rem;font-weight:normal;text-transform:none}:host(.ion-color.ion-activated) .item-native::after{background:transparent}:host(.item-interactive){--border-width:0 0 1px 0;--inner-border-width:0}:host(.item-lines-full){--border-width:0 0 1px 0}:host(.item-lines-inset){--inner-border-width:0 0 1px 0}:host(.item-lines-inset),:host(.item-lines-none){--border-width:0}:host(.item-lines-full),:host(.item-lines-none){--inner-border-width:0}:host(.item-multi-line) ::slotted([slot=start]),:host(.item-multi-line) ::slotted([slot=end]){margin-top:16px;margin-bottom:16px;-ms-flex-item-align:start;align-self:flex-start}::slotted([slot=start]){-webkit-margin-end:16px;margin-inline-end:16px}::slotted([slot=end]){-webkit-margin-start:16px;margin-inline-start:16px}::slotted(ion-icon){color:rgba(var(--ion-text-color-rgb, 0, 0, 0), 0.54);font-size:1.5em}:host(.ion-color) ::slotted(ion-icon){color:var(--ion-color-contrast)}::slotted(ion-icon[slot]){margin-top:12px;margin-bottom:12px}::slotted(ion-icon[slot=start]){-webkit-margin-end:32px;margin-inline-end:32px}::slotted(ion-icon[slot=end]){-webkit-margin-start:16px;margin-inline-start:16px}::slotted(ion-toggle[slot=start]),::slotted(ion-toggle[slot=end]){margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}::slotted(ion-note){margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;-ms-flex-item-align:start;align-self:flex-start;font-size:0.6875rem}::slotted(ion-note[slot]){padding-left:0;padding-right:0;padding-top:18px;padding-bottom:10px}::slotted(ion-avatar){width:40px;height:40px}::slotted(ion-thumbnail){--size:56px}::slotted(ion-avatar),::slotted(ion-thumbnail){margin-top:8px;margin-bottom:8px}::slotted(ion-avatar[slot=start]),::slotted(ion-thumbnail[slot=start]){-webkit-margin-end:16px;margin-inline-end:16px}::slotted(ion-avatar[slot=end]),::slotted(ion-thumbnail[slot=end]){-webkit-margin-start:16px;margin-inline-start:16px}::slotted(ion-label){margin-left:0;margin-right:0;margin-top:10px;margin-bottom:10px}:host(.item-label-stacked) ::slotted([slot=end]),:host(.item-label-floating) ::slotted([slot=end]){margin-top:7px;margin-bottom:7px}:host(.item-toggle) ::slotted(ion-label),:host(.item-radio) ::slotted(ion-label){-webkit-margin-start:0;margin-inline-start:0}::slotted(.button-small){--padding-top:2px;--padding-bottom:2px;--padding-start:.6em;--padding-end:.6em;min-height:25px;font-size:0.75rem}:host(.item-label-floating),:host(.item-label-stacked){--min-height:55px}:host(.ion-focused:not(.ion-color)) ::slotted(.label-stacked),:host(.ion-focused:not(.ion-color)) ::slotted(.label-floating),:host(.item-has-focus:not(.ion-color)) ::slotted(.label-stacked),:host(.item-has-focus:not(.ion-color)) ::slotted(.label-floating){color:var(--ion-color-primary, #0054e9)}";

const Item = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
        this.labelColorStyles = {};
        this.itemStyles = new Map();
        this.inheritedAriaAttributes = {};
        this.multipleInputs = false;
        this.focusable = true;
        this.isInteractive = false;
        /**
         * If `true`, a button tag will be rendered and the item will be tappable.
         */
        this.button = false;
        /**
         * The icon to use when `detail` is set to `true`.
         */
        this.detailIcon = chevronForward;
        /**
         * If `true`, the user cannot interact with the item.
         */
        this.disabled = false;
        /**
         * When using a router, it specifies the transition direction when navigating to
         * another page using `href`.
         */
        this.routerDirection = 'forward';
        /**
         * The type of the button. Only used when an `onclick` or `button` property is present.
         */
        this.type = 'button';
        // slot change listener updates state to reflect how/if item should be interactive
        this.updateInteractivityOnSlotChange = () => {
            this.setIsInteractive();
            this.setMultipleInputs();
        };
    }
    buttonChanged() {
        // Update the focusable option when the button option is changed
        this.focusable = this.isFocusable();
    }
    labelColorChanged(ev) {
        const { color } = this;
        // There will be a conflict with item color if
        // we apply the label color to item, so we ignore
        // the label color if the user sets a color on item
        if (color === undefined) {
            this.labelColorStyles = ev.detail;
        }
    }
    itemStyle(ev) {
        ev.stopPropagation();
        const tagName = ev.target.tagName;
        const updatedStyles = ev.detail;
        const newStyles = {};
        const childStyles = this.itemStyles.get(tagName) || {};
        let hasStyleChange = false;
        Object.keys(updatedStyles).forEach((key) => {
            if (updatedStyles[key]) {
                const itemKey = `item-${key}`;
                if (!childStyles[itemKey]) {
                    hasStyleChange = true;
                }
                newStyles[itemKey] = true;
            }
        });
        if (!hasStyleChange && Object.keys(newStyles).length !== Object.keys(childStyles).length) {
            hasStyleChange = true;
        }
        if (hasStyleChange) {
            this.itemStyles.set(tagName, newStyles);
            forceUpdate(this);
        }
    }
    connectedCallback() {
        this.hasStartEl();
    }
    componentWillLoad() {
        this.inheritedAriaAttributes = inheritAttributes(this.el, ['aria-label']);
    }
    componentDidLoad() {
        raf(() => {
            this.setMultipleInputs();
            this.setIsInteractive();
            this.focusable = this.isFocusable();
        });
    }
    totalNestedInputs() {
        // The following elements have a clickable cover that is relative to the entire item
        const covers = this.el.querySelectorAll('ion-checkbox, ion-datetime, ion-select, ion-radio');
        // The following elements can accept focus alongside the previous elements
        // therefore if these elements are also a child of item, we don't want the
        // input cover on top of those interfering with their clicks
        const inputs = this.el.querySelectorAll('ion-input, ion-range, ion-searchbar, ion-segment, ion-textarea, ion-toggle');
        // The following elements should also stay clickable when an input with cover is present
        const clickables = this.el.querySelectorAll('ion-router-link, ion-button, a, button');
        return {
            covers,
            inputs,
            clickables,
        };
    }
    // If the item contains multiple clickable elements and/or inputs, then the item
    // should not have a clickable input cover over the entire item to prevent
    // interfering with their individual click events
    setMultipleInputs() {
        const { covers, inputs, clickables } = this.totalNestedInputs();
        // Check for multiple inputs to change the position of the input cover to relative
        // for all of the covered inputs above
        this.multipleInputs =
            covers.length + inputs.length > 1 ||
                covers.length + clickables.length > 1 ||
                (covers.length > 0 && this.isClickable());
    }
    setIsInteractive() {
        // If item contains any interactive children, set isInteractive to `true`
        const { covers, inputs, clickables } = this.totalNestedInputs();
        this.isInteractive = covers.length > 0 || inputs.length > 0 || clickables.length > 0;
    }
    // If the item contains an input including a checkbox, datetime, select, or radio
    // then the item will have a clickable input cover that covers the item
    // that should get the hover, focused and activated states UNLESS it has multiple
    // inputs, then those need to individually get each click
    hasCover() {
        const inputs = this.el.querySelectorAll('ion-checkbox, ion-datetime, ion-select, ion-radio');
        return inputs.length === 1 && !this.multipleInputs;
    }
    // If the item has an href or button property it will render a native
    // anchor or button that is clickable
    isClickable() {
        return this.href !== undefined || this.button;
    }
    canActivate() {
        return this.isClickable() || this.hasCover();
    }
    isFocusable() {
        const focusableChild = this.el.querySelector('.ion-focusable');
        return this.canActivate() || focusableChild !== null;
    }
    hasStartEl() {
        const startEl = this.el.querySelector('[slot="start"]');
        if (startEl !== null) {
            this.el.classList.add('item-has-start-slot');
        }
    }
    getFirstInteractive() {
        const controls = this.el.querySelectorAll('ion-toggle:not([disabled]), ion-checkbox:not([disabled]), ion-radio:not([disabled]), ion-select:not([disabled]), ion-input:not([disabled]), ion-textarea:not([disabled])');
        return controls[0];
    }
    render() {
        const { detail, detailIcon, download, labelColorStyles, lines, disabled, href, rel, target, routerAnimation, routerDirection, inheritedAriaAttributes, multipleInputs, } = this;
        const childStyles = {};
        const mode = getIonMode(this);
        const clickable = this.isClickable();
        const canActivate = this.canActivate();
        const TagType = clickable ? (href === undefined ? 'button' : 'a') : 'div';
        const attrs = TagType === 'button'
            ? { type: this.type }
            : {
                download,
                href,
                rel,
                target,
            };
        let clickFn = {};
        const firstInteractive = this.getFirstInteractive();
        // Only set onClick if the item is clickable to prevent screen
        // readers from reading all items as clickable
        if (clickable || (firstInteractive !== undefined && !multipleInputs)) {
            clickFn = {
                onClick: (ev) => {
                    if (clickable) {
                        openURL(href, ev, routerDirection, routerAnimation);
                    }
                    if (firstInteractive !== undefined && !multipleInputs) {
                        const path = ev.composedPath();
                        const target = path[0];
                        if (ev.isTrusted) {
                            /**
                             * Dispatches a click event to the first interactive element,
                             * when it is the result of a user clicking on the item.
                             *
                             * We check if the click target is in the shadow root,
                             * which means the user clicked on the .item-native or
                             * .item-inner padding.
                             */
                            const clickedWithinShadowRoot = this.el.shadowRoot.contains(target);
                            if (clickedWithinShadowRoot) {
                                /**
                                 * For input/textarea clicking the padding should focus the
                                 * text field (thus making it editable). For everything else,
                                 * we want to click the control so it activates.
                                 */
                                if (firstInteractive.tagName === 'ION-INPUT' || firstInteractive.tagName === 'ION-TEXTAREA') {
                                    firstInteractive.setFocus();
                                }
                                firstInteractive.click();
                                /**
                                 * Stop the item event from being triggered
                                 * as the firstInteractive click event will also
                                 * trigger the item click event.
                                 */
                                ev.stopImmediatePropagation();
                            }
                        }
                    }
                },
            };
        }
        const showDetail = detail !== undefined ? detail : mode === 'ios' && clickable;
        this.itemStyles.forEach((value) => {
            Object.assign(childStyles, value);
        });
        const ariaDisabled = disabled || childStyles['item-interactive-disabled'] ? 'true' : null;
        const inList = hostContext('ion-list', this.el) && !hostContext('ion-radio-group', this.el);
        /**
         * Inputs and textareas do not need to show a cursor pointer.
         * However, other form controls such as checkboxes and radios do.
         */
        const firstInteractiveNeedsPointerCursor = firstInteractive !== undefined && !['ION-INPUT', 'ION-TEXTAREA'].includes(firstInteractive.tagName);
        return (h(Host, { key: '24b59935bd8db8b0b7f940582455a42b82cbf762', "aria-disabled": ariaDisabled, class: Object.assign(Object.assign(Object.assign({}, childStyles), labelColorStyles), createColorClasses(this.color, {
                item: true,
                [mode]: true,
                'item-lines-default': lines === undefined,
                [`item-lines-${lines}`]: lines !== undefined,
                'item-control-needs-pointer-cursor': firstInteractiveNeedsPointerCursor,
                'item-disabled': disabled,
                'in-list': inList,
                'item-multiple-inputs': this.multipleInputs,
                'ion-activatable': canActivate,
                'ion-focusable': this.focusable,
                'item-rtl': document.dir === 'rtl',
            })), role: inList ? 'listitem' : null }, h(TagType, Object.assign({ key: 'fd77b6e5f3eb2e1857a0cdd45562d71eabd30255' }, attrs, inheritedAriaAttributes, { class: "item-native", part: "native", disabled: disabled }, clickFn), h("slot", { key: '8824ac8395aafa3d63c92f2128e947cac8393ac4', name: "start", onSlotchange: this.updateInteractivityOnSlotChange }), h("div", { key: '5c9127e388a432687766d86a9db91fd1663abf03', class: "item-inner" }, h("div", { key: '9dc2d2f58c4067c0143b3963334c346c3c7f77df', class: "input-wrapper" }, h("slot", { key: '8377d9e56dc4b1913f1346111b706e7f14c24d30', onSlotchange: this.updateInteractivityOnSlotChange })), h("slot", { key: 'bc771e106174f4a84ee12e92d14df81ad7ed177d', name: "end", onSlotchange: this.updateInteractivityOnSlotChange }), showDetail && (h("ion-icon", { key: '45336d121a097cbf71ee8a3f6b554745ba5e0bbf', icon: detailIcon, lazy: false, class: "item-detail-icon", part: "detail-icon", "aria-hidden": "true", "flip-rtl": detailIcon === chevronForward }))), canActivate && mode === 'md' && h("ion-ripple-effect", { key: '197e244ae3bffebfa6ac9bfe7658d12e1af0ecb1' }))));
    }
    get el() { return getElement(this); }
    static get watchers() { return {
        "button": ["buttonChanged"]
    }; }
};
Item.style = {
    ios: itemIosCss,
    md: itemMdCss
};

const itemDividerIosCss = ":host{--padding-top:0px;--padding-end:0px;--padding-bottom:0px;--padding-start:0px;--inner-padding-top:0px;--inner-padding-end:0px;--inner-padding-bottom:0px;--inner-padding-start:0px;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-top:var(--padding-top);padding-bottom:var(--padding-bottom);padding-right:var(--padding-end);padding-left:calc(var(--padding-start) + var(--ion-safe-area-left, 0px));display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;width:100%;background:var(--background);color:var(--color);font-family:var(--ion-font-family, inherit);overflow:hidden;z-index:100;-webkit-box-sizing:border-box;box-sizing:border-box}:host-context([dir=rtl]){padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}@supports selector(:dir(rtl)){:host(:dir(rtl)){padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}}:host(.ion-color){background:var(--ion-color-base);color:var(--ion-color-contrast)}:host(.item-divider-sticky){position:-webkit-sticky;position:sticky;top:0}.item-divider-inner{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-top:var(--inner-padding-top);padding-bottom:var(--inner-padding-bottom);padding-right:calc(var(--ion-safe-area-right, 0px) + var(--inner-padding-end));padding-left:var(--inner-padding-start);display:-ms-flexbox;display:flex;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;min-height:inherit;border:0;overflow:hidden}:host-context([dir=rtl]) .item-divider-inner{padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}[dir=rtl] .item-divider-inner{padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}@supports selector(:dir(rtl)){.item-divider-inner:dir(rtl){padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}}.item-divider-wrapper{display:-ms-flexbox;display:flex;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;text-overflow:ellipsis;overflow:hidden}:host{--background:var(--ion-color-step-100, var(--ion-background-color-step-100, #e6e6e6));--color:var(--ion-color-step-850, var(--ion-text-color-step-150, #262626));--padding-start:16px;--inner-padding-end:8px;border-radius:0;position:relative;min-height:28px;font-size:1.0625rem;font-weight:600}:host([slot=start]){-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:16px;margin-inline-end:16px;margin-top:2px;margin-bottom:2px}::slotted(ion-icon[slot=start]),::slotted(ion-icon[slot=end]){margin-top:7px;margin-bottom:7px}::slotted(h1){margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px}::slotted(h2){margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px}::slotted(h3),::slotted(h4),::slotted(h5),::slotted(h6){margin-left:0;margin-right:0;margin-top:0;margin-bottom:3px}::slotted(p){margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px;color:var(--ion-text-color-step-550, #a3a3a3);font-size:0.875rem;line-height:normal;text-overflow:inherit;overflow:inherit}::slotted(h2:last-child) ::slotted(h3:last-child),::slotted(h4:last-child),::slotted(h5:last-child),::slotted(h6:last-child),::slotted(p:last-child){margin-bottom:0}";

const itemDividerMdCss = ":host{--padding-top:0px;--padding-end:0px;--padding-bottom:0px;--padding-start:0px;--inner-padding-top:0px;--inner-padding-end:0px;--inner-padding-bottom:0px;--inner-padding-start:0px;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-top:var(--padding-top);padding-bottom:var(--padding-bottom);padding-right:var(--padding-end);padding-left:calc(var(--padding-start) + var(--ion-safe-area-left, 0px));display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;width:100%;background:var(--background);color:var(--color);font-family:var(--ion-font-family, inherit);overflow:hidden;z-index:100;-webkit-box-sizing:border-box;box-sizing:border-box}:host-context([dir=rtl]){padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}@supports selector(:dir(rtl)){:host(:dir(rtl)){padding-right:calc(var(--padding-start) + var(--ion-safe-area-right, 0px));padding-left:var(--padding-end)}}:host(.ion-color){background:var(--ion-color-base);color:var(--ion-color-contrast)}:host(.item-divider-sticky){position:-webkit-sticky;position:sticky;top:0}.item-divider-inner{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-top:var(--inner-padding-top);padding-bottom:var(--inner-padding-bottom);padding-right:calc(var(--ion-safe-area-right, 0px) + var(--inner-padding-end));padding-left:var(--inner-padding-start);display:-ms-flexbox;display:flex;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;min-height:inherit;border:0;overflow:hidden}:host-context([dir=rtl]) .item-divider-inner{padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}[dir=rtl] .item-divider-inner{padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}@supports selector(:dir(rtl)){.item-divider-inner:dir(rtl){padding-right:var(--inner-padding-start);padding-left:calc(var(--ion-safe-area-left, 0px) + var(--inner-padding-end))}}.item-divider-wrapper{display:-ms-flexbox;display:flex;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;text-overflow:ellipsis;overflow:hidden}:host{--background:var(--ion-background-color, #fff);--color:var(--ion-color-step-400, var(--ion-text-color-step-600, #999999));--padding-start:16px;--inner-padding-end:16px;min-height:30px;border-bottom:1px solid var(--ion-item-border-color, var(--ion-border-color, var(--ion-color-step-150, var(--ion-background-color-step-150, rgba(0, 0, 0, 0.13)))));font-size:0.875rem}::slotted([slot=start]){-webkit-margin-end:16px;margin-inline-end:16px}::slotted([slot=end]){-webkit-margin-start:16px;margin-inline-start:16px}::slotted(ion-label){margin-left:0;margin-right:0;margin-top:13px;margin-bottom:10px}::slotted(ion-icon){color:rgba(var(--ion-text-color-rgb, 0, 0, 0), 0.54);font-size:1.7142857143em}:host(.ion-color) ::slotted(ion-icon){color:var(--ion-color-contrast)}::slotted(ion-icon[slot]){margin-top:12px;margin-bottom:12px}::slotted(ion-icon[slot=start]){-webkit-margin-end:32px;margin-inline-end:32px}::slotted(ion-icon[slot=end]){-webkit-margin-start:16px;margin-inline-start:16px}::slotted(ion-note){margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;-ms-flex-item-align:start;align-self:flex-start;font-size:0.6875rem}::slotted(ion-note[slot]){padding-left:0;padding-right:0;padding-top:18px;padding-bottom:10px}::slotted(ion-avatar){width:40px;height:40px}::slotted(ion-thumbnail){--size:56px}::slotted(ion-avatar),::slotted(ion-thumbnail){margin-top:8px;margin-bottom:8px}::slotted(ion-avatar[slot=start]),::slotted(ion-thumbnail[slot=start]){-webkit-margin-end:16px;margin-inline-end:16px}::slotted(ion-avatar[slot=end]),::slotted(ion-thumbnail[slot=end]){-webkit-margin-start:16px;margin-inline-start:16px}::slotted(h1){margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px}::slotted(h2){margin-left:0;margin-right:0;margin-top:2px;margin-bottom:2px}::slotted(h3,h4,h5,h6){margin-left:0;margin-right:0;margin-top:2px;margin-bottom:2px}::slotted(p){margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px;color:var(--ion-color-step-600, var(--ion-text-color-step-400, #666666));font-size:0.875rem;line-height:normal;text-overflow:inherit;overflow:inherit}";

const ItemDivider = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
        /**
         * When it's set to `true`, the item-divider will stay visible when it reaches the top
         * of the viewport until the next `ion-item-divider` replaces it.
         *
         * This feature relies in `position:sticky`:
         * https://caniuse.com/#feat=css-sticky
         */
        this.sticky = false;
    }
    render() {
        const mode = getIonMode(this);
        return (h(Host, { key: '1523095ce4af3f2611512ff0948ead659959ee4a', class: createColorClasses(this.color, {
                [mode]: true,
                'item-divider-sticky': this.sticky,
                item: true,
            }) }, h("slot", { key: '39105d888e115416c3a3fe588da44b4c61f4e5fe', name: "start" }), h("div", { key: '67e16f1056bd39187f3629c1bb383b7abbda829b', class: "item-divider-inner" }, h("div", { key: 'b3a218fdcc7b9aeab6e0155340152d39fa0b6329', class: "item-divider-wrapper" }, h("slot", { key: '69d8587533b387869d34b075d02f61396858fc90' })), h("slot", { key: 'b91c654699b3b26d0012ea0c719c4a07d1fcfbaa', name: "end" }))));
    }
    get el() { return getElement(this); }
};
ItemDivider.style = {
    ios: itemDividerIosCss,
    md: itemDividerMdCss
};

const itemGroupIosCss = "ion-item-group{display:block}";

const itemGroupMdCss = "ion-item-group{display:block}";

const ItemGroup = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
    }
    render() {
        const mode = getIonMode(this);
        return (h(Host, { key: 'e49dc8f99247d2431d7c6db01b6e021a0f5b1c37', role: "group", class: {
                [mode]: true,
                // Used internally for styling
                [`item-group-${mode}`]: true,
                item: true,
            } }));
    }
};
ItemGroup.style = {
    ios: itemGroupIosCss,
    md: itemGroupMdCss
};

const labelIosCss = ".item.sc-ion-label-ios-h,.item .sc-ion-label-ios-h{--color:initial;display:block;color:var(--color);font-family:var(--ion-font-family, inherit);font-size:inherit;text-overflow:ellipsis;-webkit-box-sizing:border-box;box-sizing:border-box}.ion-color.sc-ion-label-ios-h{color:var(--ion-color-base)}.ion-text-nowrap.sc-ion-label-ios-h{overflow:hidden}.item-interactive-disabled.sc-ion-label-ios-h:not(.item-multiple-inputs),.item-interactive-disabled:not(.item-multiple-inputs) .sc-ion-label-ios-h{cursor:default;opacity:0.3;pointer-events:none}.item-input.sc-ion-label-ios-h,.item-input .sc-ion-label-ios-h{-ms-flex:initial;flex:initial;max-width:200px;pointer-events:none}.item-textarea.sc-ion-label-ios-h,.item-textarea .sc-ion-label-ios-h{-ms-flex-item-align:baseline;align-self:baseline}.item-skeleton-text.sc-ion-label-ios-h,.item-skeleton-text .sc-ion-label-ios-h{overflow:hidden}.label-fixed.sc-ion-label-ios-h{-ms-flex:0 0 100px;flex:0 0 100px;width:100px;min-width:100px;max-width:200px}.label-stacked.sc-ion-label-ios-h,.label-floating.sc-ion-label-ios-h{margin-bottom:0;-ms-flex-item-align:stretch;align-self:stretch;width:auto;max-width:100%}.label-no-animate.label-floating.sc-ion-label-ios-h{-webkit-transition:none;transition:none}.sc-ion-label-ios-s h1,.sc-ion-label-ios-s h2,.sc-ion-label-ios-s h3,.sc-ion-label-ios-s h4,.sc-ion-label-ios-s h5,.sc-ion-label-ios-s h6{text-overflow:inherit;overflow:inherit}.ion-text-wrap.sc-ion-label-ios-h{font-size:0.875rem;line-height:1.5}.label-stacked.sc-ion-label-ios-h{margin-bottom:4px;font-size:0.875rem}.label-floating.sc-ion-label-ios-h{margin-bottom:0;-webkit-transform:translate(0, 29px);transform:translate(0, 29px);-webkit-transform-origin:left top;transform-origin:left top;-webkit-transition:-webkit-transform 150ms ease-in-out;transition:-webkit-transform 150ms ease-in-out;transition:transform 150ms ease-in-out;transition:transform 150ms ease-in-out, -webkit-transform 150ms ease-in-out}[dir=rtl].sc-ion-label-ios-h -no-combinator.label-floating.sc-ion-label-ios-h,[dir=rtl] .sc-ion-label-ios-h -no-combinator.label-floating.sc-ion-label-ios-h,[dir=rtl].label-floating.sc-ion-label-ios-h,[dir=rtl] .label-floating.sc-ion-label-ios-h{-webkit-transform-origin:right top;transform-origin:right top}@supports selector(:dir(rtl)){.label-floating.sc-ion-label-ios-h:dir(rtl){-webkit-transform-origin:right top;transform-origin:right top}}.item-textarea.label-floating.sc-ion-label-ios-h,.item-textarea .label-floating.sc-ion-label-ios-h{-webkit-transform:translate(0, 28px);transform:translate(0, 28px)}.item-has-focus.label-floating.sc-ion-label-ios-h,.item-has-focus .label-floating.sc-ion-label-ios-h,.item-has-placeholder.sc-ion-label-ios-h:not(.item-input).label-floating,.item-has-placeholder:not(.item-input) .label-floating.sc-ion-label-ios-h,.item-has-value.label-floating.sc-ion-label-ios-h,.item-has-value .label-floating.sc-ion-label-ios-h{-webkit-transform:scale(0.82);transform:scale(0.82)}.sc-ion-label-ios-s h1{margin-left:0;margin-right:0;margin-top:3px;margin-bottom:2px;font-size:1.375rem;font-weight:normal}.sc-ion-label-ios-s h2{margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px;font-size:1.0625rem;font-weight:normal}.sc-ion-label-ios-s h3,.sc-ion-label-ios-s h4,.sc-ion-label-ios-s h5,.sc-ion-label-ios-s h6{margin-left:0;margin-right:0;margin-top:0;margin-bottom:3px;font-size:0.875rem;font-weight:normal;line-height:normal}.sc-ion-label-ios-s p{margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px;font-size:0.875rem;line-height:normal;text-overflow:inherit;overflow:inherit}.sc-ion-label-ios-s>p{color:var(--ion-color-step-400, var(--ion-text-color-step-600, #999999))}.sc-ion-label-ios-h.in-item-color.sc-ion-label-ios-s>p{color:inherit}.sc-ion-label-ios-s h2:last-child,.sc-ion-label-ios-s h3:last-child,.sc-ion-label-ios-s h4:last-child,.sc-ion-label-ios-s h5:last-child,.sc-ion-label-ios-s h6:last-child,.sc-ion-label-ios-s p:last-child{margin-bottom:0}";

const labelMdCss = ".item.sc-ion-label-md-h,.item .sc-ion-label-md-h{--color:initial;display:block;color:var(--color);font-family:var(--ion-font-family, inherit);font-size:inherit;text-overflow:ellipsis;-webkit-box-sizing:border-box;box-sizing:border-box}.ion-color.sc-ion-label-md-h{color:var(--ion-color-base)}.ion-text-nowrap.sc-ion-label-md-h{overflow:hidden}.item-interactive-disabled.sc-ion-label-md-h:not(.item-multiple-inputs),.item-interactive-disabled:not(.item-multiple-inputs) .sc-ion-label-md-h{cursor:default;opacity:0.3;pointer-events:none}.item-input.sc-ion-label-md-h,.item-input .sc-ion-label-md-h{-ms-flex:initial;flex:initial;max-width:200px;pointer-events:none}.item-textarea.sc-ion-label-md-h,.item-textarea .sc-ion-label-md-h{-ms-flex-item-align:baseline;align-self:baseline}.item-skeleton-text.sc-ion-label-md-h,.item-skeleton-text .sc-ion-label-md-h{overflow:hidden}.label-fixed.sc-ion-label-md-h{-ms-flex:0 0 100px;flex:0 0 100px;width:100px;min-width:100px;max-width:200px}.label-stacked.sc-ion-label-md-h,.label-floating.sc-ion-label-md-h{margin-bottom:0;-ms-flex-item-align:stretch;align-self:stretch;width:auto;max-width:100%}.label-no-animate.label-floating.sc-ion-label-md-h{-webkit-transition:none;transition:none}.sc-ion-label-md-s h1,.sc-ion-label-md-s h2,.sc-ion-label-md-s h3,.sc-ion-label-md-s h4,.sc-ion-label-md-s h5,.sc-ion-label-md-s h6{text-overflow:inherit;overflow:inherit}.ion-text-wrap.sc-ion-label-md-h{line-height:1.5}.label-stacked.sc-ion-label-md-h,.label-floating.sc-ion-label-md-h{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;-webkit-transform-origin:top left;transform-origin:top left}.label-stacked.label-rtl.sc-ion-label-md-h,.label-floating.label-rtl.sc-ion-label-md-h{-webkit-transform-origin:top right;transform-origin:top right}.label-stacked.sc-ion-label-md-h{-webkit-transform:translateY(50%) scale(0.75);transform:translateY(50%) scale(0.75);-webkit-transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1)}.label-floating.sc-ion-label-md-h{-webkit-transform:translateY(96%);transform:translateY(96%);-webkit-transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), transform 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1)}.ion-focused.label-floating.sc-ion-label-md-h,.ion-focused .label-floating.sc-ion-label-md-h,.item-has-focus.label-floating.sc-ion-label-md-h,.item-has-focus .label-floating.sc-ion-label-md-h,.item-has-placeholder.sc-ion-label-md-h:not(.item-input).label-floating,.item-has-placeholder:not(.item-input) .label-floating.sc-ion-label-md-h,.item-has-value.label-floating.sc-ion-label-md-h,.item-has-value .label-floating.sc-ion-label-md-h{-webkit-transform:translateY(50%) scale(0.75);transform:translateY(50%) scale(0.75)}.ion-focused.label-stacked.sc-ion-label-md-h:not(.ion-color),.ion-focused .label-stacked.sc-ion-label-md-h:not(.ion-color),.ion-focused.label-floating.sc-ion-label-md-h:not(.ion-color),.ion-focused .label-floating.sc-ion-label-md-h:not(.ion-color),.item-has-focus.label-stacked.sc-ion-label-md-h:not(.ion-color),.item-has-focus .label-stacked.sc-ion-label-md-h:not(.ion-color),.item-has-focus.label-floating.sc-ion-label-md-h:not(.ion-color),.item-has-focus .label-floating.sc-ion-label-md-h:not(.ion-color){color:var(--ion-color-primary, #0054e9)}.ion-focused.ion-color.label-stacked.sc-ion-label-md-h:not(.ion-color),.ion-focused.ion-color .label-stacked.sc-ion-label-md-h:not(.ion-color),.ion-focused.ion-color.label-floating.sc-ion-label-md-h:not(.ion-color),.ion-focused.ion-color .label-floating.sc-ion-label-md-h:not(.ion-color),.item-has-focus.ion-color.label-stacked.sc-ion-label-md-h:not(.ion-color),.item-has-focus.ion-color .label-stacked.sc-ion-label-md-h:not(.ion-color),.item-has-focus.ion-color.label-floating.sc-ion-label-md-h:not(.ion-color),.item-has-focus.ion-color .label-floating.sc-ion-label-md-h:not(.ion-color){color:var(--ion-color-contrast)}.ion-invalid.ion-touched.label-stacked.sc-ion-label-md-h:not(.ion-color),.ion-invalid.ion-touched .label-stacked.sc-ion-label-md-h:not(.ion-color),.ion-invalid.ion-touched.label-floating.sc-ion-label-md-h:not(.ion-color),.ion-invalid.ion-touched .label-floating.sc-ion-label-md-h:not(.ion-color){color:var(--highlight-color-invalid)}.sc-ion-label-md-s h1{margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px;font-size:1.5rem;font-weight:normal}.sc-ion-label-md-s h2{margin-left:0;margin-right:0;margin-top:2px;margin-bottom:2px;font-size:1rem;font-weight:normal}.sc-ion-label-md-s h3,.sc-ion-label-md-s h4,.sc-ion-label-md-s h5,.sc-ion-label-md-s h6{margin-left:0;margin-right:0;margin-top:2px;margin-bottom:2px;font-size:0.875rem;font-weight:normal;line-height:normal}.sc-ion-label-md-s p{margin-left:0;margin-right:0;margin-top:0;margin-bottom:2px;font-size:0.875rem;line-height:1.25rem;text-overflow:inherit;overflow:inherit}.sc-ion-label-md-s>p{color:var(--ion-color-step-600, var(--ion-text-color-step-400, #666666))}.sc-ion-label-md-h.in-item-color.sc-ion-label-md-s>p{color:inherit}";

const Label = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
        this.ionColor = createEvent(this, "ionColor", 7);
        this.ionStyle = createEvent(this, "ionStyle", 7);
        this.inRange = false;
        this.noAnimate = false;
    }
    componentWillLoad() {
        this.inRange = !!this.el.closest('ion-range');
        this.noAnimate = this.position === 'floating';
        this.emitStyle();
        this.emitColor();
    }
    componentDidLoad() {
        if (this.noAnimate) {
            setTimeout(() => {
                this.noAnimate = false;
            }, 1000);
        }
    }
    colorChanged() {
        this.emitColor();
    }
    positionChanged() {
        this.emitStyle();
    }
    emitColor() {
        const { color } = this;
        this.ionColor.emit({
            'item-label-color': color !== undefined,
            [`ion-color-${color}`]: color !== undefined,
        });
    }
    emitStyle() {
        const { inRange, position } = this;
        // If the label is inside of a range we don't want
        // to override the classes added by the label that
        // is a direct child of the item
        if (!inRange) {
            this.ionStyle.emit({
                label: true,
                [`label-${position}`]: position !== undefined,
            });
        }
    }
    render() {
        const position = this.position;
        const mode = getIonMode(this);
        return (h(Host, { key: 'd6fba1a97189acc8ddfd64a2f009954a3e46e188', class: createColorClasses(this.color, {
                [mode]: true,
                'in-item-color': hostContext('ion-item.ion-color', this.el),
                [`label-${position}`]: position !== undefined,
                [`label-no-animate`]: this.noAnimate,
                'label-rtl': document.dir === 'rtl',
            }) }, h("slot", { key: 'ce0ab50b5700398fdf50f36d02b7ad287eb71481' })));
    }
    get el() { return getElement(this); }
    static get watchers() { return {
        "color": ["colorChanged"],
        "position": ["positionChanged"]
    }; }
};
Label.style = {
    ios: labelIosCss,
    md: labelMdCss
};

const listIosCss = "ion-list{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-left:0;padding-right:0;padding-top:0;padding-bottom:0;display:block;contain:content;list-style-type:none}ion-list.list-inset{-webkit-transform:translateZ(0);transform:translateZ(0);overflow:hidden}.list-ios{background:var(--ion-item-background, var(--ion-background-color, #fff))}.list-ios.list-inset{-webkit-margin-start:16px;margin-inline-start:16px;-webkit-margin-end:16px;margin-inline-end:16px;margin-top:16px;margin-bottom:16px;border-radius:10px}.list-ios.list-inset ion-item:only-child,.list-ios.list-inset ion-item:not(:only-of-type):last-of-type,.list-ios.list-inset ion-item-sliding:last-of-type ion-item{--border-width:0;--inner-border-width:0}.list-ios.list-inset+ion-list.list-inset{margin-top:0}.list-ios-lines-none .item-lines-default{--inner-border-width:0px;--border-width:0px}.list-ios-lines-full .item-lines-default{--inner-border-width:0px;--border-width:0 0 0.55px 0}.list-ios-lines-inset .item-lines-default{--inner-border-width:0 0 0.55px 0;--border-width:0px}ion-card .list-ios{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}";

const listMdCss = "ion-list{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-left:0;padding-right:0;padding-top:0;padding-bottom:0;display:block;contain:content;list-style-type:none}ion-list.list-inset{-webkit-transform:translateZ(0);transform:translateZ(0);overflow:hidden}.list-md{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-left:0;padding-right:0;padding-top:8px;padding-bottom:8px;background:var(--ion-item-background, var(--ion-background-color, #fff))}.list-md>.input:last-child::after{inset-inline-start:0}.list-md.list-inset{-webkit-margin-start:16px;margin-inline-start:16px;-webkit-margin-end:16px;margin-inline-end:16px;margin-top:16px;margin-bottom:16px;border-radius:2px}.list-md.list-inset ion-item:not(:only-of-type):last-of-type,.list-md.list-inset ion-item-sliding:last-of-type ion-item{--border-width:0;--inner-border-width:0}.list-md.list-inset ion-item:only-child{--border-width:0;--inner-border-width:0}.list-md.list-inset+ion-list.list-inset{margin-top:0}.list-md-lines-none .item-lines-default{--inner-border-width:0px;--border-width:0px}.list-md-lines-full .item-lines-default{--inner-border-width:0px;--border-width:0 0 1px 0}.list-md-lines-inset .item-lines-default{--inner-border-width:0 0 1px 0;--border-width:0px}ion-card .list-md{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}";

const List = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
        /**
         * If `true`, the list will have margin around it and rounded corners.
         */
        this.inset = false;
    }
    /**
     * If `ion-item-sliding` are used inside the list, this method closes
     * any open sliding item.
     *
     * Returns `true` if an actual `ion-item-sliding` is closed.
     */
    async closeSlidingItems() {
        const item = this.el.querySelector('ion-item-sliding');
        if (item === null || item === void 0 ? void 0 : item.closeOpened) {
            return item.closeOpened();
        }
        return false;
    }
    render() {
        const mode = getIonMode(this);
        const { lines, inset } = this;
        return (h(Host, { key: '7f9943751542d2cbd49a4ad3f28e16d9949f70d4', role: "list", class: {
                [mode]: true,
                // Used internally for styling
                [`list-${mode}`]: true,
                'list-inset': inset,
                [`list-lines-${lines}`]: lines !== undefined,
                [`list-${mode}-lines-${lines}`]: lines !== undefined,
            } }));
    }
    get el() { return getElement(this); }
};
List.style = {
    ios: listIosCss,
    md: listMdCss
};

const listHeaderIosCss = ":host{--border-style:solid;--border-width:0;--inner-border-width:0;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-left:0;padding-right:0;padding-top:0;padding-bottom:0;display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;width:100%;min-height:40px;border-width:var(--border-width);border-style:var(--border-style);border-color:var(--border-color);background:var(--background);color:var(--color);overflow:hidden}:host(.ion-color){background:var(--ion-color-base);color:var(--ion-color-contrast)}.list-header-inner{display:-ms-flexbox;display:flex;position:relative;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;min-height:inherit;border-width:var(--inner-border-width);border-style:var(--border-style);border-color:var(--border-color);overflow:inherit;-webkit-box-sizing:border-box;box-sizing:border-box}::slotted(ion-label){-ms-flex:1 1 auto;flex:1 1 auto}:host(.list-header-lines-inset),:host(.list-header-lines-none){--border-width:0}:host(.list-header-lines-full),:host(.list-header-lines-none){--inner-border-width:0}:host{--background:transparent;--color:var(--ion-color-step-850, var(--ion-text-color-step-150, #262626));--border-color:var(--ion-item-border-color, var(--ion-border-color, var(--ion-color-step-250, var(--ion-background-color-step-250, #c8c7cc))));padding-right:var(--ion-safe-area-right);padding-left:calc(var(--ion-safe-area-left, 0px) + 16px);position:relative;-ms-flex-align:end;align-items:flex-end;font-size:min(1.375rem, 56.1px);font-weight:700;letter-spacing:0}:host-context([dir=rtl]){padding-right:calc(var(--ion-safe-area-right, 0px) + 16px);padding-left:var(--ion-safe-area-left)}@supports selector(:dir(rtl)){:host(:dir(rtl)){padding-right:calc(var(--ion-safe-area-right, 0px) + 16px);padding-left:var(--ion-safe-area-left)}}::slotted(ion-button),::slotted(ion-label){margin-top:29px;margin-bottom:6px}::slotted(ion-button){--padding-top:0;--padding-bottom:0;-webkit-margin-start:3px;margin-inline-start:3px;-webkit-margin-end:3px;margin-inline-end:3px;min-height:1.4em}:host(.list-header-lines-full){--border-width:0 0 0.55px 0}:host(.list-header-lines-inset){--inner-border-width:0 0 0.55px 0}";

const listHeaderMdCss = ":host{--border-style:solid;--border-width:0;--inner-border-width:0;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;padding-left:0;padding-right:0;padding-top:0;padding-bottom:0;display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;width:100%;min-height:40px;border-width:var(--border-width);border-style:var(--border-style);border-color:var(--border-color);background:var(--background);color:var(--color);overflow:hidden}:host(.ion-color){background:var(--ion-color-base);color:var(--ion-color-contrast)}.list-header-inner{display:-ms-flexbox;display:flex;position:relative;-ms-flex:1;flex:1;-ms-flex-direction:inherit;flex-direction:inherit;-ms-flex-align:inherit;align-items:inherit;-ms-flex-item-align:stretch;align-self:stretch;min-height:inherit;border-width:var(--inner-border-width);border-style:var(--border-style);border-color:var(--border-color);overflow:inherit;-webkit-box-sizing:border-box;box-sizing:border-box}::slotted(ion-label){-ms-flex:1 1 auto;flex:1 1 auto}:host(.list-header-lines-inset),:host(.list-header-lines-none){--border-width:0}:host(.list-header-lines-full),:host(.list-header-lines-none){--inner-border-width:0}:host{--background:transparent;--color:var(--ion-text-color, #000);--border-color:var(--ion-item-border-color, var(--ion-border-color, var(--ion-color-step-150, var(--ion-background-color-step-150, rgba(0, 0, 0, 0.13)))));padding-right:var(--ion-safe-area-right);padding-left:calc(var(--ion-safe-area-left, 0px) + 16px);min-height:45px;font-size:0.875rem}:host-context([dir=rtl]){padding-right:calc(var(--ion-safe-area-right, 0px) + 16px);padding-left:var(--ion-safe-area-left)}@supports selector(:dir(rtl)){:host(:dir(rtl)){padding-right:calc(var(--ion-safe-area-right, 0px) + 16px);padding-left:var(--ion-safe-area-left)}}:host(.list-header-lines-full){--border-width:0 0 1px 0}:host(.list-header-lines-inset){--inner-border-width:0 0 1px 0}";

const ListHeader = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
    }
    render() {
        const { lines } = this;
        const mode = getIonMode(this);
        return (h(Host, { key: 'd9bc827ad8cc77231efddc2435831a7fc080f77d', class: createColorClasses(this.color, {
                [mode]: true,
                [`list-header-lines-${lines}`]: lines !== undefined,
            }) }, h("div", { key: '02dd9698304a7b2997ea1487e2f308bebea2b44c', class: "list-header-inner" }, h("slot", { key: '01d63a572c003286ae467a1ab23631e37e695042' }))));
    }
};
ListHeader.style = {
    ios: listHeaderIosCss,
    md: listHeaderMdCss
};

const noteIosCss = ":host{color:var(--color);font-family:var(--ion-font-family, inherit);-webkit-box-sizing:border-box;box-sizing:border-box}:host(.ion-color){color:var(--ion-color-base)}:host{--color:var(--ion-color-step-350, var(--ion-text-color-step-650, #a6a6a6));font-size:max(14px, 1rem)}";

const noteMdCss = ":host{color:var(--color);font-family:var(--ion-font-family, inherit);-webkit-box-sizing:border-box;box-sizing:border-box}:host(.ion-color){color:var(--ion-color-base)}:host{--color:var(--ion-color-step-600, var(--ion-text-color-step-400, #666666));font-size:0.875rem}";

const Note = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
    }
    render() {
        const mode = getIonMode(this);
        return (h(Host, { key: '0ec2ef7367d867fd7588611953f696eecdf3221e', class: createColorClasses(this.color, {
                [mode]: true,
            }) }, h("slot", { key: 'a200b94ddffb29cf6dabe6e984220930ea7efdef' })));
    }
};
Note.style = {
    ios: noteIosCss,
    md: noteMdCss
};

const skeletonTextCss = ":host{--background:rgba(var(--background-rgb, var(--ion-text-color-rgb, 0, 0, 0)), 0.065);border-radius:var(--border-radius, inherit);display:block;width:100%;height:inherit;margin-top:4px;margin-bottom:4px;background:var(--background);line-height:10px;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;pointer-events:none}span{display:inline-block}:host(.in-media){margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;height:100%}:host(.skeleton-text-animated){position:relative;background:-webkit-gradient(linear, left top, right top, color-stop(8%, rgba(var(--background-rgb, var(--ion-text-color-rgb, 0, 0, 0)), 0.065)), color-stop(18%, rgba(var(--background-rgb, var(--ion-text-color-rgb, 0, 0, 0)), 0.135)), color-stop(33%, rgba(var(--background-rgb, var(--ion-text-color-rgb, 0, 0, 0)), 0.065)));background:linear-gradient(to right, rgba(var(--background-rgb, var(--ion-text-color-rgb, 0, 0, 0)), 0.065) 8%, rgba(var(--background-rgb, var(--ion-text-color-rgb, 0, 0, 0)), 0.135) 18%, rgba(var(--background-rgb, var(--ion-text-color-rgb, 0, 0, 0)), 0.065) 33%);background-size:800px 104px;-webkit-animation-duration:1s;animation-duration:1s;-webkit-animation-fill-mode:forwards;animation-fill-mode:forwards;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;-webkit-animation-name:shimmer;animation-name:shimmer;-webkit-animation-timing-function:linear;animation-timing-function:linear}@-webkit-keyframes shimmer{0%{background-position:-400px 0}100%{background-position:400px 0}}@keyframes shimmer{0%{background-position:-400px 0}100%{background-position:400px 0}}";

const SkeletonText = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
        this.ionStyle = createEvent(this, "ionStyle", 7);
        /**
         * If `true`, the skeleton text will animate.
         */
        this.animated = false;
    }
    componentWillLoad() {
        this.emitStyle();
    }
    emitStyle() {
        // The emitted property is used by item in order
        // to add the item-skeleton-text class which applies
        // overflow: hidden to its label
        const style = {
            'skeleton-text': true,
        };
        this.ionStyle.emit(style);
    }
    render() {
        const animated = this.animated && config.getBoolean('animated', true);
        const inMedia = hostContext('ion-avatar', this.el) || hostContext('ion-thumbnail', this.el);
        const mode = getIonMode(this);
        return (h(Host, { key: 'd86ef7392507cdbf48dfd3a71f02d7a83eda4aae', class: {
                [mode]: true,
                'skeleton-text-animated': animated,
                'in-media': inMedia,
            } }, h("span", { key: '8e8b5a232a6396d2bba691b05f9de4da44b2965c' }, "\u00A0")));
    }
    get el() { return getElement(this); }
};
SkeletonText.style = skeletonTextCss;

export { Item as ion_item, ItemDivider as ion_item_divider, ItemGroup as ion_item_group, Label as ion_label, List as ion_list, ListHeader as ion_list_header, Note as ion_note, SkeletonText as ion_skeleton_text };
