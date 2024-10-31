
/*!
 * Masonry PACKAGED v4.0.0
 * Cascading grid layout library
 * http://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

!function(t,e){"use strict";"function"==typeof define&&define.amd?define("jquery-bridget/jquery-bridget",["jquery"],function(i){e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("jquery")):t.jQueryBridget=e(t,t.jQuery)}(window,function(t,e){"use strict";function i(i,r,a){function h(t,e,n){var o,r="$()."+i+'("'+e+'")';return t.each(function(t,h){var u=a.data(h,i);if(!u)return void s(i+" not initialized. Cannot call methods, i.e. "+r);var d=u[e];if(!d||"_"==e.charAt(0))return void s(r+" is not a valid method");var c=d.apply(u,n);o=void 0===o?c:o}),void 0!==o?o:t}function u(t,e){t.each(function(t,n){var o=a.data(n,i);o?(o.option(e),o._init()):(o=new r(n,e),a.data(n,i,o))})}a=a||e||t.jQuery,a&&(r.prototype.option||(r.prototype.option=function(t){a.isPlainObject(t)&&(this.options=a.extend(!0,this.options,t))}),a.fn[i]=function(t){if("string"==typeof t){var e=o.call(arguments,1);return h(this,t,e)}return u(this,t),this},n(a))}function n(t){!t||t&&t.bridget||(t.bridget=i)}var o=Array.prototype.slice,r=t.console,s="undefined"==typeof r?function(){}:function(t){r.error(t)};return n(e||t.jQuery),i}),function(t,e){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",e):"object"==typeof module&&module.exports?module.exports=e():t.EvEmitter=e()}(this,function(){function t(){}var e=t.prototype;return e.on=function(t,e){if(t&&e){var i=this._events=this._events||{},n=i[t]=i[t]||[];return-1==n.indexOf(e)&&n.push(e),this}},e.once=function(t,e){if(t&&e){this.on(t,e);var i=this._onceEvents=this._onceEvents||{},n=i[t]=i[t]||[];return n[e]=!0,this}},e.off=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=i.indexOf(e);return-1!=n&&i.splice(n,1),this}},e.emitEvent=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var n=0,o=i[n];e=e||[];for(var r=this._onceEvents&&this._onceEvents[t];o;){var s=r&&r[o];s&&(this.off(t,o),delete r[o]),o.apply(this,e),n+=s?0:1,o=i[n]}return this}},t}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("get-size/get-size",[],function(){return e()}):"object"==typeof module&&module.exports?module.exports=e():t.getSize=e()}(window,function(){"use strict";function t(t){var e=parseFloat(t),i=-1==t.indexOf("%")&&!isNaN(e);return i&&e}function e(){}function i(){for(var t={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},e=0;u>e;e++){var i=h[e];t[i]=0}return t}function n(t){var e=getComputedStyle(t);return e||a("Style returned "+e+". Are you running this code in a hidden iframe on Firefox? See http://bit.ly/getsizebug1"),e}function o(){if(!d){d=!0;var e=document.createElement("div");e.style.width="200px",e.style.padding="1px 2px 3px 4px",e.style.borderStyle="solid",e.style.borderWidth="1px 2px 3px 4px",e.style.boxSizing="border-box";var i=document.body||document.documentElement;i.appendChild(e);var o=n(e);r.isBoxSizeOuter=s=200==t(o.width),i.removeChild(e)}}function r(e){if(o(),"string"==typeof e&&(e=document.querySelector(e)),e&&"object"==typeof e&&e.nodeType){var r=n(e);if("none"==r.display)return i();var a={};a.width=e.offsetWidth,a.height=e.offsetHeight;for(var d=a.isBorderBox="border-box"==r.boxSizing,c=0;u>c;c++){var l=h[c],f=r[l],m=parseFloat(f);a[l]=isNaN(m)?0:m}var p=a.paddingLeft+a.paddingRight,g=a.paddingTop+a.paddingBottom,y=a.marginLeft+a.marginRight,v=a.marginTop+a.marginBottom,_=a.borderLeftWidth+a.borderRightWidth,E=a.borderTopWidth+a.borderBottomWidth,z=d&&s,b=t(r.width);b!==!1&&(a.width=b+(z?0:p+_));var x=t(r.height);return x!==!1&&(a.height=x+(z?0:g+E)),a.innerWidth=a.width-(p+_),a.innerHeight=a.height-(g+E),a.outerWidth=a.width+y,a.outerHeight=a.height+v,a}}var s,a="undefined"==typeof console?e:function(t){console.error(t)},h=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"],u=h.length,d=!1;return r}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("matches-selector/matches-selector",e):"object"==typeof module&&module.exports?module.exports=e():t.matchesSelector=e()}(window,function(){"use strict";var t=function(){var t=Element.prototype;if(t.matches)return"matches";if(t.matchesSelector)return"matchesSelector";for(var e=["webkit","moz","ms","o"],i=0;i<e.length;i++){var n=e[i],o=n+"MatchesSelector";if(t[o])return o}}();return function(e,i){return e[t](i)}}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("fizzy-ui-utils/utils",["matches-selector/matches-selector"],function(i){return e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("desandro-matches-selector")):t.fizzyUIUtils=e(t,t.matchesSelector)}(window,function(t,e){var i={};i.extend=function(t,e){for(var i in e)t[i]=e[i];return t},i.modulo=function(t,e){return(t%e+e)%e},i.makeArray=function(t){var e=[];if(Array.isArray(t))e=t;else if(t&&"number"==typeof t.length)for(var i=0;i<t.length;i++)e.push(t[i]);else e.push(t);return e},i.removeFrom=function(t,e){var i=t.indexOf(e);-1!=i&&t.splice(i,1)},i.getParent=function(t,i){for(;t!=document.body;)if(t=t.parentNode,e(t,i))return t},i.getQueryElement=function(t){return"string"==typeof t?document.querySelector(t):t},i.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},i.filterFindElements=function(t,n){t=i.makeArray(t);var o=[];return t.forEach(function(t){if(t instanceof HTMLElement){if(!n)return void o.push(t);e(t,n)&&o.push(t);for(var i=t.querySelectorAll(n),r=0;r<i.length;r++)o.push(i[r])}}),o},i.debounceMethod=function(t,e,i){var n=t.prototype[e],o=e+"Timeout";t.prototype[e]=function(){var t=this[o];t&&clearTimeout(t);var e=arguments,r=this;this[o]=setTimeout(function(){n.apply(r,e),delete r[o]},i||100)}},i.docReady=function(t){"complete"==document.readyState?t():document.addEventListener("DOMContentLoaded",t)},i.toDashed=function(t){return t.replace(/(.)([A-Z])/g,function(t,e,i){return e+"-"+i}).toLowerCase()};var n=t.console;return i.htmlInit=function(e,o){i.docReady(function(){var r=i.toDashed(o),s="data-"+r,a=document.querySelectorAll("["+s+"]"),h=document.querySelectorAll(".js-"+r),u=i.makeArray(a).concat(i.makeArray(h)),d=s+"-options",c=t.jQuery;u.forEach(function(t){var i,r=t.getAttribute(s)||t.getAttribute(d);try{i=r&&JSON.parse(r)}catch(a){return void(n&&n.error("Error parsing "+s+" on "+t.className+": "+a))}var h=new e(t,i);c&&c.data(t,o,h)})})},i}),function(t,e){"function"==typeof define&&define.amd?define("outlayer/item",["ev-emitter/ev-emitter","get-size/get-size"],function(i,n){return e(t,i,n)}):"object"==typeof module&&module.exports?module.exports=e(t,require("ev-emitter"),require("get-size")):(t.Outlayer={},t.Outlayer.Item=e(t,t.EvEmitter,t.getSize))}(window,function(t,e,i){"use strict";function n(t){for(var e in t)return!1;return e=null,!0}function o(t,e){t&&(this.element=t,this.layout=e,this.position={x:0,y:0},this._create())}function r(t){return t.replace(/([A-Z])/g,function(t){return"-"+t.toLowerCase()})}var s=document.documentElement.style,a="string"==typeof s.transition?"transition":"WebkitTransition",h="string"==typeof s.transform?"transform":"WebkitTransform",u={WebkitTransition:"webkitTransitionEnd",transition:"transitionend"}[a],d=[h,a,a+"Duration",a+"Property"],c=o.prototype=Object.create(e.prototype);c.constructor=o,c._create=function(){this._transn={ingProperties:{},clean:{},onEnd:{}},this.css({position:"absolute"})},c.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},c.getSize=function(){this.size=i(this.element)},c.css=function(t){var e=this.element.style;for(var i in t){var n=d[i]||i;e[n]=t[i]}},c.getPosition=function(){var t=getComputedStyle(this.element),e=this.layout._getOption("originLeft"),i=this.layout._getOption("originTop"),n=t[e?"left":"right"],o=t[i?"top":"bottom"],r=this.layout.size,s=-1!=n.indexOf("%")?parseFloat(n)/100*r.width:parseInt(n,10),a=-1!=o.indexOf("%")?parseFloat(o)/100*r.height:parseInt(o,10);s=isNaN(s)?0:s,a=isNaN(a)?0:a,s-=e?r.paddingLeft:r.paddingRight,a-=i?r.paddingTop:r.paddingBottom,this.position.x=s,this.position.y=a},c.layoutPosition=function(){var t=this.layout.size,e={},i=this.layout._getOption("originLeft"),n=this.layout._getOption("originTop"),o=i?"paddingLeft":"paddingRight",r=i?"left":"right",s=i?"right":"left",a=this.position.x+t[o];e[r]=this.getXValue(a),e[s]="";var h=n?"paddingTop":"paddingBottom",u=n?"top":"bottom",d=n?"bottom":"top",c=this.position.y+t[h];e[u]=this.getYValue(c),e[d]="",this.css(e),this.emitEvent("layout",[this])},c.getXValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&!e?t/this.layout.size.width*100+"%":t+"px"},c.getYValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&e?t/this.layout.size.height*100+"%":t+"px"},c._transitionTo=function(t,e){this.getPosition();var i=this.position.x,n=this.position.y,o=parseInt(t,10),r=parseInt(e,10),s=o===this.position.x&&r===this.position.y;if(this.setPosition(t,e),s&&!this.isTransitioning)return void this.layoutPosition();var a=t-i,h=e-n,u={};u.transform=this.getTranslate(a,h),this.transition({to:u,onTransitionEnd:{transform:this.layoutPosition},isCleaning:!0})},c.getTranslate=function(t,e){var i=this.layout._getOption("originLeft"),n=this.layout._getOption("originTop");return t=i?t:-t,e=n?e:-e,"translate3d("+t+"px, "+e+"px, 0)"},c.goTo=function(t,e){this.setPosition(t,e),this.layoutPosition()},c.moveTo=c._transitionTo,c.setPosition=function(t,e){this.position.x=parseInt(t,10),this.position.y=parseInt(e,10)},c._nonTransition=function(t){this.css(t.to),t.isCleaning&&this._removeStyles(t.to);for(var e in t.onTransitionEnd)t.onTransitionEnd[e].call(this)},c._transition=function(t){if(!parseFloat(this.layout.options.transitionDuration))return void this._nonTransition(t);var e=this._transn;for(var i in t.onTransitionEnd)e.onEnd[i]=t.onTransitionEnd[i];for(i in t.to)e.ingProperties[i]=!0,t.isCleaning&&(e.clean[i]=!0);if(t.from){this.css(t.from);var n=this.element.offsetHeight;n=null}this.enableTransition(t.to),this.css(t.to),this.isTransitioning=!0};var l="opacity,"+r(d.transform||"transform");c.enableTransition=function(){this.isTransitioning||(this.css({transitionProperty:l,transitionDuration:this.layout.options.transitionDuration}),this.element.addEventListener(u,this,!1))},c.transition=o.prototype[a?"_transition":"_nonTransition"],c.onwebkitTransitionEnd=function(t){this.ontransitionend(t)},c.onotransitionend=function(t){this.ontransitionend(t)};var f={"-webkit-transform":"transform"};c.ontransitionend=function(t){if(t.target===this.element){var e=this._transn,i=f[t.propertyName]||t.propertyName;if(delete e.ingProperties[i],n(e.ingProperties)&&this.disableTransition(),i in e.clean&&(this.element.style[t.propertyName]="",delete e.clean[i]),i in e.onEnd){var o=e.onEnd[i];o.call(this),delete e.onEnd[i]}this.emitEvent("transitionEnd",[this])}},c.disableTransition=function(){this.removeTransitionStyles(),this.element.removeEventListener(u,this,!1),this.isTransitioning=!1},c._removeStyles=function(t){var e={};for(var i in t)e[i]="";this.css(e)};var m={transitionProperty:"",transitionDuration:""};return c.removeTransitionStyles=function(){this.css(m)},c.removeElem=function(){this.element.parentNode.removeChild(this.element),this.css({display:""}),this.emitEvent("remove",[this])},c.remove=function(){return a&&parseFloat(this.layout.options.transitionDuration)?(this.once("transitionEnd",function(){this.removeElem()}),void this.hide()):void this.removeElem()},c.reveal=function(){delete this.isHidden,this.css({display:""});var t=this.layout.options,e={},i=this.getHideRevealTransitionEndProperty("visibleStyle");e[i]=this.onRevealTransitionEnd,this.transition({from:t.hiddenStyle,to:t.visibleStyle,isCleaning:!0,onTransitionEnd:e})},c.onRevealTransitionEnd=function(){this.isHidden||this.emitEvent("reveal")},c.getHideRevealTransitionEndProperty=function(t){var e=this.layout.options[t];if(e.opacity)return"opacity";for(var i in e)return i},c.hide=function(){this.isHidden=!0,this.css({display:""});var t=this.layout.options,e={},i=this.getHideRevealTransitionEndProperty("hiddenStyle");e[i]=this.onHideTransitionEnd,this.transition({from:t.visibleStyle,to:t.hiddenStyle,isCleaning:!0,onTransitionEnd:e})},c.onHideTransitionEnd=function(){this.isHidden&&(this.css({display:"none"}),this.emitEvent("hide"))},c.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},o}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("outlayer/outlayer",["ev-emitter/ev-emitter","get-size/get-size","fizzy-ui-utils/utils","./item"],function(i,n,o,r){return e(t,i,n,o,r)}):"object"==typeof module&&module.exports?module.exports=e(t,require("ev-emitter"),require("get-size"),require("fizzy-ui-utils"),require("./item")):t.Outlayer=e(t,t.EvEmitter,t.getSize,t.fizzyUIUtils,t.Outlayer.Item)}(window,function(t,e,i,n,o){"use strict";function r(t,e){var i=n.getQueryElement(t);if(!i)return void(a&&a.error("Bad element for "+this.constructor.namespace+": "+(i||t)));this.element=i,h&&(this.$element=h(this.element)),this.options=n.extend({},this.constructor.defaults),this.option(e);var o=++d;this.element.outlayerGUID=o,c[o]=this,this._create();var r=this._getOption("initLayout");r&&this.layout()}function s(t){function e(){t.apply(this,arguments)}return e.prototype=Object.create(t.prototype),e.prototype.constructor=e,e}var a=t.console,h=t.jQuery,u=function(){},d=0,c={};r.namespace="outlayer",r.Item=o,r.defaults={containerStyle:{position:"relative"},initLayout:!0,originLeft:!0,originTop:!0,resize:!0,resizeContainer:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}};var l=r.prototype;return n.extend(l,e.prototype),l.option=function(t){n.extend(this.options,t)},l._getOption=function(t){var e=this.constructor.compatOptions[t];return e&&void 0!==this.options[e]?this.options[e]:this.options[t]},r.compatOptions={initLayout:"isInitLayout",horizontal:"isHorizontal",layoutInstant:"isLayoutInstant",originLeft:"isOriginLeft",originTop:"isOriginTop",resize:"isResizeBound",resizeContainer:"isResizingContainer"},l._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),n.extend(this.element.style,this.options.containerStyle);var t=this._getOption("resize");t&&this.bindResize()},l.reloadItems=function(){this.items=this._itemize(this.element.children)},l._itemize=function(t){for(var e=this._filterFindItemElements(t),i=this.constructor.Item,n=[],o=0;o<e.length;o++){var r=e[o],s=new i(r,this);n.push(s)}return n},l._filterFindItemElements=function(t){return n.filterFindElements(t,this.options.itemSelector)},l.getItemElements=function(){return this.items.map(function(t){return t.element})},l.layout=function(){this._resetLayout(),this._manageStamps();var t=this._getOption("layoutInstant"),e=void 0!==t?t:!this._isLayoutInited;this.layoutItems(this.items,e),this._isLayoutInited=!0},l._init=l.layout,l._resetLayout=function(){this.getSize()},l.getSize=function(){this.size=i(this.element)},l._getMeasurement=function(t,e){var n,o=this.options[t];o?("string"==typeof o?n=this.element.querySelector(o):o instanceof HTMLElement&&(n=o),this[t]=n?i(n)[e]:o):this[t]=0},l.layoutItems=function(t,e){t=this._getItemsForLayout(t),this._layoutItems(t,e),this._postLayout()},l._getItemsForLayout=function(t){return t.filter(function(t){return!t.isIgnored})},l._layoutItems=function(t,e){if(this._emitCompleteOnItems("layout",t),t&&t.length){var i=[];t.forEach(function(t){var n=this._getItemLayoutPosition(t);n.item=t,n.isInstant=e||t.isLayoutInstant,i.push(n)},this),this._processLayoutQueue(i)}},l._getItemLayoutPosition=function(){return{x:0,y:0}},l._processLayoutQueue=function(t){t.forEach(function(t){this._positionItem(t.item,t.x,t.y,t.isInstant)},this)},l._positionItem=function(t,e,i,n){n?t.goTo(e,i):t.moveTo(e,i)},l._postLayout=function(){this.resizeContainer()},l.resizeContainer=function(){var t=this._getOption("resizeContainer");if(t){var e=this._getContainerSize();e&&(this._setContainerMeasure(e.width,!0),this._setContainerMeasure(e.height,!1))}},l._getContainerSize=u,l._setContainerMeasure=function(t,e){if(void 0!==t){var i=this.size;i.isBorderBox&&(t+=e?i.paddingLeft+i.paddingRight+i.borderLeftWidth+i.borderRightWidth:i.paddingBottom+i.paddingTop+i.borderTopWidth+i.borderBottomWidth),t=Math.max(t,0),this.element.style[e?"width":"height"]=t+"px"}},l._emitCompleteOnItems=function(t,e){function i(){o.dispatchEvent(t+"Complete",null,[e])}function n(){s++,s==r&&i()}var o=this,r=e.length;if(!e||!r)return void i();var s=0;e.forEach(function(e){e.once(t,n)})},l.dispatchEvent=function(t,e,i){var n=e?[e].concat(i):i;if(this.emitEvent(t,n),h)if(this.$element=this.$element||h(this.element),e){var o=h.Event(e);o.type=t,this.$element.trigger(o,i)}else this.$element.trigger(t,i)},l.ignore=function(t){var e=this.getItem(t);e&&(e.isIgnored=!0)},l.unignore=function(t){var e=this.getItem(t);e&&delete e.isIgnored},l.stamp=function(t){t=this._find(t),t&&(this.stamps=this.stamps.concat(t),t.forEach(this.ignore,this))},l.unstamp=function(t){t=this._find(t),t&&t.forEach(function(t){n.removeFrom(this.stamps,t),this.unignore(t)},this)},l._find=function(t){return t?("string"==typeof t&&(t=this.element.querySelectorAll(t)),t=n.makeArray(t)):void 0},l._manageStamps=function(){this.stamps&&this.stamps.length&&(this._getBoundingRect(),this.stamps.forEach(this._manageStamp,this))},l._getBoundingRect=function(){var t=this.element.getBoundingClientRect(),e=this.size;this._boundingRect={left:t.left+e.paddingLeft+e.borderLeftWidth,top:t.top+e.paddingTop+e.borderTopWidth,right:t.right-(e.paddingRight+e.borderRightWidth),bottom:t.bottom-(e.paddingBottom+e.borderBottomWidth)}},l._manageStamp=u,l._getElementOffset=function(t){var e=t.getBoundingClientRect(),n=this._boundingRect,o=i(t),r={left:e.left-n.left-o.marginLeft,top:e.top-n.top-o.marginTop,right:n.right-e.right-o.marginRight,bottom:n.bottom-e.bottom-o.marginBottom};return r},l.handleEvent=n.handleEvent,l.bindResize=function(){t.addEventListener("resize",this),this.isResizeBound=!0},l.unbindResize=function(){t.removeEventListener("resize",this),this.isResizeBound=!1},l.onresize=function(){this.resize()},n.debounceMethod(r,"onresize",100),l.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&this.layout()},l.needsResizeLayout=function(){var t=i(this.element),e=this.size&&t;return e&&t.innerWidth!==this.size.innerWidth},l.addItems=function(t){var e=this._itemize(t);return e.length&&(this.items=this.items.concat(e)),e},l.appended=function(t){var e=this.addItems(t);e.length&&(this.layoutItems(e,!0),this.reveal(e))},l.prepended=function(t){var e=this._itemize(t);if(e.length){var i=this.items.slice(0);this.items=e.concat(i),this._resetLayout(),this._manageStamps(),this.layoutItems(e,!0),this.reveal(e),this.layoutItems(i)}},l.reveal=function(t){this._emitCompleteOnItems("reveal",t),t&&t.length&&t.forEach(function(t){t.reveal()})},l.hide=function(t){this._emitCompleteOnItems("hide",t),t&&t.length&&t.forEach(function(t){t.hide()})},l.revealItemElements=function(t){var e=this.getItems(t);this.reveal(e)},l.hideItemElements=function(t){var e=this.getItems(t);this.hide(e)},l.getItem=function(t){for(var e=0;e<this.items.length;e++){var i=this.items[e];if(i.element==t)return i}},l.getItems=function(t){t=n.makeArray(t);var e=[];return t.forEach(function(t){var i=this.getItem(t);i&&e.push(i)},this),e},l.remove=function(t){var e=this.getItems(t);this._emitCompleteOnItems("remove",e),e&&e.length&&e.forEach(function(t){t.remove(),n.removeFrom(this.items,t)},this)},l.destroy=function(){var t=this.element.style;t.height="",t.position="",t.width="",this.items.forEach(function(t){t.destroy()}),this.unbindResize();var e=this.element.outlayerGUID;delete c[e],delete this.element.outlayerGUID,h&&h.removeData(this.element,this.constructor.namespace)},r.data=function(t){t=n.getQueryElement(t);var e=t&&t.outlayerGUID;return e&&c[e]},r.create=function(t,e){var i=s(r);return i.defaults=n.extend({},r.defaults),n.extend(i.defaults,e),i.compatOptions=n.extend({},r.compatOptions),i.namespace=t,i.data=r.data,i.Item=s(o),n.htmlInit(i,t),h&&h.bridget&&h.bridget(t,i),i},r.Item=o,r}),function(t,e){"function"==typeof define&&define.amd?define(["outlayer/outlayer","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer"),require("get-size")):t.Masonry=e(t.Outlayer,t.getSize)}(window,function(t,e){var i=t.create("masonry");return i.compatOptions.fitWidth="isFitWidth",i.prototype._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns(),this.colYs=[];for(var t=0;t<this.cols;t++)this.colYs.push(0);this.maxY=0},i.prototype.measureColumns=function(){if(this.getContainerWidth(),!this.columnWidth){var t=this.items[0],i=t&&t.element;this.columnWidth=i&&e(i).outerWidth||this.containerWidth}var n=this.columnWidth+=this.gutter,o=this.containerWidth+this.gutter,r=o/n,s=n-o%n,a=s&&1>s?"round":"floor";r=Math[a](r),this.cols=Math.max(r,1)},i.prototype.getContainerWidth=function(){var t=this._getOption("fitWidth"),i=t?this.element.parentNode:this.element,n=e(i);this.containerWidth=n&&n.innerWidth},i.prototype._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth%this.columnWidth,i=e&&1>e?"round":"ceil",n=Math[i](t.size.outerWidth/this.columnWidth);n=Math.min(n,this.cols);for(var o=this._getColGroup(n),r=Math.min.apply(Math,o),s=o.indexOf(r),a={x:this.columnWidth*s,y:r},h=r+t.size.outerHeight,u=this.cols+1-o.length,d=0;u>d;d++)this.colYs[s+d]=h;return a},i.prototype._getColGroup=function(t){if(2>t)return this.colYs;for(var e=[],i=this.cols+1-t,n=0;i>n;n++){var o=this.colYs.slice(n,n+t);e[n]=Math.max.apply(Math,o)}return e},i.prototype._manageStamp=function(t){var i=e(t),n=this._getElementOffset(t),o=this._getOption("originLeft"),r=o?n.left:n.right,s=r+i.outerWidth,a=Math.floor(r/this.columnWidth);a=Math.max(0,a);var h=Math.floor(s/this.columnWidth);h-=s%this.columnWidth?0:1,h=Math.min(this.cols-1,h);for(var u=this._getOption("originTop"),d=(u?n.top:n.bottom)+i.outerHeight,c=a;h>=c;c++)this.colYs[c]=Math.max(d,this.colYs[c])},i.prototype._getContainerSize=function(){this.maxY=Math.max.apply(Math,this.colYs);var t={height:this.maxY};return this._getOption("fitWidth")&&(t.width=this._getContainerFitWidth()),t},i.prototype._getContainerFitWidth=function(){for(var t=0,e=this.cols;--e&&0===this.colYs[e];)t++;return(this.cols-t)*this.columnWidth-this.gutter},i.prototype.needsResizeLayout=function(){var t=this.containerWidth;return this.getContainerWidth(),t!=this.containerWidth},i});
 
 /*!
 * imagesLoaded PACKAGED v4.1.3
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

!function(e,t){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",t):"object"==typeof module&&module.exports?module.exports=t():e.EvEmitter=t()}("undefined"!=typeof window?window:this,function(){function e(){}var t=e.prototype;return t.on=function(e,t){if(e&&t){var i=this._events=this._events||{},n=i[e]=i[e]||[];return-1==n.indexOf(t)&&n.push(t),this}},t.once=function(e,t){if(e&&t){this.on(e,t);var i=this._onceEvents=this._onceEvents||{},n=i[e]=i[e]||{};return n[t]=!0,this}},t.off=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=i.indexOf(t);return-1!=n&&i.splice(n,1),this}},t.emitEvent=function(e,t){var i=this._events&&this._events[e];if(i&&i.length){var n=0,o=i[n];t=t||[];for(var r=this._onceEvents&&this._onceEvents[e];o;){var s=r&&r[o];s&&(this.off(e,o),delete r[o]),o.apply(this,t),n+=s?0:1,o=i[n]}return this}},t.allOff=t.removeAllListeners=function(){delete this._events,delete this._onceEvents},e}),function(e,t){"use strict";"function"==typeof define&&define.amd?define(["ev-emitter/ev-emitter"],function(i){return t(e,i)}):"object"==typeof module&&module.exports?module.exports=t(e,require("ev-emitter")):e.imagesLoaded=t(e,e.EvEmitter)}("undefined"!=typeof window?window:this,function(e,t){function i(e,t){for(var i in t)e[i]=t[i];return e}function n(e){var t=[];if(Array.isArray(e))t=e;else if("number"==typeof e.length)for(var i=0;i<e.length;i++)t.push(e[i]);else t.push(e);return t}function o(e,t,r){return this instanceof o?("string"==typeof e&&(e=document.querySelectorAll(e)),this.elements=n(e),this.options=i({},this.options),"function"==typeof t?r=t:i(this.options,t),r&&this.on("always",r),this.getImages(),h&&(this.jqDeferred=new h.Deferred),void setTimeout(function(){this.check()}.bind(this))):new o(e,t,r)}function r(e){this.img=e}function s(e,t){this.url=e,this.element=t,this.img=new Image}var h=e.jQuery,a=e.console;o.prototype=Object.create(t.prototype),o.prototype.options={},o.prototype.getImages=function(){this.images=[],this.elements.forEach(this.addElementImages,this)},o.prototype.addElementImages=function(e){"IMG"==e.nodeName&&this.addImage(e),this.options.background===!0&&this.addElementBackgroundImages(e);var t=e.nodeType;if(t&&d[t]){for(var i=e.querySelectorAll("img"),n=0;n<i.length;n++){var o=i[n];this.addImage(o)}if("string"==typeof this.options.background){var r=e.querySelectorAll(this.options.background);for(n=0;n<r.length;n++){var s=r[n];this.addElementBackgroundImages(s)}}}};var d={1:!0,9:!0,11:!0};return o.prototype.addElementBackgroundImages=function(e){var t=getComputedStyle(e);if(t)for(var i=/url\((['"])?(.*?)\1\)/gi,n=i.exec(t.backgroundImage);null!==n;){var o=n&&n[2];o&&this.addBackground(o,e),n=i.exec(t.backgroundImage)}},o.prototype.addImage=function(e){var t=new r(e);this.images.push(t)},o.prototype.addBackground=function(e,t){var i=new s(e,t);this.images.push(i)},o.prototype.check=function(){function e(e,i,n){setTimeout(function(){t.progress(e,i,n)})}var t=this;return this.progressedCount=0,this.hasAnyBroken=!1,this.images.length?void this.images.forEach(function(t){t.once("progress",e),t.check()}):void this.complete()},o.prototype.progress=function(e,t,i){this.progressedCount++,this.hasAnyBroken=this.hasAnyBroken||!e.isLoaded,this.emitEvent("progress",[this,e,t]),this.jqDeferred&&this.jqDeferred.notify&&this.jqDeferred.notify(this,e),this.progressedCount==this.images.length&&this.complete(),this.options.debug&&a&&a.log("progress: "+i,e,t)},o.prototype.complete=function(){var e=this.hasAnyBroken?"fail":"done";if(this.isComplete=!0,this.emitEvent(e,[this]),this.emitEvent("always",[this]),this.jqDeferred){var t=this.hasAnyBroken?"reject":"resolve";this.jqDeferred[t](this)}},r.prototype=Object.create(t.prototype),r.prototype.check=function(){var e=this.getIsImageComplete();return e?void this.confirm(0!==this.img.naturalWidth,"naturalWidth"):(this.proxyImage=new Image,this.proxyImage.addEventListener("load",this),this.proxyImage.addEventListener("error",this),this.img.addEventListener("load",this),this.img.addEventListener("error",this),void(this.proxyImage.src=this.img.src))},r.prototype.getIsImageComplete=function(){return this.img.complete&&void 0!==this.img.naturalWidth},r.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.img,t])},r.prototype.handleEvent=function(e){var t="on"+e.type;this[t]&&this[t](e)},r.prototype.onload=function(){this.confirm(!0,"onload"),this.unbindEvents()},r.prototype.onerror=function(){this.confirm(!1,"onerror"),this.unbindEvents()},r.prototype.unbindEvents=function(){this.proxyImage.removeEventListener("load",this),this.proxyImage.removeEventListener("error",this),this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype=Object.create(r.prototype),s.prototype.check=function(){this.img.addEventListener("load",this),this.img.addEventListener("error",this),this.img.src=this.url;var e=this.getIsImageComplete();e&&(this.confirm(0!==this.img.naturalWidth,"naturalWidth"),this.unbindEvents())},s.prototype.unbindEvents=function(){this.img.removeEventListener("load",this),this.img.removeEventListener("error",this)},s.prototype.confirm=function(e,t){this.isLoaded=e,this.emitEvent("progress",[this,this.element,t])},o.makeJQueryPlugin=function(t){t=t||e.jQuery,t&&(h=t,h.fn.imagesLoaded=function(e,t){var i=new o(this,e,t);return i.jqDeferred.promise(h(this))})},o.makeJQueryPlugin(),o});
/**
 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
 *
 * jQuery.browser.mobile will be true if the browser is a mobile device
 *
 **/
;(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);

;(function ($) {
	$(document).ready(function () {
		"use strict";
		var RequestState1 = true;
		var RequestState2 = true;
		var RequestState3 = true;
		var lastWinTop = 0, winNo = [], $grid, newMsgsNo = {}, last_attachment_id = '', last_slider_id = '', last_album_attach_id = '', last_album_id ='';
		
		Array.prototype.remove = function(value) {
			if (this.indexOf(value)!== -1) {
				this.splice(this.indexOf(value), 1);
				return true;
			} else {
				return false;
			};
		}
		
		var MyGalleryAjax = {
			
			mygalleryInit: function () {
				var self = $(this);
				this.eventHandler();
				this.resizewindow();
				this.ScrollEvent();	
				this.HoverContent();
				this.AlbumHoverEffect();
				this.masonry();
			},
						
			LoadGallery: function (attachment_id, columns, style_class, style_css, show_title, show_desc, show_social){
				if(RequestState1 == true) {
					$('div.mygalleryLoading').prepend('<span class="myg_spinnerx16"></span>'); 
					RequestState1 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,						
						type: "POST",
						dataType: "JSON",
						data: { 
							last_attachment_id : attachment_id,
							image_size : columns,
							style_class : style_class,
							style_css : style_css,
							show_title : show_title,
							show_desc : show_desc,
							show_social : show_social,
							action : mygallery_conf.ajaxActions.myg_infinite_gallery.action,
							nonce : mygallery_conf.ajaxNonce
						 },
						success: function (data){
							var imgdata = data.infinite_gallery;
							$('.myg_infinite_gallery').append(imgdata)
							
						},
						complete: function() {
							RequestState1 = true;
							$('div.mygalleryLoading').html('');
						}
					});
				}
			},
			LoadPortfolio: function (attachment_id, columns, style_class, style_css, show_title, show_desc, show_social){
				if(RequestState1 == true) {
					$('div.mygalleryLoading').prepend('<span class="myg_spinnerx16"></span>'); 
					RequestState1 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,						
						type: "POST",
						dataType: "JSON",
						data: { 
							last_attachment_id : attachment_id,
							image_size : columns,
							style_class : style_class,
							style_css : style_css,
							show_title : show_title,
							show_desc : show_desc,
							show_social : show_social,
							action : mygallery_conf.ajaxActions.myg_infinite_portfolio.action,
							nonce : mygallery_conf.ajaxNonce
						 },
						success: function (data){
							var imgdata = data.infinite_gallery;
							$('.myg_infinite_portfolio').append(imgdata)
							
						},
						complete: function() {
							RequestState1 = true;
							$('div.mygalleryLoading').html('');
						}
					});
				}
			},
			LoadInfinityMasonry: function (attachment_id, columns, style_class, show_title, show_desc, show_social){
				if(RequestState1 == true) {
					$('div.mygalleryLoading').prepend('<span class="myg_spinnerx16"></span>'); 
					RequestState1 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,						
						type: "POST",
						dataType: "JSON",
						data: { 
							last_attachment_id : attachment_id,
							image_size : columns,
							style_class : style_class,
							show_title : show_title,
							show_desc : show_desc,
							show_social : show_social,
							action : mygallery_conf.ajaxActions.myg_infinite_masonry.action,
							nonce : mygallery_conf.ajaxNonce
						 },
						success: function (data){
							var imgdata = data.infinite_gallery;
							$('.myg_infinite_masonry').append(imgdata);
							MyGalleryAjax.masonry();
							$('.myg-masonry').masonry( 'reloadItems' );
							$('.myg-masonry').masonry( 'layout' );	
							
						},
						complete: function() {
							RequestState1 = true;
							$('div.mygalleryLoading').html('');
						}
					});
				}
			},
			LoadInfiniteRightContent: function (attachment_id, columns, style_class, style_css, show_title, show_desc, show_social){
				if(RequestState1 == true) {
					$('div.mygalleryLoading').prepend('<span class="myg_spinnerx16"></span>'); 
					RequestState1 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,						
						type: "POST",
						dataType: "JSON",
						data: { 
							last_attachment_id : attachment_id,
							image_size : columns,
							style_class : style_class,
							style_css : style_css,
							show_title : show_title,
							show_desc : show_desc,
							show_social : show_social,
							action : mygallery_conf.ajaxActions.myg_infinite_right_content.action,
							nonce : mygallery_conf.ajaxNonce
						 },
						success: function (data){
							var imgdata = data.infinite_gallery;
							$('.myg_infinite_right_content').append(imgdata)
							
						},
						complete: function() {
							RequestState1 = true;
							$('div.mygalleryLoading').html('');
						}
					});
				}
			},
			LoadInfiniteHoverGallery: function (attachment_id, columns, style_class, style_css, show_title, show_desc, show_social){
				if(RequestState1 == true) {
					$('div.mygalleryLoading').prepend('<span class="myg_spinnerx16"></span>'); 
					RequestState1 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,						
						type: "POST",
						dataType: "JSON",
						data: { 
							last_attachment_id : attachment_id,
							image_size : columns,
							style_class : style_class,
							style_css : style_css,
							show_title : show_title,
							show_desc : show_desc,
							show_social : show_social,
							action : mygallery_conf.ajaxActions.myg_infinite_hover_gallery.action,
							nonce : mygallery_conf.ajaxNonce
						 },
						success: function (data){
							var imgdata = data.infinite_gallery;
							$('.myg_infinite_hover_gallery').append(imgdata)
							
						},
						complete: function() {
							RequestState1 = true;
							$('div.mygalleryLoading').html('');
							if($('.myg_hover_content').length > 0){
								if(jQuery.browser.mobile){
								   $('.myg_hover_content .myg_social').css('display','block')
								}else{
									$('.myg_hover_content').each(function(index, element) {
										$(this).hoverdir();
									});
								}
							}
						}
					});
				}
			},
			
			LoadInfiniteSlider: function (attachment_id, show_social, direction){
				if(RequestState1 == true) {
					$('div.mygalleryLoading').prepend('<span class="myg_spinnerx16"></span>'); 
					RequestState1 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,						
						type: "POST",
						dataType: "JSON",
						data: { 
							last_attachment_id : attachment_id,
							show_social : show_social,
							direction : direction,
							action : mygallery_conf.ajaxActions.myg_infinite_slider.action,
							nonce : mygallery_conf.ajaxNonce
						 },
						success: function (data){
							var imgdata = data.infinite_slider;
							$('.myg-bxslider-infinite-wrap').append(imgdata)
							
						},
						complete: function() {
							RequestState1 = true;
							$('div.mygalleryLoading').html('');
							
						}
					});
				}
			},
			
			ScrollEvent: function(){
				$(window).scroll(function(){
					var wintop = $(window).scrollTop(), docheight = $(document).height(), winheight = $(window).height();
					var  scrolltrigger = mygallery_conf.Scroll;
					if (wintop > lastWinTop){
					   // downscroll code
					   if  ((wintop/(docheight-winheight)) > scrolltrigger) {
							if($('div.myg_infinite_gallery').length > 0){
								var last_el = $("div.myg_infinite_gallery .myg_infinite_image").last();
								var attachment_id = last_el.attr("data-myg-attachment-id");
								var columns = last_el.attr("data-myg-columns");
								var show_title = last_el.attr("data-show-title");
								var show_desc = last_el.attr("data-show-desc");
								var show_social = last_el.attr("data-show-social");
								var style_class = last_el.find('.myg_image_wrap').attr('class');
								var style_css = last_el.find('.myg_image_wrap').attr('style');
								if(last_attachment_id != attachment_id){
									MyGalleryAjax.LoadGallery(attachment_id, columns, style_class, style_css, show_title, show_desc, show_social);
								}
								
								last_attachment_id = attachment_id;
							}
							if($('div.myg_infinite_portfolio').length > 0){
								var last_el = $("div.myg_infinite_portfolio .myg_infinite_image").last();
								var attachment_id = last_el.attr("data-myg-attachment-id");
								var columns = last_el.attr("data-myg-columns");
								var show_title = last_el.attr("data-show-title");
								var show_desc = last_el.attr("data-show-desc");
								var show_social = last_el.attr("data-show-social");
								var style_class = last_el.find('.myg_image_wrap').attr('class');
								var style_css = last_el.find('.myg_image_wrap').attr('style');
								if(last_attachment_id != attachment_id){
									MyGalleryAjax.LoadPortfolio(attachment_id, columns, style_class, style_css, show_title, show_desc, show_social);
								}
								
								last_attachment_id = attachment_id;
							}
							if($('ul.myg_infinite_masonry').length > 0){
								var last_el = $("ul.myg_infinite_masonry .myg_infinite_image").last();
								var attachment_id = last_el.attr("data-myg-attachment-id");
								var columns = last_el.attr("data-myg-columns");
								var show_title = last_el.attr("data-show-title");
								var show_desc = last_el.attr("data-show-desc");
								var show_social = last_el.attr("data-show-social");
								var style_class = last_el.find('.myg_image_wrap').attr('class');
								if(last_attachment_id != attachment_id){
									MyGalleryAjax.LoadInfinityMasonry(attachment_id, columns, style_class, show_title, show_desc, show_social);
								}
								
								last_attachment_id = attachment_id;
							}
							
							if($('div.myg_infinite_right_content').length > 0){
								var last_el = $("div.myg_infinite_right_content .myg_infinite_image").last();
								var attachment_id = last_el.attr("data-myg-attachment-id");
								var columns = last_el.attr("data-myg-columns");
								var show_title = last_el.attr("data-show-title");
								var show_desc = last_el.attr("data-show-desc");
								var show_social = last_el.attr("data-show-social");
								var style_class = last_el.find('.myg_image_wrap').attr('class');
								var style_css = last_el.find('.myg_image_wrap').attr('style');
								if(last_attachment_id != attachment_id){
									MyGalleryAjax.LoadInfiniteRightContent(attachment_id, columns, style_class, style_css, show_title, show_desc, show_social);
								}
								
								last_attachment_id = attachment_id;
							}
							if($('div.myg_infinite_hover_gallery').length > 0){
								var last_el = $("div.myg_infinite_hover_gallery .myg_infinite_image").last();
								var attachment_id = last_el.attr("data-myg-attachment-id");
								var columns = last_el.attr("data-myg-columns");
								var show_title = last_el.attr("data-show-title");
								var show_desc = last_el.attr("data-show-desc");
								var show_social = last_el.attr("data-show-social");
								var style_class = last_el.find('.myg_image_wrap').attr('class');
								var style_css = last_el.find('.myg_image_wrap').attr('style');
								if(last_attachment_id != attachment_id){
									MyGalleryAjax.LoadInfiniteHoverGallery(attachment_id, columns, style_class, style_css, show_title, show_desc, show_social);
								}
								
								last_attachment_id = attachment_id;
							}
							if($('div.myg-bxslider-infinite-wrap').length > 0){
								var last_sl = $("div.myg-bxslider-infinite-wrap .myg-bxslider-infinite").last();
								var attachment_ids = last_sl.find('.bxslider').last().attr("data-attachment-ids");
								var attachment_id = attachment_ids.split(',').pop();
								var show_social = last_sl.find('.bxslider li').last().attr("data-show-social");
								var direction = last_sl.find('.bxslider li').last().attr("data-slide-direction");
								if(last_slider_id != attachment_id){
									MyGalleryAjax.LoadInfiniteSlider(attachment_id, show_social, direction);
								}
								
								last_slider_id = attachment_id;
							}
							if($('div.myg_album_timeline_container').length > 0){
								var last_el = $("div.myg_album_timeline_container .myg_album_timeline_row").last();
								var album_id = last_el.attr("data-myg-album-id");
								var album_no = $("div.myg_album_timeline_container .myg_album_timeline_row").length;
								if(last_album_id != album_id){
									MyGalleryAjax.LoadTimelineAlbum(album_id, album_no);
								}
								
								last_album_id = album_id;
							}
							if($('div.myg_album_infinity').length > 0){
								var last_el = $("div.myg_album_infinity .myg_album_image_wrap").last();
								var album_id = last_el.attr("data-myg-album-id");
								var album_no = $("div.myg_album_infinity .myg_album_image_wrap").length;
								if(last_album_id != album_id){
									MyGalleryAjax.LoadInfiniteAlbum(album_id, album_no);
								}
								
								last_album_id = album_id;
							}
						}
					} else {
						  // upscroll code
					}
					lastWinTop = wintop;
				});
			},
			
			Like: function (attachment_id) {
				if(RequestState2 == true) {
					RequestState2 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,
						type: "POST",
						dataType: "JSON",
						data:{
							attachment_id : attachment_id,
							user_id : mygallery_conf.userID,
							user_ip: mygallery_conf.userIP,
							action : mygallery_conf.ajaxActions.myg_like.action,
							nonce : mygallery_conf.ajaxNonce
						},
						success: function(data) {
							var is_insert = data.insert_id;
							var attachment_id = data.attachment_id;
							var like_no = $('.myg_like_no_'+attachment_id).html()-1;
							if(is_insert != 0){
								// Do nothing
							}else{
								$('.myg_like_'+attachment_id).removeClass('myg_liked').addClass('myg_like').attr('data-event','myg_like')
								$(".myg_like_no_"+attachment_id).html(like_no);
							}
						},
						complete: function() {
							RequestState2 = true;
						}
					});
				}
			},
			
			CreateModal: function(){
				
				var html = '';
					html += '<div id="myg_modal_wrap">';
						html += '<span id="myg_modal_close" data-event="myg_modal_close"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25"><line x1="1.9" y1="1.9" x2="23.1" y2="23.1"></line><line x1="23.1" y1="1.9" x2="1.9" y2="23.1"></line></svg></span>';
						html += '<div id="myg_modal_container">';
						html += '<div id="myg_modal_image_inner" class="myg_clearfix myg_modal_right_hide">';
							html += '<div id="myg_modal_image_wrap" style="width:100%;">';
								html += '<ul id="myg_modal_image">';
								html += '</ul>';
								html += '<span id="myg_image_left" data-event="myg_image_left">&nbsp;</span>';
								html += '<span id="myg_image_right" data-event="myg_image_right">&nbsp;</span>';
							html += '</div>';
							html += '</div>';
						html += '</div>';
						html += '</div>';
					html += '</div>';
					html += '<div class="myg_modal_gallery_overflow"><style>html{overflow:hidden!important;}</style></div>';
					$("body").addClass('revs-modal-active');
					$("body").append(html);
			},
			
			PortfolioModal: function(){								
				var html = '';
					html += '<div id="myg_modal_wrap">';
						html += '<span id="myg_modal_close" data-event="myg_modal_close"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25"><line x1="1.9" y1="1.9" x2="23.1" y2="23.1"></line><line x1="23.1" y1="1.9" x2="1.9" y2="23.1"></line></svg></span>';
						html += '<div id="myg_modal_container">';
						html += '<div id="myg_modal_image_inner" class="myg_clearfix myg_portfolio_modal myg_modal_right_hide">';
							html += '<div id="myg_modal_image_wrap" style="width:100%;">';
								html += '<ul id="myg_modal_image">';
								html += '</ul>';
							html += '</div>';
							html += '</div>';
						html += '</div>';
						html += '</div>';
					html += '</div>';
					html += '<div class="myg_modal_gallery_overflow"><style>html{overflow:hidden!important;}</style></div>';
					$("body").addClass('revs-modal-active');
					$("body").append(html);
			},
			
			CreateAlbumModal: function(){
				var html = '';
					html += '<div id="myg_album_modal_wrap">';
						html += '<span id="myg_album_modal_close" data-event="myg_album_modal_close"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25"><line x1="1.9" y1="1.9" x2="23.1" y2="23.1"></line><line x1="23.1" y1="1.9" x2="1.9" y2="23.1"></line></svg></span>';
						html += '<div id="myg_album_modal_container">';
						html += '<div id="myg_album_modal_image_inner" class="myg_clearfix">';
							html += '<div id="myg_album_modal_image">';
							html += '</div>';
							html += '<div class="mygalleryLoading myg_clearfix"></div>';
						html += '</div>';
						html += '</div>';
					html += '</div>';
					html += '<div class="myg_modal_album_overflow"><style>html{overflow:hidden!important;}</style></div>';
					$("body").addClass('revs-modal-active');
					$("body").append(html);
			},
			
			masonry: function(){
				if($('.myg-masonry').length > 0){
					// init Masonry
					var $grid = $('.myg-masonry').masonry({
						itemSelector: '.myg-item',
					});
					// layout Masonry after each image loads
					$grid.imagesLoaded().progress( function() {
					  $grid.masonry('layout');
					});
				}
			},
			
			LoadModalImage: function (attachment_id, parent_id) {
				if(RequestState3 == true) {
					RequestState3 = false;
					$("#myg_modal_image_wrap").append('<div id="myg_image_loading"><span class="sqs-spin"></span></div>');
					$.ajax({
						url: mygallery_conf.ajaxURL,
						type: "POST",
						dataType: "JSON",
						data:{
							attachment_id : attachment_id,
							parent_id : parent_id,
							user_id : mygallery_conf.userID,
							user_ip: mygallery_conf.userIP,
							action : mygallery_conf.ajaxActions.myg_load_modal_content.action,
							nonce : mygallery_conf.ajaxNonce
						},
						success: function(data) {
							var attachment_id = data.attachment_id;
							var post_parent = data.post_parent;
							
							var image = '<li id="myg_img_list_'+attachment_id+'" data-myg-attachment-id="'+attachment_id+'"  data-myg-parent-id="'+post_parent+'" class="myg_show" data-image-status="1"><img class="myg_show" src="'+data.image_src+'" ></li>';
							

							$("#myg_modal_image_wrap #myg_image_loading").remove();
							$("#myg_modal_image").append(image);
														
						},
						complete: function() {
							RequestState3 = true;
						}
					});
				}
			},
			
			LoadThumbModalImage: function (attachment_id, parent_id) {
				if(RequestState3 == true) {
					RequestState3 = false;
					$("#myg_modal_image_wrap").append('<div id="myg_image_loading"><span class="sqs-spin"></span></div>');
					$.ajax({
						url: mygallery_conf.ajaxURL,
						type: "POST",
						dataType: "JSON",
						data:{
							attachment_id : attachment_id,
							parent_id : parent_id,
							user_id : mygallery_conf.userID,
							user_ip: mygallery_conf.userIP,
							action : mygallery_conf.ajaxActions.myg_load_thumb_modal.action,
							nonce : mygallery_conf.ajaxNonce
						},
						success: function(data) {
							var attachment_id = data.attachment_id;
							var post_parent = data.post_parent;
							var title = data.title;
							var desc = data.desc;
							
							var image = '<li id="myg_img_list_'+attachment_id+'" data-myg-attachment-id="'+attachment_id+'"  data-myg-parent-id="'+post_parent+'" class="myg_show" data-image-status="1"><img class="myg_show" src="'+data.image_src+'" ></li>';
							
							var comments_row = data.comments_row;
							var content = '';
							var myg_like = data.is_liked?'myg_liked':'myg_like';
							var myg_view = data.is_viewed?'myg_viewed':'myg_view';
							var like_event = data.is_liked?'':' data-event="myg_like"';
							var likes = data.likes;
							var views = data.views;
							var comments = data.comments;
							
							content += '<div class="myg_modal_content myg_show">';
								content += '<h4 class="myg_modal_title">'+title+'</h4>';
								content += '<div class="myg_modal_desc">'+desc+'</div>';
								
								content += '<div class="myg_social myg_modal_social myg_clearfix">';
									content += '<div class="myg_social_left">';
										content += '<span data-event="myg_social_share_open" class="myg_share">&nbsp;</span>';
										content += data.social;
									content += '</div>';
									content += '<div class="myg_social_right">';
										content += '<span class="'+myg_view+'" data-myg-attachment-id="'+attachment_id+'">&nbsp;</span><span>'+views+'</span>';
										content += '<span class="myg_like_tab '+myg_like+' myg_like_'+attachment_id+'" data-myg-attachment-id="'+attachment_id+'"'+like_event+'>&nbsp;</span><span class="myg_like_no_'+attachment_id+'">'+likes+'</span>';
										content += '<span class="myg_comment" data-myg-attachment-id="'+attachment_id+'">&nbsp;</span><span>'+comments+'</span>';
									content += '</div>';
								content += '</div>';
								
								content += '<div class="myg_modal_comments_wrap">';
									content += '<div class="myg_modal_comments" data-comment-box-id="'+attachment_id+'">';
									content += comments_row;
									content += '</div>';
									content += '<div class="myg_modal_form">';
										content += '<input type="text" name="myg_comments" data-event="myg_post_comment" placeholder="Write a comment..." data-myg-attachment-id="'+attachment_id+'" value="" />';
									content += '</div>';
								content += '</div>';
							content += '</div>';
							
														
							$("#myg_modal_image_wrap #myg_image_loading").remove();
							$("#myg_modal_image li").each(function(index, element) {
								if($(this).hasClass('myg_show')){
									$(this).removeClass('myg_show').addClass('myg_hide');
									$(this).find('img').removeClass('myg_show').addClass('myg_hide');
									$('#myg_modal_content_wrap').find('.myg_modal_content').eq(index).removeClass('myg_show').addClass('myg_hide');
								}
							});
							$("#myg_modal_image").append(image);
							$('#myg_modal_content_wrap').append(content);
														
						},
						complete: function() {
							RequestState3 = true;
						}
					});
				}
			},
			
			modalThumbInit: function(){
				$(".myg_thumb_slider.modal_caroufredsel").each(function() {					
					$(this).carouFredSel({
						circular: true,
						infinite: true,
						responsive: false,
						items     : {
							width			: 80,
							height			: 80,
							visible      	: "variable",
							minimum    		: 4,
							start  			: 0
						},
						direction : "left",
						align: "center",
						padding: 5,
						scroll : {
							items         : 1,
							fx         : "scroll",
							easing        : "linear",
							duration      : 500,
							pauseOnHover  : false
						},
						auto: {
							play: true
						},
						prev: {
							button: "#msprev",
							key: "right"
						},
						next: {
							button: "#msnext",
							key: "left"
						},
						swipe: {
							onTouch: true,
						},
						
					});
				});
			},
			
			LoadInfiniteAlbum: function (album_id, album_no){
				if(RequestState1 == true) {
					$('div.mygalleryLoading').prepend('<span class="myg_spinnerx16"></span>'); 
					RequestState1 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,						
						type: "POST",
						dataType: "JSON",
						data: { 
							album_id : album_id,
							album_no : album_no,
							action : mygallery_conf.ajaxActions.myg_infinite_album.action,
							nonce : mygallery_conf.ajaxNonce
						 },
						success: function (data){
							var imgdata = data.infinite_album;
							$('.myg_album_container').append(imgdata)
							
						},
						complete: function() {
							MyGalleryAjax.AlbumHoverEffect();
							RequestState1 = true;
							$('div.mygalleryLoading').html('');
						}
					});
				}
			},
			
			LoadTimelineAlbum: function (album_id, album_no){
				if(RequestState1 == true) {
					$('div.mygalleryLoading').prepend('<span class="myg_spinnerx16"></span>'); 
					RequestState1 = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,						
						type: "POST",
						dataType: "JSON",
						data: { 
							album_id : album_id,
							album_no : album_no,
							action : mygallery_conf.ajaxActions.myg_timeline_album.action,
							nonce : mygallery_conf.ajaxNonce
						 },
						success: function (data){
							var imgdata = data.timeline_album;
							$('.myg_album_timeline_container').append(imgdata)
							
						},
						complete: function() {
							RequestState1 = true;
							$('div.mygalleryLoading').html('');
						}
					});
				}
			},
			
			LoadAlbumImage: function (album_id) {
				if(RequestState3 == true) {
					RequestState3 = false;
					$("#myg_album_modal_image_inner").addClass('myg_bgspin');
					var album_id = album_id, is_more_gallery = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,
						type: "POST",
						dataType: "JSON",
						data:{
							album_id : album_id,
							action : mygallery_conf.ajaxActions.myg_load_album_gallery.action,
							nonce : mygallery_conf.ajaxNonce
						},
						success: function(data) {
							var album_gallery = data.album_gallery;
							album_id = data.album_id;
							is_more_gallery = data.is_more_gallery;
																												
							$("#myg_album_modal_image_inner").removeClass('myg_bgspin')
							$('#myg_album_modal_image').append(album_gallery);														
						},
						complete: function() {
							RequestState3 = true;
							var docheight = $(document).height(), winheight = $(window).height();
							
							if(is_more_gallery){
								var last_el = $("#myg_album_modal_image .myg_infinite_image").last();
								var last_attach_id = last_el.attr("data-myg-attachment-id");
								var image_size = last_el.attr("data-myg-columns");
								var show_title = last_el.attr("data-show-title");
								var show_desc = last_el.attr("data-show-desc");
								var show_social = last_el.attr("data-show-social");
								var style_class = last_el.find('.myg_image_wrap').attr('class');
								var style_css = last_el.find('.myg_image_wrap').attr('style');
								var no_of_img = $("#myg_album_modal_image .myg_infinite_image").length;
								if(last_album_attach_id != last_attach_id){
									MyGalleryAjax.ReLoadAlbumImage(album_id, last_attach_id, image_size, style_class, style_css, show_title, show_desc, show_social, no_of_img);
								}
								last_album_attach_id = last_attach_id;
							}
							if($('.myg_hover_content').length > 0){
								if(jQuery.browser.mobile){
								   $('.myg_hover_content .myg_social').css('display','block')
								}else{
									$('.myg_hover_content').each(function(index, element) {
										$(this).hoverdir();
									});
								}
							}
							
						}
					});
				}
			},
			
			ReLoadAlbumImage: function (album_id, last_attach_id, image_size, style_class, style_css, show_title, show_desc, show_social, no_of_img) {
				if(RequestState3 == true) {
					RequestState3 = false;
					$("#mygalleryLoading").addClass('myg_bgspin');
					var album_id = album_id, is_more_gallery = false;
					$.ajax({
						url: mygallery_conf.ajaxURL,
						type: "POST",
						dataType: "JSON",
						data:{
							album_id : album_id,
							last_attach_id : last_attach_id,
							image_size : image_size,
							style_class : style_class,
							style_css : style_css,
							show_title : show_title,
							show_desc : show_desc,
							show_social : show_social,
							no_of_img : no_of_img,
							action : mygallery_conf.ajaxActions.myg_reload_album_gallery.action,
							nonce : mygallery_conf.ajaxNonce
						},
						success: function(data) {
							var album_gallery = data.album_gallery;
							//var title = data.title;
							//var desc = data.desc;
							album_id = data.album_id;
							is_more_gallery = data.is_more_gallery;
																												
							$("#mygalleryLoading").removeClass('myg_bgspin')
							$('#myg_album_modal_image').append(album_gallery);
								
						},
						complete: function() {
							RequestState3 = true;
														
							if(is_more_gallery ){
								var last_el = $("#myg_album_modal_image .myg_infinite_image").last();
								var last_attach_id = last_el.attr("data-myg-attachment-id");
								var image_size = last_el.attr("data-myg-columns");
								var show_title = last_el.attr("data-show-title");
								var show_desc = last_el.attr("data-show-desc");
								var show_social = last_el.attr("data-show-social");
								var style_class = last_el.find('.myg_image_wrap').attr('class');
								var style_css = last_el.find('.myg_image_wrap').attr('style');
								var no_of_img = $("#myg_album_modal_image .myg_infinite_image").length;
								if(last_album_attach_id != last_attach_id){
									MyGalleryAjax.ReLoadAlbumImage(album_id, last_attach_id, image_size, style_class, style_css, show_title, show_desc, show_social, no_of_img);
								}
								
								last_album_attach_id = last_attach_id;
							}
							
							if($('.myg_hover_content').length > 0){
								if(jQuery.browser.mobile){
								   $('.myg_hover_content .myg_social').css('display','block')
								}else{
									$('.myg_hover_content').each(function(index, element) {
										$(this).hoverdir();
									});
								}
							}
						}
					});
				}
			},
			
			LoadNextModalImage: function (attachment_id, post_parent, myg_nav) {
				if(RequestState3 == true) {
					RequestState3 = false;
					$("#myg_modal_image_wrap").append('<div id="myg_image_loading"><span class="sqs-spin"></span></div>');
					$.ajax({
						url: mygallery_conf.ajaxURL,
						type: "POST",
						dataType: "JSON",
						data:{
							attachment_id : attachment_id,
							parent_id : post_parent,
							user_id : mygallery_conf.userID,
							user_ip: mygallery_conf.userIP,
							myg_nav: myg_nav,
							action : mygallery_conf.ajaxActions.myg_next_modal_content.action,
							nonce : mygallery_conf.ajaxNonce
						},
						success: function(data) {
							var attachment_id = data.attachment_id;
							var parent_id = data.parent_id;
							var title = data.title;
							var desc = data.desc;
							var has_next = data.has_next;
							var image = '<li id="myg_img_list_'+attachment_id+'" data-myg-attachment-id="'+attachment_id+'" data-myg-parent-id="'+parent_id+'" class="myg_show" data-image-status="1"><img class="myg_show" src="'+data.image_src+'" ></li>';
							
							var myg_nav = data.myg_nav;
																					
							$("#myg_modal_image_wrap #myg_image_loading").remove();
							if(has_next == true){
								$("#myg_modal_image li").each(function(index, element) {
									if($(this).hasClass('myg_show')){
										$(this).removeClass('myg_show').addClass('myg_hide');
										$(this).find('img').removeClass('myg_show').addClass('myg_hide');
									}
								});
								if(myg_nav == 'next'){
									$("#myg_modal_image").append(image);
								}else{
									$("#myg_modal_image").prepend(image);
								}
							}else if(has_next == false){
								if(myg_nav == 'next'){
									var last = $("#myg_modal_image li").last();
									last.removeClass('myg_hide').addClass('myg_show');
									last.find('img').removeClass('myg_hide').addClass('myg_show')
									.delay(50, "steps")
									.queue("steps", function(next) {
										$("#myg_modal_image").append('<div id="myg_no_more">'+mygallery_conf.no_next_img+'</div>');
										next();
									})
									.delay(2000, "steps")
									.queue("steps", function(next) {
										$("#myg_modal_image").find('#myg_no_more').remove();
										next();
									})
									.dequeue( "steps" );
								}else{
									var first = $("#myg_modal_image li").first();
									first.removeClass('myg_hide').addClass('myg_show');
									first.find('img').removeClass('myg_hide').addClass('myg_show')
									.delay(50, "steps")
									.queue("steps", function(next) {
										$("#myg_modal_image").append('<div id="myg_no_more">'+mygallery_conf.no_prev_img+'</div>');
										next();
									})
									.delay(2000, "steps")
									.queue("steps", function(next) {
										$("#myg_modal_image").find('#myg_no_more').remove();
										next();
									})
									.dequeue( "steps" );
								}
							}
						},
						complete: function() {
							RequestState3 = true;
						}
					});
				}
			},
						
			eventHandler: function () {
				$("body").on("click", "[data-event]", function (e){					
					var Event = $(this).attr("data-event");
					var _this = $(this);
					switch(Event) {						
						case "myg_image_open":
							var attach_id = $(this).attr("data-myg-attachment-id");
							var parent_id = $(this).attr("data-myg-parent-id");
							if($("#myg_modal_wrap").length > 0){
								$("#myg_modal_image li").each(function(index, element) {
									if($(this).hasClass('myg_show')){
                                    	$(this).removeClass('myg_show').addClass('myg_hide');
										$(this).find('img').removeClass('myg_show').addClass('myg_hide');
										$('#myg_modal_content_wrap').find('.myg_modal_content').eq(index).removeClass('myg_show').addClass('myg_hide');
									}
                                });
								$("#myg_modal_wrap").css('display','block');
								$("#myg_modal_wrap").append('<div class="myg_modal_gallery_overflow"><style>html{overflow:hidden!important;}</style></div>');
							}else{
								if(attach_id == parent_id){
									MyGalleryAjax.PortfolioModal();
								}else{
									MyGalleryAjax.CreateModal();
								}
							}
							
							if($('#myg_img_list_'+attach_id).length > 0){
								$('#myg_img_list_'+attach_id).removeClass('myg_hide').addClass('myg_show');
								$('#myg_img_list_'+attach_id+' img').removeClass('myg_hide').addClass('myg_show');
								$('#myg_modal_content_wrap').find('.myg_modal_content').eq($('#myg_img_list_'+attach_id).index()).removeClass('myg_hide').addClass('myg_show');
								
							}else{
								MyGalleryAjax.LoadModalImage(attach_id, parent_id);
							}
							
						break;
						
						case "myg_open_album_gallery":
							var album_id = $(this).attr("data-myg-album-id");
							MyGalleryAjax.CreateAlbumModal();
							MyGalleryAjax.LoadAlbumImage(album_id);							
						break;
						
						case "myg_image_left":
							var list_no = $("#myg_modal_image li").length;
							var myg_nav = 'prev';
							$("#myg_modal_image li").each(function(index, element) {
								if($(this).hasClass('myg_show')){
									var attachment_id = $(this).attr('data-myg-attachment-id');
									var post_parent = $(this).attr('data-myg-parent-id');
									var el_no = parseInt(index)-1;
									if(list_no > 1 && el_no != -1){
										$(this).removeClass('myg_show').addClass('myg_hide');
										$(this).find('img').removeClass('myg_show').addClass('myg_hide');
										$('#myg_modal_content_wrap').find('.myg_modal_content').eq(index).removeClass('myg_show').addClass('myg_hide');
										$("#myg_modal_image li").eq(el_no).removeClass('myg_hide').addClass('myg_show');
										$("#myg_modal_image li").eq(el_no).find('img').removeClass('myg_hide').addClass('myg_show');
										$('#myg_modal_content_wrap').find('.myg_modal_content').eq(el_no).removeClass('myg_hide').addClass('myg_show');
										return false;
									}else{
										MyGalleryAjax.LoadNextModalImage(attachment_id, post_parent, myg_nav);
										return false;
									}
								}
							});
						break;
						
						case "myg_image_right":
							var list_no1 = $("#myg_modal_image li").length;
							var myg_nav = 'next';
							$("#myg_modal_image li").each(function(index, element) {
								if($(this).hasClass('myg_show')){
									var attachment_id = $(this).attr('data-myg-attachment-id');
									var post_parent = $(this).attr('data-myg-parent-id');
									var el_no1 = parseInt(index)+1;
									if(list_no1 > el_no1){
										$(this).removeClass('myg_show').addClass('myg_hide');
										$(this).find('img').removeClass('myg_show').addClass('myg_hide');
										$('#myg_modal_content_wrap').find('.myg_modal_content').eq(index).removeClass('myg_show').addClass('myg_hide');
										$("#myg_modal_image li").eq(el_no1).removeClass('myg_hide').addClass('myg_show');
										$("#myg_modal_image li").eq(el_no1).find('img').removeClass('myg_hide').addClass('myg_show');
										$('#myg_modal_content_wrap').find('.myg_modal_content').eq(el_no1).removeClass('myg_hide').addClass('myg_show');
										return false;
									}else{
										MyGalleryAjax.LoadNextModalImage(attachment_id, post_parent, myg_nav);
										return false;
									}
								}
							});
						break;
						
						case "myg_image_thumb":
							var attachment_id = _this.attr('data-myg-attachment-id');
							var post_parent = _this.attr('data-myg-parent-id');
							var thumb_loaded = false;
							
							if($('#myg_img_list_'+attachment_id).length > 0 && $('#myg_img_list_'+attachment_id).hasClass('myg_show')){
								// Do nothing
							}else if($('#myg_img_list_'+attachment_id).length > 0 && $('#myg_img_list_'+attachment_id).hasClass('myg_hide')){
								$("#myg_modal_image li").each(function(index, element) {
									if($(this).hasClass('myg_show')){
                                    	$(this).removeClass('myg_show').addClass('myg_hide');
										$(this).find('img').removeClass('myg_show').addClass('myg_hide');
										$('#myg_modal_content_wrap').find('.myg_modal_content').eq(index).removeClass('myg_show').addClass('myg_hide');
									}
                                });
								$('#myg_img_list_'+attachment_id).removeClass('myg_hide').addClass('myg_show');
								$('#myg_img_list_'+attachment_id+' img').removeClass('myg_hide').addClass('myg_show');
								$('#myg_modal_content_wrap').find('.myg_modal_content').eq($('#myg_img_list_'+attachment_id).index()).removeClass('myg_hide').addClass('myg_show');			
								
							}else{
								MyGalleryAjax.LoadThumbModalImage(attachment_id, post_parent);
							}
							
						break;
						
						case "myg_modal_close":
							if($(e.target).is($(this)) || $(e.target).is($(this).find('*'))){
								$("#myg_modal_wrap").remove();
								$(".myg_modal_gallery_overflow").remove();
								$("body").removeClass('myg-modal-active');
							}
						break;
						
						case "myg_album_modal_close":
							if($(e.target).is($(this)) || $(e.target).is($(this).find('*'))){
								$("#myg_album_modal_wrap").remove();
								$(".myg_modal_album_overflow").remove();
								$("body").removeClass('myg-modal-active');
							}
						break;
						
						case "myg_social_share_open":
							if($(this).siblings(".myg_social_tab").is(":visible")){
								$(this).siblings(".myg_social_tab").css({'display':'none'});
							}else{
								$(this).siblings(".myg_social_tab").css({'bottom':'30px','left':'0','display':'block'});
							}
						break;
												
						case "myg_like":
							var attach_id = $(this).attr("data-myg-attachment-id");
							var like_no = parseInt($('.myg_like_no_'+attach_id).html())+1;
							if($(this).hasClass('myg_liked')){
								// do nothing
							}else{
                                $(".myg_like_"+attach_id).removeClass('myg_like').addClass('myg_liked').removeAttr('data-event');
								$(".myg_like_no_"+attach_id).html(like_no);
								MyGalleryAjax.Like(attach_id);
							}
							
						break;
						
					}
					
				});
				
				$("body").on("keyup", "[data-event]", function (e){		
					var Event = $(this).attr("data-event");
					//var el = event.target;
					switch(Event) {												
						case "myg_post_comment":
							if(e.keyCode == 13) {
								//event.stopPropagation();
								//event.preventDefault();
								var userImage = mygallery_conf.avatar;
								var d = new Date();
								var time = d.getTime();
								var comment = $.trim($(this).val());
								var attachment_id = $(this).attr("data-myg-attachment-id");
								
								MyGalleryAjax.PostComments(userImage, time, comment, attachment_id);
								$(this).val("");
							}
						break;
					}
				})
				
				$("body").on("click", function (e){	
					var sel = $('.myg_social_tab');
					  if (sel.is(":visible") && !$(e.target).is(".myg_share") && !$(e.target).is(sel) && !$(e.target).is(sel.find('*'))) {
						  sel.fadeOut(200);
					  }
				})
			},
			
			PostComments: function(userImage, time, comment, attachment_id){
					var dateObj = new Date();
					 var month_name = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
					var month = month_name[dateObj.getMonth()]; 
					var day = dateObj.getDate();
					var year = dateObj.getFullYear();
					var newtime = 'Just now'
					var newdate =  day+ "-" + month+ "-" +year;
					
					var Container = $('[data-comment-box-id="' + attachment_id + '"]');
					//var pMessage = Message.replace(/(smiley[0-9]{1,3})/g,'<span class="bpcSmiley bpc-$1"></span>');
					var msg = '<div class="myg_comment_row myg_clearfix" data-msgtime="'+time+'"><div class="myg_comment_avatar"><img src="'+userImage+'" /></div><div class="myg_comment_text">'+comment+'<div class="myg_comment_time"><div class="myg_half">'+newdate+'</div><div class="myg_half myg_text_right">'+newtime+'</div></div></div></div>';
					
					if(comment != '') {
						$(Container).append(msg);
						$(Container).scrollTop($(Container).prop("scrollHeight"));
						$.ajax({
							url: mygallery_conf.ajaxURL,						
							type: "POST",
							dataType: "JSON",
							data: { 
								myg_comment: comment,
								attachment_id: attachment_id,
								time: time,
								action : mygallery_conf.ajaxActions.myg_post_comment.action,
								nonce : mygallery_conf.ajaxNonce
							 },
							success: function(data) {
								if(data.is_insert == 1){
									// do nothing
								}else{
									//$('[data-msgtime="' + data.row_time + '"]').addClass('cr_error_bg')
								}
							}
						});
					}
			},
			
			AlbumHoverEffect: function(){
				if($(".myg_album_image").length > 0){
					$(".myg_album_image").delegate('img', 'mouseenter', function() {
						//detect if cursor is hovering on an image which has a div with class name 'current
						//attach the css class rotate1 , rotate2 and rotate3 to each image in the stack to rotate the images to specific degrees
						var $parent = $(this).parent();
						$parent.find('img.myg_album_photo1').addClass('myg_rotate1');
						$parent.find('img.myg_album_photo2').addClass('myg_rotate2');
						$parent.find('img.myg_album_photo3').addClass('myg_rotate3');
						$parent.find('img.myg_album_photo1').css("left","50px");//reposition the last and first photo
						$parent.find('img.myg_album_photo3').css("left","-50px");
				 
					})
					.delegate('img', 'mouseleave', function() {//if user removes curser on image
					//remote all class previously added to give the photos it's initial position
						$('img.myg_album_photo1').removeClass('myg_rotate1');
						$('img.myg_album_photo2').removeClass('myg_rotate2');
						$('img.myg_album_photo3').removeClass('myg_rotate3');
						$('img.myg_album_photo1').css("left","");
						$('img.myg_album_photo3').css("left","");
				 
					});
				}
			},
									
			resizewindow: function(){
				$(window).resize(function() {
					if($('#myg_modal_wrap').length > 0 || $('#myg_album_modal_wrap').length > 0){
						var win_w = $(window).width();
						var win_h = $(window).height();
						$('#myg_modal_wrap').css({'width':win_w+'px','height':win_h+'px'});
						$('#myg_album_modal_wrap').css({'width':win_w+'px','height':win_h+'px'});
					}
				});
				
				
				if($(".myg-caroufredsel-full").length > 0){
					var win_w = $(window).width();
					var win_h = (1080*win_w)/1920;
					$(".myg_full_caroufredsel").each(function() {					
						$(this).carouFredSel({
							circular: true,
							infinite: true,
							responsive: true,
							items     : {
								width			: win_w,
								height			: win_h,
								visible      	: 1,
								minimum    		: 2,
								start  			: 0
							},
							direction : "left",
							align: "center",
							padding: 0,
							scroll : {
								items         : 1,
								fx         	  : mygallery_conf.slider_transition,
								easing        : mygallery_conf.slider_easing,
								duration      : mygallery_conf.slide_speed,
								pauseOnHover  : false
							},
							auto: {
								play: true,
								timeoutDuration: mygallery_conf.next_slide
							},
							prev: {
								button: "#mfcprev",
								key: "right"
							},
							next: {
								button: "#mfcnext",
								key: "left"
							},
							swipe: {
								onTouch: true,
							},
							onCreate: MyGalleryAjax.onCreate
						});
					});
				}
				
				if($(".myg-carousel").length > 0){
					$(".myg_caroufredsel").each(function() {					
						$(this).carouFredSel({
							circular: true,
							infinite: true,
							responsive: false,
							items     : {
								width			: "variable",
								height			: 186,
								visible      	: "variable",
								minimum    		: 2,
								start  			: 0
							},
							direction : "left",
							align: "center",
							padding: mygallery_conf.carousel_padding,
							scroll : {
								items         : 1,
								fx            : mygallery_conf.slider_transition,
								easing        : mygallery_conf.slider_easing,
								duration      : mygallery_conf.slide_speed,
								pauseOnHover  : false
							},
							auto: {
								play: true,
								timeoutDuration: mygallery_conf.next_slide
							},
							prev: {
								button: "#mcprev",
								key: "right"
							},
							next: {
								button: "#mcnext",
								key: "left"
							},
							swipe: {
								onTouch: true,
							},
							
						});
					});
				}
				
				if($(".myg_album_carousel").length > 0){
					$(".myg_album_carousel").each(function() {					
						$(this).carouFredSel({
							circular: true,
							infinite: true,
							responsive: false,
							items     : {
								width			: "variable",
								height			: 220,
								visible      	: "variable",
								minimum    		: 2,
								start  			: 0
							},
							direction : "left",
							align: "center",
							padding: mygallery_conf.carousel_padding,
							scroll : {
								items         : 1,
								fx            : mygallery_conf.slider_transition,
								easing        : mygallery_conf.slider_easing,
								duration      : mygallery_conf.slide_speed,
								pauseOnHover  : true
							},
							auto: {
								play: true,
								timeoutDuration: mygallery_conf.next_slide
							},
							prev: {
								button: "#acprev",
								key: "right"
							},
							next: {
								button: "#acnext",
								key: "left"
							},
							swipe: {
								onTouch: true,
							},
							
						});
					});
				}
				
			},
			onCreate: function() {
			  $(window).on('resize', MyGalleryAjax.onResize).trigger('resize');
			},
			
			onResize: function() {
				var $carousel = $(".myg_full_caroufredsel");
			  // Get all the possible height values from the slides
			  var heights = $carousel.children().map(function() { return $(this).height(); });
			  // Find the max height and set it
			  $carousel.parent().add($carousel).height(Math.max.apply(null, heights));
			},
						
			HoverContent : function(){
				jQuery.HoverDir 	= function( options, element ) {
					this.$el	= $( element );
					this._init( options );
				};
				jQuery.HoverDir.defaults 	= {
					hoverDelay	: 0,
					reverse		: false
				};
				jQuery.HoverDir.prototype 	= {
					_init : function( options ) {
						this.options = $.extend( true, {}, $.HoverDir.defaults, options );
						this._loadEvents();
					},
					_loadEvents	: function() {
						var _self = this;
						this.$el.on( 'mouseenter.hoverdir, mouseleave.hoverdir', function( event ) {
							var $el			= $(this),
								evType		= event.type,
								$hoverElem	= $el.find( 'article' ),
								direction	= _self._getDir( $el, { x : event.pageX, y : event.pageY } ),
								hoverClasses= _self._getClasses( direction );
							
							$hoverElem.removeClass();
							
							if( evType === 'mouseenter' ) {
								$hoverElem.hide().addClass( hoverClasses.from )
								.delay(50, "steps")
								.queue("steps", function(next) {
									$hoverElem.siblings('.myg_social').slideToggle('slow');
									next();
								})
								.dequeue( "steps" );
								clearTimeout( _self.tmhover );
								_self.tmhover	= setTimeout( function() {
									$hoverElem.show( 0, function() {
										$(this).addClass( 'myg-animate' ).addClass( hoverClasses.to );
									} );
									
								}, _self.options.hoverDelay );
								
							}else if(evType === 'mouseleave'){
								$hoverElem.addClass( 'myg-animate' )
								.delay(50, "steps")
								.queue("steps", function(next) {
									$hoverElem.siblings('.myg_social').slideToggle('slow');
									next();
								})
								.dequeue( "steps" );
								clearTimeout( _self.tmhover );
								$hoverElem.addClass( hoverClasses.from );
								
							}
						} );
					},
					_getDir	: function( $el, coordinates ) {
						var w = $el.width(),
							h = $el.height(),
							x = ( coordinates.x - $el.offset().left - ( w/2 )) * ( w > h ? ( h/w ) : 1 ),
							y = ( coordinates.y - $el.offset().top  - ( h/2 )) * ( h > w ? ( w/h ) : 1 ),
							direction = Math.round( ( ( ( Math.atan2(y, x) * (180 / Math.PI) ) + 180 ) / 90 ) + 3 )  % 4;
						return direction;
						
					},
					_getClasses	: function( direction ) {
						var fromClass, toClass;
						switch( direction ) {
							case 0:
								( !this.options.reverse ) ? fromClass = 'myg-slideFromTop' : fromClass = 'myg-slideFromBottom';
								toClass		= 'myg-slideTop';
								break;
							case 1:
								( !this.options.reverse ) ? fromClass = 'myg-slideFromRight' : fromClass = 'myg-slideFromLeft';
								toClass		= 'myg-slideLeft';
								break;
							case 2:
								( !this.options.reverse ) ? fromClass = 'myg-slideFromBottom' : fromClass = 'myg-slideFromTop';
								toClass		= 'myg-slideTop';
								break;
							case 3:
								( !this.options.reverse ) ? fromClass = 'myg-slideFromLeft' : fromClass = 'myg-slideFromRight';
								toClass		= 'myg-slideLeft';
								break;
						};
						return { from : fromClass, to: toClass };
					}
				};
				
				var logError = function( message ) {
					if ( this.console ) {
						console.error( message );
					}
				};
				
				jQuery.fn.hoverdir = function( options ) {
					if ( typeof options === 'string' ) {
						var args = Array.prototype.slice.call( arguments, 1 );
						this.each(function() {
							var instance = $.data( this, 'hoverdir' );
							if ( !instance ) {
								logError( "cannot call methods on hoverdir prior to initialization; " +
								"attempted to call method '" + options + "'" );
								return;
							}
							if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
								logError( "no such method '" + options + "' for hoverdir instance" );
								return;
							}
							instance[ options ].apply( instance, args );
						});
					} 
					else {
						this.each(function() {
							var instance = $.data( this, 'hoverdir' );
							if ( !instance ) {
								$.data( this, 'hoverdir', new $.HoverDir( options, this ) );
							}
						});
					
					}
					return this;
				};
				
				if($('.myg_hover_content').length > 0){
					if(jQuery.browser.mobile){
					   $('.myg_hover_content .myg_social').css('display','block')
					}else{
						$('.myg_hover_content').each(function(index, element) {
							$(this).hoverdir();
						});
					}
				}
				
			}
		}
		
		MyGalleryAjax.mygalleryInit();
		
	});
}(jQuery));
