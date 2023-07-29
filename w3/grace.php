<?php

class Grace /* Vesper JS generator */
{
    public $idx = [];

    private $code;

    function __construct() {
        fseek($fp = fopen(__FILE__, 'r'), __COMPILER_HALT_OFFSET__);
        $this->code = explode('~~', trim(stream_get_contents($fp)));
        fclose($fp);
    }

    static function instance() {
        static $grace;
        return $grace ?? ($grace = new self);
    }

    function tpl(&$tpl) {
        $tpl = explode("\n", $tpl);
        $list = explode('|', array_shift($tpl));
        $out = [];
        foreach ($list as $one) {
            $out[$one] = array_splice($tpl, 0, array_search(".$one", $tpl));
            array_shift($tpl);
        }
        $tpl = $out;
    }

    function json($tpl, $name, $maat) {
        $js = [];
        foreach ($tpl as $prop => $ary) {
            $x = [];
            foreach ($ary as $one) {
                $a = explode(' ', $one);
                $i = array_shift($a);
                $maat->add_class($a);
                $x[$i] = $a;
            }
            $js["$name-$prop"] = $x;
        }
        return 'let $js = ' . json_encode($js);
    }

    function buildJS($maat) {
        $out = $this->code[0];
        do {
            $name = key($maat->js);
            $v = pos($maat->js);
            [$pas, $tpl] = $this->idx[$name];
            $this->tpl($tpl);
            $code = implode("\n  ", $tpl[''] ?? ['']);
            if ($code2 = $tpl['.'] ?? '')
                $code2 = eval("return <<<DOC\n{$this->code[2]}\nDOC;");
            unset($tpl[''], $tpl['.']);
            $code .= "\n  " . $this->json($tpl, $name, $maat) . $code2;
            $out .= eval("return <<<DOC\n{$this->code[1]}\nDOC;");
        } while (false !== next($maat->js));
        return "$out";
    }

    function index($name, $row) {
        [$name, $pas] = explode(' ', $name, 2);
        $this->idx[$name] = [$pas, unl(trim($row->tpl))];
    }
}

__halt_compiler();

var sky = {v: {
  _to: function(el, $js, $$, add) {
    for (let name in $js) {
      if (!$js[name][$$])
          continue;
      let node = name == el.getAttribute('js') ? el : el.querySelector(`[js=${name}]`);
      for (let cls of $js[name][$$])
        add ? node.classList.add(cls) : node.classList.remove(cls)
    }
  },
  _fr: function(el, $js, $$) {
    sky.v._to(el, $js, $$ + '_', 1)
    setTimeout(function() {
      sky.v._to(el, $js, $$ + '_', 0)
      let prev = $$ ? 0 : 1
      sky.v._to(el, $js, prev, 0)
      sky.v._to(el, $js, $$, 1)
    }, 7);
  },
  _set: function(el, $js, type, func) {
    sky.v._end[type] = 2
    for (let name in $js) {
      let node = name == el.getAttribute('js') ? el : el.querySelector(`[js=${name}]`);
      node.addEventListener("transitionend", func, true);
    }
  },
  _end: {}
}}
~~
sky.v.$name = function$pas {
  $code
  sky.v._fr(el, \$js, \$$);
};
~~
  if (sky.v._end['$name']) {
    sky.v._end['$name'] = $$ + 1
  } else {
    sky.v._set(el, \$js, '$name', function () {
      let $$ = sky.v._end['$name'] - 1;
      $code2[0]
    })
  }
~~
el.addEventListener("transitionend", updateTransition, true);
document.getElementById("foo");
let articleParagraphs = document.querySelectorAll('article > p');




