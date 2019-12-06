<?php

$grid = [];
$file = fopen("aoc.txt", "r");
$wire1 = fgets($file);
$wire2 = fgets($file);

function divide($wire) {
    $instructions = [];
    $values = [];
    $wire = explode(",", $wire);
    for ($i = 0; $i < count($wire);$i++) {
        $instructions[$i] = substr($wire[$i], 0, 1);
        $values[$i] = intval(substr($wire[$i], 1));
    }
    return [$instructions, $values];
};

function makeGrid($wire) {
    $grid[0][0] = 10;
    $x = 0;
    $y = 0;
    for ($index = 0; $index < count($wire[0]); $index++) {
        if ($wire[0][$index] == "U") {
            for ($cell = 1; $cell <= $wire[1][$index]; $cell++) {
                $y += 1;
                $grid[$x][$y] = True;
            };
        } elseif ($wire[0][$index] == "D") {
            for ($cell = 1; $cell <= $wire[1][$index]; $cell++) {
                $y -= 1;
                $grid[$x][$y] = True;
            };            
        } elseif ($wire[0][$index] == "R") {
            for ($cell = 1; $cell <= $wire[1][$index]; $cell++) {
                $x += 1;
                $grid[$x][$y] = True;
            };  
        } elseif ($wire[0][$index] == "L") {
            for ($cell = 1; $cell <= $wire[1][$index]; $cell++) {
                $x -= 1;
                $grid[$x][$y] = True;
            };  
        };
    };
    return $grid;
};

function intersection($array1, $array2) {
    $total = [];
    $i = 0;
    foreach ($array1 as $key => $value) {
        if (!array_key_exists($key, $array2)) {continue;};

        if ($value == 1 && $array2[$key] == 1) {
            echo "Intersection at $key<br>";
            array_push($total, $key);
        };
    };
    return $total;
};


$wire1 = divide($wire1);
$wire2 = divide($wire2);
$grid1 = makeGrid($wire1);
$grid2 = makeGrid($wire2);
$size = (count($grid1) >= count($grid2)) ? count($grid1) : count($grid2);
$final_grid = [];
/*for ($i = 0; $i >= 0; $i--) {

    if (!isset($grid1[$i]) || !isset($grid2[$i])) {
        continue;
    };
    $final_grid[$i] = intersection($grid1[$i], $grid2[$i]);
}; */
foreach ($grid1 as $key => $value) {

    if (!array_key_exists($key, $grid1) || !array_key_exists($key, $grid2)) {
        continue;
    };

    $final_grid[$key] = intersection($grid1[$key], $grid2[$key]);
};
$target_x = 1000;
$target_y = 1000;

foreach ($final_grid as $x => $values) {
    if (count($values) == 0) {continue;};
    foreach ($values as $y) {
        if($x == 0 && $y == 0) {continue;}
        if ((abs($x) + abs($y)- 0) < ((abs($target_x) + abs($target_y)) - 0)) {
            $target_x = $x;
            $target_y = $y;    
        };    
    };
};

/*foreach ($final_grid as $x => $array) {
    if (count($array) == 0) {continue;};
    foreach ($array as $y => $value) {
        if($x == 0 && $y == 0) {continue;}
        if ((($x + $y)- 0) < (($target_x + $target_y) - 0)) {
            $target_x = $x;
            $target_y = $y;
        };
    };
};*/

echo $target_x;
echo "<br>";
echo $target_y;
echo "<br>";
echo ((abs($target_x) + abs($target_y)) - 0);
echo "<pre>";
echo print_r($final_grid);
echo "</pre>";
?>
