
var i$ = {
    ip: 0,
    t: {}, // tools
    load: function(tool, p) {
        ajax('tool&' + tool, {p:p || 0}, function(r) {
            var t = $($$.div ? '#tail' : '#v-right');
            t.find('#t-' + tool).remove();
            t.prepend(r);
        });
    },
    css: function(el) {
        var i = 0, s = '', list = getComputedStyle(el);
        for (; i < list.length; i++)
            s += (1 + i) + ' ' + list[i] + ' = ' + list.getPropertyValue(list[i]) + '<br>';
        $('#css').html(s)
    },
    cp: 0x20A0,//0xA220, 0-0x10FFFF  rule = ['0', '110 10', '1110 10 10', '11110 10 10 10'], 
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
        $('#tcolors').find('td').each(function() {
            $(this).css({
                cursor:'default'
            }).on('click', function() {
                if ($(this).hasClass('font-mono'))
                    return;
                $(this).css('textDecoration', 'underline');
                var bg = i$.get(this, 'bg-'), el = $('#tcolors').find('td:eq(1)')[0], el0 = $(el).prev()[0];
                el0.className = el.className;
                el0.innerHTML = el.innerHTML;
                $(el0).css('color', $(el).css('color'));
                i$.set(el, bg, 3);
                var hex = i$.rgb2hex(i$.style(this, 'background-color'));
                $(el).html(hex + ' ' + bg).css('color', i$.style(this, 'color'));
      
                     //       if($$.$tk) i$.set($$.$tk[0], bg, 3);
      
            }).on('mouseenter', function() {
                if ($(this).hasClass('font-mono'))
                    return;
                var bg = i$.get(this, 'bg-'), el = $('#color-run')[0];
                i$.set(el, bg, 3);
                bg = i$.rgb2hex(i$.style(this, 'background-color'));
                $(el).html(bg).css('color', i$.style(this, 'color'));
            })
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
    rgb2hex: function(r, g, b) {
        if (!g) {
            var ary = /(\d+),\s?(\d+),\s?(\d+)/.exec(r);
            r = parseInt(ary[1]), g = parseInt(ary[2]), b = parseInt(ary[3]);
        }
        return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }
};
