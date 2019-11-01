<html>
    <head>
        <title>PHP Challenge 1</title>
    </head>
    <body>
        <h1>Prototype Form</h1>
        <p>How do you spend your day?</p>
        <br>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
        <!--Esto esta en una lista para que sea mas organizado y se vea mejor-->
        <ul>
            <li>Please tell us your name:</li>
                <input type="text" name="firstName">
            <li>Great, now, tell us your last name:</li>
                <input type="text" name="lastName">
            <li>Ok. Now, how many years have you spent in this school?</li>
                <input type="number" name="yearsAtSchool">
            <li>Oh? And how old are you?</li>
                <input type="number" name="age">
            <li>Wait, if you have that age, then how many siblings do you have?</li>
                <input type="number" name="numberOfSiblings">
            <li>Never would've guessed it. At what time do you go to bed? And when do you wake up?</li>
                <!--Dividir esta pregunta en dos hace que se vea mejor-->
                <p>Hour of sleep:</p> <input type="time" name="timeOfSleep">
                <p>Hour of waking up:</p> <input type="time" name="timeOfWakeUp">
            <li>I want you to be honest with me: How many hours do you spend on your homework?</li>
                <input type="number" name="hoursDoingHomework">
            <li>I'll have to trust you. How many hours do you spend with your family per day?</li>
                <input type="number" name="timeSpentWithFamily">
            <li>And finally, how many hours do you spend with your friends per day?</li>
                <input type="number" name="timeSpentWithFriends">
        </ul>
            <input type="submit">
        </form>

        <?php 
        echo "<br><br><br>";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "Great! Thanks " . $_POST["firstName"] . " for responding to our survey<br>";
            echo "These are your details:<br>";
            echo "<ul>";
            foreach ($_POST as $property => $value) {
                echo "<li>$property : $value</li>";
            }
            echo "</ul>";
        
            $database = fopen("students.json", "w");
            fwrite($database, json_encode($_POST));
            fclose($database);
        }
        ?>
        <style>body {text-align: center;}</style>
    </body>
</html>