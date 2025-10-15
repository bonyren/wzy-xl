/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { ENABLE_HTML_CONTENT_DEFAULT } from "../../utils/config";
import { sanitizeDOMString } from "../../utils/sanitization/index";
import { config } from "../../global/config";
import { getIonMode } from "../../global/ionic-global";
export class InfiniteScrollContent {
    constructor() {
        this.customHTMLEnabled = config.get('innerHTMLTemplatesEnabled', ENABLE_HTML_CONTENT_DEFAULT);
    }
    componentDidLoad() {
        if (this.loadingSpinner === undefined) {
            const mode = getIonMode(this);
            this.loadingSpinner = config.get('infiniteLoadingSpinner', config.get('spinner', mode === 'ios' ? 'lines' : 'crescent'));
        }
    }
    renderLoadingText() {
        const { customHTMLEnabled, loadingText } = this;
        if (customHTMLEnabled) {
            return h("div", { class: "infinite-loading-text", innerHTML: sanitizeDOMString(loadingText) });
        }
        return h("div", { class: "infinite-loading-text" }, this.loadingText);
    }
    render() {
        const mode = getIonMode(this);
        return (h(Host, { key: '7c16060dcfe2a0b0fb3e2f8f4c449589a76f1baa', class: {
                [mode]: true,
                // Used internally for styling
                [`infinite-scroll-content-${mode}`]: true,
            } }, h("div", { key: 'a94f4d8746e053dc718f97520bd7e48cb316443a', class: "infinite-loading" }, this.loadingSpinner && (h("div", { key: '10143d5d2a50a2a2bc5de1cee8e7ab51263bcf23', class: "infinite-loading-spinner" }, h("ion-spinner", { key: '8846e88191690d9c61a0b462889ed56fbfed8b0d', name: this.loadingSpinner }))), this.loadingText !== undefined && this.renderLoadingText())));
    }
    static get is() { return "ion-infinite-scroll-content"; }
    static get originalStyleUrls() {
        return {
            "ios": ["infinite-scroll-content.ios.scss"],
            "md": ["infinite-scroll-content.md.scss"]
        };
    }
    static get styleUrls() {
        return {
            "ios": ["infinite-scroll-content.ios.css"],
            "md": ["infinite-scroll-content.md.css"]
        };
    }
    static get properties() {
        return {
            "loadingSpinner": {
                "type": "string",
                "attribute": "loading-spinner",
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
                    "text": "An animated SVG spinner that shows while loading."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            },
            "loadingText": {
                "type": "string",
                "attribute": "loading-text",
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
                    "text": "Optional text to display while loading.\n`loadingText` can accept either plaintext or HTML as a string.\nTo display characters normally reserved for HTML, they\nmust be escaped. For example `<Ionic>` would become\n`&lt;Ionic&gt;`\n\nFor more information: [Security Documentation](https://ionicframework.com/docs/faq/security)\n\nThis property accepts custom HTML as a string.\nContent is parsed as plaintext by default.\n`innerHTMLTemplatesEnabled` must be set to `true` in the Ionic config\nbefore custom HTML can be used."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            }
        };
    }
}
