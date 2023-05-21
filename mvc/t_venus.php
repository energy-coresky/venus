<?php

class t_venus extends Model_t
{
    protected $table = 'preset';

    function head_y() {
        return Venus::open('w', 'w');
    }

    function components() {
        return sqlf('@select name, $cc("$$.test(\':",id,"\')") from $_');
    }

    function get($fn, $tw = false) {
        if (':' == $fn[0]) {
            $css = $tw ? Tailwind::css() : '';
            return $css . $this->t_venus->cell(substr($fn, 1), 'tmemo');
        } elseif (strpos($fn, '/')) {
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
        return file_get_contents($fn);
    }

    function put($fn, $data = null) {
        ':' == $fn[0]
            ? $this->update(['tmemo' => $data], substr($fn, 1))
            : file_put_contents($fn, $data);
        return true;
    }
}
