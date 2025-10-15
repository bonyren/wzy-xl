/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Build, Host, h } from "@stencil/core";
import { attachComponent } from "../../utils/framework-delegate";
import { printIonError } from "../../utils/logging/index";
export class Tab {
    constructor() {
        this.loaded = false;
        /** @internal */
        this.active = false;
    }
    async componentWillLoad() {
        if (Build.isDev) {
            if (this.component !== undefined && this.el.childElementCount > 0) {
                printIonError('[ion-tab] - You can not use a lazy-loaded component in a tab and inlined content at the same time.' +
                    `- Remove the component attribute in: <ion-tab component="${this.component}">` +
                    ` or` +
                    `- Remove the embedded content inside the ion-tab: <ion-tab></ion-tab>`);
            }
        }
        if (this.active) {
            await this.setActive();
        }
    }
    /** Set the active component for the tab */
    async setActive() {
        await this.prepareLazyLoaded();
        this.active = true;
    }
    changeActive(isActive) {
        if (isActive) {
            this.prepareLazyLoaded();
        }
    }
    prepareLazyLoaded() {
        if (!this.loaded && this.component != null) {
            this.loaded = true;
            try {
                return attachComponent(this.delegate, this.el, this.component, ['ion-page']);
            }
            catch (e) {
                printIonError('[ion-tab] - Exception in prepareLazyLoaded:', e);
            }
        }
        return Promise.resolve(undefined);
    }
    render() {
        const { tab, active, component } = this;
        return (h(Host, { key: 'dbad8fe9f1566277d14647626308eaf1601ab01f', role: "tabpanel", "aria-hidden": !active ? 'true' : null, "aria-labelledby": `tab-button-${tab}`, class: {
                'ion-page': component === undefined,
                'tab-hidden': !active,
            } }, h("slot", { key: '3be64f4e7161f6769aaf8e4dcb5293fcaa09af45' })));
    }
    static get is() { return "ion-tab"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "$": ["tab.scss"]
        };
    }
    static get styleUrls() {
        return {
            "$": ["tab.css"]
        };
    }
    static get properties() {
        return {
            "active": {
                "type": "boolean",
                "attribute": "active",
                "mutable": true,
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
            "tab": {
                "type": "string",
                "attribute": "tab",
                "mutable": false,
                "complexType": {
                    "original": "string",
                    "resolved": "string",
                    "references": {}
                },
                "required": true,
                "optional": false,
                "docs": {
                    "tags": [],
                    "text": "A tab id must be provided for each `ion-tab`. It's used internally to reference\nthe selected tab or by the router to switch between them."
                },
                "getter": false,
                "setter": false,
                "reflect": false
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
                    "text": "The component to display inside of the tab."
                },
                "getter": false,
                "setter": false,
                "reflect": false
            }
        };
    }
    static get methods() {
        return {
            "setActive": {
                "complexType": {
                    "signature": "() => Promise<void>",
                    "parameters": [],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        }
                    },
                    "return": "Promise<void>"
                },
                "docs": {
                    "text": "Set the active component for the tab",
                    "tags": []
                }
            }
        };
    }
    static get elementRef() { return "el"; }
    static get watchers() {
        return [{
                "propName": "active",
                "methodName": "changeActive"
            }];
    }
}
