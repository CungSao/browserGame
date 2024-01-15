<?php

function getOtherPlayers(int $id) {
    global $db;
    $sql = "SELECT * FROM world WHERE id != ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);

    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        echo "<a href='?page=world&id=" . $row['id'] . "'>" . $row['name'] . "</a><br/>";
    }
}

function listWorld($id) {
    global $db;
    include(__ROOT__ . "/backend/armyFunctions.php");

    echo "<h3>Worlds army strength</h3>";
    listYourArmy($id);

    echo "<h3>" . "Worlds resources" . "</h3>";
    getWorldResources($id);

    echo "<a href='?page=attack&id=" . $id . "'><button>Attack</button></a>";
}

function getWorldResources($id) {
    global $db;

    $sql = "SELECT * FROM resources WHERE id=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetchAll();

    echo "Wood: " .$result[0]['wood'] ."<br>";
    echo "Stone: " .$result[0]['stone'] ."<br>";

    $iron = $result[0]['iron'];
    if($iron < 50) {
        $ironText = " (too low)";
    }
    elseif($iron >= 50 && $iron < 150){
        $ironText = "medium";
    }
    elseif($iron >= 150 && $iron < 500){
        $ironText = "high";
    }
    elseif($iron >= 500){
        $ironText = "ultra high";
    }
    echo "Iron: " . $ironText . "<br>";
    echo "Gold: " . $result[0]['gold'] . "<br>";
}

function attackWorld($id) {
    $opponentResult = getResourcesRow($id);
    $yourResult = getResourcesRow($_SESSION['loggedIn']);

    if ($yourResult[0]['armyStrength'] > $opponentResult[0]['armyStrength']) {
        echo "You win";
    } else {
        echo "You lose";
    }

}

function getResourcesRow($id) {
    global $db;
    $sql = "SELECT * FROM resources WHERE id=?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetchAll();
}

?>
