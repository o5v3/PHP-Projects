<?php
function initMemory() {
    $data = fopen("aoc.txt", "r");
    $code = explode(",", fgets($data));
    fclose($data);

    for ($i = 0; $i < count($code); $i++) {
        $code[$i] = intval($code[$i]);
    };
    

    return $code;

};

function replace($code, $noun, $verb) {
    $code[1] = $noun;
    $code[2] = $verb;
    return $code;
};


function solveCode($code) {
    $index = 0;
    $operation = True;
    while ($operation) {
        switch ($code[$index]) {
            case 99:
                $operation = False;
                break;
            case 1:
                $code[$code[$index + 3]] = $code[$code[$index + 1]] + $code[$code[$index + 2]];
                $index += 4;
                break; 
            case 2:
                $code[$code[$index + 3]] = $code[$code[$index + 1]] * $code[$code[$index + 2]];
                $index += 4;
                break;
        };
    };
    return $code;
};

function main() {
    $target = 19690720;
    $target_noun = 0;
    $target_verb = 0;
    $target_code = [];
    for ($noun = 0; $noun < 100; $noun++) {
        for ($verb = 0; $verb < 100; $verb++) {
            $code = initMemory();
            $code = replace($code, $noun, $verb);
            $code = solveCode($code);
            if ($code[0] == $target) {
                $target_noun = $noun;
                $target_verb = $verb;
                $target_code = $code;
                break;
            };
        };
    };
    echo $target_code[0];
    echo "<br>";
    echo $target_noun;
    echo "<br>";
    echo $target_verb;
    echo "<br>";
    echo 100 * $target_noun + $target_verb;
    echo "<br>";
    return $target_code;
};

$code = main();

echo $code[0];
echo "<pre>";
echo print_r($code);
echo "</pre>";
?>