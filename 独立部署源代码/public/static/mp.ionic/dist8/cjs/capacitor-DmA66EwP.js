/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
'use strict';

var index = require('./index-DkNv4J_i.js');

const getCapacitor = () => {
    if (index.win !== undefined) {
        return index.win.Capacitor;
    }
    return undefined;
};

exports.getCapacitor = getCapacitor;
