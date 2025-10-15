import type { Animation } from '../../../interface';
import type { ModalAnimationOptions } from '../modal-interface';
/**
 * Transition animation from portrait view to landscape view
 * This handles the case where a card modal is open in portrait view
 * and the user switches to landscape view
 */
export declare const portraitToLandscapeTransition: (baseEl: HTMLElement, opts: ModalAnimationOptions, duration?: number) => Animation;
/**
 * Transition animation from landscape view to portrait view
 * This handles the case where a card modal is open in landscape view
 * and the user switches to portrait view
 */
export declare const landscapeToPortraitTransition: (baseEl: HTMLElement, opts: ModalAnimationOptions, duration?: number) => Animation;
