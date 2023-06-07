<?php

class Tailwind
{
    static function css($is_app = true, $is_lnk = true) {
        if ($is_lnk)
            return css(['_venus?tailwind=' . ($is_app ? SKY::s('statp') : 'venus')]);
        MVC::mime('text/css');
        readfile($is_app && SKY::w('tailwind') ? SKY::w('tailwind') : __DIR__ . '/../assets/tailwind.css');
    }

    static $tools = [
       //'Colors' => "ajax('colors',$$.colors)",
        'Colors' => "i$.load('tcolors')",
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
            'Font Size' => "i$.load('text')",
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


    static $color2 = [
        'gray'   => ['#f9fafb','#f3f4f6','#e5e7eb','#d1d5db','#9ca3af','#6b7280','#4b5563','#374151','#1f2937','#111827'],
        'red'    => ['#fef2f2','#fee2e2','#fecaca','#fca5a5','#f87171','#ef4444','#dc2626','#b91c1c','#991b1b','#7f1d1d'],
        'yellow' => ['#fffbeb','#fef3c7','#fde68a','#fcd34d','#fbbf24','#f59e0b','#d97706','#b45309','#92400e','#78350f'],
        'green'  => ['#ecfdf5','#d1fae5','#a7f3d0','#6ee7b7','#34d399','#10b981','#059669','#047857','#065f46','#064e3b'],
        'blue'   => ['#eff6ff','#dbeafe','#bfdbfe','#93c5fd','#60a5fa','#3b82f6','#2563eb','#1d4ed8','#1e40af','#1e3a8a'],
        'indigo' => ['#eef2ff','#e0e7ff','#c7d2fe','#a5b4fc','#818cf8','#6366f1','#4f46e5','#4338ca','#3730a3','#312e81'],
        'purple' => ['#f5f3ff','#ede9fe','#ddd6fe','#c4b5fd','#a78bfa','#8b5cf6','#7c3aed','#6d28d9','#5b21b6','#4c1d95'],
        'pink'   => ['#fdf2f8','#fce7f3','#fbcfe8','#f9a8d4','#f472b6','#ec4899','#db2777','#be185d','#9d174d','#831843'],
    ];
 // inherit: 'inherit',  current: 'currentColor',  transparent: 'transparent',  black: '#000',  white: '#fff',
//    $x =[50,100,200,300,400,500,600,700,800,900,950];
    static $color3 = [
        'slate'   => ['#f8fafc','#f1f5f9','#e2e8f0','#cbd5e1','#94a3b8','#64748b','#475569','#334155','#1e293b','#0f172a','#020617'],
        'gray'    => ['#f9fafb','#f3f4f6','#e5e7eb','#d1d5db','#9ca3af','#6b7280','#4b5563','#374151','#1f2937','#111827','#030712'],
        'zinc'    => ['#fafafa','#f4f4f5','#e4e4e7','#d4d4d8','#a1a1aa','#71717a','#52525b','#3f3f46','#27272a','#18181b','#09090b'],
        'neutral' => ['#fafafa','#f5f5f5','#e5e5e5','#d4d4d4','#a3a3a3','#737373','#525252','#404040','#262626','#171717','#0a0a0a'],
        'stone'   => ['#fafaf9','#f5f5f4','#e7e5e4','#d6d3d1','#a8a29e','#78716c','#57534e','#44403c','#292524','#1c1917','#0c0a09'],
        'red'     => ['#fef2f2','#fee2e2','#fecaca','#fca5a5','#f87171','#ef4444','#dc2626','#b91c1c','#991b1b','#7f1d1d','#450a0a'],
        'orange'  => ['#fff7ed','#ffedd5','#fed7aa','#fdba74','#fb923c','#f97316','#ea580c','#c2410c','#9a3412','#7c2d12','#431407'],
        'amber'   => ['#fffbeb','#fef3c7','#fde68a','#fcd34d','#fbbf24','#f59e0b','#d97706','#b45309','#92400e','#78350f','#451a03'], # v2 yellow
        'yellow'  => ['#fefce8','#fef9c3','#fef08a','#fde047','#facc15','#eab308','#ca8a04','#a16207','#854d0e','#713f12','#422006'],
        'lime'    => ['#f7fee7','#ecfccb','#d9f99d','#bef264','#a3e635','#84cc16','#65a30d','#4d7c0f','#3f6212','#365314','#1a2e05'],
        'green'   => ['#f0fdf4','#dcfce7','#bbf7d0','#86efac','#4ade80','#22c55e','#16a34a','#15803d','#166534','#14532d','#052e16'],
        'emerald' => ['#ecfdf5','#d1fae5','#a7f3d0','#6ee7b7','#34d399','#10b981','#059669','#047857','#065f46','#064e3b','#022c22'], # v2 green
        'teal'    => ['#f0fdfa','#ccfbf1','#99f6e4','#5eead4','#2dd4bf','#14b8a6','#0d9488','#0f766e','#115e59','#134e4a','#042f2e'],
        'cyan'    => ['#ecfeff','#cffafe','#a5f3fc','#67e8f9','#22d3ee','#06b6d4','#0891b2','#0e7490','#155e75','#164e63','#083344'],
        'sky'     => ['#f0f9ff','#e0f2fe','#bae6fd','#7dd3fc','#38bdf8','#0ea5e9','#0284c7','#0369a1','#075985','#0c4a6e','#082f49'],
        'blue'    => ['#eff6ff','#dbeafe','#bfdbfe','#93c5fd','#60a5fa','#3b82f6','#2563eb','#1d4ed8','#1e40af','#1e3a8a','#172554'],
        'indigo'  => ['#eef2ff','#e0e7ff','#c7d2fe','#a5b4fc','#818cf8','#6366f1','#4f46e5','#4338ca','#3730a3','#312e81','#1e1b4b'],
        'violet'  => ['#f5f3ff','#ede9fe','#ddd6fe','#c4b5fd','#a78bfa','#8b5cf6','#7c3aed','#6d28d9','#5b21b6','#4c1d95','#2e1065'], # v2 purple
        'purple'  => ['#faf5ff','#f3e8ff','#e9d5ff','#d8b4fe','#c084fc','#a855f7','#9333ea','#7e22ce','#6b21a8','#581c87','#3b0764'],
        'fuchsia' => ['#fdf4ff','#fae8ff','#f5d0fe','#f0abfc','#e879f9','#d946ef','#c026d3','#a21caf','#86198f','#701a75','#4a044e'],
        'pink'    => ['#fdf2f8','#fce7f3','#fbcfe8','#f9a8d4','#f472b6','#ec4899','#db2777','#be185d','#9d174d','#831843','#500724'],
        'rose'    => ['#fff1f2','#ffe4e6','#fecdd3','#fda4af','#fb7185','#f43f5e','#e11d48','#be123c','#9f1239','#881337','#4c0519'],
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

