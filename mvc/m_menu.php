<?php

class m_menu extends Model_m
{
    static $rar = '<span style="font-family:Verdana;">►</span>';

    static function __callStatic($name, $args) {
        return view('venus.popup_menu', [
            'id' => $name,
            'menu' => call_user_func_array([new self, "_$name"], $args),
        ]);
    }

    function _v_links($ary) {
        $out = [];
        foreach ($ary as $one => $a) {
            [$tag, $attr] = explode('-', $one, 2);
            $sub = [];
            foreach ($a as $link => $n) {
                $sub[html($link) . " ($n)"] = '';
            }
            $out[] = ["&lt;$tag $attr...&gt;", $sub, self::$rar];
        }
        return $out;
    }

    function _muni($m) {
        $sql = '@select name,$cc("i$.cp=",id,";i$.unicode()") from $_ where priority=%d order by id';
        return [
            ['Favorite Pages', [], self::$rar],
            ['Favorite Symbols', ''],
            '',
            ['Project Fonts', [], self::$rar],
            '',
            ['ASCII', 'i$.cp=0;i$.unicode()'],
            ['Кириллица', 'i$.cp=1024;i$.unicode()'],
            ['Символы валют', 'i$.cp=8352;i$.unicode()'],
            ['Буквоподобные символы', 'i$.cp=8448;i$.unicode()'],
            ['Разные символы', 'i$.cp=9728;i$.unicode()'],
            '',
            ['Arrows', $m->sqlf($sql, 3), self::$rar],
            ['Mathematics', $m->sqlf($sql, 2), self::$rar],
            ['Figures', $m->sqlf($sql, 7), self::$rar],
            ['Other', $m->sqlf($sql, 1), self::$rar],
        ];
    }

    function _v_sourses($m) {
        $sql = '@select name, $cc("$$.test(\':",id,"\')") from $_';
        return [
            ['Add new file', '', 'Alt + N'],
            ['Add new component', '', 'Alt + C'],
            ['Components', $m->sqlf($sql), self::$rar],
            ['Files', m_venus::files(), self::$rar],
            '',
            ['Delete current file/component', ''],
            '',
            ['Buttons', [], self::$rar],
            ['Alerts', [], self::$rar],
            ['Tooltips', [], self::$rar],
            ['Switches', [], self::$rar],
            ['Popovers', [], self::$rar],
            ['Progress', [], self::$rar],
            ['Spinners', [], self::$rar],
        ];
    }

    function _v_history() {
        return [
            ['Gray', $qq=['123' => '1', '234' => '2', '345' => '3'], self::$rar], '',
            ['Red', $qq, self::$rar],
            ['Green', $qq, self::$rar],
            ['Blue', $qq, self::$rar], '',
        ];
    }

    function _other_col() {
        $white = tag('', 'class="inline px-10 text-xs bg-white border-2 relative", style="top:0px; margin:0 7px 3px 0"');
        $silver = tag('','class="inline px-10 text-xs bg-white border-2 relative", style="top:0px; margin:0 7px 3px 0; background-color:silver"');
        $black = tag('', 'class="inline px-10 text-xs bg-black border-2 relative", style="top:0px; margin:0 7px 3px 0"');
        return [
            ['white', '', "$white<code>#FFFFFF</code>"],
            ['silver', '', "$silver<code>#C0C0C0</code>"],
            ['black', '', "$black<code>#000000</code>"], '',
            ['Delete Color', ''], '',
            ['Current Color', ''],
            ['Transparent', ''],
            ['Inherit', ''],
        ];
    }
}
