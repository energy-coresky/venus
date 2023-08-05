<?php

/** Parse CSS to array, generate CSS from array,
    parse mix files for Vesper (Tailwind) CSS classes
    generate HTML from array
    find difference in Vesper vs Tailwind-CDN generation */
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
    private $code = [];
   private $add_space = false;

    public $cls = [];
    public $js = [];
    public $links = [];

    function __construct($opt = []) {
        trace('new Maat');
        $this->pad = str_pad('', $this->tab = SKY::w('tab_html') ?: 2);
        $this->opt = $opt + $this->opt;
        ini_set('memory_limit', '1024M');
    }

    static function &css($css, $opt = []) {
        $maat = new Maat($opt);
       $maat->add_space = true;
        $new =& $maat->buildCSS($css);
        //$new =& $maat->buildCSS($css, true);
        if ($maat->opt['test'])
            return $maat->test($css, $new);
        return $new;
    }

    function add_raw($str) {
        if (false !== strpos($str, 'class=') && preg_match("/class=['\"]([^'\"]+)['\"]/", $str, $m)) {
            $str = $m[1];
        }
        $this->add_class($str);
    }

    function add_js($js) {
        is_array($js) or $js = preg_split('/\s+/', trim($js));
        $res = [];
        foreach ($js as $v)
            strpos($v, ':') and $res[] = $v;
        foreach ($js as $v) {
            if (strpos($v, ':'))
                continue;
            $v = explode('-', $v)[0];
            $this->js[$v] = array_merge($res, $this->js[$v] ?? []);
        }
    }

    function add_class($classes) {
        $test = function ($x, $y) {
            if ('-' == $y || ':' == $y)
                return true;
            if ('-' == $x || '2' == $x || '[' == $x && ']' == $y) # 2xl:
                return false;
            $n = ord($x);
            return $n > 0x7A || $n < 0x61;
        };
        is_array($classes) or $classes = preg_split('/\s+/', trim($classes));
        foreach ($classes as $v) {
            if ($v && '!' == $v[0])
                $v = substr($v, 1);
            if ('' === $v || $test($v[0], $v[-1]) || isset($this->cls[$v]))
                continue;
            if ('[' == $v[0] && !preg_match("/^\[[\-a-z]+:/", $v))
                continue;
            $this->cls[$v] = 1; # 1 - need to generate
        }
    }

    function tw_native($css, $m) {
        $preflight = '::backdrop';
        $ary =& $this->parse_css($css, $preflight);
       $this->add_space = true;
        $this->code[] = [$css = $this->buildCSS($preflight), substr_count($css, "\n"), 'Preflight', 0];
        $this->code[] = [$this->buildCSS($ary, true), -1, 'TailwindCSS', 0];
       $this->add_space = false;
        #if (trim(strip_tags($css)) != $m->t_settings->preflight())
         #   trace('preflight differ!', true);
    }

   function diff($diff, &$txt, &$size) {
       $x = -1 == $size;
       $lines = explode("\n", $txt);
       $colors = ['=' => $txt = '', '*' => 'ffd', '+' => 'dfd', '.' => 'fdd'];
       $ok = true;
       for ($i = $j = 0, $size = strlen($diff); $i < $size; $i++) {
           $line = $lines[$j];
           if (in_array($z = $diff[$i], ['+', '.']) && $x)
               $z = '+' == $z ? '.' : '+';
           if ($c = $colors[$z]) {
               $x or $ok = false;
               '.' === $z ? ($line = '&nbsp;') : $j++;
               $txt .= '<div class="code" style="background:'."#$c\">$line</div>";
           } else {
               $txt .= $line . "\n";
               $j++;
           }
       }
       $ok or $size = -$size;
   }

    function code($html, $jet, $fn) {
        [$v_css, $v_js] = (new Vesper)->v_css($this);
        $n = substr_count($v_css, "\n");
       if ($this->code && 'Preflight' == $this->code[0][2]) {
           $tw_css =& $this->code[1];
           $diff = Diff::parse($v_css, $tw_css[0]);
           $this->diff($diff, $v_css, $n);
           $this->diff($diff, $tw_css[0], $tw_css[1]);
       }
        $this->code = array_merge($this->code, $jet);
        if ($v_js)
            $this->code[] = [$v_js, substr_count($v_js, "\n"), 'VesperJS', 0];
        $this->code[] = [$v_css, abs($n), $n < 0 ? '<r>VesperCSS</r>' : 'VesperCSS', 0];
        $tpl = '<label><input type="radio" value="%s" onchange="$$.set(this.value)" name="v-panel"> %s</label>';
        $s = '';
        foreach ($this->code as $i => $x)
            $s .= sprintf($tpl, $i + 1, $x[2]);
        array_unshift($this->code, [$html, substr_count($html, "\n"), sprintf($tpl, '0" checked="', 'HTML') . $s, $jet ? 0 : $fn]);
        return $this->code;
    }

    function &buildHTML(&$ary, $indent = '') {
        $cr = ['class' => 'm', 'id' => 'g', 'src' => 'z', 'js' => 'j'];#r { color:red }#y { color:#b88 }
        $cx = ['action' => 'src', 'href' => 'src', 'for' => 'id'];
        $out = '';
        foreach ($ary as $data) {
            $len = strlen($out);
            [$attr, $data] = $data;
            $out .= $indent;
            switch ($node = is_object($attr) ? $attr->{'>'} : $attr) {
            case '#text': # #cdata-section #document #document-fragment
                $out .= $data . "\n";
                continue 2;
            case '#comment':
                $out .= "<span style=\"color:#885\">&lt;!-- $data --&gt;</span>\n";
                continue 2;
            default:
                $tag = "<span class=\"vs-tag\">$node</span>";
                if (is_object($attr)) {
                    unset($attr->{'>'});
                    $join = [];
                    $style = [false, false];
                    foreach ($attr as $k => $v) {
                        $y = false;
                        switch ($k) {
                            case 'rel':
                                $style[0] = 'stylesheet' == $v;
                                break;
                            case 'id':
                                if ('trace-t' == $v && is_array($data)) {
                                    $txt = $this->buildHTML($data);
                                    $this->code[] = [$txt, substr_count($txt, "\n"), 'Trace-T'];
                                    $data = tag('Trace-T', 'class="red_label"', 'span');
                                }
                                break;
                            case 'js':
                                $this->add_js($v);
                                break;
                            case 'class':
                                if ('dev-data' == $v) {
                                    //json
                                    $data = ''; // crop inner
                                }
                                $this->add_class($v);
                                break;
                            case 'src': case 'href': case 'action':
                                $cnt = $this->links["$node-$k"][$v] ?? 0;
                                $this->links["$node-$k"][$v] = ++$cnt;
                                $style[1] = 'link-href' == "$node-$k" ? $v : false;
                                break;
                            default:
                                'on' == substr($k, 0, 2) && ($y = 'js');
                                break;
                        }
                        $x = $y ?: $cx[$k] ?? $k;
                        $g = $cr[$x] ?? false;
                        $join[] = $k . '="' . ($g ? "<$g>$v</$g>" : $v) . '"';
                    }
                    if ($style[0] && $style[1]) {
                        $path = 'http' == substr($style[1], 0, 4) ? $style[1] : LINK . substr($style[1], strlen(PATH));
                        $txt = get($path, '', false);
                        $txt = $this->buildCSS($txt);
                        $this->code[] = [$txt, substr_count($txt, "\n"), $name = explode('?', basename($style[1]))[0]];
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
                } elseif (in_array($node, ['style', 'script'])  && '' !== $data) {
                    $is_js = 'script' == $node;
                    $txt = trim($is_js ? $this->buildJS($data) : $this->buildCSS($data));
                    if (strlen($data) > SKY::w('char_len')) {
                        $this->code[] = [$txt, substr_count($txt, "\n"), $name = ($is_js ? 'JS' : 'CSS') . count($this->code)];
                        $out .= tag($name, 'class="red_label"', 'span');
                    } else {
                        $out .= "\n" . $txt . "\n";
                    }
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

    function buildJS($in) {
        return $this->parse_js($in);
    }

    function &buildCSS(&$ary, $sort = false, $plus = 0) {
        if (is_string($ary))
            $ary =& $this->parse_css($ary);
        if ($sort) usort($ary, function ($a, $b) use ($plus) {
            $ma = $plus < $a[2];
            $mb = $plus < $b[2];
            if (!$ma && !$mb)
                return strcmp($a[0], $b[0]);
            return $ma && $mb ? 0 : ($ma && !$mb ? 1 : -1);
        });
        $pad = str_pad('', $this->tab * $plus);
        $end = 'rich' == $this->opt['format'] ? "\n" : '';
        $out = '';
        foreach ($ary as $one) {
            if ($one[1]) {
                $out .= $pad . $this->name($one[0]) . " {\n";
                if ($one[2] > $plus) {
                    $out .= $this->buildCSS($one[1], $sort, 1 + $plus);
                } else foreach ($one[1] as $prop) {
                   if ($this->add_space)
                       $prop = preg_replace("/^([\w\-]+):/", '$1: ', $prop);
                    $out .= "$pad$this->pad$prop;\n";
                }
                $out .= "$pad}\n$end";
            } else {
                $out .= "$pad$one[0];\n";
            }
        }
        if ($end)
            $out = substr($out, 0, -1);
        return $out;
    }

    function name($str) {
        $ary = [];
        $hl = $this->opt['highlight'];
        foreach (preg_split("/\s*,\s*/", $str) as $v) {
            if (!$hl || in_array($v[0], ['@', ':', '['])) {
                $ary[] = $v;
            } elseif ('.' == $v[0]) {
                $ary[] = '.<m>' . substr($v, 1) . '</m>';
            } elseif ('#' == $v[0]) {
                $ary[] = '#<g>' . substr($v, 1) . '</g>';
            } else {
                $ary[] = '<span class="vs-tag">' . "$v</span>";
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

    function parse_js($in) {
        $str = '<?php ' . unl($in);
        foreach (token_get_all($str) as $k => $token) {
            $id = $str = $token;
            if (is_array($token)) {
                list($id, $str) = $token;
                switch ($id) {
                    case T_CONSTANT_ENCAPSED_STRING:
                        $str = substr($str, 1, -1);
                    case T_ENCAPSED_AND_WHITESPACE:
                        $this->add_raw($str);
                        break;
                }
            }
        }
        return $in;
    }

    function &parse_css(&$in, &$split = null, $plus = 0) {
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
                    } elseif (0 == $depth) {
                        $ary[] = [trim($sum), [], $plus];
                        $sum = '';
                        continue 2;
                    }
                    break;
                case '}':
                    if (0 == --$depth) {
                        if ($has_child) {
                            $null = null;
                            $prop =& $this->parse_css($sum, $null, 1 + $plus);
                        } else {
                            '' === trim($sum) or $prop[] = trim($sum);
                        }
                        $ary[] = [$key, $prop, $plus + $has_child];
                        if ($split === $key) {
                            $split = $ary;
                            $ary = [];
                        }
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
