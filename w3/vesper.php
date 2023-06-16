<?php

class Vesper
{
    function __construct() {
        ;
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
/*
array (
  'bg-gray-50' => 1,
  'bg-indigo-600' => 1,
  'bg-white' => 1,
  'block' => 1,
  'border' => 1,
  'border-transparent' => 1,
  'flex' => 1,
  'font-extrabold' => 1,
  'font-medium' => 1,
  'hover:bg-indigo-50' => 1,
  'hover:bg-indigo-700' => 1,
  'inline-flex' => 1,
  'items-center' => 1,
  'justify-center' => 1,
  'lg:flex' => 1,
  'lg:flex-shrink-0' => 1,
  'lg:items-center' => 1,
  'lg:justify-between' => 1,
  'lg:mt-0' => 1,
  'lg:px-8' => 1,
  'lg:py-16' => 1,
  'max-w-7xl' => 1,
  'ml-3' => 1,
  'mt-8' => 1,
  'mx-auto' => 1,
  'px-4' => 1,
  'px-5' => 1,
  'py-12' => 1,
  'py-3' => 1,
  'rounded-md' => 1,
  'shadow' => 1,
  'sm:px-6' => 1,
  'sm:text-4xl' => 1,
  'text-3xl' => 1,
  'text-base' => 1,
  'text-gray-900' => 1,
  'text-indigo-600' => 1,
  'text-white' => 1,
  'tracking-tight' => 1,
)
array (
  0 => 'bg-gray-50',
  1 => 'max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between',
  2 => 'text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl',
  3 => 'block',
  4 => 'block text-indigo-600',
  5 => 'mt-8 flex lg:mt-0 lg:flex-shrink-0',
  6 => 'inline-flex rounded-md shadow',
  7 => 'inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700',
  8 => 'ml-3 inline-flex rounded-md shadow',
  9 => 'inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50',
)
*/