<?php

class Maxwell
{
    public $idx;
    public $idx_id;
    //public $idx_reverse;

    private $used;

    static $grp = ['text', 'box', 'border', 'background', 'flex-grid', 'effect', 'other'];

    function __construct() {
        $this->idx = array_combine(self::$grp, array_pad([], 7, []));
        $this->idx_id = [];
    }

    function grp($grp, $syn, &$data) {
        if ($hash = '#' == $syn[0])
            $syn = substr($syn, 1);
        $cnt = count($ary = explode(' ', $syn, 2)) - 1;
        $pp =& $this->idx[$grp];
        foreach ($ary as $n => $one) {
            is_num($one[0]) or $one = "6$one";
            if ($hash && $n == $cnt) {
                $hash = $one;
            } else {
                isset($pp[$one]) or $pp[$one] = [];
                $pp =& $pp[$one];
            }
        }
        $data = [&$pp, $hash];
    }

    function css($ip, $path, &$data) {
        static $id;

        //$ary
        [&$pp, $hash] = $data;
        //$cnt = count($ary) - 1;
        if (!$ip || $hash) {
            $id = count($this->idx_id); # new id
            $this->idx_id[$id] = [];
        }
        if ($hash) {
            $sort = $hash[0];
            $k = substr($hash, 1) . '-' . implode('-', $path);
            $pp[$sort . trim($k, '-')] = [0];
        } else {
            $this->idx_id[$id][] = implode('-', $path);
            $pp[0] = $id;
        }

     return $id;

        $out = [];
        $j = 0;
        foreach ($ary as $i => $a) {
            $out[$i] = array_shift($a);
            if ($a)
                $j = $i;
        }
        foreach ($ary[$j] as $v) {
            $out[$j] = $v;
            if ($hash) {
                $sort = $hash[0];
                $k = substr($hash, 1) . '-' . implode('-', array_slice($out, 0, $cnt));
                $pp[$sort . trim($k, '-')] = [$out[$cnt]];
            } else {
                $this->idx_id[$cnt_opt][] = implode('-', $out);
                $pp[0] = $cnt_opt;
            }
        }
    }

    function sub($sub, &$id) {
        if (is_int(key($sub))) {
            $id = $sub[0];
            return false;
        }
        $id = 0;
        return array_map(function ($v) {
            return substr($v, 1);
        }, array_keys($sub));
    }

    function used_classes($vs, $classes = []) {
        $this->used = [];
        foreach ($classes as $cls) {
            $ps = explode(':', $cls);
            $cls = array_pop($ps);
            [$css, $node, $last_node] = $vs->listCSS([$cls]);
/*
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
*/
        }
    }

    function index() {
        return function ($i) {
            return 7 == $i->__i ? false : [
                'top' => $top = key($row = array_slice($this->idx, $i->__i, 1, true)),
                'L2' => new eVar(function ($i) use (&$row) {
                    if (!$i->__i) {
                        $row = pos($row);
                        ksort($row);
                    } elseif (!$row) {
                        return false;
                    }
                    $menu = substr(key($row), 1);
                    $sub = array_shift($row);
                    ksort($sub);
                    return [
                        'menu' => $menu,
                        'sub' => $this->sub($sub, $id),
                        'id' => $id,
                    ];
                }),
                'cls' => $this->used[$top] ?? [],
            ];
        };
    }

    function test($idx) {
        print_r($idx ? $this->idx_id : json_encode($this->idx, JSON_PRETTY_PRINT));
      return;

        $e = new eVar($this->index($vs));
        foreach ($e as $grp) {
            echo "$grp->name\n";
            foreach ($grp->L2 as $row) {
                echo "  $row->sub\n";
                foreach ($row->res as $res) {
                    $res = substr($res, 1);
                    echo "    $res\n";
                }
            }
        }
    }
}
