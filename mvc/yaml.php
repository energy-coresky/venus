<?php

return [
    'v3' => fn($v) => array_map(fn($_) => "#$_", explode(' ', $v)),
    'v2' => function ($v, $x, $a) {
        $v2 = ['gray', 'red', 'amber', 'emerald', 'blue', 'indigo', 'violet', 'pink'];
        $out = [];
        foreach ($v2 as $name)
            $out[$name] = $a['color3'][$name];
        return $out;
    },
];
