/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { r as registerInstance, c as createEvent, h, d as Host, g as getElement } from './index-4DxY6_gG.js';

const segmentViewIosCss = ":host{display:-ms-flexbox;display:flex;height:100%;overflow-x:scroll;-webkit-scroll-snap-type:x mandatory;-ms-scroll-snap-type:x mandatory;scroll-snap-type:x mandatory;scrollbar-width:none;-ms-overflow-style:none}:host::-webkit-scrollbar{display:none}:host(.segment-view-disabled){-ms-touch-action:none;touch-action:none;overflow-x:hidden}:host(.segment-view-scroll-disabled){pointer-events:none}:host(.segment-view-disabled){opacity:0.3}";

const segmentViewMdCss = ":host{display:-ms-flexbox;display:flex;height:100%;overflow-x:scroll;-webkit-scroll-snap-type:x mandatory;-ms-scroll-snap-type:x mandatory;scroll-snap-type:x mandatory;scrollbar-width:none;-ms-overflow-style:none}:host::-webkit-scrollbar{display:none}:host(.segment-view-disabled){-ms-touch-action:none;touch-action:none;overflow-x:hidden}:host(.segment-view-scroll-disabled){pointer-events:none}:host(.segment-view-disabled){opacity:0.3}";

const SegmentView = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
        this.ionSegmentViewScroll = createEvent(this, "ionSegmentViewScroll", 7);
        this.scrollEndTimeout = null;
        this.isTouching = false;
        /**
         * If `true`, the segment view cannot be interacted with.
         */
        this.disabled = false;
    }
    handleScroll(ev) {
        var _a;
        const { scrollLeft, scrollWidth, clientWidth } = ev.target;
        const scrollRatio = scrollLeft / (scrollWidth - clientWidth);
        this.ionSegmentViewScroll.emit({
            scrollRatio,
            isManualScroll: (_a = this.isManualScroll) !== null && _a !== void 0 ? _a : true,
        });
        // Reset the timeout to check for scroll end
        this.resetScrollEndTimeout();
    }
    /**
     * Handle touch start event to know when the user is actively dragging the segment view.
     */
    handleScrollStart() {
        if (this.scrollEndTimeout) {
            clearTimeout(this.scrollEndTimeout);
            this.scrollEndTimeout = null;
        }
        this.isTouching = true;
    }
    /**
     * Handle touch end event to know when the user is no longer dragging the segment view.
     */
    handleTouchEnd() {
        this.isTouching = false;
    }
    /**
     * Reset the scroll end detection timer. This is called on every scroll event.
     */
    resetScrollEndTimeout() {
        if (this.scrollEndTimeout) {
            clearTimeout(this.scrollEndTimeout);
            this.scrollEndTimeout = null;
        }
        this.scrollEndTimeout = setTimeout(() => {
            this.checkForScrollEnd();
        }, 
        // Setting this to a lower value may result in inconsistencies in behavior
        // across browsers (particularly Firefox).
        // Ideally, all of this logic is removed once the scroll end event is
        // supported on all browsers (https://caniuse.com/?search=scrollend)
        100);
    }
    /**
     * Check if the scroll has ended and the user is not actively touching.
     * If the conditions are met (active content is enabled and no active touch),
     * reset the scroll position and emit the scroll end event.
     */
    checkForScrollEnd() {
        // Only emit scroll end event if the active content is not disabled and
        // the user is not touching the segment view
        if (!this.isTouching) {
            this.isManualScroll = undefined;
        }
    }
    /**
     * @internal
     *
     * This method is used to programmatically set the displayed segment content
     * in the segment view. Calling this method will update the `value` of the
     * corresponding segment button.
     *
     * @param id: The id of the segment content to display.
     * @param smoothScroll: Whether to animate the scroll transition.
     */
    async setContent(id, smoothScroll = true) {
        const contents = this.getSegmentContents();
        const index = contents.findIndex((content) => content.id === id);
        if (index === -1)
            return;
        this.isManualScroll = false;
        this.resetScrollEndTimeout();
        const contentWidth = this.el.offsetWidth;
        this.el.scrollTo({
            top: 0,
            left: index * contentWidth,
            behavior: smoothScroll ? 'smooth' : 'instant',
        });
    }
    getSegmentContents() {
        return Array.from(this.el.querySelectorAll('ion-segment-content'));
    }
    render() {
        const { disabled, isManualScroll } = this;
        return (h(Host, { key: '754a374e89fd4dd682eb00497e717242a6f83357', class: {
                'segment-view-disabled': disabled,
                'segment-view-scroll-disabled': isManualScroll === false,
            } }, h("slot", { key: '77366044eb61f0d4bba305bd6f0ef8fd1e25194b' })));
    }
    get el() { return getElement(this); }
};
SegmentView.style = {
    ios: segmentViewIosCss,
    md: segmentViewMdCss
};

export { SegmentView as ion_segment_view };
