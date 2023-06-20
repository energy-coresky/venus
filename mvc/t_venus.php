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
            'code' => array_merge([$html = $maat->buildHTML($in->tree)], $maat->page_css, $maat->page_js),
            'lines' => $this->lines($html),
            'pos_js' => 1 + count($maat->page_css),
            'tw_css' => (new Vesper($maat))->tw_css(),
            'page' => $maat->page,
            'menu' => m_menu::v_sourses($this),
            'fn' => $this->get($in->fn),
        ];
    }

    function lines($html) {
        for ($n = 0, $s = '', $c = substr_count($html, "\n"); $n < $c; $n++)
            $s .= str_pad($n + 1, 3, ' ', STR_PAD_LEFT) . "\n";
        return $s;
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
