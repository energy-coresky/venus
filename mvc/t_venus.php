<?php

class t_venus extends Model_t
{
    protected $table = 'preset';
    public $w;

    function head_y() {
        static $dd;
        if ($dd)
            return $dd;
        SKY::$databases += Plan::app_r('conf.php')['app']['databases'];
        $this->w =& m_venus::ghost($dd = SQL::open('w'));
        return $dd;
    }

    function maat(&$in) {
        $maat = new Maat(['highlight' => true]);
//trace(print_r($in->tree,1), 'AAA');
        return [
            'code' => $maat->code($in->tree),
            'tw_css' => (new Vesper($maat))->tw_css(),
            'page' => $maat->page,
            'menu' => m_menu::v_sourses($this),
            'fn' => $this->get($in->fn),
        ];
    }

    function get($fn, $tw = false) {
        if (':' == $fn[0]) {
            if (!$tw)
                return 'Component: <b>' . $this->cell(substr($fn, 1), 'name') . '</b>';
            //$css = $tw ? Tailwind::css() : '';
            $css = $tw ? '<script src="https://cdn.tailwindcss.com"></script>' : '';
            return $css . $this->cell(substr($fn, 1), 'tmemo');
        } elseif ($ext = strpos($fn, '/')) {
            preg_match('/^https?:/', $fn) or $fn = "https://$fn";
        } else {
            $fn = WWW . 'venus/' . basename($fn);
        }
        if (!$tw)
            return ($ext ? 'External: ' : 'File: ') . $fn;
        /*require_once 'main/w3/simple_html_dom.php';
        $node = str_get_html(unl($html));
        $node->find('body', 0)->e = 1;
        $i = 2;
        foreach($node->find('body *') as $el)
            $el->e = $i++;*/
        return $ext ? get($fn, '', false) : file_get_contents($fn);
    }

    function put($fn, $data = null) {
        ':' == $fn[0]
            ? $this->update(['tmemo' => $data], substr($fn, 1))
            : file_put_contents($fn, $data);
        return true;
    }
}
