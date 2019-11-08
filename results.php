<html>
    <head>
        <title>PHP Challenge 1</title>
    </head>
    <body> 
        <?php 
        session_start();
        echo "<br><br><br>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Permite mantener el array SESSION actualizado con el state y los valores
            foreach ($_POST as $property => $value) {
                $_SESSION[$property] = $value;
            };

        function createTable($data) {
            $table = "<table style='text-align: center; margin: auto; width: auto;'>";
            foreach ($data as $property => $value) {
                if ($property == "insert" || $property == "target") {continue;};
                $table.= "<tr><td>$property</td><td>$value</td></tr>";
            
            };
            $table .= "</table><br>";
            return $table;
        };

        function main() {
            echo "Great! Thanks " . $_POST["firstName"] . " for responding to our survey<br><br>";
            echo "These are your details:<br><br>";

            echo createTable($_POST);
            
            $yearsLeft = 12 - (int) $_POST["yearAtSchool"];
            $hoursWatchingTVPerYear = (int) $_POST["hoursWatchingTV"] * 365;
            $hoursDoingHomeworkPerYear = (int) $_POST["hoursDoingHomework"] * 365;
            $hoursUsingComputerPerYear = (int) $_POST["hoursUsingComputer"] * 365;

            $percentageAwakeOnScreen = ((((int) $_POST["hoursWatchingTV"] + (int) $_POST["hoursUsingComputer"]) * 3600) * 100) / (strtotime($_POST["timeOfSleep"]) - strtotime($_POST["timeOfWakeUp"]));
            
            echo "Based on the information you entered, you will spend:<br><br>";
            echo "<ul>";
            echo "<li>" . $hoursWatchingTVPerYear . " hours watching TV or movies per year.</li><br><br>";
            echo "<li>" . $hoursDoingHomeworkPerYear . " hours doing homework per year.</li><br><br>";
            echo "<li>" . ($hoursWatchingTVPerYear + $hoursUsingComputerPerYear) . " hours in front of a TV or computer screen per year.</li><br><br>";
            echo "<li>" . (((int) $_POST["timeSpentWithFamily"] + (int) $_POST["timeSpentWithFriends"]) * 365) . " hours with friends and family per year.</li><br><br>";
            echo "<li>You have $yearsLeft years left at school, of which you'll spend:</li>";
            echo "<li>" . ($hoursDoingHomeworkPerYear * $yearsLeft) . " hours doing homework until you finish school.</li><br><br>";
            echo "<li>" . (($hoursWatchingTVPerYear + $hoursUsingComputerPerYear) * $yearsLeft) . " hours watching a screen until you finish school.</li><br><br>";
            echo "<li>" . $percentageAwakeOnScreen . " percent of your awake time in front of a screen until you finish school.</li><br><br>";
            echo "</ul>";
            //Creo que seria mas eficiente escribirlo directamente en HTML y crear otro script abajo.
			echo "Do you want to add the student to the record?<br><br>";
			echo "<form method='POST' action='challenge.php'>";
			    echo "<input type='submit' value='Return'></input>";
			echo "</form>";
			echo "<form method='POST' action='results.php'>";
                echo "<input type='submit' name='insert' value='Add record'></input>";
                echo "<br>";
            //    echo "<input type='email' name='target'></input>";
            //    echo "<input type='submit' name='insert' value='Send email'></input>";
            echo "</form>";
        }
        
        //Si se ingresa a la pagina a traves del boton "Add record", añadir al estudiante a la base de datos.
        //Si no, mostrar los datos ingresados.
        if (isset($_SESSION["insert"]) && $_SESSION["insert"] == "Add record") {
			$database = fopen("students.txt", "w");
			foreach ($_SESSION as $property => $value) {
                if ($property == "insert" || $property == "target") {continue;};
                fwrite($database, "$property $value\r\n");            
		    };
            fclose($database);
            echo "Record successfully added to database!";
            $_SESSION["insert"] = null;
        } 
        //Necesita tener un servidor de email configurado.
        /*elseif ($_SESSION["insert"] == "Send email") {
            $message = createTable($_SESSION);
            $header = "MIME-Version: 1.0\r\nContent-type: text/html;charset=UTF-8\r\n";
            $header .= "From: test@ejemplo.com";
            mail($_SESSION["target"], "Prototype Form", $message, $header);
            echo "Email delivered.";
        }*/
        
        else {
            main();
        };
        }
        ?>
        <style>body {text-align: center;} form {display: inline;}</style>
    </body>
</html>
