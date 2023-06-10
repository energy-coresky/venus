<?php

class venus_c extends Controller
{
    static $rar = '<span style="font-family:Verdana;">►</span>';

    function a_ware() {
    }

    function head_y($action) {
        'a_ware' == $action or MVC::$layout = '';
    }

    function tail_y() {
        return MVC::$layout ? parent::tail_y() : null;
    }

    function a_tailwind() {
        Tailwind::css('venus' != $this->_2, false);
    }

    function jet_c() {
        Jet::directive('ob', function($arg) {
            return "<?php ob_start() ?>";
        });
        Jet::directive('quot', function($arg) {
            return "<?php echo str_replace('\"', '&quot;', ob_get_clean()) ?>";
        });
    }

    function a_page() {
        return $this->empty_a($this->_2);
    }

    function empty_a($page = '') {
        $this->_title = 'VENUS.SKY';
        $this->_static = [[], ["~/m/venus.js", "~/w/venus/ishtar.js"], ["~/m/venus.css"]];
        $this->d_last_page = '_venus';
        return [
            'fsize' => option(3, array_combine(m_venus::$fsize, m_venus::$fsize)),
            'reg' => '/^<?\w[^>]*[^\/]$/',
            'page' => $page,
        ];
    }

    function a_fn() {
        echo $this->t_venus->get(end($_GET), true);
    }

    function j_code() {
        json([
            'html' => preg_replace("/&(\w+);/", '&amp;$1;', $this->t_venus->get($this->_3)),
            'list' => view('venus.popup_menu', ['menu' => ['project-files',
                ['Add new file', '', 'Alt + N'],
                ['Add new component', '', 'Alt + C'],
                ['Components', $this->t_venus->components(), '<span style="font-family:Verdana;">►</span>'],
                '',
                ['Delete current file/component', ''],
                '',
                ['Files', m_venus::files(), '<span style="font-family:Verdana;">►</span>'],
            ]]),
        ]);
    }

    function j_save() {
        $html = preg_replace("~<(br\s*/?|div)>~is", "\n", $_POST['html']);
        $html = html_entity_decode(strip_tags($html));
        return $this->t_venus->put($_POST['fn'], $html);
    }

    function j_menu() {
        $menu = m_venus::menu();
        return ['menu' => $menu[$this->_3] ?? $menu['t']];
    }

    function j_settings() {
        $m = new t_venus('memory');
        if ($_POST) {
            $m->update(['tmemo' => $_POST['ta']], 99);
            SKY::w('tailwind', $_POST['tw']);
        }
        return [
            'ta' => $m->cell(99, 'tmemo'),
        ];
    }

    function j_tool() {
        MVC::body("tool.$this->_2");
        $ary = ['t' => (object)[
            'p' => $p = $_POST['p'] ?? 0,
            'name' => $this->_2,
        ]];
        $w = $this->t_venus->w();
        switch ($this->_2) {
            case 'tcolors':
                '' === $_POST['p'] or $w->w_v3 = $p;
                return $ary + [
                    'v3' => Tailwind::$color3,
                    'list' => $list = Tailwind::$color2,
                    'c' => [$c = count($list), floor($c / 2)],
                    'popup_c' => $this->m_venus->popup_c(),
                    'v2_ary' => $this->m_venus->v2_ary(),
                    'rename' => ['amber' => 'yellow', 'emerald' => 'green', 'violet' => 'purple'],
                ];
            case 'hcolors':
                $list = HTML::$colors;
                $p AND sort($list);
                return $ary + ['list' => $list];
            case 'palette':
                return $ary + ['v3' => Tailwind::$color3];
            case 'ruler':
                return $ary + ['list' => ''];
            case 'box':
                return $ary + ['list' => ''];
            case 'css':
                $ary['css'] = tag(html(json_encode(m_venus::css($p))), 'id="css-data" style="display:none"');
                return $ary + ['list' => m_venus::$css];
            case 'pseudo':
                $m = new t_venus('pseudo');
                $ary += ['grp' => $m->sqlf('@select grp from $_ group by grp')];
                return $ary + ['evar' => $m->all()];
            case 'text':
                return $ary + ['sizes' => Tailwind::$size];
            case 'unicode':
                $fonts = ['arial', 'verdana', 'serif', 'cursive', 'monospace'];
                $m = new t_venus('unicode');
                if ($p)
                    $m->sqlf('update $_ set priority=9 where id=%d', $p);
                $sql = '@select name,$cc("i$.cp=",id,";i$.unicode()") from $_ where priority=%d order by id';
                return $ary + [
                    //'opt' => option(0, $opt),//$opt = $m->sqlf('@select id, name from $_ where priority=1 order by id');
                    'fonts' => option(0, array_combine($fonts, $fonts)),
                    'menu' => view('venus.popup_menu', ['menu' => ['muni',
                        ['Arrows', $m->sqlf($sql, 3), self::$rar],
                        '',
                        ['ASCII', 'i$.cp=0;i$.unicode()'],
                        ['Кириллица', 'i$.cp=1024;i$.unicode()'],
                        ['Символы валют', 'i$.cp=8352;i$.unicode()'],
                        ['Буквоподобные символы', 'i$.cp=8448;i$.unicode()'],
                        ['Разные символы', 'i$.cp=9728;i$.unicode()'],
                        '',
                        ['Mathematics', $m->sqlf($sql, 2), self::$rar],
                        ['Figures', $m->sqlf($sql, 7), self::$rar],
                        ['Other', $m->sqlf($sql, 1), self::$rar],
                    ]]),
                ];
            case 'icons':
                $src = 'C:/web/tw/node_modules/bootstrap-icons/icons/*.svg';
                $list = [];
                foreach (glob($src) as $i => $fn) {
                    if ($i < $p)
                        continue;
                    $list[basename($fn, '.svg')] = file_get_contents($fn);
                    if (count($list) > 149)
                        break;
                }
                return $ary + ['list' => $list];
        }
    }
}
