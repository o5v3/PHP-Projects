<?php

$min_number = 125730;
$max_number = 579381;

$password_count = 0;

for ($number = $min_number; $number <= $max_number; $number++) {
    $string = strval($number);
    $min_digit = intval($string[0]);
    $valid = True;
    $repeats = False;
    $tooMuchRepeats = True;
    $repeatedNumbers = ["0" => 1, "1" => 1, "2" => 1, "3" => 1, "4" => 1, "5" => 1, "6" => 1, "7" => 1, "8" => 1, "9" => 1];
    for ($index = 0; $index < strlen($string); $index++) {
        if (intval($string[$index]) < $min_digit) {
            $valid = False;
        } elseif (intval($string[$index]) > $min_digit) {
            $min_digit = intval($string[$index]);
        };
        if ($index != 0) {
            if ($string[$index] == $string[$index - 1]) {
                $repeats = True;
                $repeatedNumbers[$string[$index]] += 1;
            };
        };
    };
    foreach ($repeatedNumbers as $repNumber => $repValue) {
        if ($repValue == 2) {
            $tooMuchRepeats = False;
        };
    };
    if ($valid && $repeats && !$tooMuchRepeats) {
        $password_count += 1;
    };
};

echo $password_count;
?>
