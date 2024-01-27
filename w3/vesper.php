<?php

class Vesper /* Tailwind++ generator */
{
    public $idx = [];
    public $last_color = '';
    public $idx_e;//exact
    public $idx_s;//similar
    public $defaults = ['~num1' => '1px', '~num3' => '3px', '~nump' => '100%'];

    private $values = ['^a' => ['auto' => 'auto'], '^spacing' => []];
    private $color;
    private $grace;

    function __construct($grp = '', ?Maxwell $mw = null, &$id_base = null) {
        $grp = !$grp || 'all' == $grp ? '' : " and grp='$grp'";
        $this->values['^'] =& $this->values['^spacing']; # set alias for ^spacing
        $this->grace = Grace::instance();
        $list = $this->index($grp, $mw ?? false, $id_base ?? 0);
        if (null !== $id_base)
            $id_base = $list;
    }

    function bag($maat) {
        $ve = [];
        $tpl = $maat->tag ? $this->grace->buildVE($maat, $ve) : ''; # run this first
        $js = $ve || $maat->js ? $this->grace->buildJS($maat, $ve) : '';
        $ary = $this->listCSS($maat->cls);
        return [$maat->buildCSS($ary, !SKY::w('vesper')), $tpl, $js];
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
            $css = $this->listCSS([$cls => 1])[0] ?? false;
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

    function listCSS($ary, &$id = null) {
        $mkey = m_venus::$media; // @media (prefers-color-scheme: dark)
        $mval = m_venus::media();
        $media = array_combine($mkey, array_pad([], 6, []));
        foreach ($ary as $name => $marker) {
            #if (':' == $name[0])
             #   $name = substr($name, 1);
            if (1 != $marker || is_num($name))
                continue;
            $ps = '[' == $name[0] ? [$name] : explode(':', $name);
            $one = array_pop($ps);
            if ($sct = array_intersect($mkey, $ps)) {
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
        $ary = [];
        $pp =& $ary;
        $depth = 0;
        foreach ($media as $sct => $xx) {
            if ($xx && $sct) {
                $px = $mval[array_search($sct, $mkey)];
                $ary[] = ["@media (min-width: {$px}px)", [], $depth = 1];
                $pp =& $ary[count($ary) - 1][1];
            }
            foreach ($xx as $name => $yy) {
                [$one, $ps] = $yy;
                if (in_array($one, ['group', 'dark']))
                    continue;
                $this->color = 0;
                if (!$css = $this->genCSS($one))
                    continue;
                if (null !== $id)
                    $id = array_pop($css);
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
                        $name .= ':' . implode(':', array_reverse($ps));
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
            $len = strlen($br = Boot::bracket(substr($v, 4 + $pos)));
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
        foreach ($list as $n => $x) {
            $_ = is_num($x) ? '_' . $x : $x;
            if (isset($pp[$_])) {
                $pp =& $pp[$_];
                if ($last == $n)
                    return '[' == $x[0] ? $arbitrary($pp, $x, $x) : array_filter($pp, 'is_string');
                continue;
            } elseif ($last == $n) {
                foreach ($pp as $k => $v) {
                    $k = (string)$k;
                    if (in_array($k[0], ['^', '~']) && $this->value($k, $x, $cv)) {
                        return str_replace('{0}', $minus . $cv, array_filter($v, 'is_string'));
                    } elseif ('[' == $x[0] && '[' == $k[0]) {
                        return $arbitrary($v, $x, $k);
                    }
                }
            }
            if (isset($pp['&color']) && $last - $n < 2) {
                $this->color = implode('-', array_slice($list, 0, $n) + [-1 => '&color']);
                return $this->color($x, $list[1 + $n] ?? '', array_filter($pp['&color'], 'is_string'));
            } elseif ($x && '[' == $x[0]) {
                return $arbitrary([$x], $x, $x);
            } else {
                return false;
            }
        }
        return false;
    }

    function samples($last) {
        if ('&color' == $last)
            return $this->color(0, 0, 0, true);
        $ary = [];
        foreach (explode('+', $last) as $last) {
            if ('^' == $last[0])
                $ary = array_merge($ary, array_keys($this->values[$last]));
        }
        if ('~' == $last[0])
            $ary = array_merge($ary, $this->num(0, $last[-1], true));
        return $ary;
    }

    function value($in, $val, &$cv) {
        if ('[' == $val[0] && ']' == $val[-1] && '#' != $val[1]) {
            $cv = $this->arbitrary($val);
            return true;
        }
        foreach (explode('+', $in) as $in) {
            if ('~' == $in[0]) {
                $cv = $this->num($val, $in[-1]);
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

    function num($in, $x, $samples = false) {
        if ('f' == $x) { # fractions
            if ($samples) {
                $out = ['full'];
                for ($i = 1; $i < 12; $out[] = "$i/12", $i++);
                return $out;
            }
            if ('full' == $in)
                return '100%';
            if (!preg_match("~^(\d+)/(\d+)$~", $in, $m))
                return false;
            return round(100 * $m[1] / $m[2], 6) . '%';
        }
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
        $opacity = $dec = $tw_i = '';
        $point = '.' == $ary[0][0] ? array_shift($ary) : false;
        if ($var = array_shift($ary))
            $var = array_shift($ary);
        $pal = $base + ['none' => 'none'] + HTML::$color_hex;
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
        if ('[' == $color[0] && ']' == $color[-1]) { # Arbitrary value
            $hex = $this->last_color = $this->arbitrary($color);
        } else {
            if (strpos($color, '/'))
                [$color, $opacity] = explode('/', $color);
            if (!isset($pal[$color]))
                return false;
            $hex = $this->last_color = '' === $tw_i ? $pal[$color] : $pal[$color][$tw_i];
        }
        if ($hc = '#' == $this->last_color[0])
            $dec = implode(' ', array_map('hexdec', str_split(substr($this->last_color, 1), 2)));
        if ('' !== $opacity) {
            $opacity = '[' == $opacity[0] ? $this->arbitrary($opacity) : (int)$opacity / 100;
            $hex = "rgb($dec / $opacity)";
        } elseif ($hc) {
            $var && array_unshift($ary, "$var: 1");
            $opacity = $var ? "var($var)" : 0;
        }
        if ($point)
            array_unshift($ary, $point);
        return str_replace(['&color', '&hex'], [$hc ? "rgb($dec / $opacity)" : $this->last_color, $hex], $ary);
    }

    function index($grp, $mw, $id_base) {
        $tw = new t_venus('tw');
        $values = $tw->sqlf('#select name, tpl, css from $_ where tw_id=2');
        foreach ($values as $name => $row) {
            if ('^' != $name[0]) {
                $this->grace->index($name, $row);
                continue;
            }
            if ('' !== $row->css)
                $this->defaults[$name] = $row->css;
            foreach (explode("\n", unl(trim($row->tpl))) as $item) {
                [$k, $v] = explode(' ', $item, 2);
                $this->values[$name][$k] = $v;
            }
        }
        $list = [];
        $rules = $tw->sqlf('#select *,id as id_base from $_ where tw_id=0' . $grp);
        foreach ($rules as $row) {
            $row->minus = false;
            if ('-' == $row->name[0]) {
                $row->minus = true;
                $row->name = substr($row->name, 1);
            }
            if ($mw)
                $mw->grp($row, $data);
            $lsTitle = count($path = explode(' ', $row->name)) - 1;
            $tpl = explode("\n", unl(trim($row->tpl)));
            $pp = [&$this->idx];
            $rs = $mem = [];
            $hash = false;
            $secAt = 0;
            foreach ($path as $it => $one) { # list of titles
                $ats = [$one];
                $at = '@' == $one[0];
                $n = 0;
                if ('#' == $one[0] || $at) {
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
                    $var = $it;
                    $mem = $ats;
                }
                $p = [];
                loop:
                foreach ($ats as $m0 => $at) {
                    if ($at && '@' == $at[0]) {
                        $hash = false;
                        $tpl = $rs[$n];
                        $n = 0;
                        $secAt = 1;
                        $ats = $rs = [];
                        foreach (explode('|', array_shift($tpl)) as $tmp) {
                            $v = explode('=', $tmp, 2);
                            $rs[] = $v[1] ?? $v[0];
                            $ats[] = $v[0];
                        }
                        $mem = $ats;
                        goto loop;
                    }
                    $lsPart = count($atParts = explode('-', $at)) - 1;
                    foreach ($pp as $m1 => &$_) {
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
                                    if ($mw) {
                                        if ($mem)
                                            $path[$var] = $mem[$m0 + $m1];
                                        $id = $mw->push($m0 + $m1 + $secAt, $path, $data, $this);
                                        if ($id_base == $row->id_base)
                                            $list[$id] = 0;
                                    }
                                    $replaced = $hash ? $rs[$n++] : ($rs ? str_replace('{@}', $rs[$n++], $tpl) : $tpl);
                                    if ($mw)
                                        $replaced += [99 => (string)$id]; # add ID to main index
                                    '' === $x ? ($_ += $replaced) : ($prev[$x] += $replaced);
                                    if ($default = $this->defaults[$x] ?? false)
                                        $prev += str_replace('{0}', $default, $_);
                                }
                            }
                        }
                    }
                }
                $pp = $p;
            }
        }
        return array_keys($list);
    }
}
