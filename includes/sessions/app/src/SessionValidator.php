<?php

namespace Sessions;

class SessionValidator
{
    public function __construct() { }

    public function __destruct() { }

    public function sanitiseString($string_to_sanitise)
    {
        $sanitised_string = false;

        if (!empty($string_to_sanitise))
        {
            $sanitised_string = filter_var($string_to_sanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $sanitised_string;
    }

    public function validateInteger($value_to_check)
    {
        $checked_value = false;
        $options = [
            'options' => [
                'default' => -1, // value to return if the filter fails
                'min_range' => 0
            ]
        ];

        if (isset($value_to_check))
        {
            $checked_value = filter_var($value_to_check, FILTER_VALIDATE_INT, $options);
        }

        return $checked_value;
    }

    public function validateServerType($type_to_check)
    {
        $checked_server_type = false;
        $calculation_type = array('file', 'database');

        if (in_array($type_to_check, $calculation_type))
        {
            $checked_server_type = $type_to_check;
        }

        return $checked_server_type;
    }
}