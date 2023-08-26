<?php

/** Generate Vesper CSS menu for Syntax Utility
    Find friend CSS classes for single class or $id_base
*/
class Maxwell
{
    public $menu;
    public $ids = [0];
    //public $idx_reverse;

    private $used;

    static $grp = ['text', 'box', 'border', 'background', 'flex-grid', 'effect', 'other'];

    const ID_OPT = 1;

    function __construct() {
        $this->menu = array_combine(self::$grp, array_pad([], 7, []));
    }

    static function friends($cls) {
        $vs = new Vesper('', $mw = new self);
        $id = 0;
        $css = $vs->listCSS([$cls => 1], $id);
        $row = $mw->row($id, $vs, $grp, $cls, $css);
        return $row->opt;
    }

    static function classes($id_base) {
        $vs = new Vesper('', $mw = new self, $id_base);
        $list = [];
        foreach ($id_base as $id)
            $list = array_merge($list, $mw->explode($id, $vs));
        return $list;
    }

    function explode($id, $vs) {
        $list = [];
        foreach ($this->ids[$id][1] as $opt) {
            if (preg_match("/^(.*?)([~&\^].*)$/", $opt, $m)) {
                $opt = $vs->samples($m[2]);
                $list = array_merge($list, array_map(function ($v) use ($m) {
                    return $m[1] . $v;
                }, $opt));
            } else {
                $list[] = $opt;
            }
        }
        return $list;
    }

    function grp($row, &$data) {
        static $noname = 1;
        if (!$row->css)
            $row->css = '9no-name-' . $noname++;
        $syn = ($hash = '#' == $row->css[0]) ? substr($row->css, 1) : $row->css;
        $cnt = count($ary = explode(' ', $syn, 2)) - 1;
        $pp =& $this->menu[$row->grp];
        foreach ($ary as $n => $one) {
            is_num($one[0]) or $one = "6$one";
            if ($hash && $n == $cnt) {
                $hash = $one;
            } else {
                isset($pp[$one]) or $pp[$one] = [];
                $pp =& $pp[$one];
            }
        }
        $data = [&$pp, $hash, $row];
    }

    function push($iz, $path, &$data, $vs) {
        static $id;
        [&$pp, $hash, $row] = $data;

        if (!$iz || $hash) {
            $id = count($this->ids); # new id
            $this->ids[$id] = [$row->minus, [], $row->grp, $row->id_base];
        }
        $path = array_filter($path, function ($v) {
            return '' !== $v;
        });
        $cnt = count($list = explode('+', end($path))) - 1;
        foreach ($list as $n => $one) {
            array_pop($path);
            $tmp = $path;
            if ('^a' == $one)
                $one = 'auto';
            array_push($path, $one);
            $this->ids[$id][1][] = implode('-', $path);
            if ($cnt == $n && isset($vs->defaults[$one]))
                $this->ids[$id][1][] = implode('-', $tmp);
        }
        if ($hash) {
            $sort = $hash[0];
            array_pop($path);
            $k = substr($hash, 1) . '-' . implode('-', $path);
            $pp[$sort . trim($k, '-')] = $id;
        } else {
            $pp = $id;
        }
        return $id;
    }

    function row($id, $vs, &$grp, $cls = false, $css = false) {
        static $maat;
        $maat or $maat = new Maat;
        [$minus, $opt, $grp, $id_base] = $this->ids[$id];
        $cls or $cls = $opt[0];
        $ps = explode(':', $cls);
        array_pop($ps);
        $css or $css = $vs->listCSS([$cls => 1], $id);
        $mul = count($css) > 1;
        $txt = $maat->buildCSS($css);
        if (!$mul && '@' != $txt[0])
            $txt = str_replace("\n  ", "\n", substr($txt, 3 + strpos($txt, "\n"), -2));
        return (object)[
            'bg' => $bg = strpos($opt[0], '&') ? $vs->last_color : '',
            'cls' => $bg ? tag($cls, 'style="color:#fff;mix-blend-mode:difference"', 'span') : $cls,
            'opt' => $opt,
            'css' => tag($txt, ''),
            'ps' => $ps,
            'id_base' => $id_base,
        ];
    }

    function menu($vs, $ary) {
        $used = [];
        foreach ($ary as $cls) {
            $id = 0;
            $css = $vs->listCSS([$cls => 1], $id);
            if (!$id)
                continue;
            $row = $this->row($id, $vs, $grp, $cls, $css);
            $used[$grp][] = $row;
        }
        return function ($i) use ($used) {
            return 7 == $i->__i ? false : [
                'top' => $top = key($row = array_slice($this->menu, $i->__i, 1, true)),
                'cls' => $used[$top] ?? [],
                'L2' => new eVar(function ($i) use (&$row) {
                    if (!$i->__i) {
                        $row = pos($row);
                        ksort($row);
                    } elseif (!$row) {
                        return false;
                    }
                    $menu = substr(key($row), 1);
                    $sub = array_shift($row);
                    $id = is_array($sub) ? !ksort($sub) : $sub;
                    return [
                        'menu' => $menu,
                        'sub' => $sub,
                        'id' => $id,
                    ];
                }),
            ];
        };
    }
}
