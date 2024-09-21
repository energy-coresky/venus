<?php

class t_venus extends Model_t
{
    protected $table = 'preset';

    const empty_data = '<i class="text-7xl">TODO</i>';

    public $w;
    public $jet = [];

    function head_y() {
        static $dd;
        if ($dd)
            return $dd;
        $this->w =& m_venus::ghost($dd = SQL::open());
        return $dd;
    }

    function maat(&$in) {
        $maat = new Maat(['highlight' => true]);
        if ($in->tw_native)
            $maat->tw_native($in->tw_native, $this);
        $html = trim($maat->buildHTML($in->tree));
        return [
            'fn' => $this->get($in->fn, false, $tw),
            'menu' => m_menu::v_sourses($this, $tw),
            'code' => $maat->code($html, $this->templates($in->jet), $in),
            'preflight' => $in->tw_native ? '' : $this->t_settings->preflight(),
            'links' => m_menu::v_links($maat->links),
            'grace' => file_get_contents(__DIR__ . '/../assets/grace.js'),
        ];
    }

    function templates($ary) {
        if (!$ary)
            return [];
        $out = [];
        foreach ($ary as $fn => $type) {
            $s = $this->get($fn, 'nh', $tw);
            $html = 'jet' == $type ? Display::jet($s, '', true, true) : html($s);
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
        return Jet::text($s);
    }

    function _get($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl,CURLOPT_CONNECTTIMEOUT, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    function get($fn, $data, &$tw) {
        $tw = !SKY::w('vesper');
        if ($data && 'nh' !== $data)
            $this->history($fn);
        $pfx = $fn[0];
        $id = substr($fn, 1);
        if ('!' == $pfx) {
            $s = $this->sql('+select !! from $_tw where id=$+', $data ? 'tmemo' : 'name', $id);
            return $data ? ($s ?? self::empty_data) : "Usage: <b>$s</b>";
        } elseif (':' == $pfx) {
            return $data ? $this->cell($id, 'tmemo') : 'Venus: <b>' . ($tw = $this->cell($id, 'name')) . '</b>';
        } elseif ('~' == $pfx) {
            if ($data)
                return call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_g"], ['main', "venus/$id.html"]);
            return "Application: <b>" . ucfirst($tw = substr($id, 2)) . '</b>';
        }
        $tw = '';
        preg_match('/^https?:/', $fn) or $fn = "https://$fn";
        if (!$data)
            return "URL: <b>$fn</b>";
        if ('PHP' != substr($_SERVER['SERVER_SOFTWARE'], 0, 3))
            return $this->_get($fn);
        return '<span class="text-7xl">Cannot run second query under PHP server</span>';
    }

    function put($fn, $data = null) {
        if ('!' == $fn[0]) {
            $this->sqlf('update $_tw set tmemo=%s where id=%d', trim($data), substr($fn, 1));
        } elseif (':' == $fn[0]) {
            $this->update(['tmemo' => trim($data)], substr($fn, 1));
        } elseif ('~' == $fn[0]) {
            call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_p"], ['main', "venus/" . substr($fn, 1) . '.html'], trim($data));
        } elseif ('.jet' == substr($fn, -4)) {
            Plan::view_p(['main', $fn], $data);
        }
        return true;
    }

    function add($fn, $type, $src) {
        $fn = preg_replace("/\s+/", ' ', trim($fn));
        if ('' === $fn) {
            echo '-';
        } elseif ($src) { # Venus
            echo ':' . $this->insert([
                'name' => $fn,
                'flag' => $type,
                'tmemo' => self::empty_data,
                '!dt' => '$now',
            ]);
        } else { # App
            $fn = "$type-" . preg_replace("/ /", '-', strtolower($fn));
            $exist = call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_t"], ['main', "venus/$fn.html"]);
            if (!$exist)
                call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_p"], ['main', "venus/$fn.html"], self::empty_data);
            echo "~$fn";
        }
    }
}
