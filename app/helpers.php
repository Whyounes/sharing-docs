<?php

if (!function_exists('human_filesize')) {
    function human_filesize($bytes, $decimals = 0)
    {
        $sz = 'BKMGTP';
        $factor = (int)floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }
}
