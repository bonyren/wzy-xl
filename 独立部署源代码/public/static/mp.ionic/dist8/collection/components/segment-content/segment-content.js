/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
export class SegmentContent {
    render() {
        return (h(Host, { key: 'db6876f2aee7afa1ea8bc147337670faa68fae1c' }, h("slot", { key: 'bc05714a973a5655668679033f5809a1da6db8cc' })));
    }
    static get is() { return "ion-segment-content"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "$": ["segment-content.scss"]
        };
    }
    static get styleUrls() {
        return {
            "$": ["segment-content.css"]
        };
    }
}
