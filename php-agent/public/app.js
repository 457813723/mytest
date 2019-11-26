!function (t, e){
    "function" == typeof define && define.amd ? define(function (){
        return e(t)
    }) : e(t)
}(this, function (t){
    var e = function (){
        function $(t){
            return null == t ? String(t) : S[C.call(t)] || "object"
        }

        function F(t){
            return "function" == $(t)
        }

        function k(t){
            return null != t && t == t.window
        }

        function M(t){
            return null != t && t.nodeType == t.DOCUMENT_NODE
        }

        function R(t){
            return "object" == $(t)
        }

        function Z(t){
            return R(t) && !k(t) && Object.getPrototypeOf(t) == Object.prototype
        }

        function z(t){
            var e = !!t && "length" in t && t.length, n = r.type(t);
            return "function" != n && !k(t) && ("array" == n || 0 === e || "number" == typeof e && e > 0 && e - 1 in t)
        }

        function q(t){
            return a.call(t, function (t){
                return null != t
            })
        }

        function H(t){
            return t.length > 0 ? r.fn.concat.apply([], t) : t
        }

        function I(t){
            return t.replace(/::/g, "/").replace(/([A-Z]+)([A-Z][a-z])/g, "$1_$2").replace(/([a-z\d])([A-Z])/g, "$1_$2").replace(/_/g, "-").toLowerCase()
        }

        function V(t){
            return t in l ? l[t] : l[t] = new RegExp("(^|\\s)" + t + "(\\s|$)")
        }

        function _(t, e){
            return "number" != typeof e || h[I(t)] ? e : e + "px"
        }

        function B(t){
            var e, n;
            return c[t] || (e = f.createElement(t), f.body.appendChild(e), n = getComputedStyle(e, "").getPropertyValue("display"), e.parentNode.removeChild(e), "none" == n && (n = "block"), c[t] = n), c[t]
        }

        function U(t){
            return "children" in t ? u.call(t.children) : r.map(t.childNodes, function (t){
                return 1 == t.nodeType ? t : void 0
            })
        }

        function X(t, e){
            var n, r = t ? t.length : 0;
            for (n = 0; r > n; n++) this[n] = t[n];
            this.length = r, this.selector = e || ""
        }

        function J(t, r, i){
            for (n in r) i && (Z(r[n]) || L(r[n])) ? (Z(r[n]) && !Z(t[n]) && (t[n] = {}), L(r[n]) && !L(t[n]) && (t[n] = []), J(t[n], r[n], i)) : r[n] !== e && (t[n] = r[n])
        }

        function W(t, e){
            return null == e ? r(t) : r(t).filter(e)
        }

        function Y(t, e, n, r){
            return F(e) ? e.call(t, n, r) : e
        }

        function G(t, e, n){
            null == n ? t.removeAttribute(e) : t.setAttribute(e, n)
        }

        function K(t, n){
            var r = t.className || "", i = r && r.baseVal !== e;
            return n === e ? i ? r.baseVal : r : void (i ? r.baseVal = n : t.className = n)
        }

        function Q(t){
            try {
                return t ? "true" == t || ("false" == t ? !1 : "null" == t ? null : +t + "" == t ? +t : /^[\[\{]/.test(t) ? r.parseJSON(t) : t) : t
            } catch (e) {
                return t
            }
        }

        function tt(t, e){
            e(t);
            for (var n = 0, r = t.childNodes.length; r > n; n++) tt(t.childNodes[n], e)
        }

        var e, n, r, i, O, P, o = [], s = o.concat, a = o.filter, u = o.slice, f = t.document, c = {}, l = {}, h = {
                "column-count": 1, columns: 1, "font-weight": 1, "line-height": 1, opacity: 1, "z-index": 1, zoom: 1
            }, p = /^\s*<(\w+|!)[^>]*>/, d = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
            m = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, g = /^(?:body|html)$/i,
            v = /([A-Z])/g, y = ["val", "css", "html", "text", "data", "width", "height", "offset"],
            x = ["after", "prepend", "before", "append"], b = f.createElement("table"), E = f.createElement("tr"), j = {
                tr: f.createElement("tbody"), tbody: b, thead: b, tfoot: b, td: E, th: E, "*": f.createElement("div")
            }, w = /complete|loaded|interactive/, T = /^[\w-]*$/, S = {}, C = S.toString, N = {},
            A = f.createElement("div"), D = {
                tabindex: "tabIndex", readonly: "readOnly", "for": "htmlFor", "class": "className", maxlength: "maxLength",
                cellspacing: "cellSpacing", cellpadding: "cellPadding", rowspan: "rowSpan", colspan: "colSpan",
                usemap: "useMap", frameborder: "frameBorder", contenteditable: "contentEditable"
            }, L = Array.isArray || function (t){
                return t instanceof Array
            };
        return N.matches = function (t, e){
            if(!e || !t || 1 !== t.nodeType) return !1;
            var n = t.matches || t.webkitMatchesSelector || t.mozMatchesSelector || t.oMatchesSelector || t.matchesSelector;
            if(n) return n.call(t, e);
            var r, i = t.parentNode, o = !i;
            return o && (i = A).appendChild(t), r = ~N.qsa(i, e).indexOf(t), o && A.removeChild(t), r
        }, O = function (t){
            return t.replace(/-+(.)?/g, function (t, e){
                return e ? e.toUpperCase() : ""
            })
        }, P = function (t){
            return a.call(t, function (e, n){
                return t.indexOf(e) == n
            })
        }, N.fragment = function (t, n, i){
            var o, s, a;
            return d.test(t) && (o = r(f.createElement(RegExp.$1))), o || (t.replace && (t = t.replace(m, "<$1></$2>")), n === e && (n = p.test(t) && RegExp.$1), n in j || (n = "*"), a = j[n], a.innerHTML = "" + t, o = r.each(u.call(a.childNodes), function (){
                a.removeChild(this)
            })), Z(i) && (s = r(o), r.each(i, function (t, e){
                y.indexOf(t) > -1 ? s[t](e) : s.attr(t, e)
            })), o
        }, N.Z = function (t, e){
            return new X(t, e)
        }, N.isZ = function (t){
            return t instanceof N.Z
        }, N.init = function (t, n){
            var i;
            if(!t) return N.Z();
            if("string" == typeof t) if(t = t.trim(), "<" == t[0] && p.test(t)) i = N.fragment(t, RegExp.$1, n), t = null; else {
                if(n !== e) return r(n).find(t);
                i = N.qsa(f, t)
            } else {
                if(F(t)) return r(f).ready(t);
                if(N.isZ(t)) return t;
                if(L(t)) i = q(t); else if(R(t)) i = [t], t = null; else if(p.test(t)) i = N.fragment(t.trim(), RegExp.$1, n), t = null; else {
                    if(n !== e) return r(n).find(t);
                    i = N.qsa(f, t)
                }
            }
            return N.Z(i, t)
        }, r = function (t, e){
            return N.init(t, e)
        }, r.extend = function (t){
            var e, n = u.call(arguments, 1);
            return "boolean" == typeof t && (e = t, t = n.shift()), n.forEach(function (n){
                J(t, n, e)
            }), t
        }, N.qsa = function (t, e){
            var n, r = "#" == e[0], i = !r && "." == e[0], o = r || i ? e.slice(1) : e, s = T.test(o);
            return t.getElementById && s && r ? (n = t.getElementById(o)) ? [n] : [] : 1 !== t.nodeType && 9 !== t.nodeType && 11 !== t.nodeType ? [] : u.call(s && !r && t.getElementsByClassName ? i ? t.getElementsByClassName(o) : t.getElementsByTagName(e) : t.querySelectorAll(e))
        }, r.contains = f.documentElement.contains ? function (t, e){
            return t !== e && t.contains(e)
        } : function (t, e){
            for (; e && (e = e.parentNode);) if(e === t) return !0;
            return !1
        }, r.type = $, r.isFunction = F, r.isWindow = k, r.isArray = L, r.isPlainObject = Z, r.isEmptyObject = function (t){
            var e;
            for (e in t) return !1;
            return !0
        }, r.isNumeric = function (t){
            var e = Number(t), n = typeof t;
            return null != t && "boolean" != n && ("string" != n || t.length) && !isNaN(e) && isFinite(e) || !1
        }, r.inArray = function (t, e, n){
            return o.indexOf.call(e, t, n)
        }, r.camelCase = O, r.trim = function (t){
            return null == t ? "" : String.prototype.trim.call(t)
        }, r.uuid = 0, r.support = {}, r.expr = {}, r.noop = function (){
        }, r.map = function (t, e){
            var n, i, o, r = [];
            if(z(t)) for (i = 0; i < t.length; i++) n = e(t[i], i), null != n && r.push(n); else for (o in t) n = e(t[o], o), null != n && r.push(n);
            return H(r)
        }, r.each = function (t, e){
            var n, r;
            if(z(t)){
                for (n = 0; n < t.length; n++) if(e.call(t[n], n, t[n]) === !1) return t
            } else for (r in t) if(e.call(t[r], r, t[r]) === !1) return t;
            return t
        }, r.grep = function (t, e){
            return a.call(t, e)
        }, t.JSON && (r.parseJSON = JSON.parse), r.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (t, e){
            S["[object " + e + "]"] = e.toLowerCase()
        }), r.fn = {
            constructor: N.Z, length: 0, forEach: o.forEach, reduce: o.reduce, push: o.push, sort: o.sort,
            splice: o.splice, indexOf: o.indexOf, concat: function (){
                var t, e, n = [];
                for (t = 0; t < arguments.length; t++) e = arguments[t], n[t] = N.isZ(e) ? e.toArray() : e;
                return s.apply(N.isZ(this) ? this.toArray() : this, n)
            }, map: function (t){
                return r(r.map(this, function (e, n){
                    return t.call(e, n, e)
                }))
            }, slice: function (){
                return r(u.apply(this, arguments))
            }, ready: function (t){
                return w.test(f.readyState) && f.body ? t(r) : f.addEventListener("DOMContentLoaded", function (){
                    t(r)
                }, !1), this
            }, get: function (t){
                return t === e ? u.call(this) : this[t >= 0 ? t : t + this.length]
            }, toArray: function (){
                return this.get()
            }, size: function (){
                return this.length
            }, remove: function (){
                return this.each(function (){
                    null != this.parentNode && this.parentNode.removeChild(this)
                })
            }, each: function (t){
                return o.every.call(this, function (e, n){
                    return t.call(e, n, e) !== !1
                }), this
            }, filter: function (t){
                return F(t) ? this.not(this.not(t)) : r(a.call(this, function (e){
                    return N.matches(e, t)
                }))
            }, add: function (t, e){
                return r(P(this.concat(r(t, e))))
            }, is: function (t){
                return this.length > 0 && N.matches(this[0], t)
            }, not: function (t){
                var n = [];
                if(F(t) && t.call !== e) this.each(function (e){
                    t.call(this, e) || n.push(this)
                }); else {
                    var i = "string" == typeof t ? this.filter(t) : z(t) && F(t.item) ? u.call(t) : r(t);
                    this.forEach(function (t){
                        i.indexOf(t) < 0 && n.push(t)
                    })
                }
                return r(n)
            }, has: function (t){
                return this.filter(function (){
                    return R(t) ? r.contains(this, t) : r(this).find(t).size()
                })
            }, eq: function (t){
                return -1 === t ? this.slice(t) : this.slice(t, +t + 1)
            }, first: function (){
                var t = this[0];
                return t && !R(t) ? t : r(t)
            }, last: function (){
                var t = this[this.length - 1];
                return t && !R(t) ? t : r(t)
            }, find: function (t){
                var e, n = this;
                return e = t ? "object" == typeof t ? r(t).filter(function (){
                    var t = this;
                    return o.some.call(n, function (e){
                        return r.contains(e, t)
                    })
                }) : 1 == this.length ? r(N.qsa(this[0], t)) : this.map(function (){
                    return N.qsa(this, t)
                }) : r()
            }, closest: function (t, e){
                var n = [], i = "object" == typeof t && r(t);
                return this.each(function (r, o){
                    for (; o && !(i ? i.indexOf(o) >= 0 : N.matches(o, t));) o = o !== e && !M(o) && o.parentNode;
                    o && n.indexOf(o) < 0 && n.push(o)
                }), r(n)
            }, parents: function (t){
                for (var e = [], n = this; n.length > 0;) n = r.map(n, function (t){
                    return (t = t.parentNode) && !M(t) && e.indexOf(t) < 0 ? (e.push(t), t) : void 0
                });
                return W(e, t)
            }, parent: function (t){
                return W(P(this.pluck("parentNode")), t)
            }, children: function (t){
                return W(this.map(function (){
                    return U(this)
                }), t)
            }, contents: function (){
                return this.map(function (){
                    return this.contentDocument || u.call(this.childNodes)
                })
            }, siblings: function (t){
                return W(this.map(function (t, e){
                    return a.call(U(e.parentNode), function (t){
                        return t !== e
                    })
                }), t)
            }, empty: function (){
                return this.each(function (){
                    this.innerHTML = ""
                })
            }, pluck: function (t){
                return r.map(this, function (e){
                    return e[t]
                })
            }, show: function (){
                return this.each(function (){
                    "none" == this.style.display && (this.style.display = ""), "none" == getComputedStyle(this, "").getPropertyValue("display") && (this.style.display = B(this.nodeName))
                })
            }, replaceWith: function (t){
                return this.before(t).remove()
            }, wrap: function (t){
                var e = F(t);
                if(this[0] && !e) var n = r(t).get(0), i = n.parentNode || this.length > 1;
                return this.each(function (o){
                    r(this).wrapAll(e ? t.call(this, o) : i ? n.cloneNode(!0) : n)
                })
            }, wrapAll: function (t){
                if(this[0]){
                    r(this[0]).before(t = r(t));
                    for (var e; (e = t.children()).length;) t = e.first();
                    r(t).append(this)
                }
                return this
            }, wrapInner: function (t){
                var e = F(t);
                return this.each(function (n){
                    var i = r(this), o = i.contents(), s = e ? t.call(this, n) : t;
                    o.length ? o.wrapAll(s) : i.append(s)
                })
            }, unwrap: function (){
                return this.parent().each(function (){
                    r(this).replaceWith(r(this).children())
                }), this
            }, clone: function (){
                return this.map(function (){
                    return this.cloneNode(!0)
                })
            }, hide: function (){
                return this.css("display", "none")
            }, toggle: function (t){
                return this.each(function (){
                    var n = r(this);
                    (t === e ? "none" == n.css("display") : t) ? n.show() : n.hide()
                })
            }, prev: function (t){
                return r(this.pluck("previousElementSibling")).filter(t || "*")
            }, next: function (t){
                return r(this.pluck("nextElementSibling")).filter(t || "*")
            }, html: function (t){
                return 0 in arguments ? this.each(function (e){
                    var n = this.innerHTML;
                    r(this).empty().append(Y(this, t, e, n))
                }) : 0 in this ? this[0].innerHTML : null
            }, text: function (t){
                return 0 in arguments ? this.each(function (e){
                    var n = Y(this, t, e, this.textContent);
                    this.textContent = null == n ? "" : "" + n
                }) : 0 in this ? this.pluck("textContent").join("") : null
            }, attr: function (t, r){
                var i;
                return "string" != typeof t || 1 in arguments ? this.each(function (e){
                    if(1 === this.nodeType) if(R(t)) for (n in t) G(this, n, t[n]); else G(this, t, Y(this, r, e, this.getAttribute(t)))
                }) : 0 in this && 1 == this[0].nodeType && null != (i = this[0].getAttribute(t)) ? i : e
            }, removeAttr: function (t){
                return this.each(function (){
                    1 === this.nodeType && t.split(" ").forEach(function (t){
                        G(this, t)
                    }, this)
                })
            }, prop: function (t, e){
                return t = D[t] || t, 1 in arguments ? this.each(function (n){
                    this[t] = Y(this, e, n, this[t])
                }) : this[0] && this[0][t]
            }, removeProp: function (t){
                return t = D[t] || t, this.each(function (){
                    delete this[t]
                })
            }, data: function (t, n){
                var r = "data-" + t.replace(v, "-$1").toLowerCase(),
                    i = 1 in arguments ? this.attr(r, n) : this.attr(r);
                return null !== i ? Q(i) : e
            }, val: function (t){
                return 0 in arguments ? (null == t && (t = ""), this.each(function (e){
                    this.value = Y(this, t, e, this.value)
                })) : this[0] && (this[0].multiple ? r(this[0]).find("option").filter(function (){
                    return this.selected
                }).pluck("value") : this[0].value)
            }, offset: function (e){
                if(e) return this.each(function (t){
                    var n = r(this), i = Y(this, e, t, n.offset()), o = n.offsetParent().offset(), s = {
                        top: i.top - o.top, left: i.left - o.left
                    };
                    "static" == n.css("position") && (s.position = "relative"), n.css(s)
                });
                if(!this.length) return null;
                if(f.documentElement !== this[0] && !r.contains(f.documentElement, this[0])) return {top: 0, left: 0};
                var n = this[0].getBoundingClientRect();
                return {
                    left: n.left + t.pageXOffset, top: n.top + t.pageYOffset, width: Math.round(n.width),
                    height: Math.round(n.height)
                }
            }, css: function (t, e){
                if(arguments.length < 2){
                    var i = this[0];
                    if("string" == typeof t){
                        if(!i) return;
                        return i.style[O(t)] || getComputedStyle(i, "").getPropertyValue(t)
                    }
                    if(L(t)){
                        if(!i) return;
                        var o = {}, s = getComputedStyle(i, "");
                        return r.each(t, function (t, e){
                            o[e] = i.style[O(e)] || s.getPropertyValue(e)
                        }), o
                    }
                }
                var a = "";
                if("string" == $(t)) e || 0 === e ? a = I(t) + ":" + _(t, e) : this.each(function (){
                    this.style.removeProperty(I(t))
                }); else for (n in t) t[n] || 0 === t[n] ? a += I(n) + ":" + _(n, t[n]) + ";" : this.each(function (){
                    this.style.removeProperty(I(n))
                });
                return this.each(function (){
                    this.style.cssText += ";" + a
                })
            }, index: function (t){
                return t ? this.indexOf(r(t)[0]) : this.parent().children().indexOf(this[0])
            }, hasClass: function (t){
                return t ? o.some.call(this, function (t){
                    return this.test(K(t))
                }, V(t)) : !1
            }, addClass: function (t){
                return t ? this.each(function (e){
                    if("className" in this){
                        i = [];
                        var n = K(this), o = Y(this, t, e, n);
                        o.split(/\s+/g).forEach(function (t){
                            r(this).hasClass(t) || i.push(t)
                        }, this), i.length && K(this, n + (n ? " " : "") + i.join(" "))
                    }
                }) : this
            }, removeClass: function (t){
                return this.each(function (n){
                    if("className" in this){
                        if(t === e) return K(this, "");
                        i = K(this), Y(this, t, n, i).split(/\s+/g).forEach(function (t){
                            i = i.replace(V(t), " ")
                        }), K(this, i.trim())
                    }
                })
            }, toggleClass: function (t, n){
                return t ? this.each(function (i){
                    var o = r(this), s = Y(this, t, i, K(this));
                    s.split(/\s+/g).forEach(function (t){
                        (n === e ? !o.hasClass(t) : n) ? o.addClass(t) : o.removeClass(t)
                    })
                }) : this
            }, scrollTop: function (t){
                if(this.length){
                    var n = "scrollTop" in this[0];
                    return t === e ? n ? this[0].scrollTop : this[0].pageYOffset : this.each(n ? function (){
                        this.scrollTop = t
                    } : function (){
                        this.scrollTo(this.scrollX, t)
                    })
                }
            }, scrollLeft: function (t){
                if(this.length){
                    var n = "scrollLeft" in this[0];
                    return t === e ? n ? this[0].scrollLeft : this[0].pageXOffset : this.each(n ? function (){
                        this.scrollLeft = t
                    } : function (){
                        this.scrollTo(t, this.scrollY)
                    })
                }
            }, position: function (){
                if(this.length){
                    var t = this[0], e = this.offsetParent(), n = this.offset(), i = g.test(e[0].nodeName) ? {
                        top: 0, left: 0
                    } : e.offset();
                    return n.top -= parseFloat(r(t).css("margin-top")) || 0, n.left -= parseFloat(r(t).css("margin-left")) || 0, i.top += parseFloat(r(e[0]).css("border-top-width")) || 0, i.left += parseFloat(r(e[0]).css("border-left-width")) || 0, {
                        top: n.top - i.top, left: n.left - i.left
                    }
                }
            }, offsetParent: function (){
                return this.map(function (){
                    for (var t = this.offsetParent || f.body; t && !g.test(t.nodeName) && "static" == r(t).css("position");) t = t.offsetParent;
                    return t
                })
            }
        }, r.fn.detach = r.fn.remove, ["width", "height"].forEach(function (t){
            var n = t.replace(/./, function (t){
                return t[0].toUpperCase()
            });
            r.fn[t] = function (i){
                var o, s = this[0];
                return i === e ? k(s) ? s["inner" + n] : M(s) ? s.documentElement["scroll" + n] : (o = this.offset()) && o[t] : this.each(function (e){
                    s = r(this), s.css(t, Y(this, i, e, s[t]()))
                })
            }
        }), x.forEach(function (n, i){
            var o = i % 2;
            r.fn[n] = function (){
                var n, a, s = r.map(arguments, function (t){
                    var i = [];
                    return n = $(t), "array" == n ? (t.forEach(function (t){
                        return t.nodeType !== e ? i.push(t) : r.zepto.isZ(t) ? i = i.concat(t.get()) : void (i = i.concat(N.fragment(t)))
                    }), i) : "object" == n || null == t ? t : N.fragment(t)
                }), u = this.length > 1;
                return s.length < 1 ? this : this.each(function (e, n){
                    a = o ? n : n.parentNode, n = 0 == i ? n.nextSibling : 1 == i ? n.firstChild : 2 == i ? n : null;
                    var c = r.contains(f.documentElement, a);
                    s.forEach(function (e){
                        if(u) e = e.cloneNode(!0); else if(!a) return r(e).remove();
                        a.insertBefore(e, n), c && tt(e, function (e){
                            if(!(null == e.nodeName || "SCRIPT" !== e.nodeName.toUpperCase() || e.type && "text/javascript" !== e.type || e.src)){
                                var n = e.ownerDocument ? e.ownerDocument.defaultView : t;
                                n.eval.call(n, e.innerHTML)
                            }
                        })
                    })
                })
            }, r.fn[o ? n + "To" : "insert" + (i ? "Before" : "After")] = function (t){
                return r(t)[n](this), this
            }
        }), N.Z.prototype = X.prototype = r.fn, N.uniq = P, N.deserializeValue = Q, r.zepto = N, r
    }();
    return t.Zepto = e, void 0 === t.$ && (t.$ = e), function (e){
        function h(t){
            return t._zid || (t._zid = n++)
        }

        function p(t, e, n, r){
            if(e = d(e), e.ns) var i = m(e.ns);
            return (a[h(t)] || []).filter(function (t){
                return t && (!e.e || t.e == e.e) && (!e.ns || i.test(t.ns)) && (!n || h(t.fn) === h(n)) && (!r || t.sel == r)
            })
        }

        function d(t){
            var e = ("" + t).split(".");
            return {e: e[0], ns: e.slice(1).sort().join(" ")}
        }

        function m(t){
            return new RegExp("(?:^| )" + t.replace(" ", " .* ?") + "(?: |$)")
        }

        function g(t, e){
            return t.del && !f && t.e in c || !!e
        }

        function v(t){
            return l[t] || f && c[t] || t
        }

        function y(t, n, i, o, s, u, f){
            var c = h(t), p = a[c] || (a[c] = []);
            n.split(/\s/).forEach(function (n){
                if("ready" == n) return e(document).ready(i);
                var a = d(n);
                a.fn = i, a.sel = s, a.e in l && (i = function (t){
                    var n = t.relatedTarget;
                    return !n || n !== this && !e.contains(this, n) ? a.fn.apply(this, arguments) : void 0
                }), a.del = u;
                var c = u || i;
                a.proxy = function (e){
                    if(e = T(e), !e.isImmediatePropagationStopped()){
                        e.data = o;
                        var n = c.apply(t, e._args == r ? [e] : [e].concat(e._args));
                        return n === !1 && (e.preventDefault(), e.stopPropagation()), n
                    }
                }, a.i = p.length, p.push(a), "addEventListener" in t && t.addEventListener(v(a.e), a.proxy, g(a, f))
            })
        }

        function x(t, e, n, r, i){
            var o = h(t);
            (e || "").split(/\s/).forEach(function (e){
                p(t, e, n, r).forEach(function (e){
                    delete a[o][e.i], "removeEventListener" in t && t.removeEventListener(v(e.e), e.proxy, g(e, i))
                })
            })
        }

        function T(t, n){
            return (n || !t.isDefaultPrevented) && (n || (n = t), e.each(w, function (e, r){
                var i = n[e];
                t[e] = function (){
                    return this[r] = b, i && i.apply(n, arguments)
                }, t[r] = E
            }), t.timeStamp || (t.timeStamp = Date.now()), (n.defaultPrevented !== r ? n.defaultPrevented : "returnValue" in n ? n.returnValue === !1 : n.getPreventDefault && n.getPreventDefault()) && (t.isDefaultPrevented = b)), t
        }

        function S(t){
            var e, n = {originalEvent: t};
            for (e in t) j.test(e) || t[e] === r || (n[e] = t[e]);
            return T(n, t)
        }

        var r, n = 1, i = Array.prototype.slice, o = e.isFunction, s = function (t){
            return "string" == typeof t
        }, a = {}, u = {}, f = "onfocusin" in t, c = {focus: "focusin", blur: "focusout"}, l = {
            mouseenter: "mouseover", mouseleave: "mouseout"
        };
        u.click = u.mousedown = u.mouseup = u.mousemove = "MouseEvents", e.event = {
            add: y, remove: x
        }, e.proxy = function (t, n){
            var r = 2 in arguments && i.call(arguments, 2);
            if(o(t)){
                var a = function (){
                    return t.apply(n, r ? r.concat(i.call(arguments)) : arguments)
                };
                return a._zid = h(t), a
            }
            if(s(n)) return r ? (r.unshift(t[n], t), e.proxy.apply(null, r)) : e.proxy(t[n], t);
            throw new TypeError("expected function")
        }, e.fn.bind = function (t, e, n){
            return this.on(t, e, n)
        }, e.fn.unbind = function (t, e){
            return this.off(t, e)
        }, e.fn.one = function (t, e, n, r){
            return this.on(t, e, n, r, 1)
        };
        var b = function (){
            return !0
        }, E = function (){
            return !1
        }, j = /^([A-Z]|returnValue$|layer[XY]$|webkitMovement[XY]$)/, w = {
            preventDefault: "isDefaultPrevented", stopImmediatePropagation: "isImmediatePropagationStopped",
            stopPropagation: "isPropagationStopped"
        };
        e.fn.delegate = function (t, e, n){
            return this.on(e, t, n)
        }, e.fn.undelegate = function (t, e, n){
            return this.off(e, t, n)
        }, e.fn.live = function (t, n){
            return e(document.body).delegate(this.selector, t, n), this
        }, e.fn.die = function (t, n){
            return e(document.body).undelegate(this.selector, t, n), this
        }, e.fn.on = function (t, n, a, u, f){
            var c, l, h = this;
            return t && !s(t) ? (e.each(t, function (t, e){
                h.on(t, n, a, e, f)
            }), h) : (s(n) || o(u) || u === !1 || (u = a, a = n, n = r), (u === r || a === !1) && (u = a, a = r), u === !1 && (u = E), h.each(function (r, o){
                f && (c = function (t){
                    return x(o, t.type, u), u.apply(this, arguments)
                }), n && (l = function (t){
                    var r, s = e(t.target).closest(n, o).get(0);
                    return s && s !== o ? (r = e.extend(S(t), {
                        currentTarget: s, liveFired: o
                    }), (c || u).apply(s, [r].concat(i.call(arguments, 1)))) : void 0
                }), y(o, t, u, a, n, l || c)
            }))
        }, e.fn.off = function (t, n, i){
            var a = this;
            return t && !s(t) ? (e.each(t, function (t, e){
                a.off(t, n, e)
            }), a) : (s(n) || o(i) || i === !1 || (i = n, n = r), i === !1 && (i = E), a.each(function (){
                x(this, t, i, n)
            }))
        }, e.fn.trigger = function (t, n){
            return t = s(t) || e.isPlainObject(t) ? e.Event(t) : T(t), t._args = n, this.each(function (){
                t.type in c && "function" == typeof this[t.type] ? this[t.type]() : "dispatchEvent" in this ? this.dispatchEvent(t) : e(this).triggerHandler(t, n)
            })
        }, e.fn.triggerHandler = function (t, n){
            var r, i;
            return this.each(function (o, a){
                r = S(s(t) ? e.Event(t) : t), r._args = n, r.target = a, e.each(p(a, t.type || t), function (t, e){
                    return i = e.proxy(r), r.isImmediatePropagationStopped() ? !1 : void 0
                })
            }), i
        }, "focusin focusout focus blur load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select keydown keypress keyup error".split(" ").forEach(function (t){
            e.fn[t] = function (e){
                return 0 in arguments ? this.bind(t, e) : this.trigger(t)
            }
        }), e.Event = function (t, e){
            s(t) || (e = t, t = e.type);
            var n = document.createEvent(u[t] || "Events"), r = !0;
            if(e) for (var i in e) "bubbles" == i ? r = !!e[i] : n[i] = e[i];
            return n.initEvent(t, r, !0), T(n)
        }
    }(e), function (e){
        function p(t, n, r){
            var i = e.Event(n);
            return e(t).trigger(i, r), !i.isDefaultPrevented()
        }

        function d(t, e, n, i){
            return t.global ? p(e || r, n, i) : void 0
        }

        function m(t){
            t.global && 0 === e.active++ && d(t, null, "ajaxStart")
        }

        function g(t){
            t.global && !--e.active && d(t, null, "ajaxStop")
        }

        function v(t, e){
            var n = e.context;
            return e.beforeSend.call(n, t, e) === !1 || d(e, n, "ajaxBeforeSend", [t, e]) === !1 ? !1 : void d(e, n, "ajaxSend", [t, e])
        }

        function y(t, e, n, r){
            var i = n.context, o = "success";
            n.success.call(i, t, o, e), r && r.resolveWith(i, [t, o, e]), d(n, i, "ajaxSuccess", [e, n, t]), b(o, e, n)
        }

        function x(t, e, n, r, i){
            var o = r.context;
            r.error.call(o, n, e, t), i && i.rejectWith(o, [n, e, t]), d(r, o, "ajaxError", [n, r, t || e]), b(e, n, r)
        }

        function b(t, e, n){
            var r = n.context;
            n.complete.call(r, e, t), d(n, r, "ajaxComplete", [e, n]), g(n)
        }

        function E(t, e, n){
            if(n.dataFilter == j) return t;
            var r = n.context;
            return n.dataFilter.call(r, t, e)
        }

        function j(){
        }

        function w(t){
            return t && (t = t.split(";", 2)[0]), t && (t == c ? "html" : t == f ? "json" : a.test(t) ? "script" : u.test(t) && "xml") || "text"
        }

        function T(t, e){
            return "" == e ? t : (t + "&" + e).replace(/[&?]{1,2}/, "?")
        }

        function S(t){
            t.processData && t.data && "string" != e.type(t.data) && (t.data = e.param(t.data, t.traditional)), !t.data || t.type && "GET" != t.type.toUpperCase() && "jsonp" != t.dataType || (t.url = T(t.url, t.data), t.data = void 0)
        }

        function C(t, n, r, i){
            return e.isFunction(n) && (i = r, r = n, n = void 0), e.isFunction(r) || (i = r, r = void 0), {
                url: t, data: n, success: r, dataType: i
            }
        }

        function O(t, n, r, i){
            var o, s = e.isArray(n), a = e.isPlainObject(n);
            e.each(n, function (n, u){
                o = e.type(u), i && (n = r ? i : i + "[" + (a || "object" == o || "array" == o ? n : "") + "]"), !i && s ? t.add(u.name, u.value) : "array" == o || !r && "object" == o ? O(t, u, r, n) : t.add(n, u)
            })
        }

        var i, o, n = +new Date, r = t.document, s = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
            a = /^(?:text|application)\/javascript/i, u = /^(?:text|application)\/xml/i, f = "application/json",
            c = "text/html", l = /^\s*$/, h = r.createElement("a");
        h.href = t.location.href, e.active = 0, e.ajaxJSONP = function (i, o){
            if(!("type" in i)) return e.ajax(i);
            var c, p, s = i.jsonpCallback, a = (e.isFunction(s) ? s() : s) || "Zepto" + n++,
                u = r.createElement("script"), f = t[a], l = function (t){
                    e(u).triggerHandler("error", t || "abort")
                }, h = {abort: l};
            return o && o.promise(h), e(u).on("load error", function (n, r){
                clearTimeout(p), e(u).off().remove(), "error" != n.type && c ? y(c[0], h, i, o) : x(null, r || "error", h, i, o), t[a] = f, c && e.isFunction(f) && f(c[0]), f = c = void 0
            }), v(h, i) === !1 ? (l("abort"), h) : (t[a] = function (){
                c = arguments
            }, u.src = i.url.replace(/\?(.+)=\?/, "?$1=" + a), r.head.appendChild(u), i.timeout > 0 && (p = setTimeout(function (){
                l("timeout")
            }, i.timeout)), h)
        }, e.ajaxSettings = {
            type: "GET", beforeSend: j, success: j, error: j, complete: j, context: null, global: !0, xhr: function (){
                return new t.XMLHttpRequest
            }, accepts: {
                script: "text/javascript, application/javascript, application/x-javascript", json: f,
                xml: "application/xml, text/xml", html: c, text: "text/plain"
            }, crossDomain: !1, timeout: 0, processData: !0, cache: !0, dataFilter: j
        }, e.ajax = function (n){
            var u, f, s = e.extend({}, n || {}), a = e.Deferred && e.Deferred();
            for (i in e.ajaxSettings) void 0 === s[i] && (s[i] = e.ajaxSettings[i]);
            m(s), s.crossDomain || (u = r.createElement("a"), u.href = s.url, u.href = u.href, s.crossDomain = h.protocol + "//" + h.host != u.protocol + "//" + u.host), s.url || (s.url = t.location.toString()), (f = s.url.indexOf("#")) > -1 && (s.url = s.url.slice(0, f)), S(s);
            var c = s.dataType, p = /\?.+=\?/.test(s.url);
            if(p && (c = "jsonp"), s.cache !== !1 && (n && n.cache === !0 || "script" != c && "jsonp" != c) || (s.url = T(s.url, "_=" + Date.now())), "jsonp" == c) return p || (s.url = T(s.url, s.jsonp ? s.jsonp + "=?" : s.jsonp === !1 ? "" : "callback=?")), e.ajaxJSONP(s, a);
            var P, d = s.accepts[c], g = {}, b = function (t, e){
                g[t.toLowerCase()] = [t, e]
            }, C = /^([\w-]+:)\/\//.test(s.url) ? RegExp.$1 : t.location.protocol, N = s.xhr(), O = N.setRequestHeader;
            if(a && a.promise(N), s.crossDomain || b("X-Requested-With", "XMLHttpRequest"), b("Accept", d || "*/*"), (d = s.mimeType || d) && (d.indexOf(",") > -1 && (d = d.split(",", 2)[0]), N.overrideMimeType && N.overrideMimeType(d)), (s.contentType || s.contentType !== !1 && s.data && "GET" != s.type.toUpperCase()) && b("Content-Type", s.contentType || "application/x-www-form-urlencoded"), s.headers) for (o in s.headers) b(o, s.headers[o]);
            if(N.setRequestHeader = b, N.onreadystatechange = function (){
                if(4 == N.readyState){
                    N.onreadystatechange = j, clearTimeout(P);
                    var t, n = !1;
                    if(N.status >= 200 && N.status < 300 || 304 == N.status || 0 == N.status && "file:" == C){
                        if(c = c || w(s.mimeType || N.getResponseHeader("content-type")), "arraybuffer" == N.responseType || "blob" == N.responseType) t = N.response; else {
                            t = N.responseText;
                            try {
                                t = E(t, c, s), "script" == c ? (1, eval)(t) : "xml" == c ? t = N.responseXML : "json" == c && (t = l.test(t) ? null : e.parseJSON(t))
                            } catch (r) {
                                n = r
                            }
                            if(n) return x(n, "parsererror", N, s, a)
                        }
                        y(t, N, s, a)
                    } else x(N.statusText || null, N.status ? "error" : "abort", N, s, a)
                }
            }, v(N, s) === !1) return N.abort(), x(null, "abort", N, s, a), N;
            var A = "async" in s ? s.async : !0;
            if(N.open(s.type, s.url, A, s.username, s.password), s.xhrFields) for (o in s.xhrFields) N[o] = s.xhrFields[o];
            for (o in g) O.apply(N, g[o]);
            return s.timeout > 0 && (P = setTimeout(function (){
                N.onreadystatechange = j, N.abort(), x(null, "timeout", N, s, a)
            }, s.timeout)), N.send(s.data ? s.data : null), N
        }, e.get = function (){
            return e.ajax(C.apply(null, arguments))
        }, e.post = function (){
            var t = C.apply(null, arguments);
            return t.type = "POST", e.ajax(t)
        }, e.getJSON = function (){
            var t = C.apply(null, arguments);
            return t.dataType = "json", e.ajax(t)
        }, e.fn.load = function (t, n, r){
            if(!this.length) return this;
            var a, i = this, o = t.split(/\s/), u = C(t, n, r), f = u.success;
            return o.length > 1 && (u.url = o[0], a = o[1]), u.success = function (t){
                i.html(a ? e("<div>").html(t.replace(s, "")).find(a) : t), f && f.apply(i, arguments)
            }, e.ajax(u), this
        };
        var N = encodeURIComponent;
        e.param = function (t, n){
            var r = [];
            return r.add = function (t, n){
                e.isFunction(n) && (n = n()), null == n && (n = ""), this.push(N(t) + "=" + N(n))
            }, O(r, t, n), r.join("&").replace(/%20/g, "+")
        }
    }(e), function (t){
        t.fn.serializeArray = function (){
            var e, n, r = [], i = function (t){
                return t.forEach ? t.forEach(i) : void r.push({name: e, value: t})
            };
            return this[0] && t.each(this[0].elements, function (r, o){
                n = o.type, e = o.name, e && "fieldset" != o.nodeName.toLowerCase() && !o.disabled && "submit" != n && "reset" != n && "button" != n && "file" != n && ("radio" != n && "checkbox" != n || o.checked) && i(t(o).val())
            }), r
        }, t.fn.serialize = function (){
            var t = [];
            return this.serializeArray().forEach(function (e){
                t.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value))
            }), t.join("&")
        }, t.fn.submit = function (e){
            if(0 in arguments) this.bind("submit", e); else if(this.length){
                var n = t.Event("submit");
                this.eq(0).trigger(n), n.isDefaultPrevented() || this.get(0).submit()
            }
            return this
        }
    }(e), function (){
        try {
            getComputedStyle(void 0)
        } catch (e) {
            var n = getComputedStyle;
            t.getComputedStyle = function (t, e){
                try {
                    return n(t, e)
                } catch (r) {
                    return null
                }
            }
        }
    }(), e
});
var gs = GS = window.GS = (function (undefined){
    var self = this,
        S,
        guid = 0,
        EMPTY = '',
        loggerLevel = {
            debug: 10, // 调试
            info: 20,  // 信息
            warn: 30,  // 警告
            error: 40  // 错误
        };

    function getLogger(logger){
        var obj = {};
        for (var cat in loggerLevel) {
            if(!loggerLevel.hasOwnProperty(cat))
                continue;
            (function (obj, cat){
                obj[cat] = function (msg){
                    return S.log(msg, cat, logger);
                };
            })(obj, cat);
        }
        return obj;
    }

    // 调用： var logger = S.getLogger('js/base/utils.js');
//    if (!$.fn.zTree) {
//        logger.error('没有引用zTree相关js文件！');
//        return false;
//    }

    S = {
        __BUILD_TIME: '2014-10-04',
        Env: {
            host: self
        },
        Config: {
            debug: true,
            loggerLevel: 'debug',
            fns: {}
        },
        Version: '0.2.2',
        /**
         * 类型判断
         * @param obj
         * @param type
         * @return boolean
         */
        is: function (obj, type){
            var isNan = {"NaN": 1, "Infinity": 1, "-Infinity": 1};
            type = type.toLowerCase();
            if(type == "finite"){
                return !isNan["hasOwnProperty"](+obj);
            }
            if(type == "array"){
                return obj instanceof Array;
            }
            if(undefined === obj && type !== "undefined") return false;
            return (type == "null" && obj === null) ||
                (type == typeof obj && obj !== null) ||
                (type == "object" && obj === Object(obj)) ||
                (type == "array" && Array.isArray && Array.isArray(obj)) ||
                Object.prototype.toString.call(obj).slice(8, -1).toLowerCase() == type;
        },
        /**
         * 布尔类型判断
         * @param obj
         * @returns {boolean|*|Boolean}
         */
        isBoolean: function (obj){
            return S.is(obj, "boolean");
        },
        /**
         * 日期类型判断
         * @param obj
         * @returns {boolean|*|Boolean}
         */
        isDate: function (obj){
            return S.is(obj, "date");
        },
        /**
         * 是否是正则表达式判断
         * @param obj
         * @returns {*|boolean}
         */
        isRegExp: function (obj){
            return S.is(obj, "regexp");
        },
        /**
         * 对象类型判断
         * @param obj
         * @returns {*|boolean}
         */
        isObject: function (obj){
            return S.is(obj, "object");
        },
        /**
         * 数组类型判断
         * @param obj
         * @returns {*|boolean}
         */
        isArray: function (obj){
            return S.is(obj, "array");
        },
        /**
         * 数字类型判断
         * @param obj
         * @returns {*|boolean}
         */
        isNumber: function (obj){
            return S.is(obj, "number");
        },
        /**
         * fun 类型判断
         * @param obj
         * @returns {*|boolean}
         */
        isFunction: function (obj){
            return S.is(obj, "function");
        },
        /**
         * null类型判断
         * @param obj
         * @returns {*|boolean}
         */
        isNull: function (obj){
            return S.is(obj, "null");
        },
        /**
         * 字符串类型判断
         * @param obj
         * @returns {*|boolean}
         */
        isString: function (obj){
            return S.is(obj, "string");
        },
        /**
         * 对象是否为空判断
         * @param obj
         * @returns {boolean|*}
         */
        isEmpty: function (obj){
            return EMPTY === obj || S.isNull(obj);
        },
        /**
         * undefined 类型判断
         * @param obj
         * @returns {*|boolean}
         */
        isUndefined: function (obj){
            return S.is(obj, "undefined");
        },
        /**
         * 配置
         * @param configName
         * @param configValue
         */
        config: function (configName, configValue){
            var cfg,
                r,
                self = this,
                Config = S.Config,
                configFns = Config.fns;
            if(S.isObject(configName)){
                for (var c in configName) {
                    if(configName.hasOwnProperty(c))
                        Config[c] = configName[c];
                }
            } else {
                cfg = configFns[configName];
                if(S.isUndefined(configValue)){
                    if(cfg){
                        r = cfg.call(self);
                    } else {
                        r = Config[configName];
                    }
                } else {
                    if(cfg){
                        r = cfg.call(self, configValue);
                    } else {
                        Config[configName] = configValue;
                    }
                }
            }
            return r;
        },
        /**
         * 打印console.log();
         * @param msg
         * @param cat
         * @param logger
         * @returns {*}
         */
        log: function (msg, cat, logger){
            if(!S.Config.debug) return undefined;
            if((loggerLevel[S.Config.loggerLevel] || 1000) > loggerLevel[cat == 'log' ? 'debug' : cat])
                return "min level";
            var matched = false;
            if(logger){
                matched = S.isObject(msg);
                if(!matched)
                    msg = logger + ": " + msg;
            }
            if(typeof console !== 'undefined' && console.log){
                if(matched) console[cat && console[cat] ? cat : 'log'](logger + ":");
                console[cat && console[cat] ? cat : 'log'](msg);
                return msg;
            }
        },
        getLogger: function (logger){
            return getLogger(logger);
        },
        guid: function (pre){
            return (pre || '') + guid++;
        },
        _mix: function (target, resource){
            for (var name in resource) {
                // isPrototypeOf() 方法测试一个对象是否存在于另一个对象的原型链上。
                // hasOwnProperty用于判断一个对象上是否包含自定义属性，而不是原型链上的属性
                if(resource.hasOwnProperty(name))
                    target[name] = resource[name];
            }
        }
    };
    S.Logger = {};
    /*日志记录级别*/
    S.Logger.Level = {
        DEBUG: 'debug', // 调试
        INFO: 'info',   // 信息
        WARN: 'warn',   // 警告
        ERROR: 'error'  // 错误
    };
    return S;
})();
/**
 * Array Model
 */
(function (S, undefined){
    var AP = Array.prototype,
        indexOf = AP.indexOf,
        lastIndexOf = AP.lastIndexOf,
        UF = undefined,
        FALSE = false;
    S._mix(S, {
        /**
         * 循环操作
         * @param object
         * @param fn
         * @param context @returns {*}
         */
        each: function (object, fn, context){
            if(object){
                var key,
                    val,
                    keys,
                    i = 0,
                    length = object && object.length,
                    isObj = S.isUndefined(length) || S.isFunction(object);

                context = context || null;

                if(isObj){
                    keys = S.keys(object);
                    for (; i < keys.length; i++) {
                        key = keys[i];
                        if(fn.call(context, object[key], key, object) === FALSE){
                            break;
                        }
                    }
                } else {
                    for (val = object[0];
                         i < length; val = object[++i]) {
                        if(fn.call(context, val, i, object) === FALSE){
                            break;
                        }
                    }
                }
            }
            return object;
        },
        /**
         * 检查指定的参数在数组中的位置  不存在 返回-1
         * @param item
         * @param arr
         * @param fromIndex
         * @returns {*}
         */
        index: function (item, arr, fromIndex){
            if(indexOf){
                return fromIndex === UF ?
                    indexOf.call(arr, item) :
                    indexOf.call(arr, item, fromIndex);
            }
            var i = fromIndex || 0;
            for (; i < arr.length; i++) {
                if(arr[i] === item)
                    break;
            }
            return i;
        },
        /**
         * 与 S.index类似 arr倒序计算位置
         * @param item
         * @param arr
         * @param fromIndex
         * @returns {*}
         */
        lastIndex: function (item, arr, fromIndex){
            if(lastIndexOf){
                return fromIndex === UF ?
                    lastIndexOf.call(arr, item) :
                    lastIndexOf.call(arr, item, fromIndex);
            }
            if(fromIndex === UF){
                fromIndex = arr.length - 1;
            }
            var i = fromIndex;
            for (; i >= 0; i--) {
                if(arr[i] === item)
                    break;
            }
            return i;
        },
        /**
         * 检查是否包含在数组中
         * @param item
         * @param arr
         * @returns {boolean}
         */
        inArray: function (item, arr){
            return S.index(item, arr) >= 0;
        }
    });
})(GS);
/**
 * Date Model
 */
(function (S){
    var AP = Date.prototype;
    AP.addDays = AP.addDays || function (days){
        this.setDate(this.getDate() + days);
        return this;
    };
    var weeks = ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
    AP.format = AP.format || function (strFormat){
        if(strFormat === 'soon' || strFormat === 'week'){
            var left = this.left();
            console.log(left);
            if(left.dd < 5){
                var str = '';
                var dd = S.now().getDate() - this.getDate();
                if(left.dd == 0 && dd != 0){
                    left.status = dd < 0;
                    left.dd = 1;
                }
                if(left.dd > 0){
                    if(left.dd == 1)
                        return (left.status ? "明天" : "昨天") + this.format(' hh:mm');
                    if(strFormat == 'week'){
                        return weeks[this.getDay()];
                    } else {
                        str = left.dd + '天';
                    }
                } else if(left.hh > 0){
                    str = left.hh + '小时';
                } else if(left.mm > 0){
                    str = left.mm + '分钟';
                } else if(left.ss > 10){
                    str = left.ss + '秒';
                } else {
                    return '刚刚';
                }
                return str + (left.status ? '后' : '前');
            }
            strFormat = 'yyyy-MM-dd';
        }
        if(strFormat === "date")
            return this;
        var o = {
            "M+": this.getMonth() + 1,
            "d+": this.getDate(),
            "h+": this.getHours(),
            "m+": this.getMinutes(),
            "s+": this.getSeconds(),
            "q+": Math.floor((this.getMonth() + 3) / 3), //季度
            "S": this.getMilliseconds() //毫秒
        };
        if(/(y+)/.test(strFormat))
            strFormat = strFormat.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o) {
            if(new RegExp("(" + k + ")").test(strFormat)){
                strFormat =
                    strFormat.replace(RegExp.$1, (RegExp.$1.length == 1) ?
                        (o[k]) :
                        (("00" + o[k]).substr(("" + o[k]).length)));
            }
        }
        return strFormat;
    };
    AP.left = function (){
        var arr = {status: true};
        var nDifference = this - (new Date());
        if(nDifference < 0){
            arr.status = false;
            nDifference = Math.abs(nDifference);
        }
        console.log(nDifference);
        var iDays = nDifference / (1000 * 60 * 60 * 24);
        arr.dd = iDays > 1 ? parseInt(iDays) : 0;
        var temp = iDays - arr.dd;
        var hh = temp * 24;
        arr.hh = hh > 1 ? parseInt(hh) : 0;
        temp = temp * 24 - arr.hh;
        hh = temp * 60;
        arr.mm = hh > 1 ? parseInt(hh) : 0;
        temp = temp * 60 - arr.mm;
        hh = temp * 60;
        arr.ss = hh > 1 ? parseInt(hh) : 0;
        temp = temp * 60 - arr.ss;
        hh = temp * 1000;
        arr.ms = hh > 1 ? parseInt(hh) : 0;
        return arr;
    };
    S._mix(S, {
        /**
         * 当前时间戳
         */
        nowTick: Date.now || function (){
            return +new Date();
        },
        /**
         * 现在的时间
         * @returns {Date}
         */
        now: function (){
            return new Date(S.nowTick());
        },
        /**
         * 添加天数
         * @param date
         * @param days
         * @returns {*}
         */
        addDays: function (date, days){
            if(!S.isDate(date)) return S.now();
            days = (S.isNumber(days) ? days : 0);
            return new Date(date.addDays(days));
        },
        /**
         * 格式化时间
         * @param date
         * @param strFormat
         * @returns {*}
         */
        formatDate: function (date, strFormat){
            if(S.isString(date)) return date;
            strFormat = strFormat || "yyyy-MM-dd"; //yyyy.MM.dd hh:mm:ss q
            return (new Date(date)).format(strFormat);
            // 对Date的扩展，将 Date 转化为指定格式的String
            // 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
            // 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
            // 例子：
            // (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
            // (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18   4/16/2017 10:53:00
        },
        /**
         * 计算剩余时间
         * @param date
         * @returns {*}
         */
        leftTime: function (date){
            return date.left();
        }
    });
})(GS);
/**
 * Function Model
 * @author BAI
 * @date 2015/4/7.
 */
(function (S){
    S._mix(S, {
        later: function (method, time, isInterval, context, data){
            var timer,
                f;
            time = time || 0;
            if(S.isString(method))
                method = context[method];
            if(!method){
                S.error("fn is undefined");
            }
            f = function (){
                method.apply(context, data);
            };
            timer = (isInterval ? setInterval(f, time) : setTimeout(f, time));
            return {
                timer: timer,
                isInterval: isInterval,
                cancel: function (){
                    if(this.isInterval){
                        clearInterval(timer);
                    } else {
                        clearTimeout(timer);
                    }
                }
            };
        }
    });
})(GS);
/**
 * Json 序列化
 */
(function (S){
    S.json = function (json){
        if(S.isEmpty(json)) return json;
        if(S.isObject(json)){
            if(Date === json.constructor){
                return "'new Date(" + json.valueOf() + ")'";
            }
            var fmt = function (s){
                if(S.isObject(s) && s != null) return S.json(s);
                return (S.isString(s) || S.isNumber(s)) ? "'" + s + "'" : s;
            };
            var arr = [],
                arrItem;
            if(S.isArray(json)){
                S.each(json, function (jsonItem){
                    if(S.isNumber(jsonItem))
                        arr.push(jsonItem);
                    else if(S.isString(jsonItem))
                        arr.push("'" + jsonItem + "'");
                    else if(S.isArray(jsonItem)){
                        arr.push(S.json(jsonItem));
                    } else {
                        arrItem = [];
                        S.each(S.keys(jsonItem), function (key){
                            arrItem.push("'" + key + "':" + fmt(jsonItem[key]));
                        });
                        arr.push('{' + arrItem.join(',') + '}');
                    }
                });
                return '[' + arr.join(',') + ']';
            } else {
                S.each(S.keys(json), function (key){
                    arr.push("'" + key + "':" + fmt(json[key]));
                });
                return '{' + arr.join(',') + '}';
            }
        } else if(S.isString(json)){
            json = json.replace(/'(new Date\(\d+\))'/gi, "$1");
            return eval("(" + json + ")");
        }
    };
})(GS);
/**
 * Object Model
 */
(function (S){
    var MIX_CIRCULAR_DETECTION = '__MIX_CIRCULAR',
        hasEnumBug = !({toString: 1}.propertyIsEnumerable('toString')),
        enumProperties = [
            'constructor',
            'hasOwnProperty',
            'isPrototypeOf',
            'propertyIsEnumerable',
            'toString',
            'toLocaleString',
            'valueOf'
        ];
    S._mix(S, {
        /**
         * 获取对象属性名
         */
        keys: Object.keys || function (o){
            var result = [], p, i;

            for (p in o) {
                if(o.hasOwnProperty(p)){
                    result.push(p);
                }
            }
            if(hasEnumBug){
                for (i = enumProperties.length - 1; i >= 0; i--) {
                    p = enumProperties[i];
                    if(o.hasOwnProperty(p)){
                        result.push(p);
                    }
                }
            }
            return result;
        },
        /**
         * 扩展
         * @param target    当前对象
         * @param resource  资源对象
         * @param overwrite 是否重写
         * @param whiteList 白名单
         * @param deep      是否深度复制
         */
        mix: function (target, resource, overwrite, whiteList, deep){
            if(overwrite && S.isObject(overwrite)){
                whiteList = overwrite["whiteList"];
                deep = overwrite["deep"];
                overwrite = overwrite["overwrite"];
            }
            if(whiteList && !S.isFunction(whiteList)){
                var originalWl = whiteList;
                whiteList = function (name, val){
                    return S.inArray(name, originalWl) ? val : undefined;
                };
            }
            if(overwrite === undefined){
                overwrite = true;
            }
            var cache = [],
                c,
                i = 0;
            mixInternal(target, resource, overwrite, whiteList, deep, cache);
            while ((c = cache[i++])) {
                delete c[MIX_CIRCULAR_DETECTION];
            }
            return target;
        },
        /**
         * 克隆对象
         * @param obj
         * @returns {*}
         */
        clone: function (obj){
            var objClone;
            if(obj.constructor === Object){
                objClone = new obj.constructor();
            } else {
                objClone = new obj.constructor(obj.valueOf());
            }
            for (var key in obj) {
                if(obj.hasOwnProperty(key) && objClone[key] != obj[key]){
                    if(typeof (obj[key]) == 'object'){
                        objClone[key] = obj[key].clone();
                    } else {
                        objClone[key] = obj[key];
                    }
                }
            }
            objClone.toString = obj.toString;
            objClone.valueOf = obj.valueOf;
            return objClone;
        }
    });

    function mixInternal(target, resource, overwrite, whiteList, deep, cache){
        if(!resource || !target){
            return resource;
        }
        var i, p, keys, len;

        // 记录循环标志
        resource[MIX_CIRCULAR_DETECTION] = target;

        // 记录被记录了循环标志的对像
        cache.push(resource);

        // mix all properties
        keys = S.keys(resource);
        len = keys.length;
        for (i = 0; i < len; i++) {
            p = keys[i];
            if(p !== MIX_CIRCULAR_DETECTION){
                // no hasOwnProperty judge!
                _mix(p, target, resource, overwrite, whiteList, deep, cache);
            }
        }
        return target;
    }

    function _mix(p, r, s, ov, wl, deep, cache){
        // 要求覆盖
        // 或者目的不存在
        // 或者深度mix
        if(ov || !(p in r) || deep){
            var target = r[p],
                src = s[p];
            // prevent never-end loop
            if(target === src){
                // S.mix({},{x:undefined})
                if(target === undefined){
                    r[p] = target;
                }
                return;
            }
            if(wl){
                src = wl.call(s, p, src);
            }
            // 来源是数组和对象，并且要求深度 mix
            if(deep && src && (S.isArray(src) || S.isObject(src))){
                if(src[MIX_CIRCULAR_DETECTION]){
                    r[p] = src[MIX_CIRCULAR_DETECTION];
                } else {
                    // 目标值为对象或数组，直接 mix
                    // 否则 新建一个和源值类型一样的空数组/对象，递归 mix
                    var clone = target && (S.isArray(target) || S.isObject(target)) ?
                        target :
                        (S.isArray(src) ? [] : {});
                    r[p] = clone;
                    mixInternal(clone, src, ov, wl, true, cache);
                }
            } else if(src !== undefined && (ov || !(p in r))){
                r[p] = src;
            }
        }
    }
})(GS);
/**
 * String Model
 */
(function (S, undefined){
    var RE_TRIM = /^[\s\xa0]+|[\s\xa0]+$/g,
        trim = String.prototype.trim,
        SUBSTITUTE_REG = /\\?\{([^{}]+)\}/g,
        EMPTY = '';
    S._mix(S, {
        /**
         * trim字符串左右清楚空格
         * @param str
         * @returns {*}
         */
        trim: function (str){
            return S.isEmpty(str) ? str : (trim ? trim.call(str) : (str + '').replace(RE_TRIM, EMPTY));
        },
        /**
         * 中文字符长度
         * @param str
         * @returns {*}
         */
        lengthCn: function (str){
            if(!S.isString(str)) return 0;
            return str.replace(/[\u4e00-\u9fa5]/g, "**").length;
        },
        /**
         * 截取字符串长度，后部分截取使用“...”代替
         * @param str
         * @param num
         * @param strip
         * @returns {*}
         */
        subCn: function (str, num, strip){
            if(S.lengthCn(str) <= num) return str.toString();
            for (var i = 0; i < str.length; i++) {
                if(S.lengthCn(str.substr(0, i)) >= num){
                    return str.substr(0, i) + (strip || "...");
                }
            }
            return str;
        },
        stripTags: function (str){
            return str.replace(/<\/?[^>]+>/gi, '');
        },
        stripScript: function (h){
            return h.replace(/<script[^>]*>([\\S\\s]*?)<\/script>/g, '');
        },
        /**
         * 是否是手机号码
         * @param m
         * @returns {boolean}
         */
        isMobile: function (m){
            return /^(1[35789]\d{9})$/.test(S.trim(m));
        },
        /**
         * 是否是座机号码
         * @param str
         * @returns {boolean}
         */
        isTelephone: function (str){
            return /((0[1-9]{2,3}[\s-]?)?\d{7,8})/gi.test(S.trim(str));
        },
        /**
         * 替代
         * @param str
         * @param o
         * @param regexp
         * @returns {*}
         */
        substitute: function (str, o, regexp){
            if(!(S.isString(str) && o)){
                return str;
            }
            return str.replace(regexp || SUBSTITUTE_REG, function (match, name){
                if(match.charAt(0) === '\\'){
                    return match.slice(1);
                }
                return (o[name] === undefined) ? EMPTY : o[name];
            });
        },
        /**
         * 首字母大写
         * @param str
         * @returns {string}
         */
        ucFirst: function (str){
            str += '';
            return str.charAt(0).toUpperCase() + str.substring(1);
        },
        /**
         * 以某个字符串开始
         * @param str
         * @param prefix
         * @returns {boolean}
         */
        startsWith: function (str, prefix){
            return str.lastIndexOf(prefix, 0) === 0;
        },
        /**
         * 以某个字符串结束
         * @param str
         * @param suffix
         * @returns {boolean}
         */
        endsWith: function (str, suffix){
            var ind = str.length - suffix.length;
            return ind >= 0 && str.indexOf(suffix, ind) === ind;
        },
        /**
         *  格式化字符串
         * @param str
         * @returns {*}
         */
        format: function (str){
            if(arguments.length <= 1) return str || EMPTY;
            var result = str,
                reg;
            if(2 === arguments.length && S.isObject(arguments[1])){
                for (var key in arguments[1]) {
                    if(!arguments[1].hasOwnProperty(key)) continue;
                    reg = new RegExp("\\{" + key + "\\}", "gi");
                    result = result.replace(reg, arguments[1][key]);
                }
            } else {
                for (var i = 1; i < arguments.length; i++) {
                    reg = new RegExp("\\{" + (i - 1) + "\\}", "gi");
                    result = result.replace(reg, arguments[i]);
                }
            }
            return result;
        },
        /**
         * 左侧填充
         * @param obj
         * @param len
         * @param ch
         */
        padLeft: function (obj, len, ch){
            ch = S.isUndefined(ch) ? '0' : ch;
            var s = String(obj);
            while (s.length < len)
                s = ch + s;
            return s;
        },
        /**
         * 右侧填充
         * @param obj
         * @param len
         * @param ch
         */
        padRight: function (obj, len, ch){
            ch = S.isUndefined(ch) ? '0' : ch;
            var s = String(obj);
            while (s.length < len)
                s += ch;
            return s;
        }
    });
})(GS);
/**
 * Path Model
 * @author BAI
 * @date 2015/4/7.
 */
(function (S){
    S._mix(S, {
        /**
         * 保留2位小数
         * @param x   Number
         * @param zero  boolean
         * @returns {*}
         */
        toDecimal: function (x, zero){
            if(!zero){
                // 将浮点数四舍五入，取小数点后2位
                var f = parseFloat(x);
                if(isNaN(f)){
                    return;
                }
                f = Math.round(x * 100) / 100;
                return f;
            } else {
                //制保留2位小数，如：2，会在2后面补上00.即2.00
                var f = parseFloat(x);
                if(isNaN(f)){
                    return false;
                }
                var f = Math.round(x * 100) / 100;
                var s = f.toString();
                var rs = s.indexOf('.');
                if(rs < 0){
                    rs = s.length;
                    s += '.';
                }
                while (s.length <= rs + 2) {
                    s += '0';
                }
                return s;
            }
        }
    });
})(GS);
/**
 * 终端识别
 */
(function (S){
    /*global process*/

    var win = S.Env.host,
        doc = win.document,
        navigator = win.navigator,
        ua = navigator && navigator.userAgent || '';

    function numberify(s){
        var c = 0;
        // convert '1.2.3.4' to 1.234
        return parseFloat(s.replace(/\./g, function (){
            return (c++ === 0) ? '.' : '';
        }));
    }

    function setTridentVersion(ua, UA){
        var core, m;
        UA[core = 'trident'] = 0.1; // Trident detected, look for revision

        // Get the Trident's accurate version
        if((m = ua.match(/Trident\/([\d.]*)/)) && m[1]){
            UA[core] = numberify(m[1]);
        }

        UA.core = core;
    }

    function getIEVersion(ua){
        var m, v;
        if((m = ua.match(/MSIE ([^;]*)|Trident.*; rv(?:\s|:)?([0-9.]+)/)) &&
            (v = (m[1] || m[2]))){
            return numberify(v);
        }
        return 0;
    }

    function getDescriptorFromUserAgent(ua){
        var EMPTY = '',
            os,
            core = EMPTY,
            shell = EMPTY, m,
            IE_DETECT_RANGE = [6, 9],
            ieVersion,
            v,
            end,
            VERSION_PLACEHOLDER = '{{version}}',
            IE_DETECT_TPL = '<!--[if IE ' + VERSION_PLACEHOLDER + ']><' + 's></s><![endif]-->',
            div = doc && doc.createElement('div'),
            s = [];
        /**
         * UA
         * @class GS.UA
         * @singleton
         */
        var UA = {
            /**
             * webkit version
             * @type undefined|Number
             * @member GS.UA
             */
            webkit: undefined,
            /**
             * trident version
             * @type undefined|Number
             * @member GS.UA
             */
            trident: undefined,
            /**
             * gecko version
             * @type undefined|Number
             * @member GS.UA
             */
            gecko: undefined,
            /**
             * presto version
             * @type undefined|Number
             * @member GS.UA
             */
            presto: undefined,
            /**
             * chrome version
             * @type undefined|Number
             * @member GS.UA
             */
            chrome: undefined,
            /**
             * safari version
             * @type undefined|Number
             * @member GS.UA
             */
            safari: undefined,
            /**
             * firefox version
             * @type undefined|Number
             * @member GS.UA
             */
            firefox: undefined,
            /**
             * ie version
             * @type undefined|Number
             * @member GS.UA
             */
            ie: undefined,
            /**
             * ie document mode
             * @type undefined|Number
             * @member GS.UA
             */
            ieMode: undefined,
            /**
             * opera version
             * @type undefined|Number
             * @member GS.UA
             */
            opera: undefined,
            /**
             * mobile browser. apple, android.
             * @type String
             * @member GS.UA
             */
            mobile: undefined,
            /**
             * browser render engine name. webkit, trident
             * @type String
             * @member GS.UA
             */
            core: undefined,
            /**
             * browser shell name. ie, chrome, firefox
             * @type String
             * @member GS.UA
             */
            shell: undefined,

            /**
             * PhantomJS version number
             * @type undefined|Number
             * @member GS.UA
             */
            phantomjs: undefined,

            /**
             * operating system. android, ios, linux, windows
             * @type string
             * @member GS.UA
             */
            os: undefined,

            /**
             * ipad ios version
             * @type Number
             * @member GS.UA
             */
            ipad: undefined,
            /**
             * iphone ios version
             * @type Number
             * @member GS.UA
             */
            iphone: undefined,
            /**
             * ipod ios
             * @type Number
             * @member GS.UA
             */
            ipod: undefined,
            /**
             * ios version
             * @type Number
             * @member GS.UA
             */
            ios: undefined,

            /**
             * android version
             * @type Number
             * @member GS.UA
             */
            android: undefined,

            /**
             * nodejs version
             * @type Number
             * @member GS.UA
             */
            nodejs: undefined
        };

        // ejecta
        if(div && div.getElementsByTagName){
            // try to use IE-Conditional-Comment detect IE more accurately
            // IE10 doesn't support this method, @ref: http://blogs.msdn.com/b/ie/archive/2011/07/06/html5-parsing-in-ie10.aspx
            div.innerHTML = IE_DETECT_TPL.replace(VERSION_PLACEHOLDER, '');
            s = div.getElementsByTagName('s');
        }

        if(s.length > 0){

            setTridentVersion(ua, UA);

            // Detect the accurate version
            // 注意：
            //  UA.shell = ie, 表示外壳是 ie
            //  但 UA.ie = 7, 并不代表外壳是 ie7, 还有可能是 ie8 的兼容模式
            //  对于 ie8 的兼容模式，还要通过 documentMode 去判断。但此处不能让 UA.ie = 8, 否则
            //  很多脚本判断会失误。因为 ie8 的兼容模式表现行为和 ie7 相同，而不是和 ie8 相同
            for (v = IE_DETECT_RANGE[0], end = IE_DETECT_RANGE[1]; v <= end; v++) {
                div.innerHTML = IE_DETECT_TPL.replace(VERSION_PLACEHOLDER, v);
                if(s.length > 0){
                    UA[shell = 'ie'] = v;
                    break;
                }
            }

            // win8 embed app
            if(!UA.ie && (ieVersion = getIEVersion(ua))){
                UA[shell = 'ie'] = ieVersion;
            }

        } else {
            // WebKit
            if((m = ua.match(/AppleWebKit\/([\d.]*)/)) && m[1]){
                UA[core = 'webkit'] = numberify(m[1]);

                if((m = ua.match(/OPR\/(\d+\.\d+)/)) && m[1]){
                    UA[shell = 'opera'] = numberify(m[1]);
                }
                // Chrome
                else if((m = ua.match(/Chrome\/([\d.]*)/)) && m[1]){
                    UA[shell = 'chrome'] = numberify(m[1]);
                }
                // Safari
                else if((m = ua.match(/\/([\d.]*) Safari/)) && m[1]){
                    UA[shell = 'safari'] = numberify(m[1]);
                }

                // Apple Mobile
                if(/ Mobile\//.test(ua) && ua.match(/iPad|iPod|iPhone/)){
                    UA.mobile = 'apple'; // iPad, iPhone or iPod Touch

                    m = ua.match(/OS ([^\s]*)/);
                    if(m && m[1]){
                        UA.ios = numberify(m[1].replace('_', '.'));
                    }
                    os = 'ios';
                    m = ua.match(/iPad|iPod|iPhone/);
                    if(m && m[0]){
                        UA[m[0].toLowerCase()] = UA.ios;
                    }
                } else if(/ Android/i.test(ua)){
                    if(/Mobile/.test(ua)){
                        os = UA.mobile = 'android';
                    }
                    m = ua.match(/Android ([^\s]*);/);
                    if(m && m[1]){
                        UA.android = numberify(m[1]);
                    }
                }
                // Other WebKit Mobile Browsers
                else if((m = ua.match(/NokiaN[^\/]*|Android \d\.\d|webOS\/\d\.\d/))){
                    UA.mobile = m[0].toLowerCase(); // Nokia N-series, Android, webOS, ex: NokiaN95
                }

                if((m = ua.match(/PhantomJS\/([^\s]*)/)) && m[1]){
                    UA.phantomjs = numberify(m[1]);
                }
            }
            // NOT WebKit
            else {
                // Presto
                // ref: http://www.useragentstring.com/pages/useragentstring.php
                if((m = ua.match(/Presto\/([\d.]*)/)) && m[1]){
                    UA[core = 'presto'] = numberify(m[1]);

                    // Opera
                    if((m = ua.match(/Opera\/([\d.]*)/)) && m[1]){
                        UA[shell = 'opera'] = numberify(m[1]); // Opera detected, look for revision

                        if((m = ua.match(/Opera\/.* Version\/([\d.]*)/)) && m[1]){
                            UA[shell] = numberify(m[1]);
                        }

                        // Opera Mini
                        if((m = ua.match(/Opera Mini[^;]*/)) && m){
                            UA.mobile = m[0].toLowerCase(); // ex: Opera Mini/2.0.4509/1316
                        }
                        // Opera Mobile
                        // ex: Opera/9.80 (Windows NT 6.1; Opera Mobi/49; U; en) Presto/2.4.18 Version/10.00
                        // issue: 由于 Opera Mobile 有 Version/ 字段，可能会与 Opera 混淆，同时对于 Opera Mobile 的版本号也比较混乱
                        else if((m = ua.match(/Opera Mobi[^;]*/)) && m){
                            UA.mobile = m[0];
                        }
                    }

                    // NOT WebKit or Presto
                } else {
                    // MSIE
                    // 由于最开始已经使用了 IE 条件注释判断，因此落到这里的唯一可能性只有 IE10+
                    // and analysis tools in nodejs
                    if((ieVersion = getIEVersion(ua))){
                        UA[shell = 'ie'] = ieVersion;
                        setTridentVersion(ua, UA);
                        // NOT WebKit, Presto or IE
                    } else {
                        // Gecko
                        if((m = ua.match(/Gecko/))){
                            UA[core = 'gecko'] = 0.1; // Gecko detected, look for revision
                            if((m = ua.match(/rv:([\d.]*)/)) && m[1]){
                                UA[core] = numberify(m[1]);
                                if(/Mobile|Tablet/.test(ua)){
                                    UA.mobile = 'firefox';
                                }
                            }
                            // Firefox
                            if((m = ua.match(/Firefox\/([\d.]*)/)) && m[1]){
                                UA[shell = 'firefox'] = numberify(m[1]);
                            }
                        }
                    }
                }
            }
        }

        if(!os){
            if((/windows|win32/i).test(ua)){
                os = 'windows';
            } else if((/macintosh|mac_powerpc/i).test(ua)){
                os = 'macintosh';
            } else if((/linux/i).test(ua)){
                os = 'linux';
            } else if((/rhino/i).test(ua)){
                os = 'rhino';
            }
        }

        UA.os = os;
        UA.core = UA.core || core;
        UA.shell = shell;
        UA.ieMode = UA.ie && doc.documentMode || UA.ie;

        return UA;
    }

    var UA = GS.UA = getDescriptorFromUserAgent(ua);

    // nodejs
    if(typeof process === 'object'){
        var versions, nodeVersion;

        if((versions = process.versions) && (nodeVersion = versions.node)){
            UA.os = process.platform;
            UA.nodejs = numberify(nodeVersion);
        }
    }

    // use by analysis tools in nodejs
    UA.getDescriptorFromUserAgent = getDescriptorFromUserAgent;

    //设置html的Css
//    var browsers = [
//            // browser core type
//            'webkit',
//            'trident',
//            'gecko',
//            'presto',
//            // browser type
//            'chrome',
//            'safari',
//            'firefox',
//            'ie',
//            'opera'
//        ],
//        documentElement = doc && doc.documentElement,
//        className = '';
//    if (documentElement) {
//        S.each(browsers, function (key) {
//            var v = UA[key];
//            if (v) {
//                className += ' ks-' + key + (parseInt(v) + '');
//                className += ' ks-' + key;
//            }
//        });
//        if (S.trim(className)) {
//            documentElement.className = S.trim(documentElement.className + className);
//        }
//    }
})(GS);
/**
 * Uri
 */
(function (S){
    // [root, dir, basename, ext]
    var splitPathRe = /^(\/?)([\s\S]+\/(?!$)|\/)?((?:\.{1,2}$|[\s\S]+?)?(\.[^.\/]*)?)$/;
    S._mix(S, {
        /**
         * 获取页面参数
         * @param uri
         * @returns {Array}
         */
        uri: function (uri){
            var q = [], qs;
            qs = (uri ? uri + "" : location.search);
            // 如果当中存在“？”
            if(qs.indexOf('?') >= 0){
                // 获取substring 方法获取“？”后面的值；
                qs = qs.substring(1);
            }
            if(qs){
                // 以'&'开始分割字符串
                qs = qs.split('&');
            }
            if(qs.length > 0){
                for (var i = 0; i < qs.length; i++) {
                    var qt = qs[i].split('=');
                    q[qt[0]] = decodeURIComponent(qt[1]);
                }
            }
            return q;
        },
        /**
         * cookie操作
         */
        cookie: {
            set: function (name, value, minutes, domain){
                if("string" !== typeof name || "" === S.trim(name)) return;
                var c = name + '=' + encodeURI(value);
                if("number" === typeof minutes && minutes > 0){
                    var time = (new Date()).getTime() + 1000 * 60 * minutes;
                    c += ';expires=' + (new Date(time)).toGMTString();
                }
                if("string" == typeof domain)
                    c += ';domain=' + domain;
                document.cookie = c + '; path=/';
            },
            get: function (name){
                var b = document.cookie;
                var d = name + '=';
                var c = b.indexOf('; ' + d);
                if(c == -1){
                    c = b.indexOf(d);
                    if(c != 0){
                        return null;
                    }
                } else {
                    c += 2;
                }
                var a = b.indexOf(';', c);
                if(a == -1){
                    a = b.length;
                }
                return decodeURI(b.substring(c + d.length, a));
            },
            clear: function (name, domain){
                if(this.get(name)){
                    document.cookie = name + '=' + (domain ? '; domain=' + domain : '') + '; expires=Thu, 01-Jan-70 00:00:01 GMT';
                }
            }
        },
        /**
         * 参数化
         * @param data 参数对象
         * @returns {string} 参数字符
         */
        stringify: function (data){
            if(!data || data.length || !S.isObject(data)) return "";
            var list = [];
            S.each(S.keys(data), function (key){
                var item = data[key];
                if(!S.isObject(item) && !S.isArray(item))
                    list.push(key + '=' + encodeURIComponent(item));
                else
                    list.push(key + '=' + encodeURIComponent(S.json(item)));
            });
            return list.join('&');
        },
        ext: function (url){
            return (url.match(splitPathRe) || [])[4] || '';
        }
    });
})(GS);
/**
 * 加载script标签
 * @author BAI
 * @date 2015/04/07
 */
(function (S){
    var MILLISECONDS_OF_SECOND = 1000,
        doc = document,
        UA = S.UA,
        headNode = doc.getElementsByTagName('head')[0] || doc.documentElement,
        jsCssCallbacks = {};
    S._mix(S, {
        currentScript: function (){
            //取得正在解析的script节点
            if(document.currentScript){ //firefox 4+
                return document.currentScript.src;
            }
            // 参考 https://github.com/samyk/jiagra/blob/master/jiagra.js
            var stack;
            try {
                a.b.c(); //强制报错,以便捕获e.stack
            } catch (e) {//safari的错误对象只有line,sourceId,sourceURL
                stack = e.stack;
                if(!stack && window.opera){
                    //opera 9没有e.stack,但有e.Backtrace,但不能直接取得,需要对e对象转字符串进行抽取
                    stack = (String(e).match(/of linked script \S+/g) || []).join(" ");
                }
            }
            if(stack){
                stack = stack.split(/[@ ]/g).pop();//取得最后一行,最后一个空格或@之后的部分
                stack = stack[0] == "(" ? stack.slice(1, -1) : stack;
                return stack.replace(/(:\d+)?:\d+$/i, "");//去掉行号与或许存在的出错字符起始位置
            }
            var nodes = document.getElementsByTagName("script"); //只在head标签中寻找
            for (var i = 0, node; node = nodes[i++];) {
                if(node.readyState === "interactive"){
                    return node.className = node.src;
                }
            }
        },
        /**
         * 加载script
         * @param url     css或者js
         * @param success 调用成功后执行 ()
         * @param charset 编码
         */
        loadScript: function (url, success, charset){
            var
                config = success,
                error,
                attrs,
                css = 0,
                timeout,
                callbacks,
                timer;
            //获取链接路径是css 还是js 判断
            //toLowerCase 字符串大写转换成小写
            if(S.startsWith(S.ext(url).toLowerCase(), '.css')){
                css = 1;
            }

            //如果是一个对象{}
            /*  {
             success: function () {
             console.log('事例')
             }
             }*/
            if(S.isObject(config)){
                success = config.success;
                error = config.error;
                attrs = config.attrs;
                timeout = config.timeout;
                charset = config.charset;
            }
            callbacks = jsCssCallbacks[url] = jsCssCallbacks[url] || [];

            callbacks.push([success, error]);

            if(callbacks.length > 1){
                return callbacks.node;
            }
            // 如果为css 添加 link  &&  js=script
            var node = doc.createElement(css ? 'link' : 'script'),
                clearTimer = function (){
                    if(timer){
                        timer.cancel();
                        timer = undefined;
                    }
                };

            if(attrs){
                S.each(attrs, function (v, n){
                    var attrName = n.toLowerCase();
                    if(attrName == "async" && !S.isUndefined(node.async)){
                        node.async = v;
                    } else {
                        node.setAttribute(n, v);
                    }
                });
            }
            // 如果有编码参数
            if(charset){
                node.charset = charset;
            }

            if(css){
                node.href = url;
                node.rel = 'stylesheet';
            } else {
                node.src = url;
                node.async = true;
            }

            callbacks.node = node;

            var end = function (error){
                var index = error,
                    fn;
                clearTimer();
                S.each(jsCssCallbacks[url], function (callback){
                    if((fn = callback[index])){
                        fn.call(node);
                    }
                });
                delete jsCssCallbacks[url];
            };

            var useNative = 'onload' in node;
            var forceCssPoll = S.Config.forceCssPoll || (UA.webkit && UA.webkit < 536);

            if(css && forceCssPoll && useNative){
                useNative = false;
            }

            function onload(){
                var readyState = node.readyState;
                if(!readyState ||
                    readyState === 'loaded' ||
                    readyState === 'complete'){
                    node.onreadystatechange = node.onload = null;
                    end(0);
                }
            }

            //标准浏览器 css and all script
            if(useNative){
                node.onload = onload;
                node.onerror = function (){
                    node.onerror = null;
                    end(1);
                };
            }
            // old chrome/firefox for css
            else if(css){
//                pollCss(node, function () {
//                    end(0);
//                });
            } else {
                node.onreadystatechange = onload;
            }

            if(timeout){
                timer = S.later(function (){
                    end(1);
                }, timeout * MILLISECONDS_OF_SECOND);
            }
            if(css){
                headNode.appendChild(node);
            } else {
                headNode.insertBefore(node, headNode.firstChild);
            }
            return node;
        }
    });
})(GS);
(function (S){
    // 数组方法
    S._mix(S, {
        textArea: function (str){
            if(str == ''){
                return false;
            }
            str = str.replace(/\s+/g, ' ').split(" ");
            if(!S.isArray(str)){
                S.msg('粘贴格式错误');
            }
            //去除数组中空值
            for (var i = 0; i < str.length; i++) {
                if(str[i] == "" || typeof (str[i]) == "undefined"){
                    str.splice(i, 1);
                    i = i - 1;
                }
            }
            return str;
        },
        /**
         * 检查姓名
         * @param name
         * @returns {boolean}
         */
        checkName: function (name){
            return /^[\u4e00-\u9fa5]{2,5}$/.test(name);
        },
        /**
         * 数组去重
         * @returns {Array}
         */
        pageArrRepeat: function (arr, callback){
            if(!S.isArray(arr) && !arr.length){
                return;
            }
            var nary = arr.sort();
            var repeatData = [];
            for (var i = 0; i < nary.length - 1; i++) {
                var item = nary[i];
                if(item == nary[i + 1] && !S.inArray(item, repeatData)){
                    repeatData.push(item);
                }
            }
            callback && callback.call(this, repeatData);
        }
    });
    // 判断方法
    S._mix(S, {
        /**
         * 检查姓名
         * @param name
         * @returns {boolean}
         */
        checkName: function (name){
            return /^[\u4e00-\u9fa5]{2,5}$/.test(name);
        }
    });


})(GS);
(function (D){
    D.config({
        setWindow: {
            setUpSize: true
        }
    });
    D._mix(D, {
        sites: (function (){
            return (window.GG_WX_H5 || {}).sites || {}
        })()
    });

})(GS);

