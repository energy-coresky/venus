<?php

class Vesper
{
    public $idx;

    private $maat;
    private $tw;
    private $json;

    function __construct($maat) {
        $this->maat = $maat;
        $this->tw = new t_venus('tw');
        fseek($fp = fopen(__FILE__, 'r'), __COMPILER_HALT_OFFSET__);
        $this->json = json_decode(stream_get_contents($fp), true);
        fclose($fp);
        $this->index();
    }

    function tw_css() {
        $list = [];
        trace(print_r($this->maat->cls, 1));
        foreach ($this->maat->cls as $cls) {
            #if (is_array($cls))                ######
             #   $cls = array_shift($cls);
            foreach (explode(' ', $cls) as $one) {
                $ps = explode(':', $one);
                $list[$one] = count($ps) > 1 && ($ist = array_intersect(m_venus::$media, $ps)) ? key($ist) . $one : "0$one";
            }
        }
        asort($list);
        #trace(print_r(array_keys($list), 1));
        #print_r(array_keys($list));
        $ary = [];
        foreach (array_keys($list) as $one) {
            $ps = explode(':', $one);
            $one = array_pop($ps);
            $css = $this->genCSS($one, $ps);
            $s = array_shift($css);
            $name = ".$one";
            if ($ps = implode(':', $ps))
                $name = '.' . str_replace(':', '\\:', $ps) . "\\:$one:$ps";
            $ary[] = [$name . substr($s, 1), $css, 0];
        }
        return $this->maat->buildCSS($ary);
    }

    function genCSS($cls, $ps) {
        $p =& $this->idx;
        $minus = '';
        if ('-' == $cls[0]) {
            $minus = '-';
            $cls = substr($cls, 1);
        }
        $cnt = count($list = explode('-', $cls)) - 1;
        foreach ($list as $n => $part) {
            if (isset($p[$part]) && !is_num($part)) {
                $p =& $p[$part];
                if ($cnt == $n)
                    return array_filter($p, 'is_string');
                continue;
            } elseif ($cnt == $n) {
                foreach ($p as $k => $v) {
                    $k = (string)$k;
                    $or = '|' == $k[0];
                    if ($or || '=' == $k[0]) {
                        $cv = $this->json[$k];
                        if (isset($cv[$part])) {
                            $cv = $cv[$part];
                            return $or
                                ? str_replace(["|$k", $k], [$cv[1], $cv[0]], array_filter($v, 'is_string'))
                                : str_replace($k, $minus . $cv, array_filter($v, 'is_string'));
                        }
                    } elseif ('~' == $k[0]) {
                        $r = call_user_func([$this, '_' . substr($k, 1, -1)], $part, $k[-1]);
                        if (false !== $r)
                            return str_replace($k, $r, array_filter($v, 'is_string'));
                    }
                }
            }
            if (isset($p['&color']) && $cnt - $n < 2) {
                if ($v = $this->_color($part, $list[1 + $n] ?? '', $p['&color']))
                    return $v;
            }
        }
        return ['.', "/* not found */"];
    }

    function _inse($in, $x = '@') {
        return 1;
    }

    function _spac($in, $x = '@') {
        return 1;
    }

    function _line_($in, $x = '@') {
        return 1;
    }

    function _num($in, $x = '@') {
        if (!is_num($in))
            return false;
        if ('@' == $x)
            return 1;
        return '/' == $x ? $in / 100 : $in;
    }

    function _color($cc, $dd, $ary) {
        $list = array_keys(Tailwind::$color3);
        if (!in_array($cc, $list))
            return false;
        $var = array_shift($ary);
        [$dd, $op] = explode('/', $dd) + [1 => ''];
        $hex = Tailwind::$color3[$cc][950 == $dd ? 10 : floor($dd / 100)];
        $dec = implode(' ', array_map('hexdec', str_split(substr($hex, 1), 2)));
        if ('' === $op) {
            array_splice($ary, 1, 0, "$var: 1");
            $_op = "var($var)";
        } else {
            $_op = (int)$op / 100;
        }
        $color = "rgb($dec / $_op)";
        return str_replace('&color', $color, $ary);
    }

    function index() {
        $rules = $this->tw->sqlf('@select name, tpl from $_ where tw_id=0');
        //print_r(array_keys($rules));
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
            $rs = [];
            foreach ($titles as $x => $tt) { # list of titles
                $list = [$tt];
                if ('@' == $tt[0]) {
                    $list = [];
                    foreach (explode('|', array_shift($val)) as $tmp) {
                        $a = explode('=', $tmp, 2);
                        $list[] = $a[0];
                        $rs[] = [$tt, $a[1] ?? $a[0]];
                    }
                }
                $p = [];
                $s = 0;
                foreach ($list as $name) {
                    $cnt = count($nn = explode('-', $name)) - 1;
                    foreach ($pp as &$_) {
                        foreach ($nn as $y => $z) {
                            if ('' !== $z) {
                                isset($_[$z]) or $_[$z] = [];
                                $p[] =& $_[$z];
                                if ($cnt == $y && $x == $last) {
                                    $_[$z] = $rs ? str_replace($rs[$s][0], $rs[$s++][1], $val) : $val;
                                    if ($z == '~num~')
                                        $_ += str_replace('~num~', $this->_num(1), $_[$z]);
                                }
                                $_ =& $_[$z];
                            } else {
                                $p[] =& $_;
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

{
  "=weight":{"thin":100,"extralight":200,"light":300,"normal":400,"medium":500,"semibold":600,"bold":700,"extrabold":800,"black":900},
  "=blur":{"0":"0","none":"0","sm":"4px","DEFAULT":"8px","md":"12px","lg":"16px","xl":"24px","2xl":"40px","3xl":"64px"},
  "=radius":{"none":"0px","sm":"0.125rem","DEFAULT":"0.25rem","md":"0.375rem","lg":"0.5rem","xl":"0.75rem","2xl":"1rem","3xl":"1.5rem","full":"9999px"},
  "|size":{"xs":["0.75rem","1rem"],"sm":["0.875rem","1.25rem"],"base":["1rem","1.5rem"],"lg":["1.125rem","1.75rem"],"xl":["1.25rem","1.75rem"],
    "2xl":["1.5rem","2rem"],"3xl":["1.875rem","2.25rem"],"4xl":["2.25rem","2.5rem"],"5xl":["3rem","1"],
    "6xl":["3.75rem","1"],"7xl":["4.5rem","1"],"8xl":["6rem","1"],"9xl":["8rem","1"]},
  "=spacing":{"px":"1px","0":"0px","0.5":"0.125rem","1":"0.25rem","1.5":"0.375rem","2":"0.5rem","2.5":"0.625rem","3":"0.75rem",
	"3.5":"0.875rem","4":"1rem","5":"1.25rem","6":"1.5rem","7":"1.75rem","8":"2rem","9":"2.25rem","10":"2.5rem","11":"2.75rem",
	"12":"3rem","14":"3.5rem","16":"4rem","20":"5rem","24":"6rem","28":"7rem","32":"8rem","36":"9rem","40":"10rem",
	"44":"11rem","48":"12rem","52":"13rem","56":"14rem","60":"15rem","64":"16rem","72":"18rem","80":"20rem","96":"24rem"}
}
