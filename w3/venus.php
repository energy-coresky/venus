<?php

class Venus extends Wares
{
    static $fsize = ['320 x 480', '640 x 480', '768 x 768', '1024 x 555', '1366 x 768', /* notebook */ '1536 x 555'];

    static function load($char = false) {
        static $dd;
        global $sky;
        'ware' == $sky->_1 or MVC::$layout = '';
        return $dd ?? ($dd = parent::load('w'));
    }

    static function files() {
        $list = array_map(function($v) {
            return basename($v);
        }, glob(WWW . 'venus/*.html'));
        //$list[] = 'https://ukrposhta.ua/ru';
        $list[] = LINK;
        return array_combine($list, array_map(function($v) {
            return "$$.test('$v')";
        }, $list));
    }

    static function menu() {
        return [ // https://rogden.github.io/tailwind-config-viewer/
            't' => (new Tailwind)->tools,
            'h' => [
                'HTML Colors' => "ajax('htmlcolors',$$.htmlcolors)",
                'CSS Styles' => "$$.css(document.body)",
                'UTF-8 Table' => "$$.utf8()",
            ],
            'i' => [
                'Bootstrap' => "ajax('icons',$$.icons)",
            ],
        ];
    }
}
