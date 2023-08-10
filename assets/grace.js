var gv = {
    gv: [],
    cls: function(el, ary, add, sar) {
        for (let cls of ary) {
            if ('!' == cls.charAt(0)) {
                if (!add)
                    continue;
                cls = cls.substring(1);
            }
            if (sar) for (let [s, r] of sar)
                cls = cls.replace(s, r);
            add ? el.classList.add(cls) : el.classList.remove(cls)
        }
    },
    ps: function(all, filt) {
        let out = [], ps = filt || 'sar';
        for (let one of all) {
            a = one.split(':');
            if (a.length > 1 && ps == a[0])
                out.push('sar' == ps ? a[1].split('=') : a[1])
        }
        return out;
    },
    test: function(js, name, ary) {
        let set = js.split(' '), re = new RegExp('^' + name);
        for (let one of set) {
            if (one.match(re))
                return ary ? set : one;
        }
        return false;
    },
    findAll: function(el, name) {
        let one, out = [], js = el.getAttribute('js');
        if (js && (one = gv.test(js, name)))
            out.push([el, one]);
        if (set = el.querySelectorAll('[js*=' + name.replace(/([^_a-z\-\d])/g, '\\$1') + ']')) {
            [...set].forEach(el => {
                out.push([el, gv.test(el.getAttribute('js'), name)]);
            });
        }
        return out;
    },
    find: function(el, name) {
        let js = el.getAttribute('js'), set;
        if (js && (set = gv.test(js, name, true)))
            return [el, set];
        if (el = el.querySelector('[js*=' + name.replace(/([^_a-z\-\d])/g, '\\$1') + ']'))
            return gv.find(el, name);
        return false;
    },
    to: function(el, $js, $$, add) {
        for (let name in $js) {
            if (!$js[name][$$])
                continue;
            let node = gv.find(el, name);
            node && gv.cls(node[0], $js[name][$$], add, 1 == node[1].length ? false : gv.ps(node[1]));
        }
    },
    start: function(el, $js, $$, listen, prev) {
        if (listen)
            gv.listen(el, $$, listen);
        gv.to(el, $js, $$ + '_', 1);
        setTimeout(function() {
            gv.to(el, $js, $$ + '_', 0);
            gv.to(el, $js, $$, 1);
            null === prev || gv.to(el, $js, 'twin' === prev ? (1 - $$) : prev, 0);
        }, 10);
    },
    next_id: 1,
    listen: function(el, $$, listen) {
        var id = parseInt(el.getAttribute('gv'));
        if (id) {
            gv.gv[id].prev = gv.gv[id].state;
            gv.gv[id].state = $$;
        } else {
            el.setAttribute('gv', id = gv.next_id++);
            gv.gv[id] = {state: $$, prev: 9999, hidden:false};
            for (let one in listen) {
                if ('end' === one) {
                    let all = gv.findAll(el, 'end:');
                    for (let end of all) {
                        if ('end:hidden' === end[1]) {
                            gv.gv[id].hidden = true;
                            listen[one] = () => {
                                gv.gv[id].state || el.classList.add('hidden');
                            };
                        }
                        end[0].addEventListener('transitionend', (e) => {
                            e.stopPropagation();
                            if (gv.gv[id].prev != gv.gv[id].state)
                                listen[one](gv.gv[id].prev = gv.gv[id].state, id, end[0])
                        }, false);
                    }
                } else if ('click' === one) {
                    document.addEventListener('click', listen[one], true);
                }
            }
        }
        if ($$ && gv.gv[id].hidden)
            el.classList.remove('hidden');
    },
    clone: function(src, cnt) {
        let str = 'string' == typeof src;
        let ary = str ? document.querySelectorAll('.star') : src;
        [...ary].forEach(el => {
            for (let i = 0; i < cnt; i++)
                el.insertAdjacentHTML('afterend', el.outerHTML);
        });
    },
    all: {
        
    },
    initFunc: false,
    init: function() {
        if (gv.initFunc)
            gv.initFunc();
    }
};

document.querySelector('html').addEventListener('load', function() {
    gv.init();
}, true);
