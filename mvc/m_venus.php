<?php

class m_venus extends Model_m
{
    static $fsize = ['320 x 480', '640 x 480', '768 x 768', '1024 x 555', '1366 x 768', /* notebook */ '1536 x 555'];
    static $css = ['Properties', 'Types', 'Functions', 'Pseudo-classes', 'Pseudo-elements', 'At-rules'];
    static $css_prop_grp = ['Layout', 'Text', 'Appearance', 'Animation', 'CSS Variables', 'Grid', 'Flex', 'Table', 'Generated Content', 'Other'];
    static $css_tpl_grp = ['color', 'text', 'box', 'table', 'animation', 'Grid', 'Flex', 'Other'];
    static $rar = '<span style="font-family:Verdana;">►</span>';

#$at_rules.
#You can also browse key CSS concepts and a list of selectors organized by type. 
#Also included is a brief DOM-CSS / CSSOM reference.

    static function &ghost($dd, $name = 'all', $char = '') {
        $char = 'all' == $name ? 'w' : 't';
        list($id, $txt, $cfg) = $dd->sqlf('-select id, txt, cfg from $_memory where name=%s', $name);
        SKY::ghost($char, $cfg, 'update $_memory set cfg=%s where id=' . $id, 0, $dd);
        $ary = [&SKY::$mem[$char][3], $txt, $id];
        return $ary;
    }

    static function maat(&$in) {
        $html = Maat::html($in->tree);
        return ['html' => $html, 'lines' => 11];

        $css = ['css_n' => count($ary)];
        $m = [0, 0];
        foreach ($ary as $i => $one) {
            $len = strlen($one = trim(Maat::css($one, ['highlight' => true])));
            $css['vs-style-' . ++$i] = $one;
            if ($len > $m[1]) {
                $m[1] = $len;
                $m[0] = $i;
            }
        }
        if ($m[0])
            $css["vs-style-$m[0]"] = trim(substr($css["vs-style-$m[0]"], 0)); #6104
        return $css;
    }

    static function files() {
        $list = array_map(function($v) {
            return basename($v);
        }, glob(WWW . 'venus/*.html'));
        $list[] = LINK;
        $list[] = 'https://coresky.net/';

        return array_combine($list, array_map(function($v) {
            return "$$.test('$v')";
        }, $list));
    }

    static function css($p) {
        $m = new t_venus('css');
        return $m->sqlf('@select name,1 from $_ where css_id=%d', $p);
    }

    function v2_ary() {
        return ['oo2', 'oo6', 'oo8', 'o12', 'o16', 'o17', 'o18', 'o21'];
    }

    function popup_c() {
        $html = view('venus.popup_menu', ['menu' => ['v-history',
            ['Gray', $qq=['123' => '1', '234' => '2', '345' => '3'], m_venus::$rar], '',
            ['Red', $qq, m_venus::$rar],
            ['Green', $qq, m_venus::$rar],
            ['Blue', $qq, m_venus::$rar], '',
        ]]);
        $white = tag('', 'class="inline px-10 text-xs bg-white border-2 relative", style="top:0px; margin:0 7px 3px 0"');
        $silver = tag('','class="inline px-10 text-xs bg-white border-2 relative", style="top:0px; margin:0 7px 3px 0; background-color:silver"');
        $black = tag('', 'class="inline px-10 text-xs bg-black border-2 relative", style="top:0px; margin:0 7px 3px 0"');
        return $html . view('venus.popup_menu', ['menu' => ['other-col',
            ['white', '', "$white<code>#FFFFFF</code>"],
            ['silver', '', "$silver<code>#C0C0C0</code>"],
            ['black', '', "$black<code>#000000</code>"], '',
            ['Delete Color', ''], '',
            ['Current Color', ''],
            ['Transparent', ''],
            ['Inherit', ''],
        ]]);
    }

    static function menu() {
        return [ // https://rogden.github.io/tailwind-config-viewer/
            'h' => [
                'Ruler' => "i$.load('ruler')",
                'HTML Colors' => "i$.load('hcolors')",
                'Colors Palette' => "i$.load('palette')",
                'CSS Styles' => "i$.load('css')",
                'Tailwind' => [
                    'Colors' => "i$.load('tcolors')",
                    'Text' => "i$.load('text')",
                    'Box' => "i$.load('box')",
                    'Syntax' => "i$.load('syntax')",
                ],
                'Images & Fonts' => [
                    'Collections' => "i$.load('icons')",
                    'Create SVG' => "ajax('settings&icons=new', box)",
                    'Unicode' => "i$.load('unicode')",
                ],
                'Alpine.js' => [
                    '2do' => "i$.load('tcolors')",
                ],
            ],
            'm' => [
                'Chaos & Order' => [
                    'Analyze App' => "",
                    'Re-Build App' => "",
                ],
            ],
            'n' => [],
        ];
    }
}
