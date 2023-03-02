<?php

if (! function_exists('temperature')) {
    function temperature($temp, $to = 'fahrenheit') {

        $temp = match ($to) {
            'fahrenheit' => (($temp - 273.15) * 9/5 + 32) . "°F",
            'celcius' => ($temp - 273.15) . "°C",
            default => $temp,
        };


        return $temp;
    }
}