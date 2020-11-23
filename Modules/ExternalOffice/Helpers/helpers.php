<?php


if (!function_exists('money_formats')) {
    
    function money_formats($amount, $sample = '$')
    {
        return $sample . number_format($amount, 2);
    }
}