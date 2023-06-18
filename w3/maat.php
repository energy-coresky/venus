<?php

class Maat
{
    private $formats = ['rich', 'no-empty', 'compact', 'min-zed'];
    private $opt = [
        'test' => false,
        'highlight' => false,
        'format' => 'no-empty',
    ];
    private $tab;
    private $pad;

    public $cls = [];

    function __construct($opt = []) {
        $this->pad = str_pad('', $this->tab = SKY::w('tab_html') ?: 2);
        $this->opt = $opt + $this->opt;
        ini_set('memory_limit', '1024M');
    }

    static function &css($css, $opt = []) {
        $maat = new Maat($opt);
        $new =& $maat->buildCSS($css);
        if ($maat->opt['test'])
            return $maat->test($css, $new);
        return $new;
    }

    function &buildHTML(&$ary, $indent = '') {
        $cr = ['class', 'id', 'src'];
        $cx = ['action' => 'src', 'href' => 'src', 'for' => 'id'];
        $out = '';
        foreach ($ary as $data) {
            $len = strlen($out);
            [$attr, $data] = $data;
            $out .= $indent;
            switch ($node = is_object($attr) ? $attr->{'>'} : $attr) {
            case '#text':
                $out .= $data . "\n";
                continue 2;
            case '#comment':
                $out .= "<span class=\"vs-com\">/* $data */</span>\n";
                continue 2;
            default:
                $tag = "<span class=\"vs-tag\">$node</span>";
                if (is_object($attr)) {
                    unset($attr->{'>'});
                    $join = [];
                    foreach ($attr as $k => $v) {
                        if ('class' == $k)
                            $this->cls[] = $v;
                        $x = $cx[$k] ?? $k;
                        $join[] = $k . '="' . (in_array($x, $cr) ? "<span class=\"vs-$x\">$v</span>" : $v) . '"';
                    }
                    $out .= "&lt;$tag " . implode(' ', $join) . '&gt;';
                } else {
                    $out .= "&lt;$tag&gt;";
                }
                if (0 === $data) {
                    $out .= "\n"; # Void element
                    continue 2;
                } elseif (is_array($data)) {
                    $out .= "\n" . $this->buildHTML($data, $indent . $this->pad) . $indent;
                } elseif ('style' == $node) {
                    $out .= '';
                    // $out .= "\n" . trim($this->buildCSS($data)) . "\n";
                } elseif ('' !== $data && strlen($data . $out) > $len + 280) {
                    $out .= "\n$indent$this->pad$data\n$indent";
                } else {
                    $out .= $data;
                }
                $out .= "&lt;/$tag&gt;\n";
            }
        }
        return $out;
    }

    function &buildCSS(&$ary, $plus = 0) {
        if (is_string($ary))
            $ary =& $this->parse($ary);
        $pad = str_pad('', $this->tab * $plus);
        $end = 'rich' == $this->opt['format'] ? "\n" : '';
        $out = '';
        foreach ($ary as $one) {
            $out .= $pad . $this->name($one[0]) . " {\n";
            if ($one[2] > $plus) {
                $out .= $this->buildCSS($one[1], 1 + $plus);
            } else foreach ($one[1] as $prop) {
                $out .= "$pad$this->pad$prop;\n";
            }
            $out .= "$pad}\n$end";
        }
        if ($end)
            $out = substr($out, 0, -1);
        return $out;
    }

    function name($str) {
        $ary = [];
        $hl = $this->opt['highlight'];
        foreach (preg_split("/\s*,\s*/", $str) as $v) {
            if ($hl) switch ($v[0]) {
                case '.': $ary[] = '.<span class="vs-class">' . substr($v, 1) . '</span>';
                    break;
                case '#': $ary[] = '#<span class="vs-id">' . substr($v, 1) . '</span>';
                    break;
                case '@': case ':': case '[': $ary[] = $v;
                    break;
                default: $ary[] = '<span class="vs-tag">' . "$v</span>";
                    break;
            }
        }
        return implode(', ', $ary);
    }

    function test(&$css, &$s2) { # for lost chars
        $s1 = preg_replace("/\s+/", '', $css); # may have comments
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
                    if ('#' != $str && '*' == $str[1]) # //
                        continue;
                    if ("\n" != $str[-1] && " " != $str[-1])
                        $space = true;
                    $str = trim($str);
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
