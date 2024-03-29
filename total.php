<html>
    <head>
        <title>PHP Challenge</title>
    </head>
    <body>
        <h1>Prototype Form</h1>
        <p>How do you spend your day?</p>
        <br>

        <?php 
        session_start();

        function showForm() {
            echo "<form method='POST'>";
            echo "<input type='submit' name='state' value='Show students'>";
            echo "<input type='hidden' name='insert' value='Return'>";
            echo "</form>";
            $source = fopen("file.txt", "r");
            echo call_user_func("parser", $source);
            fclose($source);
            //Permite cambiar el archivo fuente sin que queden residuos del archivo anterior.
            session_unset();
        };

        function showResults() {
            if (isset($_SESSION["firstName"])){
                echo "Great! Thanks " . $_SESSION["firstName"] . " for responding to our survey<br><br>";
            } else {
                echo "Great! Thanks for responding to our survey<br><br>";
            }
            echo "These are your details:<br><br>";

            echo createTable($_SESSION);
            echo "Do you want to add the student to the record?<br><br>";
			echo "<form method='POST'>";
			    echo "<input type='submit' name='insert' value='Return'></input>";
			echo "</form>";
			echo "<form method='POST'>";
                echo "<input type='submit' name='insert' value='Add record'></input>";
                echo "<br>";
            echo "</form>";
            echo "<form method='POST'>";
                echo "<input type='submit' name='state' value='Show students'>";
                echo "<input type='hidden' name='insert' value='Send form'>";
            //    echo "<input type='email' name='target'></input>";
            //    echo "<input type='submit' name='insert' value='Send email'></input>";
            echo "</form>";
        };

        function resumeState($valueName = null) {
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

        //Obtiene un archivo de input y lo analiza, cambiando las instrucciones por sus equivalentes
        //y los demas se deja igual. Retorna un string de HTML valido.
        function parser($sourceFile) {
            //Instrucciones: "+" (Pregunta), "-" (Nombre de variable. Debe ir con una instruccion /)
            // "/" (Tipo de input. Debe seguir inmediatamente despues de la instruccion "-")
            // "{" (Titulo)
            $instructionSet = array("+", "-", "/", "{");
            $html = "<form method='POST'>";
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
            $html .= "<input type='submit' name='insert' value='Send form'>";
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
        };

        function showStudents() {
            $database = fopen("students.txt", "r");
            $data = array();
            while (!feof($database)) {
                $line = fgets($database);
                $words = explode("+", $line);
                if (isset($words[2])) {
                    echo createTable($data);
                    $data = array();
                }
                if ($words[0] == "") {continue;};
                if (!isset($words[1])) {continue;};
                $data[$words[0]] = $words[1];
            };
            echo createTable($data);
            fclose($database);
            echo '<form method="POST">';
            echo '<input type="submit" name="state" value="Close">';
            echo '<input type="hidden" name="insert" value="' . $_POST["insert"] . '"></form>';
        };

        function addRecord() {
            $database = fopen("students.txt", "a");
            fwrite($database, date("d/m/Y+H:i:s") . "+1\r\n");
			foreach ($_SESSION as $property => $value) {

                if ($property == "insert" || $property == "target" || $property == "state") {continue;};
                fwrite($database, "$property+$value\r\n");            
		    };
            fclose($database);
            echo "Record successfully added to database!";
            $_SESSION["insert"] = null;
        };

        function main() {
            if (isset($_POST["state"])) {
                if ($_POST["state"] == "Show students") {
                    showStudents();
                };
            };
            if (isset($_POST["insert"]))  {
                if ($_POST["insert"] == "Add record") {
                    addRecord();
                } elseif ($_POST["insert"] == "Return") {
                    showForm();
                } elseif ($_POST["insert"] == "Send form") {
                    showResults();
                } else {
                    showForm();
                };
            } else {
                    showForm();
                };
        };

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Permite mantener el array SESSION actualizado con el state y los valores
            foreach ($_POST as $property => $value) {
                $_SESSION[$property] = $value;
            };
        };

        main();
        ?>

        <style>body {text-align: center;}</style>
    </body>
</html>