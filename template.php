<html>
    <head>
        <title>PHP Challenge 1</title>
    </head>
    <body>
    <?php 
    function parser($sourceFile) {
        $instructionSet = array("+", "-", "/", "{");
        $html = "<form method='POST' action='results.php'>";
        $html .= "<ul>";
            while (!(feof($sourceFile))) {
                $line = fgets($sourceFile);
                $line = str_split($line);
                if (in_array($line[0], $instructionSet)){
                    $html .= execute($line[0], substr(implode("", $line), 1)) . "<br>";
                } else {
                    $html .= implode("", $line) . "<br>";
                };
            };
        $html .= "</ul>";
        $html .= "<input type='submit'>";
        $html .= "</form>";
        return $html;
    };

    function execute($operator, $data) {
        if ($operator == "+") {
            return "<li>$data</li>";
        };
        if ($operator == "-") {
            $pieces = explode("/", $data);
            return "<input type='$pieces[1]' name='$pieces[0]' value='<?php echo resumeState('$pieces[0]');?>'>";
        };
        if ($operator == "{") {
            return "<h2>$data</h2>";
        }
    }
    function resumeState($valueName = null)
        {
            if (!(isset($_SESSION[$valueName]))) {return;}
            $value = $_SESSION[$valueName];
            return $value;
        };
    $source = fopen("source.txt", "r");
    eval("?> " . parser($source));
    fclose($source);
    ?>
     
        <style>body {text-align: center;}</style>
    </body>
</html>
