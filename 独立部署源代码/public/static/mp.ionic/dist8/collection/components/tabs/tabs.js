/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { printIonError } from "../../utils/logging/index";
/**
 * @slot - Content is placed between the named slots if provided without a slot.
 * @slot top - Content is placed at the top of the screen.
 * @slot bottom - Content is placed at the bottom of the screen.
 */
export class Tabs {
    constructor() {
        this.transitioning = false;
        /** @internal */
        this.useRouter = false;
        this.onTabClicked = (ev) => {
            const { href, tab } = ev.detail;
            if (this.useRouter && href !== undefined) {
                const router = document.querySelector('ion-router');
                if (router) {
                    router.push(href);
                }
            }
            else {
                this.select(tab);
            }
        };
    }
    async componentWillLoad() {
        if (!this.useRouter) {
            /**
             * JavaScript and StencilJS use `ion-router`, while
             * the other frameworks use `ion-router-outlet`.
             *
             * If either component is present then tabs will not use
             * a basic tab-based navigation. It will use the history
             * stack or URL updates associated with the router.
             */
            this.useRouter =
                (!!this.el.querySelector('ion-router-outlet') || !!document.querySelector('ion-router')) &&
                    !this.el.closest('[no-router]');
        }
        if (!this.useRouter) {
            const tabs = this.tabs;
            if (tabs.length > 0) {
                await this.select(tabs[0]);
            }
        }
        this.ionNavWillLoad.emit();
    }
    componentWillRender() {
        const tabBar = this.el.querySelector('ion-tab-bar');
        if (tabBar) {
            let tab = this.selectedTab ? this.selectedTab.tab : undefined;
            // Fallback: if no selectedTab is set but we're using router mode,
            // determine the active tab from the current URL. This works around
            // timing issues in React Router integration where setRouteId may not
            // be called in time for the initial render.
            // TODO(FW-6724): Remove this with React Router upgrade
            if (!tab && this.useRouter && typeof window !== 'undefined') {
                const currentPath = window.location.pathname;
                const tabButtons = this.el.querySelectorAll('ion-tab-button');
                // Look for a tab button that matches the current path pattern
                for (const tabButton of tabButtons) {
                    const tabId = tabButton.getAttribute('tab');
                    if (tabId && currentPath.includes(tabId)) {
                        tab = tabId;
                        break;
                    }
                }
            }
            tabBar.selectedTab = tab;
        }
    }
    /**
     * Select a tab by the value of its `tab` property or an element reference. This method is only available for vanilla JavaScript projects. The Angular, React, and Vue implementations of tabs are coupled to each framework's router.
     *
     * @param tab The tab instance to select. If passed a string, it should be the value of the tab's `tab` property.
     */
    async select(tab) {
        const selectedTab = getTab(this.tabs, tab);
        if (!this.shouldSwitch(selectedTab)) {
            return false;
        }
        await this.setActive(selectedTab);
        await this.notifyRouter();
        this.tabSwitch();
        return true;
    }
    /**
     * Get a specific tab by the value of its `tab` property or an element reference. This method is only available for vanilla JavaScript projects. The Angular, React, and Vue implementations of tabs are coupled to each framework's router.
     *
     * @param tab The tab instance to select. If passed a string, it should be the value of the tab's `tab` property.
     */
    async getTab(tab) {
        return getTab(this.tabs, tab);
    }
    /**
     * Get the currently selected tab. This method is only available for vanilla JavaScript projects. The Angular, React, and Vue implementations of tabs are coupled to each framework's router.
     */
    getSelected() {
        return Promise.resolve(this.selectedTab ? this.selectedTab.tab : undefined);
    }
    /** @internal */
    async setRouteId(id) {
        const selectedTab = getTab(this.tabs, id);
        if (!this.shouldSwitch(selectedTab)) {
            return { changed: false, element: this.selectedTab };
        }
        await this.setActive(selectedTab);
        return {
            changed: true,
            element: this.selectedTab,
            markVisible: () => this.tabSwitch(),
        };
    }
    /** @internal */
    async getRouteId() {
        var _a;
        const tabId = (_a = this.selectedTab) === null || _a === void 0 ? void 0 : _a.tab;
        return tabId !== undefined ? { id: tabId, element: this.selectedTab } : undefined;
    }
    setActive(selectedTab) {
        if (this.transitioning) {
            return Promise.reject('transitioning already happening');
        }
        this.transitioning = true;
        this.leavingTab = this.selectedTab;
        this.selectedTab = selectedTab;
        this.ionTabsWillChange.emit({ tab: selectedTab.tab });
        selectedTab.active = true;
        return Promise.resolve();
    }
    tabSwitch() {
        const selectedTab = this.selectedTab;
        const leavingTab = this.leavingTab;
        this.leavingTab = undefined;
        this.transitioning = false;
        if (!selectedTab) {
            return;
        }
        if (leavingTab !== selectedTab) {
            if (leavingTab) {
                leavingTab.active = false;
            }
            this.ionTabsDidChange.emit({ tab: selectedTab.tab });
        }
    }
    notifyRouter() {
        if (this.useRouter) {
            const router = document.querySelector('ion-router');
            if (router) {
                return router.navChanged('forward');
            }
        }
        return Promise.resolve(false);
    }
    shouldSwitch(selectedTab) {
        const leavingTab = this.selectedTab;
        return selectedTab !== undefined && selectedTab !== leavingTab && !this.transitioning;
    }
    get tabs() {
        return Array.from(this.el.querySelectorAll('ion-tab'));
    }
    render() {
        return (h(Host, { key: '6dd1d17cc5a7aff4b910303006b4478080ca97af', onIonTabButtonClick: this.onTabClicked }, h("slot", { key: 'db54a692d1a825498a116f090eb305f7cceceb5a', name: "top" }), h("div", { key: 'e1b7d49ba7032e9071de2029695254e2a8303be9', class: "tabs-inner" }, h("slot", { key: '4c3b58d5292c8c834e7532c51de0861068943d79' })), h("slot", { key: 'dd59c0b9b217dfbfb0fccdbc6896b593278549cc', name: "bottom" })));
    }
    static get is() { return "ion-tabs"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "$": ["tabs.scss"]
        };
    }
    static get styleUrls() {
        return {
            "$": ["tabs.css"]
        };
    }
    static get properties() {
        return {
            "useRouter": {
                "type": "boolean",
                "attribute": "use-router",
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
            }
        };
    }
    static get states() {
        return {
            "selectedTab": {}
        };
    }
    static get events() {
        return [{
                "method": "ionNavWillLoad",
                "name": "ionNavWillLoad",
                "bubbles": true,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }],
                    "text": "Emitted when the navigation will load a component."
                },
                "complexType": {
                    "original": "void",
                    "resolved": "void",
                    "references": {}
                }
            }, {
                "method": "ionTabsWillChange",
                "name": "ionTabsWillChange",
                "bubbles": false,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted when the navigation is about to transition to a new component."
                },
                "complexType": {
                    "original": "{ tab: string }",
                    "resolved": "{ tab: string; }",
                    "references": {}
                }
            }, {
                "method": "ionTabsDidChange",
                "name": "ionTabsDidChange",
                "bubbles": false,
                "cancelable": true,
                "composed": true,
                "docs": {
                    "tags": [],
                    "text": "Emitted when the navigation has finished transitioning to a new component."
                },
                "complexType": {
                    "original": "{ tab: string }",
                    "resolved": "{ tab: string; }",
                    "references": {}
                }
            }];
    }
    static get methods() {
        return {
            "select": {
                "complexType": {
                    "signature": "(tab: string | HTMLIonTabElement) => Promise<boolean>",
                    "parameters": [{
                            "name": "tab",
                            "type": "string | HTMLIonTabElement",
                            "docs": "The tab instance to select. If passed a string, it should be the value of the tab's `tab` property."
                        }],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "HTMLIonTabElement": {
                            "location": "global",
                            "id": "global::HTMLIonTabElement"
                        }
                    },
                    "return": "Promise<boolean>"
                },
                "docs": {
                    "text": "Select a tab by the value of its `tab` property or an element reference. This method is only available for vanilla JavaScript projects. The Angular, React, and Vue implementations of tabs are coupled to each framework's router.",
                    "tags": [{
                            "name": "param",
                            "text": "tab The tab instance to select. If passed a string, it should be the value of the tab's `tab` property."
                        }]
                }
            },
            "getTab": {
                "complexType": {
                    "signature": "(tab: string | HTMLIonTabElement) => Promise<HTMLIonTabElement | undefined>",
                    "parameters": [{
                            "name": "tab",
                            "type": "string | HTMLIonTabElement",
                            "docs": "The tab instance to select. If passed a string, it should be the value of the tab's `tab` property."
                        }],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "HTMLIonTabElement": {
                            "location": "global",
                            "id": "global::HTMLIonTabElement"
                        }
                    },
                    "return": "Promise<HTMLIonTabElement | undefined>"
                },
                "docs": {
                    "text": "Get a specific tab by the value of its `tab` property or an element reference. This method is only available for vanilla JavaScript projects. The Angular, React, and Vue implementations of tabs are coupled to each framework's router.",
                    "tags": [{
                            "name": "param",
                            "text": "tab The tab instance to select. If passed a string, it should be the value of the tab's `tab` property."
                        }]
                }
            },
            "getSelected": {
                "complexType": {
                    "signature": "() => Promise<string | undefined>",
                    "parameters": [],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        }
                    },
                    "return": "Promise<string | undefined>"
                },
                "docs": {
                    "text": "Get the currently selected tab. This method is only available for vanilla JavaScript projects. The Angular, React, and Vue implementations of tabs are coupled to each framework's router.",
                    "tags": []
                }
            },
            "setRouteId": {
                "complexType": {
                    "signature": "(id: string) => Promise<RouteWrite>",
                    "parameters": [{
                            "name": "id",
                            "type": "string",
                            "docs": ""
                        }],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "RouteWrite": {
                            "location": "import",
                            "path": "../router/utils/interface",
                            "id": "src/components/router/utils/interface.ts::RouteWrite"
                        }
                    },
                    "return": "Promise<RouteWrite>"
                },
                "docs": {
                    "text": "",
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }]
                }
            },
            "getRouteId": {
                "complexType": {
                    "signature": "() => Promise<RouteID | undefined>",
                    "parameters": [],
                    "references": {
                        "Promise": {
                            "location": "global",
                            "id": "global::Promise"
                        },
                        "RouteID": {
                            "location": "import",
                            "path": "../router/utils/interface",
                            "id": "src/components/router/utils/interface.ts::RouteID"
                        }
                    },
                    "return": "Promise<RouteID | undefined>"
                },
                "docs": {
                    "text": "",
                    "tags": [{
                            "name": "internal",
                            "text": undefined
                        }]
                }
            }
        };
    }
    static get elementRef() { return "el"; }
}
const getTab = (tabs, tab) => {
    const tabEl = typeof tab === 'string' ? tabs.find((t) => t.tab === tab) : tab;
    if (!tabEl) {
        printIonError(`[ion-tabs] - Tab with id: "${tabEl}" does not exist`);
    }
    return tabEl;
};
