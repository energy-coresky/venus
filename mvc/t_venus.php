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

    function src($maat, $fn) {
        if ($maat)
            return $this->maat(unjson(file_get_contents('php://input')));
        # step 0
        $this->history($fn);
        $html = $this->get($fn, true, $tw);
        if ('#.jet' == substr($html, 0, 5)) {
            $this->jet = [$fn => 'jet'];
            $html = Jet::text($html);
        }
        return [
            'html' => $html,
            'tw' => !$tw ? $tw : $this->tailwind(),
            'jet' => $this->jet ?: false, # for step 1
        ];
    }

    function maat($in) { # step 1
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
            $str = $this->get($fn);
            $html = 'jet' == $type ? Display::jet($str, '', true, true) : html($str);
            $out[] = [$html, substr_count($str, "\n"), $type . $fn, $fn];
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
        if (null === $fn) { # return history list
            $out = [];
            foreach ($ary as $k => $v)
                $out[$v] = '$$.test(\'' . "$k')";
            return $out;
        } else { # add to history
            $ary = [$fn => $this->get($fn, false)] + $ary;
            SKY::w('hy_src', serialize(array_slice($ary, 0, 19, true)));
        }
    }

    function _inc($fn) {
        $str = $this->get($fn);
        $this->jet += [$fn => '#.jet' == substr($str, 0, 5) ? 'jet' : 'html'];
        return $str;
    }

    function get($fn, $is_html = true, &$tw = null) {
        $tw = !SKY::w('vesper');
        $id = substr($fn, 1);
        switch ($fn[0]) {
            case '!': # usage sample
                $str = $this->sql('+select !! from $_tw where id=$+', $is_html ? 'tmemo' : 'name', $id);
                return $is_html ? ($str ?? self::empty_data) : "Usage: <b>$str</b>";
            case ':': # Venus component
                return $is_html ? $this->cell($id, 'tmemo') : 'Venus: <b>' . ($tw = $this->cell($id, 'name')) . '</b>';
            case '~': # App component
                if ($is_html)
                    return call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_g"], ['main', "venus/$id.html"]);
                return "Application: <b>" . ucfirst($tw = substr($id, 2)) . '</b>';
        }
        # else HTTP
        $tw = '';
        preg_match('/^https?:/', $fn) or $fn = "https://$fn";
        if (!$is_html)
            return "URL: <b>$fn</b>";
        if ('PHP' == substr($_SERVER['SERVER_SOFTWARE'], 0, 3))
            return '<span class="text-7xl">Cannot run second query under PHP server</span>';
        $curl = curl_init($fn);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
        $html = curl_exec($curl);
        curl_close($curl);
        return $html;
    }

    function put($fn, $html = null) {
        if ('!' == $fn[0]) {
            $this->sqlf('update $_tw set tmemo=%s where id=%d', trim($html), substr($fn, 1));
        } elseif (':' == $fn[0]) {
            $this->update(['tmemo' => trim($html)], substr($fn, 1));
        } elseif ('~' == $fn[0]) {
            call_user_func(['Plan', (SKY::w('plan') ? 'mem' : 'app') . "_p"], ['main', "venus/" . substr($fn, 1) . '.html'], trim($html));
        } elseif ('.jet' == substr($fn, -4)) {
            Plan::view_p(['main', $fn], $html);
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
