/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { w as win } from './index-ZjP4CjeZ.js';

const getCapacitor = () => {
    if (win !== undefined) {
        return win.Capacitor;
    }
    return undefined;
};

export { getCapacitor as g };
