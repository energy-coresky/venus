<?php

class Vesper
{
    private $maat;

    function __construct($maat) {
        $this->maat = $maat;
    }

    function tw_css() {
        trace(print_r($this->maat->page,1));
        return '';
    }

    function in($ary) {
        $list = [];
        foreach ($ary as $cls) {
            if (is_array($cls))
                $cls = array_shift($cls);
            foreach (explode(' ', $cls) as $one) {
                $list[$one] = 1;
            }
        }
        ksort($list);
        return $list;
    }
}

