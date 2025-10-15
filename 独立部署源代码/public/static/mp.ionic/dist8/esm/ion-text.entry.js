/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { r as registerInstance, h, d as Host } from './index-4DxY6_gG.js';
import { c as createColorClasses } from './theme-DiVJyqlX.js';
import { b as getIonMode } from './ionic-global-CTSyufhF.js';

const textCss = ":host(.ion-color){color:var(--ion-color-base)}";

const Text = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
    }
    render() {
        const mode = getIonMode(this);
        return (h(Host, { key: '361035eae7b92dc109794348d39bad2f596eb6be', class: createColorClasses(this.color, {
                [mode]: true,
            }) }, h("slot", { key: 'c7b8835cf485ba9ecd73298f0529276ce1ea0852' })));
    }
};
Text.style = textCss;

export { Text as ion_text };
