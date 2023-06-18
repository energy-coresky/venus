<?php

class Vesper
{
    function __construct() {
        ;
    }

    function tw_css($ary) {
        //trace($ary, '222');
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
