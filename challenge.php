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
        <form method="POST" action="results.php">
        <!--Esto esta en una lista para que sea mas organizado y se vea mejor-->
        <ul>
            <li>Please tell us your name:</li>
                <input type="text" name="firstName" value=<?php echo resumeState('firstName'); ?>>
            <br>
            <li>Great, now, tell us your last name:</li>
                <input type="text" name="lastName" value=<?php echo resumeState('lastName'); ?>>
            <br>
            <li>Ok. Now, in what school year are you? (In grade. Ex: 1st year = 7)</li>
                <input type="number" name="yearAtSchool" value=<?php echo resumeState('yearAtSchool'); ?>>
            <br>
            <li>Oh? And how old are you?</li>
                <input type="number" name="age" value=<?php echo resumeState('age'); ?>>
            <br>
            <li>Wait, if you have that age, then how many siblings do you have?</li>
                <input type="number" name="numberOfSiblings" value=<?php echo resumeState('numberOfSiblings'); ?>>
            <br>
            <li>Never would've guessed it. At what time do you go to bed? And when do you wake up?</li>
                <!--Dividir esta pregunta en dos hace que se vea mejor-->
                <p>Hour of sleep:</p> <input type="time" name="timeOfSleep" value=<?php echo resumeState('timeOfSleep'); ?>>
                <p>Hour of waking up:</p> <input type="time" name="timeOfWakeUp" value=<?php echo resumeState('timeOfWakeUp'); ?>>
            <br>
            <li>I want you to be honest with me: How many hours do you spend on your homework?</li>
                <input type="number" name="hoursDoingHomework" value=<?php echo resumeState('hoursDoingHomework'); ?>>
            <br>
            <li>Oh really? And how much time you spend watching TV or movies?</li>
                <input type="number" name="hoursWatchingTV" value=<?php echo resumeState('hoursWatchingTV'); ?>>
            <br>
            <li>Hmmm... How much time do you spend using a computer or gaming console?</li>
                <input type="number" name="hoursUsingComputer" value=<?php echo resumeState('hoursUsingComputer'); ?>>
            <br>
            <li>I'll have to trust you. How many hours do you spend with your family per day?</li>
                <input type="number" name="timeSpentWithFamily" value=<?php echo resumeState('timeSpentWithFamily'); ?>>
            <br>
            <li>And finally, how many hours do you spend with your friends per day?</li>
                <input type="number" name="timeSpentWithFriends" value=<?php echo resumeState('timeSpentWithFriends'); ?>>
        </ul>
            <input type="submit">
        </form>
        <form method="POST">
            <input type="submit" name="insert" value="Show students">
        </form>
     
        <style>body {text-align: center;}</style>
    </body>
</html>
