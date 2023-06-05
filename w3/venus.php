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

    /** Work with database */
    function a_base() {
		$t = new t_venus('unicode');
		#SQL::$dd = $t->head_y();             2do
		#$t->onduty('preset');


        //print_r($t->head_y()->sqlf('@select grp from pseudo group by grp'));


		foreach (unserialize(view('tool.aa', [])) as $id => $desc)
			$t->head_y()->sqlf('insert into unicode values(%d, %s, "", 5)',$id,$desc);
//echo $line;
    }
}
