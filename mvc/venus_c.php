<?php

class venus_c extends Controller
{
    function a_ware() {
    }

    function head_y($action) {
        'a_ware' == $action or MVC::$layout = '';
        $this->y3 = explode('.', $this->_3, 5) + ['', '', '', '', ''];
        return ['y_2' => $this->_2, 'y_3' => $this->y3];
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

    function a_src() {
        echo $this->t_venus->get(end($_GET), true);
    }

    function j_src() {
        #$v = new Vesper;
        #Plan::_p('mvc/venus-do.html', var_export($v->in($_POST['doc'][0]), 1));
   #Plan::_p('mvc/venus-1.html', $_POST['doc'][1][0]);
        $css = trim(substr($q = Maat::parse_css($_POST['doc'][1][0]), 6040));// 6040
   #Plan::_p('mvc/venus-2.html', $q);
        json([
            'css' => html("<style>\n$css\n</style>\n"),
            //'html' => preg_replace("/&(\w+);/", '&amp;$1;', $this->t_venus->get($this->_3)),
            'list' => view('venus.popup_menu', ['menu' => ['project-files',
                ['Add new file', '', 'Alt + N'],
                ['Add new component', '', 'Alt + C'],
                ['Components', $this->t_venus->components(), m_venus::$rar],
                '',
                ['Delete current file/component', ''],
                '',
                ['Files', m_venus::files(), m_venus::$rar],
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
        MVC::$layout = '_settings.layout';
        MVC::body("settings.$this->_2");
        return $this->t_settings->model($this->_2);
    }

    function j_tool() {
        MVC::$layout = '_venus.tool';
        MVC::body("tool.$this->_2");
        return $this->m_tools->model($this->_2);
    }
}
