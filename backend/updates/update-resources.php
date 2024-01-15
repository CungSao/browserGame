<?php
    include ("D:/laragon/www/BrowserGame/system/connection.php");
    global $db;
    $sql = "SELECT * FROM buildings";
    $stmt = $db->query($sql);
    $allBuildings = $stmt->fetchAll();

    $sql = "SELECT * FROM world";
    $stmt = $db->query($sql);
    $playerWorlds = $stmt->fetchAll();

    foreach ($playerWorlds as $world) {
        $woodIncome = 0;
        $stoneIncome = 0;
        $ironIncome = 0;
        $goldIncome = 0;

        $playerBuildings = explode(",", $world['buildings']);

        foreach ($playerBuildings as $building) {
            if ($building != 0) {
                foreach ($allBuildings as $buildingInfo) {
                    if ($buildingInfo['id'] == $building) {
                        $woodIncome += $buildingInfo['incomewood'];
                        $stoneIncome += $buildingInfo['incomestone'];
                        $ironIncome += $buildingInfo['incomewood'];
                        $goldIncome += $buildingInfo['incomewood'];
                        break;
                    }
                }
            }
        }
        echo $world['id'] . " will get this as income<br>";
        $sql = "UPDATE resources SET wood= wood+$woodIncome,
                                    stone= stone+$stoneIncome,
                                    iron= iron+$ironIncome,
                                    gold= gold+$goldIncome
                                    WHERE id= $world[id] \n";
        // update database
        echo $sql;
        $db->query($sql);
    }
?>