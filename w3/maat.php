<?php

class Maat
{
    function __construct() {
        $this->parse();
    }

    function parse() {
        $str = '<?php ' . Plan::_g("assets/tailwind.css");
        //$str = '<?php ' . Plan::_g("assets/t.css");
        $i = 0;
        $ary = [[], 'cls' => []];
        $cls = [];
        $n0 = '';
        $n1 = $n2 = false;
        foreach (token_get_all(unl($str)) as $token) {
            $id = $str = $token;
            if (is_array($token)) {
                list($id, $str) = $token;
                if (in_array($id, [T_OPEN_TAG, T_COMMENT, T_DOC_COMMENT, T_WHITESPACE]))
                    continue;
                //@$ary[token_name($id)]++;
                switch ($id) {
                    case T_CONSTANT_ENCAPSED_STRING://"Courier New" 'text'
                        break;
                    case T_STRING:
                        ;
                        break;
                }
            } else {
                //@$ary[$id]++;
            }
            switch ($id) {
                case '{':
                    if (1 == ++$i) {
                        if ('.' == $n0[0]) {
                            $n0 = substr($n0, 1);
                            if ($pos = strpos($n0, '-', 1))
                                $n0 = substr($n0, 0, $pos);
                            $cls[$n0] = 1;
                        } else {
                            $ary[0][] = $n0;
                        }
                        $n0 = false;
                        $n1 = $str = '';
                    } elseif (2 == $i) {
                        //$ary[] = $n1;
                        $n1 = false;
                    }
                    break;
                case '}':
                    if (0 == --$i) {
                        $n0 = $str = '';
                    } elseif (1 == $i) {
                        $n1 = $str = '';
                    }
                    break;
            }
            if (false !== $n0)
                $n0 .= $str;
            if (false !== $n1)
                $n1 .= $str;
            
        }
        ksort($cls);
        var_export($cls);
        
    }
}
/*
    --[T_OPEN_TAG] => 1
    --[T_COMMENT] => 6631
    --[T_WHITESPACE] => 318944
    --[T_DOC_COMMENT] => 37
    --[T_CONSTANT_ENCAPSED_STRING] => 125
    [T_VAR] => 20384  var(--tw-ring-inset)
    [T_DOUBLE_COLON] => 2401 ::
    [T_NS_SEPARATOR] => 61908 \
    [T_LNUMBER] => 121719 105
    [T_DNUMBER] => 12838  1.15   .5
    [T_STRING] => 425535 tw hover
*
xxx
xxx[title]
[type='button'] [hidden]
#xxx
.xxx
::xxx
:xxx
@media { }

    [T_DEC] => 50961 -- (variable start)
    [T_LIST] => 74
    [T_EMPTY] => 141
    [T_CASE] => 6
    [T_CLONE] => 18
    [T_DEFAULT] => 12
    [T_PRINT] => 3
    [T_UNSET] => 5
    [T_LOGICAL_AND] => 1
    [T_STATIC] => 30
    [T_FUNCTION] => 68
    [T_BREAK] => 66

(
    [*] => 1805
    [,] => 49304
    [{] => 41563 ===
    [-] => 229034
    [:] => 137943
    [;] => 56937
    [}] => 41563
    [%] => 1866
    [[] => 3149 ===
    []] => 3149
    [=] => 48
    [(] => 41785 ???
    [)] => 41427
    [+] => 111
    [.] => 43415
    [@] => 43
    [/] => 1302
    [>] => 1548
    [~] => 1548
)
*/