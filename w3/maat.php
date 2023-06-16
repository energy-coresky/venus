<?php

class Maat
{
    function __construct() {
    }

    static function parse_css($css) {
        $maat = new Maat;
        ini_set('memory_limit', '1024M');
        //$css = Plan::_g("assets/tailwin.css");
        return $maat->reBuild($css);
        //$this->test($css);
    }

    function test(&$css) { # for lost chars
        $s1 = preg_replace("/\s+/", '', $css); # may have comments
        $ary =& $this->parse($css);
        $s2 =& $this->toString($ary);
        $s2 = preg_replace("/\s+/", '', $s2); # comments cropped
        $diff = [];
        for ($i = $cx = 0, $cnt = strlen($s1); $i < $cnt; $i++) {
            if (!isset($s2[$i - $cx]))
                exit('Failed length'); # for console
            if ($s1[$i] === $s2[$i - $cx])
                continue;
            if ('}' === $s1[$i] && ';' === $s2[$i - $cx]) { # semicolon added
                $cx--;
                continue;
            }
            $pos = strpos($s1, '*/', $i);
            if (false === $pos) {
                $diff[] = substr($s1, $i);
                break;
            }
            $diff[] = substr($s1, $i, 2 + $pos - $i);
            $cx += 2 + $pos - $i;
            $i = 1 + $pos;
        }
        $cnt = count($diff);
        echo $i - $cx == strlen($s2)
            ? "Test passed, found $cnt comments, first 10:\n"
            : 'Test failed';
        print_r(array_slice($diff, 0, 10));
    }

    function reBuild(&$css) {
        $ary =& $this->parse($css);
        return $this->toString($ary);
    }

    function &toString(&$ary, $plus = 0) {
        $out = '';
        $pad = str_pad('', 2 * $plus, ' ');
        foreach ($ary as $one) {
            $out .= $pad . $one[0] . " {\n";
            if ($one[2] > $plus) {
                $out .= $this->toString($one[1], 1 + $plus);
            } else foreach ($one[1] as $prop) {
                $out .= "$pad  $prop;\n";
            }
            $out .= "$pad}\n\n";
        }
        $out = substr($out, 0, -1);
        return $out;
    }

    function &parse(&$in, $plus = 0) {
        $in = preg_replace("~(#+|//+)~", "$1\n", '<?php ' . unl($in));
        $depth = $has_child = 0;
        $sum = '';
        $ary = [];
        foreach (token_get_all($in) as $k => $token) {
            $id = $str = $token;
            if (is_array($token)) {
                list($id, $str) = $token;
                if (!$k || T_DOC_COMMENT == $id || $space) {
                    $space = false;
                    continue;
                }
                if (T_WHITESPACE == $id) {
                    $str = ' '; # 2do: comment between spaces
                } elseif (T_COMMENT == $id) {
                    if ('#' != $str && '*' == $str[1])
                        continue;
                    $space = true;
                }
            }
            switch ($id) {
                case '{':
                    if (1 == ++$depth) {
                        $key = trim($sum);
                        $prop = [];
                        $sum = '';
                        continue 2;
                    } elseif (2  == $depth) {
                        $has_child = 1;
                    }
                    break;
                case ';':
                    if (1 == $depth) {
                        $prop[] = trim($sum);
                        $sum = '';
                        continue 2;
                    }
                    break;
                case '}':
                    if (0 == --$depth) {
                        if ($has_child) {
                            $prop =& $this->parse($sum, 1 + $plus);
                        } else {
                            '' === trim($sum) or $prop[] = trim($sum);
                        }
                        $ary[] = [$key, $prop, $plus + $has_child];
                        $sum = '';
                        $has_child = 0;
                        continue 2;
                    }
                    break;
            }
            $sum .= preg_replace("~(#+|//+)\n~", "$1", $str);
        }
        return $ary;
    }
}
