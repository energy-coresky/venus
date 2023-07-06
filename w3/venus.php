<?php

class Venus extends Console
{
    function __construct($argv = [], $found = []) {
        Plan::$ware = 'venus';
        parent::__construct($argv, $found);
        Plan::$ware = 'main';
    }

    /** Show Venus tables */
    function a_t() {
        $t = new t_venus('unicode');
        print_r($t->head_y()->_tables());
    }

    /** Parse css */
    function a_parse() {
        $css = Plan::_g("assets/venus.css");
        echo Maat::css($css);
    }

    /** Test Maat parser */
    function a_test() {
        $css = Plan::_g("assets/preflight.css");
        Maat::css($css, ['test' => true]);
    }

    /** Search (list) Vesper css classes */
    function a_s($in = '', $pos = 0) {
        $vesp = new Vesper;
        $ary = $vesp->list($in);
        sort($ary[$pos]);
        echo implode("\n", $ary[$pos]);
    }

    /** Debug Vesper index */
    function a_index() {
        $vesp = new Vesper;
        print_r(json_encode($vesp->idx, JSON_PRETTY_PRINT));
    }

    /** Generate Vesper(Tailwind) classes */
    function a_tw($in = null) {
        $maat = new Maat;
        $maat->cls = [$in ?? 'text-red-500 inline-flex'];
        $vesp = new Vesper;
        echo $vesp->v_css($maat);
    }

    /** Work with database */
    function a_base() {
        $m = new t_venus('css');
        $t = $m->sqlf('@select id,txt from $_ where css_id=0 limit 1');
        foreach ($t as $id => $txt) {
  #          $m->sqlf('update $_ set txt=%s where id=%d', html($txt), $id);
        }
    }
}
