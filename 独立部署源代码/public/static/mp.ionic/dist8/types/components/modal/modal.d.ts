import type { ComponentInterface, EventEmitter } from '../../stencil-public-runtime';
import type { Animation, AnimationBuilder, ComponentProps, ComponentRef, FrameworkDelegate, OverlayInterface } from '../../interface';
import type { OverlayEventDetail } from '../../utils/overlays-interface';
import type { ModalBreakpointChangeEventDetail, ModalHandleBehavior } from './modal-interface';
/**
 * @virtualProp {"ios" | "md"} mode - The mode determines which platform styles to use.
 *
 * @slot - Content is placed inside of the `.modal-content` element.
 *
 * @part backdrop - The `ion-backdrop` element.
 * @part content - The wrapper element for the default slot.
 * @part handle - The handle that is displayed at the top of the sheet modal when `handle="true"`.
 */
export declare class Modal implements ComponentInterface, OverlayInterface {
    private readonly lockController;
    private readonly triggerController;
    private gesture?;
    private coreDelegate;
    private sheetTransition?;
    private isSheetModal;
    private currentBreakpoint?;
    private wrapperEl?;
    private backdropEl?;
    private dragHandleEl?;
    private sortedBreakpoints?;
    private keyboardOpenCallback?;
    private moveSheetToBreakpoint?;
    private inheritedAttributes;
    private statusBarStyle?;
    private inline;
    private workingDelegate?;
    private usersElement?;
    private gestureAnimationDismissing;
    private currentViewIsPortrait?;
    private viewTransitionAnimation?;
    private resizeTimeout?;
    private parentRemovalObserver?;
    private cachedOriginalParent?;
    lastFocus?: HTMLElement;
    animation?: Animation;
    presented: boolean;
    el: HTMLIonModalElement;
    /** @internal */
    hasController: boolean;
    /** @internal */
    overlayIndex: number;
    /** @internal */
    delegate?: FrameworkDelegate;
    /**
     * If `true`, the keyboard will be automatically dismissed when the overlay is presented.
     */
    keyboardClose: boolean;
    /**
     * Animation to use when the modal is presented.
     */
    enterAnimation?: AnimationBuilder;
    /**
     * Animation to use when the modal is dismissed.
     */
    leaveAnimation?: AnimationBuilder;
    /**
     * The breakpoints to use when creating a sheet modal. Each value in the
     * array must be a decimal between 0 and 1 where 0 indicates the modal is fully
     * closed and 1 indicates the modal is fully open. Values are relative
     * to the height of the modal, not the height of the screen. One of the values in this
     * array must be the value of the `initialBreakpoint` property.
     * For example: [0, .25, .5, 1]
     */
    breakpoints?: number[];
    /**
     * Controls whether scrolling or dragging within the sheet modal expands
     * it to a larger breakpoint. This only takes effect when `breakpoints`
     * and `initialBreakpoint` are set.
     *
     * If `true`, scrolling or dragging anywhere in the modal will first expand
     * it to the next breakpoint. Once fully expanded, scrolling will affect the
     * content.
     * If `false`, scrolling will always affect the content. The modal will
     * only expand when dragging the header or handle. The modal will close when
     * dragging the header or handle. It can also be closed when dragging the
     * content, but only if the content is scrolled to the top.
     */
    expandToScroll: boolean;
    /**
     * A decimal value between 0 and 1 that indicates the
     * initial point the modal will open at when creating a
     * sheet modal. This value must also be listed in the
     * `breakpoints` array.
     */
    initialBreakpoint?: number;
    /**
     * A decimal value between 0 and 1 that indicates the
     * point after which the backdrop will begin to fade in
     * when using a sheet modal. Prior to this point, the
     * backdrop will be hidden and the content underneath
     * the sheet can be interacted with. This value is exclusive
     * meaning the backdrop will become active after the value
     * specified.
     */
    backdropBreakpoint: number;
    /**
     * The horizontal line that displays at the top of a sheet modal. It is `true` by default when
     * setting the `breakpoints` and `initialBreakpoint` properties.
     */
    handle?: boolean;
    /**
     * The interaction behavior for the sheet modal when the handle is pressed.
     *
     * Defaults to `"none"`, which  means the modal will not change size or position when the handle is pressed.
     * Set to `"cycle"` to let the modal cycle between available breakpoints when pressed.
     *
     * Handle behavior is unavailable when the `handle` property is set to `false` or
     * when the `breakpoints` property is not set (using a fullscreen or card modal).
     */
    handleBehavior?: ModalHandleBehavior;
    /**
     * The component to display inside of the modal.
     * @internal
     */
    component?: ComponentRef;
    /**
     * The data to pass to the modal component.
     * @internal
     */
    componentProps?: ComponentProps;
    /**
     * Additional classes to apply for custom CSS. If multiple classes are
     * provided they should be separated by spaces.
     * @internal
     */
    cssClass?: string | string[];
    /**
     * If `true`, the modal will be dismissed when the backdrop is clicked.
     */
    backdropDismiss: boolean;
    /**
     * If `true`, a backdrop will be displayed behind the modal.
     * This property controls whether or not the backdrop
     * darkens the screen when the modal is presented.
     * It does not control whether or not the backdrop
     * is active or present in the DOM.
     */
    showBackdrop: boolean;
    /**
     * If `true`, the modal will animate.
     */
    animated: boolean;
    /**
     * The element that presented the modal. This is used for card presentation effects
     * and for stacking multiple modals on top of each other. Only applies in iOS mode.
     */
    presentingElement?: HTMLElement;
    /**
     * Additional attributes to pass to the modal.
     */
    htmlAttributes?: {
        [key: string]: any;
    };
    /**
     * If `true`, the modal will open. If `false`, the modal will close.
     * Use this if you need finer grained control over presentation, otherwise
     * just use the modalController or the `trigger` property.
     * Note: `isOpen` will not automatically be set back to `false` when
     * the modal dismisses. You will need to do that in your code.
     */
    isOpen: boolean;
    onIsOpenChange(newValue: boolean, oldValue: boolean): void;
    /**
     * An ID corresponding to the trigger element that
     * causes the modal to open when clicked.
     */
    trigger: string | undefined;
    triggerChanged(): void;
    onWindowResize(): void;
    /**
     * If `true`, the component passed into `ion-modal` will
     * automatically be mounted when the modal is created. The
     * component will remain mounted even when the modal is dismissed.
     * However, the component will be destroyed when the modal is
     * destroyed. This property is not reactive and should only be
     * used when initially creating a modal.
     *
     * Note: This feature only applies to inline modals in JavaScript
     * frameworks such as Angular, React, and Vue.
     */
    keepContentsMounted: boolean;
    /**
     * If `true`, focus will not be allowed to move outside of this overlay.
     * If `false`, focus will be allowed to move outside of the overlay.
     *
     * In most scenarios this property should remain set to `true`. Setting
     * this property to `false` can cause severe accessibility issues as users
     * relying on assistive technologies may be able to move focus into
     * a confusing state. We recommend only setting this to `false` when
     * absolutely necessary.
     *
     * Developers may want to consider disabling focus trapping if this
     * overlay presents a non-Ionic overlay from a 3rd party library.
     * Developers would disable focus trapping on the Ionic overlay
     * when presenting the 3rd party overlay and then re-enable
     * focus trapping when dismissing the 3rd party overlay and moving
     * focus back to the Ionic overlay.
     */
    focusTrap: boolean;
    /**
     * Determines whether or not a modal can dismiss
     * when calling the `dismiss` method.
     *
     * If the value is `true` or the value's function returns `true`, the modal will close when trying to dismiss.
     * If the value is `false` or the value's function returns `false`, the modal will not close when trying to dismiss.
     *
     * See https://ionicframework.com/docs/troubleshooting/runtime#accessing-this
     * if you need to access `this` from within the callback.
     */
    canDismiss: boolean | ((data?: any, role?: string) => Promise<boolean>);
    /**
     * Emitted after the modal has presented.
     */
    didPresent: EventEmitter<void>;
    /**
     * Emitted before the modal has presented.
     */
    willPresent: EventEmitter<void>;
    /**
     * Emitted before the modal has dismissed.
     */
    willDismiss: EventEmitter<OverlayEventDetail>;
    /**
     * Emitted after the modal has dismissed.
     */
    didDismiss: EventEmitter<OverlayEventDetail>;
    /**
     * Emitted after the modal breakpoint has changed.
     */
    ionBreakpointDidChange: EventEmitter<ModalBreakpointChangeEventDetail>;
    /**
     * Emitted after the modal has presented.
     * Shorthand for ionModalDidPresent.
     */
    didPresentShorthand: EventEmitter<void>;
    /**
     * Emitted before the modal has presented.
     * Shorthand for ionModalWillPresent.
     */
    willPresentShorthand: EventEmitter<void>;
    /**
     * Emitted before the modal has dismissed.
     * Shorthand for ionModalWillDismiss.
     */
    willDismissShorthand: EventEmitter<OverlayEventDetail>;
    /**
     * Emitted after the modal has dismissed.
     * Shorthand for ionModalDidDismiss.
     */
    didDismissShorthand: EventEmitter<OverlayEventDetail>;
    /**
     * Emitted before the modal has presented, but after the component
     * has been mounted in the DOM.
     * This event exists so iOS can run the entering
     * transition properly
     *
     * @internal
     */
    ionMount: EventEmitter<void>;
    breakpointsChanged(breakpoints: number[] | undefined): void;
    connectedCallback(): void;
    disconnectedCallback(): void;
    componentWillLoad(): void;
    componentDidLoad(): void;
    /**
     * Determines whether or not an overlay
     * is being used inline or via a controller/JS
     * and returns the correct delegate.
     * By default, subsequent calls to getDelegate
     * will use a cached version of the delegate.
     * This is useful for calling dismiss after
     * present so that the correct delegate is given.
     */
    private getDelegate;
    /**
     * Determines whether or not the
     * modal is allowed to dismiss based
     * on the state of the canDismiss prop.
     */
    private checkCanDismiss;
    /**
     * Present the modal overlay after it has been created.
     */
    present(): Promise<void>;
    private initSwipeToClose;
    private initSheetGesture;
    private sheetOnDismiss;
    /**
     * Dismiss the modal overlay after it has been presented.
     * This is a no-op if the overlay has not been presented yet. If you want
     * to remove an overlay from the DOM that was never presented, use the
     * [remove](https://developer.mozilla.org/en-US/docs/Web/API/Element/remove) method.
     *
     * @param data Any data to emit in the dismiss events.
     * @param role The role of the element that is dismissing the modal.
     * For example, `cancel` or `backdrop`.
     */
    dismiss(data?: any, role?: string): Promise<boolean>;
    /**
     * Returns a promise that resolves when the modal did dismiss.
     */
    onDidDismiss<T = any>(): Promise<OverlayEventDetail<T>>;
    /**
     * Returns a promise that resolves when the modal will dismiss.
     */
    onWillDismiss<T = any>(): Promise<OverlayEventDetail<T>>;
    /**
     * Move a sheet style modal to a specific breakpoint.
     *
     * @param breakpoint The breakpoint value to move the sheet modal to.
     * Must be a value defined in your `breakpoints` array.
     */
    setCurrentBreakpoint(breakpoint: number): Promise<void>;
    /**
     * Returns the current breakpoint of a sheet style modal
     */
    getCurrentBreakpoint(): Promise<number | undefined>;
    private moveToNextBreakpoint;
    private onHandleClick;
    private onBackdropTap;
    private onLifecycle;
    /**
     * When the modal receives focus directly, pass focus to the handle
     * if it exists and is focusable, otherwise let the focus trap handle it.
     */
    private onModalFocus;
    private initViewTransitionListener;
    private handleViewTransition;
    private cleanupViewTransitionListener;
    private reinitSwipeToClose;
    private ensureCorrectModalPosition;
    /**
     * When the slot changes, we need to find all the modals in the slot
     * and set the data-parent-ion-modal attribute on them so we can find them
     * and dismiss them when we get dismissed.
     * We need to do it this way because when a modal is opened, it's moved to
     * the end of the body and is no longer an actual child of the modal.
     */
    private onSlotChange;
    private dismissNestedModals;
    private initParentRemovalObserver;
    private cleanupParentRemovalObserver;
    render(): any;
}
