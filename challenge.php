<html>
    <head>
        <title>PHP Challenge 1</title>
    </head>
    <body>
        <h1>Prototype Form</h1>
        <p>How do you spend your day?</p>
        <br>
        <?php session_start(); 
        function resumeState($valueName = null)
        {
            if (!(isset($_SESSION[$valueName]))) {return;}
            $value = $_SESSION[$valueName];
            return $value;
        };
        $_SESSION["insert"] = null;
        ?>
        <form method="POST" action="results.php">
        <!--Esto esta en una lista para que sea mas organizado y se vea mejor-->
        <ul>
            <li>Please tell us your name:</li>
                <input type="text" name="firstName" value=<?php echo resumeState('firstName'); ?>>
            <li>Great, now, tell us your last name:</li>
                <input type="text" name="lastName" value=<?php echo resumeState('lastName'); ?>>
            <li>Ok. Now, how many years have you spent in this school?</li>
                <input type="number" name="yearsAtSchool" value=<?php echo resumeState('yearsAtSchool'); ?>>
            <li>Oh? And how old are you?</li>
                <input type="number" name="age" value=<?php echo resumeState('age'); ?>>
            <li>Wait, if you have that age, then how many siblings do you have?</li>
                <input type="number" name="numberOfSiblings" value=<?php echo resumeState('numberOfSiblings'); ?>>
            <li>Never would've guessed it. At what time do you go to bed? And when do you wake up?</li>
                <!--Dividir esta pregunta en dos hace que se vea mejor-->
                <p>Hour of sleep:</p> <input type="time" name="timeOfSleep" value=<?php echo resumeState('timeOfSleep'); ?>>
                <p>Hour of waking up:</p> <input type="time" name="timeOfWakeUp" value=<?php echo resumeState('timeOfWakeUp'); ?>>
            <li>I want you to be honest with me: How many hours do you spend on your homework?</li>
                <input type="number" name="hoursDoingHomework" value=<?php echo resumeState('hoursDoingHomework'); ?>>
            <li>I'll have to trust you. How many hours do you spend with your family per day?</li>
                <input type="number" name="timeSpentWithFamily" value=<?php echo resumeState('timeSpentWithFamily'); ?>>
            <li>And finally, how many hours do you spend with your friends per day?</li>
                <input type="number" name="timeSpentWithFriends" value=<?php echo resumeState('timeSpentWithFriends'); ?>>
        </ul>
            <input type="submit">
        </form>

        <style>body {text-align: center;}</style>
    </body>
</html>
