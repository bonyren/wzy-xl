/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { ENABLE_HTML_CONTENT_DEFAULT } from "../../utils/config";
import { sanitizeDOMString } from "../../utils/sanitization/index";
import { arrowDown, caretBackSharp } from "ionicons/icons";
import { config } from "../../global/config";
import { getIonMode } from "../../global/ionic-global";
import { supportsRubberBandScrolling } from "../refresher/refresher.utils";
import { SPINNERS } from "../spinner/spinner-configs";
export class RefresherContent {
    constructor() {
        this.customHTMLEnabled = config.get('innerHTMLTemplatesEnabled', ENABLE_HTML_CONTENT_DEFAULT);
    }
    componentWillLoad() {
        if (this.pullingIcon === undefined) {
            /**
             * The native iOS refresher uses a spinner instead of
             * an icon, so we need to see if this device supports
             * the native iOS refresher.
             */
            const hasRubberBandScrolling = supportsRubberBandScrolling();
            const mode = getIonMode(this);
            const overflowRefresher = hasRubberBandScrolling ? 'lines' : arrowDown;
            this.pullingIcon = config.get('refreshingIcon', mode === 'ios' && hasRubberBandScrolling ? config.get('spinner', overflowRefresher) : 'circular');
        }
        if (this.refreshingSpinner === undefined) {
            const mode = getIonMode(this);
            this.refreshingSpinner = config.get('refreshingSpinner', config.get('spinner', mode === 'ios' ? 'lines' : 'circular'));
        }
    }
    renderPullingText() {
        const { customHTMLEnabled, pullingText } = this;
        if (customHTMLEnabled) {
            return h("div", { class: "refresher-pulling-text", innerHTML: sanitizeDOMString(pullingText) });
        }
        return h("div", { class: "refresher-pulling-text" }, pullingText);
    }
    renderRefreshingText() {
        const { customHTMLEnabled, refreshingText } = this;
        if (customHTMLEnabled) {
            return h("div", { class: "refresher-refreshing-text", innerHTML: sanitizeDOMString(refreshingText) });
        }
        return h("div", { class: "refresher-refreshing-text" }, refreshingText);
    }
    render() {
        const pullingIcon = this.pullingIcon;
        const hasSpinner = pullingIcon != null && SPINNERS[pullingIcon] !== undefined;
        const mode = getIonMode(this);
        return (h(Host, { key: 'e235f8a9a84070ece2e2066ced234a64663bfa1d', class: mode }, h("div", { key: '9121691818ddaa35801a5f442e144ac27686cf19', class: "refresher-pulling" }, this.pullingIcon && hasSpinner && (h("div", { key: 'c8d65d740f1575041bd3b752c789077927397fe4', class: "refresher-pulling-icon" }, h("div", { key: '309dd904977eaa788b09ea95b7fa4996a73bec5b', class: "spinner-arrow-container" }, h("ion-spinner", { key: 'a2a1480f67775d56ca7822e76be1e9f983bca2f9', name: this.pullingIcon, paused: true }), mode === 'md' && this.pullingIcon === 'circular' && (h("div", { key: '811d7e06d324bf4b6a18a31427a43e5177f3ae3a', class: "arrow-container" }, h("ion-icon", { key: '86cc48e2e8dc054ff6ff1299094da35b524be63d', icon: caretBackSharp, "aria-hidden": "true" })))))), this.pullingIcon && !hasSpinner && (h("div", { key: '464ae097dbc95c18a2dd7dfd03f8489153dab719', class: "refresher-pulling-icon" }, h("ion-icon", { key: 'ed6875978b9035add562caa743a68353743d978f', icon: this.pullingIcon, lazy: false, "aria-hidden": "true" }))), this.pullingText !== undefined && this.renderPullingText()), h("div", { key: 'aff891924e44354543fec484e5cde1ca92e69904', class: "refresher-refreshing" }, this.refreshingSpinner && (h("div", { key: '842d7ac4ff10a1058775493d62f31cbdcd34f7a0', class: "refresher-refreshing-icon" }, h("ion-spinner", { key: '8c3e6195501e7e78d5cde1e3ad1fef90fd4a953f', name: this.refreshingSpinner }))), this.refreshingText !== undefined && this.renderRefreshingText())));
    }
    static get is() { return "ion-refresher-content"; }
    static get properties() {
        return {
            "pullingIcon": {
                "type": "string",
                "attribute": "pulling-icon",
                "mutable": true,
                "complexType": {
                    "original": "SpinnerTypes | string | null",
                    "resolved": "null | string | undefined",
                    "references": {
                        "SpinnerTypes": {
                            "location": "import",
                            "path": "../spinner/spinner-configs",
                            "id": "src/components/spinner/spinner-configs.ts::SpinnerTypes"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "A static icon or a spinner to display when you begin to pull down.\nA spinner name can be provided to gradually show tick marks\nwhen pulling down on iOS devices."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "pullingText": {
                "type": "string",
                "attribute": "pulling-text",
                "mutable": false,
                "complexType": {
                    "original": "string | IonicSafeString",
                    "resolved": "IonicSafeString | string | undefined",
                    "references": {
                        "IonicSafeString": {
                            "location": "import",
                            "path": "../../utils/sanitization",
                            "id": "src/utils/sanitization/index.ts::IonicSafeString"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The text you want to display when you begin to pull down.\n`pullingText` can accept either plaintext or HTML as a string.\nTo display characters normally reserved for HTML, they\nmust be escaped. For example `<Ionic>` would become\n`&lt;Ionic&gt;`\n\nFor more information: [Security Documentation](https://ionicframework.com/docs/faq/security)\n\nContent is parsed as plaintext by default.\n`innerHTMLTemplatesEnabled` must be set to `true` in the Ionic config\nbefore custom HTML can be used."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "refreshingSpinner": {
                "type": "string",
                "attribute": "refreshing-spinner",
                "mutable": true,
                "complexType": {
                    "original": "SpinnerTypes | null",
                    "resolved": "\"bubbles\" | \"circles\" | \"circular\" | \"crescent\" | \"dots\" | \"lines\" | \"lines-sharp\" | \"lines-sharp-small\" | \"lines-small\" | null | undefined",
                    "references": {
                        "SpinnerTypes": {
                            "location": "import",
                            "path": "../spinner/spinner-configs",
                            "id": "src/components/spinner/spinner-configs.ts::SpinnerTypes"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "An animated SVG spinner that shows when refreshing begins"
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "refreshingText": {
                "type": "string",
                "attribute": "refreshing-text",
                "mutable": false,
                "complexType": {
                    "original": "string | IonicSafeString",
                    "resolved": "IonicSafeString | string | undefined",
                    "references": {
                        "IonicSafeString": {
                            "location": "import",
                            "path": "../../utils/sanitization",
                            "id": "src/utils/sanitization/index.ts::IonicSafeString"
                        }
                    }
                },
                "required": false,
                "optional": true,
                "docs": {
                    "tags": [],
                    "text": "The text you want to display when performing a refresh.\n`refreshingText` can accept either plaintext or HTML as a string.\nTo display characters normally reserved for HTML, they\nmust be escaped. For example `<Ionic>` would become\n`&lt;Ionic&gt;`\n\nFor more information: [Security Documentation](https://ionicframework.com/docs/faq/security)\n\nContent is parsed as plaintext by default.\n`innerHTMLTemplatesEnabled` must be set to `true` in the Ionic config\nbefore custom HTML can be used."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            }
        };
    }
    static get elementRef() { return "el"; }
}
