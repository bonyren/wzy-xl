/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
'use strict';

var index = require('./index-DNh170BW.js');
var notchController = require('./notch-controller-Bf5Rr4R5.js');
var compareWithUtils = require('./compare-with-utils-DSicavqM.js');
var helpers = require('./helpers-DgwmcYAu.js');
var overlays = require('./overlays-CglR7j-u.js');
var dir = require('./dir-Cn0z1rJH.js');
var theme = require('./theme-CeDs6Hcv.js');
var watchOptions = require('./watch-options-CviOsrTS.js');
var index$1 = require('./index-DqmRDbxg.js');
var ionicGlobal = require('./ionic-global-UI5YPSi-.js');
require('./index-DkNv4J_i.js');
require('./hardware-back-button-BxdNu76F.js');
require('./framework-delegate-WkyjrnCx.js');
require('./gesture-controller-dtqlP_q4.js');

const selectIosCss = ":host{--padding-top:0px;--padding-end:0px;--padding-bottom:0px;--padding-start:0px;--placeholder-color:currentColor;--placeholder-opacity:var(--ion-placeholder-opacity, 0.6);--background:transparent;--border-style:solid;--highlight-color-focused:var(--ion-color-primary, #0054e9);--highlight-color-valid:var(--ion-color-success, #2dd55b);--highlight-color-invalid:var(--ion-color-danger, #c5000f);--highlight-color:var(--highlight-color-focused);display:block;position:relative;width:100%;min-height:44px;font-family:var(--ion-font-family, inherit);white-space:nowrap;cursor:pointer;z-index:2}:host(.select-label-placement-floating),:host(.select-label-placement-stacked){min-height:56px}:host(.ion-color){--highlight-color-focused:var(--ion-color-base)}:host(.in-item){-ms-flex:1 1 0px;flex:1 1 0}:host(.select-disabled){pointer-events:none}:host(.has-focus) button{border:2px solid #5e9ed6}:host([slot=start]),:host([slot=end]){-ms-flex:initial;flex:initial;width:auto}.select-placeholder{color:var(--placeholder-color);opacity:var(--placeholder-opacity)}button{position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;margin:0;padding:0;border:0;outline:0;clip:rect(0 0 0 0);opacity:0;overflow:hidden;-webkit-appearance:none;-moz-appearance:none}.select-icon{-webkit-margin-start:4px;margin-inline-start:4px;-webkit-margin-end:0;margin-inline-end:0;margin-top:0;margin-bottom:0;position:relative;-ms-flex-negative:0;flex-shrink:0}:host(.in-item-color) .select-icon{color:inherit}:host(.select-label-placement-stacked) .select-icon,:host(.select-label-placement-floating) .select-icon{position:absolute;height:100%}:host(.select-ltr.select-label-placement-stacked) .select-icon,:host(.select-ltr.select-label-placement-floating) .select-icon{right:var(--padding-end, 0)}:host(.select-rtl.select-label-placement-stacked) .select-icon,:host(.select-rtl.select-label-placement-floating) .select-icon{left:var(--padding-start, 0)}.select-text{-ms-flex:1;flex:1;min-width:16px;font-size:inherit;text-overflow:ellipsis;white-space:inherit;overflow:hidden}.select-wrapper{-webkit-padding-start:var(--padding-start);padding-inline-start:var(--padding-start);-webkit-padding-end:var(--padding-end);padding-inline-end:var(--padding-end);padding-top:var(--padding-top);padding-bottom:var(--padding-bottom);border-radius:var(--border-radius);display:-ms-flexbox;display:flex;position:relative;-ms-flex-positive:1;flex-grow:1;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;height:inherit;min-height:inherit;-webkit-transition:background-color 15ms linear;transition:background-color 15ms linear;background:var(--background);line-height:normal;cursor:inherit;-webkit-box-sizing:border-box;box-sizing:border-box}.select-wrapper .select-placeholder{-webkit-transition:opacity 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:opacity 150ms cubic-bezier(0.4, 0, 0.2, 1)}.select-wrapper-inner{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;overflow:hidden}:host(.select-label-placement-stacked) .select-wrapper-inner,:host(.select-label-placement-floating) .select-wrapper-inner{-ms-flex-positive:1;flex-grow:1}:host(.ion-touched.ion-invalid){--highlight-color:var(--highlight-color-invalid)}:host(.ion-valid){--highlight-color:var(--highlight-color-valid)}.select-bottom{-webkit-padding-start:var(--padding-start);padding-inline-start:var(--padding-start);-webkit-padding-end:var(--padding-end);padding-inline-end:var(--padding-end);padding-top:5px;padding-bottom:0;display:-ms-flexbox;display:flex;-ms-flex-pack:justify;justify-content:space-between;border-top:var(--border-width) var(--border-style) var(--border-color);font-size:0.75rem;white-space:normal}:host(.has-focus.ion-valid),:host(.select-expanded.ion-valid),:host(.ion-touched.ion-invalid),:host(.select-expanded.ion-touched.ion-invalid){--border-color:var(--highlight-color)}.select-bottom .error-text{display:none;color:var(--highlight-color-invalid)}.select-bottom .helper-text{display:block;color:var(--ion-color-step-700, var(--ion-text-color-step-300, #4d4d4d))}:host(.ion-touched.ion-invalid) .select-bottom .error-text{display:block}:host(.ion-touched.ion-invalid) .select-bottom .helper-text{display:none}.label-text-wrapper{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;max-width:200px;-webkit-transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), transform 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1);pointer-events:none}.label-text,::slotted([slot=label]){text-overflow:ellipsis;white-space:nowrap;overflow:hidden}.label-text-wrapper-hidden,.select-outline-notch-hidden{display:none}.native-wrapper{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-webkit-transition:opacity 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:opacity 150ms cubic-bezier(0.4, 0, 0.2, 1);overflow:hidden}:host(.select-justify-space-between) .select-wrapper{-ms-flex-pack:justify;justify-content:space-between}:host(.select-justify-start) .select-wrapper{-ms-flex-pack:start;justify-content:start}:host(.select-justify-end) .select-wrapper{-ms-flex-pack:end;justify-content:end}:host(.select-label-placement-start) .select-wrapper{-ms-flex-direction:row;flex-direction:row}:host(.select-label-placement-start) .label-text-wrapper{-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:16px;margin-inline-end:16px;margin-top:0;margin-bottom:0}:host(.select-label-placement-end) .select-wrapper{-ms-flex-direction:row-reverse;flex-direction:row-reverse}:host(.select-label-placement-end) .label-text-wrapper{-webkit-margin-start:16px;margin-inline-start:16px;-webkit-margin-end:0;margin-inline-end:0;margin-top:0;margin-bottom:0}:host(.select-label-placement-fixed) .label-text-wrapper{-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:16px;margin-inline-end:16px;margin-top:0;margin-bottom:0}:host(.select-label-placement-fixed) .label-text-wrapper{-ms-flex:0 0 100px;flex:0 0 100px;width:100px;min-width:100px;max-width:200px}:host(.select-label-placement-stacked) .select-wrapper,:host(.select-label-placement-floating) .select-wrapper{-ms-flex-direction:column;flex-direction:column;-ms-flex-align:start;align-items:start}:host(.select-label-placement-stacked) .label-text-wrapper,:host(.select-label-placement-floating) .label-text-wrapper{max-width:100%}:host(.select-ltr.select-label-placement-stacked) .label-text-wrapper,:host(.select-ltr.select-label-placement-floating) .label-text-wrapper{-webkit-transform-origin:left top;transform-origin:left top}:host(.select-rtl.select-label-placement-stacked) .label-text-wrapper,:host(.select-rtl.select-label-placement-floating) .label-text-wrapper{-webkit-transform-origin:right top;transform-origin:right top}:host(.select-label-placement-stacked) .native-wrapper,:host(.select-label-placement-floating) .native-wrapper{margin-left:0;margin-right:0;margin-top:1px;margin-bottom:0;-ms-flex-positive:1;flex-grow:1;width:100%}:host(.select-label-placement-floating) .label-text-wrapper{-webkit-transform:translateY(100%) scale(1);transform:translateY(100%) scale(1)}:host(.select-label-placement-floating:not(.label-floating)) .native-wrapper .select-placeholder{opacity:0}:host(.select-expanded.select-label-placement-floating) .native-wrapper .select-placeholder,:host(.has-focus.select-label-placement-floating) .native-wrapper .select-placeholder,:host(.has-value.select-label-placement-floating) .native-wrapper .select-placeholder{opacity:1}:host(.label-floating) .label-text-wrapper{-webkit-transform:translateY(50%) scale(0.75);transform:translateY(50%) scale(0.75);max-width:calc(100% / 0.75)}::slotted([slot=start]),::slotted([slot=end]){-ms-flex-negative:0;flex-shrink:0}::slotted([slot=start]:last-of-type){-webkit-margin-end:16px;margin-inline-end:16px;-webkit-margin-start:0;margin-inline-start:0}::slotted([slot=end]:first-of-type){-webkit-margin-start:16px;margin-inline-start:16px;-webkit-margin-end:0;margin-inline-end:0}:host{--border-width:0.55px;--border-color:var(--ion-item-border-color, var(--ion-border-color, var(--ion-color-step-250, var(--ion-background-color-step-250, #c8c7cc))));--highlight-height:0px}.select-icon{width:1.125rem;height:1.125rem;color:var(--ion-color-step-650, var(--ion-text-color-step-350, #595959))}:host(.select-label-placement-stacked) .select-wrapper-inner,:host(.select-label-placement-floating) .select-wrapper-inner{width:calc(100% - 1.125rem - 4px)}:host(.select-disabled){opacity:0.3}::slotted(ion-button[slot=start].button-has-icon-only),::slotted(ion-button[slot=end].button-has-icon-only){--border-radius:50%;--padding-start:0;--padding-end:0;--padding-top:0;--padding-bottom:0;aspect-ratio:1}";

const selectMdCss = ":host{--padding-top:0px;--padding-end:0px;--padding-bottom:0px;--padding-start:0px;--placeholder-color:currentColor;--placeholder-opacity:var(--ion-placeholder-opacity, 0.6);--background:transparent;--border-style:solid;--highlight-color-focused:var(--ion-color-primary, #0054e9);--highlight-color-valid:var(--ion-color-success, #2dd55b);--highlight-color-invalid:var(--ion-color-danger, #c5000f);--highlight-color:var(--highlight-color-focused);display:block;position:relative;width:100%;min-height:44px;font-family:var(--ion-font-family, inherit);white-space:nowrap;cursor:pointer;z-index:2}:host(.select-label-placement-floating),:host(.select-label-placement-stacked){min-height:56px}:host(.ion-color){--highlight-color-focused:var(--ion-color-base)}:host(.in-item){-ms-flex:1 1 0px;flex:1 1 0}:host(.select-disabled){pointer-events:none}:host(.has-focus) button{border:2px solid #5e9ed6}:host([slot=start]),:host([slot=end]){-ms-flex:initial;flex:initial;width:auto}.select-placeholder{color:var(--placeholder-color);opacity:var(--placeholder-opacity)}button{position:absolute;top:0;left:0;right:0;bottom:0;width:100%;height:100%;margin:0;padding:0;border:0;outline:0;clip:rect(0 0 0 0);opacity:0;overflow:hidden;-webkit-appearance:none;-moz-appearance:none}.select-icon{-webkit-margin-start:4px;margin-inline-start:4px;-webkit-margin-end:0;margin-inline-end:0;margin-top:0;margin-bottom:0;position:relative;-ms-flex-negative:0;flex-shrink:0}:host(.in-item-color) .select-icon{color:inherit}:host(.select-label-placement-stacked) .select-icon,:host(.select-label-placement-floating) .select-icon{position:absolute;height:100%}:host(.select-ltr.select-label-placement-stacked) .select-icon,:host(.select-ltr.select-label-placement-floating) .select-icon{right:var(--padding-end, 0)}:host(.select-rtl.select-label-placement-stacked) .select-icon,:host(.select-rtl.select-label-placement-floating) .select-icon{left:var(--padding-start, 0)}.select-text{-ms-flex:1;flex:1;min-width:16px;font-size:inherit;text-overflow:ellipsis;white-space:inherit;overflow:hidden}.select-wrapper{-webkit-padding-start:var(--padding-start);padding-inline-start:var(--padding-start);-webkit-padding-end:var(--padding-end);padding-inline-end:var(--padding-end);padding-top:var(--padding-top);padding-bottom:var(--padding-bottom);border-radius:var(--border-radius);display:-ms-flexbox;display:flex;position:relative;-ms-flex-positive:1;flex-grow:1;-ms-flex-align:center;align-items:center;-ms-flex-pack:justify;justify-content:space-between;height:inherit;min-height:inherit;-webkit-transition:background-color 15ms linear;transition:background-color 15ms linear;background:var(--background);line-height:normal;cursor:inherit;-webkit-box-sizing:border-box;box-sizing:border-box}.select-wrapper .select-placeholder{-webkit-transition:opacity 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:opacity 150ms cubic-bezier(0.4, 0, 0.2, 1)}.select-wrapper-inner{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;overflow:hidden}:host(.select-label-placement-stacked) .select-wrapper-inner,:host(.select-label-placement-floating) .select-wrapper-inner{-ms-flex-positive:1;flex-grow:1}:host(.ion-touched.ion-invalid){--highlight-color:var(--highlight-color-invalid)}:host(.ion-valid){--highlight-color:var(--highlight-color-valid)}.select-bottom{-webkit-padding-start:var(--padding-start);padding-inline-start:var(--padding-start);-webkit-padding-end:var(--padding-end);padding-inline-end:var(--padding-end);padding-top:5px;padding-bottom:0;display:-ms-flexbox;display:flex;-ms-flex-pack:justify;justify-content:space-between;border-top:var(--border-width) var(--border-style) var(--border-color);font-size:0.75rem;white-space:normal}:host(.has-focus.ion-valid),:host(.select-expanded.ion-valid),:host(.ion-touched.ion-invalid),:host(.select-expanded.ion-touched.ion-invalid){--border-color:var(--highlight-color)}.select-bottom .error-text{display:none;color:var(--highlight-color-invalid)}.select-bottom .helper-text{display:block;color:var(--ion-color-step-700, var(--ion-text-color-step-300, #4d4d4d))}:host(.ion-touched.ion-invalid) .select-bottom .error-text{display:block}:host(.ion-touched.ion-invalid) .select-bottom .helper-text{display:none}.label-text-wrapper{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;max-width:200px;-webkit-transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), transform 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:color 150ms cubic-bezier(0.4, 0, 0.2, 1), transform 150ms cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 150ms cubic-bezier(0.4, 0, 0.2, 1);pointer-events:none}.label-text,::slotted([slot=label]){text-overflow:ellipsis;white-space:nowrap;overflow:hidden}.label-text-wrapper-hidden,.select-outline-notch-hidden{display:none}.native-wrapper{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-webkit-transition:opacity 150ms cubic-bezier(0.4, 0, 0.2, 1);transition:opacity 150ms cubic-bezier(0.4, 0, 0.2, 1);overflow:hidden}:host(.select-justify-space-between) .select-wrapper{-ms-flex-pack:justify;justify-content:space-between}:host(.select-justify-start) .select-wrapper{-ms-flex-pack:start;justify-content:start}:host(.select-justify-end) .select-wrapper{-ms-flex-pack:end;justify-content:end}:host(.select-label-placement-start) .select-wrapper{-ms-flex-direction:row;flex-direction:row}:host(.select-label-placement-start) .label-text-wrapper{-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:16px;margin-inline-end:16px;margin-top:0;margin-bottom:0}:host(.select-label-placement-end) .select-wrapper{-ms-flex-direction:row-reverse;flex-direction:row-reverse}:host(.select-label-placement-end) .label-text-wrapper{-webkit-margin-start:16px;margin-inline-start:16px;-webkit-margin-end:0;margin-inline-end:0;margin-top:0;margin-bottom:0}:host(.select-label-placement-fixed) .label-text-wrapper{-webkit-margin-start:0;margin-inline-start:0;-webkit-margin-end:16px;margin-inline-end:16px;margin-top:0;margin-bottom:0}:host(.select-label-placement-fixed) .label-text-wrapper{-ms-flex:0 0 100px;flex:0 0 100px;width:100px;min-width:100px;max-width:200px}:host(.select-label-placement-stacked) .select-wrapper,:host(.select-label-placement-floating) .select-wrapper{-ms-flex-direction:column;flex-direction:column;-ms-flex-align:start;align-items:start}:host(.select-label-placement-stacked) .label-text-wrapper,:host(.select-label-placement-floating) .label-text-wrapper{max-width:100%}:host(.select-ltr.select-label-placement-stacked) .label-text-wrapper,:host(.select-ltr.select-label-placement-floating) .label-text-wrapper{-webkit-transform-origin:left top;transform-origin:left top}:host(.select-rtl.select-label-placement-stacked) .label-text-wrapper,:host(.select-rtl.select-label-placement-floating) .label-text-wrapper{-webkit-transform-origin:right top;transform-origin:right top}:host(.select-label-placement-stacked) .native-wrapper,:host(.select-label-placement-floating) .native-wrapper{margin-left:0;margin-right:0;margin-top:1px;margin-bottom:0;-ms-flex-positive:1;flex-grow:1;width:100%}:host(.select-label-placement-floating) .label-text-wrapper{-webkit-transform:translateY(100%) scale(1);transform:translateY(100%) scale(1)}:host(.select-label-placement-floating:not(.label-floating)) .native-wrapper .select-placeholder{opacity:0}:host(.select-expanded.select-label-placement-floating) .native-wrapper .select-placeholder,:host(.has-focus.select-label-placement-floating) .native-wrapper .select-placeholder,:host(.has-value.select-label-placement-floating) .native-wrapper .select-placeholder{opacity:1}:host(.label-floating) .label-text-wrapper{-webkit-transform:translateY(50%) scale(0.75);transform:translateY(50%) scale(0.75);max-width:calc(100% / 0.75)}::slotted([slot=start]),::slotted([slot=end]){-ms-flex-negative:0;flex-shrink:0}::slotted([slot=start]:last-of-type){-webkit-margin-end:16px;margin-inline-end:16px;-webkit-margin-start:0;margin-inline-start:0}::slotted([slot=end]:first-of-type){-webkit-margin-start:16px;margin-inline-start:16px;-webkit-margin-end:0;margin-inline-end:0}:host(.select-fill-solid){--background:var(--ion-color-step-50, var(--ion-background-color-step-50, #f2f2f2));--border-color:var(--ion-color-step-500, var(--ion-background-color-step-500, gray));--border-radius:4px;--padding-start:16px;--padding-end:16px;min-height:56px}:host(.select-fill-solid) .select-wrapper{border-bottom:var(--border-width) var(--border-style) var(--border-color)}:host(.select-expanded.select-fill-solid.ion-valid),:host(.has-focus.select-fill-solid.ion-valid),:host(.select-fill-solid.ion-touched.ion-invalid){--border-color:var(--highlight-color)}:host(.select-fill-solid) .select-bottom{border-top:none}@media (any-hover: hover){:host(.select-fill-solid:hover){--background:var(--ion-color-step-100, var(--ion-background-color-step-100, #e6e6e6));--border-color:var(--ion-color-step-750, var(--ion-background-color-step-750, #404040))}}:host(.select-fill-solid.select-expanded),:host(.select-fill-solid.has-focus){--background:var(--ion-color-step-150, var(--ion-background-color-step-150, #d9d9d9));--border-color:var(--highlight-color)}:host(.select-fill-solid) .select-wrapper{border-start-start-radius:var(--border-radius);border-start-end-radius:var(--border-radius);border-end-end-radius:0px;border-end-start-radius:0px}:host(.label-floating.select-fill-solid) .label-text-wrapper{max-width:calc(100% / 0.75)}:host(.in-item.select-expanded.select-fill-solid) .select-wrapper .select-icon,:host(.in-item.has-focus.select-fill-solid) .select-wrapper .select-icon,:host(.in-item.has-focus.ion-valid.select-fill-solid) .select-wrapper .select-icon,:host(.in-item.ion-touched.ion-invalid.select-fill-solid) .select-wrapper .select-icon{color:var(--highlight-color)}:host(.select-fill-outline){--border-color:var(--ion-color-step-300, var(--ion-background-color-step-300, #b3b3b3));--border-radius:4px;--padding-start:16px;--padding-end:16px;min-height:56px}:host(.select-fill-outline.select-shape-round){--border-radius:28px;--padding-start:32px;--padding-end:32px}:host(.has-focus.select-fill-outline.ion-valid),:host(.select-fill-outline.ion-touched.ion-invalid){--border-color:var(--highlight-color)}@media (any-hover: hover){:host(.select-fill-outline:hover){--border-color:var(--ion-color-step-750, var(--ion-background-color-step-750, #404040))}}:host(.select-fill-outline.select-expanded),:host(.select-fill-outline.has-focus){--border-width:var(--highlight-height);--border-color:var(--highlight-color)}:host(.select-fill-outline) .select-bottom{border-top:none}:host(.select-fill-outline) .select-wrapper{border-bottom:none}:host(.select-ltr.select-fill-outline.select-label-placement-stacked) .label-text-wrapper,:host(.select-ltr.select-fill-outline.select-label-placement-floating) .label-text-wrapper{-webkit-transform-origin:left top;transform-origin:left top}:host(.select-rtl.select-fill-outline.select-label-placement-stacked) .label-text-wrapper,:host(.select-rtl.select-fill-outline.select-label-placement-floating) .label-text-wrapper{-webkit-transform-origin:right top;transform-origin:right top}:host(.select-fill-outline.select-label-placement-stacked) .label-text-wrapper,:host(.select-fill-outline.select-label-placement-floating) .label-text-wrapper{position:absolute;max-width:calc(100% - var(--padding-start) - var(--padding-end))}:host(.select-fill-outline) .label-text-wrapper{position:relative;z-index:1}:host(.label-floating.select-fill-outline) .label-text-wrapper{-webkit-transform:translateY(-32%) scale(0.75);transform:translateY(-32%) scale(0.75);margin-left:0;margin-right:0;margin-top:0;margin-bottom:0;max-width:calc((100% - var(--padding-start) - var(--padding-end) - 8px) / 0.75)}:host(.select-fill-outline.select-label-placement-stacked) select,:host(.select-fill-outline.select-label-placement-floating) select{margin-left:0;margin-right:0;margin-top:6px;margin-bottom:6px}:host(.select-fill-outline) .select-outline-container{left:0;right:0;top:0;bottom:0;display:-ms-flexbox;display:flex;position:absolute;width:100%;height:100%}:host(.select-fill-outline) .select-outline-start,:host(.select-fill-outline) .select-outline-end{pointer-events:none}:host(.select-fill-outline) .select-outline-start,:host(.select-fill-outline) .select-outline-notch,:host(.select-fill-outline) .select-outline-end{border-top:var(--border-width) var(--border-style) var(--border-color);border-bottom:var(--border-width) var(--border-style) var(--border-color);-webkit-box-sizing:border-box;box-sizing:border-box}:host(.select-fill-outline) .select-outline-notch{max-width:calc(100% - var(--padding-start) - var(--padding-end))}:host(.select-fill-outline) .notch-spacer{-webkit-padding-end:8px;padding-inline-end:8px;font-size:calc(1em * 0.75);opacity:0;pointer-events:none}:host(.select-fill-outline) .select-outline-start{-webkit-border-start:var(--border-width) var(--border-style) var(--border-color);border-inline-start:var(--border-width) var(--border-style) var(--border-color)}:host(.select-fill-outline) .select-outline-start{border-start-start-radius:var(--border-radius);border-start-end-radius:0px;border-end-end-radius:0px;border-end-start-radius:var(--border-radius)}:host(.select-fill-outline) .select-outline-start{width:calc(var(--padding-start) - 4px)}:host(.select-fill-outline) .select-outline-end{-webkit-border-end:var(--border-width) var(--border-style) var(--border-color);border-inline-end:var(--border-width) var(--border-style) var(--border-color)}:host(.select-fill-outline) .select-outline-end{border-start-start-radius:0px;border-start-end-radius:var(--border-radius);border-end-end-radius:var(--border-radius);border-end-start-radius:0px}:host(.select-fill-outline) .select-outline-end{-ms-flex-positive:1;flex-grow:1}:host(.label-floating.select-fill-outline) .select-outline-notch{border-top:none}:host(.in-item.select-expanded.select-fill-outline) .select-wrapper .select-icon,:host(.in-item.has-focus.select-fill-outline) .select-wrapper .select-icon,:host(.in-item.has-focus.ion-valid.select-fill-outline) .select-wrapper .select-icon,:host(.in-item.ion-touched.ion-invalid.select-fill-outline) .select-wrapper .select-icon{color:var(--highlight-color)}:host{--border-width:1px;--border-color:var(--ion-item-border-color, var(--ion-border-color, var(--ion-color-step-150, var(--ion-background-color-step-150, rgba(0, 0, 0, 0.13)))));--highlight-height:2px}:host(.select-label-placement-floating.select-expanded) .label-text-wrapper,:host(.select-label-placement-floating.has-focus) .label-text-wrapper,:host(.select-label-placement-stacked.select-expanded) .label-text-wrapper,:host(.select-label-placement-stacked.has-focus) .label-text-wrapper{color:var(--highlight-color)}:host(.has-focus.select-label-placement-floating.ion-valid) .label-text-wrapper,:host(.select-label-placement-floating.ion-touched.ion-invalid) .label-text-wrapper,:host(.has-focus.select-label-placement-stacked.ion-valid) .label-text-wrapper,:host(.select-label-placement-stacked.ion-touched.ion-invalid) .label-text-wrapper{color:var(--highlight-color)}.select-highlight{bottom:-1px;position:absolute;width:100%;height:var(--highlight-height);-webkit-transform:scale(0);transform:scale(0);-webkit-transition:-webkit-transform 200ms;transition:-webkit-transform 200ms;transition:transform 200ms;transition:transform 200ms, -webkit-transform 200ms;background:var(--highlight-color)}.select-highlight{inset-inline-start:0}:host(.select-expanded) .select-highlight,:host(.has-focus) .select-highlight{-webkit-transform:scale(1);transform:scale(1)}:host(.in-item) .select-highlight{bottom:0}:host(.in-item) .select-highlight{inset-inline-start:0}.select-icon{width:0.8125rem;-webkit-transition:-webkit-transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);transition:-webkit-transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);transition:transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);transition:transform 0.15s cubic-bezier(0.4, 0, 0.2, 1), -webkit-transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);color:var(--ion-color-step-500, var(--ion-text-color-step-500, gray))}:host(.select-expanded:not(.has-expanded-icon)) .select-icon{-webkit-transform:rotate(180deg);transform:rotate(180deg)}:host(.in-item.select-expanded) .select-wrapper .select-icon,:host(.in-item.has-focus) .select-wrapper .select-icon,:host(.in-item.has-focus.ion-valid) .select-wrapper .select-icon,:host(.in-item.ion-touched.ion-invalid) .select-wrapper .select-icon{color:var(--ion-color-step-500, var(--ion-text-color-step-500, gray))}:host(.select-expanded) .select-wrapper .select-icon,:host(.has-focus.ion-valid) .select-wrapper .select-icon,:host(.ion-touched.ion-invalid) .select-wrapper .select-icon,:host(.has-focus) .select-wrapper .select-icon{color:var(--highlight-color)}:host(.select-shape-round){--border-radius:16px}:host(.select-label-placement-stacked) .select-wrapper-inner,:host(.select-label-placement-floating) .select-wrapper-inner{width:calc(100% - 0.8125rem - 4px)}:host(.select-disabled){opacity:0.38}::slotted(ion-button[slot=start].button-has-icon-only),::slotted(ion-button[slot=end].button-has-icon-only){--border-radius:50%;--padding-start:8px;--padding-end:8px;--padding-top:8px;--padding-bottom:8px;aspect-ratio:1;min-height:40px}";

const Select = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
        this.ionChange = index.createEvent(this, "ionChange", 7);
        this.ionCancel = index.createEvent(this, "ionCancel", 7);
        this.ionDismiss = index.createEvent(this, "ionDismiss", 7);
        this.ionFocus = index.createEvent(this, "ionFocus", 7);
        this.ionBlur = index.createEvent(this, "ionBlur", 7);
        this.ionStyle = index.createEvent(this, "ionStyle", 7);
        this.inputId = `ion-sel-${selectIds++}`;
        this.helperTextId = `${this.inputId}-helper-text`;
        this.errorTextId = `${this.inputId}-error-text`;
        this.inheritedAttributes = {};
        this.isExpanded = false;
        /**
         * The `hasFocus` state ensures the focus class is
         * added regardless of how the element is focused.
         * The `ion-focused` class only applies when focused
         * via tabbing, not by clicking.
         * The `has-focus` logic was added to ensure the class
         * is applied in both cases.
         */
        this.hasFocus = false;
        /**
         * The text to display on the cancel button.
         */
        this.cancelText = 'Cancel';
        /**
         * If `true`, the user cannot interact with the select.
         */
        this.disabled = false;
        /**
         * The interface the select should use: `action-sheet`, `popover`, `alert`, or `modal`.
         */
        this.interface = 'alert';
        /**
         * Any additional options that the `alert`, `action-sheet` or `popover` interface
         * can take. See the [ion-alert docs](./alert), the
         * [ion-action-sheet docs](./action-sheet), the
         * [ion-popover docs](./popover), and the [ion-modal docs](./modal) for the
         * create options for each interface.
         *
         * Note: `interfaceOptions` will not override `inputs` or `buttons` with the `alert` interface.
         */
        this.interfaceOptions = {};
        /**
         * Where to place the label relative to the select.
         * `"start"`: The label will appear to the left of the select in LTR and to the right in RTL.
         * `"end"`: The label will appear to the right of the select in LTR and to the left in RTL.
         * `"floating"`: The label will appear smaller and above the select when the select is focused or it has a value. Otherwise it will appear on top of the select.
         * `"stacked"`: The label will appear smaller and above the select regardless even when the select is blurred or has no value.
         * `"fixed"`: The label has the same behavior as `"start"` except it also has a fixed width. Long text will be truncated with ellipses ("...").
         * When using `"floating"` or `"stacked"` we recommend initializing the select with either a `value` or a `placeholder`.
         */
        this.labelPlacement = 'start';
        /**
         * If `true`, the select can accept multiple values.
         */
        this.multiple = false;
        /**
         * The name of the control, which is submitted with the form data.
         */
        this.name = this.inputId;
        /**
         * The text to display on the ok button.
         */
        this.okText = 'OK';
        /**
         * If true, screen readers will announce it as a required field. This property
         * works only for accessibility purposes, it will not prevent the form from
         * submitting if the value is invalid.
         */
        this.required = false;
        this.onClick = (ev) => {
            const target = ev.target;
            const closestSlot = target.closest('[slot="start"], [slot="end"]');
            if (target === this.el || closestSlot === null) {
                this.setFocus();
                this.open(ev);
            }
            else {
                /**
                 * Prevent clicks to the start/end slots from opening the select.
                 * We ensure the target isn't this element in case the select is slotted
                 * in, for example, an item. This would prevent the select from ever
                 * being opened since the element itself has slot="start"/"end".
                 *
                 * Clicking a slotted element also causes a click
                 * on the <label> element (since it wraps the slots).
                 * Clicking <label> dispatches another click event on
                 * the native form control that then bubbles up to this
                 * listener. This additional event targets the host
                 * element, so the select overlay is opened.
                 *
                 * When the slotted elements are clicked (and therefore
                 * the ancestor <label> element) we want to prevent the label
                 * from dispatching another click event.
                 *
                 * Do not call stopPropagation() because this will cause
                 * click handlers on the slotted elements to never fire in React.
                 * When developers do onClick in React a native "click" listener
                 * is added on the root element, not the slotted element. When that
                 * native click listener fires, React then dispatches the synthetic
                 * click event on the slotted element. However, if stopPropagation
                 * is called then the native click event will never bubble up
                 * to the root element.
                 */
                ev.preventDefault();
            }
        };
        this.onFocus = () => {
            this.hasFocus = true;
            this.ionFocus.emit();
        };
        this.onBlur = () => {
            this.hasFocus = false;
            this.ionBlur.emit();
        };
        /**
         * Stops propagation when the label is clicked,
         * otherwise, two clicks will be triggered.
         */
        this.onLabelClick = (ev) => {
            // Only stop propagation if the click was directly on the label
            // and not on the input or other child elements
            if (ev.target === ev.currentTarget) {
                ev.stopPropagation();
            }
        };
    }
    styleChanged() {
        this.emitStyle();
    }
    setValue(value) {
        this.value = value;
        this.ionChange.emit({ value });
    }
    async connectedCallback() {
        const { el } = this;
        this.notchController = notchController.createNotchController(el, () => this.notchSpacerEl, () => this.labelSlot);
        this.updateOverlayOptions();
        this.emitStyle();
        this.mutationO = watchOptions.watchForOptions(this.el, 'ion-select-option', async () => {
            this.updateOverlayOptions();
            /**
             * We need to re-render the component
             * because one of the new ion-select-option
             * elements may match the value. In this case,
             * the rendered selected text should be updated.
             */
            index.forceUpdate(this);
        });
    }
    componentWillLoad() {
        this.inheritedAttributes = helpers.inheritAttributes(this.el, ['aria-label']);
    }
    componentDidLoad() {
        /**
         * If any of the conditions that trigger the styleChanged callback
         * are met on component load, it is possible the event emitted
         * prior to a parent web component registering an event listener.
         *
         * To ensure the parent web component receives the event, we
         * emit the style event again after the component has loaded.
         *
         * This is often seen in Angular with the `dist` output target.
         */
        this.emitStyle();
    }
    disconnectedCallback() {
        if (this.mutationO) {
            this.mutationO.disconnect();
            this.mutationO = undefined;
        }
        if (this.notchController) {
            this.notchController.destroy();
            this.notchController = undefined;
        }
    }
    /**
     * Open the select overlay. The overlay is either an alert, action sheet, or popover,
     * depending on the `interface` property on the `ion-select`.
     *
     * @param event The user interface event that called the open.
     */
    async open(event) {
        if (this.disabled || this.isExpanded) {
            return undefined;
        }
        this.isExpanded = true;
        const overlay = (this.overlay = await this.createOverlay(event));
        // Add logic to scroll selected item into view before presenting
        const scrollSelectedIntoView = () => {
            const indexOfSelected = this.childOpts.findIndex((o) => o.value === this.value);
            if (indexOfSelected > -1) {
                const selectedItem = overlay.querySelector(`.select-interface-option:nth-of-type(${indexOfSelected + 1})`);
                if (selectedItem) {
                    /**
                     * Browsers such as Firefox do not
                     * correctly delegate focus when manually
                     * focusing an element with delegatesFocus.
                     * We work around this by manually focusing
                     * the interactive element.
                     * ion-radio and ion-checkbox are the only
                     * elements that ion-select-popover uses, so
                     * we only need to worry about those two components
                     * when focusing.
                     */
                    const interactiveEl = selectedItem.querySelector('ion-radio, ion-checkbox');
                    if (interactiveEl) {
                        selectedItem.scrollIntoView({ block: 'nearest' });
                        // Needs to be called before `focusVisibleElement` to prevent issue with focus event bubbling
                        // and removing `ion-focused` style
                        interactiveEl.setFocus();
                    }
                    helpers.focusVisibleElement(selectedItem);
                }
            }
            else {
                /**
                 * If no value is set then focus the first enabled option.
                 */
                const firstEnabledOption = overlay.querySelector('ion-radio:not(.radio-disabled), ion-checkbox:not(.checkbox-disabled)');
                if (firstEnabledOption) {
                    /**
                     * Focus the option for the same reason as we do above.
                     *
                     * Needs to be called before `focusVisibleElement` to prevent issue with focus event bubbling
                     * and removing `ion-focused` style
                     */
                    firstEnabledOption.setFocus();
                    helpers.focusVisibleElement(firstEnabledOption.closest('ion-item'));
                }
            }
        };
        // For modals and popovers, we can scroll before they're visible
        if (this.interface === 'modal') {
            overlay.addEventListener('ionModalWillPresent', scrollSelectedIntoView, { once: true });
        }
        else if (this.interface === 'popover') {
            overlay.addEventListener('ionPopoverWillPresent', scrollSelectedIntoView, { once: true });
        }
        else {
            /**
             * For alerts and action sheets, we need to wait a frame after willPresent
             * because these overlays don't have their content in the DOM immediately
             * when willPresent fires. By waiting a frame, we ensure the content is
             * rendered and can be properly scrolled into view.
             */
            const scrollAfterRender = () => {
                requestAnimationFrame(() => {
                    scrollSelectedIntoView();
                });
            };
            if (this.interface === 'alert') {
                overlay.addEventListener('ionAlertWillPresent', scrollAfterRender, { once: true });
            }
            else if (this.interface === 'action-sheet') {
                overlay.addEventListener('ionActionSheetWillPresent', scrollAfterRender, { once: true });
            }
        }
        overlay.onDidDismiss().then(() => {
            this.overlay = undefined;
            this.isExpanded = false;
            this.ionDismiss.emit();
            this.setFocus();
        });
        await overlay.present();
        return overlay;
    }
    createOverlay(ev) {
        let selectInterface = this.interface;
        if (selectInterface === 'action-sheet' && this.multiple) {
            index.printIonWarning(`[ion-select] - Interface cannot be "${selectInterface}" with a multi-value select. Using the "alert" interface instead.`);
            selectInterface = 'alert';
        }
        if (selectInterface === 'popover' && !ev) {
            index.printIonWarning(`[ion-select] - Interface cannot be a "${selectInterface}" without passing an event. Using the "alert" interface instead.`);
            selectInterface = 'alert';
        }
        if (selectInterface === 'action-sheet') {
            return this.openActionSheet();
        }
        if (selectInterface === 'popover') {
            return this.openPopover(ev);
        }
        if (selectInterface === 'modal') {
            return this.openModal();
        }
        return this.openAlert();
    }
    updateOverlayOptions() {
        const overlay = this.overlay;
        if (!overlay) {
            return;
        }
        const childOpts = this.childOpts;
        const value = this.value;
        switch (this.interface) {
            case 'action-sheet':
                overlay.buttons = this.createActionSheetButtons(childOpts, value);
                break;
            case 'popover':
                const popover = overlay.querySelector('ion-select-popover');
                if (popover) {
                    popover.options = this.createOverlaySelectOptions(childOpts, value);
                }
                break;
            case 'modal':
                const modal = overlay.querySelector('ion-select-modal');
                if (modal) {
                    modal.options = this.createOverlaySelectOptions(childOpts, value);
                }
                break;
            case 'alert':
                const inputType = this.multiple ? 'checkbox' : 'radio';
                overlay.inputs = this.createAlertInputs(childOpts, inputType, value);
                break;
        }
    }
    createActionSheetButtons(data, selectValue) {
        const actionSheetButtons = data.map((option) => {
            const value = getOptionValue(option);
            // Remove hydrated before copying over classes
            const copyClasses = Array.from(option.classList)
                .filter((cls) => cls !== 'hydrated')
                .join(' ');
            const optClass = `${OPTION_CLASS} ${copyClasses}`;
            return {
                role: compareWithUtils.isOptionSelected(selectValue, value, this.compareWith) ? 'selected' : '',
                text: option.textContent,
                cssClass: optClass,
                handler: () => {
                    this.setValue(value);
                },
            };
        });
        // Add "cancel" button
        actionSheetButtons.push({
            text: this.cancelText,
            role: 'cancel',
            handler: () => {
                this.ionCancel.emit();
            },
        });
        return actionSheetButtons;
    }
    createAlertInputs(data, inputType, selectValue) {
        const alertInputs = data.map((option) => {
            const value = getOptionValue(option);
            // Remove hydrated before copying over classes
            const copyClasses = Array.from(option.classList)
                .filter((cls) => cls !== 'hydrated')
                .join(' ');
            const optClass = `${OPTION_CLASS} ${copyClasses}`;
            return {
                type: inputType,
                cssClass: optClass,
                label: option.textContent || '',
                value,
                checked: compareWithUtils.isOptionSelected(selectValue, value, this.compareWith),
                disabled: option.disabled,
            };
        });
        return alertInputs;
    }
    createOverlaySelectOptions(data, selectValue) {
        const popoverOptions = data.map((option) => {
            const value = getOptionValue(option);
            // Remove hydrated before copying over classes
            const copyClasses = Array.from(option.classList)
                .filter((cls) => cls !== 'hydrated')
                .join(' ');
            const optClass = `${OPTION_CLASS} ${copyClasses}`;
            return {
                text: option.textContent || '',
                cssClass: optClass,
                value,
                checked: compareWithUtils.isOptionSelected(selectValue, value, this.compareWith),
                disabled: option.disabled,
                handler: (selected) => {
                    this.setValue(selected);
                    if (!this.multiple) {
                        this.close();
                    }
                },
            };
        });
        return popoverOptions;
    }
    async openPopover(ev) {
        const { fill, labelPlacement } = this;
        const interfaceOptions = this.interfaceOptions;
        const mode = ionicGlobal.getIonMode(this);
        const showBackdrop = mode === 'md' ? false : true;
        const multiple = this.multiple;
        const value = this.value;
        let event = ev;
        let size = 'auto';
        const hasFloatingOrStackedLabel = labelPlacement === 'floating' || labelPlacement === 'stacked';
        /**
         * The popover should take up the full width
         * when using a fill in MD mode or if the
         * label is floating/stacked.
         */
        if (hasFloatingOrStackedLabel || (mode === 'md' && fill !== undefined)) {
            size = 'cover';
            /**
             * Otherwise the popover
             * should be positioned relative
             * to the native element.
             */
        }
        else {
            event = Object.assign(Object.assign({}, ev), { detail: {
                    ionShadowTarget: this.nativeWrapperEl,
                } });
        }
        const popoverOpts = Object.assign(Object.assign({ mode,
            event, alignment: 'center', size,
            showBackdrop }, interfaceOptions), { component: 'ion-select-popover', cssClass: ['select-popover', interfaceOptions.cssClass], componentProps: {
                header: interfaceOptions.header,
                subHeader: interfaceOptions.subHeader,
                message: interfaceOptions.message,
                multiple,
                value,
                options: this.createOverlaySelectOptions(this.childOpts, value),
            } });
        return overlays.popoverController.create(popoverOpts);
    }
    async openActionSheet() {
        const mode = ionicGlobal.getIonMode(this);
        const interfaceOptions = this.interfaceOptions;
        const actionSheetOpts = Object.assign(Object.assign({ mode }, interfaceOptions), { buttons: this.createActionSheetButtons(this.childOpts, this.value), cssClass: ['select-action-sheet', interfaceOptions.cssClass] });
        return overlays.actionSheetController.create(actionSheetOpts);
    }
    async openAlert() {
        const interfaceOptions = this.interfaceOptions;
        const inputType = this.multiple ? 'checkbox' : 'radio';
        const mode = ionicGlobal.getIonMode(this);
        const alertOpts = Object.assign(Object.assign({ mode }, interfaceOptions), { header: interfaceOptions.header ? interfaceOptions.header : this.labelText, inputs: this.createAlertInputs(this.childOpts, inputType, this.value), buttons: [
                {
                    text: this.cancelText,
                    role: 'cancel',
                    handler: () => {
                        this.ionCancel.emit();
                    },
                },
                {
                    text: this.okText,
                    handler: (selectedValues) => {
                        this.setValue(selectedValues);
                    },
                },
            ], cssClass: [
                'select-alert',
                interfaceOptions.cssClass,
                this.multiple ? 'multiple-select-alert' : 'single-select-alert',
            ] });
        return overlays.alertController.create(alertOpts);
    }
    openModal() {
        const { multiple, value, interfaceOptions } = this;
        const mode = ionicGlobal.getIonMode(this);
        const modalOpts = Object.assign(Object.assign({}, interfaceOptions), { mode, cssClass: ['select-modal', interfaceOptions.cssClass], component: 'ion-select-modal', componentProps: {
                header: interfaceOptions.header,
                multiple,
                value,
                options: this.createOverlaySelectOptions(this.childOpts, value),
            } });
        return overlays.modalController.create(modalOpts);
    }
    /**
     * Close the select interface.
     */
    close() {
        if (!this.overlay) {
            return Promise.resolve(false);
        }
        return this.overlay.dismiss();
    }
    hasValue() {
        return this.getText() !== '';
    }
    get childOpts() {
        return Array.from(this.el.querySelectorAll('ion-select-option'));
    }
    /**
     * Returns any plaintext associated with
     * the label (either prop or slot).
     * Note: This will not return any custom
     * HTML. Use the `hasLabel` getter if you
     * want to know if any slotted label content
     * was passed.
     */
    get labelText() {
        const { label } = this;
        if (label !== undefined) {
            return label;
        }
        const { labelSlot } = this;
        if (labelSlot !== null) {
            return labelSlot.textContent;
        }
        return;
    }
    getText() {
        const selectedText = this.selectedText;
        if (selectedText != null && selectedText !== '') {
            return selectedText;
        }
        return generateText(this.childOpts, this.value, this.compareWith);
    }
    setFocus() {
        if (this.focusEl) {
            this.focusEl.focus();
        }
    }
    emitStyle() {
        const { disabled } = this;
        const style = {
            'interactive-disabled': disabled,
        };
        this.ionStyle.emit(style);
    }
    renderLabel() {
        const { label } = this;
        return (index.h("div", { class: {
                'label-text-wrapper': true,
                'label-text-wrapper-hidden': !this.hasLabel,
            }, part: "label" }, label === undefined ? index.h("slot", { name: "label" }) : index.h("div", { class: "label-text" }, label)));
    }
    componentDidRender() {
        var _a;
        (_a = this.notchController) === null || _a === void 0 ? void 0 : _a.calculateNotchWidth();
    }
    /**
     * Gets any content passed into the `label` slot,
     * not the <slot> definition.
     */
    get labelSlot() {
        return this.el.querySelector('[slot="label"]');
    }
    /**
     * Returns `true` if label content is provided
     * either by a prop or a content. If you want
     * to get the plaintext value of the label use
     * the `labelText` getter instead.
     */
    get hasLabel() {
        return this.label !== undefined || this.labelSlot !== null;
    }
    /**
     * Renders the border container
     * when fill="outline".
     */
    renderLabelContainer() {
        const mode = ionicGlobal.getIonMode(this);
        const hasOutlineFill = mode === 'md' && this.fill === 'outline';
        if (hasOutlineFill) {
            /**
             * The outline fill has a special outline
             * that appears around the select and the label.
             * Certain stacked and floating label placements cause the
             * label to translate up and create a "cut out"
             * inside of that border by using the notch-spacer element.
             */
            return [
                index.h("div", { class: "select-outline-container" }, index.h("div", { class: "select-outline-start" }), index.h("div", { class: {
                        'select-outline-notch': true,
                        'select-outline-notch-hidden': !this.hasLabel,
                    } }, index.h("div", { class: "notch-spacer", "aria-hidden": "true", ref: (el) => (this.notchSpacerEl = el) }, this.label)), index.h("div", { class: "select-outline-end" })),
                this.renderLabel(),
            ];
        }
        /**
         * If not using the outline style,
         * we can render just the label.
         */
        return this.renderLabel();
    }
    /**
     * Renders either the placeholder
     * or the selected values based on
     * the state of the select.
     */
    renderSelectText() {
        const { placeholder } = this;
        const displayValue = this.getText();
        let addPlaceholderClass = false;
        let selectText = displayValue;
        if (selectText === '' && placeholder !== undefined) {
            selectText = placeholder;
            addPlaceholderClass = true;
        }
        const selectTextClasses = {
            'select-text': true,
            'select-placeholder': addPlaceholderClass,
        };
        const textPart = addPlaceholderClass ? 'placeholder' : 'text';
        return (index.h("div", { "aria-hidden": "true", class: selectTextClasses, part: textPart }, selectText));
    }
    /**
     * Renders the chevron icon
     * next to the select text.
     */
    renderSelectIcon() {
        const mode = ionicGlobal.getIonMode(this);
        const { isExpanded, toggleIcon, expandedIcon } = this;
        let icon;
        if (isExpanded && expandedIcon !== undefined) {
            icon = expandedIcon;
        }
        else {
            const defaultIcon = mode === 'ios' ? index$1.chevronExpand : index$1.caretDownSharp;
            icon = toggleIcon !== null && toggleIcon !== void 0 ? toggleIcon : defaultIcon;
        }
        return index.h("ion-icon", { class: "select-icon", part: "icon", "aria-hidden": "true", icon: icon });
    }
    get ariaLabel() {
        var _a;
        const { placeholder, inheritedAttributes } = this;
        const displayValue = this.getText();
        // The aria label should be preferred over visible text if both are specified
        const definedLabel = (_a = inheritedAttributes['aria-label']) !== null && _a !== void 0 ? _a : this.labelText;
        /**
         * If developer has specified a placeholder
         * and there is nothing selected, the selectText
         * should have the placeholder value.
         */
        let renderedLabel = displayValue;
        if (renderedLabel === '' && placeholder !== undefined) {
            renderedLabel = placeholder;
        }
        /**
         * If there is a developer-defined label,
         * then we need to concatenate the developer label
         * string with the current current value.
         * The label for the control should be read
         * before the values of the control.
         */
        if (definedLabel !== undefined) {
            renderedLabel = renderedLabel === '' ? definedLabel : `${definedLabel}, ${renderedLabel}`;
        }
        return renderedLabel;
    }
    renderListbox() {
        const { disabled, inputId, isExpanded, required } = this;
        return (index.h("button", { disabled: disabled, id: inputId, "aria-label": this.ariaLabel, "aria-haspopup": "dialog", "aria-expanded": `${isExpanded}`, "aria-describedby": this.getHintTextID(), "aria-invalid": this.getHintTextID() === this.errorTextId, "aria-required": `${required}`, onFocus: this.onFocus, onBlur: this.onBlur, ref: (focusEl) => (this.focusEl = focusEl) }));
    }
    getHintTextID() {
        const { el, helperText, errorText, helperTextId, errorTextId } = this;
        if (el.classList.contains('ion-touched') && el.classList.contains('ion-invalid') && errorText) {
            return errorTextId;
        }
        if (helperText) {
            return helperTextId;
        }
        return undefined;
    }
    /**
     * Renders the helper text or error text values
     */
    renderHintText() {
        const { helperText, errorText, helperTextId, errorTextId } = this;
        return [
            index.h("div", { id: helperTextId, class: "helper-text", part: "supporting-text helper-text" }, helperText),
            index.h("div", { id: errorTextId, class: "error-text", part: "supporting-text error-text" }, errorText),
        ];
    }
    /**
     * Responsible for rendering helper text, and error text. This element
     * should only be rendered if hint text is set.
     */
    renderBottomContent() {
        const { helperText, errorText } = this;
        /**
         * undefined and empty string values should
         * be treated as not having helper/error text.
         */
        const hasHintText = !!helperText || !!errorText;
        if (!hasHintText) {
            return;
        }
        return index.h("div", { class: "select-bottom" }, this.renderHintText());
    }
    render() {
        const { disabled, el, isExpanded, expandedIcon, labelPlacement, justify, placeholder, fill, shape, name, value, hasFocus, } = this;
        const mode = ionicGlobal.getIonMode(this);
        const hasFloatingOrStackedLabel = labelPlacement === 'floating' || labelPlacement === 'stacked';
        const justifyEnabled = !hasFloatingOrStackedLabel && justify !== undefined;
        const rtl = dir.isRTL(el) ? 'rtl' : 'ltr';
        const inItem = theme.hostContext('ion-item', this.el);
        const shouldRenderHighlight = mode === 'md' && fill !== 'outline' && !inItem;
        const hasValue = this.hasValue();
        const hasStartEndSlots = el.querySelector('[slot="start"], [slot="end"]') !== null;
        helpers.renderHiddenInput(true, el, name, parseValue(value), disabled);
        /**
         * If the label is stacked, it should always sit above the select.
         * For floating labels, the label should move above the select if
         * the select has a value, is open, or has anything in either
         * the start or end slot.
         *
         * If there is content in the start slot, the label would overlap
         * it if not forced to float. This is also applied to the end slot
         * because with the default or solid fills, the select is not
         * vertically centered in the container, but the label is. This
         * causes the slots and label to appear vertically offset from each
         * other when the label isn't floating above the input. This doesn't
         * apply to the outline fill, but this was not accounted for to keep
         * things consistent.
         *
         * TODO(FW-5592): Remove hasStartEndSlots condition
         */
        const labelShouldFloat = labelPlacement === 'stacked' || (labelPlacement === 'floating' && (hasValue || isExpanded || hasStartEndSlots));
        return (index.h(index.Host, { key: 'c03fb65e8fc9f9aab295e07b282377d57d910519', onClick: this.onClick, class: theme.createColorClasses(this.color, {
                [mode]: true,
                'in-item': inItem,
                'in-item-color': theme.hostContext('ion-item.ion-color', el),
                'select-disabled': disabled,
                'select-expanded': isExpanded,
                'has-expanded-icon': expandedIcon !== undefined,
                'has-value': hasValue,
                'label-floating': labelShouldFloat,
                'has-placeholder': placeholder !== undefined,
                'has-focus': hasFocus,
                // TODO(FW-6451): Remove `ion-focusable` class in favor of `has-focus`.
                'ion-focusable': true,
                [`select-${rtl}`]: true,
                [`select-fill-${fill}`]: fill !== undefined,
                [`select-justify-${justify}`]: justifyEnabled,
                [`select-shape-${shape}`]: shape !== undefined,
                [`select-label-placement-${labelPlacement}`]: true,
            }) }, index.h("label", { key: '0d0c8ec55269adcac625f2899a547f4e7f3e3741', class: "select-wrapper", id: "select-label", onClick: this.onLabelClick }, this.renderLabelContainer(), index.h("div", { key: 'f6dfc93c0e23cbe75a2947abde67d842db2dad78', class: "select-wrapper-inner" }, index.h("slot", { key: '957bfadf9f101f519091419a362d3abdc2be66f6', name: "start" }), index.h("div", { key: 'ca349202a484e7f2e884533fd330f0b136754f7d', class: "native-wrapper", ref: (el) => (this.nativeWrapperEl = el), part: "container" }, this.renderSelectText(), this.renderListbox()), index.h("slot", { key: 'f0e62a6533ff1c8f62bd2d27f60b23385c4fa9ed', name: "end" }), !hasFloatingOrStackedLabel && this.renderSelectIcon()), hasFloatingOrStackedLabel && this.renderSelectIcon(), shouldRenderHighlight && index.h("div", { key: 'fb840d46bafafb09898ebeebbe8c181906a3d8a2', class: "select-highlight" })), this.renderBottomContent()));
    }
    get el() { return index.getElement(this); }
    static get watchers() { return {
        "disabled": ["styleChanged"],
        "isExpanded": ["styleChanged"],
        "placeholder": ["styleChanged"],
        "value": ["styleChanged"]
    }; }
};
const getOptionValue = (el) => {
    const value = el.value;
    return value === undefined ? el.textContent || '' : value;
};
const parseValue = (value) => {
    if (value == null) {
        return undefined;
    }
    if (Array.isArray(value)) {
        return value.join(',');
    }
    return value.toString();
};
const generateText = (opts, value, compareWith) => {
    if (value === undefined) {
        return '';
    }
    if (Array.isArray(value)) {
        return value
            .map((v) => textForValue(opts, v, compareWith))
            .filter((opt) => opt !== null)
            .join(', ');
    }
    else {
        return textForValue(opts, value, compareWith) || '';
    }
};
const textForValue = (opts, value, compareWith) => {
    const selectOpt = opts.find((opt) => {
        return compareWithUtils.compareOptions(value, getOptionValue(opt), compareWith);
    });
    return selectOpt ? selectOpt.textContent : null;
};
let selectIds = 0;
const OPTION_CLASS = 'select-interface-option';
Select.style = {
    ios: selectIosCss,
    md: selectMdCss
};

const selectOptionCss = ":host{display:none}";

const SelectOption = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
        this.inputId = `ion-selopt-${selectOptionIds++}`;
        /**
         * If `true`, the user cannot interact with the select option. This property does not apply when `interface="action-sheet"` as `ion-action-sheet` does not allow for disabled buttons.
         */
        this.disabled = false;
    }
    render() {
        return index.h(index.Host, { key: '3a70eea9fa03a9acba582180761d18347c72acee', role: "option", id: this.inputId, class: ionicGlobal.getIonMode(this) });
    }
    get el() { return index.getElement(this); }
};
let selectOptionIds = 0;
SelectOption.style = selectOptionCss;

const selectPopoverIosCss = ".sc-ion-select-popover-ios-h ion-list.sc-ion-select-popover-ios{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}ion-list-header.sc-ion-select-popover-ios,ion-label.sc-ion-select-popover-ios{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}.sc-ion-select-popover-ios-h{overflow-y:auto}";

const selectPopoverMdCss = ".sc-ion-select-popover-md-h ion-list.sc-ion-select-popover-md{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}ion-list-header.sc-ion-select-popover-md,ion-label.sc-ion-select-popover-md{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}.sc-ion-select-popover-md-h{overflow-y:auto}ion-list.sc-ion-select-popover-md ion-radio.sc-ion-select-popover-md::part(container),ion-list.sc-ion-select-popover-md ion-radio.sc-ion-select-popover-md [part~=\"container\"]{display:none}ion-list.sc-ion-select-popover-md ion-radio.sc-ion-select-popover-md::part(label),ion-list.sc-ion-select-popover-md ion-radio.sc-ion-select-popover-md [part~=\"label\"]{margin-left:0;margin-right:0;margin-top:0;margin-bottom:0}ion-item.sc-ion-select-popover-md{--inner-border-width:0}.item-radio-checked.sc-ion-select-popover-md{--background:rgba(var(--ion-color-primary-rgb, 0, 84, 233), 0.08);--background-focused:var(--ion-color-primary, #0054e9);--background-focused-opacity:0.2;--background-hover:var(--ion-color-primary, #0054e9);--background-hover-opacity:0.12}.item-checkbox-checked.sc-ion-select-popover-md{--background-activated:var(--ion-item-color, var(--ion-text-color, #000));--background-focused:var(--ion-item-color, var(--ion-text-color, #000));--background-hover:var(--ion-item-color, var(--ion-text-color, #000));--color:var(--ion-color-primary, #0054e9)}";

const SelectPopover = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
        /**
         * An array of options for the popover
         */
        this.options = [];
    }
    findOptionFromEvent(ev) {
        const { options } = this;
        return options.find((o) => o.value === ev.target.value);
    }
    /**
     * When an option is selected we need to get the value(s)
     * of the selected option(s) and return it in the option
     * handler
     */
    callOptionHandler(ev) {
        const option = this.findOptionFromEvent(ev);
        const values = this.getValues(ev);
        if (option === null || option === void 0 ? void 0 : option.handler) {
            overlays.safeCall(option.handler, values);
        }
    }
    /**
     * Dismisses the host popover that the `ion-select-popover`
     * is rendered within.
     */
    dismissParentPopover() {
        const popover = this.el.closest('ion-popover');
        if (popover) {
            popover.dismiss();
        }
    }
    setChecked(ev) {
        const { multiple } = this;
        const option = this.findOptionFromEvent(ev);
        // this is a popover with checkboxes (multiple value select)
        // we need to set the checked value for this option
        if (multiple && option) {
            option.checked = ev.detail.checked;
        }
    }
    getValues(ev) {
        const { multiple, options } = this;
        if (multiple) {
            // this is a popover with checkboxes (multiple value select)
            // return an array of all the checked values
            return options.filter((o) => o.checked).map((o) => o.value);
        }
        // this is a popover with radio buttons (single value select)
        // return the value that was clicked, otherwise undefined
        const option = this.findOptionFromEvent(ev);
        return option ? option.value : undefined;
    }
    renderOptions(options) {
        const { multiple } = this;
        switch (multiple) {
            case true:
                return this.renderCheckboxOptions(options);
            default:
                return this.renderRadioOptions(options);
        }
    }
    renderCheckboxOptions(options) {
        return options.map((option) => (index.h("ion-item", { class: Object.assign({
                // TODO FW-4784
                'item-checkbox-checked': option.checked
            }, theme.getClassMap(option.cssClass)) }, index.h("ion-checkbox", { value: option.value, disabled: option.disabled, checked: option.checked, justify: "start", labelPlacement: "end", onIonChange: (ev) => {
                this.setChecked(ev);
                this.callOptionHandler(ev);
                // TODO FW-4784
                index.forceUpdate(this);
            } }, option.text))));
    }
    renderRadioOptions(options) {
        const checked = options.filter((o) => o.checked).map((o) => o.value)[0];
        return (index.h("ion-radio-group", { value: checked, onIonChange: (ev) => this.callOptionHandler(ev) }, options.map((option) => (index.h("ion-item", { class: Object.assign({
                // TODO FW-4784
                'item-radio-checked': option.value === checked
            }, theme.getClassMap(option.cssClass)) }, index.h("ion-radio", { value: option.value, disabled: option.disabled, onClick: () => this.dismissParentPopover(), onKeyUp: (ev) => {
                if (ev.key === ' ') {
                    /**
                     * Selecting a radio option with keyboard navigation,
                     * either through the Enter or Space keys, should
                     * dismiss the popover.
                     */
                    this.dismissParentPopover();
                }
            } }, option.text))))));
    }
    render() {
        const { header, message, options, subHeader } = this;
        const hasSubHeaderOrMessage = subHeader !== undefined || message !== undefined;
        return (index.h(index.Host, { key: 'ab931b49b59283825bd2afa3f7f995b0e6e05bef', class: ionicGlobal.getIonMode(this) }, index.h("ion-list", { key: '3bd12b67832607596b912a73d5b3ae9b954b244d' }, header !== undefined && index.h("ion-list-header", { key: '97da930246edf7423a039c030d40e3ff7a5148a3' }, header), hasSubHeaderOrMessage && (index.h("ion-item", { key: 'c579df6ea8fac07bb0c59d34c69b149656863224' }, index.h("ion-label", { key: 'af699c5f465710ccb13b8cf8e7be66f0e8acfad1', class: "ion-text-wrap" }, subHeader !== undefined && index.h("h3", { key: 'df9a936d42064b134e843c7229f314a2a3ec7e80' }, subHeader), message !== undefined && index.h("p", { key: '9c3ddad378df00f106afa94e9928cf68c17124dd' }, message)))), this.renderOptions(options))));
    }
    get el() { return index.getElement(this); }
};
SelectPopover.style = {
    ios: selectPopoverIosCss,
    md: selectPopoverMdCss
};

exports.ion_select = Select;
exports.ion_select_option = SelectOption;
exports.ion_select_popover = SelectPopover;
