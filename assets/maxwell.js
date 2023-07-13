
var m$ = {
    css: function (el, id) {
        ajax('tool&syn_css=' + id, function (r) {
            $(el).parents('tr:first').after(r);
        });
    },
    
    on: false,
    group: false,
    last: [0, ''],
    caret: function () {
        let cls, s = document.getSelection();//let {anchorNode, anchorOffset, focusNode, focusOffset} = 
        if (!m$.on || !s.anchorNode)
            return;
        let el = s.anchorNode.parentNode, next = s.anchorNode.nextSibling;
        if ('m' != el.nodeName.toLowerCase()) {
            if (!next || 'm' != next.nodeName.toLowerCase())
                return;
            el = next;
        }
        if (el === m$.last[0])
            return;
        // start processing
        m$.last = [el, cls = el.innerText];
        i$.load('syntax', 1, {caret:cls})
        $(el).parents('pre:eq(0)').find('m').removeClass('vs-yellow')
        if (m$.group) {
            $(el).parents('pre:eq(0)').find('m').each(function () {
                $(this).text() !== cls || $(this).addClass('vs-yellow')
            })
        } else {
            $(el).addClass('vs-yellow')
        }
    }
};
