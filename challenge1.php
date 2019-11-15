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

        //Retorna una tabla desde un array asociativo.
        function createTable($data) {
            $table = "<table style='text-align: center; margin: auto; width: auto;'>";
            foreach ($data as $property => $value) {
                if ($property == "insert" || $property == "target" || $property == "state") {continue;};
                $table.= "<tr><td>$property</td><td>$value</td></tr>";
            };
            $table .= "</table><br>";
            return $table;
        };
        
        //Muestra el contenido del database si se selecciono "Show students".
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
        <!--Boton que recarga la pagina y nos muestra el contenido del database.
        Si los campos fueron llenados, seran reseteados al recargar la pagina.-->
        <form method="POST">
            <input type="submit" name="insert" value="Show students">
        </form>

        <?php 
    //Obtiene un archivo de input y lo analiza, cambiando las instrucciones por sus equivalentes
    //y los demas se deja igual. Retorna un string de HTML valido.
    function parser($sourceFile) {
        //Instrucciones: "+" (Pregunta), "-" (Nombre de variable. Debe ir con una instruccion /)
        // "/" (Tipo de input. Debe seguir inmediatamente despues de la instruccion "-")
        // "{" (Titulo)
        $instructionSet = array("+", "-", "/", "{");
        $html = "<form method='POST' action='results.php'>";
        $html .= "<ul>";
            while (!(feof($sourceFile))) {
                $line = fgets($sourceFile);
                $line = str_split($line);
                //Si el char de inicio es una instruccion, ejecutarla.
                //Si no, añadir la linea tal y como esta. Esto permite añadir
                //HTML en el archivo fuente y este se  mostrara en la pagina.
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
            //El rtrim es necesario ya que el tipo siempre termina con algo de whitespace.
            return "<input type='" . rtrim($pieces[1]) . "' name='$pieces[0]' value='" . resumeState($pieces[0]) . "'>";
        };
        if ($operator == "{") {
            return "<h2>$data</h2>";
        }
    }
    $source = fopen("file.txt", "r");
    //Buscar manera de utilizar otra cosa que no sea eval.
    echo call_user_func("parser", $source);
    fclose($source);
    //Permite cambiar el archivo fuente sin que queden residuos del archivo anterior.
    session_unset();
    ?>
     
        <style>body {text-align: center;}</style>
    </body>
</html>
