<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class TextHelper
{
    public static function short($input = null, $max_len = 66)
    {
        if(!$input || mb_strlen($input, 'utf-8') <= $max_len) {
            return $input;
        }
        $output = '';
        $input_items = explode(' ', $input);
        foreach ($input_items as $input_item) {
            $input_item = trim($input_item);
            if(!$input_item) {
                continue;
            }
            if(!$output) {
                $output = $input_item;
                continue;
            }
            if(mb_strlen($output . ' ' . $input_item, 'utf-8') >= ($max_len - 3)) {
                $output = $output . '...';
                break;
            }
            $output .= ' ' . $input_item;
        }
        return $output;
    }
}
