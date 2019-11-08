<html>
    <head>
        <title>PHP Challenge 1</title>
    </head>
    <body>
        <h1>Prototype Form</h1>
        <p>How do you spend your day?</p>
        <br>
        <?php 
        //Permite mantener los valores en los campos en caso de que se presione "Return".
        session_start(); 

        //Obtiene el valor previo del campo del "SESSION" array. Si no existe, retorna null.
        function resumeState($valueName = null)
        {
            if (!(isset($_SESSION[$valueName]))) {return;}
            $value = $_SESSION[$valueName];
            return $value;
        };

        function createTable($data) {
            $table = "<table style='text-align: center; margin: auto; width: auto;'>";
            foreach ($data as $property => $value) {
                if ($property == "insert" || $property == "target" || $property == "state") {continue;};
                $table.= "<tr><td>$property</td><td>$value</td></tr>";
            };
            $table .= "</table><br>";
            return $table;
        };
        
        if (isset($_POST["insert"]) && $_POST["insert"] == "Show students") {
            $database = fopen("students.txt", "r");
            $data = array();
            while (!feof($database)) {
                $line = fgets($database);
                $words = explode("+", $line);
                if (isset($words[2])) {
                    echo createTable($data);
                }
                if ($words[0] == "") {continue;};
                $data[$words[0]] = $words[1];
            };
            echo createTable($data);
            fclose($database);
            echo '<form method="POST"><input type="submit" name="insert" value="Close"></form>';
        };

        //Si no se hace esto, al enviar el formulario se subiria inmediatamente al archivo.
        $_SESSION["insert"] = null;
        ?>
        <form method="POST">
            <input type="submit" name="insert" value="Show students">
        </form>
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
            return "<input type='" . rtrim($pieces[1]) . "' name='$pieces[0]' value='<?php echo resumeState('$pieces[0]');?>'>";
        };
        if ($operator == "{") {
            return "<h2>$data</h2>";
        }
    }
    $source = fopen("file.txt", "r");
    eval("?> " . parser($source));
    fclose($source);
    session_unset();
    ?>
     
        <style>body {text-align: center;}</style>
    </body>
</html>
