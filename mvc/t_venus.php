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
        if ($in->tw_native)
            $maat->tw_native($in->tw_native, $this);
        $html = trim($maat->buildHTML($in->tree));
        return [
            'code' => $maat->code($html, (new Vesper)->v_css($maat)),
            'preflight' => $in->tw_native ? '' : $this->t_settings->preflight(),
            'links' => m_menu::v_links($maat->links),
            'menu' => m_menu::v_sourses($this),
            'fn' => $this->get($in->fn, false, $tw),
        ];
    }

    function get($_fn, $data, &$tw) {
        $tw = !SKY::w('vesper');
        $pfx = $_fn[0];
        $fn = substr($_fn, 1);
        if (':' == $pfx) {
            return $data ? $this->cell($fn, 'tmemo') : 'Venus: <b>' . $this->cell($fn, 'name') . '</b>';
        } elseif ('~' == $pfx) {//Component  m_menu::$types
            if ($data)
                return call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_g"], ['main', "venus/$fn.html"]);
            return "Application: <b>" . ucfirst(substr($fn, 2)) . '</b>';
        }
        $tw = '';
        preg_match('/^https?:/', $_fn) or $_fn = "https://$_fn";
        return $data ? get($_fn, '', false) : "URL: <b>$_fn</b>"; //file_get_contents
    }

    function put($fn, $data = null) {
        if (':' == $fn[0]) {
            $this->update(['tmemo' => $data], substr($fn, 1));
        } elseif ('~' == $fn[0]) {
            call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_p"], ['main', "venus/" . substr($fn, 1) . '.html'], $data);
        }
        return true;
    }
}
