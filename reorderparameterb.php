<?php
include("DB/dbconn.php");
$team_id = $_SESSION["team_id"];
$goal_parameter = "SELECT * FROM `goal_parameter` WHERE team_id ='0' OR team_id ='$team_id' ORDER BY `goal_parameter`.`team_id` ASC ";
$parameter = mysqli_query($conn, $goal_parameter);
$i = 0;
while ($para = mysqli_fetch_object($parameter)) {
    $array[$i] = $para->parameter;
    $idarray[$i] = $para->parameter_id;
    $i++;
}

if (isset($_POST["before"])) {
    $sql1 = "UPDATE `goal_parameter` SET `parameter`='" . $array[$_POST["after"]] . "' WHERE `parameter_id` = '" . $idarray[$_POST["before"]] . "'";
    $sql2 = "UPDATE `goal_parameter` SET `parameter`='" . $array[$_POST["before"]] . "' WHERE `parameter_id` = '" . $idarray[$_POST["after"]] . "'";
    if (mysqli_query($conn, $sql2)) {
        if (mysqli_query($conn, $sql1)) {
            echo "ok";
        }
    }
}


?>