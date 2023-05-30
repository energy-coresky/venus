<?php

class Venus extends Console
{
    /** This is just test */
    function a_test() {
        var_dump(SKY::$dd); echo '-test';
    }

    /** Test without DB */
    function a__qq() {
        var_dump(SKY::$dd);
    }
}
