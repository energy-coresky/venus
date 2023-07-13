<?php

class Vesper
{
    public $idx = [];
    public $idx_e;//exact
    public $idx_s;//similar

    private $values = [];
    private $defaults = [];
    private $composite = [];
    private $color;
    private $mw;

    function __construct($grp = '', ?Maxwell $mw = null) {
        $this->mw = $mw ?? false;
        $grp = !$grp || 'all' == $grp ? '' : " and grp='$grp'";
        $this->index($grp);
    }

    function v_css($maat) {
        $ary = $this->listCSS($maat->cls);
        return $maat->buildCSS($ary, !SKY::w('vesper'));
    }

    function caret($ary) {
        $this->idx_e = $this->idx_s = [];
        $this->list();
        $out = [];
        foreach ($ary as $cls) {
            $out[$cls] = [':' => [], [], [], []];
            $pp =& $out[$cls];
            $ps = explode(':', $cls);
            $cls = array_pop($ps);
            $pp[":"] = $ps;
            $css = $this->listCSS([$cls])[0] ?? false;
            if ($css && 3 == count($css)) {
                $exact = 1 == count($css[1]) ? 1 : 0;
                foreach ($css[1] as $css) {
                    [$prop] = explode(':', $css, 2);
                    if ($exact) {
                        $pp[1] = array_unique($this->idx_e[$prop] ?? []);
                    } elseif ($this->color) {
                        $pp[1] = [$this->color];
                    }
                    $sum = array_merge($pp[0], $this->idx_e[$prop] ?? [], $this->idx_s[$prop] ?? []);
                    $pp[0] = array_diff(array_unique($sum), $pp[1]);
                }
            }
        }
        return $out;
    }

    function list($search = '', $ary = [], $depth = 0) {
        static $out = [], $x = [], $words = [];
        $depth or $ary = $this->idx;
        '-' != $search or $search = '';
        array_walk($ary, function ($v, $k) use (&$out, &$x, &$words, $depth, $search) {
            if (is_num($k) || $depth > 99)
                return;
            $x[$depth] = '_' == $k[0] ? substr($k, 1) : $k;
            if ($css = array_filter($v, 'is_string')) {
                $ary = array_slice($x, 0, 1 + $depth);
                if (!$search || in_array($search, $ary)) {
                    $out[] = $class = implode('-', $ary);
                    $words += array_filter(array_flip($ary), function ($key) {
                        return !in_array($key, range(0, 11), true);
                    }, ARRAY_FILTER_USE_KEY);
                }
                if (null !== $this->idx_e) {
                    1 == count($css) ? ($pp =& $this->idx_e) : ($pp =& $this->idx_s);
                    foreach ($css as $line) {
                        [$prop] = explode(':', $line, 2);
                        isset($pp[$prop]) or $pp[$prop] = [];
                        $pp[$prop][] = $class;
                    }
                }
            }
            $this->list($search, $v, 1 + $depth);
        });
        return [$out, array_keys($words)];
    }

    function listCSS($ary) {
        $ms = m_venus::$media; // @media (prefers-color-scheme: dark)
        $md = [0, 640, 768, 1024, 1280, 1536]; // $this->values['=screens']
        $media = array_combine($ms, array_pad([], 6, []));
        //trace(var_export($ary, 1));
        foreach ($ary as $cls) {
            $cls = trim($cls);
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
                if (2 == $name[0])
                    $name = '\\3' . $name;
                if ('placeholder-' == substr($name, 0, 12))//////////
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
                if (in_array($one, ['group', 'dark']))
                    continue;
                $this->color = 0;
                $css = $this->genCSS($one);
                //$css or $css = ["/* not found */"];
                if (!$css)
                    continue;
                $mul = false;
                if ('@' == $css[0][0])
                    $this->multiple($css, $mul, $depth);
                $spec = '.' == pos($css)[0] ? substr(array_shift($css), 1) : '';
                if ($dark = in_array('dark', $ps))
                    array_splice($ps, array_search('dark', $ps), 1);
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
                if ($dark)
                    $name = ":is(.dark $name)";
                $pp[] = [$name . $spec, $css, $depth];
                if ($mul) {
                    array_walk_recursive($mul, function (&$v, $k) use ($one, $name) {
                        $k or $v = str_replace(".$one", $name, $v);
                    });
                    $pp = array_merge($pp, $mul);
                }
            }
        }
        return $ary;
    }

    function multiple(&$css, &$mul, $depth) {
        static $maat;
        array_shift($css);
        $mul = [];
        $maat = $maat ?? new Maat;
        foreach ($css as $i => $one) {
            if ('@' == $one[0]) {
                $null = null;
                $one = substr($one, 1);
                $mul = array_merge($mul, $maat->parse_css($one, $null, $depth));
                unset($css[$i]);
            }
        }
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

    function genCSS($cls) {
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
                    } elseif ('~' == $k[0] && (strlen($div) < 2 || '#' != $div[1])) {
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
                $this->color = implode('-', array_slice($list, 0, $n) + [-1 => '&color']);
                //return $this->color($div, $list[1 + $n] ?? '', $pp['&color']);
                return $this->color($div, $list[1 + $n] ?? '', array_filter($pp['&color'], 'is_string'));
            } elseif ($div && '[' == $div[0]) {
                return $arbitrary([$div], $div, $div);
            } else {
                return false;
            }
        }
        return false;
    }

    function samples(&$in) {
        $ary = explode('-', $in);
        $name = array_pop($ary);
        $in = implode('-', $ary) . '-';
        return $this->sample_values($name);
    }

    function sample_values($name, $add_default = false) {
        if ('&color' == $name)
            return $this->color(0, 0, 0, true);
        $ary = $add_default && $this->default($name) ? [''] : [];
        if ('=' == $name[0] || '|' == $name[0]) {
            if (isset($this->values[$name]))
                return array_merge($ary, array_keys($this->values[$name]));
            foreach ($this->composite[$name] as $name) {
                if ('=' == $name[0])
                    $ary = array_merge($ary, array_keys($this->values[$name]));
            }
        }
        if ('~' == $name[0])
            $ary = array_merge($ary, call_user_func([$this, '_' . substr($name, 1, -1)], 0, $name[-1], true));
        return $ary;
    }

    function genClass($row) {
        $ary = $out = [];
        foreach (explode(' ', $row->name) as $i => $name) {
            '-' != $name[0] or $name = substr($name, 1); # crop minus
            $ary[$i] = [];
            if ('@' == $name[0] || '#' == $name[0]) {
                $r0 = explode("\n", unl(trim($row->tpl)))[0];
                foreach (explode('|', $r0) as $v) {
                    [$v] = explode('=', $v);
                    $ary[$i][] = $v;
                }
            } elseif (in_array($name[0], ['=', '~', '|', '&'])) {
                $ary[$i] = $this->sample_values($name, true);
            } else {
                $ary[$i][] = $name;
            }
        }
        foreach ($ary as $i => $list) {
            $tmp = [];
            foreach ($list as $y) {
                if ($i) foreach ($out as $line) {
                    $tmp[] = $line . ('' === $y ? '' : "-$y");
                } else {
                    $out[] = $y;
                }
            }
            if ($i)
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
        foreach ($this->composite[$in] as $in) {
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
        if ($samples) {
            $perc = array_map(function ($v) {
                return $v . '%';
            }, $range = range(0, 100, 5));
            return '/' == $x ? $range : ('%' == $x ? $perc : range(0, 9));
        }
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

    function color($color, $num, $ary, $samples = false) {
        $base = [
            'transparent' => 'transparent',
            'current' => 'currentColor',
            'inherit' => 'inherit',
        ];
        if ($samples) {
            return array_keys($base) + [
                3 => 'black',
                'white',
                'sky-300', 'silver', 'tan',
            ];
        }
        $point = '.' == $ary[0][0] ? array_shift($ary) : false;
        $var = '--' == substr($ary[0], 0, 2) && count($ary) > 1 ? array_shift($ary) : false;
        $ino = is_numeric($ary[0]) ? array_shift($ary) : false;
        if ('[' == $color[0] && ']' == $color[-1]) # Arbitrary value
            //return str_replace('&color', $this->arbitrary($color), $ary);
            $hex = $this->arbitrary($color);
        $pal = HTML::$color_hex + $base;
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
        if (!isset($hex)) {
            if (!isset($pal[$color]))
                return false;
            $hex = '' === $tw_i ? $pal[$color] : $pal[$color][$tw_i];
        }
        if ('#' != $hex[0] || !$var && false === $ino)
            return str_replace('&color', $hex, $ary);
        if ('' === $opacity) {
            $var && array_unshift($ary, "$var: 1");
            $opacity = $var ? "var($var)" : $ino;
        } else {
            $opacity = '[' == $opacity[0] ? $this->arbitrary($opacity) : (int)$opacity / 100;
        }
        if ($point)
            array_unshift($ary, $point);
        $dec = implode(' ', array_map('hexdec', str_split(substr($hex, 1), 2)));
        return str_replace(['&color', '&hex'], ["rgb($dec / $opacity)", $hex], $ary);
    }

    function index($grp) {
        $tw = new t_venus('tw');
        $values = $tw->sqlf('#select name, tpl, comp from $_ where tw_id=2');
        foreach ($values as $name => $row) {
            if ('' !== $row->comp)
                $this->defaults[$name] = $row->comp;
            $this->values[$name] = [];
            $n = '=' == $name[0] ? 2 : 3;
            foreach (explode("\n", unl(trim($row->tpl))) as $item) {
                [$k, $v, $v2] = explode(' ', $item, $n) + [2 => false];
                $this->values[$name][$k] = false === $v2 ? $v : [$v, $v2];
            }
        }
        $rules = $tw->sqlf('#select * from $_ where tw_id=0' . $grp);
        foreach ($rules as $row) {
            $minus = false;
            if ('-' == $row->name[0]) {
                $minus = true;
                $row->name = substr($row->name, 1);
            }
//trace($row->name);
            if ($this->mw)
                $this->mw->grp($row->grp, $row->css, $data);
            $lsTitle = count($path = explode(' ', $row->name)) - 1;
            $tpl = explode("\n", unl(trim($row->tpl)));
            $pp = [&$this->idx];
            $rs = [];
            $hash = false;
            $var = 0;
            foreach ($path as $it => $one) { # list of titles
                $ats = [$one];
                $at = '@' == $one[0];
                $n = 0;
                if ('#' == $one[0] || $at) {
                    $var = $it;
                    $ats = [];
                    foreach (explode('|', array_shift($tpl)) as $tmp) {
                        $v = explode('=', $tmp, 2);
                        if ($hash = !$at) {
                            $rs[] = array_splice($tpl, 0, array_search('.' . $v[0], $tpl));
                            array_shift($tpl);
                        } else {
                            $rs[] = $v[1] ?? $v[0];
                        }
                        $ats[] = $v[0];
                    }
                } elseif ('=' == $one[0] && $row->comp) {
                    $this->composite[$one] = explode(' ', $row->comp);
                }
                $p = [];
                foreach ($ats as $ip => $at) {
                    $lsPart = count($atParts = explode('-', $at)) - 1;
                    foreach ($pp as &$_) {
                        foreach ($atParts as $ia => $x) {
                            $prev =& $_;
                            if ('' !== $x) {
                                if (is_num($x))
                                    $x = '_' . $x;
                                isset($_[$x]) or $_[$x] = [];
                                $_ =& $_[$x];
                            }
                            if ($ia == $lsPart) {
                                $p[] =& $_;
                                if ($it == $lsTitle) {
                                    if ($this->mw) {
                                        $path[$var] = $ats[$ip];
                                        $id = $this->mw->css($ip, $path, $data);
                                    }
                                    $replaced = $hash ? $rs[$n++] : ($rs ? str_replace('{@}', $rs[$n++], $tpl) : $tpl);
                                    if ($this->mw)
                                        $replaced += [99 => $id]; # add ID to main index
                                    '' === $x ? ($_ += $replaced) : ($prev[$x] += $replaced);
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
