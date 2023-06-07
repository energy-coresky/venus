<?php

class m_venus extends Model_m
{
    static $fsize = ['320 x 480', '640 x 480', '768 x 768', '1024 x 555', '1366 x 768', /* notebook */ '1536 x 555'];
    static $css = ['Properties', 'Types', 'Functions', 'Pseudo-classes', 'Pseudo-elements', 'At-rules'];
    static $css_prop_grp = ['Layout', 'Text', 'Appearance', 'Animation', 'CSS Variables', 'Grid', 'Flex', 'Table', 'Generated Content', 'Other'];

#$at_rules.
#You can also browse key CSS concepts and a list of selectors organized by type. 
#Also included is a brief DOM-CSS / CSSOM reference.

    static function files() {
        $list = array_map(function($v) {
            return basename($v);
        }, glob(WWW . 'venus/*.html'));
        //$list[] = 'https://ukrposhta.ua/ru';
        $list[] = LINK;
        $list[] = LINK . 'zz2.html';
        return array_combine($list, array_map(function($v) {
            return "$$.test('$v')";
        }, $list));
    }

    static function css($p) {
        $m = new t_venus('css');
        return $m->sqlf('@select name,1 from $_ where css_id=%d', $p);
    }

    static function menu() {
        return [ // https://rogden.github.io/tailwind-config-viewer/
            'h' => [
                'Ruler' => "i$.load('ruler')",
                'HTML Colors' => "i$.load('hcolors')",
                'CSS Styles' => "i$.load('css')",
                'Unicode' => "i$.load('unicode')",
                'Tailwind' => [
                    'Colors' => "i$.load('tcolors')",
                    'Text' => "i$.load('text')",
                    'Box' => "i$.load('box')",
                    'Pseudo' => "i$.load('pseudo')",
                ],
                'Icons' => [
                    'Bootstrap' => "i$.load('icons')",
                ],
                'Alpine.js' => [
                    '2do' => "i$.load('tcolors')",
                ],
            ],
            't' => [],
            'i' => [],
        ];
    }
}
