<?php

class t_settings extends Model_t
{
    protected $table = 'memory';

    public $t;//sky sql "insert into memory values(null,'palette',null,0,null,'',null)" w venus

    private $menu = [];
    private $ary = [];
    private $form_data = [];

    function head_y() {
        if ($this->dd)
            return $this->dd;
        $dd = $this->t_venus->head_y();
        if ('all' == $this->_2) {
            $this->t =& $this->t_venus->w;
        } else {
            $this->t =& m_venus::ghost($dd, $this->_2);
        }
        return $dd;
    }

    function clk($u) {
        return "ajax('settings&$this->_2=$u', $('#f1').serialize(), box)";
    }

    function model($name) {
        if ($this->y3[0] == 'save') {
            isset($_POST['ta'])
                ? $this->update(['txt' => MVC::$_y['y_txt'] = $_POST['ta']], $this->t[2])
                : call_user_func(['SKY', 'all' == $name ? 'w' : 't'], $_POST);
        }
        $this->form_data = $this->t[0];
        if ($form = $this->{"_$name"}($u))
            $form += [99 => ['Save', 'button', 'class="btn-blue" onclick="' . $this->clk($u ?? 'save.0') . '"']];
        return $this->ary + [
            'form' => Form::A($this->form_data, $form),
            'title' => 'all' == $name ? 'Venus' : ucfirst($name),
            'color' => 'all' == $name ? 'pink' : 'blue',
            'txt' => $this->t[1],
            'left_w' => $this->menu ? 150 : 0,
            'menu' => $this->menu,
        ];
    }

    function _all(&$u = null) {
        $this->menu = [
            'q' => 'Список',
            'a' => 'Настройки',
            's' => 'Test 1',
            'f' => 'Keyboard Shortcuts',
        ];
        return [
            'tailwind' => ['Tailwind'],
            'pref' => ['Venus prefix for integrated classes'],
            'tab_html' => ['HTML Tab size', 'number', '', 2],
            'tab_php' => ['PHP Tab size', 'number', '', 4],
        ];
    }

    function _syntax(&$u = null) {
        $this->menu = [
            'form' => 'Common',
            'pseudo' => 'Pseudo',
            'classes' => 'Classes',
        ];
        if ('classes' == $this->y3[2]) {
            $id = $this->y3[3];
            $m = new t_venus('tw');
            if ('put' == $this->y3[0]) {
                $_POST['!dt_u'] = '$now';
                $_POST['css'] = $_POST['url'] = '';
                $id ? $m->update($_POST, $id) : ($id = $m->insert($_POST));
            }
            $this->form_data = $id ? $m->one($id) : [];
            $this->ary['list'] = $m->all(qp('tw_id=0 order by name'));
            $u = "put.0.classes.$id";
            return [
                'tw_id' => 0, # classes
                ['ID', 'ni', $id ?: 'New Item'],
                'grp' => ['Group', 'select', array_combine($a = m_venus::$css_tpl_grp, $a)],
                'name' => ['Name', '', 'style="width:50%"'],
                'tpl' => ['Template', 'textarea_rs', 'style="width:90%" rows="7"'],
            ];
        }
        return [
            
        ];
    }

    function _tcolors(&$u = null) {
        return [
            'vcolor' => ['Color palette', 'radio', ['Show both', 'V2 only', 'V3 only']],
        ];
    }

    function _hcolors(&$u = null) {
        return [
            
        ];
    }

    function _palette(&$u = null) {
        return [
            
        ];
    }

    function _ruler(&$u = null) {
        return [
            
        ];
    }

    function _box(&$u = null) {
        return [
            
        ];
    }

    function _css(&$u = null) {
        return [
            
        ];
    }

    function _text(&$u = null) {
        return [
            
        ];
    }

    function _unicode(&$u = null) {
        return [
            
        ];
    }

    function _icons(&$u = null) {
        /*        $src = 'path-to/icons/*.svg';
        $list = [];
        foreach (glob($src) as $i => $fn) {
            if ($i < $this->y3[0])
                continue;
            $list[basename($fn, '.svg')] = file_get_contents($fn);
#$this->sqlf('insert into icon values(null,%s,X,%s)', basename($fn, '.svg'), file_get_contents($fn));
            $list[basename($fn, '.svg')] = file_get_contents($fn);
            if (count($list) > 149)
                break;
        }
        //return ['list' => $list];*/
        return [];
    }

    function _(&$u = null) {
        return [
            
        ];
    }
}
