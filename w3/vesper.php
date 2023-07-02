<?php

class Vesper
{
    public $idx;

    private $maat;
    private $tw;
    private $values;
    private $defaults = [];
    private $comp;

  function arb($in, $x, $samples = false) {
      if ($samples)
          return ['[any_you_want]'];
      if ('[' != $in[0])
          return false;
      return str_replace('_', ' ', substr($in, 1, -1));
  }

    function __construct($maat) {
        $this->maat = $maat;
        $this->tw = new t_venus('tw');
        $values = $this->tw->sqlf('@select name, tpl, comp from $_ where tw_id=2');
        $this->values = [];
        foreach ($values as $name => $ary) {
            if ('' !== $ary[1])
                $this->defaults[$name] = $ary[1];
            $this->values[$name] = [];
            $n = '=' == $name[0] ? 2 : 3;
            foreach (explode("\n", unl(trim($ary[0]))) as $item) {
                [$k, $v, $v2] = explode(' ', $item, $n) + [2 => false];
                $this->values[$name][$k] = false === $v2 ? $v : [$v, $v2];
            }
        }
        $this->index();
    }
// class: [mask-image:radial-gradient(64rem_64rem_at_top,white,transparent)]       Component: Content Section
// .\[mask-image\:radial-gradient\(64rem_64rem_at_top\2c white\2c transparent\)\]:[mask-image
/*
.\[mask-image\:radial-gradient\(64rem_64rem_at_top\2c white\2c transparent\)\] {
   -webkit-mask-image:radial-gradient(64rem 64rem at top,white,transparent);
   mask-image:radial-gradient(64rem 64rem at top,white,transparent);
}*/

    function v_css() {
        $ms = m_venus::$media;
        $md = [0, 640, 768, 1024, 1280, 1536]; // $this->values['=screens']
        $media = array_combine($ms, array_pad([], 6, []));
        trace(var_export($this->maat->cls, 1));
        //trace(print_r($this->values));
        foreach ($this->maat->cls as $cls) {
            if ('' === $cls)
                continue;
            foreach (preg_split('/\s+/', $cls) as $name) {
                $ps = '[' == $name[0] ? [$name] : explode(':', $name);
                $one = array_pop($ps);
                if ($sct = array_intersect($ms, $ps)) {
                    $ps = array_values(array_diff($ps, $sct));
                    $sct = pos($sct);
                }
                $ps = array_map(function ($v) {
                    return in_array($v, ['even', 'odd']) ? "nth-child($v)" : $v;
                }, $ps);
                $name = str_replace(',', '\\2c ', preg_replace("/([^\w\-,])/i", "\\\\$1", $name));
                if ('placeholder-' == substr($name, 0, 12))
                    array_unshift($ps, ':placeholder');
                $media[$sct ?: ''][".$name"] = [$one, $ps];
            }
        }
        $ary = [];
        $pp =& $ary;
        $depth = 0;
        foreach ($media as $sct => $xx) {
            if ($xx && $sct) {
                $px = $md[array_search($sct, $ms)];
                $ary[] = ["@media (min-width: {$px}px)", [], $depth = 1];
                $pp =& $ary[count($ary) - 1][1];
            }
            foreach ($xx as $name => $yy) {
                [$one, $ps] = $yy;
                if ('group' == $one)
                    continue;
                $css = $this->genCSS($one) or $css = ["/* not found */"];
                $spec = '.' == pos($css)[0] ? substr(array_shift($css), 1) : '';
                if ($ps) {
                    if (array_intersect($ps, ['before'])) {
                        array_unshift($css, 'content: var(--tw-content)');
                        $ps[array_search('before', $ps)] = ':before';
                    }
                    $fst = explode('-', $ps[0]);
                    if ('group' == $fst[0] && count($fst) > 1) {
                        $name = ".group:$fst[1] $name";
                    } else {
                        $name .= ':' . implode(':', $ps);
                    }
                }
                $pp[] = [$name . $spec, $css, $depth];
            }
        }
        return $this->maat->buildCSS($ary, true);
    }

    function genCSS($cls) { // 2do container (add 6 classes), Animation
        $minus = '';
        if ('-' == $cls[0]) {
            $minus = '-';
            $cls = substr($cls, 1);
        }
        if ($pos = strpos($cls, '['))
            [$cls, $arb] = explode('[', $cls, 2);
        $last = count($list = explode('-', $cls)) - 1;
        if ($pos)
            $list[$last] .= "[$arb";
        $pp =& $this->idx;
        $arb = function ($v, $repl, $srch = '{0}') {
            return str_replace($srch, str_replace('_', ' ', substr($repl, 1, -1)), array_filter($v, 'is_string'));
        };
        foreach ($list as $n => $div) {
            $_ = is_num($div) ? '_' . $div : $div;
            if (isset($pp[$_])) {
                $pp =& $pp[$_];
                if ($last == $n)
                    return '[' == $div[0] ? $arb($pp, $div, $div) : array_filter($pp, 'is_string');
                continue;
            } elseif ($last == $n) {
                foreach ($pp as $k => $v) {
                    $k = (string)$k;
                    $or = '|' == $k[0];
                    if (($or || '=' == $k[0]) && $this->value($k, $div, $cv)) {
                        return $or
                            ? str_replace(['{1}', '{0}'], [$cv[1], $cv[0]], array_filter($v, 'is_string'))
                            : str_replace('{0}', $minus . $cv, array_filter($v, 'is_string'));
                    } elseif ('~' == $k[0]) {
                        $r = '[' == $div[0] && ']' == $div[-1]
                            ? str_replace('_', ' ', substr($div, 1, -1))
                            : call_user_func([$this, '_' . substr($k, 1, -1)], $div, $k[-1]);
                        if (false !== $r)
                            return str_replace('{0}', $r, array_filter($v, 'is_string'));
                    } elseif ('[' == $k[0] && '[' == $div[0]) {
                        return $arb($v, $div, $k);
                    }
                }
            }
            if (isset($pp['&color']) && $last - $n < 2) {
                return $this->color($div, $list[1 + $n] ?? '', $pp['&color']);
            } elseif ($div && '[' == $div[0]) {
                return $arb([$div], $div, $div);
            } else {
                return false;
            }
        }
        return false;
    }

    function genClass($row) {
        $ary = $out = [];
        $comp = $row->comp ? explode(' ', $row->comp) : [];
        foreach (explode(' ', $row->name) as $x => $_) {
            '-' != $_[0] or $_ = substr($_, 1); # crop minus
            $ary[$x] = [];
            $hash = '#' == $_[0];
            if ('@' == $_[0] || $hash) {
                $r0 = explode("\n", unl(trim($row->tpl)))[0];
                foreach (explode('|', $r0) as $v) {
                    [$v] = explode('=', $v);
                    $ary[$x][] = $v;
                }
            } elseif ('=' == $_[0] || '|' == $_[0]) {
                $default = $this->default($_) ? [-1 => ''] : [];
                if (isset($this->values[$_])) {
                    $ary[$x] = $default + array_keys($this->values[$_]);
                } else foreach ($comp as $cp) {
                    if ('=' == $cp[0])
                        $ary[$x] = $default + array_merge($ary[$x], array_keys($this->values[$cp]));
                    if ('~' == $cp[0])
                        $ary[$x] = $default + array_merge($ary[$x], call_user_func([$this, '_' . substr($cp, 1, -1)], 0, $cp[-1], true));
                }
            } elseif ('~' == $_[0]) {
                $default = $this->default($_) ? [-1 => ''] : [];
                $ary[$x] = $default + call_user_func([$this, '_' . substr($_, 1, -1)], 0, $_[-1], true);
            } else {
                $ary[$x][] = $_;
            }
        }
        foreach ($ary as $x => $list) {
            $tmp = [];
            foreach ($list as $y) {
                if ($x) foreach ($out as $line) {
                    $tmp[] = $line . ('' === $y ? '' : "-$y");
                } else {
                    $out[] = $y;
                }
            }
            if ($x)
                $out = $tmp;
        }
        return $out;
    }

    function value($in, $val, &$cv) {
        if ('[' == $val[0] && ']' == $val[-1]) { # Arbitrary value
            $cv = substr($val, 1, -1);
            return true;
        }
        if (isset($this->values[$in])) {
            $cv = $this->values[$in];
            $cv = $cv[$val] ?? false;
            return false !== $cv;
        }
        foreach ($this->comp[$in] as $in) {
            if ('~' == $in[0]) {
                $cv = call_user_func([$this, '_' . substr($in, 1, -1)], $val, $in[-1]);
                if (false !== $cv)
                    return true;
            } else {
                $cv = $this->values[$in];
                $cv = $cv[$val] ?? false;
                if (false !== $cv)
                    return true;
            }
        }
        return false;
    }

    function default($term) {
        switch ($term) {
            case '~num1': return '1px';
            case '~num3': return '3px';
            case '~num%': return '100%';
        }
        return $this->defaults[$term] ?? false;
    }

    function _fraction($v, $x, $samples = false) {
        if ($samples) {
            $out = ['full'];
            for ($i = 1; $i < 12; $out[] = "$i/12", $i++);
            return $out;
        }
        if ('full' == $v)
            return '100%';
        if (!preg_match("~^(\d+)/(\d+)$~", $v, $m))
            return false;
        return round(100 * $m[1] / $m[2], 6) . '%';
    }

    function _num($in, $x, $samples = false) {
        if ($samples)
            return '/' == $x ? range(0, 100, 5) : range(0, 9);
        if (!is_num($in))
            return false;
        switch ($x) {
            case '/': return $in / 100;
            case 's': return $in . 'ms';
            case 'd': return $in . 'deg';
            case '!': return $in;
            case '%': return $in . '%';
            default: return $in . 'px';
        }
    }
/*
.decoration-slate-300{
-webkit-text-decoration-color:#cbd5e1;
text-decoration-color:#cbd5e1
}

*/
    function color($color, $num, $ary) { // bg-sky-500/[.06] bg-[#50d71e]
        $point = '.' == $ary[0][0] ? array_shift($ary) : false;
        $var = '-' == $ary[0][0] && count($ary) > 1 ? array_shift($ary) : false;
        if ('[' == $color[0] && ']' == $color[-1]) # Arbitrary value
            return str_replace('&color', substr($color, 1, -1), $ary);
        $pal = HTML::$color_hex + [
            'transparent' => 'transparent',
            'current' => 'currentColor',
            'inherit' => 'inherit',
        ];
        $var or $pal += ['none' => 'none'];
        $opacity = $tw_i = '';
        if ('' !== $num) {
            if (!preg_match("~^(\d+)/?(\d*|\[[^\]]+\])$~", $num, $match))
                return false;
            [, $num, $opacity] = $match;
            if (0 == (int)$num % 50) {
                $pal = Tailwind::$color3;
                $tw_i = 950 == $num ? 10 : floor($num / 100);
            } elseif (0 == (int)$num % 15) {
                // 2do hsl() rotate HTML colors
            } else {
                return false;
            }
        }
        if (!isset($pal[$color]))
            return false;
        $hex = '' === $tw_i ? $pal[$color] : $pal[$color][$tw_i];
        if ('#' != $hex[0] || !$var)
            return str_replace('&color', $hex, $ary);
        if ('' === $opacity) {
            array_unshift($ary, "$var: 1");
            $opacity = "var($var)";
        } else {
            $opacity = '[' == $opacity[0] ? substr($opacity, 1, -1) : (int)$opacity / 100;
        }
        if ($point)
            array_unshift($ary, $point);
        $dec = implode(' ', array_map('hexdec', str_split(substr($hex, 1), 2)));
        return str_replace('&color', "rgb($dec / $opacity)", $ary);
    }

    function index() {
        $rules = $this->tw->sqlf('@select name, tpl, comp from $_ where tw_id=0');
        $this->idx = [];
        foreach ($rules as $key => $ary) {
            $minus = false;
            if ('-' == $key[0]) {
                $minus = true;
                $key = substr($key, 1);
            }
            $tpl = explode("\n", unl(trim($ary[0])));
            $pp = [&$this->idx];
            $lsTitle = count($titles = explode(' ', $key)) - 1;
            $rs = [];
            foreach ($titles as $it => $one) { # list of titles
                $ats = [$one];
                $hash = '#' == $one[0];
                if ('@' == $one[0] || $hash) {
                    $ats = [];
                    foreach (explode('|', array_shift($tpl)) as $tmp) {
                        $a = explode('=', $tmp, 2);
                        $ats[] = $a[0];
                        if ($hash) {
                            $rs[] = array_splice($tpl, 0, array_search('.' . $a[0], $tpl));
                            array_shift($tpl);
                        } else {
                            $rs[] = $a[1] ?? $a[0];
                        }
                    }
                } elseif ('=' == $one[0] && $ary[1]) {
                    $this->comp[$one] = explode(' ', $ary[1]);
                }
                $p = [];
                $n = 0;
                foreach ($ats as $at) {
                    $lsPart = count($atParts = explode('-', $at)) - 1;
                    foreach ($pp as &$_) {
                        foreach ($atParts as $ip => $x) {
                            $prev =& $_;
                            if ('' !== $x) {
                                if (is_num($x))
                                    $x = '_' . $x;
                                isset($_[$x]) or $_[$x] = [];
                                $_ =& $_[$x];
                            }
                            if ($ip == $lsPart) {
                                $p[] =& $_;
                                if ($it == $lsTitle) {
                                    $replaced = $hash ? $rs[$n++] : ($rs ? str_replace('{@}', $rs[$n++], $tpl) : $tpl);
                                    '' === $x ? ($_ += $replaced) : ($_ = $replaced);
                                    if ($default = $this->default($x))
                                        $prev += str_replace('{0}', $default, $_);
                                }
                            }
                        }
                    }
                }
                $pp = $p;
            }
        }
        ksort($this->idx);
    }
}
