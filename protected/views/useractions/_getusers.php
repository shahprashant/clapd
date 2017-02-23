<?php
    if (isset($usersArray) && (count($usersArray) > 0)) {
        echo "<b>People who like this Clap:</b><br>";
        foreach ($usersArray as $useractionInfo) {
            echo $useractionInfo['name'];
            echo '<br>';
        }
    }
?>
