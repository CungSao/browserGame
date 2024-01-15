<?php
    function getResources() {
        global $db;
        $id = $_SESSION['loggedIn'];
        // $sql = "SELECT * FROM resources INNER JOIN world ON world.id = resources.id WHERE resources.id=$id";
        $sql = "SELECT * FROM resources WHERE id=$id";
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll();

    ?>
    <fieldset>
        <legend style="text-align: center; border: 1px solid black">Resources</legend>
        <span class="quarterWidth">
            Wood<br>
            <?php echo $result[0]['wood'];?>
        </span>
        <span class="quarterWidth">
            Stone<br>
            <?php echo $result[0]['stone'];?>
        </span>
        <span class="quarterWidth">
            Iron<br>
            <?php echo $result[0]['iron'];?>
        </span>
        <span class="quarterWidth">
            Gold<br>
            <?php echo $result[0]['gold'];?>
        </span>
    </fieldset>
<br><br>

<?php
    }
    function getTown() {
        global $db;

        if (isset($_SESSION['townErrorMessage'])) {
            echo $_SESSION['townErrorMessage'];
            unset($_SESSION['townErrorMessage']);
        }

        // get our town layout/buildings
        $id = $_SESSION['loggedIn'];
        $sql = "SELECT * FROM world WHERE id=$id";
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll();

        $ourBuildings = explode(",", $result[0]['buildings']);

        // get all available buildings
        $sql = "SELECT * FROM buildings";
        $stmt = $db->query($sql);
        $allBuildings = $stmt->fetchAll(PDO::FETCH_GROUP);

        // draw the map
        $i = 0;
        echo "<div id='boxHolder'>";
        for ($y = 0; $y < 3; $y++) {
            for ($x=0; $x < 3; $x++) { 
                if ($ourBuildings[$i] === "0") {
                    $text = "<img class='gridPicture' src='design/img/buildings/grass.png'>";
                } else {
                    if (isset($allBuildings[$ourBuildings[$i]][0]['image'])) {
                        $text = "<img class='gridPicture' src='design/img/buildings/" . $allBuildings[$ourBuildings[$i]][0]['image'] . "'>";
                    } else {
                        $text = $allBuildings[$ourBuildings[$i]][0]['name'];
                    }
                }
                echo "<span id='" . $i . "' class='gridBox'>" . $text . "</span>";
                $i++;
            }
        }
        echo "</div>";

        echo "<div id='buildingOptions'>";
            echo "<span id='buildingLocation'></span>";

            // for ($i=1; $i < count($allBuildings) + 1; $i++) { 
            //     echo "<div class='buildingBox'>" . $allBuildings[$i][0]['name'] . "</div>";
            // }

            foreach($allBuildings as $key => $building) {
                echo "<div id='" . $key . "' class='buildingBox'>" . $building[0]['name'] . "<br>" .
                " W " . $building[0]['costwood'] .
                " S " . $building[0]['coststone'] . "<br>" .
                " I " . $building[0]['costiron'] .
                " G " . $building[0]['costgold'] .
                "</div>";
            }

        echo "</div>";
?>

<script>
    $(".gridBox").click(function(event){
        var id = $(this).attr('id');
        $("#buildingLocation").text(id);
        $("#buildingOptions").css('left', event.pageX);
        $("#buildingOptions").css('top', event.pageY);
        $("#buildingOptions").toggle();
    });
    
    $(".buildingBox").click(function(){
        var buildId = $(this).attr('id');
        var locId = $("#buildingLocation").text();
        // alert("You wanna buy id " + buildId);
        $.post("?bPage=stownFunctions", {
            location: locId,
            buildingId: buildId
        }).done(function(){
            $("#townArea").load("index.php?bPage=stownFunctions&getTown&nonUI");
        })
    });

</script>

<?php
}

    function createBuilding($location, $buildingId) {
        global $db;

        $sql = "SELECT * FROM buildings WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([($buildingId)]);
        // if the building exists
        if($stmt->rowCount() > 0) {
            // the row with building infomation
            $buildingResult = $stmt->fetchAll();

            $id = $_SESSION['loggedIn'];
            $sql = "SELECT * FROM resources INNER JOIN world ON world.id = resources.id WHERE resources.id='$id'";
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll();
            
            // our current currency
            $currencyArray = ["costwood" => $result[0]['wood'], "coststone" => $result[0]['stone'], "costiron" => $result[0]['iron'], "costgold" => $result[0]['gold']];
            $newCurrency = [];
            $couldNotAfford = 0;

            foreach ($currencyArray as $currency => $amount) {
                echo "COST : " . $buildingResult[0][$currency] . " my " . $currency . " " . $amount . "<br>";
                if ($buildingResult[0][$currency] > $amount) {
                    $couldNotAfford = 1;
                    break;
                } else {
                    $newCurrency += [$currency => $amount - $buildingResult[0][$currency]];
                };
            }
            // var_dump($newCurrency);
            if ($couldNotAfford === 0) {
                // you can afford it!

                $ourTownArray = explode(",", $result[0]['buildings']);

                if ($ourTownArray[$location] === "0") {
                    // THE SPOT IS EMPTY AND YOU CAN BUILD
                    $ourTownArray[$location] = $buildingId;
                    $ourTownArray = implode(",", $ourTownArray);

                    $sql = "UPDATE world SET buildings='$ourTownArray' WHERE id='$id'";
                    $db->query($sql);

                    $sql = "UPDATE resources SET wood=$newCurrency[costwood], stone=$newCurrency[coststone], iron=$newCurrency[costiron], gold=$newCurrency[costgold] WHERE id='$id'";
                    $db->query($sql);

                } else {
                    // echo
                    $_SESSION['townErrorMessage'] = "Another building is already in that location";
                }

            } else {
                $_SESSION['townErrorMessage'] = "You cannot afford this building";
            }
        } else {
            $_SESSION['townErrorMessage'] = "The building your trying to build doesn't extist";
        }
    }    

    if (isset($_GET['getTown'])) {
        getTown();
    }
    if (isset($_POST['location'])) {
        createBuilding($_POST['location'], $_POST['buildingId']);
    }

    ### MY: not use
    function setBackground() {
        $background = ($i % 2 == 0) ? "#626172" : "#123512";
        echo "<span class='gridBox' style='background:" . $background . "'></span>";
    }

?>
