<?php

class venus_c extends Controller
{
    function a_ware() {
    }

    function head_y($action) {
        'a_ware' == $action or MVC::$layout = '';
        $this->t_venus->head_y();
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
        $js = ["~/m/venus.js", "~/w/venus/ishtar.js", '~/w/venus/maxwell.js'];
        $this->_static = [[], $js, ["~/m/venus.css"]];
        $this->d_last_page = '_venus';
        return [
            'fsize' => option(3, array_combine(m_venus::$fsize, m_venus::$fsize)),
            'reg' => '/^<?\w[^>]*[^\/]$/',
            'page' => $page,
        ];
    }

    function j_src() {
        if ($this->_2) { # step 1
            $json = unjson(file_get_contents('php://input'));
            json($this->t_venus->maat($json));
        } else { # step 0
            $url = 'https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp';
          //$url = 'https://cdn.tailwindcss.com';
            json([
                'html' => $this->t_venus->get($this->_3, true, $tw),
                'tw' => $tw ? '<script src="' . $url . '"></script><script>tailwind.config={darkMode:\'class\'}</script>' : $tw,
            ]);
        }
    }

    function j_set() {
        SKY::w($this->_2, $this->_3);
        return true;
    }

    function j_cls() {
        $maat = new Maat;
        $maat->cls = [$_POST['n']];
        echo pre((new Vesper)->v_css($maat), '');
    }

    function j_samples() {
        $in = $_POST['n'];
        return [
            'samples' => (new Vesper)->samples($in),
            'pref' => $in,
        ];
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
