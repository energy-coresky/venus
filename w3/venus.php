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
        $css = Plan::_g("assets/z.css");
        echo Maat::css($css);
    }

    /** Test Maat parser */
    function a_test() {
        $css = Plan::_g("assets/preflight.css");
        Maat::css($css, ['test' => true]);
    }

    /** Show tables */
    function a_t() {
        $t = new t_venus('unicode');
        print_r($t->head_y()->_tables());
    }

    /** Work with database */
    function a_base() {//['Properties', 'Types', 'Functions', 3'Pseudo-classes', 4'Pseudo-elements', 5'At-rules'];
        $t = new t_venus('css');  #SQL::$dd = $t->head_y();             2do $t->onduty('preset');
# print_r($t->head_y()->sqlf('@select grp from pseudo group by grp'));
        $char=file(__DIR__ . '/z');$qq=[[],[],[],[],[],[],[],[]];
        $i=0;
        //,
        foreach (explode('#',$char[0]) as $s) {
            
            $s = trim($s);
            if (!$s || '000000'==$s || 'ffffff'==$s) {
                continue;
            }
         $qq[$i++ % 8][] = "#$s";
//echo "'#$s',\n";

            //$t->head_y()->sqlf('insert into css values(null, %s,"", %d, "","","2023-06-07 09:00:00")',$s, 5);
        }
        var_export($qq);
    }
}
/*
$qq=[];
foreach(CSS::$styles as $k=> $v){
  $qq[$k]=1;
  foreach($v as $_) $qq[$_]=1;
}
ksort($qq);
print_r($qq);


*/