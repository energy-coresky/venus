
String.prototype.$$ = function(search, start) {
    
};

var Vls = function(name, val) {
    if ('undefined' === typeof val) {
        var v = localStorage.getItem(name),
            defs = {
                main: 0
            };
        return null === v ? defs[name] : v;
    }
    return null === val ? localStorage.removeItem(name) : localStorage.setItem(name, val);
    //localStorage.clear(); ! delete ALL
};


var $$ = {
    $f: null,
    $el: null,
    $tk: null,
    F: {W:640, H:480},    // frame size current
    prev: {W:640, H:480}, // frame size previouse
    S: {},                // screen (window)
    log: function(str) {
        $('#info span:eq(2)').text(str);
    },
    doc: function(selector, fr) {
        if (!fr)
            fr = $$.$f;
        var doc = $(fr[0].contentWindow.document);
        return selector ? doc.find(selector) : doc;
    },
    root: function($var, $val) {
        document.documentElement.style.setProperty('--' + $var, $val);
    },
    mm: function(sign) {
        $$.switch($$.F.W + sign * 10, $$.F.H + sign * 10)
            || setTimeout('$$.mm(' + sign + ')', 20);
    },
    resize: function() {
        $$.S = {W: $(document.body).width(), H: $(document.body).height()};
        $$.switch($$.F.W, $$.F.H, 1);
    },
    _mx: 't',
    menu: function(x) {
        ajax(['_venus', 'menu&m=' + ($$._mx = x)], {}, $('#v-menu ul'));
        $('#v-menu select:eq(0)').val(x);
    },
    //$$.fn
    switch: function(W, H, force, x, fn, tools) {
        if (fn) {
            if (force)
                $$.swap();
            $$.fn = fn;
            $$.test();
            $$.menu(x);
            if (tools) {
                tools = tools.split(' ');
                for (var id; id = tools.pop(); i$.load(id));
            }
        }
        if (!W) { // F8 key
            W = $$.prev.W;
            H = $$.prev.H;
            $$.prev = $$.F;
        } else if (!H) { // select dropdown
            var ary = $(W).find('option:selected').val().split(' x ');
            W = parseInt(ary[0]);
            H = parseInt(ary[1]);
        }
        // else direct W, H values
        var W_ = 0, H_ = 0, wh;
        if (W >= $$.S.W - 250)
            W_ = W = $$.S.W - 250;
        if (W <= 250)
            W_ = W = 250;
        if (H >= $$.S.H - 127)
            H_ = H = $$.S.H - 127;
        if (H <= 250)
            H_ = H = 250;

        if (W != $$.F.W || H != $$.F.H || force) {
            var op = $('#fsize option[value="' + (wh = W + ' x ' + H) + '"]');
            if (op[0]) {
                op.prop('selected', true);
            } else {
                $('#fsize option:last').prop('selected', true).val(wh).html(wh + '<sup>*</sup>');
            }
            $('#main').css({
                gridTemplateColumns: '150px ' + W + 'px ' + ($$.S.W - 150 - W) + 'px',
                gridTemplateRows:    '27px '  + H + 'px ' + ($$.S.H - 27  - H) + 'px'
            });
            $$.F = {W:W, H:H};
            $$.root('frame-w', W + 'px');
            $$.root('frame-h', H + 'px');
        }

        return H_ && W_;
    },
    Vmain: function() {
        var s = $$.F.W + ',' + $$.F.H + ',' + $$.div + ",'" + $$._mx + "','" + $$.fn + "'",
            t = $($$.div ? '#tail' : '#v-right'), ary = [];
        t.find('.tool').each(function () {
            ary.push(this.id.substr(2));
        });
        Vls('main', s + ",'" + ary.join(" ") + "'");
    },
    save: function() {
        $$.Vmain();
        ajax('save', {
            html: $('#code-body pre:eq(1)').html(),
            fn: $$.fn
        }, function(r) {
            
        });
    },
    m_move: function(e) {
        var w = this == document;
        $$.info(w ? `X:${e.clientX} Y:${e.clientY}` : `x:${e.clientX} y:${e.clientY}`, 0);
        if ($$.mouse)
            $$.mouse(w ? {X:e.clientX, Y:e.clientY} : {X:150 + e.clientX, Y:27 + e.clientY});
    },
    mouse: null,
    m_up: function() {
        $(document.body).css('userSelect', 'initial');
        $$.doc('body').css('userSelect', 'initial');
        if ($$.mouse)
            $$.Vmain();
        $$.mouse = null;
    },
    m_enter: function(e) {
        $$.$el = $(this);
        $$.info($(this).prop('tagName'), 1);
    },
    _catch: function() {//alert()
        $$.$tk = $$.$el;
        var s = $$.$el.prop('tagName') + ' e= catched' //+ $$.$el.attr('e');
        $$.info(s, 2);
    },
    _bg: 0,
    bg: function() {
        if ($$._bg = 1 - $$._bg) {
            $('#v-menu').css('background', 'var(--bg)');
            $$.root('border', 'none');
        } else {
            $('#v-menu').css('background', '#fff');
            $$.root('border', '1px solid #ccc');
        }
    },
    info: function(html, pos) {
        $('#info span:eq(' + pos + ')').html(html);
    },
    init: function(id) {

    },
  html: function(str, cls) {
      str = str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
      if (cls)
          str = `<span class="vs-${cls}">` + str + '</span>';
      return str;
  },
  parse: function(s) {
      var re = '(<!doctype[^>]*>)\\s*'
             + '(<html[^>]*>)\\s*'
             + '(<head[^>]*>)([\\s\\S]+)</head>\\s*'
             + '(<body[^>]*>)([\\s\\S]+)</body>\\s*'
             + '</html>', m = s.match(new RegExp(re, 'i'));
      if (!m)
          return $$.tidy(s);
      return $$.html(m[1]) + $$.html('\n' + m[2] + '\n' + m[3] + '\n') + $$.tidy(m[4], '  ')
          + $$.html('\n</head>\n') + $$.html(m[5]) + '\n' + $$.tidy(m[6]) + $$.html('\n</body>\n</html>');
  },
    fn: sky.home,
    cur_page: '',
    test: function(fn) {
        if (fn)
            $$.fn = fn;
        $$.$f.attr('src', sky.home + '_venus?src=' + $$.fn);
    },
    tree: function(html) {
        var tree = [];
        $.each($.parseHTML(html, document, true), function(i, el) {
            var data = '', i = 0, name = el.nodeName.toLowerCase(), attr = {">":name};
            switch (name) {
                case '#text':
                    data = $(el).text().trim();
                    if ('' === data)
                        return;
                    break;
                case '#comment':
                    data = el.data.trim().replace(/\n+/g, "\n");
                    break;
                default:
                    if (-1 == el.outerHTML.indexOf('><'))
                        data = 0; // Void element
                    if (!el.hasChildNodes())
                        break;
                    data = $$.tree(el.innerHTML.trim());
                    if (1 == data.length && '#text' == data[0][0])
                        data = data[0][1];
            }
            if (el.attributes) $.each(el.attributes, function() {
                attr[this.name] = this.value;
                i++;
            });
            tree.push([i ? attr : name, data]);
        });
        return tree;
    },
    src: {},
    onload: function() {
        $$.doc().mouseup($$.m_up).mousemove($$.m_move).find('body *').mouseenter($$.m_enter);
        let frameHTML = $$.doc('html:first').html().replaceAll('\r\n', '\n').replaceAll('\r', '\n');
        $$.src = {tree: $$.tree(frameHTML)};
//console.log(name);
        sky.json('src&src=' + $$.fn, $$.src, function(r) {
            $('#project-list').html(r.menu);
            $('#code-body pre:eq(0)').html(r.lines).next().html(r.html);
  //          $('#code-head b').html(r.fn);//$$.fn
        });
    },
    div: 0,
    swap: function() {
        var r = $('#v-right').html();
        $('#v-right').html($('#tail').html());
        $('#tail').html(r)
        $$.div = 1 - $$.div;
    },
    doc_clk: function() {
        $('.popup-menu, .popup-sub').each($$.ddm)
    },
    ddm: function(mode, el, show) {
        switch (mode) {
            case 'init':
                return el.find('.popup-menu, .popup-sub').each(function() {
                    this.onanimationend = function () {
                        if ($(this).hasClass('hide-a1'))
                            $(this).hide();
                        this.removeAttribute('running');
                    };
                });
            case 'hover':
                var fire = show ? el.next().find('.popup-sub:first')[0] : false,
                    top = el.parent().parent();
                top.find('.popup-sub').each(function() {
                    $$.ddm(0, this, fire === this)
                });
                return top.find('.menu').each(function() {
                    fire && this === el[0] ? el.attr('hover', 1) : this.removeAttribute('hover');
                });
            case 'show':
                el = $(el).find('.popup-menu')[0];
                $(el).find('[hover]').removeAttr('hover');
            default:
                var hidden = 'none' === $(el).css('display');
                if (el.hasAttribute('running') || hidden && !show || !hidden && show)
                    return;
                $(el).show().attr('running', 1).removeClass(show ? 'hide-a1' : 'show-a1').addClass(show ? 'show-a1' : 'hide-a1');
        }
    }
};

(function() {
    sky.a.error(function(r) {
   //     if (!r.soft)
     //       location.href = '_crash?' + r.err_no;
        $('#tail').html(r.catch_error);
    });
    sky.err = function(s) {
        $('#tail').html(s);
        //f1[0] ? ab.message(s, 0, 1) : alert(s); // red, no animation
    };
})();

$(function() {
    /*$('body').prepend('<div id="popup" style="display:none"></div>');
    (ab.aside = $('header').get(0) ? 120 : 0) || $('body').width('100%');
    $(window).scroll(ab.scroll);
    
body{
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
    
    */

    $$.$f = $('iframe:first');

    $(window).resize($$.resize);
    $$.resize();

    sky.key[27] = function() { // Escape
        var esc = $('.escape:last');
        esc.get(0) ? esc.click() : run();
    };
    sky.key[113] = function() { // F2
        $('.f2:first').click();
    };
    sky.key[115] = function() { // F4
        $('.f4:first').click();
    };
    sky.key[117] = function() { // F6
        $('.f6:first').click();
    };
    sky.key[119] = function() { // F8
        $('.f8:first').click();
    };
    sky.key[120] = function() { // F9
        $$.swap();
    };
    sky.key[121] = $$._catch; // F10

    $(document).click($$.doc_clk).mouseup($$.m_up).mousemove($$.m_move).mouseenter(function () {
        $$.info('-', 1);
    });
    $$.doc().mouseup($$.m_up).mousemove($$.m_move);

    $('#mov-y, #mov-x').mousedown(function (e) {
        $$.doc('body').css('userSelect', 'none');
        $(document.body).css('userSelect', 'none');
        var is_x = $(this).attr('id') == 'mov-x';
        $$.mouse = function (pos) {
            $$.switch(
                is_x ? pos.X - 150 : $$.F.W,
                is_x ? $$.F.H : pos.Y - 27
            );
        };
    });
    var v = Vls('main');
    if (v) {
        eval('$$.switch(' + v + ')');
    } else {
        $$.menu('t')
    }
});
