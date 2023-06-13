<?php

class t_settings extends Model_t
{
    protected $table = 'memory';
    public $t;//sky sql "insert into memory values(null,'palette',null,0,null,'',null)" w venus

    function head_y() {
        if ($this->dd)
            return $this->dd;
        $dd = $this->t_venus->head_y();
        if ('all' == $this->_2) {
            $this->t =& $this->t_venus->w;
        } else {
            $this->t =& m_venus::ghost($dd, $this->_2);
        }
        MVC::$_y += [
            'y_2' => $this->_2,
            'y_title' => 'all' == $this->_2 ? 'Venus' : ucfirst($this->_2),
            'y_color' => 'all' == $this->_2 ? 'pink' : 'blue',
            'y_txt' => $this->t[1],
            'y_w' => (object)$this->t_venus->w[0],
            'y_t' => (object)$this->t[0],
        ];
        return $dd;
    }

    function form() {
        $onclick = "ajax('settings&$this->_2=save.0', $('#f1').serialize(), box)";
        $form = $this->{"_$this->_2"}();
        $form += [99 => ['Save', 'button', 'class="btn-blue" onclick="' . $onclick . '"']];
        return ['form' => Form::A($this->t[0], $form)];
    }

    function save($s) {
trace($this->t_venus->w);
        is_string($s)
            ? $this->update(['txt' => MVC::$_y['y_txt'] = $s], $this->t[2])
            : call_user_func(['SKY', 'all' == $this->_2 ? 'w' : 't'], $s);
    }

    function _all() {
        return [
            'tailwind' => ['Tailwind'],
            'test' => ['Test'],
        ];
    }

    function _tcolors() {
        return [
            
        ];
    }

    function _hcolors() {
        return [
            
        ];
    }

    function _palette() {
        return [
            
        ];
    }

    function _ruler() {
        return [
            
        ];
    }

    function _box() {
        return [
            
        ];
    }

    function _css() {
        return [
            
        ];
    }

    function _text() {
        return [
            
        ];
    }

    function _pseudo() {
        return [
            
        ];
    }

    function _unicode() {
        return [
            
        ];
    }

    function _icons() {
        return [
            
        ];
    }

    function _() {
        return [
            
        ];
    }
}
/*

https://ukrposhta.ua/ru
https://api.jquery.com/jquery.parsehtml/
https://rogden.github.io/tailwind-config-viewer/#Spacing
 <a href="https://tailwindui.com/">tailwindui.com</a> <a href="https://tailwindui.com/preview">tailwindui.com/preview</a><br>
    <a href="https://tailwindcss.com/docs/text-color">docs</a> |
    <a href="https://tailblocks.cc/">tailblocks.cc</a><br>
    <a href="https://alpinejs.dev/start-here">alpine.js</a> |
    <a href="https://tabler-icons.io/">bootstrap-icons +++</a><br>
    <a href="https://www.tailwindtoolbox.com/starter-components">tailwindtoolbox</a> |
    <a href="https://blocks.wickedtemplates.com/">wickedtemplates</a><br>
https://github.com/svgdotjs/svg.js  | https://svgjs.dev/docs/3.0/ | https://jsfiddle.net/Fuzzy/f2wbgx5a/ | https://habr.com/ru/post/195184/
<g>, <use>, <defs> è <symbol>  https://habr.com/ru/post/230443/
https://www.w3schools.com/html/html_formatting.asp
@@@@@@@@@@ https://developer.mozilla.org/ru/docs/Web/CSS/CSS_Backgrounds_and_Borders/Box-shadow_generator
----ICONS:
https://heroicons.com/
123

*/


