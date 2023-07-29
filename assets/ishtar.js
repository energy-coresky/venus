
var i$ = {
    ip: 0,
    t: {}, // tools
    load: function(tool, p3, post) {
        if ('object' == typeof tool)
            tool = $(tool).parents('.tool:first')[0].id.slice(2);
        if ('boolean' == typeof p3)
            p3 = p3 ? 1 : 0;
        p3 = 'undefined' == typeof p3 ? '' : '=.' + p3;
        ajax('tool&' + tool + p3, post || {}, function(r) {
            var t = $($$.div ? '#tail' : '#v-right');
            t.find('#t-' + tool).remove();
            t.prepend(r);
        });
    },
    push: function(el, s) {
        $(el).parent().find('a').removeClass('active');
        $(el).addClass('active');
        ajax(s, function(r) {
            $('#s-form').html(r)
        });
    },
    samples: function(el, cls) {
        $(el).parent().find('a').removeClass('active');
        $(el).addClass('active');
        ajax('samples', {n: cls}, function(r) {
            $('#f1').next().html(r)
        });
    },
    word: function(word) {
        ajax('settings&syntax=open.0.browse', {n:word}, box)
    },
    cls: function(el, name) {
        $(el).parent().find('a').removeClass('active');
        $(el).addClass('active');
        ajax('cls', {n: name}, 's-bottom-form');
        $('#s-bottom-form').show();
    },
    css: function(el, css_data, p) {
        var i = 1, s = '', name, td, list = getComputedStyle(el);
        if (1 == p) {
            for (; i - 1 < list.length; i++) {
                name = td = list[i - 1];
                if (1 === css_data[name]) {
                    td = '<b>' + td + '</b>';
                    css_data[name] = false;
                }
                s += '<tr class="even"><td width="30">' + i + '</td><td width="35%">'
                    + td + '</td><td>' + list.getPropertyValue(name) + '</td></tr>';
            }
        }
        Object.keys(css_data).forEach(function (name) {
            if (false !== css_data[name])
                s += '<tr class="even"><td width="30">' + i + '</td><td width="35%"><b>'
                + name + '</b></td><td>' + css_data[name] + '</td></tr>';
            i++;
        });
        $('#css').html(s);
        //$('#info span:eq(2)').html(s);//css_data.length
    },
    cp: 0,
    unicode: function(dir) {
        if (dir)
            i$.cp += dir * 128;
        if (i$.cp < 0)
            i$.cp = 0x10FF00;
        if (i$.cp > 0x10FFFF)
            i$.cp = 0;
        var i, out = '<table id="uni-font" style="font-family:arial"><tr><td></td>';
        for (i = 0; i < 16; i++)
            out += '<td class="text-center text-white bg-gray-500">' + (i < 10 ? i : String.fromCodePoint(i + 55)) + '</td>';
        out += '<td></td></tr>';
        for (i = 0; i < 8; i++) {
            out += `<tr><td class="text-center text-white bg-gray-500">${i}</td>`;
            for (var k = 0; k < 16; k++) {//&#9763;
                out += '<td class="uni-td">' + String.fromCodePoint(i$.cp + 16 * i + k); + '</td>';
            }
            out += i ? '</tr>' : '<td rowspan="8" style="width:240px;font-size:200px; text-align:center"></td></tr>';
        }
        $('#unicode').html(out + '</table>' + i$.cp);
        $('#uni-font .uni-td').css({cursor:'default'}).on('mouseenter', function() {
            $('#uni-font td[rowspan=8]').html($(this).html())
        });
    },
    tcolors: function() {
        var lock = false;
        $('#tcolors td.use-c').each(function() {
            $(this).css({
                cursor:'default'
            }).on('click', function() {
                $(this).css('textDecoration', 'underline');
                lock = !lock;
               return;
                var bg = i$.get(this, 'bg-'), el = $('#tcolors').find('td:eq(1)')[0], el0 = $(el).prev()[0];
                el0.className = el.className;
                el0.innerHTML = el.innerHTML;
                $(el0).css('color', $(el).css('color'));
                i$.set(el, bg, 3);
                var hex = i$.rgb2hex(i$.style(this, 'background-color'));
                $(el).html(hex + ' ' + bg).css('color', i$.style(this, 'color'));
      
                     //       if($$.$tk) i$.set($$.$tk[0], bg, 3);
      
            }).on('mouseenter', function() {
                if (lock)
                    return;
                var el, tt = this, next = $(tt.nextSibling).hasClass('use-c'), x, ary = [],
                    tr = tt.parentNode, pr = tr.previousSibling, nr = tr.nextSibling;
                if ((el = tt.previousSibling) && $(el).hasClass('use-c'))
                    tt = el;
                if (!next)
                    tt = tt.previousSibling;
                for (var n = -1; tt && $(tt).hasClass('use-c'); n++)
                    tt = tt.previousSibling;
                if (pr && $(pr).hasClass('use-c'))
                    tr = pr;
                if (!nr)
                    tr = tr.previousSibling;
                for (var i = 0; i < 3; i++) {
                    el = $(tr).find('td.use-c:eq(' + n + ')')[0];
                    for (var j = 0; j < 3; j++) {
                        ary.push(el);
                        if (el === this)
                            x = i * 3 + j;
                        el = el.nextSibling;
                    }
                    tr = tr.nextSibling;
                }
                $('#big-colors td').each(function(i) {
                    let bg = i$.style(ary[i], 'background-color');
                    this.style.backgroundColor = bg;
                    let cls = i == x ? 'c' : 'h', html = '<br>' + $(ary[i]).attr('c') + '-' + ary[i].innerHTML;
                    this.innerHTML = '<div class="' + cls + '-over">' + i$.rgb2hex(bg) + html + i$.twopa + '</div>';
                });
                
//                var bg = i$.get(this, 'bg-'), el = $('#color-run')[0];
  //              i$.set(el, bg, 3);
    //            bg = i$.rgb2hex(i$.style(this, 'background-color'));
      //          $(el).html(bg).css('color', i$.style(this, 'color'));
            })
        });
        $('#tcolors td.use-c:last').mouseenter();
    },
    opacity: function(el) {
        $('#tcolors tr.use-c').each(function() {
            var cell = $(this).find('td:last')[0];
            var c = i$.style(cell, 'background-color');
            var a = /(\d+),\s?(\d+),\s?(\d+)/.exec(c), opa = parseInt(el.value) / 100;
          //$('#info span:eq(2)').text(opa);
            cell.style.backgroundColor = `rgba(${a[1]}, ${a[2]}, ${a[3]}, ${opa})`;
        });
    },
    palette: function(el, d) {
        if (el)
            d ? $(el).next().val(el.value) : $(el).prev().val(el.value);
        var rgb = $('#t-palette input[type=range]').val(),
            hue = $('#t-palette input:eq(0)').val(),
            sat = $('#t-palette input:eq(2)').val(), s = '';
        for (var i = 0; i < 11; i++) {
            var li = 100 - 8 - i * 8, fc = i > 6 ? 'ddd' : '000';
            s += `<td class="palette" style="color:#${fc};background:hsl(${hue} ${sat}% ${li}%)"></td>`;
        }
        $('#palette').html(s).find('td').each(function(i) {
            i = 10 == i ? 950 : (i ? (100 * i) : 50);
            $(this).html(i + '<br>' + i$.rgb2hex(i$.style(this, 'background-color')));
        });
    },
    get: function(el, pref) {
        var i, ary = el.className.split(' '), len = pref.length;
        for (i in ary)
            if (pref == ary[i].substr(0, len))
                return ary[i];
        return '';
    },
    set: function(el, name, len) {
        var ary = [], pref = name.substr(0, len);
        if (el.className)
            ary = el.className.split(' ');
        for (i in ary)
            if (pref == ary[i].substr(0, len))
                $(el).removeClass(ary[i])
        $(el).addClass(name)
    },
    style: function(el, name) {
        return getComputedStyle(el, null).getPropertyValue(name)
    },
    twopa: '',
    rgb2hex: function(r, g, b) {
        var opacity = '';
        i$.twopa = '';
        if (!g) {
            var ary = /(\d+),\s?(\d+),\s?(\d+),? ?([\d\.]*)/.exec(r);
            if ('' !== ary[4]) {
                i$.twopa = '/' + Math.round(parseFloat(ary[4]) * 100);
                opacity = Math.round(parseFloat(ary[4]) * 256 + 256).toString(16).slice(1);
            }
            r = parseInt(ary[1]), g = parseInt(ary[2]), b = parseInt(ary[3]);
        }
        return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1) + opacity;
    }
};
