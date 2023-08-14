<?php

class Grace /* Vesper JS generator */
{
    public $idx = [];

    function __construct() {
    }

    static function instance() {
        static $grace;
        return $grace ?? ($grace = new self);
    }

    function prepare(&$tpl, &$ps) {
        $tpl = explode("\n", $tpl);
        $out = [];
        foreach (explode('|', array_shift($tpl)) as $one) {
            $out[$one] = array_splice($tpl, 0, array_search(".$one", $tpl));
            array_shift($tpl);
        }
        $tpl = $out;
        $out = [];
        $prev = 'null';
        foreach ($ps as $one) {
            if ('end:hidden' == $one)
                $prev = "'twin'";
            [$pfx, $val] = explode(':', $one);
            $out[$pfx] = array_merge([$val], $out[$pfx] ?? []);
        }
        $ps = $out;
        return $prev;
    }

    function json($tpl, $name, $maat, $ps) {
        $js = [];
        foreach ($tpl as $prop => $ary) {
            $x = [];
            foreach ($ary as $one) {
                $a = explode(' ', $one);
                $i = array_shift($a);
                $maat->add_class($a);
                $x[$i] = $a;
                foreach (($ps['sar'] ?? []) as $one) {
                    [$search, $replace] = explode('=', $one);
                    $maat->add_class(str_replace($search, $replace, $a));
                }
            }
            $js["$name-$prop"] = $x;
        }
        return '    let $js = ' . json_encode($js);
    }

    function buildJS($maat) {
        $out = '';
        $vars = ['name', 'pas', 'code'];
        $listen = function ($end) {
            return $end ? ('hidden' == $end[0] ? "{end: 'hidden'}" : '{}') : 'false';
        };
        do {
            $name = key($maat->js);
            if (!isset($this->idx[$name]))
                continue;
            $ps = pos($maat->js);
            if (isset($this->idx[$name])) {
                [$pas, $tpl] = $this->idx[$name];
                $prev = $this->prepare($tpl, $ps);
                $user = "\n    " . implode("\n    ", $tpl[''] ?? ['']);
                $code = "let prev = $prev, listen = " . $listen($ps['end'] ?? false) . ";\n";
                unset($tpl[''], $tpl['.']);
                $code .= $this->json($tpl, $name, $maat, $ps);
                $code .= $user;
                $out .= <<<DOC
gv.$name = function$pas {
    $code
    gv.start(el, \$js, $$, listen, prev);
};\n
DOC;
            }
        } while (false !== next($maat->js));
        return $out;
    }

    function index($name, $row) {
        [$name, $pas] = explode(' ', $name, 2) + [1 => '()'];
        $this->idx[$name] = [$pas, unl(trim($row->tpl))];
    }
}
