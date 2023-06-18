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
            'html' => $html = $maat->buildHTML($in->tree),
            'lines' => $this->lines($html),
            'tw_css' => (new Vesper)->tw_css($maat->cls),
            'menu' => view('venus.popup_menu', ['menu' => ['project-files',
                ['Add new file', '', 'Alt + N'],
                ['Add new component', '', 'Alt + C'],
                ['Components', $this->components(), m_venus::$rar],
                '',
                ['Delete current file/component', ''],
                '',
                ['Files', m_venus::files(), m_venus::$rar],
            ]]),
        ];
    }

    function lines($html) {
        for ($n = 0, $s = '', $c = substr_count($html, "\n"); $n < $c; $n++)
            $s .= str_pad($n + 1, 3, '0', STR_PAD_LEFT) . "\n";
        return $s;
    }

    function components() {
        return $this->sqlf('@select name, $cc("$$.test(\':",id,"\')") from $_');
    }

    function get($fn, $tw = false) {
        if (':' == $fn[0]) {
            //$css = $tw ? Tailwind::css() : '';
            $css = $tw ? '<script src="https://cdn.tailwindcss.com"></script>' : '';
            return $css . $this->t_venus->cell(substr($fn, 1), 'tmemo');
        } elseif ($ext = strpos($fn, '/')) {
            preg_match('/^https?:/', $fn) or $fn = "https://$fn";
        } else {
            $fn = WWW . 'venus/' . basename($fn);
        }
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
