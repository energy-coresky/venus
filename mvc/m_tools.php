<?php

class m_tools extends Model_m
{
    function head_y() {
        return $this->t_settings->head_y();
    }

    function model($name) {
        if (is_array($ary = $this->{"_$name"}()))
            $ary += [
                'media' => ['---'] + m_venus::$media,
            ];
        return $ary;
    }

    function _tcolors() {
        '' === $this->y3[0] or $this->w_v3 = $this->y3[0];
        return [
            'v3' => Tailwind::$color3,
            'list' => $list = Tailwind::$color2,
            'c' => [$c = count($list), floor($c / 2)],
            'menus' => m_menu::v_history() . m_menu::other_col(),
            'v2_ary' => $this->m_venus->v2_ary(),
            'rename' => ['amber' => 'yellow', 'emerald' => 'green', 'violet' => 'purple'],
        ];
    }

    function _hcolors() {
        $list = HTML::$colors;
        $this->y3[0] AND sort($list);
        return ['list' => $list];
    }

    function _palette() {
        return ['v3' => Tailwind::$color3];
    }

    function _ruler() {
        return ['list' => ''];
    }

    function _box() {
        return ['list' => ''];
    }

    function _css() {
        return [
            'css' => tag(html(json_encode(m_venus::css($this->y3[0]))), 'id="css-data" style="display:none"'),
            'list' => m_venus::$css,
        ];
    }

    function _text() {
        return ['sizes' => Tailwind::$size];
    }

    function _syntax() {
        $m = new t_venus('tw');
        return [
            'grp' => $m->sqlf('@select grp from $_ where tw_id=1 group by grp'),
            'evar' => $m->all(['tw_id=' => 1], '*', '&'),
        ];
    }

    function _unicode() {
        $fonts = ['arial', 'verdana', 'serif', 'cursive', 'monospace'];
        $m = new t_venus('unicode');
        if ($this->y3[0])
            $m->sqlf('update $_ set priority=9 where id=%d', $this->y3[0]);
        return [
            //'opt' => option(0, $opt),//$opt = $m->sqlf('@select id, name from $_ where priority=1 order by id');
            'fonts' => option(0, array_combine($fonts, $fonts)),
            'menu' => m_menu::muni($m),
        ];
    }

    function _icons() {
        if ('lic' == $this->y3[0]) {
            echo pre($this->sqlf("+select txt from icon where id=%d", $this->y3[1]), 'style="padding:10px"');
            return true;
        }
        $set = MVC::$_y['y_3'][1] = '' === $this->y3[1] ? 1 : $this->y3[1];
        $where = 'where parent_id' . ($set ? '=' : '!=');
        $attr = 'onchange="i$.ip=0;i$.load(this,\'0.\' + this.value)"';
        return [
            'srcs' => option($set, $this->sqlf('@select id,name from icon where parent_id=0'), $attr),
            'list' => $this->sqlf("@select name,txt from icon $where %d limit %d, 150", $set, $this->y3[0]),
        ];
    }

    function _() {
        return [];
    }
}
