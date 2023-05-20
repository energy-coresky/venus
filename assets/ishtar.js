
var i$ = {
    ip: 0,
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
            i$.cp += dir * 256;
        if (i$.cp < 0)
            i$.cp = 0x10FF00;
        if (i$.cp > 0x10FFFF)
            i$.cp = 0;
        var out = '<table id="uni-font" style="font-family:arial">';
        for (var i = 0; i < 16; i++) {
            out += '<tr>';
            for (var k = 0; k < 16; k++) {//&#9763;
                out += '<td>' + String.fromCodePoint(i$.cp + 16 * i + k); + '</td>';
            }
            out += '</tr>';
        }
        $('#unicode').html(out + '</table>' + i$.cp);
    }
};
