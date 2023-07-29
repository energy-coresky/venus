<?php

class t_venus extends Model_t
{
    protected $table = 'preset';

    public $w;
    public $jet = [];

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
            'code' => $maat->code($html, $this->templates($in->jet), $in->fn),
            'preflight' => $in->tw_native ? '' : $this->t_settings->preflight(),
            'links' => m_menu::v_links($maat->links),
            'fn' => $this->get($in->fn, false, $tw),
            'menu' => m_menu::v_sourses($this, $tw),
        ];
    }

    function templates($ary) {
        if (!$ary)
            return [];
        $out = [];
        foreach ($ary as $fn => $type) {
            $s = $this->get($fn, 'nh', $tw);
            $html = 'jet' == $type ? Display::jet($s, '', true) : html($s);
            $out[] = [$html, substr_count($s, "\n"), $type . $fn, $fn];
        }
        return $out;
    }

    function tailwind() {
        [$t] = m_venus::ghost($this->head_y(), 'syntax');
        $ary = [];
        foreach (['forms', 'typography', 'aspect', 'ln_clamp'] as $plug) {
            if ($t["tw_$plug"])
                $ary[] = 'aspect' == $plug ? 'aspect-ratio' : ('ln_clamp' == $plug ? 'line-clamp' : $plug);
        }
        $q = $ary ? '?plugins=' . implode(',', $ary) : '';
        return js(["https://cdn.tailwindcss.com$q"]) . js($t['tw_config']) . tag($t['tw_css'], 'type="text/tailwindcss"', 'style');
    }

    function history($fn = null) {
        $ary = unserialize(SKY::w('hy_src'));
        if (null === $fn) {
            $out = [];
            foreach ($ary as $k => $v)
                $out[$v] = '$$.test(\'' . "$k')";
            return $out;
        } else {
            $ary = [$fn => $this->get($fn, false, $tw)] + $ary;
            SKY::w('hy_src', serialize(array_slice($ary, 0, 19, true)));
        }
    }

    function _inc($fn) {
        $s = $this->get($fn, 'nh', $tw);
        $this->jet += [$fn => '#.jet' == substr($s, 0, 5) ? 'jet' : 'html'];
        return $s;
    }

    function jet($fn, &$tw) {
        $s = $this->get($fn, true, $tw);
        if ('#.jet' != substr($s, 0, 5))
            return $s;
        $this->jet = [$fn => 'jet'];
        return Jet::inline($s);
    }

    function get($fn, $data, &$tw) {
        $tw = !SKY::w('vesper');
        if ($data && 'nh' !== $data)
            $this->history($fn);
        $pfx = $fn[0];
        $id = substr($fn, 1);
        if ('!' == $pfx) {
            $s = $this->sql('+select !! from $_tw where id=$+', $data ? 'tmemo' : 'name', $id);
            return $data ? ($s ?? '<i class="text-7xl">TODO</i>') : "Usage: <b>$s</b>";
        } elseif (':' == $pfx) {
            return $data ? $this->cell($id, 'tmemo') : 'Venus: <b>' . ($tw = $this->cell($id, 'name')) . '</b>';
        } elseif ('~' == $pfx) {
            if ($data)
                return call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_g"], ['main', "venus/$id.html"]);
            return "Application: <b>" . ucfirst($tw = substr($id, 2)) . '</b>';
        }
        $tw = '';
        preg_match('/^https?:/', $fn) or $fn = "https://$fn";
        return $data ? get($fn, '', false) : "URL: <b>$fn</b>"; //file_get_contents
    }

    function put($fn, $data = null) {
        if ('!' == $fn[0]) {
            $this->sqlf('update $_tw set tmemo=%s where id=%d', $data, substr($fn, 1));
        } elseif (':' == $fn[0]) {
            $this->update(['tmemo' => $data], substr($fn, 1));
        } elseif ('~' == $fn[0]) {
            call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_p"], ['main', "venus/" . substr($fn, 1) . '.html'], $data);
        }
        return true;
    }
}
