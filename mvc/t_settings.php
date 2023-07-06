<?php

class t_settings extends Model_t
{
    protected $table = 'memory';

    public $t;//sky sql "insert into memory values(null,'palette',null,0,null,'',null)" w venus

    private $menu = [];
    private $ary = ['section' => '', 'gen' => []];
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

    function preflight() {
        return unl($this->sqlf('+select txt from $_ where id=100'));
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
        $pre = ['', 'ni', '<pre id="s-bottom-form" class="p-2 bg-yellow-200 mt-4" style="width:98%" hidden></pre>'];
        $this->form_data = $this->t[0];
        if ($form = $this->{"_$name"}($u))
            $form += [99 => ['Go', 'button', 'class="btn-blue" onclick="' . $this->clk($u ?? 'save.0') . '"'], $pre];
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
            'pref' => ['Venus prefix for integrated classes'],
            'plan' => ['App plan for `venus` dir', 'radio', ['app', 'mem']],
            'tab_html' => ['HTML Tab size', 'number', '', 2],
            'tab_php' => ['PHP Tab size', 'number', '', 4],
            'char_len' => ['Char length for separate panel', 'number', '', 0],
            ['Media width, sm', [
                'sm' => ['', 'number', 'style="width:45px"', 640],
                'md' => ['md', 'number', 'style="width:45px"', 768],
                'lg' => ['lg', 'number', 'style="width:51px"', 1024],
                'xl' => ['xl', 'number', 'style="width:51px"', 1280],
                '2xl' => ['2xl', 'number', 'style="width:51px"', 1536],
            ]],
        ];
    }

    function _syntax(&$u = null) {
        $this->menu = [
            'form' => 'Common',
            '' => 'Search',
            'values' => 'Values',
            'classes' => 'Classes',
        ];
        $vesp = new Vesper;
        $ary = $vesp->list($_POST['n'] ?? '');
        sort($ary[0]);
        sort($ary[1]);
        $this->ary['vlist'] = $ary[0];
        $this->ary['vword'] = $ary[1];

        if (in_array($this->y3[2], ['classes', 'values'])) {
            $tw_id = 'classes' == $this->y3[2] ? 0 : 2;
            $id = $this->y3[3];
            $m = new t_venus('tw');
            if ('put' == $this->y3[0]) {
                $_POST['!dt_u'] = '$now';
                $_POST['css'] = '';
                $id ? $m->update($_POST, $id) : ($id = $m->insert($_POST));
            } elseif ('open' == $this->y3[0] && $id) {
                MVC::$layout = '';
                MVC::body("settings.form_only");
            }
            $this->form_data = $id ? $m->one($id) : [];
            if ($id && !$tw_id)
                $this->ary['gen'] = (new Vesper)->genClass((object)$this->form_data);
            $this->ary['list'] = $m->all(qp('tw_id=$. order by name', $tw_id));
            $this->ary['section'] = $this->y3[2];
            $u = "put.0.classes.$id";//$tw_id
            return [
                'tw_id' => $tw_id,
                ['ID', 'ni', $id ?: 'New Item'],
                'grp' => ['Group', 'select', array_combine($a = m_venus::$css_tpl_grp, $a)],
                'name' => [$tw_id ? 'ValueName' : 'Name', '', 'style="width:50%"'],
                'comp' => [$tw_id ? 'DefaultValue' : 'Composite', '', 'style="width:50%"'],
                'tpl' => ['Template', 'textarea_rs', 'style="width:98%" rows="21"'],
            ];
        }
        $this->ary['section'] = 'Found:';
        $this->ary['list'] = $ary[0];
        $this->ary['is_ok'] = function ($v) {
            return preg_match("/^[\w\-]+$/", $v);
        };
        return [
            's' => ['Search', '', 'style="width:50%"'],
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
