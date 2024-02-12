<?php

namespace venus;
use Plan, MVC;

class globals
{
    function __construct() {
    }

    static function def() {
        Plan::$view = Plan::$ware = 'venus';
        MVC::body('glob.def');
        return [];
    }

    static function use() {
        Plan::$view = Plan::$ware = 'venus';
        MVC::body('glob.use');
        return [];
        static $grace;
        return $grace ?? ($grace = new self);
    }
}
