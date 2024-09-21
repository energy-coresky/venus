<?php

namespace venus;
use Plan, Maat, Vesper, Maxwell;

class app extends \Console
{
    function __construct($argv = [], $found = []) {
        Plan::set('venus', fn() => parent::__construct($argv, $found));
    }

    /** Test Maat parser */
    function a_test() {
        $css = Plan::_g("assets/preflight.css");
        Maat::css($css, ['test' => true]);
    }

    /** Parse vesper.css */
    function a_vesper() {
        $maat = new Maat;
        $css = (new m_venus)->vesper($maat);
        print_r($maat->cls);
    }

    /** Generate vesper.css itself */
    function a_self() {
        m_venus::self();
    }

    /** Test mix parser */
    function a_js() {
        $maat = new Maat;
        $maat->parse_js(Plan::_g("mvc/_venus.jet"));
        print_r($maat->cls);
    }

    /** Parse css */
    function a_parse($fn = false) {
        $s = Plan::_g($fn ?: "assets/venus.css");
        $maat = new Maat;
        print_r($maat->parse_css($s));
        //echo Maat::css($s);
    }

    /** Debug Vesper index */
    function a_iv($mw = 0) {
        $vs = new Vesper('', $mw ? new Maxwell : null);
        ksort($vs->idx);
        print_r(json_encode($vs->idx, JSON_PRETTY_PRINT));
    }

    /** Test Vesper(Tailwind) classes */
    function a_tw($in = null) {
        $maat = new Maat;
        $maat->add_class($in ?? 'fixed flex');
        echo implode("\n", (new Vesper)->bag($maat));
    }



    /** Friend classes */
    function a_caret($in = 'flex') {
        $vesp = new Vesper;
        $ary = $vesp->caret(explode(' ', $in));
        #ksort($vesp->idx_s);
        #print_r($vesp->idx_s);
        print_r($ary);
    }

    /** Debug Maxwell indexes */
    function a_im($i = 0) {
        new Vesper('', $mw = new Maxwell);
        print_r($i ? $mw->ids : json_encode($mw->menu, JSON_PRETTY_PRINT));
    }

    /** Search (list) Vesper css classes */
    function a_s($in = '', $pos = 0) {
        $vesp = new Vesper;
        $ary = $vesp->list($in);
        sort($ary[$pos]);
        echo implode("\n", $ary[$pos]);
    }

    /** Work with database */
    function a_base() {
        $m = new \t_venus('css');
        $t = $m->sqlf('@select id,txt from $_ where css_id=0 limit 1');
        foreach ($t as $id => $txt) {
  #          $m->sqlf('update $_ set txt=%s where id=%d', html($txt), $id);
        }
    }
}
