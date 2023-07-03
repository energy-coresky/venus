<?php

class Vesper
{
    public $idx;

    private $maat;
    private $tw;
    private $values;
    private $defaults = [];
    private $comp;

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

    function v_css() {
        $ms = m_venus::$media;
        $md = [0, 640, 768, 1024, 1280, 1536]; // $this->values['=screens']
        $media = array_combine($ms, array_pad([], 6, []));
        //trace(var_export($this->maat->cls, 1));
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

    function arbitrary($v, &$sc = null) {
        $pos = strpos($v = str_replace('_', ' ', substr($v, 1, -1)), 'calc(');
        if (false !== $pos) {
            $len = strlen($br = Rare::bracket(substr($v, 4 + $pos)));
            $br = preg_replace("/([^\s\(])(\+|\-)(\S)/", "$1 $2 $3", $br); // 2do: more smart
            $v = substr($v, 0, 4 + $pos) . $br . substr($v, 4 + $pos + $len);
        }
        if (2 == count($a = explode(':', $v, 2))) {
            $v = implode(': ', $a);
            null === $sc or $sc = true;
        }
        return $v;
    }

    function genCSS($cls) { // 2do container (add 6 classes), Animation
        $minus = '';
        if ('-' == $cls[0]) {
            $minus = '-';
            $cls = substr($cls, 1);
        }
        $pos = strpos($cls, '[');
        if ($pos = $pos !== false)
            [$cls, $atm] = explode('[', $cls, 2);
        $last = count($list = explode('-', $cls)) - 1;
        if ($pos)
            $list[$last] .= "[$atm";
        $pp =& $this->idx;
        $arbitrary = function ($v, $repl, $srch = '{0}') {
            $native = 1 == count($data = array_filter($v, 'is_string')) && $repl == $srch;
            $sc = false;
            $repl = $this->arbitrary($repl, $sc);
            if ($native && $sc)
                array_unshift($data, '-webkit-' . $data[0]);
            return str_replace($srch, $repl, $data);
        };
        foreach ($list as $n => $div) {
            $_ = is_num($div) ? '_' . $div : $div;
            if (isset($pp[$_])) {
                $pp =& $pp[$_];
                if ($last == $n)
                    return '[' == $div[0] ? $arbitrary($pp, $div, $div) : array_filter($pp, 'is_string');
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
                        $cv = '[' == $div[0] && ']' == $div[-1]
                            ? $this->arbitrary($div)
                            : call_user_func([$this, '_' . substr($k, 1, -1)], $div, $k[-1]);
                        if (false !== $cv)
                            return str_replace('{0}', $minus . $cv, array_filter($v, 'is_string'));
                    } elseif ('[' == $k[0] && '[' == $div[0]) {
                        return $arbitrary($v, $div, $k);
                    }
                }
            }
            if (isset($pp['&color']) && $last - $n < 2) {
                return $this->color($div, $list[1 + $n] ?? '', $pp['&color']);
            } elseif ($div && '[' == $div[0]) {
                return $arbitrary([$div], $div, $div);
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
        if ('[' == $val[0] && ']' == $val[-1]) {
            $cv = $this->arbitrary($val);
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
            case '~nump': return '100%';
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
        $perc = function () {
            return array_map(function ($v) {
                return $v . '%';
            }, range(0, 100, 5));
        };
        if ($samples)
            return '/' == $x ? range(0, 100, 5) : ('%' == $x ? $perc() : range(0, 9));
        if ('%' == $x && '%' == $in[-1])
            $in = substr($in, 0, -1);
        if (!is_num($in))
            return false;
        switch ($x) {
            case '/': return $in / 100;
            case 's': return $in . 'ms';
            case 'd': return $in . 'deg';
            case 'p': case '%': return $in . '%';
            case '!': return $in;
            default: return $in . 'px';
        }
    }

    function color($color, $num, $ary) {
        $point = '.' == $ary[0][0] ? array_shift($ary) : false;
        $var = '--' == substr($ary[0], 0, 2) && count($ary) > 1 ? array_shift($ary) : false;
        if ('[' == $color[0] && ']' == $color[-1]) # Arbitrary value
            return str_replace('&color', $this->arbitrary($color), $ary);
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
            $opacity = '[' == $opacity[0] ? $this->arbitrary($opacity) : (int)$opacity / 100;
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
            $lsTitle = count($titles = explode(' ', $key)) - 1;
            $tpl = explode("\n", unl(trim($ary[0])));
            $pp = [&$this->idx];
            $rs = [];
            foreach ($titles as $it => $one) { # list of titles
                $ats = [$one];
                $hash = '#' == $one[0];
                if ('@' == $one[0] || $hash) {
                    $ats = [];
                    foreach (explode('|', array_shift($tpl)) as $tmp) {
                        $v = explode('=', $tmp, 2);
                        $ats[] = $v[0];
                        if ($hash) {
                            $rs[] = array_splice($tpl, 0, array_search('.' . $v[0], $tpl));
                            array_shift($tpl);
                        } else {
                            $rs[] = $v[1] ?? $v[0];
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
