/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
'use strict';

var index = require('./index-DNh170BW.js');
var frameworkDelegate = require('./framework-delegate-WkyjrnCx.js');
require('./helpers-DgwmcYAu.js');

const tabCss = ":host(.tab-hidden){display:none !important}";

const Tab = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
        this.loaded = false;
        /** @internal */
        this.active = false;
    }
    async componentWillLoad() {
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
                return frameworkDelegate.attachComponent(this.delegate, this.el, this.component, ['ion-page']);
            }
            catch (e) {
                index.printIonError('[ion-tab] - Exception in prepareLazyLoaded:', e);
            }
        }
        return Promise.resolve(undefined);
    }
    render() {
        const { tab, active, component } = this;
        return (index.h(index.Host, { key: 'dbad8fe9f1566277d14647626308eaf1601ab01f', role: "tabpanel", "aria-hidden": !active ? 'true' : null, "aria-labelledby": `tab-button-${tab}`, class: {
                'ion-page': component === undefined,
                'tab-hidden': !active,
            } }, index.h("slot", { key: '3be64f4e7161f6769aaf8e4dcb5293fcaa09af45' })));
    }
    get el() { return index.getElement(this); }
    static get watchers() { return {
        "active": ["changeActive"]
    }; }
};
Tab.style = tabCss;

const tabsCss = ":host{left:0;right:0;top:0;bottom:0;display:-ms-flexbox;display:flex;position:absolute;-ms-flex-direction:column;flex-direction:column;width:100%;height:100%;contain:layout size style;z-index:0}.tabs-inner{position:relative;-ms-flex:1;flex:1;contain:layout size style}";

const Tabs = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
        this.ionNavWillLoad = index.createEvent(this, "ionNavWillLoad", 7);
        this.ionTabsWillChange = index.createEvent(this, "ionTabsWillChange", 3);
        this.ionTabsDidChange = index.createEvent(this, "ionTabsDidChange", 3);
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
        return (index.h(index.Host, { key: '6dd1d17cc5a7aff4b910303006b4478080ca97af', onIonTabButtonClick: this.onTabClicked }, index.h("slot", { key: 'db54a692d1a825498a116f090eb305f7cceceb5a', name: "top" }), index.h("div", { key: 'e1b7d49ba7032e9071de2029695254e2a8303be9', class: "tabs-inner" }, index.h("slot", { key: '4c3b58d5292c8c834e7532c51de0861068943d79' })), index.h("slot", { key: 'dd59c0b9b217dfbfb0fccdbc6896b593278549cc', name: "bottom" })));
    }
    get el() { return index.getElement(this); }
};
const getTab = (tabs, tab) => {
    const tabEl = typeof tab === 'string' ? tabs.find((t) => t.tab === tab) : tab;
    if (!tabEl) {
        index.printIonError(`[ion-tabs] - Tab with id: "${tabEl}" does not exist`);
    }
    return tabEl;
};
Tabs.style = tabsCss;

exports.ion_tab = Tab;
exports.ion_tabs = Tabs;
