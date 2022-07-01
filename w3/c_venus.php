<?php

class c_venus extends Controller
{
    private $layout = false;
    private $dd;

    function head_y($action) {
        global $sky, $user;
        $user = new USER;
        $link = PROTO . '://' . DOMAIN;
        define('LINK', $link . PATH);
        Plan::_r('conf.php');
        $sky->memory(3, 'w', $this->dd = SQL::open('_w'));
/*
$xx = SQL::open('xx');
$z= $xx->sqlf('@select * from $_azure');
foreach ($z as $i=>$v)
    $this->dd->sql('insert into preset @@', [
        'id'=>$i,
        'name'=>$v[0],
        'flag'=>$v[1],
        'tmemo'=>$v[2],
        'dt'=>$v[3],
    ]);
*/
    }

    function tail_y() {
        global $sky;
    }

    function empty_a() {
        //return Venus::layout();
        global $sky;
        $this->layout = true;
        MVC::$layout = '';
        MVC::body('venus.layout');
        $sky->k_title = 'Visual SKY';
        $sky->k_static = [[], ["~/venus.js"], ["~/tailwind.css", "~/venus.css"]];

        $fsize = ['320 x 480', '640 x 480', '768 x 768', '1024 x 555', '1366 x 768', /* notebook */ '1536 x 555'];
        return [
            'fsize' => option(3, array_combine($fsize, $fsize)),
            'frame' => '<iframe src="" class="w-full h-full"></iframe>',
            'reg' => '/^<?\w[^>]*[^\/]$/',
        ];
    }

    function a_fn() {
        MVC::$layout = '';
       // $sky->k_static = [[], [], ["~/tailwind.css"]];
        $this->layout = true;
        echo $this->file(end($_GET));
// return $this->empty_a();
    }

    function j_save() {
        $html = preg_replace("~<(br\s*/?|div)>~is", "\n", $_POST['html']);
        $html = html_entity_decode(strip_tags($html));
        return $this->file($_POST['fn'], $html);
    }

    function components() {
        //$list = $this->dd->sqlf('@select name, id from preset');
        return $this->dd->sqlf('@select name, $cc("az.test(\':",id,"\')") from preset');
    }

    function j_code() {
        $html = $this->file($this->_3);
        json([
            'html' => preg_replace("/&(\w+);/", '&amp;$1;', $html),
            'list' => view('venus.popup', [
                'files' => $this->files(),
                'components' => $this->components(),
            ]),
        ]);
    }

    function j_menu() {
        $tw = new Tailwind;
        $menu = [ // https://rogden.github.io/tailwind-config-viewer/
            't' => $tw->tools,
            'h' => [
                'HTML Colors' => "ajax('htmlcolors',az.htmlcolors)",
                'CSS Styles' => "az.css(document.body)",
                'UTF-8 Table' => "az.utf8()",
            ],
            'i' => [
                'Bootstrap' => "ajax('icons',az.icons)",
            ],
        ];
        $v = $this->_3;
        isset($menu[$v]) or $v = 't';
        return ['menu' => $menu[$v]];
    }
    
    function j_colors() {
        json([
            'right' => view('venus.colors', [
                'list' => array_keys($this->colors)
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
        $size = [
            'xs',
            'sm',
            'base',
            'lg',
            'xl',
            '2xl',
            '3xl',
            '4xl',
            '5xl',
            '6xl',
            '7xl',
            '8xl',
            '9xl',
        ];
        return [
            'sizes' => $size,
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
        if ($_POST)
            $this->dd->sql('update $_memory set tmemo=$+ where id=99', $_POST['s']);
        return [
            'ta' => $this->dd->sql('+select tmemo from $_memory where id=99'),
        ];
    }

    private $colors = [
        'gray' => '', // 50, 100, 200 .. 900
        'red' => '',
        'yellow' => '',
        'green' => '',
        'blue' => '',
        'indigo' => '',
        'purple' => '',
        'pink' => '',
        #'' => '',
  #      'black' => '', //
     #   'white' => '',
    ];

    private $left = [];

    function file($fn, $save = null) {
        if (':' == $fn[0]) {
            if ($save) {
                $this->dd->sqlf('update preset set tmemo=%s where id=%d', $save, substr($fn, 1));
                return true;
            }
            $css = $this->layout ? css(['~/tailwind.css']) : '';
            return $css . $this->dd->sqlf('+select tmemo from preset where id=%d', substr($fn, 1));
        } elseif (strpos($fn, '/')) {
            preg_match('/^https?:/', $fn) or $fn = "https://$fn";
        } else {
            $fn = WWW . 'tw/' . basename($fn);
        }
        /*require_once 'main/w3/simple_html_dom.php';
        $node = str_get_html(unl($html));
        $node->find('body', 0)->e = 1;
        $i = 2;
        foreach($node->find('body *') as $el)
            $el->e = $i++;*/
        if ($save) {
            file_put_contents($fn, $save);
            return true;
        }

        return file_get_contents($fn);
    }

    function files() {
        $list = array_map(function($v) {
            return basename($v);
        }, glob(WWW . 'tw/*.html'));
        $list[] = 'https://ukrposhta.ua/ru';
        return array_combine($list, array_map(function($v) {
            return "az.test('$v')";
        }, $list));
    }











///////////////////////////////
    function a_isual() { /* ====================================== */
        $this->_y = [];
        return Venus::layout();
    }
    function j_isual() {
        MVC::body('_ven.' . substr($this->_c, 2));
        return call_user_func([new Venus, $this->_c], $this->_a);
    }
}
