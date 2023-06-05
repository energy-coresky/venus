<?php

class m_venus extends Model_m
{
    static $fsize = ['320 x 480', '640 x 480', '768 x 768', '1024 x 555', '1366 x 768', /* notebook */ '1536 x 555'];

    static function files() {
        $list = array_map(function($v) {
            return basename($v);
        }, glob(WWW . 'venus/*.html'));
        //$list[] = 'https://ukrposhta.ua/ru';
        $list[] = LINK;
        return array_combine($list, array_map(function($v) {
            return "$$.test('$v')";
        }, $list));
    }

    static function pseudo() {
        return [
    'hover',
    'focus', 'focus-within', 'focus-visible',
    'active',
    'visited',
    'target',
    'first', 'last', 'only', 'odd', 'even',
    'first-of-type', 'last-of-type', 'only-of-type',
    'empty', 'disabled', 'enabled', 'checked',
    'indeterminate',
    'default',
    'required',
    'valid',
    'invalid',
    'in-range',
    'out-of-range',
    'placeholder',
    'placeholder-shown',
    'autofill',
    'read-only',
    'before', 'after',
    'first-letter', 'first-line',
    'marker',
    'selection',
    'file',
    'backdrop',
    'sm', 'md', 'lg', 'xl', '2xl',
    'min-[…]',
    'max-sm', 'max-md', 'max-lg', 'max-xl', 'max-2xl',
    'max-[…]',
    'dark',
    'portrait',
    'landscape',
    'motion-safe',
    'motion-reduce',
    'contrast-more',
    'contrast-less',
    'print',
    'supports-[…]',
    'aria-checked', 'aria-disabled', 'aria-expanded', 'aria-hidden', 'aria-pressed', 'aria-readonly', 'aria-required', 'aria-selected', 'aria-[…]',
    'data-[…]',
    'rtl', 'ltr',
    'open',
        ];
    }

    static function menu() {
        return [ // https://rogden.github.io/tailwind-config-viewer/
            'h' => [
                'Ruler' => "i$.load('ruler')",
                'HTML Colors' => "i$.load('hcolors')",
                'CSS Styles' => "i$.load('css')",
                'Unicode' => "i$.load('unicode')",
                'Tailwind' => [
                    'Colors' => "i$.load('tcolors')",
                    'Text' => "i$.load('text')",
                    'Box' => "i$.load('box')",
                    'Pseudo' => "i$.load('pseudo')",
                ],
                'Icons' => [
                    'Bootstrap' => "i$.load('icons')",
                ],
                'Alpine.js' => [
                    '2do' => "i$.load('tcolors')",
                ],
            ],
            't' => [],
            'i' => [],
        ];
    }
}
