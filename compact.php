<html>
    <head>
        <title>PHP Challenge 1</title>
    </head>
    <body>

        <?php 
        //Permite mantener los valores en los campos en caso de que se presione "Return".
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //Permite mantener el array SESSION actualizado con el state y los valores
            foreach ($_POST as $property => $value) {
                $_SESSION[$property] = $value;
            };};

        //Obtiene el valor previo del campo del "SESSION" array. Si no existe, retorna null.
        function resumeState($valueName = null)
        {
            if (!(isset($_SESSION[$valueName]))) {return;}
            $value = $_SESSION[$valueName];
            return $value;
        };

        //Si no se hace esto, al enviar el formulario se subiria inmediatamente al archivo.
        

        if (!(isset($_SESSION["state"]))) {
            $_SESSION["state"] = 1;
        }; 

        function main() {
            eval(' $_SESSION["state"] = 0; $_SESSION["insert"] = null;?>
            <h1>Prototype Form</h1>
            <p>How do you spend your day?</p>
            <br>
        <form method="POST" action="compact.php">
        <!--Esto esta en una lista para que sea mas organizado y se vea mejor-->
        <ul>
            <li>Please tell us your name:</li>
                <input type="text" name="firstName" value=<?php echo resumeState(\'firstName\'); ?>>
            <br>
            <li>Great, now, tell us your last name:</li>
                <input type="text" name="lastName" value=<?php echo resumeState(\'lastName\'); ?>>
            <br>
            <li>Ok. Now, in what school year are you? (In grade. Ex: 1st year = 7)</li>
                <input type="number" name="yearAtSchool" value=<?php echo resumeState(\'yearAtSchool\'); ?>>
            <br>
            <li>Oh? And how old are you?</li>
                <input type="number" name="age" value=<?php echo resumeState(\'age\'); ?>>
            <br>
            <li>Wait, if you have that age, then how many siblings do you have?</li>
                <input type="number" name="numberOfSiblings" value=<?php echo resumeState(\'numberOfSiblings\'); ?>>
            <br>
            <li>Never would\'ve guessed it. At what time do you go to bed? And when do you wake up?</li>
                <!--Dividir esta pregunta en dos hace que se vea mejor-->
                <p>Hour of sleep:</p> <input type="time" name="timeOfSleep" value=<?php echo resumeState(\'timeOfSleep\'); ?>>
                <p>Hour of waking up:</p> <input type="time" name="timeOfWakeUp" value=<?php echo resumeState(\'timeOfWakeUp\'); ?>>
            <br>
            <li>I want you to be honest with me: How many hours do you spend on your homework?</li>
                <input type="number" name="hoursDoingHomework" value=<?php echo resumeState(\'hoursDoingHomework\'); ?>>
            <br>
            <li>Oh really? And how much time you spend watching TV or movies?</li>
                <input type="number" name="hoursWatchingTV" value=<?php echo resumeState(\'hoursWatchingTV\'); ?>>
            <br>
            <li>Hmmm... How much time do you spend using a computer or gaming console?</li>
                <input type="number" name="hoursUsingComputer" value=<?php echo resumeState(\'hoursUsingComputer\'); ?>>
            <br>
            <li>I\'ll have to trust you. How many hours do you spend with your family per day?</li>
                <input type="number" name="timeSpentWithFamily" value=<?php echo resumeState(\'timeSpentWithFamily\'); ?>>
            <br>
            <li>And finally, how many hours do you spend with your friends per day?</li>
                <input type="number" name="timeSpentWithFriends" value=<?php echo resumeState(\'timeSpentWithFriends\'); ?>>
        </ul>
            <input type="submit" name="insert" value="Send form">
        </form>');
        };

        function results() {
        
        echo "<br><br><br>";
        
        
        $_SESSION["state"] = 0;

        function createTable($data) {
            $table = "<table style='text-align: center; margin: auto; width: auto;'>";
            foreach ($data as $property => $value) {
                if ($property == "insert" || $property == "target" || $property == "state") {continue;};
                $table.= "<tr><td>$property</td><td>$value</td></tr>";
            
            };
            $table .= "</table><br>";
            return $table;
        };

        function main2() {
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
			echo "<form method='POST' action='compact.php'>";
			    echo "<input type='submit' name='insert' value='Return'></input>";
			echo "</form>";
			echo "<form method='POST' action='compact.php'>";
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
                if ($property == "insert" || $property == "target" || $property == "state") {continue;};
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
            main2();
        };
        };
        if (isset($_POST["insert"])) {
        if ($_POST["insert"] == "Return") {
            main();
        } elseif ($_POST["insert"] == "Add record") {
           results();
        }
         elseif ($_POST["insert"] == "Send form") {
            results();
        }else {
            main();
        };} else {
            main();
        };
        ?>

        <style>body {text-align: center;}</style>
    </body>
</html>
