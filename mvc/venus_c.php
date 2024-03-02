<?php

class venus_c extends Controller
{
    function a_ware() {
    }

    function head_y($action) {
        'a_ware' == $action or MVC::$layout = '';
        $this->t_venus->head_y();
        $this->y3 = explode('.', $this->_3 ?: 'open', 5) + [1 => '', '', '', ''];
        return ['y_2' => $this->_2, 'y_3' => $this->y3];
    }

    function tail_y() {
        return MVC::$layout && !$this->fly ? parent::tail_y() : null;
    }

    #function yml_c() {
    #    Yaml::directive('hash', fn($v) => "#$v");
    #}

    function jet_c() {
        Jet::directive('ob', fn() => "<?php ob_start() ?>");
        Jet::directive('quot', fn() => "<?php echo str_replace('\"', '&quot;', ob_get_clean()) ?>");
        Jet::directive('_inc', fn($v) => $this->t_venus->_inc(substr($v, 1)));
    }

    function a_page() {
        return $this->empty_a($this->_2);
    }

    function empty_a($page = '') {
        $this->_title = 'VENUS.SKY';
        $js = ['~/m/venus.js', '~/w/venus/ishtar.js', '~/w/venus/maxwell.js', '~/w/venus/grace.js'];
        Plan::tail('', $js, ['~/w/venus/vesper.css', '~/m/venus.css']);
        Plan::$head = [];

        $this->d_last_page = '_venus';
        return [
            'fsize' => option(3, array_combine(m_venus::$fsize, m_venus::$fsize)),
            'reg' => '/^<?\w[^>]*[^\/]$/',
            'page' => $page,
            'form' => m_venus::addform(),
        ];
    }

    function j_src() {
        if ($this->_2) { # step 1
            $json = unjson(file_get_contents('php://input'));
            json($this->t_venus->maat($json));
        } else { # step 0
            json([
                'html' => $this->t_venus->jet($this->_3, $tw),
                'tw' => !$tw ? $tw : $this->t_venus->tailwind(),
                'jet' => $this->t_venus->jet ?: false,
            ]);
        }
    }

    function j_set() {
        SKY::w($this->_2, $this->_3);
        return true;
    }

    function j_cls() {
        $maat = new Maat;
        $maat->add_class($_POST['n']);
        echo pre((new Vesper)->bag($maat)[0], '');
    }

    function j_samples() {
        $ary = explode('-', $_POST['n']);
        return [
            'samples' => (new Vesper)->samples(array_pop($ary)),
            'pref' => implode('-', $ary) . '-',
        ];
    }

    function j_save() {
        $html = preg_replace("~<(br\s*/?|/?div)>~is", "\n", $_POST['html']);
        $html = html_entity_decode(strip_tags($html));
        return $this->t_venus->put($_POST['fn'], $html);
    }

    function j_add() {
        $this->t_venus->add($_POST['fn'], $_POST['type'], $_POST['src']);
    }

    function j_menu() {
        $menu = m_venus::menu();
        return ['menu' => $menu[$this->_3] ?? $menu['h']];
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
