<?php

class venus_c extends Controller
{
    function head_y($action) {
        Venus::load('w');
    }

    function tail_y() {
        if ('ware' == $this->_1)
            return parent::tail_y();
    }

    function a_ware() {
    }

    function a_tailwind() {
        Tailwind::css(false);
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

    function j_colors() {
        json([
            'right' => view('venus.colors', [
                'list' => array_keys(Tailwind::$colors)
            ])
        ]);
    }

    function j_sortcolors() {
        return $this->j_htmlcolors(1);
    }

    function j_htmlcolors($sort = 0) {
        $list = HTML::colors();
        if ($sort)
            sort($list);
        return [
            'list' => $list,
        ];
    }

    function j_dim() {
    }

    function j_text() {
        return [
            'sizes' => Tailwind::$size,
        ];
    }

    function j_icons() {
        $src = 'node_modules/bootstrap-icons/icons/*.svg';
        $list = [];
        foreach (glob($src) as $fn) {
            $list[basename($fn, '.svg')] = file_get_contents($fn);
            if (count($list) > 49)
                break;
        }
        return [
            'list' => $list
        ];
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
}
