<?php

if (! function_exists('temperature')) {
    function temperature($temp, $to = 'fahrenheit') {

        $temp = match ($to) {
            'fahrenheit' => round((($temp - 273.15) * 9/5 + 32), 2) . "°F",
            'celcius' => round(($temp - 273.15), 2) . "°C",
            default => $temp,
        };

        return $temp;
    }
}