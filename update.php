<html>
    <head>
        <title>PHP Challenge 1</title>
    </head>
    <body> 

    <?php

    function createTable($data) {
        $table = "<table style='text-align: center; margin: auto; width: auto;'>";
        foreach ($data as $property => $value) {
            if ($property == "insert" || $property == "target" || $property == "state") {continue;};
            $table.= "<tr><td>$property</td><td>$value</td></tr>";
        };
        $table .= "</table><br>";
        return $table;
    };
    
    
    function updateStudent() {
        
    };


    function getStudentsData($filename) {
        $database = fopen($filename, "r");
        $data = array();
        $studentNum = 0;
        while (!feof($database)) {
            $line = fgets($database);
            $words = explode("+", $line);
            if (isset($words[2])) {
                $studentNum += 1;
            };
            if ($words[0] == "") {continue;};
            if (!isset($words[1])) {continue;};
            $data[$studentNum][$words[0]] = $words[1];
        };
        fclose($database);
        return $data;
    };

    function createInput($student) {
        $html = "<form method='POST'>";
        $html .= "<ul>";
        $html .= "</ul>";
        $html .= "</form>";
        return $html;
    }
    
    ?>


        <style>body {text-align: center;} table, td {border: 1px dotted black;} #main {border: 1px dotted black; width: 25%; margin: auto;}</style>
    </body>
</html>