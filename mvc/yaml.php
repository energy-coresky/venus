<?php

return [
    'v3' => fn($v) => array_map(fn($_) => "#$_", explode(' ', $v)),
    'v2' => function ($v, $a) {
        $v2 = ['gray', 'red', 'amber', 'emerald', 'blue', 'indigo', 'violet', 'pink'];
        $out = [];
        foreach ($v2 as $name)
            $out[$name] = $a['color3'][$name];
        return $out;
    },
    'color' => function ($v) {
        $v = preg_replace("/\s+/", "\n", $v);
        return array_map(fn($v) => '#' . $v, strbang($v, '.'));
    },
];
