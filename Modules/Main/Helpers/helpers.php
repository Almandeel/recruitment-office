<?php


if (!function_exists('office')) {
    
    function office($column = null)
    {
        $office = auth()->user()->office;
        if (gettype($column) == 'string') {
            return $office->$column;
        }
        return $office;
    }
}

if (!function_exists('is_json')) {
    
    function is_json($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (!function_exists('cancelButton')) {
    
    function cancelButton($classes = 'btn btn-danger', $icon = 'fa fa-times')
    {
        $builder = '<a href="' . url()->previous() . '" class="' . $classes .  '">';
        $builder .= '<i class="' . $icon .  '"></i>';
        $builder .= '<span>إلغاء</span>';
        $builder .= '</a>';

        return $builder;
    }
}