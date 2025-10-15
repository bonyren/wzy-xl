/*!
 * (C) Ionic http://ionicframework.com - MIT License
 */
import { Host, h } from "@stencil/core";
import { getIonMode } from "../../global/ionic-global";
export class Row {
    render() {
        return (h(Host, { key: '65592a79621bd8f75f9566db3e8c05a4b8fc6048', class: getIonMode(this) }, h("slot", { key: '56f09784db7a0299c9ce76dfcede185b295251ff' })));
    }
    static get is() { return "ion-row"; }
    static get encapsulation() { return "shadow"; }
    static get originalStyleUrls() {
        return {
            "$": ["row.scss"]
        };
    }
    static get styleUrls() {
        return {
            "$": ["row.css"]
        };
    }
}
