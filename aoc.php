<?php

$min_number = 125730;
$max_number = 579381;

$password_count = 0;

for ($number = $min_number; $number <= $max_number; $number++) {
    $string = strval($number);
    $min_digit = intval($string[0]);
    $valid = True;
    $repeats = False;
    for ($index = 0; $index < strlen($string); $index++) {
        if (intval($string[$index]) < $min_digit) {
            $valid = False;
        } elseif (intval($string[$index]) > $min_digit) {
            $min_digit = intval($string[$index]);
        };
        if ($index != 0) {
            if ($string[$index] == $string[$index - 1]) {
                $repeats = True;
            };
            if ($index != strlen($string) - 1) {
                if (($string[$index] == $string[$index - 1]) == $string[$index + 1]){
                    $repeats = False;
                }
            };
        };
    };
    if ($valid && $repeats) {
        $password_count += 1;
    };
};

echo $password_count;
?>
