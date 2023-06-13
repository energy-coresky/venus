<?php

class m_tools extends Model_m
{
    private $addr;

    function head_y() {
        return $this->t_settings->head_y();
    }

    function _tcolors($p) {
        '' === $_POST['p'] or $this->w_v3 = $p;
        return [
            'v3' => Tailwind::$color3,
            'list' => $list = Tailwind::$color2,
            'c' => [$c = count($list), floor($c / 2)],
            'popup_c' => $this->m_venus->popup_c(),
            'v2_ary' => $this->m_venus->v2_ary(),
            'rename' => ['amber' => 'yellow', 'emerald' => 'green', 'violet' => 'purple'],
        ];
    }

    function _hcolors($p) {
        $list = HTML::$colors;
        $p AND sort($list);
        return ['list' => $list];
    }

    function _palette($p) {
        return ['v3' => Tailwind::$color3];
    }

    function _ruler($p) {
        return ['list' => ''];
    }

    function _box($p) {
        return ['list' => ''];
    }

    function _css($p) {
        return [
            'css' => tag(html(json_encode(m_venus::css($p))), 'id="css-data" style="display:none"'),
            'list' => m_venus::$css,
        ];
    }

    function _text($p) {
        return ['sizes' => Tailwind::$size];
    }

    function _pseudo($p) {
        $m = new t_venus('pseudo');
        return [
            'grp' => $m->sqlf('@select grp from $_ group by grp'),
            'evar' => $m->all(true),
        ];
    }

    function _unicode($p) {
        $fonts = ['arial', 'verdana', 'serif', 'cursive', 'monospace'];
        $m = new t_venus('unicode');
        if ($p)
            $m->sqlf('update $_ set priority=9 where id=%d', $p);
        $sql = '@select name,$cc("i$.cp=",id,";i$.unicode()") from $_ where priority=%d order by id';
        return [
            //'opt' => option(0, $opt),//$opt = $m->sqlf('@select id, name from $_ where priority=1 order by id');
            'fonts' => option(0, array_combine($fonts, $fonts)),
            'menu' => view('venus.popup_menu', ['menu' => ['muni',
                ['Arrows', $m->sqlf($sql, 3), m_venus::$rar],
                '',
                ['ASCII', 'i$.cp=0;i$.unicode()'],
                ['Кириллица', 'i$.cp=1024;i$.unicode()'],
                ['Символы валют', 'i$.cp=8352;i$.unicode()'],
                ['Буквоподобные символы', 'i$.cp=8448;i$.unicode()'],
                ['Разные символы', 'i$.cp=9728;i$.unicode()'],
                '',
                ['Mathematics', $m->sqlf($sql, 2), m_venus::$rar],
                ['Figures', $m->sqlf($sql, 7), m_venus::$rar],
                ['Other', $m->sqlf($sql, 1), m_venus::$rar],
            ]]),
        ];
    }

    function _icons($p) {
        $src = 'C:/web/tw/node_modules/bootstrap-icons/icons/*.svg';
        $list = [];
        foreach (glob($src) as $i => $fn) {
            if ($i < $p)
                continue;
            $list[basename($fn, '.svg')] = file_get_contents($fn);
            if (count($list) > 149)
                break;
        }
        return ['list' => $list];
    }

    function _($p) {
    }
}
