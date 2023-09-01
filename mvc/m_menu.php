<?php

class m_menu extends Model_m
{
    static $rar = '<span style="font-family:Verdana;">►</span>';
    static $dar = '<span style="font-family:Verdana;">▼</span>';
    static $pnt = '<span style="font-family:monospace;font-size:18px">✓</span>';

    static $types = [
        'Components',
        'Forms',
        'Layouts',
        'Menus',
        'Other Components',
        'Inputes',
        'Blocks',
        'Sets',
        'Tooltips',
        'Other tiny HTML', //Spinners
    ];

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

    function files() {
        $list = [];
        $list[] = LINK;
        $list[] = 'https://coresky.net/';
        return array_combine($list, array_map(function($v) {
            return "$$.test('$v')";
        }, $list));
    }

    function _v_sourses($m, $selected) {
        $list = function ($i) use ($m, $selected) {
            static $list;
            if (null == $list)
                $list = SKY::w('src')
                    ? call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_b"], ['main', 'venus/*'])
                    : $m->sqlf('@select id, $cc(flag,"-",name) from $_ order by flag');
            $ary = [];
            foreach ($list as $id => $name) {
                $name = basename($name, '.html');
                if ($i != $name[0])
                    continue;
                $key = substr($name, 2);
                if ($selected == $key)
                    $key = "<b>$key</b>" . tag(self::$pnt, 'class="float-right"', 'span');
                $ary[$key] = sprintf("$$.test('%s%s')", SKY::w('src') ? '~' : ':', SKY::w('src') ? $name : $id);
            }
            return $ary;
        };
        $out = [
            ['Add new component', '', 'Alt + N'],
            ['Delete current', '', 'Alt + D'],
            ['Links', $this->files(), self::$rar],
            ['History', $this->t_venus->history(), self::$rar],
            '',
            ['Venus sourses', 'ajax("set&src=0")', SKY::w('src') ? '' : self::$pnt],
            ['Application sourses', 'ajax("set&src=1")', SKY::w('src') ? self::$pnt : ''],
            '',
        ];
        foreach (m_menu::$types as $i => $type) {
            $out = array_merge($out, [[$type, ($ary = $list($i)) ?: '', count($ary) . ' ' . self::$rar]]);
            '4' != $i or $out = array_merge($out, ['']);
        }
        return $out;
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
