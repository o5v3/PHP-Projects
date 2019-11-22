<html>
    <head>
        <title>PHP Challenge</title>
    </head>
    <body>
        <!--Se agrupa todo en un div para colocarle un borde.-->
        <div id="main">
        <h1>Prototype Form</h1>
        <p>How do you spend your day?</p>
        <br>

        <?php 
        //Nos permite mantener los valores del form.
        session_start();


        function showForm() {
            echo "<form method='POST'>";
            echo "<input type='submit' name='state' value='Show students'>";
            echo "<input type='hidden' name='insert' value='Return'>";
            echo "</form>";
            echo "<form method='POST'>";
            echo "<input type='submit' name='insert' value='Update students'>";
            echo "</form>";
            //Si quieres cambiar el archivo del codigo fuente, cambia el nombre aqui.
            $source = fopen("file.txt", "r");
            echo parser($source);
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
            echo "</form>";
        };

        function viewStudent($data, $studentNum) {
            if (!isset($data[$studentNum])) {
                if (isset($data[$studentNum - 1])) {
                    viewStudent($data, $studentNum -1);
                    return;
                } else {
                    echo "<form method='POST'>";
                        echo "<input type='submit' name='insert' value='Return'></input>";
                    echo "</form>";
                    return;
                }
            }
            echo createTable($data[$studentNum]);
            echo "<form method='POST'>";
            if ($studentNum != 1){
                echo "<input type='submit' name='state' value='Anterior'></input>";
            };
            if ($studentNum != count($data)) {
                echo "<input type='submit' name='state' value='Siguiente'></input>";
            };
            echo "<input type='hidden' name='currentStudent' value='$studentNum'></input>";     
            echo "<input type='hidden' name='insert' value='Update students'></input>";                    
            echo "</form>";
            echo "<form method='POST'>";
                echo "<input type='submit' name='insert' value='Return'></input>";
            echo "</form>";
            echo "<form method='POST'>";
                echo "<input type='hidden' name='currentStudent' value='$studentNum'></input>";  
                echo "<input type='submit' name='insert' value='Update'></input>";
            echo "</form>";
            echo "<form method='POST'>";
                echo "<input type='hidden' name='currentStudent' value='$studentNum'></input>";  
                echo "<input type='submit' name='insert' value='Delete student'></input>";
            echo "</form>";
        };    
        
        function updateStudent($data, $studentNum) {
            $form = "<form method='POST'>";
            $exclusive = false;
            foreach ($data[$studentNum] as $property => $value) {
                if (!$exclusive) {
                    $form .= "<li>$property</li>";
                    $form .= "<li>$value</li>";  
                    $exclusive = true;
                    continue;                  
                }
                $form .= "<ul>";
                $form .= "<li>$property</li>";
                $form .= "<input name='$property' value='$value'></input>";
                $form .= "</ul>";
            };
            $form .= "<input type='submit' name='insert' value='Add record'></input>";
            $form .= "<input type='hidden' name='target' value='Update'></input>";   
            $form .=  "<input type='hidden' name='currentStudent' value='$studentNum'></input>";                      
            $form .= "</form>";
            echo $form;
        };

        //Se usa en showForm para mantener los valores de los campos al regresar de showResults.
        function resumeState($valueName = null) {
                if (!(isset($_SESSION[$valueName]))) {return;}
                $value = $_SESSION[$valueName];
                return $value;
        };

        //Crea una tabla a partir de un array asociativo.
        function createTable($data) {
            $table = "<table style='text-align: center; margin: auto; width: auto;'>";
            foreach ($data as $property => $value) {
                if ($property == "insert" || $property == "target" || $property == "state" || $property == "currentStudent") {continue;};
                $table.= "<tr><td>$property</td><td>$value</td></tr>";
            };
            $table .= "</table><br>";
            return $table;
        };

        //Obtiene un archivo de input y lo analiza, cambiando las instrucciones por sus equivalentes
        //y lo demas se deja igual. Retorna un string de HTML valido.
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

        //Usada por el parser para decidir que instruccion ejecutar.
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

        //Muestra tablas creadas a partir de la database.
        function showStudents() {
            $database = fopen("students.txt", "r");
            $data = array();
            while (!feof($database)) {
                //Cada linea equivale a un par Propiedad-Valor.
                $line = fgets($database);
                //Las propiedades y los valores estan separados por el signo "+".
                $words = explode("+", $line);
                //Si hay 2 o mas "+" en la linea, crear una tabla con los datos presentes y reiniciar la data.
                //Esto se usa para separar las tablas en fechas, ya que la fecha esta formateada:
                //Ejemplo: (Fecha)+(Hora)+(Separador).
                //
                //El separador puede ser cualquier char, lo que importa es que este presente.
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
            //Permite devolvernos al estado de donde activamos showStudents.
            echo '<input type="hidden" name="insert" value="' . $_POST["insert"] . '"></form>';
        };

        //Registra los campos en el archivo con el formato:
        //         Dia/Mes/Año+Hora:Minutos:Segundos+1
        //                 Propiedad+Valor
        //                 Propiedad+Valor
        //                 Propiedad+Valor
        //                       ...
        //(El 1 puede ser intercambiado por cualquier cosa y funcionara igual)
        //(Cualquier cosa excepto un +)
        function addRecord() {
            $database = fopen("students.txt", "a");
            fwrite($database, date("d/m/Y+H:i:s") . "+1\r\n");
			foreach ($_SESSION as $property => $value) {

                if ($property == "insert" || $property == "target" || $property == "state" || $property == "currentStudent") {continue;};
                fwrite($database, "$property+$value\r\n");            
		    };
            fclose($database);
            echo "Record successfully added to database!";
            $_SESSION["insert"] = null;
            session_unset();
            echo "<form method='POST'>";
                echo "<input type='submit' name='insert' value='Return'></input>";
            echo "</form>";
        };

        function updateRecord($data, $currentStudent = null) {
            $database = fopen("students.txt", "w");
            foreach ($data as $student) {
                $keys = array_keys($student);
                $first = array_shift($keys);
                $element = array_shift($student);
                fwrite($database, "$first+$element+1\r\n");
			    foreach ($student as $property => $value) {

                    if ($property == "insert" || $property == "target" || $property == "state" || $property == "currentStudent") {continue;};
                    fwrite($database, "$property+$value\r\n");            
                };
            };
            fclose($database);
            if ($_POST["insert"] == "Delete student") {
                viewStudent(getStudentsData("students.txt"), $currentStudent);
            } else {
                echo "Record successfully updated to database!";
                $_SESSION["insert"] = null;
                session_unset();
                echo "<form method='POST'>";
                    echo "<input type='submit' name='insert' value='Return'></input>";
                echo "</form>";
            };
        };

        //Obtiene los datos de los estudiantes desde el registro en la forma:
        //  $data[numeroEstudiante][propiedad] = valor
        //(Los estudiantes son indexados desde 1, no desde 0)
        function getStudentsData($filename) {
            $database = fopen($filename, "r");
            $data = array();
            $studentNum = 0;
            while (!feof($database)) {
                $line = fgets($database);
                $words = explode("+", $line);
                //Esto se ejecuta al principio tambien, lo que provoca que se indexen desde el 1.
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

        function chooseWrite($studentNum) {
            $data = getStudentsData("students.txt");
            foreach ($_SESSION as $property => $value) {
                if ($property == "insert" || $property == "target" || $property == "state") {continue;};
                $data[$studentNum][$property] = $value;
            };
            updateRecord($data);
        };

        function deleteStudent($studentNum) {
            $data = getStudentsData("students.txt");
            array_splice($data, $studentNum - 1, 1);
            updateRecord($data, $studentNum);        
        };

        function main() {
            if (!isset($_POST["currentStudent"])) {
                $_POST["currentStudent"] = 1;
            };
            $currentStudent = $_POST["currentStudent"];
            if (isset($_POST["state"])) {
                if ($_POST["state"] == "Show students") {
                    showStudents();
                };
                if ($_POST["state"] == "Anterior") {
                    $currentStudent -= 1;
                } elseif ($_POST["state"] == "Siguiente") {
                    $currentStudent += 1;
                };
            };
            if (isset($_POST["insert"]))  {
                if ($_POST["insert"] == "Add record") {
                    if (isset($_POST["target"]) && $_POST["target"] == "Update") {
                        chooseWrite($currentStudent);
                    }else {
                    addRecord();
                    };
                } elseif ($_POST["insert"] == "Update") {
                    updateStudent(getStudentsData("students.txt"), $currentStudent);
                } elseif ($_POST["insert"] == "Update students") {
                    viewStudent(getStudentsData("students.txt"), $currentStudent);
                } elseif ($_POST["insert"] == "Delete student") {
                    deleteStudent($currentStudent);
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
        </div>
        
        <style>body {text-align: center;} table, td {border: 1px dotted black;} #main {border: 1px dotted black; width: 25%; margin: auto;}</style>
    </body>
</html>