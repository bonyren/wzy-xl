/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
'use strict';

var index = require('./index-DNh170BW.js');
var ionicGlobal = require('./ionic-global-UI5YPSi-.js');
var overlays = require('./overlays-CglR7j-u.js');
var theme = require('./theme-CeDs6Hcv.js');
require('./index-DkNv4J_i.js');
require('./helpers-DgwmcYAu.js');
require('./hardware-back-button-BxdNu76F.js');
require('./framework-delegate-WkyjrnCx.js');
require('./gesture-controller-dtqlP_q4.js');

const ionicSelectModalMdCss = ".sc-ion-select-modal-ionic-h{height:100%}ion-list.sc-ion-select-modal-ionic ion-radio.sc-ion-select-modal-ionic::part(container),ion-list.sc-ion-select-modal-ionic ion-radio.sc-ion-select-modal-ionic [part~=\"container\"]{display:none}ion-list.sc-ion-select-modal-ionic ion-radio.sc-ion-select-modal-ionic::part(label),ion-list.sc-ion-select-modal-ionic ion-radio.sc-ion-select-modal-ionic [part~=\"label\"]{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}ion-item.sc-ion-select-modal-ionic{--inner-border-width:0}.item-radio-checked.sc-ion-select-modal-ionic{--background:rgba(var(--ion-color-primary-rgb, 0, 84, 233), 0.08);--background-focused:var(--ion-color-primary, #0054e9);--background-focused-opacity:0.2;--background-hover:var(--ion-color-primary, #0054e9);--background-hover-opacity:0.12}.item-checkbox-checked.sc-ion-select-modal-ionic{--background-activated:var(--ion-item-color, var(--ion-text-color, #000));--background-focused:var(--ion-item-color, var(--ion-text-color, #000));--background-hover:var(--ion-item-color, var(--ion-text-color, #000));--color:var(--ion-color-primary, #0054e9)}";

const selectModalIosCss = ".sc-ion-select-modal-ios-h{height:100%}ion-item.sc-ion-select-modal-ios{--inner-padding-end:0}ion-radio.sc-ion-select-modal-ios::after{bottom:0;position:absolute;width:calc(100% - 0.9375rem - 16px);border-width:0px 0px 0.55px 0px;border-style:solid;border-color:var(--ion-item-border-color, var(--ion-border-color, var(--ion-color-step-250, var(--ion-background-color-step-250, #c8c7cc))));content:\"\"}ion-radio.sc-ion-select-modal-ios::after{inset-inline-start:calc(0.9375rem + 16px)}";

const selectModalMdCss = ".sc-ion-select-modal-md-h{height:100%}ion-list.sc-ion-select-modal-md ion-radio.sc-ion-select-modal-md::part(container),ion-list.sc-ion-select-modal-md ion-radio.sc-ion-select-modal-md [part~=\"container\"]{display:none}ion-list.sc-ion-select-modal-md ion-radio.sc-ion-select-modal-md::part(label),ion-list.sc-ion-select-modal-md ion-radio.sc-ion-select-modal-md [part~=\"label\"]{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}ion-item.sc-ion-select-modal-md{--inner-border-width:0}.item-radio-checked.sc-ion-select-modal-md{--background:rgba(var(--ion-color-primary-rgb, 0, 84, 233), 0.08);--background-focused:var(--ion-color-primary, #0054e9);--background-focused-opacity:0.2;--background-hover:var(--ion-color-primary, #0054e9);--background-hover-opacity:0.12}.item-checkbox-checked.sc-ion-select-modal-md{--background-activated:var(--ion-item-color, var(--ion-text-color, #000));--background-focused:var(--ion-item-color, var(--ion-text-color, #000));--background-hover:var(--ion-item-color, var(--ion-text-color, #000));--color:var(--ion-color-primary, #0054e9)}";

const SelectModal = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
        this.options = [];
    }
    closeModal() {
        const modal = this.el.closest('ion-modal');
        if (modal) {
            modal.dismiss();
        }
    }
    findOptionFromEvent(ev) {
        const { options } = this;
        return options.find((o) => o.value === ev.target.value);
    }
    getValues(ev) {
        const { multiple, options } = this;
        if (multiple) {
            // this is a modal with checkboxes (multiple value select)
            // return an array of all the checked values
            return options.filter((o) => o.checked).map((o) => o.value);
        }
        // this is a modal with radio buttons (single value select)
        // return the value that was clicked, otherwise undefined
        const option = ev ? this.findOptionFromEvent(ev) : null;
        return option ? option.value : undefined;
    }
    callOptionHandler(ev) {
        const option = this.findOptionFromEvent(ev);
        const values = this.getValues(ev);
        if (option === null || option === void 0 ? void 0 : option.handler) {
            overlays.safeCall(option.handler, values);
        }
    }
    setChecked(ev) {
        const { multiple } = this;
        const option = this.findOptionFromEvent(ev);
        // this is a modal with checkboxes (multiple value select)
        // we need to set the checked value for this option
        if (multiple && option) {
            option.checked = ev.detail.checked;
        }
    }
    renderRadioOptions() {
        const checked = this.options.filter((o) => o.checked).map((o) => o.value)[0];
        return (index.h("ion-radio-group", { value: checked, onIonChange: (ev) => this.callOptionHandler(ev) }, this.options.map((option) => (index.h("ion-item", { lines: "none", class: Object.assign({
                // TODO FW-4784
                'item-radio-checked': option.value === checked
            }, theme.getClassMap(option.cssClass)) }, index.h("ion-radio", { value: option.value, disabled: option.disabled, justify: "start", labelPlacement: "end", onClick: () => this.closeModal(), onKeyUp: (ev) => {
                if (ev.key === ' ') {
                    /**
                     * Selecting a radio option with keyboard navigation,
                     * either through the Enter or Space keys, should
                     * dismiss the modal.
                     */
                    this.closeModal();
                }
            } }, option.text))))));
    }
    renderCheckboxOptions() {
        return this.options.map((option) => (index.h("ion-item", { class: Object.assign({
                // TODO FW-4784
                'item-checkbox-checked': option.checked
            }, theme.getClassMap(option.cssClass)) }, index.h("ion-checkbox", { value: option.value, disabled: option.disabled, checked: option.checked, justify: "start", labelPlacement: "end", onIonChange: (ev) => {
                this.setChecked(ev);
                this.callOptionHandler(ev);
                // TODO FW-4784
                index.forceUpdate(this);
            } }, option.text))));
    }
    render() {
        return (index.h(index.Host, { key: 'b6c0dec240b2e41985b15fdf4e5a6d3a145c1567', class: ionicGlobal.getIonMode(this) }, index.h("ion-header", { key: 'cd177e85ee0f62a60a3a708342d6ab6eb19a44dc' }, index.h("ion-toolbar", { key: 'aee8222a5a4daa540ad202b2e4cac1ef93d9558c' }, this.header !== undefined && index.h("ion-title", { key: '5f8fecc764d97bf840d3d4cfddeeccd118ab4436' }, this.header), index.h("ion-buttons", { key: '919033950d7c2b0101f96a9c9698219de9f568ea', slot: "end" }, index.h("ion-button", { key: '34b571cab6dced4bde555a077a21e91800829931', onClick: () => this.closeModal() }, "Close")))), index.h("ion-content", { key: '3c9153d26ba7a5a03d3b20fcd628d0c3031661a7' }, index.h("ion-list", { key: 'e00b222c071bc97c82ad1bba4db95a8a5c43ed6d' }, this.multiple === true ? this.renderCheckboxOptions() : this.renderRadioOptions()))));
    }
    get el() { return index.getElement(this); }
};
SelectModal.style = {
    ionic: ionicSelectModalMdCss,
    ios: selectModalIosCss,
    md: selectModalMdCss
};

exports.ion_select_modal = SelectModal;
