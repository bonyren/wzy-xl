/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
'use strict';

var index = require('./index-DNh170BW.js');
var helpers = require('./helpers-DgwmcYAu.js');
var theme = require('./theme-CeDs6Hcv.js');
var ionicGlobal = require('./ionic-global-UI5YPSi-.js');
var data = require('./data-DW6ofvJ8.js');

const datetimeButtonIosCss = ":host{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:center;justify-content:center}:host button{border-radius:8px;-webkit-margin-start:2px;margin-inline-start:2px;-webkit-margin-end:2px;margin-inline-end:2px;margin-top:0px;margin-bottom:0px;position:relative;-webkit-transition:150ms color ease-in-out;transition:150ms color ease-in-out;border:none;background:var(--ion-color-step-300, var(--ion-background-color-step-300, #edeef0));color:var(--ion-text-color, #000);font-family:inherit;font-size:1rem;cursor:pointer;overflow:hidden;-webkit-appearance:none;-moz-appearance:none;appearance:none}:host(.time-active) #time-button,:host(.date-active) #date-button{color:var(--ion-color-base)}:host(.datetime-button-disabled){pointer-events:none}:host(.datetime-button-disabled) button{opacity:0.4}:host button{-webkit-padding-start:13px;padding-inline-start:13px;-webkit-padding-end:13px;padding-inline-end:13px;padding-top:7px;padding-bottom:7px}:host button.ion-activated{color:var(--ion-color-step-600, var(--ion-text-color-step-400, #666666))}";

const datetimeButtonMdCss = ":host{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;-ms-flex-align:center;align-items:center;-ms-flex-pack:center;justify-content:center}:host button{border-radius:8px;-webkit-margin-start:2px;margin-inline-start:2px;-webkit-margin-end:2px;margin-inline-end:2px;margin-top:0px;margin-bottom:0px;position:relative;-webkit-transition:150ms color ease-in-out;transition:150ms color ease-in-out;border:none;background:var(--ion-color-step-300, var(--ion-background-color-step-300, #edeef0));color:var(--ion-text-color, #000);font-family:inherit;font-size:1rem;cursor:pointer;overflow:hidden;-webkit-appearance:none;-moz-appearance:none;appearance:none}:host(.time-active) #time-button,:host(.date-active) #date-button{color:var(--ion-color-base)}:host(.datetime-button-disabled){pointer-events:none}:host(.datetime-button-disabled) button{opacity:0.4}:host button{-webkit-padding-start:12px;padding-inline-start:12px;-webkit-padding-end:12px;padding-inline-end:12px;padding-top:6px;padding-bottom:6px}";

const DatetimeButton = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
        this.datetimeEl = null;
        this.overlayEl = null;
        this.datetimePresentation = 'date-time';
        this.datetimeActive = false;
        /**
         * The color to use from your application's color palette.
         * Default options are: `"primary"`, `"secondary"`, `"tertiary"`, `"success"`, `"warning"`, `"danger"`, `"light"`, `"medium"`, and `"dark"`.
         * For more information on colors, see [theming](/docs/theming/basics).
         */
        this.color = 'primary';
        /**
         * If `true`, the user cannot interact with the button.
         */
        this.disabled = false;
        /**
         * Accepts one or more string values and converts
         * them to DatetimeParts. This is done so datetime-button
         * can work with an array internally and not need
         * to keep checking if the datetime value is `string` or `string[]`.
         */
        this.getParsedDateValues = (value) => {
            if (value === undefined || value === null) {
                return [];
            }
            if (Array.isArray(value)) {
                return value;
            }
            return [value];
        };
        /**
         * Check the value property on the linked
         * ion-datetime and then format it according
         * to the locale specified on ion-datetime.
         */
        this.setDateTimeText = () => {
            var _a, _b, _c, _d, _e;
            const { datetimeEl, datetimePresentation } = this;
            if (!datetimeEl) {
                return;
            }
            const { value, locale, formatOptions, hourCycle, preferWheel, multiple, titleSelectedDatesFormatter } = datetimeEl;
            const parsedValues = this.getParsedDateValues(value);
            /**
             * Both ion-datetime and ion-datetime-button default
             * to today's date and time if no value is set.
             */
            const parsedDatetimes = data.parseDate(parsedValues.length > 0 ? parsedValues : [data.getToday()]);
            if (!parsedDatetimes) {
                return;
            }
            /**
             * If developers incorrectly use multiple="true"
             * with non "date" datetimes, then just select
             * the first value so the interface does
             * not appear broken. Datetime will provide a
             * warning in the console.
             */
            const firstParsedDatetime = parsedDatetimes[0];
            const computedHourCycle = data.getHourCycle(locale, hourCycle);
            this.dateText = this.timeText = undefined;
            switch (datetimePresentation) {
                case 'date-time':
                case 'time-date':
                    const dateText = data.getLocalizedDateTime(locale, firstParsedDatetime, (_a = formatOptions === null || formatOptions === void 0 ? void 0 : formatOptions.date) !== null && _a !== void 0 ? _a : { month: 'short', day: 'numeric', year: 'numeric' });
                    const timeText = data.getLocalizedTime(locale, firstParsedDatetime, computedHourCycle, formatOptions === null || formatOptions === void 0 ? void 0 : formatOptions.time);
                    if (preferWheel) {
                        this.dateText = `${dateText} ${timeText}`;
                    }
                    else {
                        this.dateText = dateText;
                        this.timeText = timeText;
                    }
                    break;
                case 'date':
                    if (multiple && parsedValues.length !== 1) {
                        let headerText = `${parsedValues.length} days`; // default/fallback for multiple selection
                        if (titleSelectedDatesFormatter !== undefined) {
                            try {
                                headerText = titleSelectedDatesFormatter(parsedValues);
                            }
                            catch (e) {
                                index.printIonError('[ion-datetime-button] - Exception in provided `titleSelectedDatesFormatter`:', e);
                            }
                        }
                        this.dateText = headerText;
                    }
                    else {
                        this.dateText = data.getLocalizedDateTime(locale, firstParsedDatetime, (_b = formatOptions === null || formatOptions === void 0 ? void 0 : formatOptions.date) !== null && _b !== void 0 ? _b : { month: 'short', day: 'numeric', year: 'numeric' });
                    }
                    break;
                case 'time':
                    this.timeText = data.getLocalizedTime(locale, firstParsedDatetime, computedHourCycle, formatOptions === null || formatOptions === void 0 ? void 0 : formatOptions.time);
                    break;
                case 'month-year':
                    this.dateText = data.getLocalizedDateTime(locale, firstParsedDatetime, (_c = formatOptions === null || formatOptions === void 0 ? void 0 : formatOptions.date) !== null && _c !== void 0 ? _c : { month: 'long', year: 'numeric' });
                    break;
                case 'month':
                    this.dateText = data.getLocalizedDateTime(locale, firstParsedDatetime, (_d = formatOptions === null || formatOptions === void 0 ? void 0 : formatOptions.time) !== null && _d !== void 0 ? _d : { month: 'long' });
                    break;
                case 'year':
                    this.dateText = data.getLocalizedDateTime(locale, firstParsedDatetime, (_e = formatOptions === null || formatOptions === void 0 ? void 0 : formatOptions.time) !== null && _e !== void 0 ? _e : { year: 'numeric' });
                    break;
            }
        };
        /**
         * Waits for the ion-datetime to re-render.
         * This is needed in order to correctly position
         * a popover relative to the trigger element.
         */
        this.waitForDatetimeChanges = async () => {
            const { datetimeEl } = this;
            if (!datetimeEl) {
                return Promise.resolve();
            }
            return new Promise((resolve) => {
                helpers.addEventListener(datetimeEl, 'ionRender', resolve, { once: true });
            });
        };
        this.handleDateClick = async (ev) => {
            const { datetimeEl, datetimePresentation } = this;
            if (!datetimeEl) {
                return;
            }
            let needsPresentationChange = false;
            /**
             * When clicking the date button,
             * we need to make sure that only a date
             * picker is displayed. For presentation styles
             * that display content other than a date picker,
             * we need to update the presentation style.
             */
            switch (datetimePresentation) {
                case 'date-time':
                case 'time-date':
                    const needsChange = datetimeEl.presentation !== 'date';
                    /**
                     * The date+time wheel picker
                     * shows date and time together,
                     * so do not adjust the presentation
                     * in that case.
                     */
                    if (!datetimeEl.preferWheel && needsChange) {
                        datetimeEl.presentation = 'date';
                        needsPresentationChange = true;
                    }
                    break;
            }
            /**
             * Track which button was clicked
             * so that it can have the correct
             * activated styles applied when
             * the modal/popover containing
             * the datetime is opened.
             */
            this.selectedButton = 'date';
            this.presentOverlay(ev, needsPresentationChange, this.dateTargetEl);
        };
        this.handleTimeClick = (ev) => {
            const { datetimeEl, datetimePresentation } = this;
            if (!datetimeEl) {
                return;
            }
            let needsPresentationChange = false;
            /**
             * When clicking the time button,
             * we need to make sure that only a time
             * picker is displayed. For presentation styles
             * that display content other than a time picker,
             * we need to update the presentation style.
             */
            switch (datetimePresentation) {
                case 'date-time':
                case 'time-date':
                    const needsChange = datetimeEl.presentation !== 'time';
                    if (needsChange) {
                        datetimeEl.presentation = 'time';
                        needsPresentationChange = true;
                    }
                    break;
            }
            /**
             * Track which button was clicked
             * so that it can have the correct
             * activated styles applied when
             * the modal/popover containing
             * the datetime is opened.
             */
            this.selectedButton = 'time';
            this.presentOverlay(ev, needsPresentationChange, this.timeTargetEl);
        };
        /**
         * If the datetime is presented in an
         * overlay, the datetime and overlay
         * should be appropriately sized.
         * These classes provide default sizing values
         * that developers can customize.
         * The goal is to provide an overlay that is
         * reasonably sized with a datetime that
         * fills the entire container.
         */
        this.presentOverlay = async (ev, needsPresentationChange, triggerEl) => {
            const { overlayEl } = this;
            if (!overlayEl) {
                return;
            }
            if (overlayEl.tagName === 'ION-POPOVER') {
                /**
                 * When the presentation on datetime changes,
                 * we need to wait for the component to re-render
                 * otherwise the computed width/height of the
                 * popover content will be wrong, causing
                 * the popover to not align with the trigger element.
                 */
                if (needsPresentationChange) {
                    await this.waitForDatetimeChanges();
                }
                /**
                 * We pass the trigger button element
                 * so that the popover aligns with the individual
                 * button that was clicked, not the component container.
                 */
                overlayEl.present(Object.assign(Object.assign({}, ev), { detail: {
                        ionShadowTarget: triggerEl,
                    } }));
            }
            else {
                overlayEl.present();
            }
        };
    }
    async componentWillLoad() {
        const { datetime } = this;
        if (!datetime) {
            index.printIonError('[ion-datetime-button] - An ID associated with an ion-datetime instance is required to function properly.', this.el);
            return;
        }
        const datetimeEl = (this.datetimeEl = document.getElementById(datetime));
        if (!datetimeEl) {
            index.printIonError(`[ion-datetime-button] - No ion-datetime instance found for ID '${datetime}'.`, this.el);
            return;
        }
        /**
         * The element reference must be an ion-datetime. Print an error
         * if a non-datetime element was provided.
         */
        if (datetimeEl.tagName !== 'ION-DATETIME') {
            index.printIonError(`[ion-datetime-button] - Expected an ion-datetime instance for ID '${datetime}' but received '${datetimeEl.tagName.toLowerCase()}' instead.`, datetimeEl);
            return;
        }
        /**
         * Since the datetime can be used in any context (overlays, accordion, etc)
         * we track when it is visible to determine when it is active.
         * This informs which button is highlighted as well as the
         * aria-expanded state.
         */
        const io = new IntersectionObserver((entries) => {
            const ev = entries[0];
            this.datetimeActive = ev.isIntersecting;
        }, {
            threshold: 0.01,
        });
        io.observe(datetimeEl);
        /**
         * Get a reference to any modal/popover
         * the datetime is being used in so we can
         * correctly size it when it is presented.
         */
        const overlayEl = (this.overlayEl = datetimeEl.closest('ion-modal, ion-popover'));
        /**
         * The .ion-datetime-button-overlay class contains
         * styles that allow any modal/popover to be
         * sized according to the dimensions of the datetime.
         * If developers want a smaller/larger overlay all they need
         * to do is change the width/height of the datetime.
         * Additionally, this lets us avoid having to set
         * explicit widths on each variant of datetime.
         */
        if (overlayEl) {
            overlayEl.classList.add('ion-datetime-button-overlay');
        }
        helpers.componentOnReady(datetimeEl, () => {
            const datetimePresentation = (this.datetimePresentation = datetimeEl.presentation || 'date-time');
            /**
             * Set the initial display
             * in the rendered buttons.
             *
             * From there, we need to listen
             * for ionChange to be emitted
             * from datetime so we know when
             * to re-render the displayed
             * text in the buttons.
             */
            this.setDateTimeText();
            helpers.addEventListener(datetimeEl, 'ionValueChange', this.setDateTimeText);
            /**
             * Configure the initial selected button
             * in the event that the datetime is displayed
             * without clicking one of the datetime buttons.
             * For example, a datetime could be expanded
             * in an accordion. In this case users only
             * need to click the accordion header to show
             * the datetime.
             */
            switch (datetimePresentation) {
                case 'date-time':
                case 'date':
                case 'month-year':
                case 'month':
                case 'year':
                    this.selectedButton = 'date';
                    break;
                case 'time-date':
                case 'time':
                    this.selectedButton = 'time';
                    break;
            }
        });
    }
    render() {
        const { color, dateText, timeText, selectedButton, datetimeActive, disabled } = this;
        const mode = ionicGlobal.getIonMode(this);
        return (index.h(index.Host, { key: '11d037e6ab061e5116842970760b04850b42f2c7', class: theme.createColorClasses(color, {
                [mode]: true,
                [`${selectedButton}-active`]: datetimeActive,
                ['datetime-button-disabled']: disabled,
            }) }, dateText && (index.h("button", { key: '08ecb62da0fcbf7466a1f2403276712a3ff17fbc', class: "ion-activatable", id: "date-button", "aria-expanded": datetimeActive ? 'true' : 'false', onClick: this.handleDateClick, disabled: disabled, part: "native", ref: (el) => (this.dateTargetEl = el) }, index.h("slot", { key: '1c04853d4d23c0f1a594602bde44511c98355644', name: "date-target" }, dateText), mode === 'md' && index.h("ion-ripple-effect", { key: '5fc566cd4bc885bcf983ce99e3dc65d7f485bf9b' }))), timeText && (index.h("button", { key: 'c9c5c34ac338badf8659da22bea5829d62c51169', class: "ion-activatable", id: "time-button", "aria-expanded": datetimeActive ? 'true' : 'false', onClick: this.handleTimeClick, disabled: disabled, part: "native", ref: (el) => (this.timeTargetEl = el) }, index.h("slot", { key: '147a9d2069dbf737f6fc64787823d6d5af5aa653', name: "time-target" }, timeText), mode === 'md' && index.h("ion-ripple-effect", { key: '70a5e25b75ed90ac6bba003468435f67aa9d8f0a' })))));
    }
    get el() { return index.getElement(this); }
};
DatetimeButton.style = {
    ios: datetimeButtonIosCss,
    md: datetimeButtonMdCss
};

exports.ion_datetime_button = DatetimeButton;
