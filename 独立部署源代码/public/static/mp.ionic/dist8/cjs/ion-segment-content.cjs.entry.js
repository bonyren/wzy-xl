/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
'use strict';

var index = require('./index-DNh170BW.js');

const segmentContentCss = ":host{scroll-snap-align:center;scroll-snap-stop:always;-ms-flex-negative:0;flex-shrink:0;width:100%;min-height:1px;overflow-y:scroll;scrollbar-width:none;-ms-overflow-style:none;}:host::-webkit-scrollbar{display:none}";

const SegmentContent = class {
    constructor(hostRef) {
        index.registerInstance(this, hostRef);
    }
    render() {
        return (index.h(index.Host, { key: 'db6876f2aee7afa1ea8bc147337670faa68fae1c' }, index.h("slot", { key: 'bc05714a973a5655668679033f5809a1da6db8cc' })));
    }
};
SegmentContent.style = segmentContentCss;

exports.ion_segment_content = SegmentContent;
