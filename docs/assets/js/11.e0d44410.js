(window.webpackJsonp=window.webpackJsonp||[]).push([[11,8],{1:function(t,e,r){"use strict";r.r(e);var n={name:"root-comp",props:["items"],data:function(){return{rootUrl:"http://emall.com.np/api/"}}},o=r(0),a=Object(o.a)(n,void 0,void 0,!1,null,null,null);e.default=a.exports},2:function(t,e,r){"use strict";r.r(e);var n={extends:r(1).default,name:"my-api",methods:{checkIfRequired:function(t,e){return"params"===t[0]&&-1!==e.indexOf("*")},displayProperly:function(t){var e=t[0],r=t[1];return"url"===e?"<a href='"+this.rootUrl+r+"' target='_blank'>"+this.rootUrl+r+"</a>":r}}},o=(r(51),r(0)),a=Object(o.a)(n,function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[r("table",{staticClass:"table"},[r("tbody",[r("tr",[r("td",[t._v(t._s("Method".toUpperCase()))]),r("td",[t._t("method")],2)]),t._l(t.items,function(e){return r("tr",[r("td",[t._v(t._s(e[0].toUpperCase()))]),"object"!=typeof e[1]?r("td",{domProps:{innerHTML:t._s(t.displayProperly(e))}}):r("td",[r("ul",t._l(e[1],function(n){return r("li",{class:{required:t.checkIfRequired(e,n)}},[t._v(t._s(n))])}))])])}),t._l(t.items,function(e){return"auth"===e[0]?r("tr",[r("td",[t._v(t._s("headers".toUpperCase()))]),r("td",[r("ul",[r("li",[t._v("Accept: application/json")]),!0===e[1]?r("li",[t._v("Authorization: Bearer {token}")]):t._e()])])]):t._e()})],2)])])},[],!1,null,"1e7427c8",null);e.default=a.exports},50:function(t,e,r){},51:function(t,e,r){"use strict";var n=r(50);r.n(n).a}}]);