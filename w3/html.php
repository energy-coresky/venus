<?php

class HTML
{
    static $colors = [
        'maroon', 'saddlebrown', 'mediumseagreen', 'indigo', 'darkorchid',
        'darkred', 'sienna', 'darkseagreen', 'darkslateblue', 'darkviolet',
        'brown', 'chocolate', 'darkolivegreen', 'slateblue', 'blueviolet',
        'firebrick', 'darkorange', 'olive', 'mediumslateblue', 'mediumpurple',
        'indianred', 'orange', 'olivedrab', 'cornflowerblue', 'purple',
        'lightcoral', 'sandybrown', 'yellowgreen', 'midnightblue', 'darkmagenta',
        'salmon', 'peru', 'darkgreen', 'navy', 'mediumorchid', 'tomato',
        'darkgoldenrod', 'green', 'darkblue', 'orchid', 'orangered',
        'goldenrod', 'forestgreen', 'mediumblue', 'violet', 'red',
        'coral', 'seagreen', 'blue', 'plum', 'crimson',
        'lightsalmon', 'limegreen', 'royalblue', 'fuchsia',
        'mediumvioletred', 'darksalmon', 'lime', 'dodgerblue', 'magenta',
        'palevioletred', 'burlywood', 'chartreuse', 'deepskyblue', 'thistle',
        'hotpink', 'tan', 'lawngreen', 'lightskyblue', 'lightpink',
        'deeppink', 'rosybrown', 'greenyellow', 'skyblue', 'pink',
        'navajowhite', 'darkkhaki', 'darkcyan', 'steelblue', 'peachpuff',
        'bisque', 'khaki', 'teal', 'cadetblue', 'mistyrose',
        'papayawhip', 'yellow', 'lightseagreen', 'mediumaquamarine', 'lavenderblush',
        'antiquewhite', 'gold', 'mediumturquoise', 'darkturquoise', 'seashell',
        'linen', 'palegoldenrod', 'turquoise', 'aqua', 'darkslategray',
        'oldlace', 'wheat', 'aquamarine', 'cyan', 'slategray',
        'floralwhite', 'moccasin', 'mediumspringgreen', 'navyblue', 'lightslategray',
        'snow', 'blanchedalmond', 'springgreen', 'lightsteelblue', 'dimgray',
        'whitesmoke', 'cornsilk', 'palegreen', 'lightblue', 'gray',
        'ghostwhite', 'lemonchiffon', 'lightgreen', 'powderblue', 'darkgray',
        'beige', 'lightgoldenrodyellow', 'honeydew', 'paleturquoise', 'silver',
        'lavender', 'lightyellow', 'mintcream', 'lightcyan', 'lightgrey',
        'white', 'ivory', 'aliceblue', 'azure', 'gainsboro',
    ];

    static $tags = [
        'html' => [
            'body', 'head',
        ], 
        'metadata' => [
            'base', 'link', 'meta', 'style', 'title',
        ],
        'section' => [
            'address', 'article', 'article', 'footer', 'header', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'main', 'nav', 'section'
        ],
        'content' => [
            'blockquote', 'dd', 'div', 'dl', 'dt', 'figcaption', 'figure', 'hr', 'li', 'ol', 'p', 'pre', 'ul',
        ],
        'text' => [
            'a', 'abbr', 'b', 'bdi', 'bdo', 'br', 'cite', 'code', 'data', 'dfn', 'em', 'i', 'kbd', 'mark', 'q',
            'rp', 'rt', 'ruby', 's', 'samp', 'small', 'span', 'strong', 'sub', 'sup', 'time', 'u', 'var', 'wbr', 'del', 'ins',
        ],
        'multimedia' => [
            'area', 'audio', 'img', 'map', 'track', 'video',
        ],
        'embed' => [
            'embed', 'iframe', 'object', 'param', 'picture', 'portal', 'source',
        ],
        'svg' => [
            'path', 'rect', 'circle', 'ellipse', 'line', 'polygon', 'polyline', 'text',
            'filters' => [
                'feBlend', 'feColorMatrix', 'feComponentTransfer', 'feComposite', 'feConvolveMatrix', 'feDiffuseLighting',
                'feDisplacementMap', 'feFlood', 'feGaussianBlur', 'feImage', 'feMerge', 'feMorphology', 'feOffset',
                'feSpecularLighting', 'feTile', 'feTurbulence', 'feDistantLight', 'fePointLight', 'feSpotLight',
            ],
        ],
        'math' => [
            
        ],
        'scripting' => [
            'canvas', 'noscript', 'script',
        ],
        'table' => [
            'table', 'caption', 'thead', 'tbody', 'tfoot', 'colgroup', 'col', 'th', 'tr', 'td',
        ],
        'forms' => [
            'form', 'input', 'label', 'select', 'optgroup', 'option', 'fieldset', 'legend',
            'button', 'textarea', 'progress', 'meter', 'output', 'datalist',
        ],   
        'interactive' => [
            'details', 'dialog', 'menu', 'summary',
        ],
        'component' => [
            'slot', 'template',
        ],
        'deprecated' => [
            'acronym', 'applet', 'basefont', 'bgsound', 'big', 'blink', 'center', 'content', 'dir', 'font', 'frame', 'frameset', 'hgroup', 'image',
            'keygen', 'marquee', 'menuitem', 'nobr', 'noembed', 'noframes', 'plaintext', 'rb', 'rtc', 'shadow', 'spacer', 'strike', 'tt', 'xmp',
        ],
    ];

}

