<?php

function listYourArmy($id) {
    global $db;
    
    $sql = "SELECT * FROM armyunits";
    $stmt = $db->query($sql);
    $armyUnits = $stmt->fetchAll();
    
    $sql = "SELECT army,armyStrength FROM resources WHERE id='$id'";
    $stmt = $db->query($sql);
    $result = $stmt->fetch();

    if (isset($result['army'])) {
        $armyArray = explode(",", $result['army']);

        foreach($armyArray as $unit){
            [$id, $amount] = explode(":", $unit);
    
            foreach($armyUnits as $armyUnit){
                if($id == $armyUnit['id']) {
                    // FOUND
                    $unitName = $armyUnit['name'];
                    break;
                }
            }
            echo "{$amount} {$unitName} <br>";
        }
    }
}

function listUnitsForPruchase() {
    global $db;
    $sql = "SELECT * FROM armyunits";
    $stmt = $db->query($sql);
    echo "<form action=?bPage=armyFunctions&recruit method=POST>";
    
    while ($results = $stmt->fetch()) {
        echo "<input name='".$results['id']."' type=number value=0 min=0> {$results['name']}
        - (" .$results['armyStrength'] ."str) 
        - {$results['cost']} gold <br>";
    };
    echo "<input type=submit>";
    echo "</form>";
}

function buyUnits() {
    global $db;

    // var_dump($_POST);
    $sql = "SELECT * FROM armyunits";
    $stmt = $db->query($sql);
    $armyUnits = $stmt->fetchAll();
    $niceArmyUnits = [];

    foreach ($armyUnits as $data) {
        $niceArmyUnits[$data['id']] = $data;
    }

    $userId = $_SESSION['loggedIn'];
    $sql = "SELECT * FROM resources WHERE id='$userId'";
    $stmt = $db->query($sql);
    $userResoures = $stmt->fetch();
    $myArmyArray = [];
    $armyArray = explode(",",$userResoures['army']);
    foreach($armyArray as $unit) {
        $ex = explode(":", $unit);
        $myArmyArray[$ex[0]] = $ex[1];
    }

    $totalCost = 0;
    $addedArmyStrength = 0;

    foreach ($_POST as $id => $amount) {
        if($amount > 0) {
            if (isset($niceArmyUnits[$id])) {
                $totalCost += $niceArmyUnits[$id]['cost'] * $amount;
                $addedArmyStrength += $niceArmyUnits[$id]['armyStrength'] * $amount;
                if (isset($myArmyArray[$id])) {
                    $myArmyArray[$id] += $amount;
                } else {
                    $myArmyArray[$id] = $amount;
                }
            } else {
                exit;
            }
        }
    }

    if ($userResoures['gold'] >= $totalCost) {
        echo "you can afford your units";

        $insertNewArmy = '';
        foreach ($myArmyArray as $id => $amount) {
            $insertNewArmy .= $id . ":" . $amount . ",";
        }

        if (substr($insertNewArmy, -1) == ",") {
            $insertNewArmy = substr($insertNewArmy, 0, -1);
        }

        echo $insertNewArmy;

        $sql = "UPDATE resources SET gold=gold-'$totalCost' WHERE id='$userId'";
        $db->query($sql);
        $sql = "UPDATE resources SET army='$insertNewArmy', armyStrength=armyStrength+'$addedArmyStrength' WHERE id='$userId'";
        $db->query($sql);
    }
    echo "<br>Total cost: " . $totalCost;
}

if (isset($_GET['recruit'])) {
    buyUnits();
}

?>