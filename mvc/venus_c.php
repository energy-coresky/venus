<?php

class venus_c extends Controller
{
    function a_ware() {
    }

    function head_y($action) {
        'a_ware' == $action or MVC::$layout = '';
    }

    function tail_y() {
        return MVC::$layout ? parent::tail_y() : null;
    }

    function a_tailwind() {
        Tailwind::css(false);
    }

    function jet_c() {
        Jet::directive('qb', function($arg) {
            return "<?php ob_start() ?>";
        });
        Jet::directive('qe', function($arg) {
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
            'fsize' => option(3, array_combine(Venus::$fsize, Venus::$fsize)),
            'reg' => '/^<?\w[^>]*[^\/]$/',
            'page' => $page,
        ];
    }

    function a_fn() {
        echo $this->t_venus->get(end($_GET), true);
    }

    function j_code() {
        $html = $this->t_venus->get($this->_3);
        json([
            'html' => preg_replace("/&(\w+);/", '&amp;$1;', $html),
            'list' => view('venus.popup', [
                'files' => Venus::files(),
                'components' => $this->t_venus->components(),
            ]),
        ]);
    }

    function j_save() {
        $html = preg_replace("~<(br\s*/?|div)>~is", "\n", $_POST['html']);
        $html = html_entity_decode(strip_tags($html));
        return $this->t_venus->put($_POST['fn'], $html);
    }

    function j_menu() {
        $menu = Venus::menu();
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
        switch ($this->_2) {
            case 'tcolors':
                return ['list' => array_keys(Tailwind::$colors)];
            case 'hcolors':
                $list = HTML::$colors;
                if ($_POST['p'])
                    sort($list);
                return ['list' => $list];
            case 'ruler':
                return ['list' => ''];
            case 'box':
                return ['list' => ''];
            case 'text':
                return ['sizes' => Tailwind::$size];
            case 'unicode':
                $fonts = ['arial', 'verdana', 'serif', 'cursive'];
                return [
                    'opt' => option(0, unserialize(view('tool.lang', []))),
                    'fonts' => option(0, array_combine($fonts, $fonts)),
                ];
            case 'icons':
                $src = 'C:/web/tw/node_modules/bootstrap-icons/icons/*.svg';
                $list = [];
                foreach (glob($src) as $i => $fn) {
                    if ($i < $_POST['p'])
                        continue;
                    $list[basename($fn, '.svg')] = file_get_contents($fn);
                    if (count($list) > 149)
                        break;
                }
                return ['list' => $list];
        }
    }
}
