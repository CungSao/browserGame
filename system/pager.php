<?php

function getPage(){
    if (isset($_GET['page'])) {
        $page = str_replace("..\\", "", $_GET['page']);
        include("pages/" . $page . ".php");

    } elseif (isset($_GET['bPage'])) {
        if ($_GET['bPage'] === "accountOptions") {
            include("../backend/accountOptions.php");
        } elseif ($_GET['bPage'] === "stownFunctions") {
            include("../backend/stownFunctions.php");
        } elseif ($_GET['bPage'] === "armyFunctions") {
            include("../backend/armyFunctions.php");
        }
    }
    
    else {
        if(isset($_SESSION['loggedIn'])) {
            include("pages/loggedIn.php");
        } else {
            include("pages/welcome.php");
        }
    }
}

?>