<?php

class Tailwind
{
    static function css($is_lnk = true) {
        $css = SKY::w('tailwind') ?: __DIR__ . '/../assets/tailwind.css';
        if ($is_lnk)
            return css(['_venus?tailwind=' . SKY::s('statp')]);
        MVC::mime('text/css');
        readfile($css);
    }

    static $tools = [
        'Colors' => "ajax('colors',$$.colors)",
        'Dimension' => [
            'Width' => '',
            'Height' => '',
            'Padding' => '', // all top-right-botton-left horizontal-vertical
            'Margin' => '',
            'Negative Margin' => '',
            'Min Width' => '',
            'Max Width' => '',
            'Min Height' => '',
            'Max Height' => '',
        ],
        'Text' => [
            'Font Size' => "ajax('text',$$.text)",
            'Font Weight' => '',
            'Letter Spacing' => '',
            'Line Height' => '',
        ],
        'Border' => [
            'Radius' => '',
            'Width' => '',
        ],
        'Other' => [
            'Screens' => '',
            'Shadows' => '',
            'Opacity' => '',
            'Transitions' => '',
        ],
    ];

    static $colors = [
        'gray' => '', // 50, 100, 200 .. 900
        'red' => '',
        'yellow' => '',
        'green' => '',
        'blue' => '',
        'indigo' => '',
        'purple' => '',
        'pink' => '',
        #'' => '',
  #      'black' => '', //
     #   'white' => '',
    ];

    static $size = [
        'xs',
        'sm',
        'base',
        'lg',
        'xl',
        '2xl',
        '3xl',
        '4xl',
        '5xl',
        '6xl',
        '7xl',
        '8xl',
        '9xl',
    ];

    private $pad = [
        '4 direction' => 'm',   // -m- -mx- p- px-  margin(2) padding(1)
        'left+right' => 'mx',
        'top+bottom' => 'my',
        'top' => 'mt',
        'right' => 'mr',
        'botom' => 'mb',
        'left' => 'ml',
    ];

    function dimensions() {
        $left = range(0, 12);
        array_push($left, 14, 16);
        $left = array_merge($left, range(20, 64, 4));
        array_push($left, 72, 80, 96);
        $left[] = 'auto';
        $left[] = 'px';
        $left[] = '0.5';
        $left[] = '1.5';
        $left[] = '2.5';
        $left[] = '3.5';
        $left[] = '1/2';
        $left[] = '1/3';
        $left[] = '2/3';
        $left[] = '1/4';
        $left[] = '2/4';
        $left[] = '3/4';
        $left[] = 'full';
    }


    private $groups = [
        'w' => 'Width',
        'h' => 'Height',
        'bg' => 'Background',
        'font' => '',
        'text' => '',
        'border' => '',
        'z' => 'Z-index',

        '-inset' => '',
        '-top' => '',
        '-right' => '',
        '-bottom' => '',
        '-left' => '',

        '-translate' => '',
        '-rotate' => '',
        '-skew' => '',
        '-space' => '',
        '-hue' => '',
        '-backdrop' => '',
        'max' => '',
        'min' => '',
        'gap' => '',
        'line' => '',
        'opacity' => '',
        'align' => '',
        'shadow' => '',
        'rounded' => '',
        'stroke' => '',

        'flex' => '',
        'order' => '',
        'col' => '',
        'row' => '',

        'focus-within:' => '',
 /*
        'btn' => '',
        'sr' => '',
        'not' => '',*/

        'focus' => '',
        'pointer' => '',
        'isolation' => '',

        'float' => '',
        'clear' => '',

        'box' => '',
        'inline' => '',
        'table' => '',
        'flow' => '',
        'list' => '',

        'origin' => '',
        'transform' => '',
        'scale' => '',
        'animate' => '',
        'cursor' => '',
        'select' => '',
        'resize' => '',
        'appearance' => '',
        'auto' => '',
        'grid' => '',
        'place' => '',
        'content' => '',
        'items' => '',
        'justify' => '',

        'divide' => '',
        'self' => '',
        'overflow' => '',
        'overscroll' => '',
        'whitespace' => '',
        'break' => '',
        'from' => '',
        'via' => '',
        'to' => '',
        'decoration' => '',
        'fill' => '',
        'object' => '',
        'normal' => '',
        'slashed' => '',
        'lining' => '',
        'oldstyle' => '',
        'proportional' => '',
        'tabular' => '',
        'diagonal' => '',
        'stacked' => '',
        'leading' => '',
        'tracking' => '',
        'no' => '',
        'subpixel' => '',
        'placeholder' => '',
        'mix' => '',
        'outline' => '',
        'ring' => '',
        'filter' => '',
        'blur' => '',
        'brightness' => '',
        'contrast' => '',
        'drop' => '',
        'grayscale' => '',
        'invert' => '',
        'saturate' => '',
        'sepia' => '',
        'transition' => '',
        'delay' => '',
        'duration' => '',
        'ease' => '',
    ];
}

