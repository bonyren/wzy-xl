/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { r as registerInstance, h, d as Host } from './index-4DxY6_gG.js';

const segmentContentCss = ":host{scroll-snap-align:center;scroll-snap-stop:always;-ms-flex-negative:0;flex-shrink:0;width:100%;min-height:1px;overflow-y:scroll;scrollbar-width:none;-ms-overflow-style:none;}:host::-webkit-scrollbar{display:none}";

const SegmentContent = class {
    constructor(hostRef) {
        registerInstance(this, hostRef);
    }
    render() {
        return (h(Host, { key: 'db6876f2aee7afa1ea8bc147337670faa68fae1c' }, h("slot", { key: 'bc05714a973a5655668679033f5809a1da6db8cc' })));
    }
};
SegmentContent.style = segmentContentCss;

export { SegmentContent as ion_segment_content };
