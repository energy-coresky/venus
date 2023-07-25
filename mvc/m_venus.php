<?php

class m_venus extends Model_m
{
    static $fsize = ['320 x 480', '640 x 480', '768 x 576', '1024 x 576', '1366 x 768', /* notebook */ '1536 x 576'];
    static $css = ['Elements', 'Properties', 'Types', 'Functions', 'Pseudo-classes', 'Pseudo-elements', 'At-rules'];
    static $media = ['', 'sm', 'md', 'lg', 'xl', '2xl']; # 640 768 1024 1280 1536

    static function &ghost($dd, $name = 'all', $char = '') {
        $char = 'all' == $name ? 'w' : 't';
        list($id, $txt, $cfg) = $dd->sqlf('-select id, txt, cfg from $_memory where name=%s', $name);
        SKY::ghost($char, $cfg, 'update $_memory set cfg=%s where id=' . $id, 0, $dd);
        $ary = [&SKY::$mem[$char][3], $txt, $id];
        return $ary;
    }

    static function media() {
        static $px = [];
        if (!$px)
            foreach (self::$media as $v)
                $px[] = $v ? SKY::w($v) : 0;
        return $px;
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
        return $m->sqlf('@select name,txt from $_ where css_id=%d', $p);
    }

    static function self() {
        $t = new t_settings;
        for ($out = '', $s = 1; $s < 6; $s++)
            $out .= $t->rw($s, 0) . "\n";
        echo $out . "\n\n";

        $path = Plan::_obj(0)->path;
        $ary = glob("$path/assets/*.js");
        $ary = array_merge($ary, glob("$path/w3/*.php"));
        $ary = array_merge($ary, glob("$path/mvc/*.php"));
        $ary = array_merge($ary, glob("$path/mvc/*.jet"));
        $maat = new Maat;
        foreach ($ary as $fn) {
            $in = file_get_contents($fn);
            $maat->parse_js($in);
        }
        echo (new Vesper)->v_css($maat);
//print_r($maat->cls);
    }

    function v2_ary() {
        return ['oo2', 'oo6', 'oo8', 'o12', 'o16', 'o17', 'o18', 'o21'];
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
