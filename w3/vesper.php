<?php

class Vesper
{
    private $maat;
    private $tw;
    public $idx;

    function __construct($maat) {
        $this->maat = $maat;
        $this->tw = new t_venus('tw');
        $this->index();
    }

    function tw_css() {
        $list = [];
        trace(print_r($this->maat->cls, 1));
        foreach ($this->maat->cls as $cls) {
            if (is_array($cls))
                $cls = array_shift($cls);
            foreach (explode(' ', $cls) as $one) {
                $ps = explode(':', $one);
                $list[$one] = count($ps) > 1 && ($ist = array_intersect(m_venus::$media, $ps)) ? key($ist) . $one : "0$one";
            }
        }
        asort($list);
        #trace(print_r(array_keys($list), 1));
        #print_r(array_keys($list));
        $ary = [];
        foreach (array_keys($list) as $one)
            $ary[] = $this->genCSS($one);
        return $this->maat->buildCSS($ary);
    }

    function genCSS($cls) {
        $ary = [];
        $p =& $this->idx;
        $cnt = count($list = explode('-', $cls)) - 1;
        foreach ($list as $n => $part) {
            if (isset($p[$part])) {
                $p =& $p[$part];
                if ($cnt == $n)
                    return [array_shift($p), $p, 0];
            } else {
                return [".$cls", ["/* not found */"], 0];
            }
        }
    }

    function _color($name) {
    }

    function _display($name) {
        return 'hidden' == $name ? 'none' : $name;
    }

    function index() {
        fseek($fp = fopen(__FILE__, 'r'), __COMPILER_HALT_OFFSET__);
        $data = strbang(unl(trim(stream_get_contents($fp))));
        fclose($fp);
        foreach ($data as &$v) {
            $vv = explode('|', $v);
            $v = [];
            foreach ($vv as $w) {
                $z = explode('=', $w);
                $v[$z[0]] = $z[1] ?? $z[0];
            }
        }

        $rules = $this->tw->sqlf('@select name, tpl from $_ where tw_id=0');
        //print_r(array_keys($rules));
        $replace = function ($lns, $ary) {
            foreach ($lns as &$v)
                $v = strtr($v, $ary);
            return $lns;
        };
        $this->idx = [];
        foreach ($rules as $key => $val) {
            $val = explode("\n", unl(trim($val)));
            $minus = false;
            if ('-' == $key[0]) {
                $minus = true;
                $key = substr($key, 1);
            }
            $pp = [&$this->idx];
            $last = count($titles = explode(' ', $key)) - 1;
            $_rs = [];
            foreach ($titles as $x => $tt) { # list of titles
                $list = [$tt];
                if ('@' == $tt[0]) {
                    $list = [];
                    foreach (explode('|', array_shift($val)) as $tmp) {
                        $a = explode('=', $tmp, 2);
                        $list[] = $a[0];
                        $_rs[] = ["+$tt" => $a[1] ?? $a[0], $tt => $a[0]];
                    }
                }
                $p = [];
                $rs = $_rs;
                foreach ($list as $name) {
                    $cnt = count($nn = explode('-', $name)) - 1;
                    foreach ($pp as &$_) {
                        foreach ($nn as $y => $z) {
                            isset($_[$z]) or $_[$z] = [];
                            $p[] =& $_[$z];
                            if ($cnt == $y && $x == $last) {
                                $_[$z] = $rs ? $replace($val, array_shift($rs)) : $val;
                            } else {
                                $_ =& $_[$z];
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
/*

*/
__halt_compiler();

qq qq,ww
