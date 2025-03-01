var Z = Object.defineProperty;
var Q = (e, t, i) => t in e ? Z(e, t, { enumerable: !0, configurable: !0, writable: !0, value: i }) : e[t] = i;
var X = (e, t, i) => Q(e, typeof t != "symbol" ? t + "" : t, i);
/*!
 * FilePond 4.31.1
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */
const isNode = (e) => e instanceof HTMLElement, createStore = (e, t = [], i = []) => {
  const a = {
    ...e
  }, n = [], l = [], r = () => ({ ...a }), s = () => {
    const m = [...n];
    return n.length = 0, m;
  }, o = () => {
    const m = [...l];
    l.length = 0, m.forEach(({ type: h, data: I }) => {
      d(h, I);
    });
  }, d = (m, h, I) => {
    if (I && !document.hidden) {
      l.push({ type: m, data: h });
      return;
    }
    p[m] && p[m](h), n.push({
      type: m,
      data: h
    });
  }, c = (m, ...h) => f[m] ? f[m](...h) : null, u = {
    getState: r,
    processActionQueue: s,
    processDispatchQueue: o,
    dispatch: d,
    query: c
  };
  let f = {};
  t.forEach((m) => {
    f = {
      ...m(a),
      ...f
    };
  });
  let p = {};
  return i.forEach((m) => {
    p = {
      ...m(d, c, a),
      ...p
    };
  }), u;
}, defineProperty = (e, t, i) => {
  if (typeof i == "function") {
    e[t] = i;
    return;
  }
  Object.defineProperty(e, t, { ...i });
}, forin = (e, t) => {
  for (const i in e)
    e.hasOwnProperty(i) && t(i, e[i]);
}, createObject = (e) => {
  const t = {};
  return forin(e, (i) => {
    defineProperty(t, i, e[i]);
  }), t;
}, attr = (e, t, i = null) => {
  if (i === null)
    return e.getAttribute(t) || e.hasAttribute(t);
  e.setAttribute(t, i);
}, ns$1 = "http://www.w3.org/2000/svg", svgElements = ["svg", "path"], isSVGElement = (e) => svgElements.includes(e), createElement = (e, t, i = {}) => {
  typeof t == "object" && (i = t, t = null);
  const a = isSVGElement(e) ? document.createElementNS(ns$1, e) : document.createElement(e);
  return t && (isSVGElement(e) ? attr(a, "class", t) : a.className = t), forin(i, (n, l) => {
    attr(a, n, l);
  }), a;
}, appendChild = (e) => (t, i) => {
  typeof i < "u" && e.children[i] ? e.insertBefore(t, e.children[i]) : e.appendChild(t);
}, appendChildView = (e, t) => (i, a) => (typeof a < "u" ? t.splice(a, 0, i) : t.push(i), i), removeChildView = (e, t) => (i) => (t.splice(t.indexOf(i), 1), i.element.parentNode && e.removeChild(i.element), i), IS_BROWSER = typeof window < "u" && typeof window.document < "u", isBrowser$4 = () => IS_BROWSER, testElement = isBrowser$4() ? createElement("svg") : {}, getChildCount = "children" in testElement ? (e) => e.children.length : (e) => e.childNodes.length, getViewRect = (e, t, i, a) => {
  const n = i[0] || e.left, l = i[1] || e.top, r = n + e.width, s = l + e.height * (a[1] || 1), o = {
    // the rectangle of the element itself
    element: {
      ...e
    },
    // the rectangle of the element expanded to contain its children, does not include any margins
    inner: {
      left: e.left,
      top: e.top,
      right: e.right,
      bottom: e.bottom
    },
    // the rectangle of the element expanded to contain its children including own margin and child margins
    // margins will be added after we've recalculated the size
    outer: {
      left: n,
      top: l,
      right: r,
      bottom: s
    }
  };
  return t.filter((d) => !d.isRectIgnored()).map((d) => d.rect).forEach((d) => {
    expandRect(o.inner, { ...d.inner }), expandRect(o.outer, { ...d.outer });
  }), calculateRectSize(o.inner), o.outer.bottom += o.element.marginBottom, o.outer.right += o.element.marginRight, calculateRectSize(o.outer), o;
}, expandRect = (e, t) => {
  t.top += e.top, t.right += e.left, t.bottom += e.top, t.left += e.left, t.bottom > e.bottom && (e.bottom = t.bottom), t.right > e.right && (e.right = t.right);
}, calculateRectSize = (e) => {
  e.width = e.right - e.left, e.height = e.bottom - e.top;
}, isNumber$1 = (e) => typeof e == "number", thereYet = (e, t, i, a = 1e-3) => Math.abs(e - t) < a && Math.abs(i) < a, spring = (
  // default options
  ({ stiffness: e = 0.5, damping: t = 0.75, mass: i = 10 } = {}) => {
    let a = null, n = null, l = 0, r = !1;
    const d = createObject({
      interpolate: (c, u) => {
        if (r) return;
        if (!(isNumber$1(a) && isNumber$1(n))) {
          r = !0, l = 0;
          return;
        }
        const f = -(n - a) * e;
        l += f / i, n += l, l *= t, thereYet(n, a, l) || u ? (n = a, l = 0, r = !0, d.onupdate(n), d.oncomplete(n)) : d.onupdate(n);
      },
      target: {
        set: (c) => {
          if (isNumber$1(c) && !isNumber$1(n) && (n = c), a === null && (a = c, n = c), a = c, n === a || typeof a > "u") {
            r = !0, l = 0, d.onupdate(n), d.oncomplete(n);
            return;
          }
          r = !1;
        },
        get: () => a
      },
      resting: {
        get: () => r
      },
      onupdate: (c) => {
      },
      oncomplete: (c) => {
      }
    });
    return d;
  }
), easeInOutQuad = (e) => e < 0.5 ? 2 * e * e : -1 + (4 - 2 * e) * e, tween = (
  // default values
  ({ duration: e = 500, easing: t = easeInOutQuad, delay: i = 0 } = {}) => {
    let a = null, n, l, r = !0, s = !1, o = null;
    const c = createObject({
      interpolate: (u, f) => {
        r || o === null || (a === null && (a = u), !(u - a < i) && (n = u - a - i, n >= e || f ? (n = 1, l = s ? 0 : 1, c.onupdate(l * o), c.oncomplete(l * o), r = !0) : (l = n / e, c.onupdate((n >= 0 ? t(s ? 1 - l : l) : 0) * o))));
      },
      target: {
        get: () => s ? 0 : o,
        set: (u) => {
          if (o === null) {
            o = u, c.onupdate(u), c.oncomplete(u);
            return;
          }
          u < o ? (o = 1, s = !0) : (s = !1, o = u), r = !1, a = null;
        }
      },
      resting: {
        get: () => r
      },
      onupdate: (u) => {
      },
      oncomplete: (u) => {
      }
    });
    return c;
  }
), animator = {
  spring,
  tween
}, createAnimator = (e, t, i) => {
  const a = e[t] && typeof e[t][i] == "object" ? e[t][i] : e[t] || e, n = typeof a == "string" ? a : a.type, l = typeof a == "object" ? { ...a } : {};
  return animator[n] ? animator[n](l) : null;
}, addGetSet = (e, t, i, a = !1) => {
  t = Array.isArray(t) ? t : [t], t.forEach((n) => {
    e.forEach((l) => {
      let r = l, s = () => i[l], o = (d) => i[l] = d;
      typeof l == "object" && (r = l.key, s = l.getter || s, o = l.setter || o), !(n[r] && !a) && (n[r] = {
        get: s,
        set: o
      });
    });
  });
}, animations = ({ mixinConfig: e, viewProps: t, viewInternalAPI: i, viewExternalAPI: a }) => {
  const n = { ...t }, l = [];
  return forin(e, (r, s) => {
    const o = createAnimator(s);
    if (!o)
      return;
    o.onupdate = (c) => {
      t[r] = c;
    }, o.target = n[r], addGetSet([{
      key: r,
      setter: (c) => {
        o.target !== c && (o.target = c);
      },
      getter: () => t[r]
    }], [i, a], t, !0), l.push(o);
  }), {
    write: (r) => {
      let s = document.hidden, o = !0;
      return l.forEach((d) => {
        d.resting || (o = !1), d.interpolate(r, s);
      }), o;
    },
    destroy: () => {
    }
  };
}, addEvent = (e) => (t, i) => {
  e.addEventListener(t, i);
}, removeEvent = (e) => (t, i) => {
  e.removeEventListener(t, i);
}, listeners = ({
  mixinConfig: e,
  viewProps: t,
  viewInternalAPI: i,
  viewExternalAPI: a,
  viewState: n,
  view: l
}) => {
  const r = [], s = addEvent(l.element), o = removeEvent(l.element);
  return a.on = (d, c) => {
    r.push({
      type: d,
      fn: c
    }), s(d, c);
  }, a.off = (d, c) => {
    r.splice(r.findIndex((u) => u.type === d && u.fn === c), 1), o(d, c);
  }, {
    write: () => !0,
    destroy: () => {
      r.forEach((d) => {
        o(d.type, d.fn);
      });
    }
  };
}, apis = ({ mixinConfig: e, viewProps: t, viewExternalAPI: i }) => {
  addGetSet(e, i, t);
}, isDefined$1 = (e) => e != null, defaults$1 = {
  opacity: 1,
  scaleX: 1,
  scaleY: 1,
  translateX: 0,
  translateY: 0,
  rotateX: 0,
  rotateY: 0,
  rotateZ: 0,
  originX: 0,
  originY: 0
}, styles = ({ mixinConfig: e, viewProps: t, viewInternalAPI: i, viewExternalAPI: a, view: n }) => {
  const l = { ...t }, r = {};
  addGetSet(e, [i, a], t);
  const s = () => [t.translateX || 0, t.translateY || 0], o = () => [t.scaleX || 0, t.scaleY || 0], d = () => n.rect ? getViewRect(n.rect, n.childViews, s(), o()) : null;
  return i.rect = { get: d }, a.rect = { get: d }, e.forEach((c) => {
    t[c] = typeof l[c] > "u" ? defaults$1[c] : l[c];
  }), {
    write: () => {
      if (propsHaveChanged(r, t))
        return applyStyles(n.element, t), Object.assign(r, { ...t }), !0;
    },
    destroy: () => {
    }
  };
}, propsHaveChanged = (e, t) => {
  if (Object.keys(e).length !== Object.keys(t).length)
    return !0;
  for (const i in t)
    if (t[i] !== e[i])
      return !0;
  return !1;
}, applyStyles = (e, {
  opacity: t,
  perspective: i,
  translateX: a,
  translateY: n,
  scaleX: l,
  scaleY: r,
  rotateX: s,
  rotateY: o,
  rotateZ: d,
  originX: c,
  originY: u,
  width: f,
  height: p
}) => {
  let m = "", h = "";
  (isDefined$1(c) || isDefined$1(u)) && (h += `transform-origin: ${c || 0}px ${u || 0}px;`), isDefined$1(i) && (m += `perspective(${i}px) `), (isDefined$1(a) || isDefined$1(n)) && (m += `translate3d(${a || 0}px, ${n || 0}px, 0) `), (isDefined$1(l) || isDefined$1(r)) && (m += `scale3d(${isDefined$1(l) ? l : 1}, ${isDefined$1(r) ? r : 1}, 1) `), isDefined$1(d) && (m += `rotateZ(${d}rad) `), isDefined$1(s) && (m += `rotateX(${s}rad) `), isDefined$1(o) && (m += `rotateY(${o}rad) `), m.length && (h += `transform:${m};`), isDefined$1(t) && (h += `opacity:${t};`, t === 0 && (h += "visibility:hidden;"), t < 1 && (h += "pointer-events:none;")), isDefined$1(p) && (h += `height:${p}px;`), isDefined$1(f) && (h += `width:${f}px;`);
  const I = e.elementCurrentStyle || "";
  (h.length !== I.length || h !== I) && (e.style.cssText = h, e.elementCurrentStyle = h);
}, Mixins = {
  styles,
  listeners,
  animations,
  apis
}, updateRect$1 = (e = {}, t = {}, i = {}) => (t.layoutCalculated || (e.paddingTop = parseInt(i.paddingTop, 10) || 0, e.marginTop = parseInt(i.marginTop, 10) || 0, e.marginRight = parseInt(i.marginRight, 10) || 0, e.marginBottom = parseInt(i.marginBottom, 10) || 0, e.marginLeft = parseInt(i.marginLeft, 10) || 0, t.layoutCalculated = !0), e.left = t.offsetLeft || 0, e.top = t.offsetTop || 0, e.width = t.offsetWidth || 0, e.height = t.offsetHeight || 0, e.right = e.left + e.width, e.bottom = e.top + e.height, e.scrollTop = t.scrollTop, e.hidden = t.offsetParent === null, e), createView = (
  // default view definition
  ({
    // element definition
    tag: e = "div",
    name: t = null,
    attributes: i = {},
    // view interaction
    read: a = () => {
    },
    write: n = () => {
    },
    create: l = () => {
    },
    destroy: r = () => {
    },
    // hooks
    filterFrameActionsForChild: s = (p, m) => m,
    didCreateView: o = () => {
    },
    didWriteView: d = () => {
    },
    // rect related
    ignoreRect: c = !1,
    ignoreRectUpdate: u = !1,
    // mixins
    mixins: f = []
  } = {}) => (p, m = {}) => {
    const h = createElement(e, `filepond--${t}`, i), I = window.getComputedStyle(h, null), b = updateRect$1();
    let g = null, E = !1;
    const T = [], S = [], L = {}, M = {}, y = [
      n
      // default writer
    ], x = [
      a
      // default reader
    ], v = [
      r
      // default destroy
    ], P = () => h, O = () => T.concat(), B = () => L, F = (C) => (N, $) => N(C, $), D = () => g || (g = getViewRect(b, T, [0, 0], [1, 1]), g), R = () => I, A = () => {
      g = null, T.forEach(($) => $._read()), !(u && b.width && b.height) && updateRect$1(b, h, I);
      const N = { root: U, props: m, rect: b };
      x.forEach(($) => $(N));
    }, w = (C, N, $) => {
      let W = N.length === 0;
      return y.forEach((G) => {
        G({
          props: m,
          root: U,
          actions: N,
          timestamp: C,
          shouldOptimize: $
        }) === !1 && (W = !1);
      }), S.forEach((G) => {
        G.write(C) === !1 && (W = !1);
      }), T.filter((G) => !!G.element.parentNode).forEach((G) => {
        G._write(
          C,
          s(G, N),
          $
        ) || (W = !1);
      }), T.forEach((G, j) => {
        G.element.parentNode || (U.appendChild(G.element, j), G._read(), G._write(
          C,
          s(G, N),
          $
        ), W = !1);
      }), E = W, d({
        props: m,
        root: U,
        actions: N,
        timestamp: C
      }), W;
    }, z = () => {
      S.forEach((C) => C.destroy()), v.forEach((C) => {
        C({ root: U, props: m });
      }), T.forEach((C) => C._destroy());
    }, k = {
      element: {
        get: P
      },
      style: {
        get: R
      },
      childViews: {
        get: O
      }
    }, V = {
      ...k,
      rect: {
        get: D
      },
      // access to custom children references
      ref: {
        get: B
      },
      // dom modifiers
      is: (C) => t === C,
      appendChild: appendChild(h),
      createChildView: F(p),
      linkView: (C) => (T.push(C), C),
      unlinkView: (C) => {
        T.splice(T.indexOf(C), 1);
      },
      appendChildView: appendChildView(h, T),
      removeChildView: removeChildView(h, T),
      registerWriter: (C) => y.push(C),
      registerReader: (C) => x.push(C),
      registerDestroyer: (C) => v.push(C),
      invalidateLayout: () => h.layoutCalculated = !1,
      // access to data store
      dispatch: p.dispatch,
      query: p.query
    }, H = {
      element: {
        get: P
      },
      childViews: {
        get: O
      },
      rect: {
        get: D
      },
      resting: {
        get: () => E
      },
      isRectIgnored: () => c,
      _read: A,
      _write: w,
      _destroy: z
    }, q = {
      ...k,
      rect: {
        get: () => b
      }
    };
    Object.keys(f).sort((C, N) => C === "styles" ? 1 : N === "styles" ? -1 : 0).forEach((C) => {
      const N = Mixins[C]({
        mixinConfig: f[C],
        viewProps: m,
        viewState: M,
        viewInternalAPI: V,
        viewExternalAPI: H,
        view: createObject(q)
      });
      N && S.push(N);
    });
    const U = createObject(V);
    l({
      root: U,
      props: m
    });
    const Y = getChildCount(h);
    return T.forEach((C, N) => {
      U.appendChild(C.element, Y + N);
    }), o(U), createObject(H);
  }
), createPainter = (e, t, i = 60) => {
  const a = "__framePainter";
  if (window[a]) {
    window[a].readers.push(e), window[a].writers.push(t);
    return;
  }
  window[a] = {
    readers: [e],
    writers: [t]
  };
  const n = window[a], l = 1e3 / i;
  let r = null, s = null, o = null, d = null;
  const c = () => {
    document.hidden ? (o = () => window.setTimeout(() => u(performance.now()), l), d = () => window.clearTimeout(s)) : (o = () => window.requestAnimationFrame(u), d = () => window.cancelAnimationFrame(s));
  };
  document.addEventListener("visibilitychange", () => {
    d && d(), c(), u(performance.now());
  });
  const u = (f) => {
    s = o(u), r || (r = f);
    const p = f - r;
    p <= l || (r = f - p % l, n.readers.forEach((m) => m()), n.writers.forEach((m) => m(f)));
  };
  return c(), u(performance.now()), {
    pause: () => {
      d(s);
    }
  };
}, createRoute = (e, t) => ({ root: i, props: a, actions: n = [], timestamp: l, shouldOptimize: r }) => {
  n.filter((s) => e[s.type]).forEach(
    (s) => e[s.type]({ root: i, props: a, action: s.data, timestamp: l, shouldOptimize: r })
  ), t && t({ root: i, props: a, actions: n, timestamp: l, shouldOptimize: r });
}, insertBefore = (e, t) => t.parentNode.insertBefore(e, t), insertAfter = (e, t) => t.parentNode.insertBefore(e, t.nextSibling), isArray$1 = (e) => Array.isArray(e), isEmpty$1 = (e) => e == null, trim = (e) => e.trim(), toString$1 = (e) => "" + e, toArray = (e, t = ",") => isEmpty$1(e) ? [] : isArray$1(e) ? e : toString$1(e).split(t).map(trim).filter((i) => i.length), isBoolean = (e) => typeof e == "boolean", toBoolean = (e) => isBoolean(e) ? e : e === "true", isString$1 = (e) => typeof e == "string", toNumber = (e) => isNumber$1(e) ? e : isString$1(e) ? toString$1(e).replace(/[a-z]+/gi, "") : 0, toInt = (e) => parseInt(toNumber(e), 10), toFloat = (e) => parseFloat(toNumber(e)), isInt = (e) => isNumber$1(e) && isFinite(e) && Math.floor(e) === e, toBytes = (e, t = 1e3) => {
  if (isInt(e))
    return e;
  let i = toString$1(e).trim();
  return /MB$/i.test(i) ? (i = i.replace(/MB$i/, "").trim(), toInt(i) * t * t) : /KB/i.test(i) ? (i = i.replace(/KB$i/, "").trim(), toInt(i) * t) : toInt(i);
}, isFunction$2 = (e) => typeof e == "function", toFunctionReference = (e) => {
  let t = self, i = e.split("."), a = null;
  for (; a = i.shift(); )
    if (t = t[a], !t)
      return null;
  return t;
}, methods = {
  process: "POST",
  patch: "PATCH",
  revert: "DELETE",
  fetch: "GET",
  restore: "GET",
  load: "GET"
}, createServerAPI = (e) => {
  const t = {};
  return t.url = isString$1(e) ? e : e.url || "", t.timeout = e.timeout ? parseInt(e.timeout, 10) : 0, t.headers = e.headers ? e.headers : {}, forin(methods, (i) => {
    t[i] = createAction(i, e[i], methods[i], t.timeout, t.headers);
  }), t.process = e.process || isString$1(e) || e.url ? t.process : null, t.remove = e.remove || null, delete t.headers, t;
}, createAction = (e, t, i, a, n) => {
  if (t === null)
    return null;
  if (typeof t == "function")
    return t;
  const l = {
    url: i === "GET" || i === "PATCH" ? `?${e}=` : "",
    method: i,
    headers: n,
    withCredentials: !1,
    timeout: a,
    onload: null,
    ondata: null,
    onerror: null
  };
  if (isString$1(t))
    return l.url = t, l;
  if (Object.assign(l, t), isString$1(l.headers)) {
    const r = l.headers.split(/:(.+)/);
    l.headers = {
      header: r[0],
      value: r[1]
    };
  }
  return l.withCredentials = toBoolean(l.withCredentials), l;
}, toServerAPI = (e) => createServerAPI(e), isNull = (e) => e === null, isObject$1 = (e) => typeof e == "object" && e !== null, isAPI = (e) => isObject$1(e) && isString$1(e.url) && isObject$1(e.process) && isObject$1(e.revert) && isObject$1(e.restore) && isObject$1(e.fetch), getType = (e) => isArray$1(e) ? "array" : isNull(e) ? "null" : isInt(e) ? "int" : /^[0-9]+ ?(?:GB|MB|KB)$/gi.test(e) ? "bytes" : isAPI(e) ? "api" : typeof e, replaceSingleQuotes = (e) => e.replace(/{\s*'/g, '{"').replace(/'\s*}/g, '"}').replace(/'\s*:/g, '":').replace(/:\s*'/g, ':"').replace(/,\s*'/g, ',"').replace(/'\s*,/g, '",'), conversionTable = {
  array: toArray,
  boolean: toBoolean,
  int: (e) => getType(e) === "bytes" ? toBytes(e) : toInt(e),
  number: toFloat,
  float: toFloat,
  bytes: toBytes,
  string: (e) => isFunction$2(e) ? e : toString$1(e),
  function: (e) => toFunctionReference(e),
  serverapi: toServerAPI,
  object: (e) => {
    try {
      return JSON.parse(replaceSingleQuotes(e));
    } catch {
      return null;
    }
  }
}, convertTo = (e, t) => conversionTable[t](e), getValueByType = (e, t, i) => {
  if (e === t)
    return e;
  let a = getType(e);
  if (a !== i) {
    const n = convertTo(e, i);
    if (a = getType(n), n === null)
      throw `Trying to assign value with incorrect type to "${option}", allowed type: "${i}"`;
    e = n;
  }
  return e;
}, createOption = (e, t) => {
  let i = e;
  return {
    enumerable: !0,
    get: () => i,
    set: (a) => {
      i = getValueByType(a, e, t);
    }
  };
}, createOptions = (e) => {
  const t = {};
  return forin(e, (i) => {
    const a = e[i];
    t[i] = createOption(a[0], a[1]);
  }), createObject(t);
}, createInitialState = (e) => ({
  // model
  items: [],
  // timeout used for calling update items
  listUpdateTimeout: null,
  // timeout used for stacking metadata updates
  itemUpdateTimeout: null,
  // queue of items waiting to be processed
  processingQueue: [],
  // options
  options: createOptions(e)
}), fromCamels = (e, t = "-") => e.split(/(?=[A-Z])/).map((i) => i.toLowerCase()).join(t), createOptionAPI = (e, t) => {
  const i = {};
  return forin(t, (a) => {
    i[a] = {
      get: () => e.getState().options[a],
      set: (n) => {
        e.dispatch(`SET_${fromCamels(a, "_").toUpperCase()}`, {
          value: n
        });
      }
    };
  }), i;
}, createOptionActions = (e) => (t, i, a) => {
  const n = {};
  return forin(e, (l) => {
    const r = fromCamels(l, "_").toUpperCase();
    n[`SET_${r}`] = (s) => {
      try {
        a.options[l] = s.value;
      } catch {
      }
      t(`DID_SET_${r}`, { value: a.options[l] });
    };
  }), n;
}, createOptionQueries = (e) => (t) => {
  const i = {};
  return forin(e, (a) => {
    i[`GET_${fromCamels(a, "_").toUpperCase()}`] = (n) => t.options[a];
  }), i;
}, InteractionMethod = {
  API: 1,
  DROP: 2,
  BROWSE: 3,
  PASTE: 4,
  NONE: 5
}, getUniqueId = () => Math.random().toString(36).substring(2, 11), arrayRemove = (e, t) => e.splice(t, 1), run = (e, t) => {
  t ? e() : document.hidden ? Promise.resolve(1).then(e) : setTimeout(e, 0);
}, on = () => {
  const e = [], t = (a, n) => {
    arrayRemove(
      e,
      e.findIndex((l) => l.event === a && (l.cb === n || !n))
    );
  }, i = (a, n, l) => {
    e.filter((r) => r.event === a).map((r) => r.cb).forEach((r) => run(() => r(...n), l));
  };
  return {
    fireSync: (a, ...n) => {
      i(a, n, !0);
    },
    fire: (a, ...n) => {
      i(a, n, !1);
    },
    on: (a, n) => {
      e.push({ event: a, cb: n });
    },
    onOnce: (a, n) => {
      e.push({
        event: a,
        cb: (...l) => {
          t(a, n), n(...l);
        }
      });
    },
    off: t
  };
}, copyObjectPropertiesToObject = (e, t, i) => {
  Object.getOwnPropertyNames(e).filter((a) => !i.includes(a)).forEach(
    (a) => Object.defineProperty(t, a, Object.getOwnPropertyDescriptor(e, a))
  );
}, PRIVATE = [
  "fire",
  "process",
  "revert",
  "load",
  "on",
  "off",
  "onOnce",
  "retryLoad",
  "extend",
  "archive",
  "archived",
  "release",
  "released",
  "requestProcessing",
  "freeze"
], createItemAPI = (e) => {
  const t = {};
  return copyObjectPropertiesToObject(e, t, PRIVATE), t;
}, removeReleasedItems = (e) => {
  e.forEach((t, i) => {
    t.released && arrayRemove(e, i);
  });
}, ItemStatus = {
  INIT: 1,
  IDLE: 2,
  PROCESSING_QUEUED: 9,
  PROCESSING: 3,
  PROCESSING_COMPLETE: 5,
  PROCESSING_ERROR: 6,
  PROCESSING_REVERT_ERROR: 10,
  LOADING: 7,
  LOAD_ERROR: 8
}, FileOrigin = {
  INPUT: 1,
  LIMBO: 2,
  LOCAL: 3
}, getNonNumeric = (e) => /[^0-9]+/.exec(e), getDecimalSeparator = () => getNonNumeric(1.1.toLocaleString())[0], getThousandsSeparator = () => {
  const e = getDecimalSeparator(), t = 1e3.toLocaleString();
  return t !== "1000" ? getNonNumeric(t)[0] : e === "." ? "," : ".";
}, Type = {
  BOOLEAN: "boolean",
  INT: "int",
  NUMBER: "number",
  STRING: "string",
  ARRAY: "array",
  OBJECT: "object",
  FUNCTION: "function",
  ACTION: "action",
  SERVER_API: "serverapi",
  REGEX: "regex"
}, filters = [], applyFilterChain = (e, t, i) => new Promise((a, n) => {
  const l = filters.filter((s) => s.key === e).map((s) => s.cb);
  if (l.length === 0) {
    a(t);
    return;
  }
  const r = l.shift();
  l.reduce(
    // loop over promises passing value to next promise
    (s, o) => s.then((d) => o(d, i)),
    // call initial filter, will return a promise
    r(t, i)
    // all executed
  ).then((s) => a(s)).catch((s) => n(s));
}), applyFilters = (e, t, i) => filters.filter((a) => a.key === e).map((a) => a.cb(t, i)), addFilter = (e, t) => filters.push({ key: e, cb: t }), extendDefaultOptions = (e) => Object.assign(defaultOptions, e), getOptions = () => ({ ...defaultOptions }), setOptions = (e) => {
  forin(e, (t, i) => {
    defaultOptions[t] && (defaultOptions[t][0] = getValueByType(
      i,
      defaultOptions[t][0],
      defaultOptions[t][1]
    ));
  });
}, defaultOptions = {
  // the id to add to the root element
  id: [null, Type.STRING],
  // input field name to use
  name: ["filepond", Type.STRING],
  // disable the field
  disabled: [!1, Type.BOOLEAN],
  // classname to put on wrapper
  className: [null, Type.STRING],
  // is the field required
  required: [!1, Type.BOOLEAN],
  // Allow media capture when value is set
  captureMethod: [null, Type.STRING],
  // - "camera", "microphone" or "camcorder",
  // - Does not work with multiple on apple devices
  // - If set, acceptedFileTypes must be made to match with media wildcard "image/*", "audio/*" or "video/*"
  // sync `acceptedFileTypes` property with `accept` attribute
  allowSyncAcceptAttribute: [!0, Type.BOOLEAN],
  // Feature toggles
  allowDrop: [!0, Type.BOOLEAN],
  // Allow dropping of files
  allowBrowse: [!0, Type.BOOLEAN],
  // Allow browsing the file system
  allowPaste: [!0, Type.BOOLEAN],
  // Allow pasting files
  allowMultiple: [!1, Type.BOOLEAN],
  // Allow multiple files (disabled by default, as multiple attribute is also required on input to allow multiple)
  allowReplace: [!0, Type.BOOLEAN],
  // Allow dropping a file on other file to replace it (only works when multiple is set to false)
  allowRevert: [!0, Type.BOOLEAN],
  // Allows user to revert file upload
  allowRemove: [!0, Type.BOOLEAN],
  // Allow user to remove a file
  allowProcess: [!0, Type.BOOLEAN],
  // Allows user to process a file, when set to false, this removes the file upload button
  allowReorder: [!1, Type.BOOLEAN],
  // Allow reordering of files
  allowDirectoriesOnly: [!1, Type.BOOLEAN],
  // Allow only selecting directories with browse (no support for filtering dnd at this point)
  // Try store file if `server` not set
  storeAsFile: [!1, Type.BOOLEAN],
  // Revert mode
  forceRevert: [!1, Type.BOOLEAN],
  // Set to 'force' to require the file to be reverted before removal
  // Input requirements
  maxFiles: [null, Type.INT],
  // Max number of files
  checkValidity: [!1, Type.BOOLEAN],
  // Enables custom validity messages
  // Where to put file
  itemInsertLocationFreedom: [!0, Type.BOOLEAN],
  // Set to false to always add items to begin or end of list
  itemInsertLocation: ["before", Type.STRING],
  // Default index in list to add items that have been dropped at the top of the list
  itemInsertInterval: [75, Type.INT],
  // Drag 'n Drop related
  dropOnPage: [!1, Type.BOOLEAN],
  // Allow dropping of files anywhere on page (prevents browser from opening file if dropped outside of Up)
  dropOnElement: [!0, Type.BOOLEAN],
  // Drop needs to happen on element (set to false to also load drops outside of Up)
  dropValidation: [!1, Type.BOOLEAN],
  // Enable or disable validating files on drop
  ignoredFiles: [[".ds_store", "thumbs.db", "desktop.ini"], Type.ARRAY],
  // Upload related
  instantUpload: [!0, Type.BOOLEAN],
  // Should upload files immediately on drop
  maxParallelUploads: [2, Type.INT],
  // Maximum files to upload in parallel
  allowMinimumUploadDuration: [!0, Type.BOOLEAN],
  // if true uploads take at least 750 ms, this ensures the user sees the upload progress giving trust the upload actually happened
  // Chunks
  chunkUploads: [!1, Type.BOOLEAN],
  // Enable chunked uploads
  chunkForce: [!1, Type.BOOLEAN],
  // Force use of chunk uploads even for files smaller than chunk size
  chunkSize: [5e6, Type.INT],
  // Size of chunks (5MB default)
  chunkRetryDelays: [[500, 1e3, 3e3], Type.ARRAY],
  // Amount of times to retry upload of a chunk when it fails
  // The server api end points to use for uploading (see docs)
  server: [null, Type.SERVER_API],
  // File size calculations, can set to 1024, this is only used for display, properties use file size base 1000
  fileSizeBase: [1e3, Type.INT],
  // Labels and status messages
  labelFileSizeBytes: ["bytes", Type.STRING],
  labelFileSizeKilobytes: ["KB", Type.STRING],
  labelFileSizeMegabytes: ["MB", Type.STRING],
  labelFileSizeGigabytes: ["GB", Type.STRING],
  labelDecimalSeparator: [getDecimalSeparator(), Type.STRING],
  // Default is locale separator
  labelThousandsSeparator: [getThousandsSeparator(), Type.STRING],
  // Default is locale separator
  labelIdle: [
    'Drag & Drop your files or <span class="filepond--label-action">Browse</span>',
    Type.STRING
  ],
  labelInvalidField: ["Field contains invalid files", Type.STRING],
  labelFileWaitingForSize: ["Waiting for size", Type.STRING],
  labelFileSizeNotAvailable: ["Size not available", Type.STRING],
  labelFileCountSingular: ["file in list", Type.STRING],
  labelFileCountPlural: ["files in list", Type.STRING],
  labelFileLoading: ["Loading", Type.STRING],
  labelFileAdded: ["Added", Type.STRING],
  // assistive only
  labelFileLoadError: ["Error during load", Type.STRING],
  labelFileRemoved: ["Removed", Type.STRING],
  // assistive only
  labelFileRemoveError: ["Error during remove", Type.STRING],
  labelFileProcessing: ["Uploading", Type.STRING],
  labelFileProcessingComplete: ["Upload complete", Type.STRING],
  labelFileProcessingAborted: ["Upload cancelled", Type.STRING],
  labelFileProcessingError: ["Error during upload", Type.STRING],
  labelFileProcessingRevertError: ["Error during revert", Type.STRING],
  labelTapToCancel: ["tap to cancel", Type.STRING],
  labelTapToRetry: ["tap to retry", Type.STRING],
  labelTapToUndo: ["tap to undo", Type.STRING],
  labelButtonRemoveItem: ["Remove", Type.STRING],
  labelButtonAbortItemLoad: ["Abort", Type.STRING],
  labelButtonRetryItemLoad: ["Retry", Type.STRING],
  labelButtonAbortItemProcessing: ["Cancel", Type.STRING],
  labelButtonUndoItemProcessing: ["Undo", Type.STRING],
  labelButtonRetryItemProcessing: ["Retry", Type.STRING],
  labelButtonProcessItem: ["Upload", Type.STRING],
  // make sure width and height plus viewpox are even numbers so icons are nicely centered
  iconRemove: [
    '<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg"><path d="M11.586 13l-2.293 2.293a1 1 0 0 0 1.414 1.414L13 14.414l2.293 2.293a1 1 0 0 0 1.414-1.414L14.414 13l2.293-2.293a1 1 0 0 0-1.414-1.414L13 11.586l-2.293-2.293a1 1 0 0 0-1.414 1.414L11.586 13z" fill="currentColor" fill-rule="nonzero"/></svg>',
    Type.STRING
  ],
  iconProcess: [
    '<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg"><path d="M14 10.414v3.585a1 1 0 0 1-2 0v-3.585l-1.293 1.293a1 1 0 0 1-1.414-1.415l3-3a1 1 0 0 1 1.414 0l3 3a1 1 0 0 1-1.414 1.415L14 10.414zM9 18a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2H9z" fill="currentColor" fill-rule="evenodd"/></svg>',
    Type.STRING
  ],
  iconRetry: [
    '<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg"><path d="M10.81 9.185l-.038.02A4.997 4.997 0 0 0 8 13.683a5 5 0 0 0 5 5 5 5 0 0 0 5-5 1 1 0 0 1 2 0A7 7 0 1 1 9.722 7.496l-.842-.21a.999.999 0 1 1 .484-1.94l3.23.806c.535.133.86.675.73 1.21l-.804 3.233a.997.997 0 0 1-1.21.73.997.997 0 0 1-.73-1.21l.23-.928v-.002z" fill="currentColor" fill-rule="nonzero"/></svg>',
    Type.STRING
  ],
  iconUndo: [
    '<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg"><path d="M9.185 10.81l.02-.038A4.997 4.997 0 0 1 13.683 8a5 5 0 0 1 5 5 5 5 0 0 1-5 5 1 1 0 0 0 0 2A7 7 0 1 0 7.496 9.722l-.21-.842a.999.999 0 1 0-1.94.484l.806 3.23c.133.535.675.86 1.21.73l3.233-.803a.997.997 0 0 0 .73-1.21.997.997 0 0 0-1.21-.73l-.928.23-.002-.001z" fill="currentColor" fill-rule="nonzero"/></svg>',
    Type.STRING
  ],
  iconDone: [
    '<svg width="26" height="26" viewBox="0 0 26 26" xmlns="http://www.w3.org/2000/svg"><path d="M18.293 9.293a1 1 0 0 1 1.414 1.414l-7.002 7a1 1 0 0 1-1.414 0l-3.998-4a1 1 0 1 1 1.414-1.414L12 15.586l6.294-6.293z" fill="currentColor" fill-rule="nonzero"/></svg>',
    Type.STRING
  ],
  // event handlers
  oninit: [null, Type.FUNCTION],
  onwarning: [null, Type.FUNCTION],
  onerror: [null, Type.FUNCTION],
  onactivatefile: [null, Type.FUNCTION],
  oninitfile: [null, Type.FUNCTION],
  onaddfilestart: [null, Type.FUNCTION],
  onaddfileprogress: [null, Type.FUNCTION],
  onaddfile: [null, Type.FUNCTION],
  onprocessfilestart: [null, Type.FUNCTION],
  onprocessfileprogress: [null, Type.FUNCTION],
  onprocessfileabort: [null, Type.FUNCTION],
  onprocessfilerevert: [null, Type.FUNCTION],
  onprocessfile: [null, Type.FUNCTION],
  onprocessfiles: [null, Type.FUNCTION],
  onremovefile: [null, Type.FUNCTION],
  onpreparefile: [null, Type.FUNCTION],
  onupdatefiles: [null, Type.FUNCTION],
  onreorderfiles: [null, Type.FUNCTION],
  // hooks
  beforeDropFile: [null, Type.FUNCTION],
  beforeAddFile: [null, Type.FUNCTION],
  beforeRemoveFile: [null, Type.FUNCTION],
  beforePrepareFile: [null, Type.FUNCTION],
  // styles
  stylePanelLayout: [null, Type.STRING],
  // null 'integrated', 'compact', 'circle'
  stylePanelAspectRatio: [null, Type.STRING],
  // null or '3:2' or 1
  styleItemPanelAspectRatio: [null, Type.STRING],
  styleButtonRemoveItemPosition: ["left", Type.STRING],
  styleButtonProcessItemPosition: ["right", Type.STRING],
  styleLoadIndicatorPosition: ["right", Type.STRING],
  styleProgressIndicatorPosition: ["right", Type.STRING],
  styleButtonRemoveItemAlign: [!1, Type.BOOLEAN],
  // custom initial files array
  files: [[], Type.ARRAY],
  // show support by displaying credits
  credits: [["https://pqina.nl/", "Powered by PQINA"], Type.ARRAY]
}, getItemByQuery = (e, t) => isEmpty$1(t) ? e[0] || null : isInt(t) ? e[t] || null : (typeof t == "object" && (t = t.id), e.find((i) => i.id === t) || null), getNumericAspectRatioFromString = (e) => {
  if (isEmpty$1(e))
    return e;
  if (/:/.test(e)) {
    const t = e.split(":");
    return t[1] / t[0];
  }
  return parseFloat(e);
}, getActiveItems = (e) => e.filter((t) => !t.archived), Status = {
  EMPTY: 0,
  IDLE: 1,
  // waiting
  ERROR: 2,
  // a file is in error state
  BUSY: 3,
  // busy processing or loading
  READY: 4
  // all files uploaded
};
let res = null;
const canUpdateFileInput = () => {
  if (res === null)
    try {
      const e = new DataTransfer();
      e.items.add(new File(["hello world"], "This_Works.txt"));
      const t = document.createElement("input");
      t.setAttribute("type", "file"), t.files = e.files, res = t.files.length === 1;
    } catch {
      res = !1;
    }
  return res;
}, ITEM_ERROR = [
  ItemStatus.LOAD_ERROR,
  ItemStatus.PROCESSING_ERROR,
  ItemStatus.PROCESSING_REVERT_ERROR
], ITEM_BUSY = [
  ItemStatus.LOADING,
  ItemStatus.PROCESSING,
  ItemStatus.PROCESSING_QUEUED,
  ItemStatus.INIT
], ITEM_READY = [ItemStatus.PROCESSING_COMPLETE], isItemInErrorState = (e) => ITEM_ERROR.includes(e.status), isItemInBusyState = (e) => ITEM_BUSY.includes(e.status), isItemInReadyState = (e) => ITEM_READY.includes(e.status), isAsync = (e) => isObject$1(e.options.server) && (isObject$1(e.options.server.process) || isFunction$2(e.options.server.process)), queries = (e) => ({
  GET_STATUS: () => {
    const t = getActiveItems(e.items), { EMPTY: i, ERROR: a, BUSY: n, IDLE: l, READY: r } = Status;
    return t.length === 0 ? i : t.some(isItemInErrorState) ? a : t.some(isItemInBusyState) ? n : t.some(isItemInReadyState) ? r : l;
  },
  GET_ITEM: (t) => getItemByQuery(e.items, t),
  GET_ACTIVE_ITEM: (t) => getItemByQuery(getActiveItems(e.items), t),
  GET_ACTIVE_ITEMS: () => getActiveItems(e.items),
  GET_ITEMS: () => e.items,
  GET_ITEM_NAME: (t) => {
    const i = getItemByQuery(e.items, t);
    return i ? i.filename : null;
  },
  GET_ITEM_SIZE: (t) => {
    const i = getItemByQuery(e.items, t);
    return i ? i.fileSize : null;
  },
  GET_STYLES: () => Object.keys(e.options).filter((t) => /^style/.test(t)).map((t) => ({
    name: t,
    value: e.options[t]
  })),
  GET_PANEL_ASPECT_RATIO: () => /circle/.test(e.options.stylePanelLayout) ? 1 : getNumericAspectRatioFromString(e.options.stylePanelAspectRatio),
  GET_ITEM_PANEL_ASPECT_RATIO: () => e.options.styleItemPanelAspectRatio,
  GET_ITEMS_BY_STATUS: (t) => getActiveItems(e.items).filter((i) => i.status === t),
  GET_TOTAL_ITEMS: () => getActiveItems(e.items).length,
  SHOULD_UPDATE_FILE_INPUT: () => e.options.storeAsFile && canUpdateFileInput() && !isAsync(e),
  IS_ASYNC: () => isAsync(e),
  GET_FILE_SIZE_LABELS: (t) => ({
    labelBytes: t("GET_LABEL_FILE_SIZE_BYTES") || void 0,
    labelKilobytes: t("GET_LABEL_FILE_SIZE_KILOBYTES") || void 0,
    labelMegabytes: t("GET_LABEL_FILE_SIZE_MEGABYTES") || void 0,
    labelGigabytes: t("GET_LABEL_FILE_SIZE_GIGABYTES") || void 0
  })
}), hasRoomForItem = (e) => {
  const t = getActiveItems(e.items).length;
  if (!e.options.allowMultiple)
    return t === 0;
  const i = e.options.maxFiles;
  return i === null || t < i;
}, limit = (e, t, i) => Math.max(Math.min(i, e), t), arrayInsert = (e, t, i) => e.splice(t, 0, i), insertItem = (e, t, i) => isEmpty$1(t) ? null : typeof i > "u" ? (e.push(t), t) : (i = limit(i, 0, e.length), arrayInsert(e, i, t), t), isBase64DataURI = (e) => /^\s*data:([a-z]+\/[a-z0-9-+.]+(;[a-z-]+=[a-z0-9-]+)?)?(;base64)?,([a-z0-9!$&',()*+;=\-._~:@\/?%\s]*)\s*$/i.test(
  e
), getFilenameFromURL = (e) => `${e}`.split("/").pop().split("?").shift(), getExtensionFromFilename = (e) => e.split(".").pop(), guesstimateExtension = (e) => {
  if (typeof e != "string")
    return "";
  const t = e.split("/").pop();
  return /svg/.test(t) ? "svg" : /zip|compressed/.test(t) ? "zip" : /plain/.test(t) ? "txt" : /msword/.test(t) ? "doc" : /[a-z]+/.test(t) ? t === "jpeg" ? "jpg" : t : "";
}, leftPad = (e, t = "") => (t + e).slice(-t.length), getDateString = (e = /* @__PURE__ */ new Date()) => `${e.getFullYear()}-${leftPad(e.getMonth() + 1, "00")}-${leftPad(
  e.getDate(),
  "00"
)}_${leftPad(e.getHours(), "00")}-${leftPad(e.getMinutes(), "00")}-${leftPad(
  e.getSeconds(),
  "00"
)}`, getFileFromBlob = (e, t, i = null, a = null) => {
  const n = typeof i == "string" ? e.slice(0, e.size, i) : e.slice(0, e.size, e.type);
  return n.lastModifiedDate = /* @__PURE__ */ new Date(), e._relativePath && (n._relativePath = e._relativePath), isString$1(t) || (t = getDateString()), t && a === null && getExtensionFromFilename(t) ? n.name = t : (a = a || guesstimateExtension(n.type), n.name = t + (a ? "." + a : "")), n;
}, getBlobBuilder = () => window.BlobBuilder = window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder || window.MSBlobBuilder, createBlob = (e, t) => {
  const i = getBlobBuilder();
  if (i) {
    const a = new i();
    return a.append(e), a.getBlob(t);
  }
  return new Blob([e], {
    type: t
  });
}, getBlobFromByteStringWithMimeType = (e, t) => {
  const i = new ArrayBuffer(e.length), a = new Uint8Array(i);
  for (let n = 0; n < e.length; n++)
    a[n] = e.charCodeAt(n);
  return createBlob(i, t);
}, getMimeTypeFromBase64DataURI = (e) => (/^data:(.+);/.exec(e) || [])[1] || null, getBase64DataFromBase64DataURI = (e) => e.split(",")[1].replace(/\s/g, ""), getByteStringFromBase64DataURI = (e) => atob(getBase64DataFromBase64DataURI(e)), getBlobFromBase64DataURI = (e) => {
  const t = getMimeTypeFromBase64DataURI(e), i = getByteStringFromBase64DataURI(e);
  return getBlobFromByteStringWithMimeType(i, t);
}, getFileFromBase64DataURI = (e, t, i) => getFileFromBlob(getBlobFromBase64DataURI(e), t, null, i), getFileNameFromHeader = (e) => {
  if (!/^content-disposition:/i.test(e)) return null;
  const t = e.split(/filename=|filename\*=.+''/).splice(1).map((i) => i.trim().replace(/^["']|[;"']{0,2}$/g, "")).filter((i) => i.length);
  return t.length ? decodeURI(t[t.length - 1]) : null;
}, getFileSizeFromHeader = (e) => {
  if (/content-length:/i.test(e)) {
    const t = e.match(/[0-9]+/)[0];
    return t ? parseInt(t, 10) : null;
  }
  return null;
}, getTranfserIdFromHeader = (e) => /x-content-transfer-id:/i.test(e) && (e.split(":")[1] || "").trim() || null, getFileInfoFromHeaders = (e) => {
  const t = {
    source: null,
    name: null,
    size: null
  }, i = e.split(`
`);
  for (let a of i) {
    const n = getFileNameFromHeader(a);
    if (n) {
      t.name = n;
      continue;
    }
    const l = getFileSizeFromHeader(a);
    if (l) {
      t.size = l;
      continue;
    }
    const r = getTranfserIdFromHeader(a);
    if (r) {
      t.source = r;
      continue;
    }
  }
  return t;
}, createFileLoader = (e) => {
  const t = {
    source: null,
    complete: !1,
    progress: 0,
    size: null,
    timestamp: null,
    duration: 0,
    request: null
  }, i = () => t.progress, a = () => {
    t.request && t.request.abort && t.request.abort();
  }, n = () => {
    const s = t.source;
    r.fire("init", s), s instanceof File ? r.fire("load", s) : s instanceof Blob ? r.fire("load", getFileFromBlob(s, s.name)) : isBase64DataURI(s) ? r.fire("load", getFileFromBase64DataURI(s)) : l(s);
  }, l = (s) => {
    if (!e) {
      r.fire("error", {
        type: "error",
        body: "Can't load URL",
        code: 400
      });
      return;
    }
    t.timestamp = Date.now(), t.request = e(
      s,
      (o) => {
        t.duration = Date.now() - t.timestamp, t.complete = !0, o instanceof Blob && (o = getFileFromBlob(o, o.name || getFilenameFromURL(s))), r.fire(
          "load",
          // if has received blob, we go with blob, if no response, we return null
          o instanceof Blob ? o : o ? o.body : null
        );
      },
      (o) => {
        r.fire(
          "error",
          typeof o == "string" ? {
            type: "error",
            code: 0,
            body: o
          } : o
        );
      },
      (o, d, c) => {
        if (c && (t.size = c), t.duration = Date.now() - t.timestamp, !o) {
          t.progress = null;
          return;
        }
        t.progress = d / c, r.fire("progress", t.progress);
      },
      () => {
        r.fire("abort");
      },
      (o) => {
        const d = getFileInfoFromHeaders(
          typeof o == "string" ? o : o.headers
        );
        r.fire("meta", {
          size: t.size || d.size,
          filename: d.name,
          source: d.source
        });
      }
    );
  }, r = {
    ...on(),
    setSource: (s) => t.source = s,
    getProgress: i,
    // file load progress
    abort: a,
    // abort file load
    load: n
    // start load
  };
  return r;
}, isGet = (e) => /GET|HEAD/.test(e), sendRequest = (e, t, i) => {
  const a = {
    onheaders: () => {
    },
    onprogress: () => {
    },
    onload: () => {
    },
    ontimeout: () => {
    },
    onerror: () => {
    },
    onabort: () => {
    },
    abort: () => {
      n = !0, r.abort();
    }
  };
  let n = !1, l = !1;
  i = {
    method: "POST",
    headers: {},
    withCredentials: !1,
    ...i
  }, t = encodeURI(t), isGet(i.method) && e && (t = `${t}${encodeURIComponent(typeof e == "string" ? e : JSON.stringify(e))}`);
  const r = new XMLHttpRequest(), s = isGet(i.method) ? r : r.upload;
  return s.onprogress = (o) => {
    n || a.onprogress(o.lengthComputable, o.loaded, o.total);
  }, r.onreadystatechange = () => {
    r.readyState < 2 || r.readyState === 4 && r.status === 0 || l || (l = !0, a.onheaders(r));
  }, r.onload = () => {
    r.status >= 200 && r.status < 300 ? a.onload(r) : a.onerror(r);
  }, r.onerror = () => a.onerror(r), r.onabort = () => {
    n = !0, a.onabort();
  }, r.ontimeout = () => a.ontimeout(r), r.open(i.method, t, !0), isInt(i.timeout) && (r.timeout = i.timeout), Object.keys(i.headers).forEach((o) => {
    const d = unescape(encodeURIComponent(i.headers[o]));
    r.setRequestHeader(o, d);
  }), i.responseType && (r.responseType = i.responseType), i.withCredentials && (r.withCredentials = !0), r.send(e), a;
}, createResponse = (e, t, i, a) => ({
  type: e,
  code: t,
  body: i,
  headers: a
}), createTimeoutResponse = (e) => (t) => {
  e(createResponse("error", 0, "Timeout", t.getAllResponseHeaders()));
}, hasQS = (e) => /\?/.test(e), buildURL = (...e) => {
  let t = "";
  return e.forEach((i) => {
    t += hasQS(t) && hasQS(i) ? i.replace(/\?/, "&") : i;
  }), t;
}, createFetchFunction = (e = "", t) => {
  if (typeof t == "function")
    return t;
  if (!t || !isString$1(t.url))
    return null;
  const i = t.onload || ((n) => n), a = t.onerror || ((n) => null);
  return (n, l, r, s, o, d) => {
    const c = sendRequest(n, buildURL(e, t.url), {
      ...t,
      responseType: "blob"
    });
    return c.onload = (u) => {
      const f = u.getAllResponseHeaders(), p = getFileInfoFromHeaders(f).name || getFilenameFromURL(n);
      l(
        createResponse(
          "load",
          u.status,
          t.method === "HEAD" ? null : getFileFromBlob(i(u.response), p),
          f
        )
      );
    }, c.onerror = (u) => {
      r(
        createResponse(
          "error",
          u.status,
          a(u.response) || u.statusText,
          u.getAllResponseHeaders()
        )
      );
    }, c.onheaders = (u) => {
      d(createResponse("headers", u.status, null, u.getAllResponseHeaders()));
    }, c.ontimeout = createTimeoutResponse(r), c.onprogress = s, c.onabort = o, c;
  };
}, ChunkStatus = {
  QUEUED: 0,
  COMPLETE: 1,
  PROCESSING: 2,
  ERROR: 3,
  WAITING: 4
}, processFileChunked = (e, t, i, a, n, l, r, s, o, d, c) => {
  const u = [], { chunkTransferId: f, chunkServer: p, chunkSize: m, chunkRetryDelays: h } = c, I = {
    serverId: f,
    aborted: !1
  }, b = t.ondata || ((F) => F), g = t.onload || ((F, D) => D === "HEAD" ? F.getResponseHeader("Upload-Offset") : F.response), E = t.onerror || ((F) => null), T = (F) => {
    const D = new FormData();
    isObject$1(n) && D.append(i, JSON.stringify(n));
    const R = typeof t.headers == "function" ? t.headers(a, n) : {
      ...t.headers,
      "Upload-Length": a.size
    }, A = {
      ...t,
      headers: R
    }, w = sendRequest(b(D), buildURL(e, t.url), A);
    w.onload = (z) => F(g(z, A.method)), w.onerror = (z) => r(
      createResponse(
        "error",
        z.status,
        E(z.response) || z.statusText,
        z.getAllResponseHeaders()
      )
    ), w.ontimeout = createTimeoutResponse(r);
  }, S = (F) => {
    const D = buildURL(e, p.url, I.serverId), A = {
      headers: typeof t.headers == "function" ? t.headers(I.serverId) : {
        ...t.headers
      },
      method: "HEAD"
    }, w = sendRequest(null, D, A);
    w.onload = (z) => F(g(z, A.method)), w.onerror = (z) => r(
      createResponse(
        "error",
        z.status,
        E(z.response) || z.statusText,
        z.getAllResponseHeaders()
      )
    ), w.ontimeout = createTimeoutResponse(r);
  }, L = Math.floor(a.size / m);
  for (let F = 0; F <= L; F++) {
    const D = F * m, R = a.slice(D, D + m, "application/offset+octet-stream");
    u[F] = {
      index: F,
      size: R.size,
      offset: D,
      data: R,
      file: a,
      progress: 0,
      retries: [...h],
      status: ChunkStatus.QUEUED,
      error: null,
      request: null,
      timeout: null
    };
  }
  const M = () => l(I.serverId), y = (F) => F.status === ChunkStatus.QUEUED || F.status === ChunkStatus.ERROR, x = (F) => {
    if (I.aborted) return;
    if (F = F || u.find(y), !F) {
      u.every((k) => k.status === ChunkStatus.COMPLETE) && M();
      return;
    }
    F.status = ChunkStatus.PROCESSING, F.progress = null;
    const D = p.ondata || ((k) => k), R = p.onerror || ((k) => null), A = buildURL(e, p.url, I.serverId), w = typeof p.headers == "function" ? p.headers(F) : {
      ...p.headers,
      "Content-Type": "application/offset+octet-stream",
      "Upload-Offset": F.offset,
      "Upload-Length": a.size,
      "Upload-Name": a.name
    }, z = F.request = sendRequest(D(F.data), A, {
      ...p,
      headers: w
    });
    z.onload = () => {
      F.status = ChunkStatus.COMPLETE, F.request = null, O();
    }, z.onprogress = (k, V, H) => {
      F.progress = k ? V : null, P();
    }, z.onerror = (k) => {
      F.status = ChunkStatus.ERROR, F.request = null, F.error = R(k.response) || k.statusText, v(F) || r(
        createResponse(
          "error",
          k.status,
          R(k.response) || k.statusText,
          k.getAllResponseHeaders()
        )
      );
    }, z.ontimeout = (k) => {
      F.status = ChunkStatus.ERROR, F.request = null, v(F) || createTimeoutResponse(r)(k);
    }, z.onabort = () => {
      F.status = ChunkStatus.QUEUED, F.request = null, o();
    };
  }, v = (F) => F.retries.length === 0 ? !1 : (F.status = ChunkStatus.WAITING, clearTimeout(F.timeout), F.timeout = setTimeout(() => {
    x(F);
  }, F.retries.shift()), !0), P = () => {
    const F = u.reduce((R, A) => R === null || A.progress === null ? null : R + A.progress, 0);
    if (F === null) return s(!1, 0, 0);
    const D = u.reduce((R, A) => R + A.size, 0);
    s(!0, F, D);
  }, O = () => {
    u.filter((D) => D.status === ChunkStatus.PROCESSING).length >= 1 || x();
  }, B = () => {
    u.forEach((F) => {
      clearTimeout(F.timeout), F.request && F.request.abort();
    });
  };
  return I.serverId ? S((F) => {
    I.aborted || (u.filter((D) => D.offset < F).forEach((D) => {
      D.status = ChunkStatus.COMPLETE, D.progress = D.size;
    }), O());
  }) : T((F) => {
    I.aborted || (d(F), I.serverId = F, O());
  }), {
    abort: () => {
      I.aborted = !0, B();
    }
  };
}, createFileProcessorFunction = (e, t, i, a) => (n, l, r, s, o, d, c) => {
  if (!n) return;
  const u = a.chunkUploads, f = u && n.size > a.chunkSize, p = u && (f || a.chunkForce);
  if (n instanceof Blob && p)
    return processFileChunked(
      e,
      t,
      i,
      n,
      l,
      r,
      s,
      o,
      d,
      c,
      a
    );
  const m = t.ondata || ((S) => S), h = t.onload || ((S) => S), I = t.onerror || ((S) => null), b = typeof t.headers == "function" ? t.headers(n, l) || {} : {
    ...t.headers
  }, g = {
    ...t,
    headers: b
  };
  var E = new FormData();
  isObject$1(l) && E.append(i, JSON.stringify(l)), (n instanceof Blob ? [{ name: null, file: n }] : n).forEach((S) => {
    E.append(
      i,
      S.file,
      S.name === null ? S.file.name : `${S.name}${S.file.name}`
    );
  });
  const T = sendRequest(m(E), buildURL(e, t.url), g);
  return T.onload = (S) => {
    r(createResponse("load", S.status, h(S.response), S.getAllResponseHeaders()));
  }, T.onerror = (S) => {
    s(
      createResponse(
        "error",
        S.status,
        I(S.response) || S.statusText,
        S.getAllResponseHeaders()
      )
    );
  }, T.ontimeout = createTimeoutResponse(s), T.onprogress = o, T.onabort = d, T;
}, createProcessorFunction = (e = "", t, i, a) => typeof t == "function" ? (...n) => t(i, ...n, a) : !t || !isString$1(t.url) ? null : createFileProcessorFunction(e, t, i, a), createRevertFunction = (e = "", t) => {
  if (typeof t == "function")
    return t;
  if (!t || !isString$1(t.url))
    return (n, l) => l();
  const i = t.onload || ((n) => n), a = t.onerror || ((n) => null);
  return (n, l, r) => {
    const s = sendRequest(
      n,
      e + t.url,
      t
      // contains method, headers and withCredentials properties
    );
    return s.onload = (o) => {
      l(
        createResponse(
          "load",
          o.status,
          i(o.response),
          o.getAllResponseHeaders()
        )
      );
    }, s.onerror = (o) => {
      r(
        createResponse(
          "error",
          o.status,
          a(o.response) || o.statusText,
          o.getAllResponseHeaders()
        )
      );
    }, s.ontimeout = createTimeoutResponse(r), s;
  };
}, getRandomNumber = (e = 0, t = 1) => e + Math.random() * (t - e), createPerceivedPerformanceUpdater = (e, t = 1e3, i = 0, a = 25, n = 250) => {
  let l = null;
  const r = Date.now(), s = () => {
    let o = Date.now() - r, d = getRandomNumber(a, n);
    o + d > t && (d = o + d - t);
    let c = o / t;
    if (c >= 1 || document.hidden) {
      e(1);
      return;
    }
    e(c), l = setTimeout(s, d);
  };
  return t > 0 && s(), {
    clear: () => {
      clearTimeout(l);
    }
  };
}, createFileProcessor = (e, t) => {
  const i = {
    complete: !1,
    perceivedProgress: 0,
    perceivedPerformanceUpdater: null,
    progress: null,
    timestamp: null,
    perceivedDuration: 0,
    duration: 0,
    request: null,
    response: null
  }, { allowMinimumUploadDuration: a } = t, n = (c, u) => {
    const f = () => {
      i.duration === 0 || i.progress === null || d.fire("progress", d.getProgress());
    }, p = () => {
      i.complete = !0, d.fire("load-perceived", i.response.body);
    };
    d.fire("start"), i.timestamp = Date.now(), i.perceivedPerformanceUpdater = createPerceivedPerformanceUpdater(
      (m) => {
        i.perceivedProgress = m, i.perceivedDuration = Date.now() - i.timestamp, f(), i.response && i.perceivedProgress === 1 && !i.complete && p();
      },
      // random delay as in a list of files you start noticing
      // files uploading at the exact same speed
      a ? getRandomNumber(750, 1500) : 0
    ), i.request = e(
      // the file to process
      c,
      // the metadata to send along
      u,
      // callbacks (load, error, progress, abort, transfer)
      // load expects the body to be a server id if
      // you want to make use of revert
      (m) => {
        i.response = isObject$1(m) ? m : {
          type: "load",
          code: 200,
          body: `${m}`,
          headers: {}
        }, i.duration = Date.now() - i.timestamp, i.progress = 1, d.fire("load", i.response.body), (!a || a && i.perceivedProgress === 1) && p();
      },
      // error is expected to be an object with type, code, body
      (m) => {
        i.perceivedPerformanceUpdater.clear(), d.fire(
          "error",
          isObject$1(m) ? m : {
            type: "error",
            code: 0,
            body: `${m}`
          }
        );
      },
      // actual processing progress
      (m, h, I) => {
        i.duration = Date.now() - i.timestamp, i.progress = m ? h / I : null, f();
      },
      // abort does not expect a value
      () => {
        i.perceivedPerformanceUpdater.clear(), d.fire("abort", i.response ? i.response.body : null);
      },
      // register the id for this transfer
      (m) => {
        d.fire("transfer", m);
      }
    );
  }, l = () => {
    i.request && (i.perceivedPerformanceUpdater.clear(), i.request.abort && i.request.abort(), i.complete = !0);
  }, r = () => {
    l(), i.complete = !1, i.perceivedProgress = 0, i.progress = 0, i.timestamp = null, i.perceivedDuration = 0, i.duration = 0, i.request = null, i.response = null;
  }, s = a ? () => i.progress ? Math.min(i.progress, i.perceivedProgress) : null : () => i.progress || null, o = a ? () => Math.min(i.duration, i.perceivedDuration) : () => i.duration, d = {
    ...on(),
    process: n,
    // start processing file
    abort: l,
    // abort active process request
    getProgress: s,
    getDuration: o,
    reset: r
  };
  return d;
}, getFilenameWithoutExtension = (e) => e.substring(0, e.lastIndexOf(".")) || e, createFileStub = (e) => {
  let t = [e.name, e.size, e.type];
  return e instanceof Blob || isBase64DataURI(e) ? t[0] = e.name || getDateString() : isBase64DataURI(e) ? (t[1] = e.length, t[2] = getMimeTypeFromBase64DataURI(e)) : isString$1(e) && (t[0] = getFilenameFromURL(e), t[1] = 0, t[2] = "application/octet-stream"), {
    name: t[0],
    size: t[1],
    type: t[2]
  };
}, isFile = (e) => !!(e instanceof File || e instanceof Blob && e.name), deepCloneObject = (e) => {
  if (!isObject$1(e)) return e;
  const t = isArray$1(e) ? [] : {};
  for (const i in e) {
    if (!e.hasOwnProperty(i)) continue;
    const a = e[i];
    t[i] = a && isObject$1(a) ? deepCloneObject(a) : a;
  }
  return t;
}, createItem = (e = null, t = null, i = null) => {
  const a = getUniqueId(), n = {
    // is archived
    archived: !1,
    // if is frozen, no longer fires events
    frozen: !1,
    // removed from view
    released: !1,
    // original source
    source: null,
    // file model reference
    file: i,
    // id of file on server
    serverFileReference: t,
    // id of file transfer on server
    transferId: null,
    // is aborted
    processingAborted: !1,
    // current item status
    status: t ? ItemStatus.PROCESSING_COMPLETE : ItemStatus.INIT,
    // active processes
    activeLoader: null,
    activeProcessor: null
  };
  let l = null;
  const r = {}, s = (y) => n.status = y, o = (y, ...x) => {
    n.released || n.frozen || L.fire(y, ...x);
  }, d = () => getExtensionFromFilename(n.file.name), c = () => n.file.type, u = () => n.file.size, f = () => n.file, p = (y, x, v) => {
    if (n.source = y, L.fireSync("init"), n.file) {
      L.fireSync("load-skip");
      return;
    }
    n.file = createFileStub(y), x.on("init", () => {
      o("load-init");
    }), x.on("meta", (P) => {
      n.file.size = P.size, n.file.filename = P.filename, P.source && (e = FileOrigin.LIMBO, n.serverFileReference = P.source, n.status = ItemStatus.PROCESSING_COMPLETE), o("load-meta");
    }), x.on("progress", (P) => {
      s(ItemStatus.LOADING), o("load-progress", P);
    }), x.on("error", (P) => {
      s(ItemStatus.LOAD_ERROR), o("load-request-error", P);
    }), x.on("abort", () => {
      s(ItemStatus.INIT), o("load-abort");
    }), x.on("load", (P) => {
      n.activeLoader = null;
      const O = (F) => {
        n.file = isFile(F) ? F : n.file, e === FileOrigin.LIMBO && n.serverFileReference ? s(ItemStatus.PROCESSING_COMPLETE) : s(ItemStatus.IDLE), o("load");
      }, B = (F) => {
        n.file = P, o("load-meta"), s(ItemStatus.LOAD_ERROR), o("load-file-error", F);
      };
      if (n.serverFileReference) {
        O(P);
        return;
      }
      v(P, O, B);
    }), x.setSource(y), n.activeLoader = x, x.load();
  }, m = () => {
    n.activeLoader && n.activeLoader.load();
  }, h = () => {
    if (n.activeLoader) {
      n.activeLoader.abort();
      return;
    }
    s(ItemStatus.INIT), o("load-abort");
  }, I = (y, x) => {
    if (n.processingAborted) {
      n.processingAborted = !1;
      return;
    }
    if (s(ItemStatus.PROCESSING), l = null, !(n.file instanceof Blob)) {
      L.on("load", () => {
        I(y, x);
      });
      return;
    }
    y.on("load", (O) => {
      n.transferId = null, n.serverFileReference = O;
    }), y.on("transfer", (O) => {
      n.transferId = O;
    }), y.on("load-perceived", (O) => {
      n.activeProcessor = null, n.transferId = null, n.serverFileReference = O, s(ItemStatus.PROCESSING_COMPLETE), o("process-complete", O);
    }), y.on("start", () => {
      o("process-start");
    }), y.on("error", (O) => {
      n.activeProcessor = null, s(ItemStatus.PROCESSING_ERROR), o("process-error", O);
    }), y.on("abort", (O) => {
      n.activeProcessor = null, n.serverFileReference = O, s(ItemStatus.IDLE), o("process-abort"), l && l();
    }), y.on("progress", (O) => {
      o("process-progress", O);
    });
    const v = (O) => {
      n.archived || y.process(O, { ...r });
    }, P = console.error;
    x(n.file, v, P), n.activeProcessor = y;
  }, b = () => {
    n.processingAborted = !1, s(ItemStatus.PROCESSING_QUEUED);
  }, g = () => new Promise((y) => {
    if (!n.activeProcessor) {
      n.processingAborted = !0, s(ItemStatus.IDLE), o("process-abort"), y();
      return;
    }
    l = () => {
      y();
    }, n.activeProcessor.abort();
  }), E = (y, x) => new Promise((v, P) => {
    const O = n.serverFileReference !== null ? n.serverFileReference : n.transferId;
    if (O === null) {
      v();
      return;
    }
    y(
      O,
      () => {
        n.serverFileReference = null, n.transferId = null, v();
      },
      (B) => {
        if (!x) {
          v();
          return;
        }
        s(ItemStatus.PROCESSING_REVERT_ERROR), o("process-revert-error"), P(B);
      }
    ), s(ItemStatus.IDLE), o("process-revert");
  }), T = (y, x, v) => {
    const P = y.split("."), O = P[0], B = P.pop();
    let F = r;
    P.forEach((D) => F = F[D]), JSON.stringify(F[B]) !== JSON.stringify(x) && (F[B] = x, o("metadata-update", {
      key: O,
      value: r[O],
      silent: v
    }));
  }, L = {
    id: { get: () => a },
    origin: { get: () => e, set: (y) => e = y },
    serverId: { get: () => n.serverFileReference },
    transferId: { get: () => n.transferId },
    status: { get: () => n.status },
    filename: { get: () => n.file.name },
    filenameWithoutExtension: { get: () => getFilenameWithoutExtension(n.file.name) },
    fileExtension: { get: d },
    fileType: { get: c },
    fileSize: { get: u },
    file: { get: f },
    relativePath: { get: () => n.file._relativePath },
    source: { get: () => n.source },
    getMetadata: (y) => deepCloneObject(y ? r[y] : r),
    setMetadata: (y, x, v) => {
      if (isObject$1(y)) {
        const P = y;
        return Object.keys(P).forEach((O) => {
          T(O, P[O], x);
        }), y;
      }
      return T(y, x, v), x;
    },
    extend: (y, x) => M[y] = x,
    abortLoad: h,
    retryLoad: m,
    requestProcessing: b,
    abortProcessing: g,
    load: p,
    process: I,
    revert: E,
    ...on(),
    freeze: () => n.frozen = !0,
    release: () => n.released = !0,
    released: { get: () => n.released },
    archive: () => n.archived = !0,
    archived: { get: () => n.archived },
    // replace source and file object
    setFile: (y) => n.file = y
  }, M = createObject(L);
  return M;
}, getItemIndexByQuery = (e, t) => isEmpty$1(t) ? 0 : isString$1(t) ? e.findIndex((i) => i.id === t) : -1, getItemById = (e, t) => {
  const i = getItemIndexByQuery(e, t);
  if (!(i < 0))
    return e[i] || null;
}, fetchBlob = (e, t, i, a, n, l) => {
  const r = sendRequest(null, e, {
    method: "GET",
    responseType: "blob"
  });
  return r.onload = (s) => {
    const o = s.getAllResponseHeaders(), d = getFileInfoFromHeaders(o).name || getFilenameFromURL(e);
    t(createResponse("load", s.status, getFileFromBlob(s.response, d), o));
  }, r.onerror = (s) => {
    i(createResponse("error", s.status, s.statusText, s.getAllResponseHeaders()));
  }, r.onheaders = (s) => {
    l(createResponse("headers", s.status, null, s.getAllResponseHeaders()));
  }, r.ontimeout = createTimeoutResponse(i), r.onprogress = a, r.onabort = n, r;
}, getDomainFromURL = (e) => (e.indexOf("//") === 0 && (e = location.protocol + e), e.toLowerCase().replace("blob:", "").replace(/([a-z])?:\/\//, "$1").split("/")[0]), isExternalURL = (e) => (e.indexOf(":") > -1 || e.indexOf("//") > -1) && getDomainFromURL(location.href) !== getDomainFromURL(e), dynamicLabel = (e) => (...t) => isFunction$2(e) ? e(...t) : e, isMockItem = (e) => !isFile(e.file), listUpdated = (e, t) => {
  clearTimeout(t.listUpdateTimeout), t.listUpdateTimeout = setTimeout(() => {
    e("DID_UPDATE_ITEMS", { items: getActiveItems(t.items) });
  }, 0);
}, optionalPromise = (e, ...t) => new Promise((i) => {
  if (!e)
    return i(!0);
  const a = e(...t);
  if (a == null)
    return i(!0);
  if (typeof a == "boolean")
    return i(a);
  typeof a.then == "function" && a.then(i);
}), sortItems = (e, t) => {
  e.items.sort((i, a) => t(createItemAPI(i), createItemAPI(a)));
}, getItemByQueryFromState = (e, t) => ({
  query: i,
  success: a = () => {
  },
  failure: n = () => {
  },
  ...l
} = {}) => {
  const r = getItemByQuery(e.items, i);
  if (!r) {
    n({
      error: createResponse("error", 0, "Item not found"),
      file: null
    });
    return;
  }
  t(r, a, n, l || {});
}, actions = (e, t, i) => ({
  /**
   * Aborts all ongoing processes
   */
  ABORT_ALL: () => {
    getActiveItems(i.items).forEach((a) => {
      a.freeze(), a.abortLoad(), a.abortProcessing();
    });
  },
  /**
   * Sets initial files
   */
  DID_SET_FILES: ({ value: a = [] }) => {
    const n = a.map((r) => ({
      source: r.source ? r.source : r,
      options: r.options
    }));
    let l = getActiveItems(i.items);
    l.forEach((r) => {
      n.find((s) => s.source === r.source || s.source === r.file) || e("REMOVE_ITEM", { query: r, remove: !1 });
    }), l = getActiveItems(i.items), n.forEach((r, s) => {
      l.find((o) => o.source === r.source || o.file === r.source) || e("ADD_ITEM", {
        ...r,
        interactionMethod: InteractionMethod.NONE,
        index: s
      });
    });
  },
  DID_UPDATE_ITEM_METADATA: ({ id: a, action: n, change: l }) => {
    l.silent || (clearTimeout(i.itemUpdateTimeout), i.itemUpdateTimeout = setTimeout(() => {
      const r = getItemById(i.items, a);
      if (!t("IS_ASYNC")) {
        applyFilterChain("SHOULD_PREPARE_OUTPUT", !1, {
          item: r,
          query: t,
          action: n,
          change: l
        }).then((c) => {
          const u = t("GET_BEFORE_PREPARE_FILE");
          u && (c = u(r, c)), c && e(
            "REQUEST_PREPARE_OUTPUT",
            {
              query: a,
              item: r,
              success: (f) => {
                e("DID_PREPARE_OUTPUT", { id: a, file: f });
              }
            },
            !0
          );
        });
        return;
      }
      r.origin === FileOrigin.LOCAL && e("DID_LOAD_ITEM", {
        id: r.id,
        error: null,
        serverFileReference: r.source
      });
      const s = () => {
        setTimeout(() => {
          e("REQUEST_ITEM_PROCESSING", { query: a });
        }, 32);
      }, o = (c) => {
        r.revert(
          createRevertFunction(i.options.server.url, i.options.server.revert),
          t("GET_FORCE_REVERT")
        ).then(c ? s : () => {
        }).catch(() => {
        });
      }, d = (c) => {
        r.abortProcessing().then(c ? s : () => {
        });
      };
      if (r.status === ItemStatus.PROCESSING_COMPLETE)
        return o(i.options.instantUpload);
      if (r.status === ItemStatus.PROCESSING)
        return d(i.options.instantUpload);
      i.options.instantUpload && s();
    }, 0));
  },
  MOVE_ITEM: ({ query: a, index: n }) => {
    const l = getItemByQuery(i.items, a);
    if (!l) return;
    const r = i.items.indexOf(l);
    n = limit(n, 0, i.items.length - 1), r !== n && i.items.splice(n, 0, i.items.splice(r, 1)[0]);
  },
  SORT: ({ compare: a }) => {
    sortItems(i, a), e("DID_SORT_ITEMS", {
      items: t("GET_ACTIVE_ITEMS")
    });
  },
  ADD_ITEMS: ({ items: a, index: n, interactionMethod: l, success: r = () => {
  }, failure: s = () => {
  } }) => {
    let o = n;
    if (n === -1 || typeof n > "u") {
      const p = t("GET_ITEM_INSERT_LOCATION"), m = t("GET_TOTAL_ITEMS");
      o = p === "before" ? 0 : m;
    }
    const d = t("GET_IGNORED_FILES"), c = (p) => isFile(p) ? !d.includes(p.name.toLowerCase()) : !isEmpty$1(p), f = a.filter(c).map(
      (p) => new Promise((m, h) => {
        e("ADD_ITEM", {
          interactionMethod: l,
          source: p.source || p,
          success: m,
          failure: h,
          index: o++,
          options: p.options || {}
        });
      })
    );
    Promise.all(f).then(r).catch(s);
  },
  /**
   * @param source
   * @param index
   * @param interactionMethod
   */
  ADD_ITEM: ({
    source: a,
    index: n = -1,
    interactionMethod: l,
    success: r = () => {
    },
    failure: s = () => {
    },
    options: o = {}
  }) => {
    if (isEmpty$1(a)) {
      s({
        error: createResponse("error", 0, "No source"),
        file: null
      });
      return;
    }
    if (isFile(a) && i.options.ignoredFiles.includes(a.name.toLowerCase()))
      return;
    if (!hasRoomForItem(i)) {
      if (i.options.allowMultiple || !i.options.allowMultiple && !i.options.allowReplace) {
        const g = createResponse("warning", 0, "Max files");
        e("DID_THROW_MAX_FILES", {
          source: a,
          error: g
        }), s({ error: g, file: null });
        return;
      }
      const b = getActiveItems(i.items)[0];
      if (b.status === ItemStatus.PROCESSING_COMPLETE || b.status === ItemStatus.PROCESSING_REVERT_ERROR) {
        const g = t("GET_FORCE_REVERT");
        if (b.revert(
          createRevertFunction(i.options.server.url, i.options.server.revert),
          g
        ).then(() => {
          g && e("ADD_ITEM", {
            source: a,
            index: n,
            interactionMethod: l,
            success: r,
            failure: s,
            options: o
          });
        }).catch(() => {
        }), g) return;
      }
      e("REMOVE_ITEM", { query: b.id });
    }
    const d = o.type === "local" ? FileOrigin.LOCAL : o.type === "limbo" ? FileOrigin.LIMBO : FileOrigin.INPUT, c = createItem(
      // where did this file come from
      d,
      // an input file never has a server file reference
      d === FileOrigin.INPUT ? null : a,
      // file mock data, if defined
      o.file
    );
    Object.keys(o.metadata || {}).forEach((b) => {
      c.setMetadata(b, o.metadata[b]);
    }), applyFilters("DID_CREATE_ITEM", c, { query: t, dispatch: e });
    const u = t("GET_ITEM_INSERT_LOCATION");
    i.options.itemInsertLocationFreedom || (n = u === "before" ? -1 : i.items.length), insertItem(i.items, c, n), isFunction$2(u) && a && sortItems(i, u);
    const f = c.id;
    c.on("init", () => {
      e("DID_INIT_ITEM", { id: f });
    }), c.on("load-init", () => {
      e("DID_START_ITEM_LOAD", { id: f });
    }), c.on("load-meta", () => {
      e("DID_UPDATE_ITEM_META", { id: f });
    }), c.on("load-progress", (b) => {
      e("DID_UPDATE_ITEM_LOAD_PROGRESS", { id: f, progress: b });
    }), c.on("load-request-error", (b) => {
      const g = dynamicLabel(i.options.labelFileLoadError)(b);
      if (b.code >= 400 && b.code < 500) {
        e("DID_THROW_ITEM_INVALID", {
          id: f,
          error: b,
          status: {
            main: g,
            sub: `${b.code} (${b.body})`
          }
        }), s({ error: b, file: createItemAPI(c) });
        return;
      }
      e("DID_THROW_ITEM_LOAD_ERROR", {
        id: f,
        error: b,
        status: {
          main: g,
          sub: i.options.labelTapToRetry
        }
      });
    }), c.on("load-file-error", (b) => {
      e("DID_THROW_ITEM_INVALID", {
        id: f,
        error: b.status,
        status: b.status
      }), s({ error: b.status, file: createItemAPI(c) });
    }), c.on("load-abort", () => {
      e("REMOVE_ITEM", { query: f });
    }), c.on("load-skip", () => {
      c.on("metadata-update", (b) => {
        isFile(c.file) && e("DID_UPDATE_ITEM_METADATA", { id: f, change: b });
      }), e("COMPLETE_LOAD_ITEM", {
        query: f,
        item: c,
        data: {
          source: a,
          success: r
        }
      });
    }), c.on("load", () => {
      const b = (g) => {
        if (!g) {
          e("REMOVE_ITEM", {
            query: f
          });
          return;
        }
        c.on("metadata-update", (E) => {
          e("DID_UPDATE_ITEM_METADATA", { id: f, change: E });
        }), applyFilterChain("SHOULD_PREPARE_OUTPUT", !1, { item: c, query: t }).then(
          (E) => {
            const T = t("GET_BEFORE_PREPARE_FILE");
            T && (E = T(c, E));
            const S = () => {
              e("COMPLETE_LOAD_ITEM", {
                query: f,
                item: c,
                data: {
                  source: a,
                  success: r
                }
              }), listUpdated(e, i);
            };
            if (E) {
              e(
                "REQUEST_PREPARE_OUTPUT",
                {
                  query: f,
                  item: c,
                  success: (L) => {
                    e("DID_PREPARE_OUTPUT", { id: f, file: L }), S();
                  }
                },
                !0
              );
              return;
            }
            S();
          }
        );
      };
      applyFilterChain("DID_LOAD_ITEM", c, { query: t, dispatch: e }).then(() => {
        optionalPromise(t("GET_BEFORE_ADD_FILE"), createItemAPI(c)).then(
          b
        );
      }).catch((g) => {
        if (!g || !g.error || !g.status) return b(!1);
        e("DID_THROW_ITEM_INVALID", {
          id: f,
          error: g.error,
          status: g.status
        });
      });
    }), c.on("process-start", () => {
      e("DID_START_ITEM_PROCESSING", { id: f });
    }), c.on("process-progress", (b) => {
      e("DID_UPDATE_ITEM_PROCESS_PROGRESS", { id: f, progress: b });
    }), c.on("process-error", (b) => {
      e("DID_THROW_ITEM_PROCESSING_ERROR", {
        id: f,
        error: b,
        status: {
          main: dynamicLabel(i.options.labelFileProcessingError)(b),
          sub: i.options.labelTapToRetry
        }
      });
    }), c.on("process-revert-error", (b) => {
      e("DID_THROW_ITEM_PROCESSING_REVERT_ERROR", {
        id: f,
        error: b,
        status: {
          main: dynamicLabel(i.options.labelFileProcessingRevertError)(b),
          sub: i.options.labelTapToRetry
        }
      });
    }), c.on("process-complete", (b) => {
      e("DID_COMPLETE_ITEM_PROCESSING", {
        id: f,
        error: null,
        serverFileReference: b
      }), e("DID_DEFINE_VALUE", { id: f, value: b });
    }), c.on("process-abort", () => {
      e("DID_ABORT_ITEM_PROCESSING", { id: f });
    }), c.on("process-revert", () => {
      e("DID_REVERT_ITEM_PROCESSING", { id: f }), e("DID_DEFINE_VALUE", { id: f, value: null });
    }), e("DID_ADD_ITEM", { id: f, index: n, interactionMethod: l }), listUpdated(e, i);
    const { url: p, load: m, restore: h, fetch: I } = i.options.server || {};
    c.load(
      a,
      // this creates a function that loads the file based on the type of file (string, base64, blob, file) and location of file (local, remote, limbo)
      createFileLoader(
        d === FileOrigin.INPUT ? (
          // input, if is remote, see if should use custom fetch, else use default fetchBlob
          isString$1(a) && isExternalURL(a) && I ? createFetchFunction(p, I) : fetchBlob
        ) : (
          // limbo or local
          d === FileOrigin.LIMBO ? createFetchFunction(p, h) : createFetchFunction(p, m)
        )
        // local
      ),
      // called when the file is loaded so it can be piped through the filters
      (b, g, E) => {
        applyFilterChain("LOAD_FILE", b, { query: t }).then(g).catch(E);
      }
    );
  },
  REQUEST_PREPARE_OUTPUT: ({ item: a, success: n, failure: l = () => {
  } }) => {
    const r = {
      error: createResponse("error", 0, "Item not found"),
      file: null
    };
    if (a.archived) return l(r);
    applyFilterChain("PREPARE_OUTPUT", a.file, { query: t, item: a }).then((s) => {
      applyFilterChain("COMPLETE_PREPARE_OUTPUT", s, { query: t, item: a }).then((o) => {
        if (a.archived) return l(r);
        n(o);
      });
    });
  },
  COMPLETE_LOAD_ITEM: ({ item: a, data: n }) => {
    const { success: l, source: r } = n, s = t("GET_ITEM_INSERT_LOCATION");
    if (isFunction$2(s) && r && sortItems(i, s), e("DID_LOAD_ITEM", {
      id: a.id,
      error: null,
      serverFileReference: a.origin === FileOrigin.INPUT ? null : r
    }), l(createItemAPI(a)), a.origin === FileOrigin.LOCAL) {
      e("DID_LOAD_LOCAL_ITEM", { id: a.id });
      return;
    }
    if (a.origin === FileOrigin.LIMBO) {
      e("DID_COMPLETE_ITEM_PROCESSING", {
        id: a.id,
        error: null,
        serverFileReference: r
      }), e("DID_DEFINE_VALUE", {
        id: a.id,
        value: a.serverId || r
      });
      return;
    }
    t("IS_ASYNC") && i.options.instantUpload && e("REQUEST_ITEM_PROCESSING", { query: a.id });
  },
  RETRY_ITEM_LOAD: getItemByQueryFromState(i, (a) => {
    a.retryLoad();
  }),
  REQUEST_ITEM_PREPARE: getItemByQueryFromState(i, (a, n, l) => {
    e(
      "REQUEST_PREPARE_OUTPUT",
      {
        query: a.id,
        item: a,
        success: (r) => {
          e("DID_PREPARE_OUTPUT", { id: a.id, file: r }), n({
            file: a,
            output: r
          });
        },
        failure: l
      },
      !0
    );
  }),
  REQUEST_ITEM_PROCESSING: getItemByQueryFromState(i, (a, n, l) => {
    if (!// waiting for something
    (a.status === ItemStatus.IDLE || // processing went wrong earlier
    a.status === ItemStatus.PROCESSING_ERROR)) {
      const s = () => e("REQUEST_ITEM_PROCESSING", { query: a, success: n, failure: l }), o = () => document.hidden ? s() : setTimeout(s, 32);
      a.status === ItemStatus.PROCESSING_COMPLETE || a.status === ItemStatus.PROCESSING_REVERT_ERROR ? a.revert(
        createRevertFunction(i.options.server.url, i.options.server.revert),
        t("GET_FORCE_REVERT")
      ).then(o).catch(() => {
      }) : a.status === ItemStatus.PROCESSING && a.abortProcessing().then(o);
      return;
    }
    a.status !== ItemStatus.PROCESSING_QUEUED && (a.requestProcessing(), e("DID_REQUEST_ITEM_PROCESSING", { id: a.id }), e("PROCESS_ITEM", { query: a, success: n, failure: l }, !0));
  }),
  PROCESS_ITEM: getItemByQueryFromState(i, (a, n, l) => {
    const r = t("GET_MAX_PARALLEL_UPLOADS");
    if (t("GET_ITEMS_BY_STATUS", ItemStatus.PROCESSING).length === r) {
      i.processingQueue.push({
        id: a.id,
        success: n,
        failure: l
      });
      return;
    }
    if (a.status === ItemStatus.PROCESSING) return;
    const o = () => {
      const c = i.processingQueue.shift();
      if (!c) return;
      const { id: u, success: f, failure: p } = c, m = getItemByQuery(i.items, u);
      if (!m || m.archived) {
        o();
        return;
      }
      e("PROCESS_ITEM", { query: u, success: f, failure: p }, !0);
    };
    a.onOnce("process-complete", () => {
      n(createItemAPI(a)), o();
      const c = i.options.server;
      if (i.options.instantUpload && a.origin === FileOrigin.LOCAL && isFunction$2(c.remove)) {
        const p = () => {
        };
        a.origin = FileOrigin.LIMBO, i.options.server.remove(a.source, p, p);
      }
      t("GET_ITEMS_BY_STATUS", ItemStatus.PROCESSING_COMPLETE).length === i.items.length && e("DID_COMPLETE_ITEM_PROCESSING_ALL");
    }), a.onOnce("process-error", (c) => {
      l({ error: c, file: createItemAPI(a) }), o();
    });
    const d = i.options;
    a.process(
      createFileProcessor(
        createProcessorFunction(d.server.url, d.server.process, d.name, {
          chunkTransferId: a.transferId,
          chunkServer: d.server.patch,
          chunkUploads: d.chunkUploads,
          chunkForce: d.chunkForce,
          chunkSize: d.chunkSize,
          chunkRetryDelays: d.chunkRetryDelays
        }),
        {
          allowMinimumUploadDuration: t("GET_ALLOW_MINIMUM_UPLOAD_DURATION")
        }
      ),
      // called when the file is about to be processed so it can be piped through the transform filters
      (c, u, f) => {
        applyFilterChain("PREPARE_OUTPUT", c, { query: t, item: a }).then((p) => {
          e("DID_PREPARE_OUTPUT", { id: a.id, file: p }), u(p);
        }).catch(f);
      }
    );
  }),
  RETRY_ITEM_PROCESSING: getItemByQueryFromState(i, (a) => {
    e("REQUEST_ITEM_PROCESSING", { query: a });
  }),
  REQUEST_REMOVE_ITEM: getItemByQueryFromState(i, (a) => {
    optionalPromise(t("GET_BEFORE_REMOVE_FILE"), createItemAPI(a)).then((n) => {
      n && e("REMOVE_ITEM", { query: a });
    });
  }),
  RELEASE_ITEM: getItemByQueryFromState(i, (a) => {
    a.release();
  }),
  REMOVE_ITEM: getItemByQueryFromState(i, (a, n, l, r) => {
    const s = () => {
      const d = a.id;
      getItemById(i.items, d).archive(), e("DID_REMOVE_ITEM", { error: null, id: d, item: a }), listUpdated(e, i), n(createItemAPI(a));
    }, o = i.options.server;
    a.origin === FileOrigin.LOCAL && o && isFunction$2(o.remove) && r.remove !== !1 ? (e("DID_START_ITEM_REMOVE", { id: a.id }), o.remove(
      a.source,
      () => s(),
      (d) => {
        e("DID_THROW_ITEM_REMOVE_ERROR", {
          id: a.id,
          error: createResponse("error", 0, d, null),
          status: {
            main: dynamicLabel(i.options.labelFileRemoveError)(d),
            sub: i.options.labelTapToRetry
          }
        });
      }
    )) : ((r.revert && a.origin !== FileOrigin.LOCAL && a.serverId !== null || // if chunked uploads are enabled and we're uploading in chunks for this specific file
    // or if the file isn't big enough for chunked uploads but chunkForce is set then call
    // revert before removing from the view...
    i.options.chunkUploads && a.file.size > i.options.chunkSize || i.options.chunkUploads && i.options.chunkForce) && a.revert(
      createRevertFunction(i.options.server.url, i.options.server.revert),
      t("GET_FORCE_REVERT")
    ), s());
  }),
  ABORT_ITEM_LOAD: getItemByQueryFromState(i, (a) => {
    a.abortLoad();
  }),
  ABORT_ITEM_PROCESSING: getItemByQueryFromState(i, (a) => {
    if (a.serverId) {
      e("REVERT_ITEM_PROCESSING", { id: a.id });
      return;
    }
    a.abortProcessing().then(() => {
      i.options.instantUpload && e("REMOVE_ITEM", { query: a.id });
    });
  }),
  REQUEST_REVERT_ITEM_PROCESSING: getItemByQueryFromState(i, (a) => {
    if (!i.options.instantUpload) {
      e("REVERT_ITEM_PROCESSING", { query: a });
      return;
    }
    const n = (s) => {
      s && e("REVERT_ITEM_PROCESSING", { query: a });
    }, l = t("GET_BEFORE_REMOVE_FILE");
    if (!l)
      return n(!0);
    const r = l(createItemAPI(a));
    if (r == null)
      return n(!0);
    if (typeof r == "boolean")
      return n(r);
    typeof r.then == "function" && r.then(n);
  }),
  REVERT_ITEM_PROCESSING: getItemByQueryFromState(i, (a) => {
    a.revert(
      createRevertFunction(i.options.server.url, i.options.server.revert),
      t("GET_FORCE_REVERT")
    ).then(() => {
      (i.options.instantUpload || isMockItem(a)) && e("REMOVE_ITEM", { query: a.id });
    }).catch(() => {
    });
  }),
  SET_OPTIONS: ({ options: a }) => {
    const n = Object.keys(a), l = PrioritizedOptions.filter((s) => n.includes(s));
    [
      // add prioritized first if passed to options, else remove
      ...l,
      // prevent duplicate keys
      ...Object.keys(a).filter((s) => !l.includes(s))
    ].forEach((s) => {
      e(`SET_${fromCamels(s, "_").toUpperCase()}`, {
        value: a[s]
      });
    });
  }
}), PrioritizedOptions = [
  "server"
  // must be processed before "files"
], formatFilename = (e) => e, createElement$1 = (e) => document.createElement(e), text = (e, t) => {
  let i = e.childNodes[0];
  i ? t !== i.nodeValue && (i.nodeValue = t) : (i = document.createTextNode(t), e.appendChild(i));
}, polarToCartesian = (e, t, i, a) => {
  const n = (a % 360 - 90) * Math.PI / 180;
  return {
    x: e + i * Math.cos(n),
    y: t + i * Math.sin(n)
  };
}, describeArc = (e, t, i, a, n, l) => {
  const r = polarToCartesian(e, t, i, n), s = polarToCartesian(e, t, i, a);
  return ["M", r.x, r.y, "A", i, i, 0, l, 0, s.x, s.y].join(" ");
}, percentageArc = (e, t, i, a, n) => {
  let l = 1;
  return n > a && n - a <= 0.5 && (l = 0), a > n && a - n >= 0.5 && (l = 0), describeArc(
    e,
    t,
    i,
    Math.min(0.9999, a) * 360,
    Math.min(0.9999, n) * 360,
    l
  );
}, create = ({ root: e, props: t }) => {
  t.spin = !1, t.progress = 0, t.opacity = 0;
  const i = createElement("svg");
  e.ref.path = createElement("path", {
    "stroke-width": 2,
    "stroke-linecap": "round"
  }), i.appendChild(e.ref.path), e.ref.svg = i, e.appendChild(i);
}, write = ({ root: e, props: t }) => {
  if (t.opacity === 0)
    return;
  t.align && (e.element.dataset.align = t.align);
  const i = parseInt(attr(e.ref.path, "stroke-width"), 10), a = e.rect.element.width * 0.5;
  let n = 0, l = 0;
  t.spin ? (n = 0, l = 0.5) : (n = 0, l = t.progress);
  const r = percentageArc(a, a, a - i, n, l);
  attr(e.ref.path, "d", r), attr(e.ref.path, "stroke-opacity", t.spin || t.progress > 0 ? 1 : 0);
}, progressIndicator = createView({
  tag: "div",
  name: "progress-indicator",
  ignoreRectUpdate: !0,
  ignoreRect: !0,
  create,
  write,
  mixins: {
    apis: ["progress", "spin", "align"],
    styles: ["opacity"],
    animations: {
      opacity: { type: "tween", duration: 500 },
      progress: {
        type: "spring",
        stiffness: 0.95,
        damping: 0.65,
        mass: 10
      }
    }
  }
}), create$1 = ({ root: e, props: t }) => {
  e.element.innerHTML = (t.icon || "") + `<span>${t.label}</span>`, t.isDisabled = !1;
}, write$1 = ({ root: e, props: t }) => {
  const { isDisabled: i } = t, a = e.query("GET_DISABLED") || t.opacity === 0;
  a && !i ? (t.isDisabled = !0, attr(e.element, "disabled", "disabled")) : !a && i && (t.isDisabled = !1, e.element.removeAttribute("disabled"));
}, fileActionButton = createView({
  tag: "button",
  attributes: {
    type: "button"
  },
  ignoreRect: !0,
  ignoreRectUpdate: !0,
  name: "file-action-button",
  mixins: {
    apis: ["label"],
    styles: ["translateX", "translateY", "scaleX", "scaleY", "opacity"],
    animations: {
      scaleX: "spring",
      scaleY: "spring",
      translateX: "spring",
      translateY: "spring",
      opacity: { type: "tween", duration: 250 }
    },
    listeners: !0
  },
  create: create$1,
  write: write$1
}), toNaturalFileSize = (e, t = ".", i = 1e3, a = {}) => {
  const {
    labelBytes: n = "bytes",
    labelKilobytes: l = "KB",
    labelMegabytes: r = "MB",
    labelGigabytes: s = "GB"
  } = a;
  e = Math.round(Math.abs(e));
  const o = i, d = i * i, c = i * i * i;
  return e < o ? `${e} ${n}` : e < d ? `${Math.floor(e / o)} ${l}` : e < c ? `${removeDecimalsWhenZero(e / d, 1, t)} ${r}` : `${removeDecimalsWhenZero(e / c, 2, t)} ${s}`;
}, removeDecimalsWhenZero = (e, t, i) => e.toFixed(t).split(".").filter((a) => a !== "0").join(i), create$2 = ({ root: e, props: t }) => {
  const i = createElement$1("span");
  i.className = "filepond--file-info-main", attr(i, "aria-hidden", "true"), e.appendChild(i), e.ref.fileName = i;
  const a = createElement$1("span");
  a.className = "filepond--file-info-sub", e.appendChild(a), e.ref.fileSize = a, text(a, e.query("GET_LABEL_FILE_WAITING_FOR_SIZE")), text(i, formatFilename(e.query("GET_ITEM_NAME", t.id)));
}, updateFile = ({ root: e, props: t }) => {
  text(
    e.ref.fileSize,
    toNaturalFileSize(
      e.query("GET_ITEM_SIZE", t.id),
      ".",
      e.query("GET_FILE_SIZE_BASE"),
      e.query("GET_FILE_SIZE_LABELS", e.query)
    )
  ), text(e.ref.fileName, formatFilename(e.query("GET_ITEM_NAME", t.id)));
}, updateFileSizeOnError = ({ root: e, props: t }) => {
  if (isInt(e.query("GET_ITEM_SIZE", t.id))) {
    updateFile({ root: e, props: t });
    return;
  }
  text(e.ref.fileSize, e.query("GET_LABEL_FILE_SIZE_NOT_AVAILABLE"));
}, fileInfo = createView({
  name: "file-info",
  ignoreRect: !0,
  ignoreRectUpdate: !0,
  write: createRoute({
    DID_LOAD_ITEM: updateFile,
    DID_UPDATE_ITEM_META: updateFile,
    DID_THROW_ITEM_LOAD_ERROR: updateFileSizeOnError,
    DID_THROW_ITEM_INVALID: updateFileSizeOnError
  }),
  didCreateView: (e) => {
    applyFilters("CREATE_VIEW", { ...e, view: e });
  },
  create: create$2,
  mixins: {
    styles: ["translateX", "translateY"],
    animations: {
      translateX: "spring",
      translateY: "spring"
    }
  }
}), toPercentage = (e) => Math.round(e * 100), create$3 = ({ root: e }) => {
  const t = createElement$1("span");
  t.className = "filepond--file-status-main", e.appendChild(t), e.ref.main = t;
  const i = createElement$1("span");
  i.className = "filepond--file-status-sub", e.appendChild(i), e.ref.sub = i, didSetItemLoadProgress({ root: e, action: { progress: null } });
}, didSetItemLoadProgress = ({ root: e, action: t }) => {
  const i = t.progress === null ? e.query("GET_LABEL_FILE_LOADING") : `${e.query("GET_LABEL_FILE_LOADING")} ${toPercentage(t.progress)}%`;
  text(e.ref.main, i), text(e.ref.sub, e.query("GET_LABEL_TAP_TO_CANCEL"));
}, didSetItemProcessProgress = ({ root: e, action: t }) => {
  const i = t.progress === null ? e.query("GET_LABEL_FILE_PROCESSING") : `${e.query("GET_LABEL_FILE_PROCESSING")} ${toPercentage(t.progress)}%`;
  text(e.ref.main, i), text(e.ref.sub, e.query("GET_LABEL_TAP_TO_CANCEL"));
}, didRequestItemProcessing = ({ root: e }) => {
  text(e.ref.main, e.query("GET_LABEL_FILE_PROCESSING")), text(e.ref.sub, e.query("GET_LABEL_TAP_TO_CANCEL"));
}, didAbortItemProcessing = ({ root: e }) => {
  text(e.ref.main, e.query("GET_LABEL_FILE_PROCESSING_ABORTED")), text(e.ref.sub, e.query("GET_LABEL_TAP_TO_RETRY"));
}, didCompleteItemProcessing = ({ root: e }) => {
  text(e.ref.main, e.query("GET_LABEL_FILE_PROCESSING_COMPLETE")), text(e.ref.sub, e.query("GET_LABEL_TAP_TO_UNDO"));
}, clear = ({ root: e }) => {
  text(e.ref.main, ""), text(e.ref.sub, "");
}, error = ({ root: e, action: t }) => {
  text(e.ref.main, t.status.main), text(e.ref.sub, t.status.sub);
}, fileStatus = createView({
  name: "file-status",
  ignoreRect: !0,
  ignoreRectUpdate: !0,
  write: createRoute({
    DID_LOAD_ITEM: clear,
    DID_REVERT_ITEM_PROCESSING: clear,
    DID_REQUEST_ITEM_PROCESSING: didRequestItemProcessing,
    DID_ABORT_ITEM_PROCESSING: didAbortItemProcessing,
    DID_COMPLETE_ITEM_PROCESSING: didCompleteItemProcessing,
    DID_UPDATE_ITEM_PROCESS_PROGRESS: didSetItemProcessProgress,
    DID_UPDATE_ITEM_LOAD_PROGRESS: didSetItemLoadProgress,
    DID_THROW_ITEM_LOAD_ERROR: error,
    DID_THROW_ITEM_INVALID: error,
    DID_THROW_ITEM_PROCESSING_ERROR: error,
    DID_THROW_ITEM_PROCESSING_REVERT_ERROR: error,
    DID_THROW_ITEM_REMOVE_ERROR: error
  }),
  didCreateView: (e) => {
    applyFilters("CREATE_VIEW", { ...e, view: e });
  },
  create: create$3,
  mixins: {
    styles: ["translateX", "translateY", "opacity"],
    animations: {
      opacity: { type: "tween", duration: 250 },
      translateX: "spring",
      translateY: "spring"
    }
  }
}), Buttons = {
  AbortItemLoad: {
    label: "GET_LABEL_BUTTON_ABORT_ITEM_LOAD",
    action: "ABORT_ITEM_LOAD",
    className: "filepond--action-abort-item-load",
    align: "LOAD_INDICATOR_POSITION"
    // right
  },
  RetryItemLoad: {
    label: "GET_LABEL_BUTTON_RETRY_ITEM_LOAD",
    action: "RETRY_ITEM_LOAD",
    icon: "GET_ICON_RETRY",
    className: "filepond--action-retry-item-load",
    align: "BUTTON_PROCESS_ITEM_POSITION"
    // right
  },
  RemoveItem: {
    label: "GET_LABEL_BUTTON_REMOVE_ITEM",
    action: "REQUEST_REMOVE_ITEM",
    icon: "GET_ICON_REMOVE",
    className: "filepond--action-remove-item",
    align: "BUTTON_REMOVE_ITEM_POSITION"
    // left
  },
  ProcessItem: {
    label: "GET_LABEL_BUTTON_PROCESS_ITEM",
    action: "REQUEST_ITEM_PROCESSING",
    icon: "GET_ICON_PROCESS",
    className: "filepond--action-process-item",
    align: "BUTTON_PROCESS_ITEM_POSITION"
    // right
  },
  AbortItemProcessing: {
    label: "GET_LABEL_BUTTON_ABORT_ITEM_PROCESSING",
    action: "ABORT_ITEM_PROCESSING",
    className: "filepond--action-abort-item-processing",
    align: "BUTTON_PROCESS_ITEM_POSITION"
    // right
  },
  RetryItemProcessing: {
    label: "GET_LABEL_BUTTON_RETRY_ITEM_PROCESSING",
    action: "RETRY_ITEM_PROCESSING",
    icon: "GET_ICON_RETRY",
    className: "filepond--action-retry-item-processing",
    align: "BUTTON_PROCESS_ITEM_POSITION"
    // right
  },
  RevertItemProcessing: {
    label: "GET_LABEL_BUTTON_UNDO_ITEM_PROCESSING",
    action: "REQUEST_REVERT_ITEM_PROCESSING",
    icon: "GET_ICON_UNDO",
    className: "filepond--action-revert-item-processing",
    align: "BUTTON_PROCESS_ITEM_POSITION"
    // right
  }
}, ButtonKeys = [];
forin(Buttons, (e) => {
  ButtonKeys.push(e);
});
const calculateFileInfoOffset = (e) => {
  if (getRemoveIndicatorAligment(e) === "right") return 0;
  const t = e.ref.buttonRemoveItem.rect.element;
  return t.hidden ? null : t.width + t.left;
}, calculateButtonWidth = (e) => e.ref.buttonAbortItemLoad.rect.element.width, calculateFileVerticalCenterOffset = (e) => Math.floor(e.ref.buttonRemoveItem.rect.element.height / 4), calculateFileHorizontalCenterOffset = (e) => Math.floor(e.ref.buttonRemoveItem.rect.element.left / 2), getLoadIndicatorAlignment = (e) => e.query("GET_STYLE_LOAD_INDICATOR_POSITION"), getProcessIndicatorAlignment = (e) => e.query("GET_STYLE_PROGRESS_INDICATOR_POSITION"), getRemoveIndicatorAligment = (e) => e.query("GET_STYLE_BUTTON_REMOVE_ITEM_POSITION"), DefaultStyle = {
  buttonAbortItemLoad: { opacity: 0 },
  buttonRetryItemLoad: { opacity: 0 },
  buttonRemoveItem: { opacity: 0 },
  buttonProcessItem: { opacity: 0 },
  buttonAbortItemProcessing: { opacity: 0 },
  buttonRetryItemProcessing: { opacity: 0 },
  buttonRevertItemProcessing: { opacity: 0 },
  loadProgressIndicator: { opacity: 0, align: getLoadIndicatorAlignment },
  processProgressIndicator: { opacity: 0, align: getProcessIndicatorAlignment },
  processingCompleteIndicator: { opacity: 0, scaleX: 0.75, scaleY: 0.75 },
  info: { translateX: 0, translateY: 0, opacity: 0 },
  status: { translateX: 0, translateY: 0, opacity: 0 }
}, IdleStyle = {
  buttonRemoveItem: { opacity: 1 },
  buttonProcessItem: { opacity: 1 },
  info: { translateX: calculateFileInfoOffset },
  status: { translateX: calculateFileInfoOffset }
}, ProcessingStyle = {
  buttonAbortItemProcessing: { opacity: 1 },
  processProgressIndicator: { opacity: 1 },
  status: { opacity: 1 }
}, StyleMap = {
  DID_THROW_ITEM_INVALID: {
    buttonRemoveItem: { opacity: 1 },
    info: { translateX: calculateFileInfoOffset },
    status: { translateX: calculateFileInfoOffset, opacity: 1 }
  },
  DID_START_ITEM_LOAD: {
    buttonAbortItemLoad: { opacity: 1 },
    loadProgressIndicator: { opacity: 1 },
    status: { opacity: 1 }
  },
  DID_THROW_ITEM_LOAD_ERROR: {
    buttonRetryItemLoad: { opacity: 1 },
    buttonRemoveItem: { opacity: 1 },
    info: { translateX: calculateFileInfoOffset },
    status: { opacity: 1 }
  },
  DID_START_ITEM_REMOVE: {
    processProgressIndicator: { opacity: 1, align: getRemoveIndicatorAligment },
    info: { translateX: calculateFileInfoOffset },
    status: { opacity: 0 }
  },
  DID_THROW_ITEM_REMOVE_ERROR: {
    processProgressIndicator: { opacity: 0, align: getRemoveIndicatorAligment },
    buttonRemoveItem: { opacity: 1 },
    info: { translateX: calculateFileInfoOffset },
    status: { opacity: 1, translateX: calculateFileInfoOffset }
  },
  DID_LOAD_ITEM: IdleStyle,
  DID_LOAD_LOCAL_ITEM: {
    buttonRemoveItem: { opacity: 1 },
    info: { translateX: calculateFileInfoOffset },
    status: { translateX: calculateFileInfoOffset }
  },
  DID_START_ITEM_PROCESSING: ProcessingStyle,
  DID_REQUEST_ITEM_PROCESSING: ProcessingStyle,
  DID_UPDATE_ITEM_PROCESS_PROGRESS: ProcessingStyle,
  DID_COMPLETE_ITEM_PROCESSING: {
    buttonRevertItemProcessing: { opacity: 1 },
    info: { opacity: 1 },
    status: { opacity: 1 }
  },
  DID_THROW_ITEM_PROCESSING_ERROR: {
    buttonRemoveItem: { opacity: 1 },
    buttonRetryItemProcessing: { opacity: 1 },
    status: { opacity: 1 },
    info: { translateX: calculateFileInfoOffset }
  },
  DID_THROW_ITEM_PROCESSING_REVERT_ERROR: {
    buttonRevertItemProcessing: { opacity: 1 },
    status: { opacity: 1 },
    info: { opacity: 1 }
  },
  DID_ABORT_ITEM_PROCESSING: {
    buttonRemoveItem: { opacity: 1 },
    buttonProcessItem: { opacity: 1 },
    info: { translateX: calculateFileInfoOffset },
    status: { opacity: 1 }
  },
  DID_REVERT_ITEM_PROCESSING: IdleStyle
}, processingCompleteIndicatorView = createView({
  create: ({ root: e }) => {
    e.element.innerHTML = e.query("GET_ICON_DONE");
  },
  name: "processing-complete-indicator",
  ignoreRect: !0,
  mixins: {
    styles: ["scaleX", "scaleY", "opacity"],
    animations: {
      scaleX: "spring",
      scaleY: "spring",
      opacity: { type: "tween", duration: 250 }
    }
  }
}), create$4 = ({ root: e, props: t }) => {
  const i = Object.keys(Buttons).reduce((m, h) => (m[h] = { ...Buttons[h] }, m), {}), { id: a } = t, n = e.query("GET_ALLOW_REVERT"), l = e.query("GET_ALLOW_REMOVE"), r = e.query("GET_ALLOW_PROCESS"), s = e.query("GET_INSTANT_UPLOAD"), o = e.query("IS_ASYNC"), d = e.query("GET_STYLE_BUTTON_REMOVE_ITEM_ALIGN");
  let c;
  o ? r && !n ? c = (m) => !/RevertItemProcessing/.test(m) : !r && n ? c = (m) => !/ProcessItem|RetryItemProcessing|AbortItemProcessing/.test(m) : !r && !n && (c = (m) => !/Process/.test(m)) : c = (m) => !/Process/.test(m);
  const u = c ? ButtonKeys.filter(c) : ButtonKeys.concat();
  if (s && n && (i.RevertItemProcessing.label = "GET_LABEL_BUTTON_REMOVE_ITEM", i.RevertItemProcessing.icon = "GET_ICON_REMOVE"), o && !n) {
    const m = StyleMap.DID_COMPLETE_ITEM_PROCESSING;
    m.info.translateX = calculateFileHorizontalCenterOffset, m.info.translateY = calculateFileVerticalCenterOffset, m.status.translateY = calculateFileVerticalCenterOffset, m.processingCompleteIndicator = { opacity: 1, scaleX: 1, scaleY: 1 };
  }
  if (o && !r && ([
    "DID_START_ITEM_PROCESSING",
    "DID_REQUEST_ITEM_PROCESSING",
    "DID_UPDATE_ITEM_PROCESS_PROGRESS",
    "DID_THROW_ITEM_PROCESSING_ERROR"
  ].forEach((m) => {
    StyleMap[m].status.translateY = calculateFileVerticalCenterOffset;
  }), StyleMap.DID_THROW_ITEM_PROCESSING_ERROR.status.translateX = calculateButtonWidth), d && n) {
    i.RevertItemProcessing.align = "BUTTON_REMOVE_ITEM_POSITION";
    const m = StyleMap.DID_COMPLETE_ITEM_PROCESSING;
    m.info.translateX = calculateFileInfoOffset, m.status.translateY = calculateFileVerticalCenterOffset, m.processingCompleteIndicator = { opacity: 1, scaleX: 1, scaleY: 1 };
  }
  l || (i.RemoveItem.disabled = !0), forin(i, (m, h) => {
    const I = e.createChildView(fileActionButton, {
      label: e.query(h.label),
      icon: e.query(h.icon),
      opacity: 0
    });
    u.includes(m) && e.appendChildView(I), h.disabled && (I.element.setAttribute("disabled", "disabled"), I.element.setAttribute("hidden", "hidden")), I.element.dataset.align = e.query(`GET_STYLE_${h.align}`), I.element.classList.add(h.className), I.on("click", (b) => {
      b.stopPropagation(), !h.disabled && e.dispatch(h.action, { query: a });
    }), e.ref[`button${m}`] = I;
  }), e.ref.processingCompleteIndicator = e.appendChildView(
    e.createChildView(processingCompleteIndicatorView)
  ), e.ref.processingCompleteIndicator.element.dataset.align = e.query(
    "GET_STYLE_BUTTON_PROCESS_ITEM_POSITION"
  ), e.ref.info = e.appendChildView(e.createChildView(fileInfo, { id: a })), e.ref.status = e.appendChildView(e.createChildView(fileStatus, { id: a }));
  const f = e.appendChildView(
    e.createChildView(progressIndicator, {
      opacity: 0,
      align: e.query("GET_STYLE_LOAD_INDICATOR_POSITION")
    })
  );
  f.element.classList.add("filepond--load-indicator"), e.ref.loadProgressIndicator = f;
  const p = e.appendChildView(
    e.createChildView(progressIndicator, {
      opacity: 0,
      align: e.query("GET_STYLE_PROGRESS_INDICATOR_POSITION")
    })
  );
  p.element.classList.add("filepond--process-indicator"), e.ref.processProgressIndicator = p, e.ref.activeStyles = [];
}, write$2 = ({ root: e, actions: t, props: i }) => {
  route({ root: e, actions: t, props: i });
  let a = t.concat().filter((n) => /^DID_/.test(n.type)).reverse().find((n) => StyleMap[n.type]);
  if (a) {
    e.ref.activeStyles = [];
    const n = StyleMap[a.type];
    forin(DefaultStyle, (l, r) => {
      const s = e.ref[l];
      forin(r, (o, d) => {
        const c = n[l] && typeof n[l][o] < "u" ? n[l][o] : d;
        e.ref.activeStyles.push({ control: s, key: o, value: c });
      });
    });
  }
  e.ref.activeStyles.forEach(({ control: n, key: l, value: r }) => {
    n[l] = typeof r == "function" ? r(e) : r;
  });
}, route = createRoute({
  DID_SET_LABEL_BUTTON_ABORT_ITEM_PROCESSING: ({ root: e, action: t }) => {
    e.ref.buttonAbortItemProcessing.label = t.value;
  },
  DID_SET_LABEL_BUTTON_ABORT_ITEM_LOAD: ({ root: e, action: t }) => {
    e.ref.buttonAbortItemLoad.label = t.value;
  },
  DID_SET_LABEL_BUTTON_ABORT_ITEM_REMOVAL: ({ root: e, action: t }) => {
    e.ref.buttonAbortItemRemoval.label = t.value;
  },
  DID_REQUEST_ITEM_PROCESSING: ({ root: e }) => {
    e.ref.processProgressIndicator.spin = !0, e.ref.processProgressIndicator.progress = 0;
  },
  DID_START_ITEM_LOAD: ({ root: e }) => {
    e.ref.loadProgressIndicator.spin = !0, e.ref.loadProgressIndicator.progress = 0;
  },
  DID_START_ITEM_REMOVE: ({ root: e }) => {
    e.ref.processProgressIndicator.spin = !0, e.ref.processProgressIndicator.progress = 0;
  },
  DID_UPDATE_ITEM_LOAD_PROGRESS: ({ root: e, action: t }) => {
    e.ref.loadProgressIndicator.spin = !1, e.ref.loadProgressIndicator.progress = t.progress;
  },
  DID_UPDATE_ITEM_PROCESS_PROGRESS: ({ root: e, action: t }) => {
    e.ref.processProgressIndicator.spin = !1, e.ref.processProgressIndicator.progress = t.progress;
  }
}), file = createView({
  create: create$4,
  write: write$2,
  didCreateView: (e) => {
    applyFilters("CREATE_VIEW", { ...e, view: e });
  },
  name: "file"
}), create$5 = ({ root: e, props: t }) => {
  e.ref.fileName = createElement$1("legend"), e.appendChild(e.ref.fileName), e.ref.file = e.appendChildView(e.createChildView(file, { id: t.id })), e.ref.data = !1;
}, didLoadItem = ({ root: e, props: t }) => {
  text(e.ref.fileName, formatFilename(e.query("GET_ITEM_NAME", t.id)));
}, fileWrapper = createView({
  create: create$5,
  ignoreRect: !0,
  write: createRoute({
    DID_LOAD_ITEM: didLoadItem
  }),
  didCreateView: (e) => {
    applyFilters("CREATE_VIEW", { ...e, view: e });
  },
  tag: "fieldset",
  name: "file-wrapper"
}), PANEL_SPRING_PROPS = { type: "spring", damping: 0.6, mass: 7 }, create$6 = ({ root: e, props: t }) => {
  [
    {
      name: "top"
    },
    {
      name: "center",
      props: {
        translateY: null,
        scaleY: null
      },
      mixins: {
        animations: {
          scaleY: PANEL_SPRING_PROPS
        },
        styles: ["translateY", "scaleY"]
      }
    },
    {
      name: "bottom",
      props: {
        translateY: null
      },
      mixins: {
        animations: {
          translateY: PANEL_SPRING_PROPS
        },
        styles: ["translateY"]
      }
    }
  ].forEach((i) => {
    createSection(e, i, t.name);
  }), e.element.classList.add(`filepond--${t.name}`), e.ref.scalable = null;
}, createSection = (e, t, i) => {
  const a = createView({
    name: `panel-${t.name} filepond--${i}`,
    mixins: t.mixins,
    ignoreRectUpdate: !0
  }), n = e.createChildView(a, t.props);
  e.ref[t.name] = e.appendChildView(n);
}, write$3 = ({ root: e, props: t }) => {
  if ((e.ref.scalable === null || t.scalable !== e.ref.scalable) && (e.ref.scalable = isBoolean(t.scalable) ? t.scalable : !0, e.element.dataset.scalable = e.ref.scalable), !t.height) return;
  const i = e.ref.top.rect.element, a = e.ref.bottom.rect.element, n = Math.max(i.height + a.height, t.height);
  e.ref.center.translateY = i.height, e.ref.center.scaleY = (n - i.height - a.height) / 100, e.ref.bottom.translateY = n - a.height;
}, panel = createView({
  name: "panel",
  read: ({ root: e, props: t }) => t.heightCurrent = e.ref.bottom.translateY,
  write: write$3,
  create: create$6,
  ignoreRect: !0,
  mixins: {
    apis: ["height", "heightCurrent", "scalable"]
  }
}), createDragHelper = (e) => {
  const t = e.map((a) => a.id);
  let i;
  return {
    setIndex: (a) => {
      i = a;
    },
    getIndex: () => i,
    getItemIndex: (a) => t.indexOf(a.id)
  };
}, ITEM_TRANSLATE_SPRING = {
  type: "spring",
  stiffness: 0.75,
  damping: 0.45,
  mass: 10
}, ITEM_SCALE_SPRING = "spring", StateMap = {
  DID_START_ITEM_LOAD: "busy",
  DID_UPDATE_ITEM_LOAD_PROGRESS: "loading",
  DID_THROW_ITEM_INVALID: "load-invalid",
  DID_THROW_ITEM_LOAD_ERROR: "load-error",
  DID_LOAD_ITEM: "idle",
  DID_THROW_ITEM_REMOVE_ERROR: "remove-error",
  DID_START_ITEM_REMOVE: "busy",
  DID_START_ITEM_PROCESSING: "busy processing",
  DID_REQUEST_ITEM_PROCESSING: "busy processing",
  DID_UPDATE_ITEM_PROCESS_PROGRESS: "processing",
  DID_COMPLETE_ITEM_PROCESSING: "processing-complete",
  DID_THROW_ITEM_PROCESSING_ERROR: "processing-error",
  DID_THROW_ITEM_PROCESSING_REVERT_ERROR: "processing-revert-error",
  DID_ABORT_ITEM_PROCESSING: "cancelled",
  DID_REVERT_ITEM_PROCESSING: "idle"
}, create$7 = ({ root: e, props: t }) => {
  if (e.ref.handleClick = (a) => e.dispatch("DID_ACTIVATE_ITEM", { id: t.id }), e.element.id = `filepond--item-${t.id}`, e.element.addEventListener("click", e.ref.handleClick), e.ref.container = e.appendChildView(e.createChildView(fileWrapper, { id: t.id })), e.ref.panel = e.appendChildView(e.createChildView(panel, { name: "item-panel" })), e.ref.panel.height = null, t.markedForRemoval = !1, !e.query("GET_ALLOW_REORDER")) return;
  e.element.dataset.dragState = "idle";
  const i = (a) => {
    if (!a.isPrimary) return;
    let n = !1;
    const l = {
      x: a.pageX,
      y: a.pageY
    };
    t.dragOrigin = {
      x: e.translateX,
      y: e.translateY
    }, t.dragCenter = {
      x: a.offsetX,
      y: a.offsetY
    };
    const r = createDragHelper(e.query("GET_ACTIVE_ITEMS"));
    e.dispatch("DID_GRAB_ITEM", { id: t.id, dragState: r });
    const s = (u) => {
      if (!u.isPrimary) return;
      u.stopPropagation(), u.preventDefault(), t.dragOffset = {
        x: u.pageX - l.x,
        y: u.pageY - l.y
      }, t.dragOffset.x * t.dragOffset.x + t.dragOffset.y * t.dragOffset.y > 16 && !n && (n = !0, e.element.removeEventListener("click", e.ref.handleClick)), e.dispatch("DID_DRAG_ITEM", { id: t.id, dragState: r });
    }, o = (u) => {
      u.isPrimary && (t.dragOffset = {
        x: u.pageX - l.x,
        y: u.pageY - l.y
      }, c());
    }, d = () => {
      c();
    }, c = () => {
      document.removeEventListener("pointercancel", d), document.removeEventListener("pointermove", s), document.removeEventListener("pointerup", o), e.dispatch("DID_DROP_ITEM", { id: t.id, dragState: r }), n && setTimeout(() => e.element.addEventListener("click", e.ref.handleClick), 0);
    };
    document.addEventListener("pointercancel", d), document.addEventListener("pointermove", s), document.addEventListener("pointerup", o);
  };
  e.element.addEventListener("pointerdown", i);
}, route$1 = createRoute({
  DID_UPDATE_PANEL_HEIGHT: ({ root: e, action: t }) => {
    e.height = t.height;
  }
}), write$4 = createRoute(
  {
    DID_GRAB_ITEM: ({ root: e, props: t }) => {
      t.dragOrigin = {
        x: e.translateX,
        y: e.translateY
      };
    },
    DID_DRAG_ITEM: ({ root: e }) => {
      e.element.dataset.dragState = "drag";
    },
    DID_DROP_ITEM: ({ root: e, props: t }) => {
      t.dragOffset = null, t.dragOrigin = null, e.element.dataset.dragState = "drop";
    }
  },
  ({ root: e, actions: t, props: i, shouldOptimize: a }) => {
    e.element.dataset.dragState === "drop" && e.scaleX <= 1 && (e.element.dataset.dragState = "idle");
    let n = t.concat().filter((r) => /^DID_/.test(r.type)).reverse().find((r) => StateMap[r.type]);
    n && n.type !== i.currentState && (i.currentState = n.type, e.element.dataset.filepondItemState = StateMap[i.currentState] || "");
    const l = e.query("GET_ITEM_PANEL_ASPECT_RATIO") || e.query("GET_PANEL_ASPECT_RATIO");
    l ? a || (e.height = e.rect.element.width * l) : (route$1({ root: e, actions: t, props: i }), !e.height && e.ref.container.rect.element.height > 0 && (e.height = e.ref.container.rect.element.height)), a && (e.ref.panel.height = null), e.ref.panel.height = e.height;
  }
), item = createView({
  create: create$7,
  write: write$4,
  destroy: ({ root: e, props: t }) => {
    e.element.removeEventListener("click", e.ref.handleClick), e.dispatch("RELEASE_ITEM", { query: t.id });
  },
  tag: "li",
  name: "item",
  mixins: {
    apis: [
      "id",
      "interactionMethod",
      "markedForRemoval",
      "spawnDate",
      "dragCenter",
      "dragOrigin",
      "dragOffset"
    ],
    styles: ["translateX", "translateY", "scaleX", "scaleY", "opacity", "height"],
    animations: {
      scaleX: ITEM_SCALE_SPRING,
      scaleY: ITEM_SCALE_SPRING,
      translateX: ITEM_TRANSLATE_SPRING,
      translateY: ITEM_TRANSLATE_SPRING,
      opacity: { type: "tween", duration: 150 }
    }
  }
});
var getItemsPerRow = (e, t) => Math.max(1, Math.floor((e + 1) / t));
const getItemIndexByPosition = (e, t, i) => {
  if (!i) return;
  const a = e.rect.element.width, n = t.length;
  let l = null;
  if (n === 0 || i.top < t[0].rect.element.top) return -1;
  const s = t[0].rect.element, o = s.marginLeft + s.marginRight, d = s.width + o, c = getItemsPerRow(a, d);
  if (c === 1) {
    for (let p = 0; p < n; p++) {
      const m = t[p], h = m.rect.outer.top + m.rect.element.height * 0.5;
      if (i.top < h)
        return p;
    }
    return n;
  }
  const u = s.marginTop + s.marginBottom, f = s.height + u;
  for (let p = 0; p < n; p++) {
    const m = p % c, h = Math.floor(p / c), I = m * d, b = h * f, g = b - s.marginTop, E = I + d, T = b + f + s.marginBottom;
    if (i.top < T && i.top > g) {
      if (i.left < E)
        return p;
      p !== n - 1 ? l = p : l = null;
    }
  }
  return l !== null ? l : n;
}, dropAreaDimensions = {
  height: 0,
  width: 0,
  get getHeight() {
    return this.height;
  },
  set setHeight(e) {
    (this.height === 0 || e === 0) && (this.height = e);
  },
  get getWidth() {
    return this.width;
  },
  set setWidth(e) {
    (this.width === 0 || e === 0) && (this.width = e);
  }
}, create$8 = ({ root: e }) => {
  attr(e.element, "role", "list"), e.ref.lastItemSpanwDate = Date.now();
}, addItemView = ({ root: e, action: t }) => {
  const { id: i, index: a, interactionMethod: n } = t;
  e.ref.addIndex = a;
  const l = Date.now();
  let r = l, s = 1;
  if (n !== InteractionMethod.NONE) {
    s = 0;
    const o = e.query("GET_ITEM_INSERT_INTERVAL"), d = l - e.ref.lastItemSpanwDate;
    r = d < o ? l + (o - d) : l;
  }
  e.ref.lastItemSpanwDate = r, e.appendChildView(
    e.createChildView(
      // view type
      item,
      // props
      {
        spawnDate: r,
        id: i,
        opacity: s,
        interactionMethod: n
      }
    ),
    a
  );
}, moveItem = (e, t, i, a = 0, n = 1) => {
  e.dragOffset ? (e.translateX = null, e.translateY = null, e.translateX = e.dragOrigin.x + e.dragOffset.x, e.translateY = e.dragOrigin.y + e.dragOffset.y, e.scaleX = 1.025, e.scaleY = 1.025) : (e.translateX = t, e.translateY = i, Date.now() > e.spawnDate && (e.opacity === 0 && introItemView(e, t, i, a, n), e.scaleX = 1, e.scaleY = 1, e.opacity = 1));
}, introItemView = (e, t, i, a, n) => {
  e.interactionMethod === InteractionMethod.NONE ? (e.translateX = null, e.translateX = t, e.translateY = null, e.translateY = i) : e.interactionMethod === InteractionMethod.DROP ? (e.translateX = null, e.translateX = t - a * 20, e.translateY = null, e.translateY = i - n * 10, e.scaleX = 0.8, e.scaleY = 0.8) : e.interactionMethod === InteractionMethod.BROWSE ? (e.translateY = null, e.translateY = i - 30) : e.interactionMethod === InteractionMethod.API && (e.translateX = null, e.translateX = t - 30, e.translateY = null);
}, removeItemView = ({ root: e, action: t }) => {
  const { id: i } = t, a = e.childViews.find((n) => n.id === i);
  a && (a.scaleX = 0.9, a.scaleY = 0.9, a.opacity = 0, a.markedForRemoval = !0);
}, getItemHeight = (e) => e.rect.element.height + e.rect.element.marginBottom * 0.5 + e.rect.element.marginTop * 0.5, getItemWidth = (e) => e.rect.element.width + e.rect.element.marginLeft * 0.5 + e.rect.element.marginRight * 0.5, dragItem = ({ root: e, action: t }) => {
  const { id: i, dragState: a } = t, n = e.query("GET_ITEM", { id: i }), l = e.childViews.find((I) => I.id === i), r = e.childViews.length, s = a.getItemIndex(n);
  if (!l) return;
  const o = {
    x: l.dragOrigin.x + l.dragOffset.x + l.dragCenter.x,
    y: l.dragOrigin.y + l.dragOffset.y + l.dragCenter.y
  }, d = getItemHeight(l), c = getItemWidth(l);
  let u = Math.floor(e.rect.outer.width / c);
  u > r && (u = r);
  const f = Math.floor(r / u + 1);
  dropAreaDimensions.setHeight = d * f, dropAreaDimensions.setWidth = c * u;
  var p = {
    y: Math.floor(o.y / d),
    x: Math.floor(o.x / c),
    getGridIndex: function() {
      return o.y > dropAreaDimensions.getHeight || o.y < 0 || o.x > dropAreaDimensions.getWidth || o.x < 0 ? s : this.y * u + this.x;
    },
    getColIndex: function() {
      const b = e.query("GET_ACTIVE_ITEMS"), g = e.childViews.filter((P) => P.rect.element.height), E = b.map(
        (P) => g.find((O) => O.id === P.id)
      ), T = E.findIndex((P) => P === l), S = getItemHeight(l), L = E.length;
      let M = L, y = 0, x = 0, v = 0;
      for (let P = 0; P < L; P++)
        if (y = getItemHeight(E[P]), v = x, x = v + y, o.y < x) {
          if (T > P) {
            if (o.y < v + S) {
              M = P;
              break;
            }
            continue;
          }
          M = P;
          break;
        }
      return M;
    }
  };
  const m = u > 1 ? p.getGridIndex() : p.getColIndex();
  e.dispatch("MOVE_ITEM", { query: l, index: m });
  const h = a.getIndex();
  if (h === void 0 || h !== m) {
    if (a.setIndex(m), h === void 0) return;
    e.dispatch("DID_REORDER_ITEMS", {
      items: e.query("GET_ACTIVE_ITEMS"),
      origin: s,
      target: m
    });
  }
}, route$2 = createRoute({
  DID_ADD_ITEM: addItemView,
  DID_REMOVE_ITEM: removeItemView,
  DID_DRAG_ITEM: dragItem
}), write$5 = ({ root: e, props: t, actions: i, shouldOptimize: a }) => {
  route$2({ root: e, props: t, actions: i });
  const { dragCoordinates: n } = t, l = e.rect.element.width, r = e.childViews.filter((E) => E.rect.element.height), s = e.query("GET_ACTIVE_ITEMS").map((E) => r.find((T) => T.id === E.id)).filter((E) => E), o = n ? getItemIndexByPosition(e, s, n) : null, d = e.ref.addIndex || null;
  e.ref.addIndex = null;
  let c = 0, u = 0, f = 0;
  if (s.length === 0) return;
  const p = s[0].rect.element, m = p.marginTop + p.marginBottom, h = p.marginLeft + p.marginRight, I = p.width + h, b = p.height + m, g = getItemsPerRow(l, I);
  if (g === 1) {
    let E = 0, T = 0;
    s.forEach((S, L) => {
      if (o) {
        let x = L - o;
        x === -2 ? T = -m * 0.25 : x === -1 ? T = -m * 0.75 : x === 0 ? T = m * 0.75 : x === 1 ? T = m * 0.25 : T = 0;
      }
      a && (S.translateX = null, S.translateY = null), S.markedForRemoval || moveItem(S, 0, E + T);
      let y = (S.rect.element.height + m) * (S.markedForRemoval ? S.opacity : 1);
      E += y;
    });
  } else {
    let E = 0, T = 0;
    s.forEach((S, L) => {
      L === o && (c = 1), L === d && (f += 1), S.markedForRemoval && S.opacity < 0.5 && (u -= 1);
      const M = L + f + c + u, y = M % g, x = Math.floor(M / g), v = y * I, P = x * b, O = Math.sign(v - E), B = Math.sign(P - T);
      E = v, T = P, !S.markedForRemoval && (a && (S.translateX = null, S.translateY = null), moveItem(S, v, P, O, B));
    });
  }
}, filterSetItemActions = (e, t) => t.filter((i) => i.data && i.data.id ? e.id === i.data.id : !0), list = createView({
  create: create$8,
  write: write$5,
  tag: "ul",
  name: "list",
  didWriteView: ({ root: e }) => {
    e.childViews.filter((t) => t.markedForRemoval && t.opacity === 0 && t.resting).forEach((t) => {
      t._destroy(), e.removeChildView(t);
    });
  },
  filterFrameActionsForChild: filterSetItemActions,
  mixins: {
    apis: ["dragCoordinates"]
  }
}), create$9 = ({ root: e, props: t }) => {
  e.ref.list = e.appendChildView(e.createChildView(list)), t.dragCoordinates = null, t.overflowing = !1;
}, storeDragCoordinates = ({ root: e, props: t, action: i }) => {
  e.query("GET_ITEM_INSERT_LOCATION_FREEDOM") && (t.dragCoordinates = {
    left: i.position.scopeLeft - e.ref.list.rect.element.left,
    top: i.position.scopeTop - (e.rect.outer.top + e.rect.element.marginTop + e.rect.element.scrollTop)
  });
}, clearDragCoordinates = ({ props: e }) => {
  e.dragCoordinates = null;
}, route$3 = createRoute({
  DID_DRAG: storeDragCoordinates,
  DID_END_DRAG: clearDragCoordinates
}), write$6 = ({ root: e, props: t, actions: i }) => {
  if (route$3({ root: e, props: t, actions: i }), e.ref.list.dragCoordinates = t.dragCoordinates, t.overflowing && !t.overflow && (t.overflowing = !1, e.element.dataset.state = "", e.height = null), t.overflow) {
    const a = Math.round(t.overflow);
    a !== e.height && (t.overflowing = !0, e.element.dataset.state = "overflow", e.height = a);
  }
}, listScroller = createView({
  create: create$9,
  write: write$6,
  name: "list-scroller",
  mixins: {
    apis: ["overflow", "dragCoordinates"],
    styles: ["height", "translateY"],
    animations: {
      translateY: "spring"
    }
  }
}), attrToggle = (e, t, i, a = "") => {
  i ? attr(e, t, a) : e.removeAttribute(t);
}, resetFileInput = (e) => {
  if (!(!e || e.value === "")) {
    try {
      e.value = "";
    } catch {
    }
    if (e.value) {
      const t = createElement$1("form"), i = e.parentNode, a = e.nextSibling;
      t.appendChild(e), t.reset(), a ? i.insertBefore(e, a) : i.appendChild(e);
    }
  }
}, create$a = ({ root: e, props: t }) => {
  e.element.id = `filepond--browser-${t.id}`, attr(e.element, "name", e.query("GET_NAME")), attr(e.element, "aria-controls", `filepond--assistant-${t.id}`), attr(e.element, "aria-labelledby", `filepond--drop-label-${t.id}`), setAcceptedFileTypes({ root: e, action: { value: e.query("GET_ACCEPTED_FILE_TYPES") } }), toggleAllowMultiple({ root: e, action: { value: e.query("GET_ALLOW_MULTIPLE") } }), toggleDirectoryFilter({ root: e, action: { value: e.query("GET_ALLOW_DIRECTORIES_ONLY") } }), toggleDisabled({ root: e }), toggleRequired({ root: e, action: { value: e.query("GET_REQUIRED") } }), setCaptureMethod({ root: e, action: { value: e.query("GET_CAPTURE_METHOD") } }), e.ref.handleChange = (i) => {
    if (!e.element.value)
      return;
    const a = Array.from(e.element.files).map((n) => (n._relativePath = n.webkitRelativePath, n));
    setTimeout(() => {
      t.onload(a), resetFileInput(e.element);
    }, 250);
  }, e.element.addEventListener("change", e.ref.handleChange);
}, setAcceptedFileTypes = ({ root: e, action: t }) => {
  e.query("GET_ALLOW_SYNC_ACCEPT_ATTRIBUTE") && attrToggle(e.element, "accept", !!t.value, t.value ? t.value.join(",") : "");
}, toggleAllowMultiple = ({ root: e, action: t }) => {
  attrToggle(e.element, "multiple", t.value);
}, toggleDirectoryFilter = ({ root: e, action: t }) => {
  attrToggle(e.element, "webkitdirectory", t.value);
}, toggleDisabled = ({ root: e }) => {
  const t = e.query("GET_DISABLED"), i = e.query("GET_ALLOW_BROWSE"), a = t || !i;
  attrToggle(e.element, "disabled", a);
}, toggleRequired = ({ root: e, action: t }) => {
  t.value ? e.query("GET_TOTAL_ITEMS") === 0 && attrToggle(e.element, "required", !0) : attrToggle(e.element, "required", !1);
}, setCaptureMethod = ({ root: e, action: t }) => {
  attrToggle(e.element, "capture", !!t.value, t.value === !0 ? "" : t.value);
}, updateRequiredStatus = ({ root: e }) => {
  const { element: t } = e;
  e.query("GET_TOTAL_ITEMS") > 0 ? (attrToggle(t, "required", !1), attrToggle(t, "name", !1)) : (attrToggle(t, "name", !0, e.query("GET_NAME")), e.query("GET_CHECK_VALIDITY") && t.setCustomValidity(""), e.query("GET_REQUIRED") && attrToggle(t, "required", !0));
}, updateFieldValidityStatus = ({ root: e }) => {
  e.query("GET_CHECK_VALIDITY") && e.element.setCustomValidity(e.query("GET_LABEL_INVALID_FIELD"));
}, browser = createView({
  tag: "input",
  name: "browser",
  ignoreRect: !0,
  ignoreRectUpdate: !0,
  attributes: {
    type: "file"
  },
  create: create$a,
  destroy: ({ root: e }) => {
    e.element.removeEventListener("change", e.ref.handleChange);
  },
  write: createRoute({
    DID_LOAD_ITEM: updateRequiredStatus,
    DID_REMOVE_ITEM: updateRequiredStatus,
    DID_THROW_ITEM_INVALID: updateFieldValidityStatus,
    DID_SET_DISABLED: toggleDisabled,
    DID_SET_ALLOW_BROWSE: toggleDisabled,
    DID_SET_ALLOW_DIRECTORIES_ONLY: toggleDirectoryFilter,
    DID_SET_ALLOW_MULTIPLE: toggleAllowMultiple,
    DID_SET_ACCEPTED_FILE_TYPES: setAcceptedFileTypes,
    DID_SET_CAPTURE_METHOD: setCaptureMethod,
    DID_SET_REQUIRED: toggleRequired
  })
}), Key = {
  ENTER: 13,
  SPACE: 32
}, create$b = ({ root: e, props: t }) => {
  const i = createElement$1("label");
  attr(i, "for", `filepond--browser-${t.id}`), attr(i, "id", `filepond--drop-label-${t.id}`), attr(i, "aria-hidden", "true"), e.ref.handleKeyDown = (a) => {
    (a.keyCode === Key.ENTER || a.keyCode === Key.SPACE) && (a.preventDefault(), e.ref.label.click());
  }, e.ref.handleClick = (a) => {
    a.target === i || i.contains(a.target) || e.ref.label.click();
  }, i.addEventListener("keydown", e.ref.handleKeyDown), e.element.addEventListener("click", e.ref.handleClick), updateLabelValue(i, t.caption), e.appendChild(i), e.ref.label = i;
}, updateLabelValue = (e, t) => {
  e.innerHTML = t;
  const i = e.querySelector(".filepond--label-action");
  return i && attr(i, "tabindex", "0"), t;
}, dropLabel = createView({
  name: "drop-label",
  ignoreRect: !0,
  create: create$b,
  destroy: ({ root: e }) => {
    e.ref.label.addEventListener("keydown", e.ref.handleKeyDown), e.element.removeEventListener("click", e.ref.handleClick);
  },
  write: createRoute({
    DID_SET_LABEL_IDLE: ({ root: e, action: t }) => {
      updateLabelValue(e.ref.label, t.value);
    }
  }),
  mixins: {
    styles: ["opacity", "translateX", "translateY"],
    animations: {
      opacity: { type: "tween", duration: 150 },
      translateX: "spring",
      translateY: "spring"
    }
  }
}), blob = createView({
  name: "drip-blob",
  ignoreRect: !0,
  mixins: {
    styles: ["translateX", "translateY", "scaleX", "scaleY", "opacity"],
    animations: {
      scaleX: "spring",
      scaleY: "spring",
      translateX: "spring",
      translateY: "spring",
      opacity: { type: "tween", duration: 250 }
    }
  }
}), addBlob = ({ root: e }) => {
  const t = e.rect.element.width * 0.5, i = e.rect.element.height * 0.5;
  e.ref.blob = e.appendChildView(
    e.createChildView(blob, {
      opacity: 0,
      scaleX: 2.5,
      scaleY: 2.5,
      translateX: t,
      translateY: i
    })
  );
}, moveBlob = ({ root: e, action: t }) => {
  if (!e.ref.blob) {
    addBlob({ root: e });
    return;
  }
  e.ref.blob.translateX = t.position.scopeLeft, e.ref.blob.translateY = t.position.scopeTop, e.ref.blob.scaleX = 1, e.ref.blob.scaleY = 1, e.ref.blob.opacity = 1;
}, hideBlob = ({ root: e }) => {
  e.ref.blob && (e.ref.blob.opacity = 0);
}, explodeBlob = ({ root: e }) => {
  e.ref.blob && (e.ref.blob.scaleX = 2.5, e.ref.blob.scaleY = 2.5, e.ref.blob.opacity = 0);
}, write$7 = ({ root: e, props: t, actions: i }) => {
  route$4({ root: e, props: t, actions: i });
  const { blob: a } = e.ref;
  i.length === 0 && a && a.opacity === 0 && (e.removeChildView(a), e.ref.blob = null);
}, route$4 = createRoute({
  DID_DRAG: moveBlob,
  DID_DROP: explodeBlob,
  DID_END_DRAG: hideBlob
}), drip = createView({
  ignoreRect: !0,
  ignoreRectUpdate: !0,
  name: "drip",
  write: write$7
}), setInputFiles = (e, t) => {
  try {
    const i = new DataTransfer();
    t.forEach((a) => {
      a instanceof File ? i.items.add(a) : i.items.add(
        new File([a], a.name, {
          type: a.type
        })
      );
    }), e.files = i.files;
  } catch {
    return !1;
  }
  return !0;
}, create$c = ({ root: e }) => e.ref.fields = {}, getField = (e, t) => e.ref.fields[t], syncFieldPositionsWithItems = (e) => {
  e.query("GET_ACTIVE_ITEMS").forEach((t) => {
    e.ref.fields[t.id] && e.element.appendChild(e.ref.fields[t.id]);
  });
}, didReorderItems = ({ root: e }) => syncFieldPositionsWithItems(e), didAddItem = ({ root: e, action: t }) => {
  const n = !(e.query("GET_ITEM", t.id).origin === FileOrigin.LOCAL) && e.query("SHOULD_UPDATE_FILE_INPUT"), l = createElement$1("input");
  l.type = n ? "file" : "hidden", l.name = e.query("GET_NAME"), l.disabled = e.query("GET_DISABLED"), e.ref.fields[t.id] = l, syncFieldPositionsWithItems(e);
}, didLoadItem$1 = ({ root: e, action: t }) => {
  const i = getField(e, t.id);
  if (!i || (t.serverFileReference !== null && (i.value = t.serverFileReference), !e.query("SHOULD_UPDATE_FILE_INPUT"))) return;
  const a = e.query("GET_ITEM", t.id);
  setInputFiles(i, [a.file]);
}, didPrepareOutput = ({ root: e, action: t }) => {
  e.query("SHOULD_UPDATE_FILE_INPUT") && setTimeout(() => {
    const i = getField(e, t.id);
    i && setInputFiles(i, [t.file]);
  }, 0);
}, didSetDisabled = ({ root: e }) => {
  e.element.disabled = e.query("GET_DISABLED");
}, didRemoveItem = ({ root: e, action: t }) => {
  const i = getField(e, t.id);
  i && (i.parentNode && i.parentNode.removeChild(i), delete e.ref.fields[t.id]);
}, didDefineValue = ({ root: e, action: t }) => {
  const i = getField(e, t.id);
  i && (t.value === null ? i.removeAttribute("value") : i.type != "file" && (i.value = t.value), syncFieldPositionsWithItems(e));
}, write$8 = createRoute({
  DID_SET_DISABLED: didSetDisabled,
  DID_ADD_ITEM: didAddItem,
  DID_LOAD_ITEM: didLoadItem$1,
  DID_REMOVE_ITEM: didRemoveItem,
  DID_DEFINE_VALUE: didDefineValue,
  DID_PREPARE_OUTPUT: didPrepareOutput,
  DID_REORDER_ITEMS: didReorderItems,
  DID_SORT_ITEMS: didReorderItems
}), data$1 = createView({
  tag: "fieldset",
  name: "data",
  create: create$c,
  write: write$8,
  ignoreRect: !0
}), getRootNode = (e) => "getRootNode" in e ? e.getRootNode() : document, images = ["jpg", "jpeg", "png", "gif", "bmp", "webp", "svg", "tiff"], text$1 = ["css", "csv", "html", "txt"], map$1 = {
  zip: "zip|compressed",
  epub: "application/epub+zip"
}, guesstimateMimeType = (e = "") => (e = e.toLowerCase(), images.includes(e) ? "image/" + (e === "jpg" ? "jpeg" : e === "svg" ? "svg+xml" : e) : text$1.includes(e) ? "text/" + e : map$1[e] || ""), requestDataTransferItems = (e) => new Promise((t, i) => {
  const a = getLinks(e);
  if (a.length && !hasFiles(e))
    return t(a);
  getFiles(e).then(t);
}), hasFiles = (e) => e.files ? e.files.length > 0 : !1, getFiles = (e) => new Promise((t, i) => {
  const a = (e.items ? Array.from(e.items) : []).filter((n) => isFileSystemItem(n)).map((n) => getFilesFromItem(n));
  if (!a.length) {
    t(e.files ? Array.from(e.files) : []);
    return;
  }
  Promise.all(a).then((n) => {
    const l = [];
    n.forEach((r) => {
      l.push.apply(l, r);
    }), t(
      l.filter((r) => r).map((r) => (r._relativePath || (r._relativePath = r.webkitRelativePath), r))
    );
  }).catch(console.error);
}), isFileSystemItem = (e) => {
  if (isEntry(e)) {
    const t = getAsEntry(e);
    if (t)
      return t.isFile || t.isDirectory;
  }
  return e.kind === "file";
}, getFilesFromItem = (e) => new Promise((t, i) => {
  if (isDirectoryEntry(e)) {
    getFilesInDirectory(getAsEntry(e)).then(t).catch(i);
    return;
  }
  t([e.getAsFile()]);
}), getFilesInDirectory = (e) => new Promise((t, i) => {
  const a = [];
  let n = 0, l = 0;
  const r = () => {
    l === 0 && n === 0 && t(a);
  }, s = (o) => {
    n++;
    const d = o.createReader(), c = () => {
      d.readEntries((u) => {
        if (u.length === 0) {
          n--, r();
          return;
        }
        u.forEach((f) => {
          f.isDirectory ? s(f) : (l++, f.file((p) => {
            const m = correctMissingFileType(p);
            f.fullPath && (m._relativePath = f.fullPath), a.push(m), l--, r();
          }));
        }), c();
      }, i);
    };
    c();
  };
  s(e);
}), correctMissingFileType = (e) => {
  if (e.type.length) return e;
  const t = e.lastModifiedDate, i = e.name, a = guesstimateMimeType(getExtensionFromFilename(e.name));
  return a.length && (e = e.slice(0, e.size, a), e.name = i, e.lastModifiedDate = t), e;
}, isDirectoryEntry = (e) => isEntry(e) && (getAsEntry(e) || {}).isDirectory, isEntry = (e) => "webkitGetAsEntry" in e, getAsEntry = (e) => e.webkitGetAsEntry(), getLinks = (e) => {
  let t = [];
  try {
    if (t = getLinksFromTransferMetaData(e), t.length)
      return t;
    t = getLinksFromTransferURLData(e);
  } catch {
  }
  return t;
}, getLinksFromTransferURLData = (e) => {
  let t = e.getData("url");
  return typeof t == "string" && t.length ? [t] : [];
}, getLinksFromTransferMetaData = (e) => {
  let t = e.getData("text/html");
  if (typeof t == "string" && t.length) {
    const i = t.match(/src\s*=\s*"(.+?)"/);
    if (i)
      return [i[1]];
  }
  return [];
}, dragNDropObservers = [], eventPosition = (e) => ({
  pageLeft: e.pageX,
  pageTop: e.pageY,
  scopeLeft: e.offsetX || e.layerX,
  scopeTop: e.offsetY || e.layerY
}), createDragNDropClient = (e, t, i) => {
  const a = getDragNDropObserver(t), n = {
    element: e,
    filterElement: i,
    state: null,
    ondrop: () => {
    },
    onenter: () => {
    },
    ondrag: () => {
    },
    onexit: () => {
    },
    onload: () => {
    },
    allowdrop: () => {
    }
  };
  return n.destroy = a.addListener(n), n;
}, getDragNDropObserver = (e) => {
  const t = dragNDropObservers.find((a) => a.element === e);
  if (t)
    return t;
  const i = createDragNDropObserver(e);
  return dragNDropObservers.push(i), i;
}, createDragNDropObserver = (e) => {
  const t = [], i = {
    dragenter,
    dragover,
    dragleave,
    drop
  }, a = {};
  forin(i, (l, r) => {
    a[l] = r(e, t), e.addEventListener(l, a[l], !1);
  });
  const n = {
    element: e,
    addListener: (l) => (t.push(l), () => {
      t.splice(t.indexOf(l), 1), t.length === 0 && (dragNDropObservers.splice(dragNDropObservers.indexOf(n), 1), forin(i, (r) => {
        e.removeEventListener(r, a[r], !1);
      }));
    })
  };
  return n;
}, elementFromPoint = (e, t) => ("elementFromPoint" in e || (e = document), e.elementFromPoint(t.x, t.y)), isEventTarget = (e, t) => {
  const i = getRootNode(t), a = elementFromPoint(i, {
    x: e.pageX - window.pageXOffset,
    y: e.pageY - window.pageYOffset
  });
  return a === t || t.contains(a);
};
let initialTarget = null;
const setDropEffect = (e, t) => {
  try {
    e.dropEffect = t;
  } catch {
  }
}, dragenter = (e, t) => (i) => {
  i.preventDefault(), initialTarget = i.target, t.forEach((a) => {
    const { element: n, onenter: l } = a;
    isEventTarget(i, n) && (a.state = "enter", l(eventPosition(i)));
  });
}, dragover = (e, t) => (i) => {
  i.preventDefault();
  const a = i.dataTransfer;
  requestDataTransferItems(a).then((n) => {
    let l = !1;
    t.some((r) => {
      const { filterElement: s, element: o, onenter: d, onexit: c, ondrag: u, allowdrop: f } = r;
      setDropEffect(a, "copy");
      const p = f(n);
      if (!p) {
        setDropEffect(a, "none");
        return;
      }
      if (isEventTarget(i, o)) {
        if (l = !0, r.state === null) {
          r.state = "enter", d(eventPosition(i));
          return;
        }
        if (r.state = "over", s && !p) {
          setDropEffect(a, "none");
          return;
        }
        u(eventPosition(i));
      } else
        s && !l && setDropEffect(a, "none"), r.state && (r.state = null, c(eventPosition(i)));
    });
  });
}, drop = (e, t) => (i) => {
  i.preventDefault();
  const a = i.dataTransfer;
  requestDataTransferItems(a).then((n) => {
    t.forEach((l) => {
      const { filterElement: r, element: s, ondrop: o, onexit: d, allowdrop: c } = l;
      if (l.state = null, !(r && !isEventTarget(i, s))) {
        if (!c(n)) return d(eventPosition(i));
        o(eventPosition(i), n);
      }
    });
  });
}, dragleave = (e, t) => (i) => {
  initialTarget === i.target && t.forEach((a) => {
    const { onexit: n } = a;
    a.state = null, n(eventPosition(i));
  });
}, createHopper = (e, t, i) => {
  e.classList.add("filepond--hopper");
  const { catchesDropsOnPage: a, requiresDropOnElement: n, filterItems: l = (c) => c } = i, r = createDragNDropClient(
    e,
    a ? document.documentElement : e,
    n
  );
  let s = "", o = "";
  r.allowdrop = (c) => t(l(c)), r.ondrop = (c, u) => {
    const f = l(u);
    if (!t(f)) {
      d.ondragend(c);
      return;
    }
    o = "drag-drop", d.onload(f, c);
  }, r.ondrag = (c) => {
    d.ondrag(c);
  }, r.onenter = (c) => {
    o = "drag-over", d.ondragstart(c);
  }, r.onexit = (c) => {
    o = "drag-exit", d.ondragend(c);
  };
  const d = {
    updateHopperState: () => {
      s !== o && (e.dataset.hopperState = o, s = o);
    },
    onload: () => {
    },
    ondragstart: () => {
    },
    ondrag: () => {
    },
    ondragend: () => {
    },
    destroy: () => {
      r.destroy();
    }
  };
  return d;
};
let listening = !1;
const listeners$1 = [], handlePaste = (e) => {
  const t = document.activeElement;
  if (t && /textarea|input/i.test(t.nodeName)) {
    let i = !1, a = t;
    for (; a !== document.body; ) {
      if (a.classList.contains("filepond--root")) {
        i = !0;
        break;
      }
      a = a.parentNode;
    }
    if (!i) return;
  }
  requestDataTransferItems(e.clipboardData).then((i) => {
    i.length && listeners$1.forEach((a) => a(i));
  });
}, listen = (e) => {
  listeners$1.includes(e) || (listeners$1.push(e), !listening && (listening = !0, document.addEventListener("paste", handlePaste)));
}, unlisten = (e) => {
  arrayRemove(listeners$1, listeners$1.indexOf(e)), listeners$1.length === 0 && (document.removeEventListener("paste", handlePaste), listening = !1);
}, createPaster = () => {
  const e = (i) => {
    t.onload(i);
  }, t = {
    destroy: () => {
      unlisten(e);
    },
    onload: () => {
    }
  };
  return listen(e), t;
}, create$d = ({ root: e, props: t }) => {
  e.element.id = `filepond--assistant-${t.id}`, attr(e.element, "role", "status"), attr(e.element, "aria-live", "polite"), attr(e.element, "aria-relevant", "additions");
};
let addFilesNotificationTimeout = null, notificationClearTimeout = null;
const filenames = [], assist = (e, t) => {
  e.element.textContent = t;
}, clear$1 = (e) => {
  e.element.textContent = "";
}, listModified = (e, t, i) => {
  const a = e.query("GET_TOTAL_ITEMS");
  assist(
    e,
    `${i} ${t}, ${a} ${a === 1 ? e.query("GET_LABEL_FILE_COUNT_SINGULAR") : e.query("GET_LABEL_FILE_COUNT_PLURAL")}`
  ), clearTimeout(notificationClearTimeout), notificationClearTimeout = setTimeout(() => {
    clear$1(e);
  }, 1500);
}, isUsingFilePond = (e) => e.element.parentNode.contains(document.activeElement), itemAdded = ({ root: e, action: t }) => {
  if (!isUsingFilePond(e))
    return;
  e.element.textContent = "";
  const i = e.query("GET_ITEM", t.id);
  filenames.push(i.filename), clearTimeout(addFilesNotificationTimeout), addFilesNotificationTimeout = setTimeout(() => {
    listModified(e, filenames.join(", "), e.query("GET_LABEL_FILE_ADDED")), filenames.length = 0;
  }, 750);
}, itemRemoved = ({ root: e, action: t }) => {
  if (!isUsingFilePond(e))
    return;
  const i = t.item;
  listModified(e, i.filename, e.query("GET_LABEL_FILE_REMOVED"));
}, itemProcessed = ({ root: e, action: t }) => {
  const a = e.query("GET_ITEM", t.id).filename, n = e.query("GET_LABEL_FILE_PROCESSING_COMPLETE");
  assist(e, `${a} ${n}`);
}, itemProcessedUndo = ({ root: e, action: t }) => {
  const a = e.query("GET_ITEM", t.id).filename, n = e.query("GET_LABEL_FILE_PROCESSING_ABORTED");
  assist(e, `${a} ${n}`);
}, itemError = ({ root: e, action: t }) => {
  const a = e.query("GET_ITEM", t.id).filename;
  assist(e, `${t.status.main} ${a} ${t.status.sub}`);
}, assistant = createView({
  create: create$d,
  ignoreRect: !0,
  ignoreRectUpdate: !0,
  write: createRoute({
    DID_LOAD_ITEM: itemAdded,
    DID_REMOVE_ITEM: itemRemoved,
    DID_COMPLETE_ITEM_PROCESSING: itemProcessed,
    DID_ABORT_ITEM_PROCESSING: itemProcessedUndo,
    DID_REVERT_ITEM_PROCESSING: itemProcessedUndo,
    DID_THROW_ITEM_REMOVE_ERROR: itemError,
    DID_THROW_ITEM_LOAD_ERROR: itemError,
    DID_THROW_ITEM_INVALID: itemError,
    DID_THROW_ITEM_PROCESSING_ERROR: itemError
  }),
  tag: "span",
  name: "assistant"
}), toCamels = (e, t = "-") => e.replace(new RegExp(`${t}.`, "g"), (i) => i.charAt(1).toUpperCase()), debounce = (e, t = 16, i = !0) => {
  let a = Date.now(), n = null;
  return (...l) => {
    clearTimeout(n);
    const r = Date.now() - a, s = () => {
      a = Date.now(), e(...l);
    };
    r < t ? i || (n = setTimeout(s, t - r)) : s();
  };
}, MAX_FILES_LIMIT = 1e6, prevent = (e) => e.preventDefault(), create$e = ({ root: e, props: t }) => {
  const i = e.query("GET_ID");
  i && (e.element.id = i);
  const a = e.query("GET_CLASS_NAME");
  a && a.split(" ").filter((o) => o.length).forEach((o) => {
    e.element.classList.add(o);
  }), e.ref.label = e.appendChildView(
    e.createChildView(dropLabel, {
      ...t,
      translateY: null,
      caption: e.query("GET_LABEL_IDLE")
    })
  ), e.ref.list = e.appendChildView(e.createChildView(listScroller, { translateY: null })), e.ref.panel = e.appendChildView(e.createChildView(panel, { name: "panel-root" })), e.ref.assistant = e.appendChildView(e.createChildView(assistant, { ...t })), e.ref.data = e.appendChildView(e.createChildView(data$1, { ...t })), e.ref.measure = createElement$1("div"), e.ref.measure.style.height = "100%", e.element.appendChild(e.ref.measure), e.ref.bounds = null, e.query("GET_STYLES").filter((o) => !isEmpty$1(o.value)).map(({ name: o, value: d }) => {
    e.element.dataset[o] = d;
  }), e.ref.widthPrevious = null, e.ref.widthUpdated = debounce(() => {
    e.ref.updateHistory = [], e.dispatch("DID_RESIZE_ROOT");
  }, 250), e.ref.previousAspectRatio = null, e.ref.updateHistory = [];
  const n = window.matchMedia("(pointer: fine) and (hover: hover)").matches, l = "PointerEvent" in window;
  e.query("GET_ALLOW_REORDER") && l && !n && (e.element.addEventListener("touchmove", prevent, { passive: !1 }), e.element.addEventListener("gesturestart", prevent));
  const r = e.query("GET_CREDITS");
  if (r.length === 2) {
    const o = document.createElement("a");
    o.className = "filepond--credits", o.setAttribute("aria-hidden", "true"), o.href = r[0], o.tabindex = -1, o.target = "_blank", o.rel = "noopener noreferrer", o.textContent = r[1], e.element.appendChild(o), e.ref.credits = o;
  }
}, write$9 = ({ root: e, props: t, actions: i }) => {
  if (route$5({ root: e, props: t, actions: i }), i.filter((L) => /^DID_SET_STYLE_/.test(L.type)).filter((L) => !isEmpty$1(L.data.value)).map(({ type: L, data: M }) => {
    const y = toCamels(L.substring(8).toLowerCase(), "_");
    e.element.dataset[y] = M.value, e.invalidateLayout();
  }), e.rect.element.hidden) return;
  e.rect.element.width !== e.ref.widthPrevious && (e.ref.widthPrevious = e.rect.element.width, e.ref.widthUpdated());
  let a = e.ref.bounds;
  a || (a = e.ref.bounds = calculateRootBoundingBoxHeight(e), e.element.removeChild(e.ref.measure), e.ref.measure = null);
  const { hopper: n, label: l, list: r, panel: s } = e.ref;
  n && n.updateHopperState();
  const o = e.query("GET_PANEL_ASPECT_RATIO"), d = e.query("GET_ALLOW_MULTIPLE"), c = e.query("GET_TOTAL_ITEMS"), u = d ? e.query("GET_MAX_FILES") || MAX_FILES_LIMIT : 1, f = c === u, p = i.find((L) => L.type === "DID_ADD_ITEM");
  if (f && p) {
    const L = p.data.interactionMethod;
    l.opacity = 0, d ? l.translateY = -40 : L === InteractionMethod.API ? l.translateX = 40 : L === InteractionMethod.BROWSE ? l.translateY = 40 : l.translateY = 30;
  } else f || (l.opacity = 1, l.translateX = 0, l.translateY = 0);
  const m = calculateListItemMargin(e), h = calculateListHeight(e), I = l.rect.element.height, b = !d || f ? 0 : I, g = f ? r.rect.element.marginTop : 0, E = c === 0 ? 0 : r.rect.element.marginBottom, T = b + g + h.visual + E, S = b + g + h.bounds + E;
  if (r.translateY = Math.max(0, b - r.rect.element.marginTop) - m.top, o) {
    const L = e.rect.element.width, M = L * o;
    o !== e.ref.previousAspectRatio && (e.ref.previousAspectRatio = o, e.ref.updateHistory = []);
    const y = e.ref.updateHistory;
    y.push(L);
    const x = 2;
    if (y.length > x * 2) {
      const P = y.length, O = P - 10;
      let B = 0;
      for (let F = P; F >= O; F--)
        if (y[F] === y[F - 2] && B++, B >= x)
          return;
    }
    s.scalable = !1, s.height = M;
    const v = (
      // the height of the panel minus the label height
      M - b - // the room we leave open between the end of the list and the panel bottom
      (E - m.bottom) - // if we're full we need to leave some room between the top of the panel and the list
      (f ? g : 0)
    );
    h.visual > v ? r.overflow = v : r.overflow = null, e.height = M;
  } else if (a.fixedHeight) {
    s.scalable = !1;
    const L = (
      // the height of the panel minus the label height
      a.fixedHeight - b - // the room we leave open between the end of the list and the panel bottom
      (E - m.bottom) - // if we're full we need to leave some room between the top of the panel and the list
      (f ? g : 0)
    );
    h.visual > L ? r.overflow = L : r.overflow = null;
  } else if (a.cappedHeight) {
    const L = T >= a.cappedHeight, M = Math.min(a.cappedHeight, T);
    s.scalable = !0, s.height = L ? M : M - m.top - m.bottom;
    const y = (
      // the height of the panel minus the label height
      M - b - // the room we leave open between the end of the list and the panel bottom
      (E - m.bottom) - // if we're full we need to leave some room between the top of the panel and the list
      (f ? g : 0)
    );
    T > a.cappedHeight && h.visual > y ? r.overflow = y : r.overflow = null, e.height = Math.min(
      a.cappedHeight,
      S - m.top - m.bottom
    );
  } else {
    const L = c > 0 ? m.top + m.bottom : 0;
    s.scalable = !0, s.height = Math.max(I, T - L), e.height = Math.max(I, S - L);
  }
  e.ref.credits && s.heightCurrent && (e.ref.credits.style.transform = `translateY(${s.heightCurrent}px)`);
}, calculateListItemMargin = (e) => {
  const t = e.ref.list.childViews[0].childViews[0];
  return t ? {
    top: t.rect.element.marginTop,
    bottom: t.rect.element.marginBottom
  } : {
    top: 0,
    bottom: 0
  };
}, calculateListHeight = (e) => {
  let t = 0, i = 0;
  const a = e.ref.list, n = a.childViews[0], l = n.childViews.filter((g) => g.rect.element.height), r = e.query("GET_ACTIVE_ITEMS").map((g) => l.find((E) => E.id === g.id)).filter((g) => g);
  if (r.length === 0) return { visual: t, bounds: i };
  const s = n.rect.element.width, o = getItemIndexByPosition(n, r, a.dragCoordinates), d = r[0].rect.element, c = d.marginTop + d.marginBottom, u = d.marginLeft + d.marginRight, f = d.width + u, p = d.height + c, m = typeof o < "u" && o >= 0 ? 1 : 0, h = r.find((g) => g.markedForRemoval && g.opacity < 0.45) ? -1 : 0, I = r.length + m + h, b = getItemsPerRow(s, f);
  return b === 1 ? r.forEach((g) => {
    const E = g.rect.element.height + c;
    i += E, t += E * g.opacity;
  }) : (i = Math.ceil(I / b) * p, t = i), { visual: t, bounds: i };
}, calculateRootBoundingBoxHeight = (e) => {
  const t = e.ref.measureHeight || null;
  return {
    cappedHeight: parseInt(e.style.maxHeight, 10) || null,
    fixedHeight: t === 0 ? null : t
  };
}, exceedsMaxFiles = (e, t) => {
  const i = e.query("GET_ALLOW_REPLACE"), a = e.query("GET_ALLOW_MULTIPLE"), n = e.query("GET_TOTAL_ITEMS");
  let l = e.query("GET_MAX_FILES");
  const r = t.length;
  return !a && r > 1 ? (e.dispatch("DID_THROW_MAX_FILES", {
    source: t,
    error: createResponse("warning", 0, "Max files")
  }), !0) : (l = a ? l : 1, !a && i ? !1 : isInt(l) && n + r > l ? (e.dispatch("DID_THROW_MAX_FILES", {
    source: t,
    error: createResponse("warning", 0, "Max files")
  }), !0) : !1);
}, getDragIndex = (e, t, i) => {
  const a = e.childViews[0];
  return getItemIndexByPosition(a, t, {
    left: i.scopeLeft - a.rect.element.left,
    top: i.scopeTop - (e.rect.outer.top + e.rect.element.marginTop + e.rect.element.scrollTop)
  });
}, toggleDrop = (e) => {
  const t = e.query("GET_ALLOW_DROP"), i = e.query("GET_DISABLED"), a = t && !i;
  if (a && !e.ref.hopper) {
    const n = createHopper(
      e.element,
      (l) => {
        const r = e.query("GET_BEFORE_DROP_FILE") || (() => !0);
        return e.query("GET_DROP_VALIDATION") ? l.every(
          (o) => applyFilters("ALLOW_HOPPER_ITEM", o, {
            query: e.query
          }).every((d) => d === !0) && r(o)
        ) : !0;
      },
      {
        filterItems: (l) => {
          const r = e.query("GET_IGNORED_FILES");
          return l.filter((s) => isFile(s) ? !r.includes(s.name.toLowerCase()) : !0);
        },
        catchesDropsOnPage: e.query("GET_DROP_ON_PAGE"),
        requiresDropOnElement: e.query("GET_DROP_ON_ELEMENT")
      }
    );
    n.onload = (l, r) => {
      const o = e.ref.list.childViews[0].childViews.filter((c) => c.rect.element.height), d = e.query("GET_ACTIVE_ITEMS").map((c) => o.find((u) => u.id === c.id)).filter((c) => c);
      applyFilterChain("ADD_ITEMS", l, { dispatch: e.dispatch }).then((c) => {
        if (exceedsMaxFiles(e, c)) return !1;
        e.dispatch("ADD_ITEMS", {
          items: c,
          index: getDragIndex(e.ref.list, d, r),
          interactionMethod: InteractionMethod.DROP
        });
      }), e.dispatch("DID_DROP", { position: r }), e.dispatch("DID_END_DRAG", { position: r });
    }, n.ondragstart = (l) => {
      e.dispatch("DID_START_DRAG", { position: l });
    }, n.ondrag = debounce((l) => {
      e.dispatch("DID_DRAG", { position: l });
    }), n.ondragend = (l) => {
      e.dispatch("DID_END_DRAG", { position: l });
    }, e.ref.hopper = n, e.ref.drip = e.appendChildView(e.createChildView(drip));
  } else !a && e.ref.hopper && (e.ref.hopper.destroy(), e.ref.hopper = null, e.removeChildView(e.ref.drip));
}, toggleBrowse = (e, t) => {
  const i = e.query("GET_ALLOW_BROWSE"), a = e.query("GET_DISABLED"), n = i && !a;
  n && !e.ref.browser ? e.ref.browser = e.appendChildView(
    e.createChildView(browser, {
      ...t,
      onload: (l) => {
        applyFilterChain("ADD_ITEMS", l, {
          dispatch: e.dispatch
        }).then((r) => {
          if (exceedsMaxFiles(e, r)) return !1;
          e.dispatch("ADD_ITEMS", {
            items: r,
            index: -1,
            interactionMethod: InteractionMethod.BROWSE
          });
        });
      }
    }),
    0
  ) : !n && e.ref.browser && (e.removeChildView(e.ref.browser), e.ref.browser = null);
}, togglePaste = (e) => {
  const t = e.query("GET_ALLOW_PASTE"), i = e.query("GET_DISABLED"), a = t && !i;
  a && !e.ref.paster ? (e.ref.paster = createPaster(), e.ref.paster.onload = (n) => {
    applyFilterChain("ADD_ITEMS", n, { dispatch: e.dispatch }).then((l) => {
      if (exceedsMaxFiles(e, l)) return !1;
      e.dispatch("ADD_ITEMS", {
        items: l,
        index: -1,
        interactionMethod: InteractionMethod.PASTE
      });
    });
  }) : !a && e.ref.paster && (e.ref.paster.destroy(), e.ref.paster = null);
}, route$5 = createRoute({
  DID_SET_ALLOW_BROWSE: ({ root: e, props: t }) => {
    toggleBrowse(e, t);
  },
  DID_SET_ALLOW_DROP: ({ root: e }) => {
    toggleDrop(e);
  },
  DID_SET_ALLOW_PASTE: ({ root: e }) => {
    togglePaste(e);
  },
  DID_SET_DISABLED: ({ root: e, props: t }) => {
    toggleDrop(e), togglePaste(e), toggleBrowse(e, t), e.query("GET_DISABLED") ? e.element.dataset.disabled = "disabled" : e.element.removeAttribute("data-disabled");
  }
}), root$1 = createView({
  name: "root",
  read: ({ root: e }) => {
    e.ref.measure && (e.ref.measureHeight = e.ref.measure.offsetHeight);
  },
  create: create$e,
  write: write$9,
  destroy: ({ root: e }) => {
    e.ref.paster && e.ref.paster.destroy(), e.ref.hopper && e.ref.hopper.destroy(), e.element.removeEventListener("touchmove", prevent), e.element.removeEventListener("gesturestart", prevent);
  },
  mixins: {
    styles: ["height"]
  }
}), createApp = (e = {}) => {
  let t = null;
  const i = getOptions(), a = createStore(
    // initial state (should be serializable)
    createInitialState(i),
    // queries
    [queries, createOptionQueries(i)],
    // action handlers
    [actions, createOptionActions(i)]
  );
  a.dispatch("SET_OPTIONS", { options: e });
  const n = () => {
    document.hidden || a.dispatch("KICK");
  };
  document.addEventListener("visibilitychange", n);
  let l = null, r = !1, s = !1, o = null, d = null;
  const c = () => {
    r || (r = !0), clearTimeout(l), l = setTimeout(() => {
      r = !1, o = null, d = null, s && (s = !1, a.dispatch("DID_STOP_RESIZE"));
    }, 500);
  };
  window.addEventListener("resize", c);
  const u = root$1(a, { id: getUniqueId() });
  let f = !1, p = !1;
  const m = {
    // necessary for update loop
    /**
     * Reads from dom (never call manually)
     * @private
     */
    _read: () => {
      r && (d = window.innerWidth, o || (o = d), !s && d !== o && (a.dispatch("DID_START_RESIZE"), s = !0)), p && f && (f = u.element.offsetParent === null), !f && (u._read(), p = u.rect.element.hidden);
    },
    /**
     * Writes to dom (never call manually)
     * @private
     */
    _write: (R) => {
      const A = a.processActionQueue().filter((w) => !/^SET_/.test(w.type));
      f && !A.length || (g(A), f = u._write(R, A, s), removeReleasedItems(a.query("GET_ITEMS")), f && a.processDispatchQueue());
    }
  }, h = (R) => (A) => {
    const w = {
      type: R
    };
    if (!A)
      return w;
    if (A.hasOwnProperty("error") && (w.error = A.error ? { ...A.error } : null), A.status && (w.status = { ...A.status }), A.file && (w.output = A.file), A.source)
      w.file = A.source;
    else if (A.item || A.id) {
      const z = A.item ? A.item : a.query("GET_ITEM", A.id);
      w.file = z ? createItemAPI(z) : null;
    }
    return A.items && (w.items = A.items.map(createItemAPI)), /progress/.test(R) && (w.progress = A.progress), A.hasOwnProperty("origin") && A.hasOwnProperty("target") && (w.origin = A.origin, w.target = A.target), w;
  }, I = {
    DID_DESTROY: h("destroy"),
    DID_INIT: h("init"),
    DID_THROW_MAX_FILES: h("warning"),
    DID_INIT_ITEM: h("initfile"),
    DID_START_ITEM_LOAD: h("addfilestart"),
    DID_UPDATE_ITEM_LOAD_PROGRESS: h("addfileprogress"),
    DID_LOAD_ITEM: h("addfile"),
    DID_THROW_ITEM_INVALID: [h("error"), h("addfile")],
    DID_THROW_ITEM_LOAD_ERROR: [h("error"), h("addfile")],
    DID_THROW_ITEM_REMOVE_ERROR: [h("error"), h("removefile")],
    DID_PREPARE_OUTPUT: h("preparefile"),
    DID_START_ITEM_PROCESSING: h("processfilestart"),
    DID_UPDATE_ITEM_PROCESS_PROGRESS: h("processfileprogress"),
    DID_ABORT_ITEM_PROCESSING: h("processfileabort"),
    DID_COMPLETE_ITEM_PROCESSING: h("processfile"),
    DID_COMPLETE_ITEM_PROCESSING_ALL: h("processfiles"),
    DID_REVERT_ITEM_PROCESSING: h("processfilerevert"),
    DID_THROW_ITEM_PROCESSING_ERROR: [h("error"), h("processfile")],
    DID_REMOVE_ITEM: h("removefile"),
    DID_UPDATE_ITEMS: h("updatefiles"),
    DID_ACTIVATE_ITEM: h("activatefile"),
    DID_REORDER_ITEMS: h("reorderfiles")
  }, b = (R) => {
    const A = { pond: D, ...R };
    delete A.type, u.element.dispatchEvent(
      new CustomEvent(`FilePond:${R.type}`, {
        // event info
        detail: A,
        // event behaviour
        bubbles: !0,
        cancelable: !0,
        composed: !0
        // triggers listeners outside of shadow root
      })
    );
    const w = [];
    R.hasOwnProperty("error") && w.push(R.error), R.hasOwnProperty("file") && w.push(R.file);
    const z = ["type", "error", "file"];
    Object.keys(R).filter((V) => !z.includes(V)).forEach((V) => w.push(R[V])), D.fire(R.type, ...w);
    const k = a.query(`GET_ON${R.type.toUpperCase()}`);
    k && k(...w);
  }, g = (R) => {
    R.length && R.filter((A) => I[A.type]).forEach((A) => {
      const w = I[A.type];
      (Array.isArray(w) ? w : [w]).forEach((z) => {
        A.type === "DID_INIT_ITEM" ? b(z(A.data)) : setTimeout(() => {
          b(z(A.data));
        }, 0);
      });
    });
  }, E = (R) => a.dispatch("SET_OPTIONS", { options: R }), T = (R) => a.query("GET_ACTIVE_ITEM", R), S = (R) => new Promise((A, w) => {
    a.dispatch("REQUEST_ITEM_PREPARE", {
      query: R,
      success: (z) => {
        A(z);
      },
      failure: (z) => {
        w(z);
      }
    });
  }), L = (R, A = {}) => new Promise((w, z) => {
    x([{ source: R, options: A }], { index: A.index }).then((k) => w(k && k[0])).catch(z);
  }), M = (R) => R.file && R.id, y = (R, A) => (typeof R == "object" && !M(R) && !A && (A = R, R = void 0), a.dispatch("REMOVE_ITEM", { ...A, query: R }), a.query("GET_ACTIVE_ITEM", R) === null), x = (...R) => new Promise((A, w) => {
    const z = [], k = {};
    if (isArray$1(R[0]))
      z.push.apply(z, R[0]), Object.assign(k, R[1] || {});
    else {
      const V = R[R.length - 1];
      typeof V == "object" && !(V instanceof Blob) && Object.assign(k, R.pop()), z.push(...R);
    }
    a.dispatch("ADD_ITEMS", {
      items: z,
      index: k.index,
      interactionMethod: InteractionMethod.API,
      success: A,
      failure: w
    });
  }), v = () => a.query("GET_ACTIVE_ITEMS"), P = (R) => new Promise((A, w) => {
    a.dispatch("REQUEST_ITEM_PROCESSING", {
      query: R,
      success: (z) => {
        A(z);
      },
      failure: (z) => {
        w(z);
      }
    });
  }), O = (...R) => {
    const A = Array.isArray(R[0]) ? R[0] : R, w = A.length ? A : v();
    return Promise.all(w.map(S));
  }, B = (...R) => {
    const A = Array.isArray(R[0]) ? R[0] : R;
    if (!A.length) {
      const w = v().filter(
        (z) => !(z.status === ItemStatus.IDLE && z.origin === FileOrigin.LOCAL) && z.status !== ItemStatus.PROCESSING && z.status !== ItemStatus.PROCESSING_COMPLETE && z.status !== ItemStatus.PROCESSING_REVERT_ERROR
      );
      return Promise.all(w.map(P));
    }
    return Promise.all(A.map(P));
  }, F = (...R) => {
    const A = Array.isArray(R[0]) ? R[0] : R;
    let w;
    typeof A[A.length - 1] == "object" ? w = A.pop() : Array.isArray(R[0]) && (w = R[1]);
    const z = v();
    return A.length ? A.map((V) => isNumber$1(V) ? z[V] ? z[V].id : null : V).filter((V) => V).map((V) => y(V, w)) : Promise.all(z.map((V) => y(V, w)));
  }, D = {
    // supports events
    ...on(),
    // inject private api methods
    ...m,
    // inject all getters and setters
    ...createOptionAPI(a, i),
    /**
     * Override options defined in options object
     * @param options
     */
    setOptions: E,
    /**
     * Load the given file
     * @param source - the source of the file (either a File, base64 data uri or url)
     * @param options - object, { index: 0 }
     */
    addFile: L,
    /**
     * Load the given files
     * @param sources - the sources of the files to load
     * @param options - object, { index: 0 }
     */
    addFiles: x,
    /**
     * Returns the file objects matching the given query
     * @param query { string, number, null }
     */
    getFile: T,
    /**
     * Upload file with given name
     * @param query { string, number, null  }
     */
    processFile: P,
    /**
     * Request prepare output for file with given name
     * @param query { string, number, null  }
     */
    prepareFile: S,
    /**
     * Removes a file by its name
     * @param query { string, number, null  }
     */
    removeFile: y,
    /**
     * Moves a file to a new location in the files list
     */
    moveFile: (R, A) => a.dispatch("MOVE_ITEM", { query: R, index: A }),
    /**
     * Returns all files (wrapped in public api)
     */
    getFiles: v,
    /**
     * Starts uploading all files
     */
    processFiles: B,
    /**
     * Clears all files from the files list
     */
    removeFiles: F,
    /**
     * Starts preparing output of all files
     */
    prepareFiles: O,
    /**
     * Sort list of files
     */
    sort: (R) => a.dispatch("SORT", { compare: R }),
    /**
     * Browse the file system for a file
     */
    browse: () => {
      var R = u.element.querySelector("input[type=file]");
      R && R.click();
    },
    /**
     * Destroys the app
     */
    destroy: () => {
      D.fire("destroy", u.element), a.dispatch("ABORT_ALL"), u._destroy(), window.removeEventListener("resize", c), document.removeEventListener("visibilitychange", n), a.dispatch("DID_DESTROY");
    },
    /**
     * Inserts the plugin before the target element
     */
    insertBefore: (R) => insertBefore(u.element, R),
    /**
     * Inserts the plugin after the target element
     */
    insertAfter: (R) => insertAfter(u.element, R),
    /**
     * Appends the plugin to the target element
     */
    appendTo: (R) => R.appendChild(u.element),
    /**
     * Replaces an element with the app
     */
    replaceElement: (R) => {
      insertBefore(u.element, R), R.parentNode.removeChild(R), t = R;
    },
    /**
     * Restores the original element
     */
    restoreElement: () => {
      t && (insertAfter(t, u.element), u.element.parentNode.removeChild(u.element), t = null);
    },
    /**
     * Returns true if the app root is attached to given element
     * @param element
     */
    isAttachedTo: (R) => u.element === R || t === R,
    /**
     * Returns the root element
     */
    element: {
      get: () => u.element
    },
    /**
     * Returns the current pond status
     */
    status: {
      get: () => a.query("GET_STATUS")
    }
  };
  return a.dispatch("DID_INIT"), createObject(D);
}, createAppObject = (e = {}) => {
  const t = {};
  return forin(getOptions(), (a, n) => {
    t[a] = n[0];
  }), createApp({
    // default options
    ...t,
    // custom options
    ...e
  });
}, lowerCaseFirstLetter = (e) => e.charAt(0).toLowerCase() + e.slice(1), attributeNameToPropertyName = (e) => toCamels(e.replace(/^data-/, "")), mapObject = (e, t) => {
  forin(t, (i, a) => {
    forin(e, (n, l) => {
      const r = new RegExp(i);
      if (!r.test(n) || (delete e[n], a === !1))
        return;
      if (isString$1(a)) {
        e[a] = l;
        return;
      }
      const o = a.group;
      isObject$1(a) && !e[o] && (e[o] = {}), e[o][lowerCaseFirstLetter(n.replace(r, ""))] = l;
    }), a.mapping && mapObject(e[a.group], a.mapping);
  });
}, getAttributesAsObject = (e, t = {}) => {
  const i = [];
  forin(e.attributes, (n) => {
    i.push(e.attributes[n]);
  });
  const a = i.filter((n) => n.name).reduce((n, l) => {
    const r = attr(e, l.name);
    return n[attributeNameToPropertyName(l.name)] = r === l.name ? !0 : r, n;
  }, {});
  return mapObject(a, t), a;
}, createAppAtElement = (e, t = {}) => {
  const i = {
    // translate to other name
    "^class$": "className",
    "^multiple$": "allowMultiple",
    "^capture$": "captureMethod",
    "^webkitdirectory$": "allowDirectoriesOnly",
    // group under single property
    "^server": {
      group: "server",
      mapping: {
        "^process": {
          group: "process"
        },
        "^revert": {
          group: "revert"
        },
        "^fetch": {
          group: "fetch"
        },
        "^restore": {
          group: "restore"
        },
        "^load": {
          group: "load"
        }
      }
    },
    // don't include in object
    "^type$": !1,
    "^files$": !1
  };
  applyFilters("SET_ATTRIBUTE_TO_OPTION_MAP", i);
  const a = {
    ...t
  }, n = getAttributesAsObject(
    e.nodeName === "FIELDSET" ? e.querySelector("input[type=file]") : e,
    i
  );
  Object.keys(n).forEach((r) => {
    isObject$1(n[r]) ? (isObject$1(a[r]) || (a[r] = {}), Object.assign(a[r], n[r])) : a[r] = n[r];
  }), a.files = (t.files || []).concat(
    Array.from(e.querySelectorAll("input:not([type=file])")).map((r) => ({
      source: r.value,
      options: {
        type: r.dataset.type
      }
    }))
  );
  const l = createAppObject(a);
  return e.files && Array.from(e.files).forEach((r) => {
    l.addFile(r);
  }), l.replaceElement(e), l;
}, createApp$1 = (...e) => isNode(e[0]) ? createAppAtElement(...e) : createAppObject(...e), PRIVATE_METHODS = ["fire", "_read", "_write"], createAppAPI = (e) => {
  const t = {};
  return copyObjectPropertiesToObject(e, t, PRIVATE_METHODS), t;
}, replaceInString = (e, t) => e.replace(/(?:{([a-zA-Z]+)})/g, (i, a) => t[a]), createWorker = (e) => {
  const t = new Blob(["(", e.toString(), ")()"], {
    type: "application/javascript"
  }), i = URL.createObjectURL(t), a = new Worker(i);
  return {
    transfer: (n, l) => {
    },
    post: (n, l, r) => {
      const s = getUniqueId();
      a.onmessage = (o) => {
        o.data.id === s && l(o.data.message);
      }, a.postMessage(
        {
          id: s,
          message: n
        },
        r
      );
    },
    terminate: () => {
      a.terminate(), URL.revokeObjectURL(i);
    }
  };
}, loadImage$1 = (e) => new Promise((t, i) => {
  const a = new Image();
  a.onload = () => {
    t(a);
  }, a.onerror = (n) => {
    i(n);
  }, a.src = e;
}), renameFile = (e, t) => {
  const i = e.slice(0, e.size, e.type);
  return i.lastModifiedDate = e.lastModifiedDate, i.name = t, i;
}, copyFile = (e) => renameFile(e, e.name), registeredPlugins = [], createAppPlugin = (e) => {
  if (registeredPlugins.includes(e))
    return;
  registeredPlugins.push(e);
  const t = e({
    addFilter,
    utils: {
      Type,
      forin,
      isString: isString$1,
      isFile,
      toNaturalFileSize,
      replaceInString,
      getExtensionFromFilename,
      getFilenameWithoutExtension,
      guesstimateMimeType,
      getFileFromBlob,
      getFilenameFromURL,
      createRoute,
      createWorker,
      createView,
      createItemAPI,
      loadImage: loadImage$1,
      copyFile,
      renameFile,
      createBlob,
      applyFilterChain,
      text,
      getNumericAspectRatioFromString
    },
    views: {
      fileActionButton
    }
  });
  extendDefaultOptions(t.options);
}, isOperaMini = () => Object.prototype.toString.call(window.operamini) === "[object OperaMini]", hasPromises = () => "Promise" in window, hasBlobSlice = () => "slice" in Blob.prototype, hasCreateObjectURL = () => "URL" in window && "createObjectURL" in window.URL, hasVisibility = () => "visibilityState" in document, hasTiming = () => "performance" in window, hasCSSSupports = () => "supports" in (window.CSS || {}), isIE11 = () => /MSIE|Trident/.test(window.navigator.userAgent), supported = (() => {
  const e = (
    // Has to be a browser
    isBrowser$4() && // Can't run on Opera Mini due to lack of everything
    !isOperaMini() && // Require these APIs to feature detect a modern browser
    hasVisibility() && hasPromises() && hasBlobSlice() && hasCreateObjectURL() && hasTiming() && // doesn't need CSSSupports but is a good way to detect Safari 9+ (we do want to support IE11 though)
    (hasCSSSupports() || isIE11())
  );
  return () => e;
})(), state = {
  // active app instances, used to redraw the apps and to find the later
  apps: []
}, name = "filepond", fn = () => {
};
let OptionTypes = {}, create$f = fn, destroy = fn, parse = fn, find = fn, registerPlugin = fn, getOptions$1 = fn, setOptions$1 = fn;
if (supported()) {
  createPainter(
    () => {
      state.apps.forEach((i) => i._read());
    },
    (i) => {
      state.apps.forEach((a) => a._write(i));
    }
  );
  const e = () => {
    document.dispatchEvent(
      new CustomEvent("FilePond:loaded", {
        detail: {
          supported,
          create: create$f,
          destroy,
          parse,
          find,
          registerPlugin,
          setOptions: setOptions$1
        }
      })
    ), document.removeEventListener("DOMContentLoaded", e);
  };
  document.readyState !== "loading" ? setTimeout(() => e(), 0) : document.addEventListener("DOMContentLoaded", e);
  const t = () => forin(getOptions(), (i, a) => {
    OptionTypes[i] = a[1];
  });
  OptionTypes = {}, t(), create$f = (...i) => {
    const a = createApp$1(...i);
    return a.on("destroy", destroy), state.apps.push(a), createAppAPI(a);
  }, destroy = (i) => {
    const a = state.apps.findIndex((n) => n.isAttachedTo(i));
    return a >= 0 ? (state.apps.splice(a, 1)[0].restoreElement(), !0) : !1;
  }, parse = (i) => Array.from(i.querySelectorAll(`.${name}`)).filter(
    (l) => !state.apps.find((r) => r.isAttachedTo(l))
  ).map((l) => create$f(l)), find = (i) => {
    const a = state.apps.find((n) => n.isAttachedTo(i));
    return a ? createAppAPI(a) : null;
  }, registerPlugin = (...i) => {
    i.forEach(createAppPlugin), t();
  }, getOptions$1 = () => {
    const i = {};
    return forin(getOptions(), (a, n) => {
      i[a] = n[0];
    }), i;
  }, setOptions$1 = (i) => (isObject$1(i) && (state.apps.forEach((a) => {
    a.setOptions(i);
  }), setOptions(i)), getOptions$1());
}
/*!
 * FilePondPluginFileValidateSize 2.2.8
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */
const plugin$3 = ({ addFilter: e, utils: t }) => {
  const { Type: i, replaceInString: a, toNaturalFileSize: n } = t;
  return e("ALLOW_HOPPER_ITEM", (l, { query: r }) => {
    if (!r("GET_ALLOW_FILE_SIZE_VALIDATION"))
      return !0;
    const s = r("GET_MAX_FILE_SIZE");
    if (s !== null && l.size > s)
      return !1;
    const o = r("GET_MIN_FILE_SIZE");
    return !(o !== null && l.size < o);
  }), e(
    "LOAD_FILE",
    (l, { query: r }) => new Promise((s, o) => {
      if (!r("GET_ALLOW_FILE_SIZE_VALIDATION"))
        return s(l);
      const d = r("GET_FILE_VALIDATE_SIZE_FILTER");
      if (d && !d(l))
        return s(l);
      const c = r("GET_MAX_FILE_SIZE");
      if (c !== null && l.size > c) {
        o({
          status: {
            main: r("GET_LABEL_MAX_FILE_SIZE_EXCEEDED"),
            sub: a(r("GET_LABEL_MAX_FILE_SIZE"), {
              filesize: n(
                c,
                ".",
                r("GET_FILE_SIZE_BASE"),
                r("GET_FILE_SIZE_LABELS", r)
              )
            })
          }
        });
        return;
      }
      const u = r("GET_MIN_FILE_SIZE");
      if (u !== null && l.size < u) {
        o({
          status: {
            main: r("GET_LABEL_MIN_FILE_SIZE_EXCEEDED"),
            sub: a(r("GET_LABEL_MIN_FILE_SIZE"), {
              filesize: n(
                u,
                ".",
                r("GET_FILE_SIZE_BASE"),
                r("GET_FILE_SIZE_LABELS", r)
              )
            })
          }
        });
        return;
      }
      const f = r("GET_MAX_TOTAL_FILE_SIZE");
      if (f !== null && r("GET_ACTIVE_ITEMS").reduce((m, h) => m + h.fileSize, 0) > f) {
        o({
          status: {
            main: r("GET_LABEL_MAX_TOTAL_FILE_SIZE_EXCEEDED"),
            sub: a(r("GET_LABEL_MAX_TOTAL_FILE_SIZE"), {
              filesize: n(
                f,
                ".",
                r("GET_FILE_SIZE_BASE"),
                r("GET_FILE_SIZE_LABELS", r)
              )
            })
          }
        });
        return;
      }
      s(l);
    })
  ), {
    options: {
      // Enable or disable file type validation
      allowFileSizeValidation: [!0, i.BOOLEAN],
      // Max individual file size in bytes
      maxFileSize: [null, i.INT],
      // Min individual file size in bytes
      minFileSize: [null, i.INT],
      // Max total file size in bytes
      maxTotalFileSize: [null, i.INT],
      // Filter the files that need to be validated for size
      fileValidateSizeFilter: [null, i.FUNCTION],
      // error labels
      labelMinFileSizeExceeded: ["File is too small", i.STRING],
      labelMinFileSize: ["Minimum file size is {filesize}", i.STRING],
      labelMaxFileSizeExceeded: ["File is too large", i.STRING],
      labelMaxFileSize: ["Maximum file size is {filesize}", i.STRING],
      labelMaxTotalFileSizeExceeded: ["Maximum total size exceeded", i.STRING],
      labelMaxTotalFileSize: ["Maximum total file size is {filesize}", i.STRING]
    }
  };
}, isBrowser$3 = typeof window < "u" && typeof window.document < "u";
isBrowser$3 && document.dispatchEvent(new CustomEvent("FilePond:pluginloaded", { detail: plugin$3 }));
/*!
 * FilePondPluginFileValidateType 1.2.9
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */
const plugin$2 = ({ addFilter: e, utils: t }) => {
  const {
    Type: i,
    isString: a,
    replaceInString: n,
    guesstimateMimeType: l,
    getExtensionFromFilename: r,
    getFilenameFromURL: s
  } = t, o = (p, m) => {
    const h = (/^[^/]+/.exec(p) || []).pop(), I = m.slice(0, -2);
    return h === I;
  }, d = (p, m) => p.some((h) => /\*$/.test(h) ? o(m, h) : h === m), c = (p) => {
    let m = "";
    if (a(p)) {
      const h = s(p), I = r(h);
      I && (m = l(I));
    } else
      m = p.type;
    return m;
  }, u = (p, m, h) => {
    if (m.length === 0)
      return !0;
    const I = c(p);
    return h ? new Promise((b, g) => {
      h(p, I).then((E) => {
        d(m, E) ? b() : g();
      }).catch(g);
    }) : d(m, I);
  }, f = (p) => (m) => p[m] === null ? !1 : p[m] || m;
  return e(
    "SET_ATTRIBUTE_TO_OPTION_MAP",
    (p) => Object.assign(p, {
      accept: "acceptedFileTypes"
    })
  ), e("ALLOW_HOPPER_ITEM", (p, { query: m }) => m("GET_ALLOW_FILE_TYPE_VALIDATION") ? u(p, m("GET_ACCEPTED_FILE_TYPES")) : !0), e(
    "LOAD_FILE",
    (p, { query: m }) => new Promise((h, I) => {
      if (!m("GET_ALLOW_FILE_TYPE_VALIDATION")) {
        h(p);
        return;
      }
      const b = m("GET_ACCEPTED_FILE_TYPES"), g = m("GET_FILE_VALIDATE_TYPE_DETECT_TYPE"), E = u(p, b, g), T = () => {
        const S = b.map(
          f(
            m("GET_FILE_VALIDATE_TYPE_LABEL_EXPECTED_TYPES_MAP")
          )
        ).filter((M) => M !== !1), L = S.filter(
          (M, y) => S.indexOf(M) === y
        );
        I({
          status: {
            main: m("GET_LABEL_FILE_TYPE_NOT_ALLOWED"),
            sub: n(
              m("GET_FILE_VALIDATE_TYPE_LABEL_EXPECTED_TYPES"),
              {
                allTypes: L.join(", "),
                allButLastType: L.slice(0, -1).join(", "),
                lastType: L[L.length - 1]
              }
            )
          }
        });
      };
      if (typeof E == "boolean")
        return E ? h(p) : T();
      E.then(() => {
        h(p);
      }).catch(T);
    })
  ), {
    // default options
    options: {
      // Enable or disable file type validation
      allowFileTypeValidation: [!0, i.BOOLEAN],
      // What file types to accept
      acceptedFileTypes: [[], i.ARRAY],
      // - must be comma separated
      // - mime types: image/png, image/jpeg, image/gif
      // - extensions: .png, .jpg, .jpeg ( not enabled yet )
      // - wildcards: image/*
      // label to show when a type is not allowed
      labelFileTypeNotAllowed: ["File is of invalid type", i.STRING],
      // nicer label
      fileValidateTypeLabelExpectedTypes: [
        "Expects {allButLastType} or {lastType}",
        i.STRING
      ],
      // map mime types to extensions
      fileValidateTypeLabelExpectedTypesMap: [{}, i.OBJECT],
      // Custom function to detect type of file
      fileValidateTypeDetectType: [null, i.FUNCTION]
    }
  };
}, isBrowser$2 = typeof window < "u" && typeof window.document < "u";
isBrowser$2 && document.dispatchEvent(new CustomEvent("FilePond:pluginloaded", { detail: plugin$2 }));
/*!
 * FilePondPluginImagePreview 4.6.12
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */
const isPreviewableImage = (e) => /^image/.test(e.type), vectorMultiply = (e, t) => createVector(e.x * t, e.y * t), vectorAdd = (e, t) => createVector(e.x + t.x, e.y + t.y), vectorNormalize = (e) => {
  const t = Math.sqrt(e.x * e.x + e.y * e.y);
  return t === 0 ? {
    x: 0,
    y: 0
  } : createVector(e.x / t, e.y / t);
}, vectorRotate = (e, t, i) => {
  const a = Math.cos(t), n = Math.sin(t), l = createVector(e.x - i.x, e.y - i.y);
  return createVector(
    i.x + a * l.x - n * l.y,
    i.y + n * l.x + a * l.y
  );
}, createVector = (e = 0, t = 0) => ({ x: e, y: t }), getMarkupValue = (e, t, i = 1, a) => {
  if (typeof e == "string")
    return parseFloat(e) * i;
  if (typeof e == "number")
    return e * (a ? t[a] : Math.min(t.width, t.height));
}, getMarkupStyles = (e, t, i) => {
  const a = e.borderStyle || e.lineStyle || "solid", n = e.backgroundColor || e.fontColor || "transparent", l = e.borderColor || e.lineColor || "transparent", r = getMarkupValue(
    e.borderWidth || e.lineWidth,
    t,
    i
  ), s = e.lineCap || "round", o = e.lineJoin || "round", d = typeof a == "string" ? "" : a.map((u) => getMarkupValue(u, t, i)).join(","), c = e.opacity || 1;
  return {
    "stroke-linecap": s,
    "stroke-linejoin": o,
    "stroke-width": r || 0,
    "stroke-dasharray": d,
    stroke: l,
    fill: n,
    opacity: c
  };
}, isDefined = (e) => e != null, getMarkupRect = (e, t, i = 1) => {
  let a = getMarkupValue(e.x, t, i, "width") || getMarkupValue(e.left, t, i, "width"), n = getMarkupValue(e.y, t, i, "height") || getMarkupValue(e.top, t, i, "height"), l = getMarkupValue(e.width, t, i, "width"), r = getMarkupValue(e.height, t, i, "height"), s = getMarkupValue(e.right, t, i, "width"), o = getMarkupValue(e.bottom, t, i, "height");
  return isDefined(n) || (isDefined(r) && isDefined(o) ? n = t.height - r - o : n = o), isDefined(a) || (isDefined(l) && isDefined(s) ? a = t.width - l - s : a = s), isDefined(l) || (isDefined(a) && isDefined(s) ? l = t.width - a - s : l = 0), isDefined(r) || (isDefined(n) && isDefined(o) ? r = t.height - n - o : r = 0), {
    x: a || 0,
    y: n || 0,
    width: l || 0,
    height: r || 0
  };
}, pointsToPathShape = (e) => e.map((t, i) => `${i === 0 ? "M" : "L"} ${t.x} ${t.y}`).join(" "), setAttributes = (e, t) => Object.keys(t).forEach((i) => e.setAttribute(i, t[i])), ns = "http://www.w3.org/2000/svg", svg = (e, t) => {
  const i = document.createElementNS(ns, e);
  return t && setAttributes(i, t), i;
}, updateRect = (e) => setAttributes(e, {
  ...e.rect,
  ...e.styles
}), updateEllipse = (e) => {
  const t = e.rect.x + e.rect.width * 0.5, i = e.rect.y + e.rect.height * 0.5, a = e.rect.width * 0.5, n = e.rect.height * 0.5;
  return setAttributes(e, {
    cx: t,
    cy: i,
    rx: a,
    ry: n,
    ...e.styles
  });
}, IMAGE_FIT_STYLE = {
  contain: "xMidYMid meet",
  cover: "xMidYMid slice"
}, updateImage = (e, t) => {
  setAttributes(e, {
    ...e.rect,
    ...e.styles,
    preserveAspectRatio: IMAGE_FIT_STYLE[t.fit] || "none"
  });
}, TEXT_ANCHOR = {
  left: "start",
  center: "middle",
  right: "end"
}, updateText = (e, t, i, a) => {
  const n = getMarkupValue(t.fontSize, i, a), l = t.fontFamily || "sans-serif", r = t.fontWeight || "normal", s = TEXT_ANCHOR[t.textAlign] || "start";
  setAttributes(e, {
    ...e.rect,
    ...e.styles,
    "stroke-width": 0,
    "font-weight": r,
    "font-size": n,
    "font-family": l,
    "text-anchor": s
  }), e.text !== t.text && (e.text = t.text, e.textContent = t.text.length ? t.text : " ");
}, updateLine = (e, t, i, a) => {
  setAttributes(e, {
    ...e.rect,
    ...e.styles,
    fill: "none"
  });
  const n = e.childNodes[0], l = e.childNodes[1], r = e.childNodes[2], s = e.rect, o = {
    x: e.rect.x + e.rect.width,
    y: e.rect.y + e.rect.height
  };
  if (setAttributes(n, {
    x1: s.x,
    y1: s.y,
    x2: o.x,
    y2: o.y
  }), !t.lineDecoration) return;
  l.style.display = "none", r.style.display = "none";
  const d = vectorNormalize({
    x: o.x - s.x,
    y: o.y - s.y
  }), c = getMarkupValue(0.05, i, a);
  if (t.lineDecoration.indexOf("arrow-begin") !== -1) {
    const u = vectorMultiply(d, c), f = vectorAdd(s, u), p = vectorRotate(s, 2, f), m = vectorRotate(s, -2, f);
    setAttributes(l, {
      style: "display:block;",
      d: `M${p.x},${p.y} L${s.x},${s.y} L${m.x},${m.y}`
    });
  }
  if (t.lineDecoration.indexOf("arrow-end") !== -1) {
    const u = vectorMultiply(d, -c), f = vectorAdd(o, u), p = vectorRotate(o, 2, f), m = vectorRotate(o, -2, f);
    setAttributes(r, {
      style: "display:block;",
      d: `M${p.x},${p.y} L${o.x},${o.y} L${m.x},${m.y}`
    });
  }
}, updatePath = (e, t, i, a) => {
  setAttributes(e, {
    ...e.styles,
    fill: "none",
    d: pointsToPathShape(
      t.points.map((n) => ({
        x: getMarkupValue(n.x, i, a, "width"),
        y: getMarkupValue(n.y, i, a, "height")
      }))
    )
  });
}, createShape = (e) => (t) => svg(e, { id: t.id }), createImage = (e) => {
  const t = svg("image", {
    id: e.id,
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    opacity: "0"
  });
  return t.onload = () => {
    t.setAttribute("opacity", e.opacity || 1);
  }, t.setAttributeNS(
    "http://www.w3.org/1999/xlink",
    "xlink:href",
    e.src
  ), t;
}, createLine = (e) => {
  const t = svg("g", {
    id: e.id,
    "stroke-linecap": "round",
    "stroke-linejoin": "round"
  }), i = svg("line");
  t.appendChild(i);
  const a = svg("path");
  t.appendChild(a);
  const n = svg("path");
  return t.appendChild(n), t;
}, CREATE_TYPE_ROUTES = {
  image: createImage,
  rect: createShape("rect"),
  ellipse: createShape("ellipse"),
  text: createShape("text"),
  path: createShape("path"),
  line: createLine
}, UPDATE_TYPE_ROUTES = {
  rect: updateRect,
  ellipse: updateEllipse,
  image: updateImage,
  text: updateText,
  path: updatePath,
  line: updateLine
}, createMarkupByType = (e, t) => CREATE_TYPE_ROUTES[e](t), updateMarkupByType = (e, t, i, a, n) => {
  t !== "path" && (e.rect = getMarkupRect(i, a, n)), e.styles = getMarkupStyles(i, a, n), UPDATE_TYPE_ROUTES[t](e, i, a, n);
}, MARKUP_RECT = [
  "x",
  "y",
  "left",
  "top",
  "right",
  "bottom",
  "width",
  "height"
], toOptionalFraction = (e) => typeof e == "string" && /%/.test(e) ? parseFloat(e) / 100 : e, prepareMarkup = (e) => {
  const [t, i] = e, a = i.points ? {} : MARKUP_RECT.reduce((n, l) => (n[l] = toOptionalFraction(i[l]), n), {});
  return [
    t,
    {
      zIndex: 0,
      ...i,
      ...a
    }
  ];
}, sortMarkupByZIndex = (e, t) => e[1].zIndex > t[1].zIndex ? 1 : e[1].zIndex < t[1].zIndex ? -1 : 0, createMarkupView = (e) => e.utils.createView({
  name: "image-preview-markup",
  tag: "svg",
  ignoreRect: !0,
  mixins: {
    apis: ["width", "height", "crop", "markup", "resize", "dirty"]
  },
  write: ({ root: t, props: i }) => {
    if (!i.dirty) return;
    const { crop: a, resize: n, markup: l } = i, r = i.width, s = i.height;
    let o = a.width, d = a.height;
    if (n) {
      const { size: p } = n;
      let m = p && p.width, h = p && p.height;
      const I = n.mode, b = n.upscale;
      m && !h && (h = m), h && !m && (m = h);
      const g = o < m && d < h;
      if (!g || g && b) {
        let E = m / o, T = h / d;
        if (I === "force")
          o = m, d = h;
        else {
          let S;
          I === "cover" ? S = Math.max(E, T) : I === "contain" && (S = Math.min(E, T)), o = o * S, d = d * S;
        }
      }
    }
    const c = {
      width: r,
      height: s
    };
    t.element.setAttribute("width", c.width), t.element.setAttribute("height", c.height);
    const u = Math.min(r / o, s / d);
    t.element.innerHTML = "";
    const f = t.query("GET_IMAGE_PREVIEW_MARKUP_FILTER");
    l.filter(f).map(prepareMarkup).sort(sortMarkupByZIndex).forEach((p) => {
      const [m, h] = p, I = createMarkupByType(m, h);
      updateMarkupByType(I, m, h, c, u), t.element.appendChild(I);
    });
  }
}), createVector$1 = (e, t) => ({ x: e, y: t }), vectorDot = (e, t) => e.x * t.x + e.y * t.y, vectorSubtract = (e, t) => createVector$1(e.x - t.x, e.y - t.y), vectorDistanceSquared = (e, t) => vectorDot(vectorSubtract(e, t), vectorSubtract(e, t)), vectorDistance = (e, t) => Math.sqrt(vectorDistanceSquared(e, t)), getOffsetPointOnEdge = (e, t) => {
  const i = e, a = 1.5707963267948966, n = t, l = 1.5707963267948966 - t, r = Math.sin(a), s = Math.sin(n), o = Math.sin(l), d = Math.cos(l), c = i / r, u = c * s, f = c * o;
  return createVector$1(d * u, d * f);
}, getRotatedRectSize = (e, t) => {
  const i = e.width, a = e.height, n = getOffsetPointOnEdge(i, t), l = getOffsetPointOnEdge(a, t), r = createVector$1(e.x + Math.abs(n.x), e.y - Math.abs(n.y)), s = createVector$1(
    e.x + e.width + Math.abs(l.y),
    e.y + Math.abs(l.x)
  ), o = createVector$1(
    e.x - Math.abs(l.y),
    e.y + e.height - Math.abs(l.x)
  );
  return {
    width: vectorDistance(r, s),
    height: vectorDistance(r, o)
  };
}, calculateCanvasSize = (e, t, i = 1) => {
  const a = e.height / e.width;
  let n = 1, l = t, r = 1, s = a;
  s > l && (s = l, r = s / a);
  const o = Math.max(n / r, l / s), d = e.width / (i * o * r), c = d * t;
  return {
    width: d,
    height: c
  };
}, getImageRectZoomFactor = (e, t, i, a) => {
  const n = a.x > 0.5 ? 1 - a.x : a.x, l = a.y > 0.5 ? 1 - a.y : a.y, r = n * 2 * e.width, s = l * 2 * e.height, o = getRotatedRectSize(t, i);
  return Math.max(
    o.width / r,
    o.height / s
  );
}, getCenteredCropRect = (e, t) => {
  let i = e.width, a = i * t;
  a > e.height && (a = e.height, i = a / t);
  const n = (e.width - i) * 0.5, l = (e.height - a) * 0.5;
  return {
    x: n,
    y: l,
    width: i,
    height: a
  };
}, getCurrentCropSize = (e, t = {}) => {
  let { zoom: i, rotation: a, center: n, aspectRatio: l } = t;
  l || (l = e.height / e.width);
  const r = calculateCanvasSize(e, l, i), s = {
    width: r.width,
    height: r.height
  }, o = typeof t.scaleToFit > "u" || t.scaleToFit, d = getImageRectZoomFactor(
    e,
    getCenteredCropRect(s, l),
    a,
    o ? n : { x: 0.5, y: 0.5 }
  ), c = i * d;
  return {
    widthFloat: r.width / c,
    heightFloat: r.height / c,
    width: Math.round(r.width / c),
    height: Math.round(r.height / c)
  };
}, IMAGE_SCALE_SPRING_PROPS = {
  type: "spring",
  stiffness: 0.5,
  damping: 0.45,
  mass: 10
}, createBitmapView = (e) => e.utils.createView({
  name: "image-bitmap",
  ignoreRect: !0,
  mixins: { styles: ["scaleX", "scaleY"] },
  create: ({ root: t, props: i }) => {
    t.appendChild(i.image);
  }
}), createImageCanvasWrapper = (e) => e.utils.createView({
  name: "image-canvas-wrapper",
  tag: "div",
  ignoreRect: !0,
  mixins: {
    apis: ["crop", "width", "height"],
    styles: [
      "originX",
      "originY",
      "translateX",
      "translateY",
      "scaleX",
      "scaleY",
      "rotateZ"
    ],
    animations: {
      originX: IMAGE_SCALE_SPRING_PROPS,
      originY: IMAGE_SCALE_SPRING_PROPS,
      scaleX: IMAGE_SCALE_SPRING_PROPS,
      scaleY: IMAGE_SCALE_SPRING_PROPS,
      translateX: IMAGE_SCALE_SPRING_PROPS,
      translateY: IMAGE_SCALE_SPRING_PROPS,
      rotateZ: IMAGE_SCALE_SPRING_PROPS
    }
  },
  create: ({ root: t, props: i }) => {
    i.width = i.image.width, i.height = i.image.height, t.ref.bitmap = t.appendChildView(
      t.createChildView(createBitmapView(e), { image: i.image })
    );
  },
  write: ({ root: t, props: i }) => {
    const { flip: a } = i.crop, { bitmap: n } = t.ref;
    n.scaleX = a.horizontal ? -1 : 1, n.scaleY = a.vertical ? -1 : 1;
  }
}), createClipView = (e) => e.utils.createView({
  name: "image-clip",
  tag: "div",
  ignoreRect: !0,
  mixins: {
    apis: [
      "crop",
      "markup",
      "resize",
      "width",
      "height",
      "dirty",
      "background"
    ],
    styles: ["width", "height", "opacity"],
    animations: {
      opacity: { type: "tween", duration: 250 }
    }
  },
  didWriteView: function({ root: t, props: i }) {
    i.background && (t.element.style.backgroundColor = i.background);
  },
  create: ({ root: t, props: i }) => {
    t.ref.image = t.appendChildView(
      t.createChildView(
        createImageCanvasWrapper(e),
        Object.assign({}, i)
      )
    ), t.ref.createMarkup = () => {
      t.ref.markup || (t.ref.markup = t.appendChildView(
        t.createChildView(createMarkupView(e), Object.assign({}, i))
      ));
    }, t.ref.destroyMarkup = () => {
      t.ref.markup && (t.removeChildView(t.ref.markup), t.ref.markup = null);
    };
    const a = t.query(
      "GET_IMAGE_PREVIEW_TRANSPARENCY_INDICATOR"
    );
    a !== null && (a === "grid" ? t.element.dataset.transparencyIndicator = a : t.element.dataset.transparencyIndicator = "color");
  },
  write: ({ root: t, props: i, shouldOptimize: a }) => {
    const { crop: n, markup: l, resize: r, dirty: s, width: o, height: d } = i;
    t.ref.image.crop = n;
    const c = {
      width: o,
      height: d,
      center: {
        x: o * 0.5,
        y: d * 0.5
      }
    }, u = {
      width: t.ref.image.width,
      height: t.ref.image.height
    }, f = {
      x: n.center.x * u.width,
      y: n.center.y * u.height
    }, p = {
      x: c.center.x - u.width * n.center.x,
      y: c.center.y - u.height * n.center.y
    }, m = Math.PI * 2 + n.rotation % (Math.PI * 2), h = n.aspectRatio || u.height / u.width, I = typeof n.scaleToFit > "u" || n.scaleToFit, b = getImageRectZoomFactor(
      u,
      getCenteredCropRect(c, h),
      m,
      I ? n.center : { x: 0.5, y: 0.5 }
    ), g = n.zoom * b;
    l && l.length ? (t.ref.createMarkup(), t.ref.markup.width = o, t.ref.markup.height = d, t.ref.markup.resize = r, t.ref.markup.dirty = s, t.ref.markup.markup = l, t.ref.markup.crop = getCurrentCropSize(u, n)) : t.ref.markup && t.ref.destroyMarkup();
    const E = t.ref.image;
    if (a) {
      E.originX = null, E.originY = null, E.translateX = null, E.translateY = null, E.rotateZ = null, E.scaleX = null, E.scaleY = null;
      return;
    }
    E.originX = f.x, E.originY = f.y, E.translateX = p.x, E.translateY = p.y, E.rotateZ = m, E.scaleX = g, E.scaleY = g;
  }
}), createImageView = (e) => e.utils.createView({
  name: "image-preview",
  tag: "div",
  ignoreRect: !0,
  mixins: {
    apis: ["image", "crop", "markup", "resize", "dirty", "background"],
    styles: ["translateY", "scaleX", "scaleY", "opacity"],
    animations: {
      scaleX: IMAGE_SCALE_SPRING_PROPS,
      scaleY: IMAGE_SCALE_SPRING_PROPS,
      translateY: IMAGE_SCALE_SPRING_PROPS,
      opacity: { type: "tween", duration: 400 }
    }
  },
  create: ({ root: t, props: i }) => {
    t.ref.clip = t.appendChildView(
      t.createChildView(createClipView(e), {
        id: i.id,
        image: i.image,
        crop: i.crop,
        markup: i.markup,
        resize: i.resize,
        dirty: i.dirty,
        background: i.background
      })
    );
  },
  write: ({ root: t, props: i, shouldOptimize: a }) => {
    const { clip: n } = t.ref, { image: l, crop: r, markup: s, resize: o, dirty: d } = i;
    if (n.crop = r, n.markup = s, n.resize = o, n.dirty = d, n.opacity = a ? 0 : 1, a || t.rect.element.hidden) return;
    const c = l.height / l.width;
    let u = r.aspectRatio || c;
    const f = t.rect.inner.width, p = t.rect.inner.height;
    let m = t.query("GET_IMAGE_PREVIEW_HEIGHT");
    const h = t.query("GET_IMAGE_PREVIEW_MIN_HEIGHT"), I = t.query("GET_IMAGE_PREVIEW_MAX_HEIGHT"), b = t.query("GET_PANEL_ASPECT_RATIO"), g = t.query("GET_ALLOW_MULTIPLE");
    b && !g && (m = f * b, u = b);
    let E = m !== null ? m : Math.max(
      h,
      Math.min(f * u, I)
    ), T = E / u;
    T > f && (T = f, E = T * u), E > p && (E = p, T = p / u), n.width = T, n.height = E;
  }
});
let SVG_MASK = `<svg width="500" height="200" viewBox="0 0 500 200" preserveAspectRatio="none">
    <defs>
        <radialGradient id="gradient-__UID__" cx=".5" cy="1.25" r="1.15">
            <stop offset='50%' stop-color='#000000'/>
            <stop offset='56%' stop-color='#0a0a0a'/>
            <stop offset='63%' stop-color='#262626'/>
            <stop offset='69%' stop-color='#4f4f4f'/>
            <stop offset='75%' stop-color='#808080'/>
            <stop offset='81%' stop-color='#b1b1b1'/>
            <stop offset='88%' stop-color='#dadada'/>
            <stop offset='94%' stop-color='#f6f6f6'/>
            <stop offset='100%' stop-color='#ffffff'/>
        </radialGradient>
        <mask id="mask-__UID__">
            <rect x="0" y="0" width="500" height="200" fill="url(#gradient-__UID__)"></rect>
        </mask>
    </defs>
    <rect x="0" width="500" height="200" fill="currentColor" mask="url(#mask-__UID__)"></rect>
</svg>`, SVGMaskUniqueId = 0;
const createImageOverlayView = (e) => e.utils.createView({
  name: "image-preview-overlay",
  tag: "div",
  ignoreRect: !0,
  create: ({ root: t, props: i }) => {
    let a = SVG_MASK;
    if (document.querySelector("base")) {
      const n = new URL(
        window.location.href.replace(window.location.hash, "")
      ).href;
      a = a.replace(/url\(\#/g, "url(" + n + "#");
    }
    SVGMaskUniqueId++, t.element.classList.add(
      `filepond--image-preview-overlay-${i.status}`
    ), t.element.innerHTML = a.replace(/__UID__/g, SVGMaskUniqueId);
  },
  mixins: {
    styles: ["opacity"],
    animations: {
      opacity: { type: "spring", mass: 25 }
    }
  }
}), BitmapWorker = function() {
  self.onmessage = (e) => {
    createImageBitmap(e.data.message.file).then((t) => {
      self.postMessage({ id: e.data.id, message: t }, [t]);
    });
  };
}, ColorMatrixWorker = function() {
  self.onmessage = (e) => {
    const t = e.data.message.imageData, i = e.data.message.colorMatrix, a = t.data, n = a.length, l = i[0], r = i[1], s = i[2], o = i[3], d = i[4], c = i[5], u = i[6], f = i[7], p = i[8], m = i[9], h = i[10], I = i[11], b = i[12], g = i[13], E = i[14], T = i[15], S = i[16], L = i[17], M = i[18], y = i[19];
    let x = 0, v = 0, P = 0, O = 0, B = 0;
    for (; x < n; x += 4)
      v = a[x] / 255, P = a[x + 1] / 255, O = a[x + 2] / 255, B = a[x + 3] / 255, a[x] = Math.max(
        0,
        Math.min((v * l + P * r + O * s + B * o + d) * 255, 255)
      ), a[x + 1] = Math.max(
        0,
        Math.min((v * c + P * u + O * f + B * p + m) * 255, 255)
      ), a[x + 2] = Math.max(
        0,
        Math.min((v * h + P * I + O * b + B * g + E) * 255, 255)
      ), a[x + 3] = Math.max(
        0,
        Math.min((v * T + P * S + O * L + B * M + y) * 255, 255)
      );
    self.postMessage({ id: e.data.id, message: t }, [
      t.data.buffer
    ]);
  };
}, getImageSize$1 = (e, t) => {
  let i = new Image();
  i.onload = () => {
    const a = i.naturalWidth, n = i.naturalHeight;
    i = null, t(a, n);
  }, i.src = e;
}, transforms = {
  1: () => [1, 0, 0, 1, 0, 0],
  2: (e) => [-1, 0, 0, 1, e, 0],
  3: (e, t) => [-1, 0, 0, -1, e, t],
  4: (e, t) => [1, 0, 0, -1, 0, t],
  5: () => [0, 1, 1, 0, 0, 0],
  6: (e, t) => [0, 1, -1, 0, t, 0],
  7: (e, t) => [0, -1, -1, 0, t, e],
  8: (e) => [0, -1, 1, 0, 0, e]
}, fixImageOrientation = (e, t, i, a) => {
  a !== -1 && e.transform.apply(e, transforms[a](t, i));
}, createPreviewImage = (e, t, i, a) => {
  t = Math.round(t), i = Math.round(i);
  const n = document.createElement("canvas");
  n.width = t, n.height = i;
  const l = n.getContext("2d");
  return a >= 5 && a <= 8 && ([t, i] = [i, t]), fixImageOrientation(l, t, i, a), l.drawImage(e, 0, 0, t, i), n;
}, isBitmap = (e) => /^image/.test(e.type) && !/svg/.test(e.type), MAX_WIDTH = 10, MAX_HEIGHT = 10, calculateAverageColor = (e) => {
  const t = Math.min(MAX_WIDTH / e.width, MAX_HEIGHT / e.height), i = document.createElement("canvas"), a = i.getContext("2d"), n = i.width = Math.ceil(e.width * t), l = i.height = Math.ceil(e.height * t);
  a.drawImage(e, 0, 0, n, l);
  let r = null;
  try {
    r = a.getImageData(0, 0, n, l).data;
  } catch {
    return null;
  }
  const s = r.length;
  let o = 0, d = 0, c = 0, u = 0;
  for (; u < s; u += 4)
    o += r[u] * r[u], d += r[u + 1] * r[u + 1], c += r[u + 2] * r[u + 2];
  return o = averageColor(o, s), d = averageColor(d, s), c = averageColor(c, s), { r: o, g: d, b: c };
}, averageColor = (e, t) => Math.floor(Math.sqrt(e / (t / 4))), cloneCanvas = (e, t) => (t = t || document.createElement("canvas"), t.width = e.width, t.height = e.height, t.getContext("2d").drawImage(e, 0, 0), t), cloneImageData = (e) => {
  let t;
  try {
    t = new ImageData(e.width, e.height);
  } catch {
    t = document.createElement("canvas").getContext("2d").createImageData(e.width, e.height);
  }
  return t.data.set(new Uint8ClampedArray(e.data)), t;
}, loadImage = (e) => new Promise((t, i) => {
  const a = new Image();
  a.crossOrigin = "Anonymous", a.onload = () => {
    t(a);
  }, a.onerror = (n) => {
    i(n);
  }, a.src = e;
}), createImageWrapperView = (e) => {
  const t = createImageOverlayView(e), i = createImageView(e), { createWorker: a } = e.utils, n = (g, E, T) => new Promise((S) => {
    g.ref.imageData || (g.ref.imageData = T.getContext("2d").getImageData(0, 0, T.width, T.height));
    const L = cloneImageData(g.ref.imageData);
    if (!E || E.length !== 20)
      return T.getContext("2d").putImageData(L, 0, 0), S();
    const M = a(ColorMatrixWorker);
    M.post(
      {
        imageData: L,
        colorMatrix: E
      },
      (y) => {
        T.getContext("2d").putImageData(y, 0, 0), M.terminate(), S();
      },
      [L.data.buffer]
    );
  }), l = (g, E) => {
    g.removeChildView(E), E.image.width = 1, E.image.height = 1, E._destroy();
  }, r = ({ root: g }) => {
    const E = g.ref.images.shift();
    return E.opacity = 0, E.translateY = -15, g.ref.imageViewBin.push(E), E;
  }, s = ({ root: g, props: E, image: T }) => {
    const S = E.id, L = g.query("GET_ITEM", { id: S });
    if (!L) return;
    const M = L.getMetadata("crop") || {
      center: {
        x: 0.5,
        y: 0.5
      },
      flip: {
        horizontal: !1,
        vertical: !1
      },
      zoom: 1,
      rotation: 0,
      aspectRatio: null
    }, y = g.query(
      "GET_IMAGE_TRANSFORM_CANVAS_BACKGROUND_COLOR"
    );
    let x, v, P = !1;
    g.query("GET_IMAGE_PREVIEW_MARKUP_SHOW") && (x = L.getMetadata("markup") || [], v = L.getMetadata("resize"), P = !0);
    const O = g.appendChildView(
      g.createChildView(i, {
        id: S,
        image: T,
        crop: M,
        resize: v,
        markup: x,
        dirty: P,
        background: y,
        opacity: 0,
        scaleX: 1.15,
        scaleY: 1.15,
        translateY: 15
      }),
      g.childViews.length
    );
    g.ref.images.push(O), O.opacity = 1, O.scaleX = 1, O.scaleY = 1, O.translateY = 0, setTimeout(() => {
      g.dispatch("DID_IMAGE_PREVIEW_SHOW", { id: S });
    }, 250);
  }, o = ({ root: g, props: E }) => {
    const T = g.query("GET_ITEM", { id: E.id });
    if (!T) return;
    const S = g.ref.images[g.ref.images.length - 1];
    S.crop = T.getMetadata("crop"), S.background = g.query(
      "GET_IMAGE_TRANSFORM_CANVAS_BACKGROUND_COLOR"
    ), g.query("GET_IMAGE_PREVIEW_MARKUP_SHOW") && (S.dirty = !0, S.resize = T.getMetadata("resize"), S.markup = T.getMetadata("markup"));
  }, d = ({ root: g, props: E, action: T }) => {
    if (!/crop|filter|markup|resize/.test(T.change.key) || !g.ref.images.length) return;
    const S = g.query("GET_ITEM", { id: E.id });
    if (S) {
      if (/filter/.test(T.change.key)) {
        const L = g.ref.images[g.ref.images.length - 1];
        n(g, T.change.value, L.image);
        return;
      }
      if (/crop|markup|resize/.test(T.change.key)) {
        const L = S.getMetadata("crop"), M = g.ref.images[g.ref.images.length - 1];
        if (L && L.aspectRatio && M.crop && M.crop.aspectRatio && Math.abs(L.aspectRatio - M.crop.aspectRatio) > 1e-5) {
          const y = r({ root: g });
          s({ root: g, props: E, image: cloneCanvas(y.image) });
        } else
          o({ root: g, props: E });
      }
    }
  }, c = (g) => {
    const T = window.navigator.userAgent.match(/Firefox\/([0-9]+)\./), S = T ? parseInt(T[1]) : null;
    return S !== null && S <= 58 ? !1 : "createImageBitmap" in window && isBitmap(g);
  }, u = ({ root: g, props: E }) => {
    const { id: T } = E, S = g.query("GET_ITEM", T);
    if (!S) return;
    const L = URL.createObjectURL(S.file);
    getImageSize$1(L, (M, y) => {
      g.dispatch("DID_IMAGE_PREVIEW_CALCULATE_SIZE", {
        id: T,
        width: M,
        height: y
      });
    });
  }, f = ({ root: g, props: E }) => {
    const { id: T } = E, S = g.query("GET_ITEM", T);
    if (!S) return;
    const L = URL.createObjectURL(S.file), M = () => {
      loadImage(L).then(y);
    }, y = (x) => {
      URL.revokeObjectURL(L);
      const P = (S.getMetadata("exif") || {}).orientation || -1;
      let { width: O, height: B } = x;
      if (!O || !B) return;
      P >= 5 && P <= 8 && ([O, B] = [B, O]);
      const F = Math.max(1, window.devicePixelRatio * 0.75), R = g.query("GET_IMAGE_PREVIEW_ZOOM_FACTOR") * F, A = B / O, w = g.rect.element.width, z = g.rect.element.height;
      let k = w, V = k * A;
      A > 1 ? (k = Math.min(O, w * R), V = k * A) : (V = Math.min(B, z * R), k = V / A);
      const H = createPreviewImage(
        x,
        k,
        V,
        P
      ), q = () => {
        const Y = g.query(
          "GET_IMAGE_PREVIEW_CALCULATE_AVERAGE_IMAGE_COLOR"
        ) ? calculateAverageColor(data) : null;
        S.setMetadata("color", Y, !0), "close" in x && x.close(), g.ref.overlayShadow.opacity = 1, s({ root: g, props: E, image: H });
      }, U = S.getMetadata("filter");
      U ? n(g, U, H).then(q) : q();
    };
    if (c(S.file)) {
      const x = a(BitmapWorker);
      x.post(
        {
          file: S.file
        },
        (v) => {
          if (x.terminate(), !v) {
            M();
            return;
          }
          y(v);
        }
      );
    } else
      M();
  }, p = ({ root: g }) => {
    const E = g.ref.images[g.ref.images.length - 1];
    E.translateY = 0, E.scaleX = 1, E.scaleY = 1, E.opacity = 1;
  }, m = ({ root: g }) => {
    g.ref.overlayShadow.opacity = 1, g.ref.overlayError.opacity = 0, g.ref.overlaySuccess.opacity = 0;
  }, h = ({ root: g }) => {
    g.ref.overlayShadow.opacity = 0.25, g.ref.overlayError.opacity = 1;
  }, I = ({ root: g }) => {
    g.ref.overlayShadow.opacity = 0.25, g.ref.overlaySuccess.opacity = 1;
  }, b = ({ root: g }) => {
    g.ref.images = [], g.ref.imageData = null, g.ref.imageViewBin = [], g.ref.overlayShadow = g.appendChildView(
      g.createChildView(t, {
        opacity: 0,
        status: "idle"
      })
    ), g.ref.overlaySuccess = g.appendChildView(
      g.createChildView(t, {
        opacity: 0,
        status: "success"
      })
    ), g.ref.overlayError = g.appendChildView(
      g.createChildView(t, {
        opacity: 0,
        status: "failure"
      })
    );
  };
  return e.utils.createView({
    name: "image-preview-wrapper",
    create: b,
    styles: ["height"],
    apis: ["height"],
    destroy: ({ root: g }) => {
      g.ref.images.forEach((E) => {
        E.image.width = 1, E.image.height = 1;
      });
    },
    didWriteView: ({ root: g }) => {
      g.ref.images.forEach((E) => {
        E.dirty = !1;
      });
    },
    write: e.utils.createRoute(
      {
        // image preview stated
        DID_IMAGE_PREVIEW_DRAW: p,
        DID_IMAGE_PREVIEW_CONTAINER_CREATE: u,
        DID_FINISH_CALCULATE_PREVIEWSIZE: f,
        DID_UPDATE_ITEM_METADATA: d,
        // file states
        DID_THROW_ITEM_LOAD_ERROR: h,
        DID_THROW_ITEM_PROCESSING_ERROR: h,
        DID_THROW_ITEM_INVALID: h,
        DID_COMPLETE_ITEM_PROCESSING: I,
        DID_START_ITEM_PROCESSING: m,
        DID_REVERT_ITEM_PROCESSING: m
      },
      ({ root: g }) => {
        const E = g.ref.imageViewBin.filter(
          (T) => T.opacity === 0
        );
        g.ref.imageViewBin = g.ref.imageViewBin.filter(
          (T) => T.opacity > 0
        ), E.forEach((T) => l(g, T)), E.length = 0;
      }
    )
  });
}, plugin$1 = (e) => {
  const { addFilter: t, utils: i } = e, { Type: a, createRoute: n, isFile: l } = i, r = createImageWrapperView(e);
  return t("CREATE_VIEW", (s) => {
    const { is: o, view: d, query: c } = s;
    if (!o("file") || !c("GET_ALLOW_IMAGE_PREVIEW")) return;
    const u = ({ root: I, props: b }) => {
      const { id: g } = b, E = c("GET_ITEM", g);
      if (!E || !l(E.file) || E.archived) return;
      const T = E.file;
      if (!isPreviewableImage(T) || !c("GET_IMAGE_PREVIEW_FILTER_ITEM")(E)) return;
      const S = "createImageBitmap" in (window || {}), L = c("GET_IMAGE_PREVIEW_MAX_FILE_SIZE");
      if (!S && L && T.size > L)
        return;
      I.ref.imagePreview = d.appendChildView(
        d.createChildView(r, { id: g })
      );
      const M = I.query("GET_IMAGE_PREVIEW_HEIGHT");
      M && I.dispatch("DID_UPDATE_PANEL_HEIGHT", {
        id: E.id,
        height: M
      });
      const y = !S && T.size > c("GET_IMAGE_PREVIEW_MAX_INSTANT_PREVIEW_FILE_SIZE");
      I.dispatch("DID_IMAGE_PREVIEW_CONTAINER_CREATE", { id: g }, y);
    }, f = (I, b) => {
      if (!I.ref.imagePreview) return;
      let { id: g } = b;
      const E = I.query("GET_ITEM", { id: g });
      if (!E) return;
      const T = I.query("GET_PANEL_ASPECT_RATIO"), S = I.query("GET_ITEM_PANEL_ASPECT_RATIO"), L = I.query("GET_IMAGE_PREVIEW_HEIGHT");
      if (T || S || L) return;
      let { imageWidth: M, imageHeight: y } = I.ref;
      if (!M || !y) return;
      const x = I.query("GET_IMAGE_PREVIEW_MIN_HEIGHT"), v = I.query("GET_IMAGE_PREVIEW_MAX_HEIGHT"), O = (E.getMetadata("exif") || {}).orientation || -1;
      if (O >= 5 && O <= 8 && ([M, y] = [y, M]), !isBitmap(E.file) || I.query("GET_IMAGE_PREVIEW_UPSCALE")) {
        const w = 2048 / M;
        M *= w, y *= w;
      }
      const B = y / M, F = (E.getMetadata("crop") || {}).aspectRatio || B;
      let D = Math.max(
        x,
        Math.min(y, v)
      );
      const R = I.rect.element.width, A = Math.min(
        R * F,
        D
      );
      I.dispatch("DID_UPDATE_PANEL_HEIGHT", {
        id: E.id,
        height: A
      });
    }, p = ({ root: I }) => {
      I.ref.shouldRescale = !0;
    }, m = ({ root: I, action: b }) => {
      b.change.key === "crop" && (I.ref.shouldRescale = !0);
    }, h = ({ root: I, action: b }) => {
      I.ref.imageWidth = b.width, I.ref.imageHeight = b.height, I.ref.shouldRescale = !0, I.ref.shouldDrawPreview = !0, I.dispatch("KICK");
    };
    d.registerWriter(
      n(
        {
          DID_RESIZE_ROOT: p,
          DID_STOP_RESIZE: p,
          DID_LOAD_ITEM: u,
          DID_IMAGE_PREVIEW_CALCULATE_SIZE: h,
          DID_UPDATE_ITEM_METADATA: m
        },
        ({ root: I, props: b }) => {
          I.ref.imagePreview && (I.rect.element.hidden || (I.ref.shouldRescale && (f(I, b), I.ref.shouldRescale = !1), I.ref.shouldDrawPreview && (requestAnimationFrame(() => {
            requestAnimationFrame(() => {
              I.dispatch("DID_FINISH_CALCULATE_PREVIEWSIZE", {
                id: b.id
              });
            });
          }), I.ref.shouldDrawPreview = !1)));
        }
      )
    );
  }), {
    options: {
      // Enable or disable image preview
      allowImagePreview: [!0, a.BOOLEAN],
      // filters file items to determine which are shown as preview
      imagePreviewFilterItem: [() => !0, a.FUNCTION],
      // Fixed preview height
      imagePreviewHeight: [null, a.INT],
      // Min image height
      imagePreviewMinHeight: [44, a.INT],
      // Max image height
      imagePreviewMaxHeight: [256, a.INT],
      // Max size of preview file for when createImageBitmap is not supported
      imagePreviewMaxFileSize: [null, a.INT],
      // The amount of extra pixels added to the image preview to allow comfortable zooming
      imagePreviewZoomFactor: [2, a.INT],
      // Should we upscale small images to fit the max bounding box of the preview area
      imagePreviewUpscale: [!1, a.BOOLEAN],
      // Max size of preview file that we allow to try to instant preview if createImageBitmap is not supported, else image is queued for loading
      imagePreviewMaxInstantPreviewFileSize: [1e6, a.INT],
      // Style of the transparancy indicator used behind images
      imagePreviewTransparencyIndicator: [null, a.STRING],
      // Enables or disables reading average image color
      imagePreviewCalculateAverageImageColor: [!1, a.BOOLEAN],
      // Enables or disables the previewing of markup
      imagePreviewMarkupShow: [!0, a.BOOLEAN],
      // Allows filtering of markup to only show certain shapes
      imagePreviewMarkupFilter: [() => !0, a.FUNCTION]
    }
  };
}, isBrowser$1 = typeof window < "u" && typeof window.document < "u";
isBrowser$1 && document.dispatchEvent(
  new CustomEvent("FilePond:pluginloaded", { detail: plugin$1 })
);
/*!
 * FilePondPluginImageValidateSize 1.2.7
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */
const isImage = (e) => /^image/.test(e.type), getImageSize = (e) => new Promise((t, i) => {
  const a = document.createElement("img");
  a.src = URL.createObjectURL(e), a.onerror = (l) => {
    clearInterval(n), i(l);
  };
  const n = setInterval(() => {
    a.naturalWidth && a.naturalHeight && (clearInterval(n), URL.revokeObjectURL(a.src), t({
      width: a.naturalWidth,
      height: a.naturalHeight
    }));
  }, 1);
}), plugin = ({ addFilter: e, utils: t }) => {
  const { Type: i, replaceInString: a, isFile: n } = t, l = (r, s, o) => new Promise((d, c) => {
    const u = ({ width: f, height: p }) => {
      const {
        minWidth: m,
        minHeight: h,
        maxWidth: I,
        maxHeight: b,
        minResolution: g,
        maxResolution: E
      } = s, T = f * p;
      f < m || p < h ? c("TOO_SMALL") : f > I || p > b ? c("TOO_BIG") : g !== null && T < g ? c("TOO_LOW_RES") : E !== null && T > E && c("TOO_HIGH_RES"), d();
    };
    getImageSize(r).then(u).catch(() => {
      if (!o) {
        c();
        return;
      }
      o(r, s).then(u).catch(() => c());
    });
  });
  return e(
    "LOAD_FILE",
    (r, { query: s }) => new Promise((o, d) => {
      if (!n(r) || !isImage(r) || !s("GET_ALLOW_IMAGE_VALIDATE_SIZE")) {
        o(r);
        return;
      }
      const c = {
        minWidth: s("GET_IMAGE_VALIDATE_SIZE_MIN_WIDTH"),
        minHeight: s("GET_IMAGE_VALIDATE_SIZE_MIN_HEIGHT"),
        maxWidth: s("GET_IMAGE_VALIDATE_SIZE_MAX_WIDTH"),
        maxHeight: s("GET_IMAGE_VALIDATE_SIZE_MAX_HEIGHT"),
        minResolution: s("GET_IMAGE_VALIDATE_SIZE_MIN_RESOLUTION"),
        maxResolution: s("GET_IMAGE_VALIDATE_SIZE_MAX_RESOLUTION")
      }, u = s("GET_IMAGE_VALIDATE_SIZE_MEASURE");
      l(r, c, u).then(() => {
        o(r);
      }).catch((f) => {
        const p = f ? {
          TOO_SMALL: {
            label: s(
              "GET_IMAGE_VALIDATE_SIZE_LABEL_IMAGE_SIZE_TOO_SMALL"
            ),
            details: s(
              "GET_IMAGE_VALIDATE_SIZE_LABEL_EXPECTED_MIN_SIZE"
            )
          },
          TOO_BIG: {
            label: s(
              "GET_IMAGE_VALIDATE_SIZE_LABEL_IMAGE_SIZE_TOO_BIG"
            ),
            details: s(
              "GET_IMAGE_VALIDATE_SIZE_LABEL_EXPECTED_MAX_SIZE"
            )
          },
          TOO_LOW_RES: {
            label: s(
              "GET_IMAGE_VALIDATE_SIZE_LABEL_IMAGE_RESOLUTION_TOO_LOW"
            ),
            details: s(
              "GET_IMAGE_VALIDATE_SIZE_LABEL_EXPECTED_MIN_RESOLUTION"
            )
          },
          TOO_HIGH_RES: {
            label: s(
              "GET_IMAGE_VALIDATE_SIZE_LABEL_IMAGE_RESOLUTION_TOO_HIGH"
            ),
            details: s(
              "GET_IMAGE_VALIDATE_SIZE_LABEL_EXPECTED_MAX_RESOLUTION"
            )
          }
        }[f] : {
          label: s("GET_IMAGE_VALIDATE_SIZE_LABEL_FORMAT_ERROR"),
          details: r.type
        };
        d({
          status: {
            main: p.label,
            sub: f ? a(p.details, c) : p.details
          }
        });
      });
    })
  ), {
    // default options
    options: {
      // Enable or disable file type validation
      allowImageValidateSize: [!0, i.BOOLEAN],
      // Error thrown when image can not be loaded
      imageValidateSizeLabelFormatError: [
        "Image type not supported",
        i.STRING
      ],
      // Custom function to use as image measure
      imageValidateSizeMeasure: [null, i.FUNCTION],
      // Required amount of pixels in the image
      imageValidateSizeMinResolution: [null, i.INT],
      imageValidateSizeMaxResolution: [null, i.INT],
      imageValidateSizeLabelImageResolutionTooLow: [
        "Resolution is too low",
        i.STRING
      ],
      imageValidateSizeLabelImageResolutionTooHigh: [
        "Resolution is too high",
        i.STRING
      ],
      imageValidateSizeLabelExpectedMinResolution: [
        "Minimum resolution is {minResolution}",
        i.STRING
      ],
      imageValidateSizeLabelExpectedMaxResolution: [
        "Maximum resolution is {maxResolution}",
        i.STRING
      ],
      // Required dimensions
      imageValidateSizeMinWidth: [1, i.INT],
      // needs to be at least one pixel
      imageValidateSizeMinHeight: [1, i.INT],
      imageValidateSizeMaxWidth: [65535, i.INT],
      // maximum size of JPEG, fine for now I guess
      imageValidateSizeMaxHeight: [65535, i.INT],
      // Label to show when an image is too small or image is too big
      imageValidateSizeLabelImageSizeTooSmall: [
        "Image is too small",
        i.STRING
      ],
      imageValidateSizeLabelImageSizeTooBig: ["Image is too big", i.STRING],
      imageValidateSizeLabelExpectedMinSize: [
        "Minimum size is {minWidth} × {minHeight}",
        i.STRING
      ],
      imageValidateSizeLabelExpectedMaxSize: [
        "Maximum size is {maxWidth} × {maxHeight}",
        i.STRING
      ]
    }
  };
}, isBrowser = typeof window < "u" && typeof window.document < "u";
isBrowser && document.dispatchEvent(
  new CustomEvent("FilePond:pluginloaded", { detail: plugin })
);
const ar_AR = {
  labelIdle: 'اسحب و ادرج ملفاتك أو <span class="filepond--label-action"> تصفح </span>',
  labelInvalidField: "الحقل يحتوي على ملفات غير صالحة",
  labelFileWaitingForSize: "بانتظار الحجم",
  labelFileSizeNotAvailable: "الحجم غير متاح",
  labelFileLoading: "بالإنتظار",
  labelFileLoadError: "حدث خطأ أثناء التحميل",
  labelFileProcessing: "يتم الرفع",
  labelFileProcessingComplete: "تم الرفع",
  labelFileProcessingAborted: "تم إلغاء الرفع",
  labelFileProcessingError: "حدث خطأ أثناء الرفع",
  labelFileProcessingRevertError: "حدث خطأ أثناء التراجع",
  labelFileRemoveError: "حدث خطأ أثناء الحذف",
  labelTapToCancel: "انقر للإلغاء",
  labelTapToRetry: "انقر لإعادة المحاولة",
  labelTapToUndo: "انقر للتراجع",
  labelButtonRemoveItem: "مسح",
  labelButtonAbortItemLoad: "إلغاء",
  labelButtonRetryItemLoad: "إعادة",
  labelButtonAbortItemProcessing: "إلغاء",
  labelButtonUndoItemProcessing: "تراجع",
  labelButtonRetryItemProcessing: "إعادة",
  labelButtonProcessItem: "رفع",
  labelMaxFileSizeExceeded: "الملف كبير جدا",
  labelMaxFileSize: "حجم الملف الأقصى: {filesize}",
  labelMaxTotalFileSizeExceeded: "تم تجاوز الحد الأقصى للحجم الإجمالي",
  labelMaxTotalFileSize: "الحد الأقصى لحجم الملف: {filesize}",
  labelFileTypeNotAllowed: "ملف من نوع غير صالح",
  fileValidateTypeLabelExpectedTypes: "تتوقع {allButLastType} من {lastType}",
  imageValidateSizeLabelFormatError: "نوع الصورة غير مدعوم",
  imageValidateSizeLabelImageSizeTooSmall: "الصورة صغير جدا",
  imageValidateSizeLabelImageSizeTooBig: "الصورة كبيرة جدا",
  imageValidateSizeLabelExpectedMinSize: "الحد الأدنى للأبعاد هو: {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "الحد الأقصى للأبعاد هو: {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "الدقة ضعيفة جدا",
  imageValidateSizeLabelImageResolutionTooHigh: "الدقة مرتفعة جدا",
  imageValidateSizeLabelExpectedMinResolution: "أقل دقة: {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "أقصى دقة: {maxResolution}"
}, az_AZ = {
  labelIdle: 'Faylınızı Sürüşdürün & Buraxın ya da <span class="filepond--label-action"> Seçin </span>',
  labelInvalidField: "Sahədə etibarsız fayllar var",
  labelFileWaitingForSize: "Ölçü hesablanır",
  labelFileSizeNotAvailable: "Ölçü mövcud deyil",
  labelFileLoading: "Yüklənir",
  labelFileLoadError: "Yükləmə əsnasında xəta baş verdi",
  labelFileProcessing: "Yüklənir",
  labelFileProcessingComplete: "Yükləmə tamamlandı",
  labelFileProcessingAborted: "Yükləmə ləğv edildi",
  labelFileProcessingError: "Yükəyərkən xəta baş verdi",
  labelFileProcessingRevertError: "Geri çəkərkən xəta baş verdi",
  labelFileRemoveError: "Çıxararkən xəta baş verdi",
  labelTapToCancel: "İmtina etmək üçün klikləyin",
  labelTapToRetry: "Təkrar yoxlamaq üçün klikləyin",
  labelTapToUndo: "Geri almaq üçün klikləyin",
  labelButtonRemoveItem: "Çıxar",
  labelButtonAbortItemLoad: "İmtina Et",
  labelButtonRetryItemLoad: "Təkrar yoxla",
  labelButtonAbortItemProcessing: "İmtina et",
  labelButtonUndoItemProcessing: "Geri Al",
  labelButtonRetryItemProcessing: "Təkrar yoxla",
  labelButtonProcessItem: "Yüklə",
  labelMaxFileSizeExceeded: "Fayl çox böyükdür",
  labelMaxFileSize: "Ən böyük fayl ölçüsü: {filesize}",
  labelMaxTotalFileSizeExceeded: "Maksimum ölçü keçildi",
  labelMaxTotalFileSize: "Maksimum fayl ölçüsü :{filesize}",
  labelFileTypeNotAllowed: "Etibarsız fayl tipi",
  fileValidateTypeLabelExpectedTypes: "Bu {allButLastType} ya da bu fayl olması lazımdır: {lastType}",
  imageValidateSizeLabelFormatError: "Şəkil tipi dəstəklənmir",
  imageValidateSizeLabelImageSizeTooSmall: "Şəkil çox kiçik",
  imageValidateSizeLabelImageSizeTooBig: "Şəkil çox böyük",
  imageValidateSizeLabelExpectedMinSize: "Minimum ölçü {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maksimum ölçü {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Görüntü imkanı çox aşağı",
  imageValidateSizeLabelImageResolutionTooHigh: "Görüntü imkanı çox yüksək",
  imageValidateSizeLabelExpectedMinResolution: "Minimum görüntü imkanı {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maximum görüntü imkanı {maxResolution}"
}, cs_CZ = {
  labelIdle: 'Přetáhněte soubor sem (drag&drop) nebo <span class="filepond--label-action"> Vyhledat </span>',
  labelInvalidField: "Pole obsahuje chybné soubory",
  labelFileWaitingForSize: "Zjišťuje se velikost",
  labelFileSizeNotAvailable: "Velikost není známá",
  labelFileLoading: "Přenáší se",
  labelFileLoadError: "Chyba při přenosu",
  labelFileProcessing: "Probíhá upload",
  labelFileProcessingComplete: "Upload dokončen",
  labelFileProcessingAborted: "Upload stornován",
  labelFileProcessingError: "Chyba při uploadu",
  labelFileProcessingRevertError: "Chyba při obnově",
  labelFileRemoveError: "Chyba při odstranění",
  labelTapToCancel: "klepněte pro storno",
  labelTapToRetry: "klepněte pro opakování",
  labelTapToUndo: "klepněte pro vrácení",
  labelButtonRemoveItem: "Odstranit",
  labelButtonAbortItemLoad: "Storno",
  labelButtonRetryItemLoad: "Opakovat",
  labelButtonAbortItemProcessing: "Zpět",
  labelButtonUndoItemProcessing: "Vrátit",
  labelButtonRetryItemProcessing: "Opakovat",
  labelButtonProcessItem: "Upload",
  labelMaxFileSizeExceeded: "Soubor je příliš velký",
  labelMaxFileSize: "Největší velikost souboru je {filesize}",
  labelMaxTotalFileSizeExceeded: "Překročena maximální celková velikost souboru",
  labelMaxTotalFileSize: "Maximální celková velikost souboru je {filesize}",
  labelFileTypeNotAllowed: "Soubor je nesprávného typu",
  fileValidateTypeLabelExpectedTypes: "Očekává se {allButLastType} nebo {lastType}",
  imageValidateSizeLabelFormatError: "Obrázek tohoto typu není podporován",
  imageValidateSizeLabelImageSizeTooSmall: "Obrázek je příliš malý",
  imageValidateSizeLabelImageSizeTooBig: "Obrázek je příliš velký",
  imageValidateSizeLabelExpectedMinSize: "Minimální rozměr je {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maximální rozměr je {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Rozlišení je příliš malé",
  imageValidateSizeLabelImageResolutionTooHigh: "Rozlišení je příliš velké",
  imageValidateSizeLabelExpectedMinResolution: "Minimální rozlišení je {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maximální rozlišení je {maxResolution}"
}, da_DK = {
  labelIdle: 'Træk & slip filer eller <span class = "filepond - label-action"> Gennemse </span>',
  labelInvalidField: "Felt indeholder ugyldige filer",
  labelFileWaitingForSize: "Venter på størrelse",
  labelFileSizeNotAvailable: "Størrelse ikke tilgængelig",
  labelFileLoading: "Loader",
  labelFileLoadError: "Load fejlede",
  labelFileProcessing: "Uploader",
  labelFileProcessingComplete: "Upload færdig",
  labelFileProcessingAborted: "Upload annulleret",
  labelFileProcessingError: "Upload fejlede",
  labelFileProcessingRevertError: "Fortryd fejlede",
  labelFileRemoveError: "Fjern fejlede",
  labelTapToCancel: "tryk for at annullere",
  labelTapToRetry: "tryk for at prøve igen",
  labelTapToUndo: "tryk for at fortryde",
  labelButtonRemoveItem: "Fjern",
  labelButtonAbortItemLoad: "Annuller",
  labelButtonRetryItemLoad: "Forsøg igen",
  labelButtonAbortItemProcessing: "Annuller",
  labelButtonUndoItemProcessing: "Fortryd",
  labelButtonRetryItemProcessing: "Prøv igen",
  labelButtonProcessItem: "Upload",
  labelMaxFileSizeExceeded: "Filen er for stor",
  labelMaxFileSize: "Maksimal filstørrelse er {filesize}",
  labelMaxTotalFileSizeExceeded: "Maksimal totalstørrelse overskredet",
  labelMaxTotalFileSize: "Maksimal total filstørrelse er {filesize}",
  labelFileTypeNotAllowed: "Ugyldig filtype",
  fileValidateTypeLabelExpectedTypes: "Forventer {allButLastType} eller {lastType}",
  imageValidateSizeLabelFormatError: "Ugyldigt format",
  imageValidateSizeLabelImageSizeTooSmall: "Billedet er for lille",
  imageValidateSizeLabelImageSizeTooBig: "Billedet er for stort",
  imageValidateSizeLabelExpectedMinSize: "Minimum størrelse er {minBredde} × {minHøjde}",
  imageValidateSizeLabelExpectedMaxSize: "Maksimal størrelse er {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "For lav opløsning",
  imageValidateSizeLabelImageResolutionTooHigh: "For høj opløsning",
  imageValidateSizeLabelExpectedMinResolution: "Minimum opløsning er {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maksimal opløsning er {maxResolution}"
}, de_DE = {
  labelIdle: 'Dateien ablegen oder <span class="filepond--label-action"> auswählen </span>',
  labelInvalidField: "Feld beinhaltet ungültige Dateien",
  labelFileWaitingForSize: "Dateigröße berechnen",
  labelFileSizeNotAvailable: "Dateigröße nicht verfügbar",
  labelFileLoading: "Laden",
  labelFileLoadError: "Fehler beim Laden",
  labelFileProcessing: "Upload läuft",
  labelFileProcessingComplete: "Upload abgeschlossen",
  labelFileProcessingAborted: "Upload abgebrochen",
  labelFileProcessingError: "Fehler beim Upload",
  labelFileProcessingRevertError: "Fehler beim Wiederherstellen",
  labelFileRemoveError: "Fehler beim Löschen",
  labelTapToCancel: "abbrechen",
  labelTapToRetry: "erneut versuchen",
  labelTapToUndo: "rückgängig",
  labelButtonRemoveItem: "Entfernen",
  labelButtonAbortItemLoad: "Verwerfen",
  labelButtonRetryItemLoad: "Erneut versuchen",
  labelButtonAbortItemProcessing: "Abbrechen",
  labelButtonUndoItemProcessing: "Rückgängig",
  labelButtonRetryItemProcessing: "Erneut versuchen",
  labelButtonProcessItem: "Upload",
  labelMaxFileSizeExceeded: "Datei ist zu groß",
  labelMaxFileSize: "Maximale Dateigröße: {filesize}",
  labelMaxTotalFileSizeExceeded: "Maximale gesamte Dateigröße überschritten",
  labelMaxTotalFileSize: "Maximale gesamte Dateigröße: {filesize}",
  labelFileTypeNotAllowed: "Dateityp ungültig",
  fileValidateTypeLabelExpectedTypes: "Erwartet {allButLastType} oder {lastType}",
  imageValidateSizeLabelFormatError: "Bildtyp nicht unterstützt",
  imageValidateSizeLabelImageSizeTooSmall: "Bild ist zu klein",
  imageValidateSizeLabelImageSizeTooBig: "Bild ist zu groß",
  imageValidateSizeLabelExpectedMinSize: "Mindestgröße: {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maximale Größe: {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Auflösung ist zu niedrig",
  imageValidateSizeLabelImageResolutionTooHigh: "Auflösung ist zu hoch",
  imageValidateSizeLabelExpectedMinResolution: "Mindestauflösung: {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maximale Auflösung: {maxResolution}"
}, el_EL = {
  labelIdle: 'Σύρετε τα αρχεία σας στο πλαίσιο ή <span class="filepond--label-action"> Επιλέξτε </span>',
  labelInvalidField: "Το πεδίο περιέχει μη έγκυρα αρχεία",
  labelFileWaitingForSize: "Σε αναμονή για το μέγεθος",
  labelFileSizeNotAvailable: "Μέγεθος μη διαθέσιμο",
  labelFileLoading: "Φόρτωση σε εξέλιξη",
  labelFileLoadError: "Σφάλμα κατά τη φόρτωση",
  labelFileProcessing: "Επεξεργασία",
  labelFileProcessingComplete: "Η επεξεργασία ολοκληρώθηκε",
  labelFileProcessingAborted: "Η επεξεργασία ακυρώθηκε",
  labelFileProcessingError: "Σφάλμα κατά την επεξεργασία",
  labelFileProcessingRevertError: "Σφάλμα κατά την επαναφορά",
  labelFileRemoveError: "Σφάλμα κατά την διαγραφή",
  labelTapToCancel: "πατήστε για ακύρωση",
  labelTapToRetry: "πατήστε για επανάληψη",
  labelTapToUndo: "πατήστε για αναίρεση",
  labelButtonRemoveItem: "Αφαίρεση",
  labelButtonAbortItemLoad: "Ακύρωση",
  labelButtonRetryItemLoad: "Επανάληψη",
  labelButtonAbortItemProcessing: "Ακύρωση",
  labelButtonUndoItemProcessing: "Αναίρεση",
  labelButtonRetryItemProcessing: "Επανάληψη",
  labelButtonProcessItem: "Μεταφόρτωση",
  labelMaxFileSizeExceeded: "Το αρχείο είναι πολύ μεγάλο",
  labelMaxFileSize: "Το μέγιστο μέγεθος αρχείου είναι {filesize}",
  labelMaxTotalFileSizeExceeded: "Υπέρβαση του μέγιστου συνολικού μεγέθους",
  labelMaxTotalFileSize: "Το μέγιστο συνολικό μέγεθος αρχείων είναι {filesize}",
  labelFileTypeNotAllowed: "Μη έγκυρος τύπος αρχείου",
  fileValidateTypeLabelExpectedTypes: "Τα αποδεκτά αρχεία είναι {allButLastType} ή {lastType}",
  imageValidateSizeLabelFormatError: "Ο τύπος της εικόνας δεν υποστηρίζεται",
  imageValidateSizeLabelImageSizeTooSmall: "Η εικόνα είναι πολύ μικρή",
  imageValidateSizeLabelImageSizeTooBig: "Η εικόνα είναι πολύ μεγάλη",
  imageValidateSizeLabelExpectedMinSize: "Το ελάχιστο αποδεκτό μέγεθος είναι {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Το μέγιστο αποδεκτό μέγεθος είναι {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Η ανάλυση της εικόνας είναι πολύ χαμηλή",
  imageValidateSizeLabelImageResolutionTooHigh: "Η ανάλυση της εικόνας είναι πολύ υψηλή",
  imageValidateSizeLabelExpectedMinResolution: "Η ελάχιστη αποδεκτή ανάλυση είναι {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Η μέγιστη αποδεκτή ανάλυση είναι {maxResolution}"
}, en_EN = {
  labelIdle: 'Drag & Drop your files or <span class="filepond--label-action"> Browse </span>',
  labelInvalidField: "Field contains invalid files",
  labelFileWaitingForSize: "Waiting for size",
  labelFileSizeNotAvailable: "Size not available",
  labelFileLoading: "Loading",
  labelFileLoadError: "Error during load",
  labelFileProcessing: "Uploading",
  labelFileProcessingComplete: "Upload complete",
  labelFileProcessingAborted: "Upload cancelled",
  labelFileProcessingError: "Error during upload",
  labelFileProcessingRevertError: "Error during revert",
  labelFileRemoveError: "Error during remove",
  labelTapToCancel: "tap to cancel",
  labelTapToRetry: "tap to retry",
  labelTapToUndo: "tap to undo",
  labelButtonRemoveItem: "Remove",
  labelButtonAbortItemLoad: "Abort",
  labelButtonRetryItemLoad: "Retry",
  labelButtonAbortItemProcessing: "Cancel",
  labelButtonUndoItemProcessing: "Undo",
  labelButtonRetryItemProcessing: "Retry",
  labelButtonProcessItem: "Upload",
  labelMaxFileSizeExceeded: "File is too large",
  labelMaxFileSize: "Maximum file size is {filesize}",
  labelMaxTotalFileSizeExceeded: "Maximum total size exceeded",
  labelMaxTotalFileSize: "Maximum total file size is {filesize}",
  labelFileTypeNotAllowed: "File of invalid type",
  fileValidateTypeLabelExpectedTypes: "Expects {allButLastType} or {lastType}",
  imageValidateSizeLabelFormatError: "Image type not supported",
  imageValidateSizeLabelImageSizeTooSmall: "Image is too small",
  imageValidateSizeLabelImageSizeTooBig: "Image is too big",
  imageValidateSizeLabelExpectedMinSize: "Minimum size is {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maximum size is {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Resolution is too low",
  imageValidateSizeLabelImageResolutionTooHigh: "Resolution is too high",
  imageValidateSizeLabelExpectedMinResolution: "Minimum resolution is {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maximum resolution is {maxResolution}"
}, es_ES = {
  labelIdle: 'Arrastra y suelta tus archivos o <span class = "filepond--label-action"> Examina <span>',
  labelInvalidField: "El campo contiene archivos inválidos",
  labelFileWaitingForSize: "Esperando tamaño",
  labelFileSizeNotAvailable: "Tamaño no disponible",
  labelFileLoading: "Cargando",
  labelFileLoadError: "Error durante la carga",
  labelFileProcessing: "Subiendo",
  labelFileProcessingComplete: "Subida completa",
  labelFileProcessingAborted: "Subida cancelada",
  labelFileProcessingError: "Error durante la subida",
  labelFileProcessingRevertError: "Error durante la reversión",
  labelFileRemoveError: "Error durante la eliminación",
  labelTapToCancel: "toca para cancelar",
  labelTapToRetry: "tocar para reintentar",
  labelTapToUndo: "tocar para deshacer",
  labelButtonRemoveItem: "Eliminar",
  labelButtonAbortItemLoad: "Cancelar",
  labelButtonRetryItemLoad: "Reintentar",
  labelButtonAbortItemProcessing: "Cancelar",
  labelButtonUndoItemProcessing: "Deshacer",
  labelButtonRetryItemProcessing: "Reintentar",
  labelButtonProcessItem: "Subir",
  labelMaxFileSizeExceeded: "El archivo es demasiado grande",
  labelMaxFileSize: "El tamaño máximo del archivo es {filesize}",
  labelMaxTotalFileSizeExceeded: "Tamaño total máximo excedido",
  labelMaxTotalFileSize: "El tamaño total máximo del archivo es {filesize}",
  labelFileTypeNotAllowed: "Archivo de tipo inválido",
  fileValidateTypeLabelExpectedTypes: "Espera {allButLastType} o {lastType}",
  imageValidateSizeLabelFormatError: "Tipo de imagen no soportada",
  imageValidateSizeLabelImageSizeTooSmall: "La imagen es demasiado pequeña",
  imageValidateSizeLabelImageSizeTooBig: "La imagen es demasiado grande",
  imageValidateSizeLabelExpectedMinSize: "El tamaño mínimo es {minWidth} x {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "El tamaño máximo es {maxWidth} x {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "La resolución es demasiado baja",
  imageValidateSizeLabelImageResolutionTooHigh: "La resolución es demasiado alta",
  imageValidateSizeLabelExpectedMinResolution: "La resolución mínima es {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "La resolución máxima es {maxResolution}"
}, fa_IR = {
  labelIdle: 'فایل را اینجا بکشید و رها کنید، یا <span class="filepond--label-action"> جستجو کنید </span>',
  labelInvalidField: "فیلد دارای فایل های نامعتبر است",
  labelFileWaitingForSize: "Waiting for size",
  labelFileSizeNotAvailable: "حجم فایل مجاز نیست",
  labelFileLoading: "درحال بارگذاری",
  labelFileLoadError: "خطا در زمان اجرا",
  labelFileProcessing: "درحال بارگذاری",
  labelFileProcessingComplete: "بارگذاری کامل شد",
  labelFileProcessingAborted: "بارگذاری لغو شد",
  labelFileProcessingError: "خطا در زمان بارگذاری",
  labelFileProcessingRevertError: "خطا در زمان حذف",
  labelFileRemoveError: "خطا در زمان حذف",
  labelTapToCancel: "برای لغو ضربه بزنید",
  labelTapToRetry: "برای تکرار کلیک کنید",
  labelTapToUndo: "برای برگشت کلیک کنید",
  labelButtonRemoveItem: "حذف",
  labelButtonAbortItemLoad: "لغو",
  labelButtonRetryItemLoad: "تکرار",
  labelButtonAbortItemProcessing: "لغو",
  labelButtonUndoItemProcessing: "برگشت",
  labelButtonRetryItemProcessing: "تکرار",
  labelButtonProcessItem: "بارگذاری",
  labelMaxFileSizeExceeded: "فایل بسیار حجیم است",
  labelMaxFileSize: "حداکثر مجاز فایل {filesize} است",
  labelMaxTotalFileSizeExceeded: "از حداکثر حجم فایل بیشتر شد",
  labelMaxTotalFileSize: "حداکثر حجم فایل {filesize} است",
  labelFileTypeNotAllowed: "نوع فایل نامعتبر است",
  fileValidateTypeLabelExpectedTypes: "در انتظار {allButLastType} یا {lastType}",
  imageValidateSizeLabelFormatError: "فرمت تصویر پشتیبانی نمی شود",
  imageValidateSizeLabelImageSizeTooSmall: "تصویر بسیار کوچک است",
  imageValidateSizeLabelImageSizeTooBig: "تصویر بسیار بزرگ است",
  imageValidateSizeLabelExpectedMinSize: "حداقل اندازه {minWidth} × {minHeight} است",
  imageValidateSizeLabelExpectedMaxSize: "حداکثر اندازه {maxWidth} × {maxHeight} است",
  imageValidateSizeLabelImageResolutionTooLow: "وضوح تصویر بسیار کم است",
  imageValidateSizeLabelImageResolutionTooHigh: "وضوع تصویر بسیار زیاد است",
  imageValidateSizeLabelExpectedMinResolution: "حداقل وضوح تصویر {minResolution} است",
  imageValidateSizeLabelExpectedMaxResolution: "حداکثر وضوح تصویر {maxResolution} است"
}, fi_FI = {
  labelIdle: 'Vedä ja pudota tiedostoja tai <span class="filepond--label-action"> Selaa </span>',
  labelInvalidField: "Kentässä on virheellisiä tiedostoja",
  labelFileWaitingForSize: "Odotetaan kokoa",
  labelFileSizeNotAvailable: "Kokoa ei saatavilla",
  labelFileLoading: "Ladataan",
  labelFileLoadError: "Virhe latauksessa",
  labelFileProcessing: "Lähetetään",
  labelFileProcessingComplete: "Lähetys valmis",
  labelFileProcessingAborted: "Lähetys peruttu",
  labelFileProcessingError: "Virhe lähetyksessä",
  labelFileProcessingRevertError: "Virhe palautuksessa",
  labelFileRemoveError: "Virhe poistamisessa",
  labelTapToCancel: "peruuta napauttamalla",
  labelTapToRetry: "yritä uudelleen napauttamalla",
  labelTapToUndo: "kumoa napauttamalla",
  labelButtonRemoveItem: "Poista",
  labelButtonAbortItemLoad: "Keskeytä",
  labelButtonRetryItemLoad: "Yritä uudelleen",
  labelButtonAbortItemProcessing: "Peruuta",
  labelButtonUndoItemProcessing: "Kumoa",
  labelButtonRetryItemProcessing: "Yritä uudelleen",
  labelButtonProcessItem: "Lähetä",
  labelMaxFileSizeExceeded: "Tiedoston koko on liian suuri",
  labelMaxFileSize: "Tiedoston maksimikoko on {filesize}",
  labelMaxTotalFileSizeExceeded: "Tiedostojen yhdistetty maksimikoko ylitetty",
  labelMaxTotalFileSize: "Tiedostojen yhdistetty maksimikoko on {filesize}",
  labelFileTypeNotAllowed: "Tiedostotyyppiä ei sallita",
  fileValidateTypeLabelExpectedTypes: "Sallitaan {allButLastType} tai {lastType}",
  imageValidateSizeLabelFormatError: "Kuvatyyppiä ei tueta",
  imageValidateSizeLabelImageSizeTooSmall: "Kuva on liian pieni",
  imageValidateSizeLabelImageSizeTooBig: "Kuva on liian suuri",
  imageValidateSizeLabelExpectedMinSize: "Minimikoko on {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maksimikoko on {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Resoluutio on liian pieni",
  imageValidateSizeLabelImageResolutionTooHigh: "Resoluutio on liian suuri",
  imageValidateSizeLabelExpectedMinResolution: "Minimiresoluutio on {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maksimiresoluutio on {maxResolution}"
}, fr_FR = {
  labelIdle: 'Faites glisser vos fichiers ou <span class = "filepond--label-action"> Parcourir </span>',
  labelInvalidField: "Le champ contient des fichiers invalides",
  labelFileWaitingForSize: "En attente de taille",
  labelFileSizeNotAvailable: "Taille non disponible",
  labelFileLoading: "Chargement",
  labelFileLoadError: "Erreur durant le chargement",
  labelFileProcessing: "Traitement",
  labelFileProcessingComplete: "Traitement effectué",
  labelFileProcessingAborted: "Traitement interrompu",
  labelFileProcessingError: "Erreur durant le traitement",
  labelFileProcessingRevertError: "Erreur durant la restauration",
  labelFileRemoveError: "Erreur durant la suppression",
  labelTapToCancel: "appuyer pour annuler",
  labelTapToRetry: "appuyer pour réessayer",
  labelTapToUndo: "appuyer pour revenir en arrière",
  labelButtonRemoveItem: "Retirer",
  labelButtonAbortItemLoad: "Annuler",
  labelButtonRetryItemLoad: "Recommencer",
  labelButtonAbortItemProcessing: "Annuler",
  labelButtonUndoItemProcessing: "Revenir en arrière",
  labelButtonRetryItemProcessing: "Recommencer",
  labelButtonProcessItem: "Transférer",
  labelMaxFileSizeExceeded: "Le fichier est trop volumineux",
  labelMaxFileSize: "La taille maximale de fichier est {filesize}",
  labelMaxTotalFileSizeExceeded: "Taille totale maximale dépassée",
  labelMaxTotalFileSize: "La taille totale maximale des fichiers est {filesize}",
  labelFileTypeNotAllowed: "Fichier non valide",
  fileValidateTypeLabelExpectedTypes: "Attendu {allButLastType} ou {lastType}",
  imageValidateSizeLabelFormatError: "Type d'image non pris en charge",
  imageValidateSizeLabelImageSizeTooSmall: "L'image est trop petite",
  imageValidateSizeLabelImageSizeTooBig: "L'image est trop grande",
  imageValidateSizeLabelExpectedMinSize: "La taille minimale est {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "La taille maximale est {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "La résolution est trop faible",
  imageValidateSizeLabelImageResolutionTooHigh: "La résolution est trop élevée",
  imageValidateSizeLabelExpectedMinResolution: "La résolution minimale est {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "La résolution maximale est {maxResolution}"
}, he_HE = {
  labelIdle: 'גרור ושחרר את הקבצים כאן או <span class="filepond--label-action"> לחץ כאן לבחירה </span>',
  labelInvalidField: "קובץ לא חוקי",
  labelFileWaitingForSize: "מחשב את גודל הקבצים",
  labelFileSizeNotAvailable: "לא ניתן לקבוע את גודל הקבצים",
  labelFileLoading: "טוען...",
  labelFileLoadError: "שגיאה ארעה בעת טעינת הקבצים",
  labelFileProcessing: "מעלה את הקבצים",
  labelFileProcessingComplete: "העלאת הקבצים הסתיימה",
  labelFileProcessingAborted: "העלאת הקבצים בוטלה",
  labelFileProcessingError: "שגיאה ארעה בעת העלאת הקבצים",
  labelFileProcessingRevertError: "שגיאה ארעה בעת שחזור הקבצים",
  labelFileRemoveError: "שגיאה ארעה בעת הסרת הקובץ",
  labelTapToCancel: "הקלק לביטול",
  labelTapToRetry: "הקלק לנסות שנית",
  labelTapToUndo: "הקלק לשחזר",
  labelButtonRemoveItem: "הסר",
  labelButtonAbortItemLoad: "בטל",
  labelButtonRetryItemLoad: "טען שנית",
  labelButtonAbortItemProcessing: "בטל",
  labelButtonUndoItemProcessing: "שחזר",
  labelButtonRetryItemProcessing: "נסה שנית",
  labelButtonProcessItem: "העלה קובץ",
  labelMaxFileSizeExceeded: "הקובץ גדול מדי",
  labelMaxFileSize: "גודל המירבי המותר הוא: {filesize}",
  labelMaxTotalFileSizeExceeded: "גודל הקבצים חורג מהכמות המותרת",
  labelMaxTotalFileSize: "הגודל המירבי של סך הקבצים: {filesize}",
  labelFileTypeNotAllowed: "קובץ מסוג זה אינו מותר",
  fileValidateTypeLabelExpectedTypes: "הקבצים המותרים הם {allButLastType} או {lastType}",
  imageValidateSizeLabelFormatError: "תמונה בפורמט זה אינה נתמכת",
  imageValidateSizeLabelImageSizeTooSmall: "תמונה זו קטנה מדי",
  imageValidateSizeLabelImageSizeTooBig: "תמונה זו גדולה מדי",
  imageValidateSizeLabelExpectedMinSize: "הגודל צריך להיות לפחות: {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "הגודל המרבי המותר: {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "הרזולוציה של תמונה זו נמוכה מדי",
  imageValidateSizeLabelImageResolutionTooHigh: "הרזולוציה של תמונה זו גבוהה מדי",
  imageValidateSizeLabelExpectedMinResolution: "הרזולוציה צריכה להיות לפחות: {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "הרזולוציה המירבית המותרת היא: {maxResolution}"
}, hr_HR = {
  labelIdle: 'Ovdje "ispusti" datoteku ili <span class="filepond--label-action"> Pretraži </span>',
  labelInvalidField: "Polje sadrži neispravne datoteke",
  labelFileWaitingForSize: "Čekanje na veličinu datoteke",
  labelFileSizeNotAvailable: "Veličina datoteke nije dostupna",
  labelFileLoading: "Učitavanje",
  labelFileLoadError: "Greška tijekom učitavanja",
  labelFileProcessing: "Prijenos",
  labelFileProcessingComplete: "Prijenos završen",
  labelFileProcessingAborted: "Prijenos otkazan",
  labelFileProcessingError: "Greška tijekom prijenosa",
  labelFileProcessingRevertError: "Greška tijekom vraćanja",
  labelFileRemoveError: "Greška tijekom uklananja datoteke",
  labelTapToCancel: "Dodirni za prekid",
  labelTapToRetry: "Dodirni za ponovno",
  labelTapToUndo: "Dodirni za vraćanje",
  labelButtonRemoveItem: "Ukloni",
  labelButtonAbortItemLoad: "Odbaci",
  labelButtonRetryItemLoad: "Ponovi",
  labelButtonAbortItemProcessing: "Prekini",
  labelButtonUndoItemProcessing: "Vrati",
  labelButtonRetryItemProcessing: "Ponovi",
  labelButtonProcessItem: "Prijenos",
  labelMaxFileSizeExceeded: "Datoteka je prevelika",
  labelMaxFileSize: "Maksimalna veličina datoteke je {filesize}",
  labelMaxTotalFileSizeExceeded: "Maksimalna ukupna veličina datoteke prekoračena",
  labelMaxTotalFileSize: "Maksimalna ukupna veličina datoteke je {filesize}",
  labelFileTypeNotAllowed: "Tip datoteke nije podržan",
  fileValidateTypeLabelExpectedTypes: "Očekivan {allButLastType} ili {lastType}",
  imageValidateSizeLabelFormatError: "Tip slike nije podržan",
  imageValidateSizeLabelImageSizeTooSmall: "Slika je premala",
  imageValidateSizeLabelImageSizeTooBig: "Slika je prevelika",
  imageValidateSizeLabelExpectedMinSize: "Minimalna veličina je {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maksimalna veličina je {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Rezolucija je preniska",
  imageValidateSizeLabelImageResolutionTooHigh: "Rezolucija je previsoka",
  imageValidateSizeLabelExpectedMinResolution: "Minimalna rezolucija je {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maksimalna rezolucija je {maxResolution}"
}, hu_HU = {
  labelIdle: 'Mozgasd ide a fájlt a feltöltéshez, vagy <span class="filepond--label-action"> tallózás </span>',
  labelInvalidField: "A mező érvénytelen fájlokat tartalmaz",
  labelFileWaitingForSize: "Fáljméret kiszámolása",
  labelFileSizeNotAvailable: "A fájlméret nem elérhető",
  labelFileLoading: "Töltés",
  labelFileLoadError: "Hiba a betöltés során",
  labelFileProcessing: "Feltöltés",
  labelFileProcessingComplete: "Sikeres feltöltés",
  labelFileProcessingAborted: "A feltöltés megszakítva",
  labelFileProcessingError: "Hiba történt a feltöltés során",
  labelFileProcessingRevertError: "Hiba a visszaállítás során",
  labelFileRemoveError: "Hiba történt az eltávolítás során",
  labelTapToCancel: "koppints a törléshez",
  labelTapToRetry: "koppints az újrakezdéshez",
  labelTapToUndo: "koppints a visszavonáshoz",
  labelButtonRemoveItem: "Eltávolítás",
  labelButtonAbortItemLoad: "Megszakítás",
  labelButtonRetryItemLoad: "Újrapróbálkozás",
  labelButtonAbortItemProcessing: "Megszakítás",
  labelButtonUndoItemProcessing: "Visszavonás",
  labelButtonRetryItemProcessing: "Újrapróbálkozás",
  labelButtonProcessItem: "Feltöltés",
  labelMaxFileSizeExceeded: "A fájl túllépte a maximális méretet",
  labelMaxFileSize: "Maximális fájlméret: {filesize}",
  labelMaxTotalFileSizeExceeded: "Túllépte a maximális teljes méretet",
  labelMaxTotalFileSize: "A maximáis teljes fájlméret: {filesize}",
  labelFileTypeNotAllowed: "Érvénytelen típusú fájl",
  fileValidateTypeLabelExpectedTypes: "Engedélyezett típusok {allButLastType} vagy {lastType}",
  imageValidateSizeLabelFormatError: "A képtípus nem támogatott",
  imageValidateSizeLabelImageSizeTooSmall: "A kép túl kicsi",
  imageValidateSizeLabelImageSizeTooBig: "A kép túl nagy",
  imageValidateSizeLabelExpectedMinSize: "Minimum méret: {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maximum méret: {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "A felbontás túl alacsony",
  imageValidateSizeLabelImageResolutionTooHigh: "A felbontás túl magas",
  imageValidateSizeLabelExpectedMinResolution: "Minimáis felbontás: {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maximális felbontás: {maxResolution}"
}, id_ID = {
  labelIdle: 'Seret & Jatuhkan berkas Anda atau <span class="filepond--label-action">Jelajahi</span>',
  labelInvalidField: "Isian berisi berkas yang tidak valid",
  labelFileWaitingForSize: "Menunggu ukuran berkas",
  labelFileSizeNotAvailable: "Ukuran berkas tidak tersedia",
  labelFileLoading: "Memuat",
  labelFileLoadError: "Kesalahan saat memuat",
  labelFileProcessing: "Mengunggah",
  labelFileProcessingComplete: "Pengunggahan selesai",
  labelFileProcessingAborted: "Pengunggahan dibatalkan",
  labelFileProcessingError: "Kesalahan saat pengunggahan",
  labelFileProcessingRevertError: "Kesalahan saat pemulihan",
  labelFileRemoveError: "Kesalahan saat penghapusan",
  labelTapToCancel: "ketuk untuk membatalkan",
  labelTapToRetry: "ketuk untuk mencoba lagi",
  labelTapToUndo: "ketuk untuk mengurungkan",
  labelButtonRemoveItem: "Hapus",
  labelButtonAbortItemLoad: "Batalkan",
  labelButtonRetryItemLoad: "Coba Kembali",
  labelButtonAbortItemProcessing: "Batalkan",
  labelButtonUndoItemProcessing: "Urungkan",
  labelButtonRetryItemProcessing: "Coba Kembali",
  labelButtonProcessItem: "Unggah",
  labelMaxFileSizeExceeded: "Berkas terlalu besar",
  labelMaxFileSize: "Ukuran berkas maksimum adalah {filesize}",
  labelMaxTotalFileSizeExceeded: "Jumlah berkas maksimum terlampaui",
  labelMaxTotalFileSize: "Jumlah berkas maksimum adalah {filesize}",
  labelFileTypeNotAllowed: "Jenis berkas tidak valid",
  fileValidateTypeLabelExpectedTypes: "Mengharapkan {allButLastType} atau {lastType}",
  imageValidateSizeLabelFormatError: "Jenis citra tidak didukung",
  imageValidateSizeLabelImageSizeTooSmall: "Citra terlalu kecil",
  imageValidateSizeLabelImageSizeTooBig: "Citra terlalu besar",
  imageValidateSizeLabelExpectedMinSize: "Ukuran minimum adalah {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Ukuran maksimum adalah {minWidth} × {minHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Resolusi terlalu rendah",
  imageValidateSizeLabelImageResolutionTooHigh: "Resolusi terlalu tinggi",
  imageValidateSizeLabelExpectedMinResolution: "Resolusi minimum adalah {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Resolusi maksimum adalah {maxResolution}"
}, it_IT = {
  labelIdle: 'Trascina e rilascia i tuoi file oppure <span class = "filepond--label-action"> Carica <span>',
  labelInvalidField: "Il campo contiene dei file non validi",
  labelFileWaitingForSize: "Aspettando le dimensioni",
  labelFileSizeNotAvailable: "Dimensioni non disponibili",
  labelFileLoading: "Caricamento",
  labelFileLoadError: "Errore durante il caricamento",
  labelFileProcessing: "Caricamento",
  labelFileProcessingComplete: "Caricamento completato",
  labelFileProcessingAborted: "Caricamento cancellato",
  labelFileProcessingError: "Errore durante il caricamento",
  labelFileProcessingRevertError: "Errore durante il ripristino",
  labelFileRemoveError: "Errore durante l'eliminazione",
  labelTapToCancel: "tocca per cancellare",
  labelTapToRetry: "tocca per riprovare",
  labelTapToUndo: "tocca per ripristinare",
  labelButtonRemoveItem: "Elimina",
  labelButtonAbortItemLoad: "Cancella",
  labelButtonRetryItemLoad: "Ritenta",
  labelButtonAbortItemProcessing: "Camcella",
  labelButtonUndoItemProcessing: "Indietro",
  labelButtonRetryItemProcessing: "Ritenta",
  labelButtonProcessItem: "Carica",
  labelMaxFileSizeExceeded: "Il peso del file è eccessivo",
  labelMaxFileSize: "Il peso massimo del file è {filesize}",
  labelMaxTotalFileSizeExceeded: "Dimensione totale massima superata",
  labelMaxTotalFileSize: "La dimensione massima totale del file è {filesize}",
  labelFileTypeNotAllowed: "File non supportato",
  fileValidateTypeLabelExpectedTypes: "Aspetta {allButLastType} o {lastType}",
  imageValidateSizeLabelFormatError: "Tipo di immagine non compatibile",
  imageValidateSizeLabelImageSizeTooSmall: "L'immagine è troppo piccola",
  imageValidateSizeLabelImageSizeTooBig: "L'immagine è troppo grande",
  imageValidateSizeLabelExpectedMinSize: "La dimensione minima è {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "La dimensione massima è {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "La risoluzione è troppo bassa",
  imageValidateSizeLabelImageResolutionTooHigh: "La risoluzione è troppo alta",
  imageValidateSizeLabelExpectedMinResolution: "La risoluzione minima è {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "La risoluzione massima è {maxResolution}"
}, ja_JA = {
  labelIdle: 'ファイルをドラッグ&ドロップ又は<span class="filepond--label-action">ファイル選択</span>',
  labelInvalidField: "アップロードできないファイルが含まれています",
  labelFileWaitingForSize: "ファイルサイズを待っています",
  labelFileSizeNotAvailable: "ファイルサイズがみつかりません",
  labelFileLoading: "読込中...",
  labelFileLoadError: "読込中にエラーが発生",
  labelFileProcessing: "読込中...",
  labelFileProcessingComplete: "アップロード完了",
  labelFileProcessingAborted: "アップロードがキャンセルされました",
  labelFileProcessingError: "アップロード中にエラーが発生",
  labelFileProcessingRevertError: "ロールバック中にエラーが発生",
  labelFileRemoveError: "削除中にエラーが発生",
  labelTapToCancel: "クリックしてキャンセル",
  labelTapToRetry: "クリックしてもう一度お試し下さい",
  labelTapToUndo: "元に戻すにはタップします",
  labelButtonRemoveItem: "削除",
  labelButtonAbortItemLoad: "中断",
  labelButtonRetryItemLoad: "もう一度実行",
  labelButtonAbortItemProcessing: "キャンセル",
  labelButtonUndoItemProcessing: "元に戻す",
  labelButtonRetryItemProcessing: "もう一度実行",
  labelButtonProcessItem: "アップロード",
  labelMaxFileSizeExceeded: "ファイルサイズが大きすぎます",
  labelMaxFileSize: "最大ファイルサイズは {filesize} です",
  labelMaxTotalFileSizeExceeded: "最大合計サイズを超えました",
  labelMaxTotalFileSize: "最大合計ファイルサイズは {filesize} です",
  labelFileTypeNotAllowed: "無効なファイルです",
  fileValidateTypeLabelExpectedTypes: "サポートしているファイルは {allButLastType} 又は {lastType} です",
  imageValidateSizeLabelFormatError: "サポートしていない画像です",
  imageValidateSizeLabelImageSizeTooSmall: "画像が小さすぎます",
  imageValidateSizeLabelImageSizeTooBig: "画像が大きすぎます",
  imageValidateSizeLabelExpectedMinSize: "画像の最小サイズは{minWidth}×{minHeight}です",
  imageValidateSizeLabelExpectedMaxSize: "画像の最大サイズは{maxWidth} × {maxHeight}です",
  imageValidateSizeLabelImageResolutionTooLow: "画像の解像度が低すぎます",
  imageValidateSizeLabelImageResolutionTooHigh: "画像の解像度が高すぎます",
  imageValidateSizeLabelExpectedMinResolution: "画像の最小解像度は{minResolution}です",
  imageValidateSizeLabelExpectedMaxResolution: "画像の最大解像度は{maxResolution}です"
}, km_KM = {
  labelIdle: 'ទាញ&ដាក់ហ្វាល់ឯកសាររបស់អ្នក ឬ <span class="filepond--label-action"> ស្វែងរក </span>',
  labelInvalidField: "ចន្លោះមានឯកសារមិនត្រឹមត្រូវ",
  labelFileWaitingForSize: "កំពុងរង់ចាំទំហំ",
  labelFileSizeNotAvailable: "ទំហំមិនអាចប្រើបាន",
  labelFileLoading: "កំពុងដំណើរការ",
  labelFileLoadError: "មានបញ្ហាកំឡុងពេលដំណើរការ",
  labelFileProcessing: "កំពុងផ្ទុកឡើង",
  labelFileProcessingComplete: "ការផ្ទុកឡើងពេញលេញ",
  labelFileProcessingAborted: "ការបង្ហោះត្រូវបានបោះបង់",
  labelFileProcessingError: "មានបញ្ហាកំឡុងពេលកំពុងផ្ទុកឡើង",
  labelFileProcessingRevertError: "មានបញ្ហាកំឡុងពេលត្រឡប់",
  labelFileRemoveError: "មានបញ្ហាកំឡុងពេលដកចេញ",
  labelTapToCancel: "ចុចដើម្បីបោះបង់",
  labelTapToRetry: "ចុចដើម្បីព្យាយាមម្តងទៀត",
  labelTapToUndo: "ចុចដើម្បីមិនធ្វើវិញ",
  labelButtonRemoveItem: "យកចេញ",
  labelButtonAbortItemLoad: "បោះបង់",
  labelButtonRetryItemLoad: "ព្យាយាមម្តងទៀត",
  labelButtonAbortItemProcessing: "បោះបង់",
  labelButtonUndoItemProcessing: "មិនធ្វើវិញ",
  labelButtonRetryItemProcessing: "ព្យាយាមម្តងទៀត",
  labelButtonProcessItem: "ផ្ទុកឡើង",
  labelMaxFileSizeExceeded: "ឯកសារធំពេក",
  labelMaxFileSize: "ទំហំឯកសារអតិបរមាគឺ {filesize}",
  labelMaxTotalFileSizeExceeded: "លើសទំហំសរុបអតិបរមា",
  labelMaxTotalFileSize: "ទំហំឯកសារសរុបអតិបរមាគឺ {filesize}",
  labelFileTypeNotAllowed: "ប្រភេទឯកសារមិនត្រឹមត្រូវ",
  fileValidateTypeLabelExpectedTypes: "រំពឹងថា {allButLastType} ឬ {lastType}",
  imageValidateSizeLabelFormatError: "ប្រភេទរូបភាពមិនត្រឹមត្រូវ",
  imageValidateSizeLabelImageSizeTooSmall: "រូបភាពតូចពេក",
  imageValidateSizeLabelImageSizeTooBig: "រូបភាពធំពេក",
  imageValidateSizeLabelExpectedMinSize: "ទំហំអប្បបរមាគឺ {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "ទំហំអតិបរមាគឺ {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "គុណភាពបង្ហាញទាបពេក",
  imageValidateSizeLabelImageResolutionTooHigh: "គុណភាពបង្ហាញខ្ពស់ពេក",
  imageValidateSizeLabelExpectedMinResolution: "គុណភាពបង្ហាញអប្បបរមាគឺ {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "គុណភាពបង្ហាញអតិបរមាគឺ {maxResolution}"
}, lt_LT = {
  labelIdle: 'Įdėkite failus čia arba <span class="filepond--label-action"> Ieškokite </span>',
  labelInvalidField: "Laukelis talpina netinkamus failus",
  labelFileWaitingForSize: "Laukiama dydžio",
  labelFileSizeNotAvailable: "Dydis nežinomas",
  labelFileLoading: "Kraunama",
  labelFileLoadError: "Klaida įkeliant",
  labelFileProcessing: "Įkeliama",
  labelFileProcessingComplete: "Įkėlimas sėkmingas",
  labelFileProcessingAborted: "Įkėlimas atšauktas",
  labelFileProcessingError: "Įkeliant įvyko klaida",
  labelFileProcessingRevertError: "Atšaukiant įvyko klaida",
  labelFileRemoveError: "Ištrinant įvyko klaida",
  labelTapToCancel: "Palieskite norėdami atšaukti",
  labelTapToRetry: "Palieskite norėdami pakartoti",
  labelTapToUndo: "Palieskite norėdami atšaukti",
  labelButtonRemoveItem: "Ištrinti",
  labelButtonAbortItemLoad: "Sustabdyti",
  labelButtonRetryItemLoad: "Pakartoti",
  labelButtonAbortItemProcessing: "Atšaukti",
  labelButtonUndoItemProcessing: "Atšaukti",
  labelButtonRetryItemProcessing: "Pakartoti",
  labelButtonProcessItem: "Įkelti",
  labelMaxFileSizeExceeded: "Failas per didelis",
  labelMaxFileSize: "Maksimalus failo dydis yra {filesize}",
  labelMaxTotalFileSizeExceeded: "Viršijote maksimalų leistiną dydį",
  labelMaxTotalFileSize: "Maksimalus leistinas dydis yra {filesize}",
  labelFileTypeNotAllowed: "Netinkamas failas",
  fileValidateTypeLabelExpectedTypes: "Tikisi {allButLastType} arba {lastType}",
  imageValidateSizeLabelFormatError: "Nuotraukos formatas nepalaikomas",
  imageValidateSizeLabelImageSizeTooSmall: "Nuotrauka per maža",
  imageValidateSizeLabelImageSizeTooBig: "Nuotrauka per didelė",
  imageValidateSizeLabelExpectedMinSize: "Minimalus dydis yra {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maksimalus dydis yra {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Rezoliucija per maža",
  imageValidateSizeLabelImageResolutionTooHigh: "Rezoliucija per didelė",
  imageValidateSizeLabelExpectedMinResolution: "Minimali rezoliucija yra {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maksimali rezoliucija yra {maxResolution}"
}, nl_NL = {
  labelIdle: 'Drag & Drop je bestanden of <span class="filepond--label-action"> Bladeren </span>',
  labelInvalidField: "Veld bevat ongeldige bestanden",
  labelFileWaitingForSize: "Wachten op grootte",
  labelFileSizeNotAvailable: "Grootte niet beschikbaar",
  labelFileLoading: "Laden",
  labelFileLoadError: "Fout tijdens laden",
  labelFileProcessing: "Uploaden",
  labelFileProcessingComplete: "Upload afgerond",
  labelFileProcessingAborted: "Upload geannuleerd",
  labelFileProcessingError: "Fout tijdens upload",
  labelFileProcessingRevertError: "Fout bij herstellen",
  labelFileRemoveError: "Fout bij verwijderen",
  labelTapToCancel: "tik om te annuleren",
  labelTapToRetry: "tik om opnieuw te proberen",
  labelTapToUndo: "tik om ongedaan te maken",
  labelButtonRemoveItem: "Verwijderen",
  labelButtonAbortItemLoad: "Afbreken",
  labelButtonRetryItemLoad: "Opnieuw proberen",
  labelButtonAbortItemProcessing: "Annuleren",
  labelButtonUndoItemProcessing: "Ongedaan maken",
  labelButtonRetryItemProcessing: "Opnieuw proberen",
  labelButtonProcessItem: "Upload",
  labelMaxFileSizeExceeded: "Bestand is te groot",
  labelMaxFileSize: "Maximale bestandsgrootte is {filesize}",
  labelMaxTotalFileSizeExceeded: "Maximale totale grootte overschreden",
  labelMaxTotalFileSize: "Maximale totale bestandsgrootte is {filesize}",
  labelFileTypeNotAllowed: "Ongeldig bestandstype",
  fileValidateTypeLabelExpectedTypes: "Verwacht {allButLastType} of {lastType}",
  imageValidateSizeLabelFormatError: "Afbeeldingstype niet ondersteund",
  imageValidateSizeLabelImageSizeTooSmall: "Afbeelding is te klein",
  imageValidateSizeLabelImageSizeTooBig: "Afbeelding is te groot",
  imageValidateSizeLabelExpectedMinSize: "Minimale afmeting is {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maximale afmeting is {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Resolutie is te laag",
  imageValidateSizeLabelImageResolutionTooHigh: "Resolution is too high",
  imageValidateSizeLabelExpectedMinResolution: "Minimale resolutie is {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maximale resolutie is {maxResolution}"
}, no_NB = {
  labelIdle: 'Dra og slipp filene dine, eller <span class="filepond--label-action"> Bla gjennom... </span>',
  labelInvalidField: "Feltet inneholder ugyldige filer",
  labelFileWaitingForSize: "Venter på størrelse",
  labelFileSizeNotAvailable: "Størrelse ikke tilgjengelig",
  labelFileLoading: "Laster",
  labelFileLoadError: "Feil under lasting",
  labelFileProcessing: "Laster opp",
  labelFileProcessingComplete: "Opplasting ferdig",
  labelFileProcessingAborted: "Opplasting avbrutt",
  labelFileProcessingError: "Feil under opplasting",
  labelFileProcessingRevertError: "Feil under reversering",
  labelFileRemoveError: "Feil under flytting",
  labelTapToCancel: "klikk for å avbryte",
  labelTapToRetry: "klikk for å prøve på nytt",
  labelTapToUndo: "klikk for å angre",
  labelButtonRemoveItem: "Fjern",
  labelButtonAbortItemLoad: "Avbryt",
  labelButtonRetryItemLoad: "Prøv på nytt",
  labelButtonAbortItemProcessing: "Avbryt",
  labelButtonUndoItemProcessing: "Angre",
  labelButtonRetryItemProcessing: "Prøv på nytt",
  labelButtonProcessItem: "Last opp",
  labelMaxFileSizeExceeded: "Filen er for stor",
  labelMaxFileSize: "Maksimal filstørrelse er {filesize}",
  labelMaxTotalFileSizeExceeded: "Maksimal total størrelse oversteget",
  labelMaxTotalFileSize: "Maksimal total størrelse er {filesize}",
  labelFileTypeNotAllowed: "Ugyldig filtype",
  fileValidateTypeLabelExpectedTypes: "Forventer {allButLastType} eller {lastType}",
  imageValidateSizeLabelFormatError: "Bildeformat ikke støttet",
  imageValidateSizeLabelImageSizeTooSmall: "Bildet er for lite",
  imageValidateSizeLabelImageSizeTooBig: "Bildet er for stort",
  imageValidateSizeLabelExpectedMinSize: "Minimumsstørrelse er {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maksimumsstørrelse er {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Oppløsningen er for lav",
  imageValidateSizeLabelImageResolutionTooHigh: "Oppløsningen er for høy",
  imageValidateSizeLabelExpectedMinResolution: "Minimum oppløsning er {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maksimal oppløsning er {maxResolution}"
}, pl_PL = {
  labelIdle: 'Przeciągnij i upuść lub <span class="filepond--label-action">wybierz</span> pliki',
  labelInvalidField: "Nieprawidłowe pliki",
  labelFileWaitingForSize: "Pobieranie rozmiaru",
  labelFileSizeNotAvailable: "Nieznany rozmiar",
  labelFileLoading: "Wczytywanie",
  labelFileLoadError: "Błąd wczytywania",
  labelFileProcessing: "Przesyłanie",
  labelFileProcessingComplete: "Przesłano",
  labelFileProcessingAborted: "Przerwano",
  labelFileProcessingError: "Przesyłanie nie powiodło się",
  labelFileProcessingRevertError: "Coś poszło nie tak",
  labelFileRemoveError: "Nieudane usunięcie",
  labelTapToCancel: "Anuluj",
  labelTapToRetry: "Ponów",
  labelTapToUndo: "Cofnij",
  labelButtonRemoveItem: "Usuń",
  labelButtonAbortItemLoad: "Przerwij",
  labelButtonRetryItemLoad: "Ponów",
  labelButtonAbortItemProcessing: "Anuluj",
  labelButtonUndoItemProcessing: "Cofnij",
  labelButtonRetryItemProcessing: "Ponów",
  labelButtonProcessItem: "Prześlij",
  labelMaxFileSizeExceeded: "Plik jest zbyt duży",
  labelMaxFileSize: "Dopuszczalna wielkość pliku to {filesize}",
  labelMaxTotalFileSizeExceeded: "Przekroczono łączny rozmiar plików",
  labelMaxTotalFileSize: "Łączny rozmiar plików nie może przekroczyć {filesize}",
  labelFileTypeNotAllowed: "Niedozwolony rodzaj pliku",
  fileValidateTypeLabelExpectedTypes: "Oczekiwano {allButLastType} lub {lastType}",
  imageValidateSizeLabelFormatError: "Nieobsługiwany format obrazu",
  imageValidateSizeLabelImageSizeTooSmall: "Obraz jest zbyt mały",
  imageValidateSizeLabelImageSizeTooBig: "Obraz jest zbyt duży",
  imageValidateSizeLabelExpectedMinSize: "Minimalne wymiary obrazu to {minWidth}×{minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maksymalna wymiary obrazu to {maxWidth}×{maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Rozdzielczość jest zbyt niska",
  imageValidateSizeLabelImageResolutionTooHigh: "Rozdzielczość jest zbyt wysoka",
  imageValidateSizeLabelExpectedMinResolution: "Minimalna rozdzielczość to {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maksymalna rozdzielczość to {maxResolution}"
}, pt_BR = {
  labelIdle: 'Arraste e solte os arquivos ou <span class="filepond--label-action"> Clique aqui </span>',
  labelInvalidField: "Arquivos inválidos",
  labelFileWaitingForSize: "Calculando o tamanho do arquivo",
  labelFileSizeNotAvailable: "Tamanho do arquivo indisponível",
  labelFileLoading: "Carregando",
  labelFileLoadError: "Erro durante o carregamento",
  labelFileProcessing: "Enviando",
  labelFileProcessingComplete: "Envio finalizado",
  labelFileProcessingAborted: "Envio cancelado",
  labelFileProcessingError: "Erro durante o envio",
  labelFileProcessingRevertError: "Erro ao reverter o envio",
  labelFileRemoveError: "Erro ao remover o arquivo",
  labelTapToCancel: "clique para cancelar",
  labelTapToRetry: "clique para reenviar",
  labelTapToUndo: "clique para desfazer",
  labelButtonRemoveItem: "Remover",
  labelButtonAbortItemLoad: "Abortar",
  labelButtonRetryItemLoad: "Reenviar",
  labelButtonAbortItemProcessing: "Cancelar",
  labelButtonUndoItemProcessing: "Desfazer",
  labelButtonRetryItemProcessing: "Reenviar",
  labelButtonProcessItem: "Enviar",
  labelMaxFileSizeExceeded: "Arquivo é muito grande",
  labelMaxFileSize: "O tamanho máximo permitido: {filesize}",
  labelMaxTotalFileSizeExceeded: "Tamanho total dos arquivos excedido",
  labelMaxTotalFileSize: "Tamanho total permitido: {filesize}",
  labelFileTypeNotAllowed: "Tipo de arquivo inválido",
  fileValidateTypeLabelExpectedTypes: "Tipos de arquivo suportados são {allButLastType} ou {lastType}",
  imageValidateSizeLabelFormatError: "Tipo de imagem inválida",
  imageValidateSizeLabelImageSizeTooSmall: "Imagem muito pequena",
  imageValidateSizeLabelImageSizeTooBig: "Imagem muito grande",
  imageValidateSizeLabelExpectedMinSize: "Tamanho mínimo permitida: {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Tamanho máximo permitido: {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Resolução muito baixa",
  imageValidateSizeLabelImageResolutionTooHigh: "Resolução muito alta",
  imageValidateSizeLabelExpectedMinResolution: "Resolução mínima permitida: {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Resolução máxima permitida: {maxResolution}"
}, pt_PT = {
  labelIdle: 'Arraste & Largue os ficheiros ou <span class="filepond--label-action"> Seleccione </span>',
  labelInvalidField: "O campo contém ficheiros inválidos",
  labelFileWaitingForSize: "A aguardar tamanho",
  labelFileSizeNotAvailable: "Tamanho não disponível",
  labelFileLoading: "A carregar",
  labelFileLoadError: "Erro ao carregar",
  labelFileProcessing: "A carregar",
  labelFileProcessingComplete: "Carregamento completo",
  labelFileProcessingAborted: "Carregamento cancelado",
  labelFileProcessingError: "Erro ao carregar",
  labelFileProcessingRevertError: "Erro ao reverter",
  labelFileRemoveError: "Erro ao remover",
  labelTapToCancel: "carregue para cancelar",
  labelTapToRetry: "carregue para tentar novamente",
  labelTapToUndo: "carregue para desfazer",
  labelButtonRemoveItem: "Remover",
  labelButtonAbortItemLoad: "Abortar",
  labelButtonRetryItemLoad: "Tentar novamente",
  labelButtonAbortItemProcessing: "Cancelar",
  labelButtonUndoItemProcessing: "Desfazer",
  labelButtonRetryItemProcessing: "Tentar novamente",
  labelButtonProcessItem: "Carregar",
  labelMaxFileSizeExceeded: "Ficheiro demasiado grande",
  labelMaxFileSize: "O tamanho máximo do ficheiro é de {filesize}",
  labelMaxTotalFileSizeExceeded: "Tamanho máximo total excedido",
  labelMaxTotalFileSize: "O tamanho máximo total do ficheiro é de {filesize}",
  labelFileTypeNotAllowed: "Tipo de ficheiro inválido",
  fileValidateTypeLabelExpectedTypes: "É esperado {allButLastType} ou {lastType}",
  imageValidateSizeLabelFormatError: "Tipo de imagem não suportada",
  imageValidateSizeLabelImageSizeTooSmall: "A imagem é demasiado pequena",
  imageValidateSizeLabelImageSizeTooBig: "A imagem é demasiado grande",
  imageValidateSizeLabelExpectedMinSize: "O tamanho mínimo é de {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "O tamanho máximo é de {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "A resolução é demasiado baixa",
  imageValidateSizeLabelImageResolutionTooHigh: "A resolução é demasiado grande",
  imageValidateSizeLabelExpectedMinResolution: "A resolução mínima é de {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "A resolução máxima é de {maxResolution}"
}, ro_RO = {
  labelIdle: 'Trage și plasează fișiere sau <span class="filepond--label-action"> Caută-le </span>',
  labelInvalidField: "Câmpul conține fișiere care nu sunt valide",
  labelFileWaitingForSize: "În așteptarea dimensiunii",
  labelFileSizeNotAvailable: "Dimensiunea nu este diponibilă",
  labelFileLoading: "Se încarcă",
  labelFileLoadError: "Eroare la încărcare",
  labelFileProcessing: "Se încarcă",
  labelFileProcessingComplete: "Încărcare finalizată",
  labelFileProcessingAborted: "Încărcare anulată",
  labelFileProcessingError: "Eroare la încărcare",
  labelFileProcessingRevertError: "Eroare la anulare",
  labelFileRemoveError: "Eroare la ştergere",
  labelTapToCancel: "apasă pentru a anula",
  labelTapToRetry: "apasă pentru a reîncerca",
  labelTapToUndo: "apasă pentru a anula",
  labelButtonRemoveItem: "Şterge",
  labelButtonAbortItemLoad: "Anulează",
  labelButtonRetryItemLoad: "Reîncearcă",
  labelButtonAbortItemProcessing: "Anulează",
  labelButtonUndoItemProcessing: "Anulează",
  labelButtonRetryItemProcessing: "Reîncearcă",
  labelButtonProcessItem: "Încarcă",
  labelMaxFileSizeExceeded: "Fișierul este prea mare",
  labelMaxFileSize: "Dimensiunea maximă a unui fișier este de {filesize}",
  labelMaxTotalFileSizeExceeded: "Dimensiunea totală maximă a fost depășită",
  labelMaxTotalFileSize: "Dimensiunea totală maximă a fișierelor este de {filesize}",
  labelFileTypeNotAllowed: "Tipul fișierului nu este valid",
  fileValidateTypeLabelExpectedTypes: "Se așteaptă {allButLastType} sau {lastType}",
  imageValidateSizeLabelFormatError: "Formatul imaginii nu este acceptat",
  imageValidateSizeLabelImageSizeTooSmall: "Imaginea este prea mică",
  imageValidateSizeLabelImageSizeTooBig: "Imaginea este prea mare",
  imageValidateSizeLabelExpectedMinSize: "Mărimea minimă este de {maxWidth} x {maxHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Mărimea maximă este de {maxWidth} x {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Rezoluția este prea mică",
  imageValidateSizeLabelImageResolutionTooHigh: "Rezoluția este prea mare",
  imageValidateSizeLabelExpectedMinResolution: "Rezoluția minimă este de {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Rezoluția maximă este de {maxResolution}"
}, ru_RU = {
  labelIdle: 'Перетащите файлы или <span class="filepond--label-action"> выберите </span>',
  labelInvalidField: "Поле содержит недопустимые файлы",
  labelFileWaitingForSize: "Укажите размер",
  labelFileSizeNotAvailable: "Размер не поддерживается",
  labelFileLoading: "Ожидание",
  labelFileLoadError: "Ошибка при ожидании",
  labelFileProcessing: "Загрузка",
  labelFileProcessingComplete: "Загрузка завершена",
  labelFileProcessingAborted: "Загрузка отменена",
  labelFileProcessingError: "Ошибка при загрузке",
  labelFileProcessingRevertError: "Ошибка при возврате",
  labelFileRemoveError: "Ошибка при удалении",
  labelTapToCancel: "нажмите для отмены",
  labelTapToRetry: "нажмите, чтобы повторить попытку",
  labelTapToUndo: "нажмите для отмены последнего действия",
  labelButtonRemoveItem: "Удалить",
  labelButtonAbortItemLoad: "Прекращено",
  labelButtonRetryItemLoad: "Повторите попытку",
  labelButtonAbortItemProcessing: "Отмена",
  labelButtonUndoItemProcessing: "Отмена последнего действия",
  labelButtonRetryItemProcessing: "Повторите попытку",
  labelButtonProcessItem: "Загрузка",
  labelMaxFileSizeExceeded: "Файл слишком большой",
  labelMaxFileSize: "Максимальный размер файла: {filesize}",
  labelMaxTotalFileSizeExceeded: "Превышен максимальный размер",
  labelMaxTotalFileSize: "Максимальный размер файла: {filesize}",
  labelFileTypeNotAllowed: "Файл неверного типа",
  fileValidateTypeLabelExpectedTypes: "Ожидается {allButLastType} или {lastType}",
  imageValidateSizeLabelFormatError: "Тип изображения не поддерживается",
  imageValidateSizeLabelImageSizeTooSmall: "Изображение слишком маленькое",
  imageValidateSizeLabelImageSizeTooBig: "Изображение слишком большое",
  imageValidateSizeLabelExpectedMinSize: "Минимальный размер: {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Максимальный размер: {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Разрешение слишком низкое",
  imageValidateSizeLabelImageResolutionTooHigh: "Разрешение слишком высокое",
  imageValidateSizeLabelExpectedMinResolution: "Минимальное разрешение: {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Максимальное разрешение: {maxResolution}"
}, sk_SK = {
  labelIdle: 'Natiahnúť súbor (drag&drop) alebo <span class="filepond--label-action"> Vyhľadať </span>',
  labelInvalidField: "Pole obsahuje chybné súbory",
  labelFileWaitingForSize: "Zisťuje sa veľkosť",
  labelFileSizeNotAvailable: "Neznáma veľkosť",
  labelFileLoading: "Prenáša sa",
  labelFileLoadError: "Chyba pri prenose",
  labelFileProcessing: "Prebieha upload",
  labelFileProcessingComplete: "Upload dokončený",
  labelFileProcessingAborted: "Upload stornovaný",
  labelFileProcessingError: "Chyba pri uploade",
  labelFileProcessingRevertError: "Chyba pri obnove",
  labelFileRemoveError: "Chyba pri odstránení",
  labelTapToCancel: "Kliknite pre storno",
  labelTapToRetry: "Kliknite pre opakovanie",
  labelTapToUndo: "Kliknite pre vrátenie",
  labelButtonRemoveItem: "Odstrániť",
  labelButtonAbortItemLoad: "Storno",
  labelButtonRetryItemLoad: "Opakovať",
  labelButtonAbortItemProcessing: "Späť",
  labelButtonUndoItemProcessing: "Vrátiť",
  labelButtonRetryItemProcessing: "Opakovať",
  labelButtonProcessItem: "Upload",
  labelMaxFileSizeExceeded: "Súbor je príliš veľký",
  labelMaxFileSize: "Najväčšia veľkosť súboru je {filesize}",
  labelMaxTotalFileSizeExceeded: "Prekročená maximálna celková veľkosť súboru",
  labelMaxTotalFileSize: "Maximálna celková veľkosť súboru je {filesize}",
  labelFileTypeNotAllowed: "Súbor je nesprávneho typu",
  fileValidateTypeLabelExpectedTypes: "Očakáva sa {allButLastType} alebo {lastType}",
  imageValidateSizeLabelFormatError: "Obrázok tohto typu nie je podporovaný",
  imageValidateSizeLabelImageSizeTooSmall: "Obrázok je príliš malý",
  imageValidateSizeLabelImageSizeTooBig: "Obrázok je príliš veľký",
  imageValidateSizeLabelExpectedMinSize: "Minimálny rozmer je {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maximálny rozmer je {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Rozlíšenie je príliš malé",
  imageValidateSizeLabelImageResolutionTooHigh: "Rozlišenie je príliš veľké",
  imageValidateSizeLabelExpectedMinResolution: "Minimálne rozlíšenie je {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maximálne rozlíšenie je {maxResolution}"
}, sv_SE = {
  labelIdle: 'Drag och släpp dina filer eller <span class="filepond--label-action"> Bläddra </span>',
  labelInvalidField: "Fältet innehåller felaktiga filer",
  labelFileWaitingForSize: "Väntar på storlek",
  labelFileSizeNotAvailable: "Storleken finns inte tillgänglig",
  labelFileLoading: "Laddar",
  labelFileLoadError: "Fel under laddning",
  labelFileProcessing: "Laddar upp",
  labelFileProcessingComplete: "Uppladdning klar",
  labelFileProcessingAborted: "Uppladdning avbruten",
  labelFileProcessingError: "Fel under uppladdning",
  labelFileProcessingRevertError: "Fel under återställning",
  labelFileRemoveError: "Fel under borttagning",
  labelTapToCancel: "tryck för att avbryta",
  labelTapToRetry: "tryck för att försöka igen",
  labelTapToUndo: "tryck för att ångra",
  labelButtonRemoveItem: "Tabort",
  labelButtonAbortItemLoad: "Avbryt",
  labelButtonRetryItemLoad: "Försök igen",
  labelButtonAbortItemProcessing: "Avbryt",
  labelButtonUndoItemProcessing: "Ångra",
  labelButtonRetryItemProcessing: "Försök igen",
  labelButtonProcessItem: "Ladda upp",
  labelMaxFileSizeExceeded: "Filen är för stor",
  labelMaxFileSize: "Största tillåtna filstorlek är {filesize}",
  labelMaxTotalFileSizeExceeded: "Maximal uppladdningsstorlek uppnåd",
  labelMaxTotalFileSize: "Maximal uppladdningsstorlek är {filesize}",
  labelFileTypeNotAllowed: "Felaktig filtyp",
  fileValidateTypeLabelExpectedTypes: "Godkända filtyper {allButLastType} eller {lastType}",
  imageValidateSizeLabelFormatError: "Bildtypen saknar stöd",
  imageValidateSizeLabelImageSizeTooSmall: "Bilden är för liten",
  imageValidateSizeLabelImageSizeTooBig: "Bilden är för stor",
  imageValidateSizeLabelExpectedMinSize: "Minimal storlek är {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maximal storlek är {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Upplösningen är för låg",
  imageValidateSizeLabelImageResolutionTooHigh: "Upplösningen är för hög",
  imageValidateSizeLabelExpectedMinResolution: "Minsta tillåtna upplösning är {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Högsta tillåtna upplösning är {maxResolution}"
}, tr_TR = {
  labelIdle: 'Dosyanızı Sürükleyin & Bırakın ya da <span class="filepond--label-action"> Seçin </span>',
  labelInvalidField: "Alan geçersiz dosyalar içeriyor",
  labelFileWaitingForSize: "Boyut hesaplanıyor",
  labelFileSizeNotAvailable: "Boyut mevcut değil",
  labelFileLoading: "Yükleniyor",
  labelFileLoadError: "Yükleme sırasında hata oluştu",
  labelFileProcessing: "Yükleniyor",
  labelFileProcessingComplete: "Yükleme tamamlandı",
  labelFileProcessingAborted: "Yükleme iptal edildi",
  labelFileProcessingError: "Yüklerken hata oluştu",
  labelFileProcessingRevertError: "Geri çekerken hata oluştu",
  labelFileRemoveError: "Kaldırırken hata oluştu",
  labelTapToCancel: "İptal etmek için tıklayın",
  labelTapToRetry: "Tekrar denemek için tıklayın",
  labelTapToUndo: "Geri almak için tıklayın",
  labelButtonRemoveItem: "Kaldır",
  labelButtonAbortItemLoad: "İptal Et",
  labelButtonRetryItemLoad: "Tekrar dene",
  labelButtonAbortItemProcessing: "İptal et",
  labelButtonUndoItemProcessing: "Geri Al",
  labelButtonRetryItemProcessing: "Tekrar dene",
  labelButtonProcessItem: "Yükle",
  labelMaxFileSizeExceeded: "Dosya çok büyük",
  labelMaxFileSize: "En fazla dosya boyutu: {filesize}",
  labelMaxTotalFileSizeExceeded: "Maximum boyut aşıldı",
  labelMaxTotalFileSize: "Maximum dosya boyutu :{filesize}",
  labelFileTypeNotAllowed: "Geçersiz dosya tipi",
  fileValidateTypeLabelExpectedTypes: "Şu {allButLastType} ya da şu dosya olması gerekir: {lastType}",
  imageValidateSizeLabelFormatError: "Resim tipi desteklenmiyor",
  imageValidateSizeLabelImageSizeTooSmall: "Resim çok küçük",
  imageValidateSizeLabelImageSizeTooBig: "Resim çok büyük",
  imageValidateSizeLabelExpectedMinSize: "Minimum boyut {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Maximum boyut {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Çözünürlük çok düşük",
  imageValidateSizeLabelImageResolutionTooHigh: "Çözünürlük çok yüksek",
  imageValidateSizeLabelExpectedMinResolution: "Minimum çözünürlük {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Maximum çözünürlük {maxResolution}"
}, uk_UA = {
  labelIdle: 'Перетягніть файли або <span class="filepond--label-action"> виберіть </span>',
  labelInvalidField: "Поле містить недопустимі файли",
  labelFileWaitingForSize: "Вкажіть розмір",
  labelFileSizeNotAvailable: "Розмір не доступний",
  labelFileLoading: "Очікування",
  labelFileLoadError: "Помилка при очікуванні",
  labelFileProcessing: "Завантаження",
  labelFileProcessingComplete: "Завантаження завершено",
  labelFileProcessingAborted: "Завантаження скасовано",
  labelFileProcessingError: "Помилка при завантаженні",
  labelFileProcessingRevertError: "Помилка при відновленні",
  labelFileRemoveError: "Помилка при видаленні",
  labelTapToCancel: "Відмінити",
  labelTapToRetry: "Натисніть, щоб повторити спробу",
  labelTapToUndo: "Натисніть, щоб відмінити останню дію",
  labelButtonRemoveItem: "Видалити",
  labelButtonAbortItemLoad: "Відмінити",
  labelButtonRetryItemLoad: "Повторити спробу",
  labelButtonAbortItemProcessing: "Відмінити",
  labelButtonUndoItemProcessing: "Відмінити останню дію",
  labelButtonRetryItemProcessing: "Повторити спробу",
  labelButtonProcessItem: "Завантаження",
  labelMaxFileSizeExceeded: "Файл занадто великий",
  labelMaxFileSize: "Максимальний розмір файлу: {filesize}",
  labelMaxTotalFileSizeExceeded: "Перевищено максимальний загальний розмір",
  labelMaxTotalFileSize: "Максимальний загальний розмір: {filesize}",
  labelFileTypeNotAllowed: "Формат файлу не підтримується",
  fileValidateTypeLabelExpectedTypes: "Очікується {allButLastType} або {lastType}",
  imageValidateSizeLabelFormatError: "Формат зображення не підтримується",
  imageValidateSizeLabelImageSizeTooSmall: "Зображення занадто маленьке",
  imageValidateSizeLabelImageSizeTooBig: "Зображення занадто велике",
  imageValidateSizeLabelExpectedMinSize: "Мінімальний розмір: {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Максимальний розмір: {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Розміри зображення занадто маленькі",
  imageValidateSizeLabelImageResolutionTooHigh: "Розміри зображення занадто великі",
  imageValidateSizeLabelExpectedMinResolution: "Мінімальні розміри: {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Максимальні розміри: {maxResolution}"
}, vi_VI = {
  labelIdle: 'Kéo thả tệp của bạn hoặc <span class="filepond--label-action"> Tìm kiếm </span>',
  labelInvalidField: "Trường chứa các tệp không hợp lệ",
  labelFileWaitingForSize: "Đang chờ kích thước",
  labelFileSizeNotAvailable: "Kích thước không có sẵn",
  labelFileLoading: "Đang tải",
  labelFileLoadError: "Lỗi khi tải",
  labelFileProcessing: "Đang tải lên",
  labelFileProcessingComplete: "Tải lên thành công",
  labelFileProcessingAborted: "Đã huỷ tải lên",
  labelFileProcessingError: "Lỗi khi tải lên",
  labelFileProcessingRevertError: "Lỗi khi hoàn nguyên",
  labelFileRemoveError: "Lỗi khi xóa",
  labelTapToCancel: "nhấn để hủy",
  labelTapToRetry: "nhấn để thử lại",
  labelTapToUndo: "nhấn để hoàn tác",
  labelButtonRemoveItem: "Xoá",
  labelButtonAbortItemLoad: "Huỷ bỏ",
  labelButtonRetryItemLoad: "Thử lại",
  labelButtonAbortItemProcessing: "Hủy bỏ",
  labelButtonUndoItemProcessing: "Hoàn tác",
  labelButtonRetryItemProcessing: "Thử lại",
  labelButtonProcessItem: "Tải lên",
  labelMaxFileSizeExceeded: "Tập tin quá lớn",
  labelMaxFileSize: "Kích thước tệp tối đa là {filesize}",
  labelMaxTotalFileSizeExceeded: "Đã vượt quá tổng kích thước tối đa",
  labelMaxTotalFileSize: "Tổng kích thước tệp tối đa là {filesize}",
  labelFileTypeNotAllowed: "Tệp thuộc loại không hợp lệ",
  fileValidateTypeLabelExpectedTypes: "Kiểu tệp hợp lệ là {allButLastType} hoặc {lastType}",
  imageValidateSizeLabelFormatError: "Loại hình ảnh không được hỗ trợ",
  imageValidateSizeLabelImageSizeTooSmall: "Hình ảnh quá nhỏ",
  imageValidateSizeLabelImageSizeTooBig: "Hình ảnh quá lớn",
  imageValidateSizeLabelExpectedMinSize: "Kích thước tối thiểu là {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "Kích thước tối đa là {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "Độ phân giải quá thấp",
  imageValidateSizeLabelImageResolutionTooHigh: "Độ phân giải quá cao",
  imageValidateSizeLabelExpectedMinResolution: "Độ phân giải tối thiểu là {minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "Độ phân giải tối đa là {maxResolution}"
}, zh_CN = {
  labelIdle: '拖放文件，或者 <span class="filepond--label-action"> 浏览 </span>',
  labelInvalidField: "字段包含无效文件",
  labelFileWaitingForSize: "计算文件大小",
  labelFileSizeNotAvailable: "文件大小不可用",
  labelFileLoading: "加载",
  labelFileLoadError: "加载错误",
  labelFileProcessing: "上传",
  labelFileProcessingComplete: "已上传",
  labelFileProcessingAborted: "上传已取消",
  labelFileProcessingError: "上传出错",
  labelFileProcessingRevertError: "还原出错",
  labelFileRemoveError: "删除出错",
  labelTapToCancel: "点击取消",
  labelTapToRetry: "点击重试",
  labelTapToUndo: "点击撤消",
  labelButtonRemoveItem: "删除",
  labelButtonAbortItemLoad: "中止",
  labelButtonRetryItemLoad: "重试",
  labelButtonAbortItemProcessing: "取消",
  labelButtonUndoItemProcessing: "撤消",
  labelButtonRetryItemProcessing: "重试",
  labelButtonProcessItem: "上传",
  labelMaxFileSizeExceeded: "文件太大",
  labelMaxFileSize: "最大值: {filesize}",
  labelMaxTotalFileSizeExceeded: "超过最大文件大小",
  labelMaxTotalFileSize: "最大文件大小：{filesize}",
  labelFileTypeNotAllowed: "文件类型无效",
  fileValidateTypeLabelExpectedTypes: "应为 {allButLastType} 或 {lastType}",
  imageValidateSizeLabelFormatError: "不支持图像类型",
  imageValidateSizeLabelImageSizeTooSmall: "图像太小",
  imageValidateSizeLabelImageSizeTooBig: "图像太大",
  imageValidateSizeLabelExpectedMinSize: "最小值: {minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "最大值: {maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "分辨率太低",
  imageValidateSizeLabelImageResolutionTooHigh: "分辨率太高",
  imageValidateSizeLabelExpectedMinResolution: "最小分辨率：{minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "最大分辨率：{maxResolution}"
}, zh_TW = {
  labelIdle: '拖放檔案，或者 <span class="filepond--label-action"> 瀏覽 </span>',
  labelInvalidField: "不支援此檔案",
  labelFileWaitingForSize: "正在計算檔案大小",
  labelFileSizeNotAvailable: "檔案大小不符",
  labelFileLoading: "讀取中",
  labelFileLoadError: "讀取錯誤",
  labelFileProcessing: "上傳",
  labelFileProcessingComplete: "已上傳",
  labelFileProcessingAborted: "上傳已取消",
  labelFileProcessingError: "上傳發生錯誤",
  labelFileProcessingRevertError: "還原錯誤",
  labelFileRemoveError: "刪除錯誤",
  labelTapToCancel: "點擊取消",
  labelTapToRetry: "點擊重試",
  labelTapToUndo: "點擊還原",
  labelButtonRemoveItem: "刪除",
  labelButtonAbortItemLoad: "停止",
  labelButtonRetryItemLoad: "重試",
  labelButtonAbortItemProcessing: "取消",
  labelButtonUndoItemProcessing: "取消",
  labelButtonRetryItemProcessing: "重試",
  labelButtonProcessItem: "上傳",
  labelMaxFileSizeExceeded: "檔案過大",
  labelMaxFileSize: "最大值：{filesize}",
  labelMaxTotalFileSizeExceeded: "超過最大可上傳大小",
  labelMaxTotalFileSize: "最大可上傳大小：{filesize}",
  labelFileTypeNotAllowed: "不支援此類型檔案",
  fileValidateTypeLabelExpectedTypes: "應為 {allButLastType} 或 {lastType}",
  imageValidateSizeLabelFormatError: "不支持此類圖片類型",
  imageValidateSizeLabelImageSizeTooSmall: "圖片過小",
  imageValidateSizeLabelImageSizeTooBig: "圖片過大",
  imageValidateSizeLabelExpectedMinSize: "最小尺寸：{minWidth} × {minHeight}",
  imageValidateSizeLabelExpectedMaxSize: "最大尺寸：{maxWidth} × {maxHeight}",
  imageValidateSizeLabelImageResolutionTooLow: "解析度過低",
  imageValidateSizeLabelImageResolutionTooHigh: "解析度過高",
  imageValidateSizeLabelExpectedMinResolution: "最低解析度：{minResolution}",
  imageValidateSizeLabelExpectedMaxResolution: "最高解析度：{maxResolution}"
}, locales = {
  "ar-ar": ar_AR,
  "az-az": az_AZ,
  "cs-cz": cs_CZ,
  "da-dk": da_DK,
  "de-de": de_DE,
  "el-el": el_EL,
  "en-en": en_EN,
  "es-es": es_ES,
  "fa-ir": fa_IR,
  "fi-fi": fi_FI,
  "fr-fr": fr_FR,
  "he-he": he_HE,
  "hr-hr": hr_HR,
  "hu-hu": hu_HU,
  "id-id": id_ID,
  "it-it": it_IT,
  "ja-ja": ja_JA,
  "km-km": km_KM,
  "lt-lt": lt_LT,
  "nl-nl": nl_NL,
  "no-nb": no_NB,
  "pl-pl": pl_PL,
  "pt-br": pt_BR,
  "pt-pt": pt_PT,
  "ro-ro": ro_RO,
  "ru-ru": ru_RU,
  "sk-sk": sk_SK,
  "sv-se": sv_SE,
  "tr-tr": tr_TR,
  "uk-ua": uk_UA,
  "vi-vi": vi_VI,
  "zh-cn": zh_CN,
  "zh-tw": zh_TW
}, filepond = (e) => ({
  init() {
    registerPlugin(plugin$3), registerPlugin(plugin$2), registerPlugin(plugin$1), registerPlugin(plugin);
    const t = this.$wire.fields[e.field].properties;
    create$f(this.$refs.input, {
      allowMultiple: t.multiple,
      minFileSize: t.file_size.min ? `${t.file_size.min}KB` : null,
      maxFileSize: t.file_size.max ? `${t.file_size.max}KB` : null,
      acceptedFileTypes: t.file_types,
      imageValidateSizeMinWidth: t.dimensions.min_width ?? 1,
      imageValidateSizeMinHeight: t.dimensions.min_height ?? 1,
      imageValidateSizeMaxWidth: t.dimensions.max_width ?? 65535,
      imageValidateSizeMaxHeight: t.dimensions.max_height ?? 65535,
      credits: !1,
      server: {
        process: (i, a, n, l, r, s, o, d, c) => {
          this.$wire.upload(t.key, a, l, r, s);
        },
        revert: (i, a) => {
          this.$wire.removeUpload(t.key, i, a);
        }
      },
      ...locales[e.locale]
    });
  },
  reset(t) {
    t === this.$wire.id && find(this.$el.querySelector(".filepond--root")).removeFiles();
  }
}), KEYS = [
  "if",
  "if_any",
  "show_when",
  "show_when_any",
  "unless",
  "unless_any",
  "hide_when",
  "hide_when_any"
], OPERATORS = [
  "equals",
  "not",
  "contains",
  "contains_any",
  "===",
  "!==",
  ">",
  ">=",
  "<",
  "<=",
  "custom"
], ALIASES = {
  is: "equals",
  "==": "equals",
  isnt: "not",
  "!=": "not",
  includes: "contains",
  includes_any: "contains_any"
};
var VERSION = "1.13.6", root = typeof self == "object" && self.self === self && self || typeof global == "object" && global.global === global && global || Function("return this")() || {}, ArrayProto = Array.prototype, ObjProto = Object.prototype, push = ArrayProto.push, slice = ArrayProto.slice, toString = ObjProto.toString, hasOwnProperty = ObjProto.hasOwnProperty, nativeIsArray = Array.isArray, nativeKeys = Object.keys, _isNaN = isNaN, hasEnumBug = !{ toString: null }.propertyIsEnumerable("toString"), nonEnumerableProps = [
  "valueOf",
  "isPrototypeOf",
  "toString",
  "propertyIsEnumerable",
  "hasOwnProperty",
  "toLocaleString"
], MAX_ARRAY_INDEX = Math.pow(2, 53) - 1;
function _(e) {
  if (e instanceof _) return e;
  if (!(this instanceof _)) return new _(e);
  this._wrapped = e;
}
_.VERSION = VERSION;
_.prototype.value = function() {
  return this._wrapped;
};
_.prototype.valueOf = _.prototype.toJSON = _.prototype.value;
_.prototype.toString = function() {
  return String(this._wrapped);
};
function identity(e) {
  return e;
}
function tagTester(e) {
  var t = "[object " + e + "]";
  return function(i) {
    return toString.call(i) === t;
  };
}
var isFunction = tagTester("Function"), nodelist = root.document && root.document.childNodes;
typeof /./ != "function" && typeof Int8Array != "object" && typeof nodelist != "function" && (isFunction = function(e) {
  return typeof e == "function" || !1;
});
const isFunction$1 = isFunction;
function isObject(e) {
  var t = typeof e;
  return t === "function" || t === "object" && !!e;
}
const isArray = nativeIsArray || tagTester("Array");
function createAssigner(e, t) {
  return function(i) {
    var a = arguments.length;
    if (a < 2 || i == null) return i;
    for (var n = 1; n < a; n++)
      for (var l = arguments[n], r = e(l), s = r.length, o = 0; o < s; o++) {
        var d = r[o];
        i[d] = l[d];
      }
    return i;
  };
}
function has(e, t) {
  return e != null && hasOwnProperty.call(e, t);
}
function emulatedSet(e) {
  for (var t = {}, i = e.length, a = 0; a < i; ++a) t[e[a]] = !0;
  return {
    contains: function(n) {
      return t[n] === !0;
    },
    push: function(n) {
      return t[n] = !0, e.push(n);
    }
  };
}
function collectNonEnumProps(e, t) {
  t = emulatedSet(t);
  var i = nonEnumerableProps.length, a = e.constructor, n = isFunction$1(a) && a.prototype || ObjProto, l = "constructor";
  for (has(e, l) && !t.contains(l) && t.push(l); i--; )
    l = nonEnumerableProps[i], l in e && e[l] !== n[l] && !t.contains(l) && t.push(l);
}
function keys(e) {
  if (!isObject(e)) return [];
  if (nativeKeys) return nativeKeys(e);
  var t = [];
  for (var i in e) has(e, i) && t.push(i);
  return hasEnumBug && collectNonEnumProps(e, t), t;
}
const extendOwn = createAssigner(keys);
function isMatch(e, t) {
  var i = keys(t), a = i.length;
  if (e == null) return !a;
  for (var n = Object(e), l = 0; l < a; l++) {
    var r = i[l];
    if (t[r] !== n[r] || !(r in n)) return !1;
  }
  return !0;
}
function matcher(e) {
  return e = extendOwn({}, e), function(t) {
    return isMatch(t, e);
  };
}
function deepGet(e, t) {
  for (var i = t.length, a = 0; a < i; a++) {
    if (e == null) return;
    e = e[t[a]];
  }
  return i ? e : void 0;
}
function toPath$1(e) {
  return isArray(e) ? e : [e];
}
_.toPath = toPath$1;
function toPath(e) {
  return _.toPath(e);
}
function property(e) {
  return e = toPath(e), function(t) {
    return deepGet(t, e);
  };
}
function optimizeCb(e, t, i) {
  if (t === void 0) return e;
  switch (i ?? 3) {
    case 1:
      return function(a) {
        return e.call(t, a);
      };
    // The 2-argument case is omitted because we’re not using it.
    case 3:
      return function(a, n, l) {
        return e.call(t, a, n, l);
      };
    case 4:
      return function(a, n, l, r) {
        return e.call(t, a, n, l, r);
      };
  }
  return function() {
    return e.apply(t, arguments);
  };
}
function baseIteratee(e, t, i) {
  return e == null ? identity : isFunction$1(e) ? optimizeCb(e, t, i) : isObject(e) && !isArray(e) ? matcher(e) : property(e);
}
function iteratee(e, t) {
  return baseIteratee(e, t, 1 / 0);
}
_.iteratee = iteratee;
function cb(e, t, i) {
  return _.iteratee !== iteratee ? _.iteratee(e, t) : baseIteratee(e, t, i);
}
function createSizePropertyCheck(e) {
  return function(t) {
    var i = e(t);
    return typeof i == "number" && i >= 0 && i <= MAX_ARRAY_INDEX;
  };
}
function shallowProperty(e) {
  return function(t) {
    return t == null ? void 0 : t[e];
  };
}
const getLength = shallowProperty("length"), isArrayLike = createSizePropertyCheck(getLength);
function map(e, t, i) {
  t = cb(t, i);
  for (var a = !isArrayLike(e) && keys(e), n = (a || e).length, l = Array(n), r = 0; r < n; r++) {
    var s = a ? a[r] : r;
    l[r] = t(e[s], s, e);
  }
  return l;
}
function each(e, t, i) {
  t = optimizeCb(t, i);
  var a, n;
  if (isArrayLike(e))
    for (a = 0, n = e.length; a < n; a++)
      t(e[a], a, e);
  else {
    var l = keys(e);
    for (a = 0, n = l.length; a < n; a++)
      t(e[l[a]], l[a], e);
  }
  return e;
}
function filter(e, t, i) {
  var a = [];
  return t = cb(t, i), each(e, function(n, l, r) {
    t(n, l, r) && a.push(n);
  }), a;
}
function chain(e) {
  var t = _(e);
  return t._chain = !0, t;
}
function functions(e) {
  var t = [];
  for (var i in e)
    isFunction$1(e[i]) && t.push(i);
  return t.sort();
}
function chainResult(e, t) {
  return e._chain ? _(t).chain() : t;
}
function mixin(e) {
  return each(functions(e), function(t) {
    var i = _[t] = e[t];
    _.prototype[t] = function() {
      var a = [this._wrapped];
      return push.apply(a, arguments), chainResult(this, i.apply(_, a));
    };
  }), _;
}
mixin({ chain, filter, each });
class Converter {
  fromBlueprint(t, i = null) {
    return map(t, (a, n) => this.splitRhs(n, a, i));
  }
  toBlueprint(t) {
    let i = {};
    return each(t, (a) => {
      i[a.field] = this.combineRhs(a);
    }), i;
  }
  splitRhs(t, i, a = null) {
    return {
      field: this.getScopedFieldHandle(t, a),
      operator: this.getOperatorFromRhs(i),
      value: this.getValueFromRhs(i)
    };
  }
  getScopedFieldHandle(t, i) {
    return t.startsWith("$root.") || t.startsWith("root.") || t.startsWith("$parent.") ? t : i ? i + t : t;
  }
  getOperatorFromRhs(t) {
    let i = "==";
    return chain(this.getOperatorsAndAliases()).filter((a) => new RegExp(`^${a} [^=]`).test(this.normalizeConditionString(t))).each((a) => i = a), this.normalizeOperator(i);
  }
  normalizeOperator(t) {
    return ALIASES[t] ? ALIASES[t] : t;
  }
  getValueFromRhs(t) {
    let i = this.normalizeConditionString(t);
    return chain(this.getOperatorsAndAliases()).filter((a) => new RegExp(`^${a} [^=]`).test(i)).each((a) => i = i.replace(new RegExp(`^${a}[ ]*`), "")), i;
  }
  combineRhs(t) {
    let i = t.operator ? t.operator.trim() : "", a = t.value.trim();
    return `${i} ${a}`.trim();
  }
  getOperatorsAndAliases() {
    return OPERATORS.concat(Object.keys(ALIASES));
  }
  normalizeConditionString(t) {
    return t === null ? "null" : t === "" ? "empty" : t.toString();
  }
}
class ParentResolver {
  constructor(t) {
    this.currentFieldPath = t;
  }
  resolve(t) {
    let i = this.getParentFieldPath(this.currentFieldPath, !0), a = this.removeOneParentKeyword(t);
    for (; a.startsWith("$parent."); )
      i = this.getParentFieldPath(i), a = this.removeOneParentKeyword(a);
    return `$root.${i ? `${i}.${a}` : a}`;
  }
  getParentFieldPath(t, i) {
    const a = new RegExp("(.*?[^\\.]+)(\\.[0-9]+)*\\.[^\\.]*$");
    return (i || this.isAtSetLevel(t)) && (t = t.replace(a, "$1")), t.includes(".") ? t.replace(a, "$1$2") : "";
  }
  isAtSetLevel(t) {
    return t.match(new RegExp("(\\.[0-9]+)$"));
  }
  removeOneParentKeyword(t) {
    return t.replace(new RegExp("^\\$parent."), "");
  }
}
function getDefaults() {
  return {
    async: !1,
    baseUrl: null,
    breaks: !1,
    extensions: null,
    gfm: !0,
    headerIds: !0,
    headerPrefix: "",
    highlight: null,
    hooks: null,
    langPrefix: "language-",
    mangle: !0,
    pedantic: !1,
    renderer: null,
    sanitize: !1,
    sanitizer: null,
    silent: !1,
    smartypants: !1,
    tokenizer: null,
    walkTokens: null,
    xhtml: !1
  };
}
let defaults = getDefaults();
function changeDefaults(e) {
  defaults = e;
}
const escapeTest = /[&<>"']/, escapeReplace = new RegExp(escapeTest.source, "g"), escapeTestNoEncode = /[<>"']|&(?!(#\d{1,7}|#[Xx][a-fA-F0-9]{1,6}|\w+);)/, escapeReplaceNoEncode = new RegExp(escapeTestNoEncode.source, "g"), escapeReplacements = {
  "&": "&amp;",
  "<": "&lt;",
  ">": "&gt;",
  '"': "&quot;",
  "'": "&#39;"
}, getEscapeReplacement = (e) => escapeReplacements[e];
function escape(e, t) {
  if (t) {
    if (escapeTest.test(e))
      return e.replace(escapeReplace, getEscapeReplacement);
  } else if (escapeTestNoEncode.test(e))
    return e.replace(escapeReplaceNoEncode, getEscapeReplacement);
  return e;
}
const unescapeTest = /&(#(?:\d+)|(?:#x[0-9A-Fa-f]+)|(?:\w+));?/ig;
function unescape$1(e) {
  return e.replace(unescapeTest, (t, i) => (i = i.toLowerCase(), i === "colon" ? ":" : i.charAt(0) === "#" ? i.charAt(1) === "x" ? String.fromCharCode(parseInt(i.substring(2), 16)) : String.fromCharCode(+i.substring(1)) : ""));
}
const caret = /(^|[^\[])\^/g;
function edit(e, t) {
  e = typeof e == "string" ? e : e.source, t = t || "";
  const i = {
    replace: (a, n) => (n = n.source || n, n = n.replace(caret, "$1"), e = e.replace(a, n), i),
    getRegex: () => new RegExp(e, t)
  };
  return i;
}
const nonWordAndColonTest = /[^\w:]/g, originIndependentUrl = /^$|^[a-z][a-z0-9+.-]*:|^[?#]/i;
function cleanUrl(e, t, i) {
  if (e) {
    let a;
    try {
      a = decodeURIComponent(unescape$1(i)).replace(nonWordAndColonTest, "").toLowerCase();
    } catch {
      return null;
    }
    if (a.indexOf("javascript:") === 0 || a.indexOf("vbscript:") === 0 || a.indexOf("data:") === 0)
      return null;
  }
  t && !originIndependentUrl.test(i) && (i = resolveUrl(t, i));
  try {
    i = encodeURI(i).replace(/%25/g, "%");
  } catch {
    return null;
  }
  return i;
}
const baseUrls = {}, justDomain = /^[^:]+:\/*[^/]*$/, protocol = /^([^:]+:)[\s\S]*$/, domain = /^([^:]+:\/*[^/]*)[\s\S]*$/;
function resolveUrl(e, t) {
  baseUrls[" " + e] || (justDomain.test(e) ? baseUrls[" " + e] = e + "/" : baseUrls[" " + e] = rtrim(e, "/", !0)), e = baseUrls[" " + e];
  const i = e.indexOf(":") === -1;
  return t.substring(0, 2) === "//" ? i ? t : e.replace(protocol, "$1") + t : t.charAt(0) === "/" ? i ? t : e.replace(domain, "$1") + t : e + t;
}
const noopTest = { exec: function() {
} };
function splitCells(e, t) {
  const i = e.replace(/\|/g, (l, r, s) => {
    let o = !1, d = r;
    for (; --d >= 0 && s[d] === "\\"; ) o = !o;
    return o ? "|" : " |";
  }), a = i.split(/ \|/);
  let n = 0;
  if (a[0].trim() || a.shift(), a.length > 0 && !a[a.length - 1].trim() && a.pop(), a.length > t)
    a.splice(t);
  else
    for (; a.length < t; ) a.push("");
  for (; n < a.length; n++)
    a[n] = a[n].trim().replace(/\\\|/g, "|");
  return a;
}
function rtrim(e, t, i) {
  const a = e.length;
  if (a === 0)
    return "";
  let n = 0;
  for (; n < a; ) {
    const l = e.charAt(a - n - 1);
    if (l === t && !i)
      n++;
    else if (l !== t && i)
      n++;
    else
      break;
  }
  return e.slice(0, a - n);
}
function findClosingBracket(e, t) {
  if (e.indexOf(t[1]) === -1)
    return -1;
  const i = e.length;
  let a = 0, n = 0;
  for (; n < i; n++)
    if (e[n] === "\\")
      n++;
    else if (e[n] === t[0])
      a++;
    else if (e[n] === t[1] && (a--, a < 0))
      return n;
  return -1;
}
function checkSanitizeDeprecation(e) {
  e && e.sanitize && !e.silent && console.warn("marked(): sanitize and sanitizer parameters are deprecated since version 0.7.0, should not be used and will be removed in the future. Read more here: https://marked.js.org/#/USING_ADVANCED.md#options");
}
function repeatString(e, t) {
  if (t < 1)
    return "";
  let i = "";
  for (; t > 1; )
    t & 1 && (i += e), t >>= 1, e += e;
  return i + e;
}
function outputLink(e, t, i, a) {
  const n = t.href, l = t.title ? escape(t.title) : null, r = e[1].replace(/\\([\[\]])/g, "$1");
  if (e[0].charAt(0) !== "!") {
    a.state.inLink = !0;
    const s = {
      type: "link",
      raw: i,
      href: n,
      title: l,
      text: r,
      tokens: a.inlineTokens(r)
    };
    return a.state.inLink = !1, s;
  }
  return {
    type: "image",
    raw: i,
    href: n,
    title: l,
    text: escape(r)
  };
}
function indentCodeCompensation(e, t) {
  const i = e.match(/^(\s+)(?:```)/);
  if (i === null)
    return t;
  const a = i[1];
  return t.split(`
`).map((n) => {
    const l = n.match(/^\s+/);
    if (l === null)
      return n;
    const [r] = l;
    return r.length >= a.length ? n.slice(a.length) : n;
  }).join(`
`);
}
class Tokenizer {
  constructor(t) {
    this.options = t || defaults;
  }
  space(t) {
    const i = this.rules.block.newline.exec(t);
    if (i && i[0].length > 0)
      return {
        type: "space",
        raw: i[0]
      };
  }
  code(t) {
    const i = this.rules.block.code.exec(t);
    if (i) {
      const a = i[0].replace(/^ {1,4}/gm, "");
      return {
        type: "code",
        raw: i[0],
        codeBlockStyle: "indented",
        text: this.options.pedantic ? a : rtrim(a, `
`)
      };
    }
  }
  fences(t) {
    const i = this.rules.block.fences.exec(t);
    if (i) {
      const a = i[0], n = indentCodeCompensation(a, i[3] || "");
      return {
        type: "code",
        raw: a,
        lang: i[2] ? i[2].trim().replace(this.rules.inline._escapes, "$1") : i[2],
        text: n
      };
    }
  }
  heading(t) {
    const i = this.rules.block.heading.exec(t);
    if (i) {
      let a = i[2].trim();
      if (/#$/.test(a)) {
        const n = rtrim(a, "#");
        (this.options.pedantic || !n || / $/.test(n)) && (a = n.trim());
      }
      return {
        type: "heading",
        raw: i[0],
        depth: i[1].length,
        text: a,
        tokens: this.lexer.inline(a)
      };
    }
  }
  hr(t) {
    const i = this.rules.block.hr.exec(t);
    if (i)
      return {
        type: "hr",
        raw: i[0]
      };
  }
  blockquote(t) {
    const i = this.rules.block.blockquote.exec(t);
    if (i) {
      const a = i[0].replace(/^ *>[ \t]?/gm, ""), n = this.lexer.state.top;
      this.lexer.state.top = !0;
      const l = this.lexer.blockTokens(a);
      return this.lexer.state.top = n, {
        type: "blockquote",
        raw: i[0],
        tokens: l,
        text: a
      };
    }
  }
  list(t) {
    let i = this.rules.block.list.exec(t);
    if (i) {
      let a, n, l, r, s, o, d, c, u, f, p, m, h = i[1].trim();
      const I = h.length > 1, b = {
        type: "list",
        raw: "",
        ordered: I,
        start: I ? +h.slice(0, -1) : "",
        loose: !1,
        items: []
      };
      h = I ? `\\d{1,9}\\${h.slice(-1)}` : `\\${h}`, this.options.pedantic && (h = I ? h : "[*+-]");
      const g = new RegExp(`^( {0,3}${h})((?:[	 ][^\\n]*)?(?:\\n|$))`);
      for (; t && (m = !1, !(!(i = g.exec(t)) || this.rules.block.hr.test(t))); ) {
        if (a = i[0], t = t.substring(a.length), c = i[2].split(`
`, 1)[0].replace(/^\t+/, (T) => " ".repeat(3 * T.length)), u = t.split(`
`, 1)[0], this.options.pedantic ? (r = 2, p = c.trimLeft()) : (r = i[2].search(/[^ ]/), r = r > 4 ? 1 : r, p = c.slice(r), r += i[1].length), o = !1, !c && /^ *$/.test(u) && (a += u + `
`, t = t.substring(u.length + 1), m = !0), !m) {
          const T = new RegExp(`^ {0,${Math.min(3, r - 1)}}(?:[*+-]|\\d{1,9}[.)])((?:[ 	][^\\n]*)?(?:\\n|$))`), S = new RegExp(`^ {0,${Math.min(3, r - 1)}}((?:- *){3,}|(?:_ *){3,}|(?:\\* *){3,})(?:\\n+|$)`), L = new RegExp(`^ {0,${Math.min(3, r - 1)}}(?:\`\`\`|~~~)`), M = new RegExp(`^ {0,${Math.min(3, r - 1)}}#`);
          for (; t && (f = t.split(`
`, 1)[0], u = f, this.options.pedantic && (u = u.replace(/^ {1,4}(?=( {4})*[^ ])/g, "  ")), !(L.test(u) || M.test(u) || T.test(u) || S.test(t))); ) {
            if (u.search(/[^ ]/) >= r || !u.trim())
              p += `
` + u.slice(r);
            else {
              if (o || c.search(/[^ ]/) >= 4 || L.test(c) || M.test(c) || S.test(c))
                break;
              p += `
` + u;
            }
            !o && !u.trim() && (o = !0), a += f + `
`, t = t.substring(f.length + 1), c = u.slice(r);
          }
        }
        b.loose || (d ? b.loose = !0 : /\n *\n *$/.test(a) && (d = !0)), this.options.gfm && (n = /^\[[ xX]\] /.exec(p), n && (l = n[0] !== "[ ] ", p = p.replace(/^\[[ xX]\] +/, ""))), b.items.push({
          type: "list_item",
          raw: a,
          task: !!n,
          checked: l,
          loose: !1,
          text: p
        }), b.raw += a;
      }
      b.items[b.items.length - 1].raw = a.trimRight(), b.items[b.items.length - 1].text = p.trimRight(), b.raw = b.raw.trimRight();
      const E = b.items.length;
      for (s = 0; s < E; s++)
        if (this.lexer.state.top = !1, b.items[s].tokens = this.lexer.blockTokens(b.items[s].text, []), !b.loose) {
          const T = b.items[s].tokens.filter((L) => L.type === "space"), S = T.length > 0 && T.some((L) => /\n.*\n/.test(L.raw));
          b.loose = S;
        }
      if (b.loose)
        for (s = 0; s < E; s++)
          b.items[s].loose = !0;
      return b;
    }
  }
  html(t) {
    const i = this.rules.block.html.exec(t);
    if (i) {
      const a = {
        type: "html",
        raw: i[0],
        pre: !this.options.sanitizer && (i[1] === "pre" || i[1] === "script" || i[1] === "style"),
        text: i[0]
      };
      if (this.options.sanitize) {
        const n = this.options.sanitizer ? this.options.sanitizer(i[0]) : escape(i[0]);
        a.type = "paragraph", a.text = n, a.tokens = this.lexer.inline(n);
      }
      return a;
    }
  }
  def(t) {
    const i = this.rules.block.def.exec(t);
    if (i) {
      const a = i[1].toLowerCase().replace(/\s+/g, " "), n = i[2] ? i[2].replace(/^<(.*)>$/, "$1").replace(this.rules.inline._escapes, "$1") : "", l = i[3] ? i[3].substring(1, i[3].length - 1).replace(this.rules.inline._escapes, "$1") : i[3];
      return {
        type: "def",
        tag: a,
        raw: i[0],
        href: n,
        title: l
      };
    }
  }
  table(t) {
    const i = this.rules.block.table.exec(t);
    if (i) {
      const a = {
        type: "table",
        header: splitCells(i[1]).map((n) => ({ text: n })),
        align: i[2].replace(/^ *|\| *$/g, "").split(/ *\| */),
        rows: i[3] && i[3].trim() ? i[3].replace(/\n[ \t]*$/, "").split(`
`) : []
      };
      if (a.header.length === a.align.length) {
        a.raw = i[0];
        let n = a.align.length, l, r, s, o;
        for (l = 0; l < n; l++)
          /^ *-+: *$/.test(a.align[l]) ? a.align[l] = "right" : /^ *:-+: *$/.test(a.align[l]) ? a.align[l] = "center" : /^ *:-+ *$/.test(a.align[l]) ? a.align[l] = "left" : a.align[l] = null;
        for (n = a.rows.length, l = 0; l < n; l++)
          a.rows[l] = splitCells(a.rows[l], a.header.length).map((d) => ({ text: d }));
        for (n = a.header.length, r = 0; r < n; r++)
          a.header[r].tokens = this.lexer.inline(a.header[r].text);
        for (n = a.rows.length, r = 0; r < n; r++)
          for (o = a.rows[r], s = 0; s < o.length; s++)
            o[s].tokens = this.lexer.inline(o[s].text);
        return a;
      }
    }
  }
  lheading(t) {
    const i = this.rules.block.lheading.exec(t);
    if (i)
      return {
        type: "heading",
        raw: i[0],
        depth: i[2].charAt(0) === "=" ? 1 : 2,
        text: i[1],
        tokens: this.lexer.inline(i[1])
      };
  }
  paragraph(t) {
    const i = this.rules.block.paragraph.exec(t);
    if (i) {
      const a = i[1].charAt(i[1].length - 1) === `
` ? i[1].slice(0, -1) : i[1];
      return {
        type: "paragraph",
        raw: i[0],
        text: a,
        tokens: this.lexer.inline(a)
      };
    }
  }
  text(t) {
    const i = this.rules.block.text.exec(t);
    if (i)
      return {
        type: "text",
        raw: i[0],
        text: i[0],
        tokens: this.lexer.inline(i[0])
      };
  }
  escape(t) {
    const i = this.rules.inline.escape.exec(t);
    if (i)
      return {
        type: "escape",
        raw: i[0],
        text: escape(i[1])
      };
  }
  tag(t) {
    const i = this.rules.inline.tag.exec(t);
    if (i)
      return !this.lexer.state.inLink && /^<a /i.test(i[0]) ? this.lexer.state.inLink = !0 : this.lexer.state.inLink && /^<\/a>/i.test(i[0]) && (this.lexer.state.inLink = !1), !this.lexer.state.inRawBlock && /^<(pre|code|kbd|script)(\s|>)/i.test(i[0]) ? this.lexer.state.inRawBlock = !0 : this.lexer.state.inRawBlock && /^<\/(pre|code|kbd|script)(\s|>)/i.test(i[0]) && (this.lexer.state.inRawBlock = !1), {
        type: this.options.sanitize ? "text" : "html",
        raw: i[0],
        inLink: this.lexer.state.inLink,
        inRawBlock: this.lexer.state.inRawBlock,
        text: this.options.sanitize ? this.options.sanitizer ? this.options.sanitizer(i[0]) : escape(i[0]) : i[0]
      };
  }
  link(t) {
    const i = this.rules.inline.link.exec(t);
    if (i) {
      const a = i[2].trim();
      if (!this.options.pedantic && /^</.test(a)) {
        if (!/>$/.test(a))
          return;
        const r = rtrim(a.slice(0, -1), "\\");
        if ((a.length - r.length) % 2 === 0)
          return;
      } else {
        const r = findClosingBracket(i[2], "()");
        if (r > -1) {
          const o = (i[0].indexOf("!") === 0 ? 5 : 4) + i[1].length + r;
          i[2] = i[2].substring(0, r), i[0] = i[0].substring(0, o).trim(), i[3] = "";
        }
      }
      let n = i[2], l = "";
      if (this.options.pedantic) {
        const r = /^([^'"]*[^\s])\s+(['"])(.*)\2/.exec(n);
        r && (n = r[1], l = r[3]);
      } else
        l = i[3] ? i[3].slice(1, -1) : "";
      return n = n.trim(), /^</.test(n) && (this.options.pedantic && !/>$/.test(a) ? n = n.slice(1) : n = n.slice(1, -1)), outputLink(i, {
        href: n && n.replace(this.rules.inline._escapes, "$1"),
        title: l && l.replace(this.rules.inline._escapes, "$1")
      }, i[0], this.lexer);
    }
  }
  reflink(t, i) {
    let a;
    if ((a = this.rules.inline.reflink.exec(t)) || (a = this.rules.inline.nolink.exec(t))) {
      let n = (a[2] || a[1]).replace(/\s+/g, " ");
      if (n = i[n.toLowerCase()], !n) {
        const l = a[0].charAt(0);
        return {
          type: "text",
          raw: l,
          text: l
        };
      }
      return outputLink(a, n, a[0], this.lexer);
    }
  }
  emStrong(t, i, a = "") {
    let n = this.rules.inline.emStrong.lDelim.exec(t);
    if (!n || n[3] && a.match(/[\p{L}\p{N}]/u)) return;
    const l = n[1] || n[2] || "";
    if (!l || l && (a === "" || this.rules.inline.punctuation.exec(a))) {
      const r = n[0].length - 1;
      let s, o, d = r, c = 0;
      const u = n[0][0] === "*" ? this.rules.inline.emStrong.rDelimAst : this.rules.inline.emStrong.rDelimUnd;
      for (u.lastIndex = 0, i = i.slice(-1 * t.length + r); (n = u.exec(i)) != null; ) {
        if (s = n[1] || n[2] || n[3] || n[4] || n[5] || n[6], !s) continue;
        if (o = s.length, n[3] || n[4]) {
          d += o;
          continue;
        } else if ((n[5] || n[6]) && r % 3 && !((r + o) % 3)) {
          c += o;
          continue;
        }
        if (d -= o, d > 0) continue;
        o = Math.min(o, o + d + c);
        const f = t.slice(0, r + n.index + (n[0].length - s.length) + o);
        if (Math.min(r, o) % 2) {
          const m = f.slice(1, -1);
          return {
            type: "em",
            raw: f,
            text: m,
            tokens: this.lexer.inlineTokens(m)
          };
        }
        const p = f.slice(2, -2);
        return {
          type: "strong",
          raw: f,
          text: p,
          tokens: this.lexer.inlineTokens(p)
        };
      }
    }
  }
  codespan(t) {
    const i = this.rules.inline.code.exec(t);
    if (i) {
      let a = i[2].replace(/\n/g, " ");
      const n = /[^ ]/.test(a), l = /^ /.test(a) && / $/.test(a);
      return n && l && (a = a.substring(1, a.length - 1)), a = escape(a, !0), {
        type: "codespan",
        raw: i[0],
        text: a
      };
    }
  }
  br(t) {
    const i = this.rules.inline.br.exec(t);
    if (i)
      return {
        type: "br",
        raw: i[0]
      };
  }
  del(t) {
    const i = this.rules.inline.del.exec(t);
    if (i)
      return {
        type: "del",
        raw: i[0],
        text: i[2],
        tokens: this.lexer.inlineTokens(i[2])
      };
  }
  autolink(t, i) {
    const a = this.rules.inline.autolink.exec(t);
    if (a) {
      let n, l;
      return a[2] === "@" ? (n = escape(this.options.mangle ? i(a[1]) : a[1]), l = "mailto:" + n) : (n = escape(a[1]), l = n), {
        type: "link",
        raw: a[0],
        text: n,
        href: l,
        tokens: [
          {
            type: "text",
            raw: n,
            text: n
          }
        ]
      };
    }
  }
  url(t, i) {
    let a;
    if (a = this.rules.inline.url.exec(t)) {
      let n, l;
      if (a[2] === "@")
        n = escape(this.options.mangle ? i(a[0]) : a[0]), l = "mailto:" + n;
      else {
        let r;
        do
          r = a[0], a[0] = this.rules.inline._backpedal.exec(a[0])[0];
        while (r !== a[0]);
        n = escape(a[0]), a[1] === "www." ? l = "http://" + a[0] : l = a[0];
      }
      return {
        type: "link",
        raw: a[0],
        text: n,
        href: l,
        tokens: [
          {
            type: "text",
            raw: n,
            text: n
          }
        ]
      };
    }
  }
  inlineText(t, i) {
    const a = this.rules.inline.text.exec(t);
    if (a) {
      let n;
      return this.lexer.state.inRawBlock ? n = this.options.sanitize ? this.options.sanitizer ? this.options.sanitizer(a[0]) : escape(a[0]) : a[0] : n = escape(this.options.smartypants ? i(a[0]) : a[0]), {
        type: "text",
        raw: a[0],
        text: n
      };
    }
  }
}
const block = {
  newline: /^(?: *(?:\n|$))+/,
  code: /^( {4}[^\n]+(?:\n(?: *(?:\n|$))*)?)+/,
  fences: /^ {0,3}(`{3,}(?=[^`\n]*(?:\n|$))|~{3,})([^\n]*)(?:\n|$)(?:|([\s\S]*?)(?:\n|$))(?: {0,3}\1[~`]* *(?=\n|$)|$)/,
  hr: /^ {0,3}((?:-[\t ]*){3,}|(?:_[ \t]*){3,}|(?:\*[ \t]*){3,})(?:\n+|$)/,
  heading: /^ {0,3}(#{1,6})(?=\s|$)(.*)(?:\n+|$)/,
  blockquote: /^( {0,3}> ?(paragraph|[^\n]*)(?:\n|$))+/,
  list: /^( {0,3}bull)([ \t][^\n]+?)?(?:\n|$)/,
  html: "^ {0,3}(?:<(script|pre|style|textarea)[\\s>][\\s\\S]*?(?:</\\1>[^\\n]*\\n+|$)|comment[^\\n]*(\\n+|$)|<\\?[\\s\\S]*?(?:\\?>\\n*|$)|<![A-Z][\\s\\S]*?(?:>\\n*|$)|<!\\[CDATA\\[[\\s\\S]*?(?:\\]\\]>\\n*|$)|</?(tag)(?: +|\\n|/?>)[\\s\\S]*?(?:(?:\\n *)+\\n|$)|<(?!script|pre|style|textarea)([a-z][\\w-]*)(?:attribute)*? */?>(?=[ \\t]*(?:\\n|$))[\\s\\S]*?(?:(?:\\n *)+\\n|$)|</(?!script|pre|style|textarea)[a-z][\\w-]*\\s*>(?=[ \\t]*(?:\\n|$))[\\s\\S]*?(?:(?:\\n *)+\\n|$))",
  def: /^ {0,3}\[(label)\]: *(?:\n *)?([^<\s][^\s]*|<.*?>)(?:(?: +(?:\n *)?| *\n *)(title))? *(?:\n+|$)/,
  table: noopTest,
  lheading: /^((?:.|\n(?!\n))+?)\n {0,3}(=+|-+) *(?:\n+|$)/,
  // regex template, placeholders will be replaced according to different paragraph
  // interruption rules of commonmark and the original markdown spec:
  _paragraph: /^([^\n]+(?:\n(?!hr|heading|lheading|blockquote|fences|list|html|table| +\n)[^\n]+)*)/,
  text: /^[^\n]+/
};
block._label = /(?!\s*\])(?:\\.|[^\[\]\\])+/;
block._title = /(?:"(?:\\"?|[^"\\])*"|'[^'\n]*(?:\n[^'\n]+)*\n?'|\([^()]*\))/;
block.def = edit(block.def).replace("label", block._label).replace("title", block._title).getRegex();
block.bullet = /(?:[*+-]|\d{1,9}[.)])/;
block.listItemStart = edit(/^( *)(bull) */).replace("bull", block.bullet).getRegex();
block.list = edit(block.list).replace(/bull/g, block.bullet).replace("hr", "\\n+(?=\\1?(?:(?:- *){3,}|(?:_ *){3,}|(?:\\* *){3,})(?:\\n+|$))").replace("def", "\\n+(?=" + block.def.source + ")").getRegex();
block._tag = "address|article|aside|base|basefont|blockquote|body|caption|center|col|colgroup|dd|details|dialog|dir|div|dl|dt|fieldset|figcaption|figure|footer|form|frame|frameset|h[1-6]|head|header|hr|html|iframe|legend|li|link|main|menu|menuitem|meta|nav|noframes|ol|optgroup|option|p|param|section|source|summary|table|tbody|td|tfoot|th|thead|title|tr|track|ul";
block._comment = /<!--(?!-?>)[\s\S]*?(?:-->|$)/;
block.html = edit(block.html, "i").replace("comment", block._comment).replace("tag", block._tag).replace("attribute", / +[a-zA-Z:_][\w.:-]*(?: *= *"[^"\n]*"| *= *'[^'\n]*'| *= *[^\s"'=<>`]+)?/).getRegex();
block.paragraph = edit(block._paragraph).replace("hr", block.hr).replace("heading", " {0,3}#{1,6} ").replace("|lheading", "").replace("|table", "").replace("blockquote", " {0,3}>").replace("fences", " {0,3}(?:`{3,}(?=[^`\\n]*\\n)|~{3,})[^\\n]*\\n").replace("list", " {0,3}(?:[*+-]|1[.)]) ").replace("html", "</?(?:tag)(?: +|\\n|/?>)|<(?:script|pre|style|textarea|!--)").replace("tag", block._tag).getRegex();
block.blockquote = edit(block.blockquote).replace("paragraph", block.paragraph).getRegex();
block.normal = { ...block };
block.gfm = {
  ...block.normal,
  table: "^ *([^\\n ].*\\|.*)\\n {0,3}(?:\\| *)?(:?-+:? *(?:\\| *:?-+:? *)*)(?:\\| *)?(?:\\n((?:(?! *\\n|hr|heading|blockquote|code|fences|list|html).*(?:\\n|$))*)\\n*|$)"
  // Cells
};
block.gfm.table = edit(block.gfm.table).replace("hr", block.hr).replace("heading", " {0,3}#{1,6} ").replace("blockquote", " {0,3}>").replace("code", " {4}[^\\n]").replace("fences", " {0,3}(?:`{3,}(?=[^`\\n]*\\n)|~{3,})[^\\n]*\\n").replace("list", " {0,3}(?:[*+-]|1[.)]) ").replace("html", "</?(?:tag)(?: +|\\n|/?>)|<(?:script|pre|style|textarea|!--)").replace("tag", block._tag).getRegex();
block.gfm.paragraph = edit(block._paragraph).replace("hr", block.hr).replace("heading", " {0,3}#{1,6} ").replace("|lheading", "").replace("table", block.gfm.table).replace("blockquote", " {0,3}>").replace("fences", " {0,3}(?:`{3,}(?=[^`\\n]*\\n)|~{3,})[^\\n]*\\n").replace("list", " {0,3}(?:[*+-]|1[.)]) ").replace("html", "</?(?:tag)(?: +|\\n|/?>)|<(?:script|pre|style|textarea|!--)").replace("tag", block._tag).getRegex();
block.pedantic = {
  ...block.normal,
  html: edit(
    `^ *(?:comment *(?:\\n|\\s*$)|<(tag)[\\s\\S]+?</\\1> *(?:\\n{2,}|\\s*$)|<tag(?:"[^"]*"|'[^']*'|\\s[^'"/>\\s]*)*?/?> *(?:\\n{2,}|\\s*$))`
  ).replace("comment", block._comment).replace(/tag/g, "(?!(?:a|em|strong|small|s|cite|q|dfn|abbr|data|time|code|var|samp|kbd|sub|sup|i|b|u|mark|ruby|rt|rp|bdi|bdo|span|br|wbr|ins|del|img)\\b)\\w+(?!:|[^\\w\\s@]*@)\\b").getRegex(),
  def: /^ *\[([^\]]+)\]: *<?([^\s>]+)>?(?: +(["(][^\n]+[")]))? *(?:\n+|$)/,
  heading: /^(#{1,6})(.*)(?:\n+|$)/,
  fences: noopTest,
  // fences not supported
  lheading: /^(.+?)\n {0,3}(=+|-+) *(?:\n+|$)/,
  paragraph: edit(block.normal._paragraph).replace("hr", block.hr).replace("heading", ` *#{1,6} *[^
]`).replace("lheading", block.lheading).replace("blockquote", " {0,3}>").replace("|fences", "").replace("|list", "").replace("|html", "").getRegex()
};
const inline = {
  escape: /^\\([!"#$%&'()*+,\-./:;<=>?@\[\]\\^_`{|}~])/,
  autolink: /^<(scheme:[^\s\x00-\x1f<>]*|email)>/,
  url: noopTest,
  tag: "^comment|^</[a-zA-Z][\\w:-]*\\s*>|^<[a-zA-Z][\\w-]*(?:attribute)*?\\s*/?>|^<\\?[\\s\\S]*?\\?>|^<![a-zA-Z]+\\s[\\s\\S]*?>|^<!\\[CDATA\\[[\\s\\S]*?\\]\\]>",
  // CDATA section
  link: /^!?\[(label)\]\(\s*(href)(?:\s+(title))?\s*\)/,
  reflink: /^!?\[(label)\]\[(ref)\]/,
  nolink: /^!?\[(ref)\](?:\[\])?/,
  reflinkSearch: "reflink|nolink(?!\\()",
  emStrong: {
    lDelim: /^(?:\*+(?:([punct_])|[^\s*]))|^_+(?:([punct*])|([^\s_]))/,
    //        (1) and (2) can only be a Right Delimiter. (3) and (4) can only be Left.  (5) and (6) can be either Left or Right.
    //          () Skip orphan inside strong                                      () Consume to delim     (1) #***                (2) a***#, a***                             (3) #***a, ***a                 (4) ***#              (5) #***#                 (6) a***a
    rDelimAst: /^(?:[^_*\\]|\\.)*?\_\_(?:[^_*\\]|\\.)*?\*(?:[^_*\\]|\\.)*?(?=\_\_)|(?:[^*\\]|\\.)+(?=[^*])|[punct_](\*+)(?=[\s]|$)|(?:[^punct*_\s\\]|\\.)(\*+)(?=[punct_\s]|$)|[punct_\s](\*+)(?=[^punct*_\s])|[\s](\*+)(?=[punct_])|[punct_](\*+)(?=[punct_])|(?:[^punct*_\s\\]|\\.)(\*+)(?=[^punct*_\s])/,
    rDelimUnd: /^(?:[^_*\\]|\\.)*?\*\*(?:[^_*\\]|\\.)*?\_(?:[^_*\\]|\\.)*?(?=\*\*)|(?:[^_\\]|\\.)+(?=[^_])|[punct*](\_+)(?=[\s]|$)|(?:[^punct*_\s\\]|\\.)(\_+)(?=[punct*\s]|$)|[punct*\s](\_+)(?=[^punct*_\s])|[\s](\_+)(?=[punct*])|[punct*](\_+)(?=[punct*])/
    // ^- Not allowed for _
  },
  code: /^(`+)([^`]|[^`][\s\S]*?[^`])\1(?!`)/,
  br: /^( {2,}|\\)\n(?!\s*$)/,
  del: noopTest,
  text: /^(`+|[^`])(?:(?= {2,}\n)|[\s\S]*?(?:(?=[\\<!\[`*_]|\b_|$)|[^ ](?= {2,}\n)))/,
  punctuation: /^([\spunctuation])/
};
inline._punctuation = "!\"#$%&'()+\\-.,/:;<=>?@\\[\\]`^{|}~";
inline.punctuation = edit(inline.punctuation).replace(/punctuation/g, inline._punctuation).getRegex();
inline.blockSkip = /\[[^\]]*?\]\([^\)]*?\)|`[^`]*?`|<[^>]*?>/g;
inline.escapedEmSt = /(?:^|[^\\])(?:\\\\)*\\[*_]/g;
inline._comment = edit(block._comment).replace("(?:-->|$)", "-->").getRegex();
inline.emStrong.lDelim = edit(inline.emStrong.lDelim).replace(/punct/g, inline._punctuation).getRegex();
inline.emStrong.rDelimAst = edit(inline.emStrong.rDelimAst, "g").replace(/punct/g, inline._punctuation).getRegex();
inline.emStrong.rDelimUnd = edit(inline.emStrong.rDelimUnd, "g").replace(/punct/g, inline._punctuation).getRegex();
inline._escapes = /\\([!"#$%&'()*+,\-./:;<=>?@\[\]\\^_`{|}~])/g;
inline._scheme = /[a-zA-Z][a-zA-Z0-9+.-]{1,31}/;
inline._email = /[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+(@)[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+(?![-_])/;
inline.autolink = edit(inline.autolink).replace("scheme", inline._scheme).replace("email", inline._email).getRegex();
inline._attribute = /\s+[a-zA-Z:_][\w.:-]*(?:\s*=\s*"[^"]*"|\s*=\s*'[^']*'|\s*=\s*[^\s"'=<>`]+)?/;
inline.tag = edit(inline.tag).replace("comment", inline._comment).replace("attribute", inline._attribute).getRegex();
inline._label = /(?:\[(?:\\.|[^\[\]\\])*\]|\\.|`[^`]*`|[^\[\]\\`])*?/;
inline._href = /<(?:\\.|[^\n<>\\])+>|[^\s\x00-\x1f]*/;
inline._title = /"(?:\\"?|[^"\\])*"|'(?:\\'?|[^'\\])*'|\((?:\\\)?|[^)\\])*\)/;
inline.link = edit(inline.link).replace("label", inline._label).replace("href", inline._href).replace("title", inline._title).getRegex();
inline.reflink = edit(inline.reflink).replace("label", inline._label).replace("ref", block._label).getRegex();
inline.nolink = edit(inline.nolink).replace("ref", block._label).getRegex();
inline.reflinkSearch = edit(inline.reflinkSearch, "g").replace("reflink", inline.reflink).replace("nolink", inline.nolink).getRegex();
inline.normal = { ...inline };
inline.pedantic = {
  ...inline.normal,
  strong: {
    start: /^__|\*\*/,
    middle: /^__(?=\S)([\s\S]*?\S)__(?!_)|^\*\*(?=\S)([\s\S]*?\S)\*\*(?!\*)/,
    endAst: /\*\*(?!\*)/g,
    endUnd: /__(?!_)/g
  },
  em: {
    start: /^_|\*/,
    middle: /^()\*(?=\S)([\s\S]*?\S)\*(?!\*)|^_(?=\S)([\s\S]*?\S)_(?!_)/,
    endAst: /\*(?!\*)/g,
    endUnd: /_(?!_)/g
  },
  link: edit(/^!?\[(label)\]\((.*?)\)/).replace("label", inline._label).getRegex(),
  reflink: edit(/^!?\[(label)\]\s*\[([^\]]*)\]/).replace("label", inline._label).getRegex()
};
inline.gfm = {
  ...inline.normal,
  escape: edit(inline.escape).replace("])", "~|])").getRegex(),
  _extended_email: /[A-Za-z0-9._+-]+(@)[a-zA-Z0-9-_]+(?:\.[a-zA-Z0-9-_]*[a-zA-Z0-9])+(?![-_])/,
  url: /^((?:ftp|https?):\/\/|www\.)(?:[a-zA-Z0-9\-]+\.?)+[^\s<]*|^email/,
  _backpedal: /(?:[^?!.,:;*_'"~()&]+|\([^)]*\)|&(?![a-zA-Z0-9]+;$)|[?!.,:;*_'"~)]+(?!$))+/,
  del: /^(~~?)(?=[^\s~])([\s\S]*?[^\s~])\1(?=[^~]|$)/,
  text: /^([`~]+|[^`~])(?:(?= {2,}\n)|(?=[a-zA-Z0-9.!#$%&'*+\/=?_`{\|}~-]+@)|[\s\S]*?(?:(?=[\\<!\[`*~_]|\b_|https?:\/\/|ftp:\/\/|www\.|$)|[^ ](?= {2,}\n)|[^a-zA-Z0-9.!#$%&'*+\/=?_`{\|}~-](?=[a-zA-Z0-9.!#$%&'*+\/=?_`{\|}~-]+@)))/
};
inline.gfm.url = edit(inline.gfm.url, "i").replace("email", inline.gfm._extended_email).getRegex();
inline.breaks = {
  ...inline.gfm,
  br: edit(inline.br).replace("{2,}", "*").getRegex(),
  text: edit(inline.gfm.text).replace("\\b_", "\\b_| {2,}\\n").replace(/\{2,\}/g, "*").getRegex()
};
function smartypants(e) {
  return e.replace(/---/g, "—").replace(/--/g, "–").replace(/(^|[-\u2014/(\[{"\s])'/g, "$1‘").replace(/'/g, "’").replace(/(^|[-\u2014/(\[{\u2018\s])"/g, "$1“").replace(/"/g, "”").replace(/\.{3}/g, "…");
}
function mangle(e) {
  let t = "", i, a;
  const n = e.length;
  for (i = 0; i < n; i++)
    a = e.charCodeAt(i), Math.random() > 0.5 && (a = "x" + a.toString(16)), t += "&#" + a + ";";
  return t;
}
class Lexer {
  constructor(t) {
    this.tokens = [], this.tokens.links = /* @__PURE__ */ Object.create(null), this.options = t || defaults, this.options.tokenizer = this.options.tokenizer || new Tokenizer(), this.tokenizer = this.options.tokenizer, this.tokenizer.options = this.options, this.tokenizer.lexer = this, this.inlineQueue = [], this.state = {
      inLink: !1,
      inRawBlock: !1,
      top: !0
    };
    const i = {
      block: block.normal,
      inline: inline.normal
    };
    this.options.pedantic ? (i.block = block.pedantic, i.inline = inline.pedantic) : this.options.gfm && (i.block = block.gfm, this.options.breaks ? i.inline = inline.breaks : i.inline = inline.gfm), this.tokenizer.rules = i;
  }
  /**
   * Expose Rules
   */
  static get rules() {
    return {
      block,
      inline
    };
  }
  /**
   * Static Lex Method
   */
  static lex(t, i) {
    return new Lexer(i).lex(t);
  }
  /**
   * Static Lex Inline Method
   */
  static lexInline(t, i) {
    return new Lexer(i).inlineTokens(t);
  }
  /**
   * Preprocessing
   */
  lex(t) {
    t = t.replace(/\r\n|\r/g, `
`), this.blockTokens(t, this.tokens);
    let i;
    for (; i = this.inlineQueue.shift(); )
      this.inlineTokens(i.src, i.tokens);
    return this.tokens;
  }
  /**
   * Lexing
   */
  blockTokens(t, i = []) {
    this.options.pedantic ? t = t.replace(/\t/g, "    ").replace(/^ +$/gm, "") : t = t.replace(/^( *)(\t+)/gm, (s, o, d) => o + "    ".repeat(d.length));
    let a, n, l, r;
    for (; t; )
      if (!(this.options.extensions && this.options.extensions.block && this.options.extensions.block.some((s) => (a = s.call({ lexer: this }, t, i)) ? (t = t.substring(a.raw.length), i.push(a), !0) : !1))) {
        if (a = this.tokenizer.space(t)) {
          t = t.substring(a.raw.length), a.raw.length === 1 && i.length > 0 ? i[i.length - 1].raw += `
` : i.push(a);
          continue;
        }
        if (a = this.tokenizer.code(t)) {
          t = t.substring(a.raw.length), n = i[i.length - 1], n && (n.type === "paragraph" || n.type === "text") ? (n.raw += `
` + a.raw, n.text += `
` + a.text, this.inlineQueue[this.inlineQueue.length - 1].src = n.text) : i.push(a);
          continue;
        }
        if (a = this.tokenizer.fences(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.heading(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.hr(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.blockquote(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.list(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.html(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.def(t)) {
          t = t.substring(a.raw.length), n = i[i.length - 1], n && (n.type === "paragraph" || n.type === "text") ? (n.raw += `
` + a.raw, n.text += `
` + a.raw, this.inlineQueue[this.inlineQueue.length - 1].src = n.text) : this.tokens.links[a.tag] || (this.tokens.links[a.tag] = {
            href: a.href,
            title: a.title
          });
          continue;
        }
        if (a = this.tokenizer.table(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.lheading(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (l = t, this.options.extensions && this.options.extensions.startBlock) {
          let s = 1 / 0;
          const o = t.slice(1);
          let d;
          this.options.extensions.startBlock.forEach(function(c) {
            d = c.call({ lexer: this }, o), typeof d == "number" && d >= 0 && (s = Math.min(s, d));
          }), s < 1 / 0 && s >= 0 && (l = t.substring(0, s + 1));
        }
        if (this.state.top && (a = this.tokenizer.paragraph(l))) {
          n = i[i.length - 1], r && n.type === "paragraph" ? (n.raw += `
` + a.raw, n.text += `
` + a.text, this.inlineQueue.pop(), this.inlineQueue[this.inlineQueue.length - 1].src = n.text) : i.push(a), r = l.length !== t.length, t = t.substring(a.raw.length);
          continue;
        }
        if (a = this.tokenizer.text(t)) {
          t = t.substring(a.raw.length), n = i[i.length - 1], n && n.type === "text" ? (n.raw += `
` + a.raw, n.text += `
` + a.text, this.inlineQueue.pop(), this.inlineQueue[this.inlineQueue.length - 1].src = n.text) : i.push(a);
          continue;
        }
        if (t) {
          const s = "Infinite loop on byte: " + t.charCodeAt(0);
          if (this.options.silent) {
            console.error(s);
            break;
          } else
            throw new Error(s);
        }
      }
    return this.state.top = !0, i;
  }
  inline(t, i = []) {
    return this.inlineQueue.push({ src: t, tokens: i }), i;
  }
  /**
   * Lexing/Compiling
   */
  inlineTokens(t, i = []) {
    let a, n, l, r = t, s, o, d;
    if (this.tokens.links) {
      const c = Object.keys(this.tokens.links);
      if (c.length > 0)
        for (; (s = this.tokenizer.rules.inline.reflinkSearch.exec(r)) != null; )
          c.includes(s[0].slice(s[0].lastIndexOf("[") + 1, -1)) && (r = r.slice(0, s.index) + "[" + repeatString("a", s[0].length - 2) + "]" + r.slice(this.tokenizer.rules.inline.reflinkSearch.lastIndex));
    }
    for (; (s = this.tokenizer.rules.inline.blockSkip.exec(r)) != null; )
      r = r.slice(0, s.index) + "[" + repeatString("a", s[0].length - 2) + "]" + r.slice(this.tokenizer.rules.inline.blockSkip.lastIndex);
    for (; (s = this.tokenizer.rules.inline.escapedEmSt.exec(r)) != null; )
      r = r.slice(0, s.index + s[0].length - 2) + "++" + r.slice(this.tokenizer.rules.inline.escapedEmSt.lastIndex), this.tokenizer.rules.inline.escapedEmSt.lastIndex--;
    for (; t; )
      if (o || (d = ""), o = !1, !(this.options.extensions && this.options.extensions.inline && this.options.extensions.inline.some((c) => (a = c.call({ lexer: this }, t, i)) ? (t = t.substring(a.raw.length), i.push(a), !0) : !1))) {
        if (a = this.tokenizer.escape(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.tag(t)) {
          t = t.substring(a.raw.length), n = i[i.length - 1], n && a.type === "text" && n.type === "text" ? (n.raw += a.raw, n.text += a.text) : i.push(a);
          continue;
        }
        if (a = this.tokenizer.link(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.reflink(t, this.tokens.links)) {
          t = t.substring(a.raw.length), n = i[i.length - 1], n && a.type === "text" && n.type === "text" ? (n.raw += a.raw, n.text += a.text) : i.push(a);
          continue;
        }
        if (a = this.tokenizer.emStrong(t, r, d)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.codespan(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.br(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.del(t)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (a = this.tokenizer.autolink(t, mangle)) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (!this.state.inLink && (a = this.tokenizer.url(t, mangle))) {
          t = t.substring(a.raw.length), i.push(a);
          continue;
        }
        if (l = t, this.options.extensions && this.options.extensions.startInline) {
          let c = 1 / 0;
          const u = t.slice(1);
          let f;
          this.options.extensions.startInline.forEach(function(p) {
            f = p.call({ lexer: this }, u), typeof f == "number" && f >= 0 && (c = Math.min(c, f));
          }), c < 1 / 0 && c >= 0 && (l = t.substring(0, c + 1));
        }
        if (a = this.tokenizer.inlineText(l, smartypants)) {
          t = t.substring(a.raw.length), a.raw.slice(-1) !== "_" && (d = a.raw.slice(-1)), o = !0, n = i[i.length - 1], n && n.type === "text" ? (n.raw += a.raw, n.text += a.text) : i.push(a);
          continue;
        }
        if (t) {
          const c = "Infinite loop on byte: " + t.charCodeAt(0);
          if (this.options.silent) {
            console.error(c);
            break;
          } else
            throw new Error(c);
        }
      }
    return i;
  }
}
class Renderer {
  constructor(t) {
    this.options = t || defaults;
  }
  code(t, i, a) {
    const n = (i || "").match(/\S*/)[0];
    if (this.options.highlight) {
      const l = this.options.highlight(t, n);
      l != null && l !== t && (a = !0, t = l);
    }
    return t = t.replace(/\n$/, "") + `
`, n ? '<pre><code class="' + this.options.langPrefix + escape(n) + '">' + (a ? t : escape(t, !0)) + `</code></pre>
` : "<pre><code>" + (a ? t : escape(t, !0)) + `</code></pre>
`;
  }
  /**
   * @param {string} quote
   */
  blockquote(t) {
    return `<blockquote>
${t}</blockquote>
`;
  }
  html(t) {
    return t;
  }
  /**
   * @param {string} text
   * @param {string} level
   * @param {string} raw
   * @param {any} slugger
   */
  heading(t, i, a, n) {
    if (this.options.headerIds) {
      const l = this.options.headerPrefix + n.slug(a);
      return `<h${i} id="${l}">${t}</h${i}>
`;
    }
    return `<h${i}>${t}</h${i}>
`;
  }
  hr() {
    return this.options.xhtml ? `<hr/>
` : `<hr>
`;
  }
  list(t, i, a) {
    const n = i ? "ol" : "ul", l = i && a !== 1 ? ' start="' + a + '"' : "";
    return "<" + n + l + `>
` + t + "</" + n + `>
`;
  }
  /**
   * @param {string} text
   */
  listitem(t) {
    return `<li>${t}</li>
`;
  }
  checkbox(t) {
    return "<input " + (t ? 'checked="" ' : "") + 'disabled="" type="checkbox"' + (this.options.xhtml ? " /" : "") + "> ";
  }
  /**
   * @param {string} text
   */
  paragraph(t) {
    return `<p>${t}</p>
`;
  }
  /**
   * @param {string} header
   * @param {string} body
   */
  table(t, i) {
    return i && (i = `<tbody>${i}</tbody>`), `<table>
<thead>
` + t + `</thead>
` + i + `</table>
`;
  }
  /**
   * @param {string} content
   */
  tablerow(t) {
    return `<tr>
${t}</tr>
`;
  }
  tablecell(t, i) {
    const a = i.header ? "th" : "td";
    return (i.align ? `<${a} align="${i.align}">` : `<${a}>`) + t + `</${a}>
`;
  }
  /**
   * span level renderer
   * @param {string} text
   */
  strong(t) {
    return `<strong>${t}</strong>`;
  }
  /**
   * @param {string} text
   */
  em(t) {
    return `<em>${t}</em>`;
  }
  /**
   * @param {string} text
   */
  codespan(t) {
    return `<code>${t}</code>`;
  }
  br() {
    return this.options.xhtml ? "<br/>" : "<br>";
  }
  /**
   * @param {string} text
   */
  del(t) {
    return `<del>${t}</del>`;
  }
  /**
   * @param {string} href
   * @param {string} title
   * @param {string} text
   */
  link(t, i, a) {
    if (t = cleanUrl(this.options.sanitize, this.options.baseUrl, t), t === null)
      return a;
    let n = '<a href="' + t + '"';
    return i && (n += ' title="' + i + '"'), n += ">" + a + "</a>", n;
  }
  /**
   * @param {string} href
   * @param {string} title
   * @param {string} text
   */
  image(t, i, a) {
    if (t = cleanUrl(this.options.sanitize, this.options.baseUrl, t), t === null)
      return a;
    let n = `<img src="${t}" alt="${a}"`;
    return i && (n += ` title="${i}"`), n += this.options.xhtml ? "/>" : ">", n;
  }
  text(t) {
    return t;
  }
}
class TextRenderer {
  // no need for block level renderers
  strong(t) {
    return t;
  }
  em(t) {
    return t;
  }
  codespan(t) {
    return t;
  }
  del(t) {
    return t;
  }
  html(t) {
    return t;
  }
  text(t) {
    return t;
  }
  link(t, i, a) {
    return "" + a;
  }
  image(t, i, a) {
    return "" + a;
  }
  br() {
    return "";
  }
}
class Slugger {
  constructor() {
    this.seen = {};
  }
  /**
   * @param {string} value
   */
  serialize(t) {
    return t.toLowerCase().trim().replace(/<[!\/a-z].*?>/ig, "").replace(/[\u2000-\u206F\u2E00-\u2E7F\\'!"#$%&()*+,./:;<=>?@[\]^`{|}~]/g, "").replace(/\s/g, "-");
  }
  /**
   * Finds the next safe (unique) slug to use
   * @param {string} originalSlug
   * @param {boolean} isDryRun
   */
  getNextSafeSlug(t, i) {
    let a = t, n = 0;
    if (this.seen.hasOwnProperty(a)) {
      n = this.seen[t];
      do
        n++, a = t + "-" + n;
      while (this.seen.hasOwnProperty(a));
    }
    return i || (this.seen[t] = n, this.seen[a] = 0), a;
  }
  /**
   * Convert string to unique id
   * @param {object} [options]
   * @param {boolean} [options.dryrun] Generates the next unique slug without
   * updating the internal accumulator.
   */
  slug(t, i = {}) {
    const a = this.serialize(t);
    return this.getNextSafeSlug(a, i.dryrun);
  }
}
class Parser {
  constructor(t) {
    this.options = t || defaults, this.options.renderer = this.options.renderer || new Renderer(), this.renderer = this.options.renderer, this.renderer.options = this.options, this.textRenderer = new TextRenderer(), this.slugger = new Slugger();
  }
  /**
   * Static Parse Method
   */
  static parse(t, i) {
    return new Parser(i).parse(t);
  }
  /**
   * Static Parse Inline Method
   */
  static parseInline(t, i) {
    return new Parser(i).parseInline(t);
  }
  /**
   * Parse Loop
   */
  parse(t, i = !0) {
    let a = "", n, l, r, s, o, d, c, u, f, p, m, h, I, b, g, E, T, S, L;
    const M = t.length;
    for (n = 0; n < M; n++) {
      if (p = t[n], this.options.extensions && this.options.extensions.renderers && this.options.extensions.renderers[p.type] && (L = this.options.extensions.renderers[p.type].call({ parser: this }, p), L !== !1 || !["space", "hr", "heading", "code", "table", "blockquote", "list", "html", "paragraph", "text"].includes(p.type))) {
        a += L || "";
        continue;
      }
      switch (p.type) {
        case "space":
          continue;
        case "hr": {
          a += this.renderer.hr();
          continue;
        }
        case "heading": {
          a += this.renderer.heading(
            this.parseInline(p.tokens),
            p.depth,
            unescape$1(this.parseInline(p.tokens, this.textRenderer)),
            this.slugger
          );
          continue;
        }
        case "code": {
          a += this.renderer.code(
            p.text,
            p.lang,
            p.escaped
          );
          continue;
        }
        case "table": {
          for (u = "", c = "", s = p.header.length, l = 0; l < s; l++)
            c += this.renderer.tablecell(
              this.parseInline(p.header[l].tokens),
              { header: !0, align: p.align[l] }
            );
          for (u += this.renderer.tablerow(c), f = "", s = p.rows.length, l = 0; l < s; l++) {
            for (d = p.rows[l], c = "", o = d.length, r = 0; r < o; r++)
              c += this.renderer.tablecell(
                this.parseInline(d[r].tokens),
                { header: !1, align: p.align[r] }
              );
            f += this.renderer.tablerow(c);
          }
          a += this.renderer.table(u, f);
          continue;
        }
        case "blockquote": {
          f = this.parse(p.tokens), a += this.renderer.blockquote(f);
          continue;
        }
        case "list": {
          for (m = p.ordered, h = p.start, I = p.loose, s = p.items.length, f = "", l = 0; l < s; l++)
            g = p.items[l], E = g.checked, T = g.task, b = "", g.task && (S = this.renderer.checkbox(E), I ? g.tokens.length > 0 && g.tokens[0].type === "paragraph" ? (g.tokens[0].text = S + " " + g.tokens[0].text, g.tokens[0].tokens && g.tokens[0].tokens.length > 0 && g.tokens[0].tokens[0].type === "text" && (g.tokens[0].tokens[0].text = S + " " + g.tokens[0].tokens[0].text)) : g.tokens.unshift({
              type: "text",
              text: S
            }) : b += S), b += this.parse(g.tokens, I), f += this.renderer.listitem(b, T, E);
          a += this.renderer.list(f, m, h);
          continue;
        }
        case "html": {
          a += this.renderer.html(p.text);
          continue;
        }
        case "paragraph": {
          a += this.renderer.paragraph(this.parseInline(p.tokens));
          continue;
        }
        case "text": {
          for (f = p.tokens ? this.parseInline(p.tokens) : p.text; n + 1 < M && t[n + 1].type === "text"; )
            p = t[++n], f += `
` + (p.tokens ? this.parseInline(p.tokens) : p.text);
          a += i ? this.renderer.paragraph(f) : f;
          continue;
        }
        default: {
          const y = 'Token with "' + p.type + '" type was not found.';
          if (this.options.silent) {
            console.error(y);
            return;
          } else
            throw new Error(y);
        }
      }
    }
    return a;
  }
  /**
   * Parse Inline Tokens
   */
  parseInline(t, i) {
    i = i || this.renderer;
    let a = "", n, l, r;
    const s = t.length;
    for (n = 0; n < s; n++) {
      if (l = t[n], this.options.extensions && this.options.extensions.renderers && this.options.extensions.renderers[l.type] && (r = this.options.extensions.renderers[l.type].call({ parser: this }, l), r !== !1 || !["escape", "html", "link", "image", "strong", "em", "codespan", "br", "del", "text"].includes(l.type))) {
        a += r || "";
        continue;
      }
      switch (l.type) {
        case "escape": {
          a += i.text(l.text);
          break;
        }
        case "html": {
          a += i.html(l.text);
          break;
        }
        case "link": {
          a += i.link(l.href, l.title, this.parseInline(l.tokens, i));
          break;
        }
        case "image": {
          a += i.image(l.href, l.title, l.text);
          break;
        }
        case "strong": {
          a += i.strong(this.parseInline(l.tokens, i));
          break;
        }
        case "em": {
          a += i.em(this.parseInline(l.tokens, i));
          break;
        }
        case "codespan": {
          a += i.codespan(l.text);
          break;
        }
        case "br": {
          a += i.br();
          break;
        }
        case "del": {
          a += i.del(this.parseInline(l.tokens, i));
          break;
        }
        case "text": {
          a += i.text(l.text);
          break;
        }
        default: {
          const o = 'Token with "' + l.type + '" type was not found.';
          if (this.options.silent) {
            console.error(o);
            return;
          } else
            throw new Error(o);
        }
      }
    }
    return a;
  }
}
class Hooks {
  constructor(t) {
    this.options = t || defaults;
  }
  /**
   * Process markdown before marked
   */
  preprocess(t) {
    return t;
  }
  /**
   * Process HTML after marked is finished
   */
  postprocess(t) {
    return t;
  }
}
X(Hooks, "passThroughHooks", /* @__PURE__ */ new Set([
  "preprocess",
  "postprocess"
]));
function onError(e, t, i) {
  return (a) => {
    if (a.message += `
Please report this to https://github.com/markedjs/marked.`, e) {
      const n = "<p>An error occurred:</p><pre>" + escape(a.message + "", !0) + "</pre>";
      if (t)
        return Promise.resolve(n);
      if (i) {
        i(null, n);
        return;
      }
      return n;
    }
    if (t)
      return Promise.reject(a);
    if (i) {
      i(a);
      return;
    }
    throw a;
  };
}
function parseMarkdown(e, t) {
  return (i, a, n) => {
    typeof a == "function" && (n = a, a = null);
    const l = { ...a };
    a = { ...marked.defaults, ...l };
    const r = onError(a.silent, a.async, n);
    if (typeof i > "u" || i === null)
      return r(new Error("marked(): input parameter is undefined or null"));
    if (typeof i != "string")
      return r(new Error("marked(): input parameter is of type " + Object.prototype.toString.call(i) + ", string expected"));
    if (checkSanitizeDeprecation(a), a.hooks && (a.hooks.options = a), n) {
      const s = a.highlight;
      let o;
      try {
        a.hooks && (i = a.hooks.preprocess(i)), o = e(i, a);
      } catch (u) {
        return r(u);
      }
      const d = function(u) {
        let f;
        if (!u)
          try {
            a.walkTokens && marked.walkTokens(o, a.walkTokens), f = t(o, a), a.hooks && (f = a.hooks.postprocess(f));
          } catch (p) {
            u = p;
          }
        return a.highlight = s, u ? r(u) : n(null, f);
      };
      if (!s || s.length < 3 || (delete a.highlight, !o.length)) return d();
      let c = 0;
      marked.walkTokens(o, function(u) {
        u.type === "code" && (c++, setTimeout(() => {
          s(u.text, u.lang, function(f, p) {
            if (f)
              return d(f);
            p != null && p !== u.text && (u.text = p, u.escaped = !0), c--, c === 0 && d();
          });
        }, 0));
      }), c === 0 && d();
      return;
    }
    if (a.async)
      return Promise.resolve(a.hooks ? a.hooks.preprocess(i) : i).then((s) => e(s, a)).then((s) => a.walkTokens ? Promise.all(marked.walkTokens(s, a.walkTokens)).then(() => s) : s).then((s) => t(s, a)).then((s) => a.hooks ? a.hooks.postprocess(s) : s).catch(r);
    try {
      a.hooks && (i = a.hooks.preprocess(i));
      const s = e(i, a);
      a.walkTokens && marked.walkTokens(s, a.walkTokens);
      let o = t(s, a);
      return a.hooks && (o = a.hooks.postprocess(o)), o;
    } catch (s) {
      return r(s);
    }
  };
}
function marked(e, t, i) {
  return parseMarkdown(Lexer.lex, Parser.parse)(e, t, i);
}
marked.options = marked.setOptions = function(e) {
  return marked.defaults = { ...marked.defaults, ...e }, changeDefaults(marked.defaults), marked;
};
marked.getDefaults = getDefaults;
marked.defaults = defaults;
marked.use = function(...e) {
  const t = marked.defaults.extensions || { renderers: {}, childTokens: {} };
  e.forEach((i) => {
    const a = { ...i };
    if (a.async = marked.defaults.async || a.async || !1, i.extensions && (i.extensions.forEach((n) => {
      if (!n.name)
        throw new Error("extension name required");
      if (n.renderer) {
        const l = t.renderers[n.name];
        l ? t.renderers[n.name] = function(...r) {
          let s = n.renderer.apply(this, r);
          return s === !1 && (s = l.apply(this, r)), s;
        } : t.renderers[n.name] = n.renderer;
      }
      if (n.tokenizer) {
        if (!n.level || n.level !== "block" && n.level !== "inline")
          throw new Error("extension level must be 'block' or 'inline'");
        t[n.level] ? t[n.level].unshift(n.tokenizer) : t[n.level] = [n.tokenizer], n.start && (n.level === "block" ? t.startBlock ? t.startBlock.push(n.start) : t.startBlock = [n.start] : n.level === "inline" && (t.startInline ? t.startInline.push(n.start) : t.startInline = [n.start]));
      }
      n.childTokens && (t.childTokens[n.name] = n.childTokens);
    }), a.extensions = t), i.renderer) {
      const n = marked.defaults.renderer || new Renderer();
      for (const l in i.renderer) {
        const r = n[l];
        n[l] = (...s) => {
          let o = i.renderer[l].apply(n, s);
          return o === !1 && (o = r.apply(n, s)), o;
        };
      }
      a.renderer = n;
    }
    if (i.tokenizer) {
      const n = marked.defaults.tokenizer || new Tokenizer();
      for (const l in i.tokenizer) {
        const r = n[l];
        n[l] = (...s) => {
          let o = i.tokenizer[l].apply(n, s);
          return o === !1 && (o = r.apply(n, s)), o;
        };
      }
      a.tokenizer = n;
    }
    if (i.hooks) {
      const n = marked.defaults.hooks || new Hooks();
      for (const l in i.hooks) {
        const r = n[l];
        Hooks.passThroughHooks.has(l) ? n[l] = (s) => {
          if (marked.defaults.async)
            return Promise.resolve(i.hooks[l].call(n, s)).then((d) => r.call(n, d));
          const o = i.hooks[l].call(n, s);
          return r.call(n, o);
        } : n[l] = (...s) => {
          let o = i.hooks[l].apply(n, s);
          return o === !1 && (o = r.apply(n, s)), o;
        };
      }
      a.hooks = n;
    }
    if (i.walkTokens) {
      const n = marked.defaults.walkTokens;
      a.walkTokens = function(l) {
        let r = [];
        return r.push(i.walkTokens.call(this, l)), n && (r = r.concat(n.call(this, l))), r;
      };
    }
    marked.setOptions(a);
  });
};
marked.walkTokens = function(e, t) {
  let i = [];
  for (const a of e)
    switch (i = i.concat(t.call(marked, a)), a.type) {
      case "table": {
        for (const n of a.header)
          i = i.concat(marked.walkTokens(n.tokens, t));
        for (const n of a.rows)
          for (const l of n)
            i = i.concat(marked.walkTokens(l.tokens, t));
        break;
      }
      case "list": {
        i = i.concat(marked.walkTokens(a.items, t));
        break;
      }
      default:
        marked.defaults.extensions && marked.defaults.extensions.childTokens && marked.defaults.extensions.childTokens[a.type] ? marked.defaults.extensions.childTokens[a.type].forEach(function(n) {
          i = i.concat(marked.walkTokens(a[n], t));
        }) : a.tokens && (i = i.concat(marked.walkTokens(a.tokens, t)));
    }
  return i;
};
marked.parseInline = parseMarkdown(Lexer.lexInline, Parser.parseInline);
marked.Parser = Parser;
marked.parser = Parser.parse;
marked.Renderer = Renderer;
marked.TextRenderer = TextRenderer;
marked.Lexer = Lexer;
marked.lexer = Lexer.lex;
marked.Tokenizer = Tokenizer;
marked.Slugger = Slugger;
marked.Hooks = Hooks;
marked.parse = marked;
marked.options;
marked.setOptions;
marked.use;
marked.walkTokens;
marked.parseInline;
Parser.parse;
Lexer.lex;
function getAugmentedNamespace(e) {
  if (e.__esModule) return e;
  var t = e.default;
  if (typeof t == "function") {
    var i = function a() {
      return this instanceof a ? Reflect.construct(t, arguments, this.constructor) : t.apply(this, arguments);
    };
    i.prototype = t.prototype;
  } else i = {};
  return Object.defineProperty(i, "__esModule", { value: !0 }), Object.keys(e).forEach(function(a) {
    var n = Object.getOwnPropertyDescriptor(e, a);
    Object.defineProperty(i, a, n.get ? n : {
      enumerable: !0,
      get: function() {
        return e[a];
      }
    });
  }), i;
}
function commonjsRequire(e) {
  throw new Error('Could not dynamically require "' + e + '". Please configure the dynamicRequireTargets or/and ignoreDynamicRequires option of @rollup/plugin-commonjs appropriately for this require call to work.');
}
var uniqid = { exports: {} };
const __viteBrowserExternal = {}, __viteBrowserExternal$1 = /* @__PURE__ */ Object.freeze(/* @__PURE__ */ Object.defineProperty({
  __proto__: null,
  default: __viteBrowserExternal
}, Symbol.toStringTag, { value: "Module" })), require$$0 = /* @__PURE__ */ getAugmentedNamespace(__viteBrowserExternal$1);
var hasRequiredUniqid;
function requireUniqid() {
  if (hasRequiredUniqid) return uniqid.exports;
  hasRequiredUniqid = 1;
  var e = typeof process < "u" && process.pid ? process.pid.toString(36) : "", t = "";
  if (typeof __webpack_require__ != "function" && typeof commonjsRequire < "u") {
    var i = "", a = require$$0;
    if (a.networkInterfaces) var n = a.networkInterfaces();
    if (n) {
      e:
        for (let s in n) {
          const o = n[s], d = o.length;
          for (var l = 0; l < d; l++)
            if (o[l] !== void 0 && o[l].mac && o[l].mac != "00:00:00:00:00:00") {
              i = o[l].mac;
              break e;
            }
        }
      t = i ? parseInt(i.replace(/\:|\D+/gi, "")).toString(36) : "";
    }
  }
  uniqid.exports = uniqid.exports.default = function(s, o) {
    return (s || "") + t + e + r().toString(36) + (o || "");
  }, uniqid.exports.process = function(s, o) {
    return (s || "") + e + r().toString(36) + (o || "");
  }, uniqid.exports.time = function(s, o) {
    return (s || "") + r().toString(36) + (o || "");
  };
  function r() {
    var s = Date.now(), o = r.last || s;
    return r.last = s > o ? s : o + 1;
  }
  return uniqid.exports;
}
requireUniqid();
function data_get(e, t, i = null) {
  var a = Array.isArray(t) ? t : t.split("."), n = a.reduce((l, r) => l && l[r], e);
  return n !== void 0 ? n : i;
}
const isString = tagTester("String");
var isArguments = tagTester("Arguments");
(function() {
  isArguments(arguments) || (isArguments = function(e) {
    return has(e, "callee");
  });
})();
const isArguments$1 = isArguments;
function isEmpty(e) {
  if (e == null) return !0;
  var t = getLength(e);
  return typeof t == "number" && (isArray(e) || isString(e) || isArguments$1(e)) ? t === 0 : getLength(keys(e)) === 0;
}
function values(e) {
  for (var t = keys(e), i = t.length, a = Array(i), n = 0; n < i; n++)
    a[n] = e[t[n]];
  return a;
}
function sortedIndex(e, t, i, a) {
  i = cb(i, a, 1);
  for (var n = i(t), l = 0, r = getLength(e); l < r; ) {
    var s = Math.floor((l + r) / 2);
    i(e[s]) < n ? l = s + 1 : r = s;
  }
  return l;
}
function createPredicateIndexFinder(e) {
  return function(t, i, a) {
    i = cb(i, a);
    for (var n = getLength(t), l = 0; l >= 0 && l < n; l += e)
      if (i(t[l], l, t)) return l;
    return -1;
  };
}
const findIndex = createPredicateIndexFinder(1), isNumber = tagTester("Number");
function isNaN$1(e) {
  return isNumber(e) && _isNaN(e);
}
function createIndexFinder(e, t, i) {
  return function(a, n, l) {
    var r = 0, s = getLength(a);
    if (typeof l == "number")
      r = l >= 0 ? l : Math.max(l + s, r);
    else if (i && l && s)
      return l = i(a, n), a[l] === n ? l : -1;
    if (n !== n)
      return l = t(slice.call(a, r, s), isNaN$1), l >= 0 ? l + r : -1;
    for (l = r; l >= 0 && l < s; l += e)
      if (a[l] === n) return l;
    return -1;
  };
}
const indexOf = createIndexFinder(1, findIndex, sortedIndex);
function contains(e, t, i, a) {
  return isArrayLike(e) || (e = values(e)), (typeof i != "number" || a) && (i = 0), indexOf(e, t, i) >= 0;
}
function intersection(e) {
  for (var t = [], i = arguments.length, a = 0, n = getLength(e); a < n; a++) {
    var l = e[a];
    if (!contains(t, l)) {
      var r;
      for (r = 1; r < i && contains(arguments[r], l); r++)
        ;
      r === i && t.push(l);
    }
  }
  return t;
}
function negate(e) {
  return function() {
    return !e.apply(this, arguments);
  };
}
function reject(e, t, i) {
  return filter(e, negate(cb(t)), i);
}
function initial(e, t, i) {
  return slice.call(e, 0, Math.max(0, e.length - (t == null || i ? 1 : t)));
}
function first(e, t, i) {
  return e == null || e.length < 1 ? t == null || i ? void 0 : [] : t == null || i ? e[0] : initial(e, e.length - t);
}
mixin({ chain, map, each, filter, reject, first, isEmpty });
const NUMBER_SPECIFIC_COMPARISONS = [
  ">",
  ">=",
  "<",
  "<="
];
class Validator {
  constructor(e, t, i, a, n) {
    this.field = e, this.values = t, this.dottedFieldPath = i, this.store = a, this.storeName = n, this.rootValues = a ? a.state.publish[n].values : !1, this.passOnAny = !1, this.showOnPass = !0, this.converter = new Converter();
  }
  passesConditions(e) {
    let t = e || this.getConditions();
    if (t === void 0)
      return !0;
    if (this.isCustomConditionWithoutTarget(t))
      return this.passesCustomCondition(this.prepareCondition(t));
    let i = this.passOnAny ? this.passesAnyConditions(t) : this.passesAllConditions(t);
    return this.showOnPass ? i : !i;
  }
  getConditions() {
    let e = chain(KEYS).filter((i) => this.field[i]).first().value();
    if (!e)
      return;
    e.includes("any") && (this.passOnAny = !0), (e.includes("unless") || e.includes("hide_when")) && (this.showOnPass = !1);
    let t = this.field[e];
    return this.isCustomConditionWithoutTarget(t) ? t : this.converter.fromBlueprint(t, this.field.prefix);
  }
  isCustomConditionWithoutTarget(e) {
    return isString(e);
  }
  passesAllConditions(e) {
    return chain(e).map((t) => this.prepareCondition(t)).reject((t) => this.passesCondition(t)).isEmpty().value();
  }
  passesAnyConditions(e) {
    return !chain(e).map((t) => this.prepareCondition(t)).filter((t) => this.passesCondition(t)).isEmpty().value();
  }
  prepareCondition(e) {
    if (isString(e) || e.operator === "custom")
      return this.prepareCustomCondition(e);
    let t = this.prepareOperator(e.operator), i = this.prepareLhs(e.field, t), a = this.prepareRhs(e.value, t);
    return { lhs: i, operator: t, rhs: a };
  }
  prepareOperator(e) {
    switch (e) {
      case null:
      case "":
      case "is":
      case "equals":
        return "==";
      case "isnt":
      case "not":
      case "¯\\_(ツ)_/¯":
        return "!=";
      case "includes":
      case "contains":
        return "includes";
      case "includes_any":
      case "contains_any":
        return "includes_any";
    }
    return e;
  }
  prepareLhs(e, t) {
    let i = this.getFieldValue(e);
    return NUMBER_SPECIFIC_COMPARISONS.includes(t) ? Number(i) : t === "includes" && !isObject(i) ? i ? i.toString() : "" : (isString(i) && isEmpty(i) && (i = null), isString(i) ? JSON.stringify(i.trim()) : i);
  }
  prepareRhs(e, t) {
    switch (e) {
      case "null":
        return null;
      case "true":
        return !0;
      case "false":
        return !1;
    }
    return NUMBER_SPECIFIC_COMPARISONS.includes(t) ? Number(e) : e === "empty" || t === "includes" || t === "includes_any" ? e : isString(e) ? JSON.stringify(e.trim()) : e;
  }
  prepareCustomCondition(e) {
    let t = this.prepareFunctionName(e.value || e), i = this.prepareParams(e.value || e), a = e.field ? this.getFieldValue(e.field) : null, n = e.field;
    return { functionName: t, params: i, target: a, targetHandle: n };
  }
  prepareFunctionName(e) {
    return e.replace(new RegExp("^custom "), "").split(":")[0];
  }
  prepareParams(e) {
    let t = e.split(":")[1];
    return t ? t.split(",").map((i) => i.trim()) : [];
  }
  getFieldValue(e) {
    return e.startsWith("$parent.") && (e = new ParentResolver(this.dottedFieldPath).resolve(e)), e.startsWith("$root.") || e.startsWith("root.") ? data_get(this.rootValues, e.replace(new RegExp("^\\$?root\\."), "")) : data_get(this.values, e);
  }
  passesCondition(condition) {
    return condition.functionName ? this.passesCustomCondition(condition) : condition.operator === "includes" ? this.passesIncludesCondition(condition) : condition.operator === "includes_any" ? this.passesIncludesAnyCondition(condition) : (condition.rhs === "empty" && (condition.lhs = isEmpty(condition.lhs), condition.rhs = !0), isObject(condition.lhs) ? !1 : eval(`${condition.lhs} ${condition.operator} ${condition.rhs}`));
  }
  passesIncludesCondition(e) {
    return e.lhs.includes(e.rhs);
  }
  passesIncludesAnyCondition(e) {
    let t = e.rhs.split(",").map((i) => i.trim());
    return Array.isArray(e.lhs) ? intersection(e.lhs, t).length : new RegExp(t.join("|")).test(e.lhs);
  }
  passesCustomCondition(e) {
    let t = data_get(this.store.state.statamic.conditions, e.functionName);
    if (typeof t != "function")
      return console.error(`Statamic field condition [${e.functionName}] was not properly defined.`), !1;
    let i = t({
      params: e.params,
      target: e.target,
      targetHandle: e.targetHandle,
      values: this.values,
      root: this.rootValues,
      store: this.store,
      storeName: this.storeName,
      fieldPath: this.dottedFieldPath
    });
    return this.showOnPass ? i : !i;
  }
  passesNonRevealerConditions(e) {
    let t = this.getConditions();
    if (this.isCustomConditionWithoutTarget(t))
      return this.passesConditions(t);
    let i = data_get(this.store.state.publish[this.storeName], "revealerFields", []), a = chain(this.getConditions()).reject((n) => i.includes(this.relativeLhsToAbsoluteFieldPath(n.field, e))).value();
    return this.passesConditions(a);
  }
  relativeLhsToAbsoluteFieldPath(e, t) {
    return e.startsWith("$parent.") && (e = new ParentResolver(this.dottedFieldPath).resolve(e)), e.startsWith("$root.") || e.startsWith("root.") ? e.replace(new RegExp("^\\$?root\\."), "") : t ? t + "." + e : e;
  }
}
class FieldConditions {
  showField(t, i) {
    return new Validator(t, i).passesConditions();
  }
}
const form = () => ({
  fields: {},
  sections: {},
  processForm() {
    this.fields = this.processFields(this.$wire.fields), this.sections = this.processSections(this.fields);
  },
  processFields(e) {
    const t = Object.entries(e).reduce((i, [a, n]) => (i[a] = n.value, i), {});
    return Object.entries(e).reduce((i, [a, n]) => {
      const l = new FieldConditions().showField(n.properties.conditions, t);
      return i[a] = {
        visible: l && !n.properties.hidden,
        submittable: n.properties.always_save || l,
        section: n.section
      }, this.$wire.submittableFields[a] = i[a].submittable, i;
    }, {});
  },
  processSections(e) {
    const t = Object.entries(e).reduce((a, [n, l]) => (l.section && (a[l.section] = a[l.section] || [], a[l.section].push(l.visible)), a), {}), i = Object.fromEntries(
      Object.entries(t).map(([a, n]) => [
        a,
        n.some(Boolean)
      ])
    );
    return JSON.stringify(i) !== JSON.stringify(this.$wire.stepVisibility) && (this.$wire.stepVisibility = i, this.$wire.$refresh()), i;
  },
  showField(e) {
    return this.fields[e].visible;
  },
  showSection(e) {
    return this.sections[e];
  },
  showStep(e) {
    return this.sections[e];
  }
}), grecaptcha = (e) => ({
  init() {
    if (typeof window.grecaptchaIsReady > "u")
      return setTimeout(() => this.init(), 100);
    window.grecaptcha.render(this.$el, {
      sitekey: e.siteKey,
      callback: (t) => this.$wire.set(e.field, t),
      "expired-callback": () => this.$wire.set(e.field, null)
    });
  }
});
Alpine.data("filepond", filepond);
Alpine.data("form", form);
Alpine.data("grecaptcha", grecaptcha);
