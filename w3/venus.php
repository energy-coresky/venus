<?php

class Venus extends Console
{
    function __construct($argv = [], $found = []) {
        Plan::$ware = 'venus';
        parent::__construct($argv, $found);
        Plan::$ware = 'main';
    }

    /** Parse css */
    function a_parse() {
        new Maat;
    }
}
