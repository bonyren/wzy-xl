/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
'use strict';

var index = require('./index-DNh170BW.js');
var ionicGlobal = require('./ionic-global-UI5YPSi-.js');
var theme = require('./theme-CeDs6Hcv.js');

const avatarIosCss = ":host{border-radius:var(--border-radius);display:block}::slotted(ion-img),::slotted(img){border-radius:var(--border-radius);width:100%;height:100%;-o-object-fit:cover;object-fit:cover;overflow:hidden}:host{--border-radius:50%;width:48px;height:48px}";

const avatarMdCss = ":host{border-radius:var(--border-radius);display:block}::slotted(ion-img),::slotted(img){border-radius:var(--border-radius);width:100%;height:100%;-o-object-fit:cover;object-fit:cover;overflow:hidden}:host{--border-radius:50%;width:64px;height:64px}";

const Avatar = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
    }
    render() {
        return (index.h(index.Host, { key: '998217066084f966bf5d356fed85bcbd451f675a', class: ionicGlobal.getIonMode(this) }, index.h("slot", { key: '1a6f7c9d4dc6a875f86b5b3cda6d59cb39587f22' })));
    }
};
Avatar.style = {
    ios: avatarIosCss,
    md: avatarMdCss
};

const badgeIosCss = ":host{--background:var(--ion-color-primary, #0054e9);--color:var(--ion-color-primary-contrast, #fff);--padding-top:3px;--padding-end:8px;--padding-bottom:3px;--padding-start:8px;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;-webkit-padding-start:var(--padding-start);padding-inline-start:var(--padding-start);-webkit-padding-end:var(--padding-end);padding-inline-end:var(--padding-end);padding-top:var(--padding-top);padding-bottom:var(--padding-bottom);display:inline-block;min-width:10px;background:var(--background);color:var(--color);font-family:var(--ion-font-family, inherit);font-size:0.8125rem;font-weight:bold;line-height:1;text-align:center;white-space:nowrap;contain:content;vertical-align:baseline}:host(.ion-color){background:var(--ion-color-base);color:var(--ion-color-contrast)}:host(:empty){display:none}:host{border-radius:10px;font-size:max(13px, 0.8125rem)}";

const badgeMdCss = ":host{--background:var(--ion-color-primary, #0054e9);--color:var(--ion-color-primary-contrast, #fff);--padding-top:3px;--padding-end:8px;--padding-bottom:3px;--padding-start:8px;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;-webkit-padding-start:var(--padding-start);padding-inline-start:var(--padding-start);-webkit-padding-end:var(--padding-end);padding-inline-end:var(--padding-end);padding-top:var(--padding-top);padding-bottom:var(--padding-bottom);display:inline-block;min-width:10px;background:var(--background);color:var(--color);font-family:var(--ion-font-family, inherit);font-size:0.8125rem;font-weight:bold;line-height:1;text-align:center;white-space:nowrap;contain:content;vertical-align:baseline}:host(.ion-color){background:var(--ion-color-base);color:var(--ion-color-contrast)}:host(:empty){display:none}:host{--padding-top:3px;--padding-end:4px;--padding-bottom:4px;--padding-start:4px;border-radius:4px}";

const Badge = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
    }
    render() {
        const mode = ionicGlobal.getIonMode(this);
        return (index.h(index.Host, { key: '1a2d39c5deec771a2f2196447627b62a7d4c8389', class: theme.createColorClasses(this.color, {
                [mode]: true,
            }) }, index.h("slot", { key: 'fc1b6587f1ed24715748eb6785e7fb7a57cdd5cd' })));
    }
};
Badge.style = {
    ios: badgeIosCss,
    md: badgeMdCss
};

const thumbnailCss = ":host{--size:48px;--border-radius:0;border-radius:var(--border-radius);display:block;width:var(--size);height:var(--size)}::slotted(ion-img),::slotted(img){border-radius:var(--border-radius);width:100%;height:100%;-o-object-fit:cover;object-fit:cover;overflow:hidden}";

const Thumbnail = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
    }
    render() {
        return (index.h(index.Host, { key: '70ada828e8cf541ab3b47f94b7e56ce34114ef88', class: ionicGlobal.getIonMode(this) }, index.h("slot", { key: 'c43e105669d2bae123619b616f3af8ca2f722d61' })));
    }
};
Thumbnail.style = thumbnailCss;

exports.ion_avatar = Avatar;
exports.ion_badge = Badge;
exports.ion_thumbnail = Thumbnail;
