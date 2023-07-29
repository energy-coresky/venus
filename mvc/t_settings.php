<?php

class t_settings extends Model_t
{
    protected $table = 'memory';

    public $t;//sky sql "insert into memory values(null,'user','',null)" w venus

    private $menu = [];
    private $ary = ['section' => '', 'gen' => []];
    private $form_data = [];
    private $ttl = [1 => 'User-1', 'Preflight', 'Forms', 'User-2', 'Variables', '<b>Generated CSS</b>', 'User-3'];

    function head_y() {
        if ($this->dd)
            return $this->dd;
        $dd = $this->t_venus->head_y();
        if ('all' == $this->_2) {
            $this->t =& $this->t_venus->w;
        } else {
            $this->t =& $this->cfg($this->_2, $dd);
        }
        return $dd;
    }

    function &cfg($name = 'syntax', $dd = false) {
        static $ary, $mem = '';
        $mem == $name or $ary =& m_venus::ghost($dd ?: $this->dd, $mem = $name);
        return $ary;
    }

    function preflight() {
        $out = '';
        $cfg =& $this->cfg();
        for ($s = 1; $s < 6; $s++) {
            if ($v = $cfg[0]['vr_' . $s])
                $out .= $this->rw($s, $v);
        }
        return $out;
    }

    function clk($u) {
        return "ajax('settings&$this->_2=$u', $('#f1').serialize(), box)";
    }

    function model($name) {
        if ($this->y3[0] == 'save') {
            isset($_POST['ta'])
                ? $this->update(['txt' => $this->t[1] = $_POST['ta']], $this->t[2])
                : call_user_func(['SKY', 'all' == $name ? 'w' : 't'], $_POST);
        }
        $pre = ['', 'ni', '<pre id="s-bottom-form" class="p-2 bg-yellow-200 mt-4" style="width:98%" hidden></pre>'];
        $this->form_data = $this->t[0];
        if ($form = $this->{"_$name"}($u))
            $form += [99 => ['Go', 'button', 'name="x" class="btn-blue" onclick="' . $this->clk($u ?? 'save.0') . '"'], $pre];
        return $this->ary + [
            'form' => Form::A($this->form_data, $form),
            'title' => 'all' == $name ? 'Venus' : ucfirst($name),
            'bg_color' => 'all' == $name ? 'bg-pink-200' : 'bg-blue-200',
            'tx_color' => 'all' == $name ? 'text-pink-200' : 'text-blue-200',
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

    function rw($s, $v, $save = false) {
        static $data, $mem = -1;
        if ($mem != $v) {
            $mem = $v;
            $data = explode("~.~", unl($this->sqlf('+select txt from $_ where id=%d', 200 + $v)));
        }
        if (false !== $save) {
            $data[$s - 1] = $save;
            $this->sqlf('update $_ set txt=%s where id=%d', implode("\n~.~\n", $data), 200 + $v);
        }
        return trim($data[$s - 1]);
    }

    function _vss(&$u = null) {
        MVC::$layout = '';
        [, $s, $v] = $this->y3;
        $this->ary['ta'] = $this->rw($s, $v, $_POST['ta'] ?? false);
        $this->ary['section'] = $this->ttl[$s];
        return [];
    }

    function general() {
        $ary = [50 => '<fieldset class="border mx-3"><legend class="px-3">Vesper settings</legend>'];
        $dig = [1 => '❶', '❷', '❸', '❹', '❺', '❻', '❼'];
        
        foreach ($this->ttl as $i => $name) {
            $v = $this->form_data[$k = 'vr_' . $i];
            $gen = 6 == $i;
            $desc = $gen ? tag('based on parsing', 'for="vr-6"', 'label') : ($v ? a("edit $name, version $v", ["ajax('settings&vss=open.$i.$v', {}, 'f1')"]) : 'Section OFF');
            $ary += [51 + $i => [$name . ' ' . tag($dig[$i], '', $v ? 'g' : 'r'), [
                $k => $gen ? ['', 'chk', 'id="vr-6"'] : ['', 'number', 'class="w-12" min="0" max="8"', 1],
                ['&nbsp; ' . $desc, 'li', ''],
            ]]];
        }
        return [
            '<fieldset class="border mx-3"><legend class="px-3">General</legend>',
            ['Root font-size', [
                'font_sz' => ['', 'number', 'class="w-12" min="8" max="22"', 16],
                ['&nbsp; (default to 16px)', 'li'],
            ]],
            '</fieldset>',
            '<fieldset class="border mx-3"><legend class="px-3">Tailwind CDN settings</legend>',
            'tw_forms' => ['Tailwind forms', 'chk'],
            'tw_typography' => ['Tailwind typography', 'chk'],
            'tw_aspect' => ['Tailwind aspect-ratio', 'chk'],
            'tw_ln_clamp' => ['Tailwind line-clamp', 'chk'],
            'tw_config' => ['Tailwind config', 'textarea_rs', 'class="resize w-2/3"'],
            'tw_css' => ['Tailwind CSS', 'textarea_rs', 'class="resize w-2/3"'],
            '</fieldset>',
        ] + $ary + [98 => '</fieldset>'];
    }

    function syntax() {
        if ('form' == $this->y3[2])
            return $this->general();
        $grp = '' === $this->y3[3] ? 'all' : $this->y3[3];
        $vs = new Vesper($grp, $mw = new Maxwell);
        $ary = $vs->list($_POST['n'] ?? '');
        sort($ary[0]);
        sort($ary[1]);
        $this->ary['vlist'] = $ary[0];
        $this->ary['vword'] = $ary[1];
        $this->ary['section'] = 'Found: ' . count($ary[0]);
        $this->ary['list'] = $ary[0];
        $this->ary['is_ok'] = function ($v) {
            return preg_match("/^[\w\-]+$/", $v);
        };
        $list = [];
        foreach ([-1 => 'all'] + Maxwell::$grp as $v) {
            $act = $grp == $v ? 'background:blue;color:#fff;"' : '"';
            $href = "ajax('settings&syntax=open.0.browse.$v', box)";
            $list[] = a($v, [$href], 'style="margin-left:2px; padding:0 4px;' . $act);
        }
        return [
            ['Group', 'ni', implode('', $list)],
            's' => ['Search', '', 'style="width:50%"'],
        ];
    }

    function _syntax(&$u = null) {
        $this->menu = [
            'form' => 'Common',
            'browse' => 'Browse',
            'values' => 'Values',
            'classes' => 'Classes',
        ];
        $this->ary['vword'] = [];
        $type = 'values' == $this->y3[2] ? 2 : 0;
        $last = unserialize(SKY::t('last_y3'));
        if (!$this->y3[2])
            MVC::$_y['y_3'] = $this->y3 = $last;
        if ('open' == $this->y3[0])
            SKY::t('last_y3', serialize($this->y3));
        if (!$type && 'classes' != $this->y3[2])
            return $this->syntax();

        $id = $this->y3[3];
        $tw = new t_venus('tw');
        if ('put' == $this->y3[0]) {
            $_POST['!dt_u'] = '$now';
            $id ? $tw->update($_POST, $id) : ($id = $tw->insert($_POST));
        } elseif ('open' == $this->y3[0] && $id) {
            MVC::$layout = '';
            SKY::t('last_y3', serialize($last));
            MVC::body("settings.form_only");
        }
        $this->form_data = $id ? $tw->one($id) : [];
        if ($id && !$type)
            $this->ary['gen'] = Maxwell::classes($id);
        $this->ary['list'] = $tw->sqlf('@select id, name from $_ where tw_id=%d', $type);
        uasort($this->ary['list'], function ($a, $b) {
            '-' != $a[0] or $a = substr($a, 1);
            '-' != $b[0] or $b = substr($b, 1);
            '@' != $a[0] && '#' != $a[0] or $a = substr($a, 1);
            '@' != $b[0] && '#' != $b[0] or $b = substr($b, 1);
            return strcmp($a, $b);
        });
        $this->ary['section'] = $this->y3[2];
        $u = "put.0.classes.$id";
        return [
            'tw_id' => $type,
            ['ID', 'ni', $id ?: 'New Item'],
            'grp' => ['Group', 'select', array_combine($a = Maxwell::$grp, $a)],
            'name' => [$type ? 'ValueName' : 'MetaName', '', 'style="width:50%"'],
            'css' => [$type ? 'DefaultValue' : 'Menu', '', 'style="width:50%"'],
            'tpl' => ['Template', 'textarea_rs', 'style="width:98%" rows="21"'],
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
