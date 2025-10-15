import type { ComponentInterface, EventEmitter } from '../../stencil-public-runtime';
import type { MenuChangeEventDetail, MenuCloseEventDetail, MenuI, MenuType, Side } from './menu-interface';
/**
 * @part container - The container for the menu content.
 * @part backdrop - The backdrop that appears over the main content when the menu is open.
 */
export declare class Menu implements ComponentInterface, MenuI {
    private animation?;
    private lastOnEnd;
    private gesture?;
    private blocker;
    private didLoad;
    /**
     * Flag used to determine if an open/close
     * operation was cancelled. For example, if
     * an app calls "menu.open" then disables the menu
     * part way through the animation, then this would
     * be considered a cancelled operation.
     */
    private operationCancelled;
    isAnimating: boolean;
    width: number;
    _isOpen: boolean;
    backdropEl?: HTMLIonBackdropElement;
    menuInnerEl?: HTMLElement;
    contentEl?: HTMLElement;
    lastFocus?: HTMLElement;
    private inheritedAttributes;
    private handleFocus;
    el: HTMLIonMenuElement;
    /**
     * If true, then the menu should be
     * visible within a split pane.
     * If false, then the menu is hidden.
     * However, the menu-button/menu-toggle
     * components can be used to open the
     * menu.
     */
    isPaneVisible: boolean;
    isEndSide: boolean;
    /**
     * The `id` of the main content. When using
     * a router this is typically `ion-router-outlet`.
     * When not using a router, this is typically
     * your main view's `ion-content`. This is not the
     * id of the `ion-content` inside of your `ion-menu`.
     */
    contentId?: string;
    /**
     * An id for the menu.
     */
    menuId?: string;
    /**
     * The display type of the menu.
     * Available options: `"overlay"`, `"reveal"`, `"push"`.
     */
    type?: MenuType;
    typeChanged(type: string, oldType: string | undefined): void;
    /**
     * If `true`, the menu is disabled.
     */
    disabled: boolean;
    protected disabledChanged(): void;
    /**
     * Which side of the view the menu should be placed.
     */
    side: Side;
    protected sideChanged(): void;
    /**
     * If `true`, swiping the menu is enabled.
     */
    swipeGesture: boolean;
    protected swipeGestureChanged(): void;
    /**
     * The edge threshold for dragging the menu open.
     * If a drag/swipe happens over this value, the menu is not triggered.
     */
    maxEdgeStart: number;
    /**
     * Emitted when the menu is about to be opened.
     */
    ionWillOpen: EventEmitter<void>;
    /**
     * Emitted when the menu is about to be closed.
     */
    ionWillClose: EventEmitter<MenuCloseEventDetail>;
    /**
     * Emitted when the menu is open.
     */
    ionDidOpen: EventEmitter<void>;
    /**
     * Emitted when the menu is closed.
     */
    ionDidClose: EventEmitter<MenuCloseEventDetail>;
    /**
     * Emitted when the menu state is changed.
     * @internal
     */
    protected ionMenuChange: EventEmitter<MenuChangeEventDetail>;
    connectedCallback(): Promise<void>;
    componentWillLoad(): void;
    componentDidLoad(): Promise<void>;
    private menuChanged;
    disconnectedCallback(): Promise<void>;
    onSplitPaneChanged(ev: CustomEvent<{
        visible: boolean;
    }>): void;
    onBackdropClick(ev: any): void;
    onKeydown(ev: KeyboardEvent): void;
    /**
     * Returns `true` is the menu is open.
     */
    isOpen(): Promise<boolean>;
    /**
     * Returns `true` if the menu is active.
     *
     * A menu is active when it can be opened or closed, meaning it's enabled
     * and it's not part of a `ion-split-pane`.
     */
    isActive(): Promise<boolean>;
    /**
     * Opens the menu. If the menu is already open or it can't be opened,
     * it returns `false`.
     *
     * @param animated If `true`, the menu will animate when opening.
     * If `false`, the menu will open instantly without animation.
     * Defaults to `true`.
     */
    open(animated?: boolean): Promise<boolean>;
    /**
     * Closes the menu. If the menu is already closed or it can't be closed,
     * it returns `false`.
     *
     * @param animated If `true`, the menu will animate when closing. If `false`,
     * the menu will close instantly without animation. Defaults to `true`.
     * @param role The role of the element that is closing the menu.
     * This can be useful in a button handler for determining which button was
     * clicked to close the menu. Some examples include:
     * `"cancel"`, `"destructive"`, `"selected"`, and `"backdrop"`.
     */
    close(animated?: boolean, role?: string): Promise<boolean>;
    /**
     * Toggles the menu. If the menu is already open, it will try to close,
     * otherwise it will try to open it.
     * If the operation can't be completed successfully, it returns `false`.
     *
     * @param animated If `true`, the menu will animate when opening/closing.
     * If `false`, the menu will open/close instantly without animation.
     * Defaults to `true`.
     */
    toggle(animated?: boolean): Promise<boolean>;
    /**
     * Opens or closes the menu.
     * If the operation can't be completed successfully, it returns `false`.
     *
     * @param shouldOpen If `true`, the menu will open. If `false`, the menu
     * will close.
     * @param animated If `true`, the menu will animate when opening/closing.
     * If `false`, the menu will open/close instantly without animation.
     * @param role The role of the element that is closing the menu.
     */
    setOpen(shouldOpen: boolean, animated?: boolean, role?: string): Promise<boolean>;
    private trapKeyboardFocus;
    _setOpen(shouldOpen: boolean, animated?: boolean, role?: string): Promise<boolean>;
    private loadAnimation;
    private startAnimation;
    private _isActive;
    private canSwipe;
    private canStart;
    private onWillStart;
    private onStart;
    private onMove;
    private onEnd;
    private beforeAnimation;
    private afterAnimation;
    private updateState;
    render(): any;
}
