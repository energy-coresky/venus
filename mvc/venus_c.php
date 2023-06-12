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
        return MVC::$layout && !$this->fly ? parent::tail_y() : null;
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
        MVC::$layout = '_venus.settings';
        MVC::body("settings.$this->_2");
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
        MVC::$layout = '_venus.tool';
        MVC::body("tool.$this->_2");
        $this->t_venus->w();
        $y = (object)[
            'p' => $p = $_POST['p'] ?? 0,
            'name' => $this->_2,
            'w' => ['---', 'sm', 'md', 'lg', 'xl', '2xl'],
        ];
        return $this->m_tools->{"_$this->_2"}($p) + ['t' => $y];
    }
}
