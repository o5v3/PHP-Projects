<?php
function initMemory() {
    $data = fopen("aoc.txt", "r");
    $code = explode(",", fgets($data));
    fclose($data);

    return $code;
};

function solveCode($code, $input) {
    $index = 0;
    $operation = True;
    while ($operation) {
        if (strlen($code[$index]) == 1 || $code[$index] == "99") {
        switch ($code[$index]) {
            case "99":
                $operation = False;
                break;
            case "1":
                $code[$code[$index + 3]] = strval(intval($code[$code[$index + 1]]) + intval($code[$code[$index + 2]]));
                $index += 4;
                break; 
            case "2":
                $code[$code[$index + 3]] = strval(intval($code[$code[$index + 1]]) * intval($code[$code[$index + 2]]));
                $index += 4;
                break;
            case "3":
                $code[$code[$index + 1]] = $input;
                $index += 2;
                break;
            case "4":
                echo $code[$code[$index + 1]] . "<br>";
                $index += 2;
                break;
            case "5":
                if ($code[$code[$index + 1]] != "0") {
                    $index = intval($code[$code[$index + 2]]);
                } else {
                    $index += 3;
                }
                break;
            case "6":
                if ($code[$code[$index + 1]] == "0") {
                    $index = intval($code[$code[$index + 2]]);
                } else {
                    $index += 3;
                }
                break;     
            case "7":
                if (intval($code[$code[$index + 1]]) < intval($code[$code[$index + 2]])) {
                    $code[$code[$index + 3]] = "1";
                } else {
                    $code[$code[$index + 3]] = "0";
                }
                $index += 4;
                break;
            case "8":
                if (intval($code[$code[$index + 1]]) == intval($code[$code[$index + 2]])) {
                    $code[$code[$index + 3]] = "1";
                } else {
                    $code[$code[$index + 3]] = "0";
                }
                $index += 4;
                break;
        }; } else {
        $command = strrev($code[$index]);
        $p1 = intval($command[2]) ?? 0;
        $p2 = intval($command[3]) ?? 0;
        switch ($command[1] . $command[0]) {
            case "02":
                if ($p1 == 0) {
                    $value1 = $code[$code[$index + 1]];
                } else {
                    $value1 = $code[$index + 1];
                };
                if ($p2 == 0) {
                    $value2 = $code[$code[$index + 2]];
                } else {
                    $value2 = $code[$index + 2];
                }
                $code[$code[$index + 3]] = strval(intval($value1) * intval($value2));
                $index += 4;
                break;
            case "01":
                if ($p1 == 0) {
                    $value1 = $code[$code[$index + 1]];
                } else {
                    $value1 = $code[$index + 1];
                };
                if ($p2 == 0) {
                    $value2 = $code[$code[$index + 2]];
                } else {
                    $value2 = $code[$index + 2];
                }
                $code[$code[$index + 3]] = strval(intval($value1) + intval($value2));
                $index += 4;
                break;
            case "04":
                echo $code[$index + 1] - "<br>";
                $index += 2;
                break;
            case "05":
                if ($p1 == 0) {
                    $value1 = $code[$code[$index + 1]];
                } else {
                    $value1 = $code[$index + 1];
                };
                if ($p2 == 0) {
                    $value2 = $code[$code[$index + 2]];
                } else {
                    $value2 = $code[$index + 2];
                };
                if ($value1 != "0") {
                    $index = intval($value2);
                } else {
                    $index += 3;
                };
                break;
            case "06":
                if ($p1 == 0) {
                    $value1 = $code[$code[$index + 1]];
                } else {
                    $value1 = $code[$index + 1];
                };
                if ($p2 == 0) {
                    $value2 = $code[$code[$index + 2]];
                } else {
                    $value2 = $code[$index + 2];
                };
                if ($value1 == "0") {
                    $index = intval($value2);
                } else {
                    $index += 3;
                };
                break;
            case "07":
                if ($p1 == 0) {
                    $value1 = $code[$code[$index + 1]];
                } else {
                    $value1 = $code[$index + 1];
                };
                if ($p2 == 0) {
                    $value2 = $code[$code[$index + 2]];
                } else {
                    $value2 = $code[$index + 2];
                };
                if (intval($value1) < intval($value2)) {
                    $code[$code[$index + 3]] = "1";
                } else {
                    $code[$code[$index + 3]] = "0";
                };
                $index += 4;
                break;
            case "08":
                if ($p1 == 0) {
                    $value1 = $code[$code[$index + 1]];
                } else {
                    $value1 = $code[$index + 1];
                };
                if ($p2 == 0) {
                    $value2 = $code[$code[$index + 2]];
                } else {
                    $value2 = $code[$index + 2];
                };
                if (intval($value1) == intval($value2)) {
                    $code[$code[$index + 3]] = "1";
                } else {
                    $code[$code[$index + 3]] = "0";
                };
                $index += 4;
                break;
        }
        }
    };
    return $code;
};

function main() {
    $code = initMemory();
    $code = solveCode($code, "5");
    return $code;
};


$code = main();
/*
echo $code[0];
echo "<pre>";
echo print_r($code);
echo "</pre>";
*/
?>